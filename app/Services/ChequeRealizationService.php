<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanyBankAccount;
use App\Models\Receipt;
use App\Models\ReceiptRealizationLog;
use App\Models\Voucher;
use App\Models\VoucherLine;
use App\Models\Account;
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

        if (!$receipt->company_bank_account_id) {
            throw new \Exception(
                "Receipt #{$receipt->id} has no Company Bank Account assigned. Please assign one first."
            );
        }

        return DB::transaction(function () use ($receipt, $data) {
            $oldStatus = $receipt->realization_status;
            $userId    = $data['realized_by'];
            $remarks   = $data['remarks'] ?? 'Cheque cleared by bank.';

            // 1. Update receipt status
            $receipt->update([
                'realization_status' => 'realized',
                'realized_at'        => now(),
                'realized_by'        => $userId,
            ]);

            // 2. Credit company bank account balance (Step 5.4)
            $bankAccount = CompanyBankAccount::lockForUpdate()
                ->findOrFail($receipt->company_bank_account_id);
            $bankAccount->increment('current_balance', $receipt->amount);

            // 3. Write audit log
            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'realized',
                'remarks'    => $remarks,
                'changed_by' => $userId,
            ]);

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
            $oldStatus = $receipt->realization_status;

            $receipt->update(['realization_status' => 'bounced']);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => 'bounced',
                'remarks'    => $data['remarks'] ?? 'Cheque dishonoured by bank.',
                'changed_by' => $data['changed_by'],
            ]);

            return $receipt->fresh();
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Advance Status (Pending → Cheque in Hand → Deposited)
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
        $allowed = ['cheque_in_hand', 'deposited', 'cancelled'];

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
            $oldStatus = $receipt->realization_status;

            $receipt->update(['realization_status' => $newStatus]);

            ReceiptRealizationLog::create([
                'receipt_id' => $receipt->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'remarks'    => $data['remarks'] ?? null,
                'changed_by' => $data['changed_by'],
            ]);

            return $receipt->fresh();
        });
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

            // Step 5.5: Validate sufficient balance
            if ((float) $bankAccount->current_balance < (float) $data['amount']) {
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

            // Generate sequential voucher number
            $lastVoucher = Voucher::where('system_id', $systemId)
                ->where('type', 'Payment')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $voucherNumber = 'PV-' . str_pad(
                (string) (($lastVoucher ? (int) substr($lastVoucher->voucher_number, 3) : 0) + 1),
                5,
                '0',
                STR_PAD_LEFT
            );

            $narration = $data['narration'] ?? match($data['purpose'] ?? 'other') {
                'supplier_payment' => 'Supplier Payment via ' . $bankAccount->bank_name,
                'customer_refund'  => 'Customer Refund via ' . $bankAccount->bank_name,
                'site_expense'     => 'Site Expense via ' . $bankAccount->bank_name,
                default            => 'Treasury Disbursement via ' . $bankAccount->bank_name,
            };

            // Step 5.6: Create the Payment Voucher
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

            // Resolve debit account (Expense / Payable based on purpose)
            $debitAccount  = $this->resolveDebitAccount($systemId, $data['purpose'] ?? 'other');
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

            // Deduct from treasury balance (Step 5.5)
            $bankAccount->decrement('current_balance', $data['amount']);

            return $voucher;
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Find the appropriate debit (expense/payable) account based on payment purpose.
     */
    private function resolveDebitAccount(int $systemId, string $purpose): ?Account
    {
        $searchTerms = match ($purpose) {
            'supplier_payment' => ['Payable', 'Supplier', 'Vendor', 'Accounts Payable'],
            'customer_refund'  => ['Customer', 'Refund', 'Receivable'],
            'site_expense'     => ['Site', 'Expense', 'Construction'],
            default            => ['Expense'],
        };

        return Account::where('system_id', $systemId)
            ->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'LIKE', "%{$term}%");
                }
            })
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
