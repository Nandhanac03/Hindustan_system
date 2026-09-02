<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\CompanyBankAccount;
use App\Models\PaymentMode;
use App\Services\ChequeRealizationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChequeRealizationController extends Controller
{
    public function __construct(
        private readonly ChequeRealizationService $realizationService
    ) {}

    /**
     * Show all instruments awaiting bank clearance.
     */
    public function realizationQueue(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing', 'bounced', 'cancelled'])
            ->latest('receipt_date');

        $pendingReceipts = (clone $query)->paginate(25)->withQueryString();

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalPendingCount  = Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'])->count();
        $totalPendingAmount = (float) Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'])->sum('amount');

        $inProgressCount    = Receipt::whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->count();
        $inProgressAmount   = (float) Receipt::whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->sum('amount');

        $realizedCount      = Receipt::where('realization_status', 'realized')->count();
        $realizedAmount     = (float) Receipt::where('realization_status', 'realized')->sum('amount');

        $bouncedCount       = Receipt::where('realization_status', 'bounced')->count();
        $bouncedAmount      = (float) Receipt::where('realization_status', 'bounced')->sum('amount');

        $customers = \App\Models\Customer::orderBy('name')->get(['id', 'name']);
        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        $chequeStatusesMap = [];
        foreach ($chequeStatuses as $cs) {
            $key = strtolower(str_replace(' ', '_', trim($cs->name)));
            $color = strtolower($cs->color_code ?? 'slate-500');
            $badgeClasses = match(true) {
                str_contains($color, 'emerald') || str_contains($color, 'green') => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                str_contains($color, 'amber') || str_contains($color, 'orange') || str_contains($color, 'yellow') => 'bg-amber-50 text-amber-800 border-amber-300',
                str_contains($color, 'blue') => 'bg-blue-50 text-blue-800 border-blue-300',
                str_contains($color, 'rose') || str_contains($color, 'red') => 'bg-rose-50 text-rose-800 border-rose-300',
                str_contains($color, 'purple') || str_contains($color, 'violet') => 'bg-purple-50 text-purple-800 border-purple-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300',
            };
            $chequeStatusesMap[$key] = [
                'name' => strtoupper($cs->name),
                'badge_classes' => $badgeClasses,
            ];
        }

        $allReceipts = $query->get();
        $allReceiptsFormatted = $allReceipts->map(function($r) use ($chequeStatusesMap) {
            $bankName = $r->companyBankAccount?->bank_name ?: ($r->bank?->bank_name ?: 'General Account');
            $accNo    = $r->companyBankAccount?->account_number;
            $rst      = strtolower($r->realization_status ?? 'pending');
            $rstMaster = $chequeStatusesMap[$rst] ?? null;
            $badgeClasses = $rstMaster ? $rstMaster['badge_classes'] : match($rst) {
                'realized' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                'cheque_in_hand' => 'bg-amber-50 text-amber-800 border-amber-300',
                'deposited' => 'bg-blue-50 text-blue-800 border-blue-300',
                'in_clearing' => 'bg-sky-50 text-sky-800 border-sky-300',
                'bounced' => 'bg-rose-50 text-rose-800 border-rose-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300'
            };
            $statusName = $rstMaster ? $rstMaster['name'] : strtoupper(str_replace('_', ' ', $rst));

            $icons = [
                'pending'        => '⏳',
                'cheque_in_hand' => '🖐',
                'deposited'      => '🏦',
                'in_clearing'    => '🔄',
                'realized'       => '✅',
                'bounced'        => '❌',
                'cancelled'      => '🚫',
            ];

            return [
                'id'                          => $r->id,
                'receipt_no'                  => $r->receipt_no ?? ('RV/2025-26/' . str_pad($r->id, 6, '0', STR_PAD_LEFT)),
                'ref'                         => $r->reference_no ?: ('REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT)),
                'date'                        => $r->receipt_date?->format('Y-m-d'),
                'date_formatted'              => $r->receipt_date?->format('d M Y') ?? '—',
                'customer_id'                 => $r->customer_id,
                'customer_name'               => $r->customer?->name ?? '—',
                'company_bank_account_id'     => $r->company_bank_account_id,
                'company_bank_account_name'   => $bankName,
                'company_bank_account_number' => $accNo,
                'drawee_bank'                 => $r->drawee_bank ?? '—',
                'cheque_date'                 => $r->cheque_date?->format('Y-m-d'),
                'cheque_date_formatted'       => $r->cheque_date?->format('d M Y') ?? '—',
                'amount'                      => (float)$r->amount,
                'payment_mode'                => $r->payment_mode ?: 'Cheque',
                'realization_status'          => $r->realization_status ?? 'pending',
                'status_display_name'         => $statusName,
                'status_badge_classes'        => $badgeClasses,
                'status_icon'                 => $icons[$r->realization_status] ?? '•',
                'remarksText'                 => $r->realizationLogs->first()?->remarks ?? ($r->remarks ?? ''),
                'is_terminal'                 => in_array($r->realization_status, ['bounced', 'cancelled']),
            ];
        });

        return view('cheque-realization.queue', compact(
            'pendingReceipts',
            'allReceiptsFormatted',
            'companyBankAccounts',
            'totalPendingAmount',
            'totalPendingCount',
            'inProgressCount',
            'inProgressAmount',
            'realizedCount',
            'realizedAmount',
            'bouncedCount',
            'bouncedAmount',
            'customers',
            'chequeStatuses'
        ));
    }

    /**
     * Show realized instruments.
     */
    public function realizedReceipts(Request $request): View
    {
        $query = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->where('realization_status', 'realized')
            ->latest('receipt_date');

        $pendingReceipts = (clone $query)->paginate(25)->withQueryString();

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalPendingCount  = Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'])->count();
        $totalPendingAmount = (float) Receipt::whereIn('realization_status', ['pending', 'cheque_in_hand', 'deposited', 'in_clearing'])->sum('amount');

        $inProgressCount    = Receipt::whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->count();
        $inProgressAmount   = (float) Receipt::whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->sum('amount');

        $realizedCount      = Receipt::where('realization_status', 'realized')->count();
        $realizedAmount     = (float) Receipt::where('realization_status', 'realized')->sum('amount');

        $bouncedCount       = Receipt::where('realization_status', 'bounced')->count();
        $bouncedAmount      = (float) Receipt::where('realization_status', 'bounced')->sum('amount');

        $customers = \App\Models\Customer::orderBy('name')->get(['id', 'name']);
        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        $chequeStatusesMap = [];
        foreach ($chequeStatuses as $cs) {
            $key = strtolower(str_replace(' ', '_', trim($cs->name)));
            $color = strtolower($cs->color_code ?? 'slate-500');
            $badgeClasses = match(true) {
                str_contains($color, 'emerald') || str_contains($color, 'green') => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                str_contains($color, 'amber') || str_contains($color, 'orange') || str_contains($color, 'yellow') => 'bg-amber-50 text-amber-800 border-amber-300',
                str_contains($color, 'blue') => 'bg-blue-50 text-blue-800 border-blue-300',
                str_contains($color, 'rose') || str_contains($color, 'red') => 'bg-rose-50 text-rose-800 border-rose-300',
                str_contains($color, 'purple') || str_contains($color, 'violet') => 'bg-purple-50 text-purple-800 border-purple-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300',
            };
            $chequeStatusesMap[$key] = [
                'name' => strtoupper($cs->name),
                'badge_classes' => $badgeClasses,
            ];
        }

        $allReceipts = $query->get();
        $allReceiptsFormatted = $allReceipts->map(function($r) use ($chequeStatusesMap) {
            $bankName = $r->companyBankAccount?->bank_name ?: ($r->bank?->bank_name ?: 'General Account');
            $accNo    = $r->companyBankAccount?->account_number;
            $rst      = strtolower($r->realization_status ?? 'pending');
            $rstMaster = $chequeStatusesMap[$rst] ?? null;
            $badgeClasses = $rstMaster ? $rstMaster['badge_classes'] : match($rst) {
                'realized' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                'cheque_in_hand' => 'bg-amber-50 text-amber-800 border-amber-300',
                'deposited' => 'bg-blue-50 text-blue-800 border-blue-300',
                'in_clearing' => 'bg-sky-50 text-sky-800 border-sky-300',
                'bounced' => 'bg-rose-50 text-rose-800 border-rose-300',
                default => 'bg-slate-100 text-slate-700 border-slate-300'
            };
            $statusName = $rstMaster ? $rstMaster['name'] : strtoupper(str_replace('_', ' ', $rst));

            $icons = [
                'pending'        => '⏳',
                'cheque_in_hand' => '🖐',
                'deposited'      => '🏦',
                'in_clearing'    => '🔄',
                'realized'       => '✅',
                'bounced'        => '❌',
                'cancelled'      => '🚫',
            ];

            return [
                'id'                          => $r->id,
                'receipt_no'                  => $r->receipt_no ?? ('RV/2025-26/' . str_pad($r->id, 6, '0', STR_PAD_LEFT)),
                'ref'                         => $r->reference_no ?: ('REC-' . str_pad((string)$r->id, 5, '0', STR_PAD_LEFT)),
                'date'                        => $r->receipt_date?->format('Y-m-d'),
                'date_formatted'              => $r->receipt_date?->format('d M Y') ?? '—',
                'customer_id'                 => $r->customer_id,
                'customer_name'               => $r->customer?->name ?? '—',
                'company_bank_account_id'     => $r->company_bank_account_id,
                'company_bank_account_name'   => $bankName,
                'company_bank_account_number' => $accNo,
                'drawee_bank'                 => $r->drawee_bank ?? '—',
                'cheque_date'                 => $r->cheque_date?->format('Y-m-d'),
                'cheque_date_formatted'       => $r->cheque_date?->format('d M Y') ?? '—',
                'amount'                      => (float)$r->amount,
                'payment_mode'                => $r->payment_mode ?: 'Cheque',
                'realization_status'          => $r->realization_status ?? 'pending',
                'status_display_name'         => $statusName,
                'status_badge_classes'        => $badgeClasses,
                'status_icon'                 => $icons[$r->realization_status] ?? '•',
                'remarksText'                 => $r->realizationLogs->first()?->remarks ?? ($r->remarks ?? ''),
                'is_terminal'                 => in_array($r->realization_status, ['bounced', 'cancelled']),
            ];
        });

        return view('cheque-realization.queue', compact(
            'pendingReceipts',
            'allReceiptsFormatted',
            'companyBankAccounts',
            'totalPendingAmount',
            'totalPendingCount',
            'inProgressCount',
            'inProgressAmount',
            'realizedCount',
            'realizedAmount',
            'bouncedCount',
            'bouncedAmount',
            'customers',
            'chequeStatuses'
        ));
    }

    /**
     * Show realization process details screen.
     */
    public function showRealizationProcess(Request $request, int $id): View
    {
        $receipt = Receipt::with(['companyBankAccount', 'customer', 'project', 'bank'])
            ->findOrFail($id);

        $companyBankAccounts = CompanyBankAccount::orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $chequeStatuses = \App\Models\ChequeStatus::where('is_active', 1)->get();

        return view('cheque-realization.detail', compact('receipt', 'companyBankAccounts', 'chequeStatuses'));
    }

    /**
     * Mark as Realized
     */
    public function realize(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'realization_date' => ['required', 'date'],
            'bank_reference_no' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->realize($receipt, [
                'realized_by' => auth()->id(),
                'remarks'     => $validated['remarks'] ?? null,
                'company_bank_account_id' => $validated['company_bank_account_id'],
                'realization_date' => $validated['realization_date'],
                'bank_reference_no' => $validated['bank_reference_no'] ?? null,
            ]);

            $bankName = $receipt->companyBankAccount?->bank_name ?? 'Company Bank Account';
            $amount   = number_format((float) $receipt->amount, 2);

            return redirect()->route('treasury.dashboard')->with(
                'success',
                "✅ Receipt #{$id} Realized! ₹{$amount} credited to {$bankName}. Treasury balance updated."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark as Bounced
     */
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

            return redirect()->route('cheque-realization.queue')->with(
                'success',
                "❌ Receipt #{$id} marked as Bounced. No balance change. Audit log recorded."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Advance Status (e.g. to deposited)
     */
    public function advanceStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'new_status' => ['required', 'in:pending,cheque_in_hand,deposited,in_clearing,cancelled'],
            'remarks'    => ['nullable', 'string', 'max:500'],
            'company_bank_account_id' => ['nullable', 'exists:company_bank_accounts,id'],
            'bank_reference_no' => ['nullable', 'string', 'max:255'],
        ]);

        $receipt = Receipt::findOrFail($id);

        try {
            $this->realizationService->advanceStatus($receipt, $validated['new_status'], [
                'changed_by' => auth()->id(),
                'remarks'    => $validated['remarks'] ?? null,
                'company_bank_account_id' => $validated['company_bank_account_id'] ?? null,
                'bank_reference_no' => $validated['bank_reference_no'] ?? null,
            ]);

            $label = Receipt::STATUSES[$validated['new_status']] ?? $validated['new_status'];

            return redirect()->route('cheque-realization.queue')->with('success', "Receipt #{$id} updated to: {$label}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
