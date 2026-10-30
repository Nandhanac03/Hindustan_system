<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanyBankAccount;
use App\Models\Receipt;
use App\Models\ReceiptRealizationLog;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\Account;
use App\Models\Payee;
use App\Models\JournalVoucher;
use App\Models\JournalEntry;
use App\Models\ChartOfAccount;
use App\Models\VoucherType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ChequeRealizationService
 *
 * Handles the full Cheque Realization & Treasury Allocation workflow:
 *   - Step 5.3: realize()         — marks instrument realized, credits bank balance
 *   - Step 5.3: markBounced()     — marks instrument bounced, no balance effect
 *   - Step 5.5 + 5.6: recordPayment() — debits treasury, creates Payment Voucher
 */
class ChequeRealizationService
{
    // ─────────────────────────────────────────────────────────────────────────
    // Step 5.3 — Mark Cheque as Realized
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Realize a receipt instrument: update status and credit bank account balance.
     *
     * @param  Receipt $receipt
     * @param  array   $data  { realized_by: int, remarks?: string }
     * @return Receipt         The refreshed receipt after realization.
     * @throws \Exception      If already in a terminal state.
     */
    public function realize(Receipt $receipt, array $data): Receipt
    {
        if ($receipt->isTerminal()) {
            throw new \Exception(
                "Receipt #{$receipt->id} cannot be realized — it is already '{$receipt->realization_status}'."
            );
        }

        $bankAccountId = $data['company_bank_account_id'] ?? $receipt->company_bank_account_id;

        if (!$bankAccountId) {
            throw new \Exception(
                "Receipt #{$receipt->id} has no Company Bank Account assigned. Please select a Company Bank Account."
            );
        }

        return DB::transaction(function () use ($receipt, $data, $bankAccountId) {
            $oldStatus  = $receipt->realization_status;
            $userId     = $data['realized_by'];
            $remarks    = $data['remarks'] ?? 'Cheque cleared by bank.';
            if (!empty($data['bank_reference_no'])) {
                $remarks = ($remarks ? $remarks . ' ' : '') . '(Bank Ref: ' . $data['bank_reference_no'] . ')';
            }
            $dateInput  = $data['realized_at'] ?? ($data['realization_date'] ?? null);
            $realizedAt = !empty($dateInput) ? \Carbon\Carbon::parse($dateInput) : now();

            // 1. Update receipt status and bank account
            $receipt->update([
                'company_bank_account_id' => $bankAccountId,
                'realization_status'      => 'realized',
                'realized_at'             => $realizedAt,
                'realized_by'             => $userId,
            ]);

            // 2. Credit company bank account balance (Step 5.4)
            $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail($bankAccountId);
            $bankAccount->increment('current_balance', $receipt->amount);

            // Also update ReceiptStore record
            \App\Models\ReceiptStore::updateOrCreate(
                ['receipt_id' => $receipt->id],
                [
                    'company_bank_account_id' => $bankAccountId,
                    'customer_id'             => $receipt->customer_id,
                    'project_id'              => $receipt->project_id,
                    'receipt_date'            => $receipt->receipt_date,
                    'amount'                  => $receipt->amount,
                    'payment_mode'            => $receipt->payment_mode ?: 'Cheque',
                    'reference_no'            => $receipt->reference_no,
                    'remarks'                 => $remarks,
                    'status'                  => 'realized',
                    'created_by'              => $userId,
                ]
            );

            // 3. Write audit log
            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'realized',
                'remarks'    => $remarks,
                'changed_by' => $userId,
            ]);

            $sale = $this->resolveSaleForReceipt($receipt);
            if ($sale) {
                \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            }

            // 4. Create Double Entry Accounting Postings (ONLY when instrument is Realized)
            try {
                $allBankNames = CompanyBankAccount::pluck('bank_name')->filter()->unique()->implode(' / ');
                $bankAccountName = 'Bank Balances (' . ($allBankNames ?: 'Karnataka Bank / HDFC Escrow') . ')';

                $requiredAccounts = [
                    '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET'],
                    '1010' => ['name' => 'Customer Receivable', 'type' => 'ASSET'],
                ];
                foreach ($requiredAccounts as $accCode => $accInfo) {
                    $coa = ChartOfAccount::firstOrCreate(
                        ['account_code' => $accCode],
                        [
                            'account_name' => $accInfo['name'],
                            'account_type' => $accInfo['type'],
                            'is_active'    => true,
                        ]
                    );

                    if ($accCode === '1001' && $coa->account_name !== $accInfo['name']) {
                        $coa->update(['account_name' => $accInfo['name']]);
                    }
                }

                $voucherType = VoucherType::firstOrCreate(
                    ['code' => 'CUSTOMER_RECEIPT'],
                    [
                        'name'        => 'Customer Payment Receipt',
                        'prefix'      => 'JV-CR',
                        'description' => 'Generated on cheque realization / payment receipt',
                        'is_active'   => true,
                    ]
                );

                $custName = $receipt->customer ? $receipt->customer->name : 'Customer';
                $refNo = $receipt->reference_no ? $receipt->reference_no : $receipt->id;

                $journalVoucher = JournalVoucher::create([
                    'voucher_no'      => 'JV-CR-' . date('Y') . '-' . str_pad((string)$receipt->id, 4, '0', STR_PAD_LEFT),
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date'    => $realizedAt ? \Carbon\Carbon::parse($realizedAt)->toDateString() : date('Y-m-d'),
                    'reference_id'    => $receipt->id,
                    'narration'       => 'Cheque #' . $refNo . ' cleared - ' . $custName,
                    'is_active'       => true,
                ]);

                // 1. Bank Account (Debit 1001: Asset Increases)
                JournalEntry::create([
                    'voucher_id'     => $journalVoucher->id,
                    'account_id'     => '1001',
                    'entity_type'    => 'BANK',
                    'entity_id'      => $bankAccountId,
                    'debit_amount'   => $receipt->amount,
                    'credit_amount'  => 0.00,
                    'line_narration' => ($receipt->companyBankAccount ? $receipt->companyBankAccount->bank_name : 'Karnataka Bank') . ' (Asset Increases)',
                ]);

                // 2. Customer Receivables (Credit 1010: Debt Decreases)
                JournalEntry::create([
                    'voucher_id'     => $journalVoucher->id,
                    'account_id'     => '1010',
                    'entity_type'    => 'CUSTOMER',
                    'entity_id'      => $receipt->customer_id,
                    'debit_amount'   => 0.00,
                    'credit_amount'  => $receipt->amount,
                    'line_narration' => 'Customer Receivables (' . $custName . ' Debt Decreases)',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Journal Voucher Creation Error on Receipt Realization #' . $receipt->id . ': ' . $e->getMessage());
            }

            return $receipt->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 5.3 — Mark Cheque as Bounced
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Mark a receipt instrument as bounced (dishonoured). No balance change.
     *
     * @param  Receipt $receipt
     * @param  array   $data  { changed_by: int, remarks?: string }
     * @return Receipt
     * @throws \Exception
     */
    public function markBounced(Receipt $receipt, array $data): Receipt
    {
        if ($receipt->isTerminal()) {
            throw new \Exception(
                "Receipt #{$receipt->id} cannot be marked bounced — it is already '{$receipt->realization_status}'."
            );
        }

        return DB::transaction(function () use ($receipt, $data) {
            $oldStatus   = $receipt->realization_status;
            $bounceDate  = !empty($data['realization_date']) ? \Carbon\Carbon::parse($data['realization_date']) : now();
            $reasonText  = trim((string)($data['remarks'] ?? 'Cheque dishonoured by bank.'));
            $penaltyAmt  = isset($data['penalty_amount']) ? max(0.0, round((float)$data['penalty_amount'], 2)) : 0.0;

            $formattedRemarks = '[Bounce Date: ' . $bounceDate->format('d M Y') . '] ' . ($reasonText ?: 'Cheque dishonoured by bank.');
            if ($penaltyAmt > 0) {
                $formattedRemarks .= ' (Bounce Penalty: ₹' . number_format($penaltyAmt, 2) . ')';
            }

            $receipt->update([
                'realization_status' => 'bounced',
                'realized_at'        => $bounceDate,
                'realized_by'        => $data['changed_by'] ?? auth()->id(),
                'remarks'            => $formattedRemarks,
            ]);

            \App\Models\ReceiptStore::where('receipt_id', $receipt->id)->update([
                'status'  => 'bounced',
                'remarks' => $formattedRemarks,
            ]);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'bounced',
                'remarks'    => $formattedRemarks,
                'changed_by' => $data['changed_by'] ?? auth()->id(),
            ]);

            // Re-open the existing EMI installment due and restore amount to Customer Receivable
            $this->reopenInstallmentAndApplyPenalty($receipt, $penaltyAmt);

            return $receipt->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Re-Initialize Bounced Cheque (Bounced → Cheque in Hand)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Re-initialize a bounced cheque instrument back into 'cheque_in_hand' status for re-presentation.
     *
     * @param  Receipt $receipt
     * @param  array   $data  { changed_by: int, remarks?: string }
     * @return Receipt
     * @throws \Exception
     */
    public function reinitialize(Receipt $receipt, array $data): Receipt
    {
        if (!in_array($receipt->realization_status, ['bounced', 'cancelled'], true)) {
            throw new \Exception(
                "Receipt #{$receipt->id} is not in bounced/cancelled status (current: '{$receipt->realization_status}')."
            );
        }

        return DB::transaction(function () use ($receipt, $data) {
            $oldStatus = $receipt->realization_status;

            $receipt->update(['realization_status' => 'cheque_in_hand']);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'cheque_in_hand',
                'remarks'    => $data['remarks'] ?? 'Cheque re-initialized for re-presentation to bank.',
                'changed_by' => $data['changed_by'],
            ]);

            $sale = $this->resolveSaleForReceipt($receipt);
            if ($sale) {
                \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            }

            return $receipt->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Replace Bounced / Cancelled Cheque & Update Existing EMI Record
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Replace a bounced or cancelled cheque/payment by updating the same existing
     * EMI installment record (new cheque number, new due date, and optional bank
     * penalty / bounce charges) without creating a new EMI row.
     */
    public function replaceInstrument(Receipt $receipt, array $data): Receipt
    {
        if (!in_array($receipt->realization_status, ['bounced', 'cancelled'], true)) {
            throw new \Exception(
                "Only bounced or cancelled instruments can be replaced (current: '{$receipt->realization_status}')."
            );
        }

        return DB::transaction(function () use ($receipt, $data) {
            $oldStatus  = $receipt->realization_status;
            $oldRef     = $receipt->reference_no ?: ('REC-' . $receipt->id);
            $newRef     = trim((string)($data['reference_no'] ?? $oldRef));
            $newDueDate = !empty($data['new_due_date'])
                ? \Carbon\Carbon::parse($data['new_due_date'])->toDateString()
                : ($receipt->cheque_date?->toDateString() ?? now()->toDateString());
            $receiptDate = !empty($data['receipt_date'])
                ? \Carbon\Carbon::parse($data['receipt_date'])->toDateString()
                : now()->toDateString();
            $paymentMode = $data['payment_mode'] ?? ($receipt->payment_mode ?: 'Cheque');
            $penaltyAmt  = isset($data['penalty_amount']) ? max(0.0, round((float)$data['penalty_amount'], 2)) : 0.0;
            $userId      = $data['changed_by'] ?? auth()->id();

            // 1. Re-open existing EMI installment due and apply new due date + penalty to the SAME EMI record
            $targetInst = $this->reopenInstallmentAndApplyPenalty(
                $receipt,
                $penaltyAmt,
                $newDueDate,
                isset($data['installment_id']) ? (int)$data['installment_id'] : null
            );

            $baseAmount = (float)$receipt->amount;
            $updatedAmount = isset($data['amount']) && (float)$data['amount'] > 0
                ? round((float)$data['amount'], 2)
                : round($baseAmount + $penaltyAmt, 2);

            $dueDateFormatted = \Carbon\Carbon::parse($newDueDate)->format('d M Y');
            $emiLabel = $targetInst ? $targetInst->label : 'Installment';
            $replacementNote = "Replaced " . strtoupper((string)$oldStatus) . " #{$oldRef} with #{$newRef} for {$emiLabel} (New Due: {$dueDateFormatted}"
                . ($penaltyAmt > 0 ? ", Bounce/Penalty Charge: ₹" . number_format($penaltyAmt, 2) : "")
                . ")" . (!empty($data['remarks']) ? " — " . trim((string)$data['remarks']) : "");

            $updateFields = [
                'reference_no'       => $newRef,
                'cheque_date'        => $newDueDate,
                'receipt_date'       => $receiptDate,
                'payment_mode'       => $paymentMode,
                'amount'             => $updatedAmount,
                'realization_status' => 'pending',
                'realized_at'        => null,
                'realized_by'        => null,
                'remarks'            => $replacementNote,
            ];

            if (!empty($data['company_bank_account_id'])) {
                $updateFields['company_bank_account_id'] = $data['company_bank_account_id'];
            }
            if (!empty($data['drawee_bank'])) {
                $updateFields['drawee_bank'] = $data['drawee_bank'];
            }
            if (!empty($data['bank_id'])) {
                $updateFields['bank_id'] = $data['bank_id'];
            }

            $receipt->update($updateFields);

            \App\Models\ReceiptStore::where('receipt_id', $receipt->id)->update([
                'reference_no' => $newRef,
                'receipt_date' => $receiptDate,
                'payment_mode' => $paymentMode,
                'amount'       => $updatedAmount,
                'status'       => 'pending',
                'remarks'      => $replacementNote,
            ]);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'pending',
                'remarks'    => $replacementNote,
                'changed_by' => $userId,
            ]);

            // 2. Re-allocate payment status on the sale so the existing EMI reflects the replacement instrument
            $sale = $this->resolveSaleForReceipt($receipt);
            if ($sale) {
                \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            }

            return $receipt->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Advance Status (Pending → Cheque in Hand → Deposited / Cancelled)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Move a receipt to the next intermediate status.
     *
     * @param  Receipt $receipt
     * @param  string  $newStatus  One of: cheque_in_hand | deposited | cancelled
     * @param  array   $data       { changed_by: int, remarks?: string }
     * @return Receipt
     */
    public function advanceStatus(Receipt $receipt, string $newStatus, array $data): Receipt
    {
        $allowed = ['pending', 'cheque_in_hand', 'deposited', 'in_clearing', 'cancelled'];

        if (!in_array($newStatus, $allowed, true)) {
            throw new \InvalidArgumentException(
                "Invalid status '{$newStatus}'. Use realize() or markBounced() for terminal transitions."
            );
        }

        if ($receipt->isTerminal()) {
            throw new \Exception(
                "Receipt #{$receipt->id} is already in terminal state '{$receipt->realization_status}'."
            );
        }

        return DB::transaction(function () use ($receipt, $newStatus, $data) {
            $oldStatus  = $receipt->realization_status;
            $penaltyAmt = isset($data['penalty_amount']) ? max(0.0, round((float)$data['penalty_amount'], 2)) : 0.0;

            $updateData = ['realization_status' => $newStatus];
            if (!empty($data['company_bank_account_id'])) {
                $updateData['company_bank_account_id'] = $data['company_bank_account_id'];
            }

            $remarks = trim((string)($data['remarks'] ?? ''));
            if ($newStatus === 'cancelled') {
                $cancelDate = !empty($data['realization_date']) ? \Carbon\Carbon::parse($data['realization_date']) : now();
                $remarks = '[Cancelled Date: ' . $cancelDate->format('d M Y') . '] ' . ($remarks ?: 'Instrument cancelled.');
                if ($penaltyAmt > 0) {
                    $remarks .= ' (Penalty Charge: ₹' . number_format($penaltyAmt, 2) . ')';
                }
                $updateData['realized_at'] = $cancelDate;
                $updateData['realized_by'] = $data['changed_by'] ?? auth()->id();
                $updateData['remarks']     = $remarks;
            } else {
                if (!empty($data['bank_reference_no'])) {
                    $remarks = ($remarks ? $remarks . ' ' : '') . '(Bank Ref: ' . $data['bank_reference_no'] . ')';
                }
            }

            $receipt->update($updateData);

            \App\Models\ReceiptStore::where('receipt_id', $receipt->id)->update([
                'status'  => $newStatus,
                'remarks' => $remarks ?: $receipt->remarks,
            ]);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remarks'    => $remarks ?: null,
                'changed_by' => $data['changed_by'] ?? auth()->id(),
            ]);

            if ($newStatus === 'cancelled') {
                $this->reopenInstallmentAndApplyPenalty($receipt, $penaltyAmt);
            } else {
                $sale = $this->resolveSaleForReceipt($receipt);
                if ($sale) {
                    \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
                }
            }

            return $receipt->fresh();
        });
    }

    /**
     * Resolve the associated Sale for a receipt (by sale_id or customer_id + unit_id/project_id).
     */
    public function resolveSaleForReceipt(Receipt $receipt): ?\App\Models\Sale
    {
        if ($receipt->sale_id) {
            return \App\Models\Sale::find($receipt->sale_id);
        }

        if ($receipt->customer_id) {
            $query = \App\Models\Sale::where('customer_id', $receipt->customer_id)
                ->where('status', 'active');
            if ($receipt->unit_id) {
                $query->where('unit_id', $receipt->unit_id);
            } elseif ($receipt->project_id) {
                $query->where('project_id', $receipt->project_id);
            }
            $sale = $query->latest('id')->first();
            if (!$sale) {
                $sale = \App\Models\Sale::where('customer_id', $receipt->customer_id)
                    ->where('status', 'active')
                    ->latest('id')
                    ->first();
            }
            if ($sale) {
                $receipt->update(['sale_id' => $sale->id]);
                return $sale;
            }
        }

        return null;
    }

    /**
     * Re-open the existing EMI installment due when a cheque bounces or is cancelled
     * (without creating a new EMI), and optionally update its due_date and apply
     * any bank penalty / bounce charges to the same EMI & Customer Receivable.
     */
    public function reopenInstallmentAndApplyPenalty(
        Receipt $receipt,
        float $penaltyAmount = 0.0,
        ?string $newDueDate = null,
        ?int $targetInstallmentId = null
    ): ?\App\Models\CustomerInstallment {
        $sale = $this->resolveSaleForReceipt($receipt);
        if (!$sale) {
            return null;
        }

        // 1. Re-allocate payment status excluding bounced/cancelled receipts so the existing EMI reopens
        \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);

        // 2. Find the reopened (first unpaid) existing EMI installment (or specific installment if passed)
        $targetInst = null;
        if ($targetInstallmentId) {
            $targetInst = \App\Models\CustomerInstallment::where('sale_id', $sale->id)
                ->where('id', $targetInstallmentId)
                ->first();
        }
        if (!$targetInst) {
            $targetInst = \App\Models\CustomerInstallment::where('sale_id', $sale->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->orderBy('installment_no')
                ->orderBy('id')
                ->first();
        }

        // 3. Apply new due date and/or bounce penalty charge to the SAME existing EMI record
        if ($targetInst && ($penaltyAmount > 0 || !empty($newDueDate))) {
            $instUpdates = [];
            if (!empty($newDueDate)) {
                $instUpdates['due_date'] = $newDueDate;
            }
            if ($penaltyAmount > 0) {
                $instUpdates['amount'] = round((float)$targetInst->amount + $penaltyAmount, 2);
                $sale->refresh();
                $sale->update([
                    'total_amount' => round((float)$sale->total_amount + $penaltyAmount, 2),
                ]);
            }
            if (!empty($instUpdates)) {
                $targetInst->update($instUpdates);
            }

            // Re-sync installment status and Sale::remaining_balance
            \App\Models\CustomerInstallment::allocatePaymentStatusForSale($sale->id);
            $targetInst->refresh();
        }

        return $targetInst;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 5.5 + 5.6 — Record Outward Treasury Payment & Generate Voucher
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Disburse funds from a verified company bank account and create a Payment Voucher.
     *
     * @param array $data {
     *   company_bank_account_id: int,
     *   payee_id: int|null,
     *   payee_name: string|null,
     *   amount: float,
     *   payment_date: string,
     *   payment_mode: string,
     *   reference_no: string|null,
     *   purpose: string,   — supplier_payment | customer_refund | site_expense | other
     *   narration: string|null,
     *   created_by: int,
     *   system_id: int,
     * }
     *
     * @return Voucher  The generated Payment Voucher.
     * @throws ValidationException  If insufficient balance.
     */
    public function recordPayment(array $data): Voucher
    {
        return DB::transaction(function () use ($data) {
            $bankAccount = CompanyBankAccount::lockForUpdate()
                ->findOrFail($data['company_bank_account_id']);

            // Step 5.5: Validate sufficient balance (skip for historical entries)
            $isHistorical = !empty($data['is_historical']);
            if (!$isHistorical && (float) $bankAccount->current_balance < (float) $data['amount']) {
                throw ValidationException::withMessages([
                    'amount' => [
                        "Insufficient balance. Available: ₹" .
                        number_format((float) $bankAccount->current_balance, 2) .
                        ", Requested: ₹" . number_format((float) $data['amount'], 2),
                    ],
                ]);
            }

            $systemId  = $data['system_id'] ?? 1;
            $createdBy = $data['created_by'];

            // Generate sequential voucher number safely
            $lastVoucher = Voucher::where('system_id', $systemId)
                ->where('type', 'Payment')
                ->where('voucher_number', 'LIKE', 'PV-%')
                ->lockForUpdate()
                ->orderByDesc('id')
                
                ->first();

            $nextSeq = 1;
            if ($lastVoucher && preg_match('/^PV-(\d+)$/', $lastVoucher->voucher_number, $matches)) {
                $nextSeq = ((int) $matches[1]) + 1;
            }

            // Ensure no duplicate collision
            do {
                $voucherNumber = 'PV-' . str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);
                $exists = Voucher::where('system_id', $systemId)
                    ->where('voucher_number', $voucherNumber)
                    ->exists();
                if ($exists) {
                    $nextSeq++;
                }
            } while ($exists);

            $narration = $data['narration'] ?? match($data['purpose'] ?? 'other') {
                'contractor_payment', 'supplier_payment' => 'Contractor Payment via ' . $bankAccount->bank_name,
                'customer_refund'                        => 'Customer Refund via ' . $bankAccount->bank_name,
                'site_expense'                           => 'Site Expense via ' . $bankAccount->bank_name,
                default                                  => 'Treasury Disbursement via ' . $bankAccount->bank_name,
            };

            // Step 5.6: Create the Payment Voucher
            if ($isHistorical) {
                $narration .= ' [Historical]';
            }
            $voucher = Voucher::create([
                'system_id'      => $systemId,
                'voucher_number' => $voucherNumber,
                'type'           => 'Payment',
                'date'           => $data['payment_date'],
                'narration'      => $narration,
                'reference_no'   => $data['reference_no'] ?? null,
                'created_by'     => $createdBy,
                'status'         => 'Posted',
            ]);

            // Resolve debit account (Expense / Payable based on purpose and payee linked account)
            $debitAccount  = $this->resolveDebitAccount($systemId, $data['purpose'] ?? 'other', $data['payee_id'] ?? null);
            // Credit account = Company Bank Account (Asset)
            $creditAccount = $this->resolveBankAssetAccount($systemId, $bankAccount);

            if ($debitAccount) {
                VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $debitAccount->id,
                    'debit'      => $data['amount'],
                    'credit'     => 0,
                    'narration'  => $narration,
                ]);
            }

            if ($creditAccount) {
                VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $creditAccount->id,
                    'debit'      => 0,
                    'credit'     => $data['amount'],
                    'narration'  => $narration,
                ]);
            }

            // Deduct from treasury balance (Step 5.5) — skip for historical entries
            if (!$isHistorical) {
                $bankAccount->decrement('current_balance', $data['amount']);
            }

            return $voucher;
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Find the appropriate debit (expense/payable) account based on payment purpose and payee.
     */
    private function resolveDebitAccount(int $systemId, string $purpose, ?int $payeeId = null): ?Account
    {
        // 1. If payee is specified, prioritize the payee's linked account
        if ($payeeId) {
            $payee = Payee::find($payeeId);
            if ($payee && $payee->linked_account_id) {
                $linkedAcc = Account::where('system_id', $systemId)->find($payee->linked_account_id);
                if ($linkedAcc) {
                    return $linkedAcc;
                }
            }
        }

        $searchTerms = match ($purpose) {
            'contractor_payment', 'supplier_payment' => ['Contractor', 'Payable'],
            'customer_refund'                        => ['Customer', 'Refund', 'Receivable'],
            'site_expense'                           => ['Site', 'Expense', 'Construction'],
            default                                  => ['Expense'],
        };

        return Account::where('system_id', $systemId)
            ->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'LIKE', "%{$term}%");
                }
            })
            ->where('name', 'NOT LIKE', '%Broker%') // Never use broker commission accounts for contractor payments
            ->first();
    }

    /**
     * Find (or gracefully skip) a Bank Asset account linked to the company bank account.
     */
    private function resolveBankAssetAccount(int $systemId, CompanyBankAccount $bank): ?Account
    {
        return Account::where('system_id', $systemId)
            ->where('type', 'Asset')
            ->where(function ($q) use ($bank) {
                $q->where('name', 'LIKE', '%' . $bank->bank_name . '%')
                  ->orWhere('name', 'LIKE', '%Bank%');
            })
            ->first();
    }
}
