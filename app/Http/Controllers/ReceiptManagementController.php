<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptStore;
use App\Models\CompanyBankAccount;
use App\Models\Customer;
use App\Models\Project;
use App\Models\PaymentMode;
use App\Services\ChequeRealizationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReceiptManagementController extends Controller
{
    public function __construct(
        private readonly ChequeRealizationService $realizationService
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — Main Receipt Management Page
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'unit', 'bank'])
            ->latest('receipt_date')
            ->latest('id');

        // Apply Bank Account filter
        if ($request->filled('company_bank_account_id')) {
            $query->where('company_bank_account_id', $request->input('company_bank_account_id'));
        }

        // Apply Realization Status filter
        if ($request->filled('realization_status')) {
            $query->where('realization_status', $request->input('realization_status'));
        }

        // Apply Search filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('drawee_bank', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('companyBankAccount', function ($bq) use ($search) {
                      $bq->where('bank_name', 'like', "%{$search}%")
                         ->orWhere('account_number', 'like', "%{$search}%");
                  });
            });
        }

        // Apply Date Range filter
        if ($request->filled('date_from')) {
            $query->whereDate('receipt_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('receipt_date', '<=', $request->input('date_to'));
        }

        $receipts = $query->paginate(20)->withQueryString();

        $allReceiptsFormatted = collect($receipts->items())->map(function ($r) {
            $bankName = $r->companyBankAccount?->bank_name ?: ($r->bank?->bank_name ?: 'General Account');
            $accNo    = $r->companyBankAccount?->account_number;
            $upiId    = $r->companyBankAccount?->upi_id;
            $ifsc     = $r->companyBankAccount?->ifsc_code ?: $r->bank?->ifsc_code;
            return [
                'id'                          => $r->id,
                'ref'                         => $r->reference_no ?: 'REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT),
                'amount'                      => (float)$r->amount,
                'date'                        => $r->receipt_date?->format('Y-m-d'),
                'customer_name'               => $r->customer?->name ?? '—',
                'customer_id'                 => $r->customer_id,
                'payment_mode'                => $r->payment_mode,
                'company_bank_account_id'     => $r->company_bank_account_id,
                'company_bank_account_name'   => $bankName,
                'company_bank_account_number' => $accNo,
                'company_bank_account_upi_id' => $upiId,
                'company_bank_account_ifsc'   => $ifsc,
                'project_id'                  => $r->project_id,
                'project_name'                => $r->project?->name ?? ($r->sale?->project?->name ?? '—'),
                'unit_id'                     => $r->unit_id,
                'unit_name'                   => $r->unit?->door_no ?? ($r->sale?->unit?->door_no ?? '—'),
                'reference_no'                => $r->reference_no,
                'remarks'                     => $r->remarks,
                'is_allocated'                => (bool)$r->is_allocated,
                // Realization fields
                'realization_status'          => $r->realization_status,
                'realization_status_label'    => $r->realization_status_label,
                'realization_status_color'    => $r->realization_status_color,
                'is_cheque_instrument'        => $r->isChequeInstrument(),
                'can_realize'                 => !$r->isTerminal(),
                'cheque_date'                 => $r->cheque_date?->format('Y-m-d'),
                'drawee_bank'                 => $r->drawee_bank,
                'realized_at'                 => $r->realized_at?->format('d M Y, h:i A'),
            ];
        });

        // Format Stored Receipts from receipt_stores table
        $receiptStoresList = ReceiptStore::with(['companyBankAccount', 'customer', 'project', 'receipt'])
            ->latest('created_at')
            ->get();

        $storedReceiptsFormatted = $receiptStoresList->map(function ($rs) {
            return [
                'id'                          => $rs->id,
                'receipt_id'                  => $rs->receipt_id,
                'ref'                         => $rs->reference_no ?: ($rs->receipt?->reference_no ?: 'REC-STORE-' . str_pad((string)$rs->id, 5, '0', STR_PAD_LEFT)),
                'amount'                      => (float)$rs->amount,
                'date'                        => $rs->receipt_date?->format('Y-m-d'),
                'customer_name'               => $rs->customer?->name ?? '—',
                'payment_mode'                => $rs->payment_mode,
                'company_bank_account_id'     => $rs->company_bank_account_id,
                'company_bank_account_name'   => $rs->companyBankAccount?->bank_name ?? 'Company Bank',
                'company_bank_account_number' => $rs->companyBankAccount?->account_number ?? '—',
                'company_bank_account_upi_id' => $rs->companyBankAccount?->upi_id ?? null,
                'project_name'                => $rs->project?->name ?? '—',
                'status'                      => $rs->status ?: 'stored',
                'created_at_formatted'        => $rs->created_at?->format('d M Y, h:i A'),
            ];
        });

        // Key Dashboard Metrics & Bank Transfer Queue
        $totalCollectionAmount  = (float) Receipt::where('realization_status', 'realized')->sum('amount');
        $totalReceiptsCount     = Receipt::count();
        $pendingRealizationCount= Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])->count();
        $pendingRealizationAmount= (float) Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])->sum('amount');
        $storedCount            = $storedReceiptsFormatted->count();
        $totalTransferredAmount = (float) ReceiptStore::sum('amount');

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalLiquidity     = $companyBankAccounts->sum('current_balance');
        $defaultBankAccount = $companyBankAccounts->firstWhere('is_default', true);

        $customers    = Customer::orderBy('name')->get(['id', 'name', 'phone']);
        $projects     = Project::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        $paymentModes = class_exists(PaymentMode::class)
            ? PaymentMode::where('status', 'active')->orderBy('name')->get(['id', 'name', 'code'])
            : collect([]);

        $realizationStatuses = Receipt::STATUSES;

        return view('receipt-management.index', compact(
            'receipts',
            'allReceiptsFormatted',
            'storedReceiptsFormatted',
            'totalCollectionAmount',
            'totalReceiptsCount',
            'pendingRealizationCount',
            'pendingRealizationAmount',
            'storedCount',
            'totalTransferredAmount',
            'companyBankAccounts',
            'totalLiquidity',
            'defaultBankAccount',
            'customers',
            'projects',
            'paymentModes',
            'realizationStatuses'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // REALIZATION QUEUE — Pending Instruments
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Step 5.2 — Show all instruments awaiting bank clearance.
     */
    public function realizationQueue(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])
            ->latest('receipt_date');

        if ($request->filled('company_bank_account_id')) {
            $query->where('company_bank_account_id', $request->input('company_bank_account_id'));
        }

        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->input('payment_mode'));
        }

        $pendingReceipts = $query->paginate(25)->withQueryString();

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalPendingAmount = Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited'])
            ->sum('amount');

        $paymentModes = class_exists(PaymentMode::class)
            ? PaymentMode::where('status', 'active')->orderBy('name')->get(['id', 'name', 'code'])
            : collect([]);

        return view('receipt-management.realization-queue', compact(
            'pendingReceipts',
            'companyBankAccounts',
            'totalPendingAmount',
            'paymentModes'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE — Create New Receipt
    // ─────────────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'receipt_date'            => ['required', 'date'],
            'amount'                  => ['required', 'numeric', 'min:0.01'],
            'payment_mode'            => ['required', 'string'],
            'reference_no'            => ['nullable', 'string', 'max:100'],
            'customer_id'             => ['nullable', 'exists:customers,id'],
            'payer_name'              => ['nullable', 'string', 'max:255'],
            'project_id'              => ['nullable', 'exists:projects,id'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
            // Cheque-specific fields
            'cheque_date'             => ['nullable', 'date'],
            'drawee_bank'             => ['nullable', 'string', 'max:100'],
        ]);

        $isChequeMode = in_array($validated['payment_mode'], Receipt::DEFERRED_MODES, true);

        DB::transaction(function () use ($validated, $isChequeMode, &$receipt) {
            $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail($validated['company_bank_account_id']);

            $remarks = $validated['remarks'] ?? null;
            if (empty($remarks) && !empty($validated['payer_name'])) {
                $remarks = 'Inbound Payment received from ' . $validated['payer_name'];
            }

            // Step 5.2: For cheques → status = cheque_in_hand, balance NOT updated yet.
            // For Cash/NEFT/UPI → status = realized, balance credited immediately.
            $initStatus = $isChequeMode ? 'cheque_in_hand' : 'realized';

            $receipt = Receipt::create([
                'company_bank_account_id' => $bankAccount->id,
                'receipt_date'            => $validated['receipt_date'],
                'amount'                  => $validated['amount'],
                'payment_mode'            => $validated['payment_mode'],
                'reference_no'            => $validated['reference_no'] ?? null,
                'customer_id'             => $validated['customer_id'] ?? null,
                'project_id'              => $validated['project_id'] ?? null,
                'remarks'                 => $remarks,
                'is_allocated'            => false,
                'realization_status'      => $initStatus,
                'cheque_date'             => $validated['cheque_date'] ?? null,
                'drawee_bank'             => $validated['drawee_bank'] ?? null,
                // If instantly realized, stamp timestamp
                'realized_at'             => $isChequeMode ? null : now(),
                'realized_by'             => $isChequeMode ? null : auth()->id(),
            ]);

            // Only credit balance immediately for non-cheque instruments
            if (!$isChequeMode) {
                $bankAccount->increment('current_balance', $validated['amount']);
            }

            // Save record into receipt_stores table
            ReceiptStore::create([
                'receipt_id'              => $receipt->id,
                'company_bank_account_id' => $bankAccount->id,
                'customer_id'             => $validated['customer_id'] ?? null,
                'project_id'              => $validated['project_id'] ?? null,
                'receipt_date'            => $validated['receipt_date'],
                'amount'                  => $validated['amount'],
                'payment_mode'            => $validated['payment_mode'],
                'reference_no'            => $validated['reference_no'] ?? null,
                'remarks'                 => $remarks,
                'status'                  => $initStatus,
                'created_by'              => auth()->id(),
            ]);
        });

        $message = $isChequeMode
            ? "Receipt #{$receipt->id} recorded as 'Cheque in Hand'. Bank balance will update upon realization."
            : "Receipt #{$receipt->id} created & ₹" . number_format((float)$receipt->amount, 2) . " credited to Company Bank Account.";

        return redirect()->route('receipt-management.index')->with('success', $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // REALIZE — Step 5.3: Mark Cheque as Realized (Step 5.4: Balance Updates)
    // ─────────────────────────────────────────────────────────────────────────

    public function realize(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->realize($receipt, [
                'realized_by' => auth()->id(),
                'remarks'     => $validated['remarks'] ?? null,
            ]);

            $bankName = $receipt->companyBankAccount?->bank_name ?? 'Company Bank Account';
            $amount   = number_format((float) $receipt->amount, 2);

            return redirect()->back()->with(
                'success',
                "✅ Receipt #{$id} Realized! ₹{$amount} credited to {$bankName}. Treasury balance updated."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MARK BOUNCED — Cheque Dishonoured
    // ─────────────────────────────────────────────────────────────────────────

    public function markBounced(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->markBounced($receipt, [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? 'Cheque dishonoured by bank.',
            ]);

            return redirect()->back()->with(
                'success',
                "❌ Receipt #{$id} marked as Bounced. No balance change. Audit log recorded."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADVANCE STATUS — Move to next intermediate status
    // ─────────────────────────────────────────────────────────────────────────

    public function advanceStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'new_status' => ['required', 'in:cheque_in_hand,deposited,cancelled'],
            'remarks'    => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->advanceStatus($receipt, $validated['new_status'], [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? null,
            ]);

            $label = Receipt::STATUSES[$validated['new_status']] ?? $validated['new_status'];

            return redirect()->back()->with('success', "Receipt #{$id} updated to: {$label}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ASSIGN BANK (existing — kept for compatibility)
    // ─────────────────────────────────────────────────────────────────────────

    public function assignBank(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
        ]);

        $receipt = Receipt::findOrFail($id);

        DB::transaction(function () use ($receipt, $validated) {
            $newBank = CompanyBankAccount::lockForUpdate()->findOrFail($validated['company_bank_account_id']);

            // Only adjust balance for already-realized receipts
            if ($receipt->isRealized()) {
                if ($receipt->company_bank_account_id && $receipt->company_bank_account_id != $newBank->id) {
                    $oldBank = CompanyBankAccount::lockForUpdate()->find($receipt->company_bank_account_id);
                    if ($oldBank) {
                        $oldBank->decrement('current_balance', $receipt->amount);
                    }
                    $newBank->increment('current_balance', $receipt->amount);
                } elseif (!$receipt->company_bank_account_id) {
                    $newBank->increment('current_balance', $receipt->amount);
                }
            }

            $receipt->update(['company_bank_account_id' => $newBank->id]);

            ReceiptStore::updateOrCreate(
                ['receipt_id' => $receipt->id],
                [
                    'company_bank_account_id' => $newBank->id,
                    'customer_id'             => $receipt->customer_id,
                    'project_id'              => $receipt->project_id,
                    'unit_id'                 => $receipt->unit_id,
                    'receipt_date'            => $receipt->receipt_date,
                    'amount'                  => $receipt->amount,
                    'payment_mode'            => $receipt->payment_mode ?: 'NEFT/RTGS',
                    'reference_no'            => $receipt->reference_no,
                    'remarks'                 => $receipt->remarks,
                    'status'                  => $receipt->realization_status,
                    'created_by'              => auth()->id(),
                ]
            );
        });

        return redirect()->route('receipt-management.index')
            ->with('success', "Receipt #{$receipt->id} assigned to bank account.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ASSIGN BANK BULK (existing — kept for compatibility)
    // ─────────────────────────────────────────────────────────────────────────

    public function assignBankBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'receipt_ids'             => ['required', 'array', 'min:1'],
            'receipt_ids.*'           => ['exists:receipts,id'],
        ]);

        $receiptIds    = $validated['receipt_ids'];
        $bankAccountId = $validated['company_bank_account_id'];

        $count       = 0;
        $totalAmount = 0.0;

        DB::transaction(function () use ($receiptIds, $bankAccountId, &$count, &$totalAmount) {
            $newBank  = CompanyBankAccount::lockForUpdate()->findOrFail($bankAccountId);
            $receipts = Receipt::whereIn('id', $receiptIds)->lockForUpdate()->get();

            foreach ($receipts as $receipt) {
                // Only move realized balance between accounts
                if ($receipt->isRealized()) {
                    if ($receipt->company_bank_account_id && $receipt->company_bank_account_id != $newBank->id) {
                        $oldBank = CompanyBankAccount::lockForUpdate()->find($receipt->company_bank_account_id);
                        if ($oldBank) {
                            $oldBank->decrement('current_balance', $receipt->amount);
                        }
                        $newBank->increment('current_balance', $receipt->amount);
                    } elseif (!$receipt->company_bank_account_id) {
                        $newBank->increment('current_balance', $receipt->amount);
                    }
                }

                $receipt->update(['company_bank_account_id' => $newBank->id]);

                ReceiptStore::updateOrCreate(
                    ['receipt_id' => $receipt->id],
                    [
                        'company_bank_account_id' => $newBank->id,
                        'customer_id'             => $receipt->customer_id,
                        'project_id'              => $receipt->project_id,
                        'unit_id'                 => $receipt->unit_id,
                        'receipt_date'            => $receipt->receipt_date,
                        'amount'                  => $receipt->amount,
                        'payment_mode'            => $receipt->payment_mode ?: 'Cheque',
                        'reference_no'            => $receipt->reference_no,
                        'remarks'                 => $receipt->remarks,
                        'status'                  => $receipt->realization_status,
                        'created_by'              => auth()->id(),
                    ]
                );

                $count++;
                $totalAmount += (float)$receipt->amount;
            }
        });

        return redirect()->route('receipt-management.index')
            ->with('success', "Bulk Action: {$count} Receipts (₹" . number_format($totalAmount, 2) . ") assigned to Company Bank Account.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DESTROY — Cancel Receipt
    // ─────────────────────────────────────────────────────────────────────────

    public function destroy(int $id): RedirectResponse
    {
        $receipt = Receipt::findOrFail($id);

        DB::transaction(function () use ($receipt) {
            // Only reverse balance if receipt was already realized
            if ($receipt->isRealized() && $receipt->company_bank_account_id) {
                $bankAccount = CompanyBankAccount::lockForUpdate()->find($receipt->company_bank_account_id);
                if ($bankAccount) {
                    $bankAccount->decrement('current_balance', $receipt->amount);
                }
            }

            ReceiptStore::where('receipt_id', $receipt->id)->delete();
            $receipt->delete();
        });

        return redirect()->route('receipt-management.index')
            ->with('success', "Receipt #{$id} cancelled and removed.");
    }
}
