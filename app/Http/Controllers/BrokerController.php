<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\Brokerage;
use App\Models\Sale;
use App\Models\Account;
use App\Models\Booking;
use App\Models\Project;
use App\Models\CompanyBankAccount;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BrokerController extends Controller
{
    public function index(Request $request): View
    {
        $systemId = Auth::user()->system_id;
        $this->syncCommissions($systemId);

        $query = Broker::where('system_id', $systemId)
            ->with(['linkedAccount', 'brokerages.sale.customer', 'brokerages.sale.project'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $brokers = $query->get();

        foreach ($brokers as $broker) {
            $broker->total_deals = $broker->brokerages->count();
            $broker->total_sale_value = $broker->brokerages->sum(fn($b) => $b->sale->total_amount ?? 0);
            
            $accrued = 0.0;
            $payable = 0.0;
            $paid = 0.0;

            foreach ($broker->brokerages as $entry) {
                $commAmt = (float)$entry->commission_amount;
                $paidAmt = (float)$entry->paid_amount;

                // Paid portion ALWAYS contributes to Paid Out
                $paid += $paidAmt;

                $remaining = max(0.0, $commAmt - $paidAmt);

                if ($entry->status === 'pending' && $paidAmt <= 0) {
                    $accrued += $remaining;
                } elseif ($remaining > 0) {
                    $payable += $remaining;
                }
            }

            $broker->accrued_commission = $accrued;
            $broker->payable_commission = $payable;
            $broker->paid_commission = $paid;
            $broker->total_commission = $accrued + $payable + $paid;
        }

        if ($request->filled('sort_by')) {
            $sortBy = $request->sort_by;
            if ($sortBy === 'deals_desc') {
                $brokers = $brokers->sortByDesc('total_deals')->values();
            } elseif ($sortBy === 'accrued_desc') {
                $brokers = $brokers->sortByDesc('accrued_commission')->values();
            } elseif ($sortBy === 'payable_desc') {
                $brokers = $brokers->sortByDesc('payable_commission')->values();
            } elseif ($sortBy === 'rate_desc') {
                $brokers = $brokers->sortByDesc('default_commission_pct')->values();
            }
        }

        return view('brokers.index', compact('brokers'));
    }

    public function commissionLedger(Request $request): View
    {
        $systemId = Auth::user()->system_id;
        $this->syncCommissions($systemId);

        $brokers = Broker::where('system_id', $systemId)
            ->with(['linkedAccount', 'brokerages.sale.customer', 'brokerages.sale.project'])
            ->orderBy('name')
            ->get();

        foreach ($brokers as $broker) {
            $accrued = 0.0;
            $payable = 0.0;
            $paid = 0.0;

            foreach ($broker->brokerages as $entry) {
                $commAmt = (float)$entry->commission_amount;
                $paidAmt = (float)$entry->paid_amount;

                $paid += $paidAmt;

                $remaining = max(0.0, $commAmt - $paidAmt);

                if ($entry->status === 'pending' && $paidAmt <= 0) {
                    $accrued += $remaining;
                } elseif ($remaining > 0) {
                    $payable += $remaining;
                }
            }

            $broker->accrued_commission = $accrued;
            $broker->payable_commission = $payable;
            $broker->paid_commission = $paid;
        }

        // Summary totals across all brokers
        $totalAccrued = $brokers->sum('accrued_commission');
        $totalPayable = $brokers->sum('payable_commission');
        $totalPaid = $brokers->sum('paid_commission');

        // Fetch recent deals/transactions with broker visibility
        $dealsQuery = Brokerage::whereHas('broker', function ($q) use ($systemId) {
                $q->where('system_id', $systemId);
            })
            ->with(['broker', 'sale.project', 'sale.customer', 'sale.unit']);

        if ($request->filled('broker_id')) {
            $dealsQuery->where('broker_id', $request->broker_id);
        }
        if ($request->filled('project_id')) {
            $dealsQuery->whereHas('sale', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        $deals = $dealsQuery->latest()->paginate(15);
        $projects = Project::where('is_active', true)->get();

        return view('brokers.commission-ledger', compact(
            'brokers',
            'deals',
            'projects',
            'totalAccrued',
            'totalPayable',
            'totalPaid'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'default_commission_pct' => ['required', 'numeric', 'min:0.01', 'max:100'],
        ]);

        $systemId = Auth::user()->system_id;

        DB::transaction(function () use ($validated, $systemId) {
            $account = Account::create([
                'system_id' => $systemId,
                'code' => 'BRK-' . strtoupper(bin2hex(random_bytes(3))),
                'name' => $validated['name'] . ' Commission Payable Account',
                'type' => 'liability',
                'is_active' => true,
            ]);

            $broker = Broker::create([
                'system_id' => $systemId,
                'name' => $validated['name'],
                'default_commission_pct' => (float)$validated['default_commission_pct'],
                'linked_account_id' => $account->id,
            ]);

            ActivityLog::record(
                'broker.created',
                "Registered new broker '{$broker->name}' with default commission of {$broker->default_commission_pct}%. Linked ledger account: {$account->code}."
            );
        });

        return redirect()->route('brokers.index')
            ->with('status', 'Broker profile registered successfully with linked liability ledger account.');
    }

    public function update(Request $request, Broker $broker): RedirectResponse
    {
        $systemId = Auth::user()->system_id;
        if ($broker->system_id !== $systemId) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'default_commission_pct' => ['required', 'numeric', 'min:0.01', 'max:100'],
        ]);

        $oldPct = $broker->default_commission_pct;
        $broker->update([
            'name' => $validated['name'],
            'default_commission_pct' => (float)$validated['default_commission_pct'],
        ]);

        ActivityLog::record(
            'broker.updated',
            "Updated broker '{$broker->name}' details. Commission changed from {$oldPct}% to {$broker->default_commission_pct}%."
        );

        return redirect()->back()
            ->with('status', "Broker '{$broker->name}' updated successfully.");
    }

    public function destroy(Broker $broker): RedirectResponse
    {
        $systemId = Auth::user()->system_id;
        if ($broker->system_id !== $systemId) {
            abort(403);
        }

        if ($broker->brokerages()->count() > 0) {
            return redirect()->back()->withErrors(['delete' => "Cannot delete broker '{$broker->name}' because they have associated brokerages/sales."]);
        }

        $brokerName = $broker->name;
        $broker->delete();

        ActivityLog::record(
            'broker.deleted',
            "Deleted broker '{$brokerName}'."
        );

        return redirect()->back()
            ->with('status', "Broker '{$brokerName}' deleted successfully.");
    }

    public function payableReport(Request $request): View
    {
        $systemId = Auth::user()->system_id;
        $this->syncCommissions($systemId);

        $brokers = Broker::where('system_id', $systemId)
            ->with(['linkedAccount', 'brokerages.sale.customer', 'brokerages.sale.unit', 'brokerages.sale.project'])
            ->orderBy('name')
            ->get();

        $brokerReports = [];
        $totalAccrued = 0.0;
        $totalPayable = 0.0;
        $totalPaid = 0.0;

        foreach ($brokers as $broker) {
            $accrued = 0.0;
            $payable = 0.0;
            $paid = 0.0;
            $pendingDealsCount = 0;

            foreach ($broker->brokerages as $entry) {
                $commAmt = (float)$entry->commission_amount;
                $paidAmt = (float)$entry->paid_amount;

                $paid += $paidAmt;
                $remaining = max(0.0, $commAmt - $paidAmt);

                if ($entry->status === 'pending' && $paidAmt <= 0) {
                    $accrued += $remaining;
                    $pendingDealsCount++;
                } elseif ($remaining > 0) {
                    $payable += $remaining;
                    $pendingDealsCount++;
                }
            }

            $totalAccrued += $accrued;
            $totalPayable += $payable;
            $totalPaid += $paid;

            $brokerReports[] = (object)[
                'broker' => $broker,
                'accrued' => $accrued,
                'payable' => $payable,
                'paid' => $paid,
                'total_pending' => $accrued + $payable,
                'pending_deals_count' => $pendingDealsCount,
            ];
        }

        $companyBankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        return view('brokers.payable-report', compact(
            'brokerReports',
            'brokers',
            'totalAccrued',
            'totalPayable',
            'totalPaid',
            'companyBankAccounts'
        ));
    }

    public function recordPayout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_entry_id' => ['nullable', 'exists:brokerages,id'],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
        ]);

        $systemId = Auth::user()->system_id;
        $user = Auth::user();
        $count = 0;
        $totalPaid = 0.0;

        try {
            DB::transaction(function () use ($validated, $systemId, $user, &$count, &$totalPaid) {
                $broker = null;
                $narration = '';

                if (!empty($validated['commission_entry_id'])) {
                    $entry = Brokerage::where('id', $validated['commission_entry_id'])
                        ->whereIn('status', ['payable', 'partial'])
                        ->firstOrFail();

                    // Validate system_id ownership via Broker
                    if ($entry->broker->system_id !== $systemId) abort(403);

                    $entry->update(['status' => 'paid', 'paid_amount' => $entry->commission_amount]);
                    $count = 1;
                    $totalPaid = (float)$entry->commission_amount;
                    $broker = $entry->broker;
                    $brokerName = $broker->name ?? 'Broker';
                    $narration = "Commission payout to broker '{$brokerName}' for Sale #{$entry->sale->sale_number}.";

                    ActivityLog::record('broker.payout', $narration);
                } elseif (!empty($validated['broker_id'])) {
                    $broker = Broker::where('system_id', $systemId)->findOrFail($validated['broker_id']);
                    $entries = Brokerage::where('broker_id', $broker->id)
                        ->whereIn('status', ['payable', 'partial'])
                        ->get();

                    foreach ($entries as $entry) {
                        $entry->update(['status' => 'paid', 'paid_amount' => $entry->commission_amount]);
                        $count++;
                        $totalPaid += (float)$entry->commission_amount;
                    }

                    if ($count > 0) {
                        $narration = "Bulk commission payout across {$count} deal(s) to broker '{$broker->name}'.";
                        ActivityLog::record('broker.payout', $narration);
                    }
                }

                if ($broker && $totalPaid > 0) {
                    $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail($validated['company_bank_account_id']);
                    if ((float) $bankAccount->current_balance < $totalPaid) {
                        throw new \Exception("Insufficient balance in the selected bank account. Available: " . $bankAccount->formatted_balance);
                    }

                    // Decrement bank balance
                    $bankAccount->decrement('current_balance', $totalPaid);

                    // Post Payment Voucher to ledger
                    $voucher = \App\Models\Voucher::create([
                        'system_id' => $systemId,
                        'voucher_number' => 'PV-BROKER-' . $broker->id . '-' . time(),
                        'type' => 'Payment',
                        'date' => now()->toDateString(),
                        'narration' => $narration ?: 'Broker commission payout',
                        'created_by' => $user->id,
                        'status' => 'Posted',
                    ]);

                    // 1. Debit Broker's linked account (reducing liability)
                    $brokerLine = \App\Models\VoucherLine::create([
                        'voucher_id' => $voucher->id,
                        'account_id' => $broker->linked_account_id,
                        'debit' => $totalPaid,
                        'credit' => 0.00,
                        'line_narration' => 'Debit Broker commission payable',
                    ]);

                    \App\Models\LedgerEntry::create([
                        'system_id' => $systemId,
                        'account_id' => $broker->linked_account_id,
                        'voucher_id' => $voucher->id,
                        'voucher_line_id' => $brokerLine->id,
                        'date' => now()->toDateString(),
                        'debit' => $totalPaid,
                        'credit' => 0.00,
                        'running_balance' => 0.00,
                    ]);

                    // 2. Credit Bank account
                    $bankLedgerAccount = Account::where('system_id', $systemId)
                        ->where('type', 'Asset')
                        ->where(function ($q) use ($bankAccount) {
                            $q->where('name', 'LIKE', '%' . $bankAccount->bank_name . '%')
                              ->orWhere('name', 'LIKE', '%Bank%');
                        })
                        ->first();

                    if (!$bankLedgerAccount) {
                        $bankLedgerAccount = Account::firstOrCreate(
                            ['system_id' => $systemId, 'code' => 'BANK-GEN'],
                            ['name' => 'General Bank Account', 'type' => 'Asset', 'is_active' => true]
                        );
                    }

                    $cashLine = \App\Models\VoucherLine::create([
                        'voucher_id' => $voucher->id,
                        'account_id' => $bankLedgerAccount->id,
                        'debit' => 0.00,
                        'credit' => $totalPaid,
                        'line_narration' => 'Credit Bank for commission payout',
                    ]);

                    \App\Models\LedgerEntry::create([
                        'system_id' => $systemId,
                        'account_id' => $bankLedgerAccount->id,
                        'voucher_id' => $voucher->id,
                        'voucher_line_id' => $cashLine->id,
                        'date' => now()->toDateString(),
                        'debit' => 0.00,
                        'credit' => $totalPaid,
                        'running_balance' => 0.00,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '⚠️ ' . $e->getMessage());
        }

        if ($count === 0) {
            return redirect()->back()->with('error', 'No payable commissions found to disburse.');
        }

        return redirect()->back()->with('status', "Successfully recorded commission payout of ₹" . number_format($totalPaid, 2) . " across {$count} transaction(s).");
    }

    private function syncCommissions(int $systemId): void
    {
        // Transition 'pending' commissions to 'payable' if the sale has received customer payments
        $pendingBrokerages = Brokerage::where('status', 'pending')
            ->whereHas('broker', function($q) use($systemId) {
                $q->where('system_id', $systemId);
            })
            ->with('sale')
            ->get();

        foreach ($pendingBrokerages as $entry) {
            if ($entry->sale) {
                $totalPaid = $entry->sale->total_amount - $entry->sale->remaining_balance;
                
                if ($totalPaid > 0) {
                    $paidAmt = (float)$entry->paid_amount;
                    $commAmt = (float)$entry->commission_amount;

                    $newStatus = 'payable';
                    if ($paidAmt >= $commAmt - 0.01 && $commAmt > 0) {
                        $newStatus = 'paid';
                    } elseif ($paidAmt > 0) {
                        $newStatus = 'partial';
                    }

                    $entry->update([
                        'status' => $newStatus,
                    ]);
                }
            }
        }
    }
}
