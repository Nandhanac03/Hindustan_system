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
use App\Models\PaymentMode;
use App\Models\ActivityLog;
use App\Models\JournalVoucher;
use App\Models\JournalEntry;
use App\Models\VoucherType;
use App\Models\ChartOfAccount;
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

        $projects = Project::where('is_active', true)->orderBy('name')->get();
        if ($projects->isEmpty()) {
            $projects = Project::orderBy('name')->get();
        }

        // If project_id is not explicitly provided in request, default to first project
        $selectedProjectId = $request->has('project_id') ? (string)$request->project_id : ($projects->first()?->id ? (string)$projects->first()->id : '');

        $allBrokers = Broker::where('system_id', $systemId)
            ->orderBy('name')
            ->get();

        $brokers = Broker::where('system_id', $systemId)
            ->with(['linkedAccount', 'brokerages.sale.customer', 'brokerages.sale.unit', 'brokerages.sale.project'])
            ->orderBy('name')
            ->get();

        foreach ($brokers as $broker) {
            $matchingBrokerages = $broker->brokerages->filter(function ($entry) use ($selectedProjectId) {
                if ($selectedProjectId === '' || $selectedProjectId === null) {
                    return true;
                }
                $saleProjId = $entry->sale?->project_id ?? $entry->sale?->unit?->project_id;
                return (string)$saleProjId === (string)$selectedProjectId;
            });

            $broker->total_deals = $matchingBrokerages->count();
            $broker->total_sale_value = $matchingBrokerages->sum(fn($b) => $b->sale->total_amount ?? 0);
            
            $accrued = 0.0;
            $payable = 0.0;
            $paid = 0.0;
            
            foreach ($matchingBrokerages as $entry) {
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

        return view('brokers.index', compact('brokers', 'allBrokers', 'projects', 'selectedProjectId'));
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

    public function commissionLedger(Request $request): View
    {
        $systemId = Auth::user()->system_id;
        $this->syncCommissions($systemId);

        $brokers = Broker::where('system_id', $systemId)
            ->with(['linkedAccount', 'brokerages.sale.customer', 'brokerages.sale.unit', 'brokerages.sale.project'])
            ->orderBy('name')
            ->get();

        $projects = Project::where('is_active', true)->orderBy('name')->get();

        $companyBankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $paymentModes = PaymentMode::where('status', 'active')
            ->orderByRaw("CASE WHEN code = 'BANK_TRANSFER' OR name LIKE '%Bank Transfer%' THEN 0 ELSE 1 END, id ASC")
            ->get();

        $allDeals = collect();
        $allLedgerEntries = collect();
        $totalCommissionAccrued = 0.0;
        $totalDisbursementsReleased = 0.0;

        $brokerAccountIds = $brokers->pluck('linked_account_id')->filter()->unique()->toArray();

        $vouchers = \App\Models\Voucher::where('system_id', $systemId)
            ->where('type', 'Payment')
            ->where(function ($q) use ($brokerAccountIds) {
                $q->where('voucher_number', 'LIKE', 'PV-BROKER-%')
                  ->orWhere('narration', 'LIKE', '%Commission payout%')
                  ->orWhere('narration', 'LIKE', '%Broker%');
                if (!empty($brokerAccountIds)) {
                    $q->orWhereHas('lines', function ($lq) use ($brokerAccountIds) {
                        $lq->whereIn('account_id', $brokerAccountIds);
                    });
                }
            })
            ->with(['companyBankAccount', 'lines'])
            ->get();

        // 1. Collect Broker Commission Allocations (Deals)
        foreach ($brokers as $broker) {
            foreach ($broker->brokerages as $entry) {
                $sale = $entry->sale;
                $claimDate = $sale?->sale_date ? \Carbon\Carbon::parse($sale->sale_date) : $entry->created_at;
                $saleNo = $sale?->sale_number ?? ('COMM-#' . $entry->id);
                $unitDoor = $sale?->unit?->door_no ? ($sale->unit->door_no) : '';
                $customerName = $sale?->customer?->name ?? 'Customer';
                $projectName = $sale?->project?->name ?? ($sale?->unit?->project?->name ?? 'Tabasco Project');
                $dealAmount = (float)($sale?->total_amount ?? 0);
                $remainingBalance = (float)($sale?->remaining_balance ?? 0);
                $collectedAmount = max(0.0, $dealAmount - $remainingBalance);
                $collectedPct = $dealAmount > 0 ? (int)round(($collectedAmount / $dealAmount) * 100) : 0;

                $commPct = (float)($entry->commission_percent ?? $broker->default_commission_pct ?? 0);
                $commAmount = (float)$entry->commission_amount;
                $paidAmount = (float)$entry->paid_amount;
                $balanceDue = max(0.0, $commAmount - $paidAmount);
                $payoutPct = $commAmount > 0 ? (int)min(100, round(($paidAmount / $commAmount) * 100)) : 0;

                $status = $entry->status ?? 'pending';
                if ($paidAmount >= $commAmount - 0.01 && $commAmount > 0) {
                    $status = 'paid';
                } elseif ($paidAmount > 0) {
                    $status = 'partial';
                }

                $particulars = "Brokerage Commission for Sale #{$saleNo}" . ($projectName ? " ({$projectName}" . ($unitDoor ? " - Unit {$unitDoor}" : "") . " - {$customerName})" : "");

                $totalCommissionAccrued += $commAmount;

                $dealEntries = [];

                // Claim Entry
                $claimEntry = [
                    'id'                 => 'comm_' . $entry->id,
                    'type'               => 'CLAIM',
                    'type_label'         => 'Commission Allocated',
                    'date'               => $claimDate->format('Y-m-d'),
                    'date_formatted'     => $claimDate->format('d/m/Y'),
                    'broker_id'          => $broker->id,
                    'broker_name'        => $broker->name,
                    'project_id'         => $sale?->project_id,
                    'project_name'       => $projectName,
                    'unit_name'          => $unitDoor ? "Unit {$unitDoor}" : '',
                    'customer_name'      => $customerName,
                    'deal_value'         => $dealAmount,
                    'commission_percent' => $commPct,
                    'ref_no'             => $saleNo,
                    'particulars'        => $particulars,
                    'gross_amount'       => $commAmount,
                    'net_approved'       => $commAmount,
                    'paid_amount'        => 0.0,
                    'running_balance'    => $commAmount,
                    'status'             => $status,
                    'entry_id'           => $entry->id,
                    'jv_id'              => null,
                    'voucher_id'         => null,
                    'company_bank_account_name' => null,
                    'payment_mode'       => null,
                ];

                $dealEntries[] = $claimEntry;
                $allLedgerEntries->push($claimEntry);

                // Find matching vouchers for this specific deal
                $matchedVouchers = $vouchers->filter(function($v) use ($saleNo) {
                    return (stripos($v->narration ?? '', $saleNo) !== false);
                });

                $dealRunningBalance = $commAmount;

                foreach ($matchedVouchers as $v) {
                    $debitAmount = (float)$v->lines->where('debit', '>', 0)->sum('debit');
                    if ($debitAmount <= 0 && preg_match('/₹\s*([\d,\.]+)/u', $v->narration ?? '', $amtMatch)) {
                        $debitAmount = (float)str_replace(',', '', $amtMatch[1]);
                    }
                    if ($debitAmount <= 0) {
                        $debitAmount = (float)$v->lines->sum('credit');
                    }

                    $paymentMode = 'Bank Transfer';
                    if (preg_match('/\[Mode:\s*([^\]]+)\]/i', $v->narration ?? '', $pmMatch)) {
                        $paymentMode = trim($pmMatch[1]);
                    } elseif (stripos($v->narration ?? '', 'cheque') !== false) {
                        $paymentMode = 'Cheque';
                    } elseif (stripos($v->narration ?? '', 'cash') !== false) {
                        $paymentMode = 'Cash';
                    } elseif (stripos($v->narration ?? '', 'upi') !== false) {
                        $paymentMode = 'UPI / Online';
                    }

                    $vDate = $v->date ? \Carbon\Carbon::parse($v->date) : $v->created_at;
                    $dealRunningBalance = max(0.0, $dealRunningBalance - $debitAmount);

                    $vEntry = [
                        'id'                 => 'vouch_' . $v->id,
                        'type'               => 'DISBURSEMENT',
                        'type_label'         => 'Payment Released',
                        'date'               => $vDate->format('Y-m-d'),
                        'date_formatted'     => $vDate->format('d/m/Y'),
                        'broker_id'          => $broker->id,
                        'broker_name'        => $broker->name,
                        'project_id'         => $sale?->project_id,
                        'project_name'       => $projectName,
                        'unit_name'          => $unitDoor ? "Unit {$unitDoor}" : '',
                        'customer_name'      => $customerName,
                        'deal_value'         => $dealAmount,
                        'commission_percent' => $commPct,
                        'ref_no'             => $v->reference_no ?: $v->voucher_number,
                        'particulars'        => $v->narration ?: ("Commission Payout Released to {$broker->name}"),
                        'gross_amount'       => 0.0,
                        'net_approved'       => 0.0,
                        'paid_amount'        => $debitAmount,
                        'running_balance'    => $dealRunningBalance,
                        'status'             => 'paid',
                        'entry_id'           => $entry->id,
                        'payment_id'         => $v->id,
                        'voucher_id'         => $v->id,
                        'company_bank_account_name' => $v->companyBankAccount?->bank_name ?? 'Source Bank Account',
                        'payment_mode'       => $paymentMode,
                    ];

                    $dealEntries[] = $vEntry;
                    $allLedgerEntries->push($vEntry);
                }

                // If deal has paid_amount > 0 but no specific voucher matched, add recorded payment entry
                if ($paidAmount > 0 && $matchedVouchers->isEmpty()) {
                    $dealRunningBalance = max(0.0, $dealRunningBalance - $paidAmount);
                    $dealEntries[] = [
                        'id'                 => 'deal_paid_' . $entry->id,
                        'type'               => 'DISBURSEMENT',
                        'type_label'         => 'Payment Released',
                        'date'               => $entry->updated_at ? $entry->updated_at->format('Y-m-d') : now()->format('Y-m-d'),
                        'date_formatted'     => $entry->updated_at ? $entry->updated_at->format('d/m/Y') : now()->format('d/m/Y'),
                        'broker_id'          => $broker->id,
                        'broker_name'        => $broker->name,
                        'project_id'         => $sale?->project_id,
                        'project_name'       => $projectName,
                        'unit_name'          => $unitDoor ? "Unit {$unitDoor}" : '',
                        'customer_name'      => $customerName,
                        'deal_value'         => $dealAmount,
                        'commission_percent' => $commPct,
                        'ref_no'             => 'PV-COMM-' . $entry->id,
                        'particulars'        => "Commission Payout Released for Sale #{$saleNo}",
                        'gross_amount'       => 0.0,
                        'net_approved'       => 0.0,
                        'paid_amount'        => $paidAmount,
                        'running_balance'    => $dealRunningBalance,
                        'status'             => 'paid',
                        'entry_id'           => $entry->id,
                        'voucher_id'         => null,
                        'company_bank_account_name' => 'Bank Transfer',
                        'payment_mode'       => 'Bank Transfer',
                    ];
                }

                $totalDisbursementsReleased += $paidAmount;

                $allDeals->push([
                    'id'                    => $entry->id,
                    'sale_id'               => $sale?->id,
                    'broker_id'             => $broker->id,
                    'broker_name'           => $broker->name,
                    'project_id'            => $sale?->project_id,
                    'project_name'          => $projectName,
                    'unit_name'             => $unitDoor,
                    'customer_name'         => $customerName,
                    'sale_number'           => $saleNo,
                    'sale_date'             => $claimDate->format('Y-m-d'),
                    'sale_date_formatted'   => $claimDate->format('d M Y'),
                    'net_sale_value'        => $dealAmount,
                    'sale_remaining_balance'=> $remainingBalance,
                    'sale_collected_pct'    => $collectedPct,
                    'commission_percent'    => $commPct,
                    'commission_amount'     => $commAmount,
                    'paid_amount'           => $paidAmount,
                    'balance_due'           => $balanceDue,
                    'payout_pct'            => $payoutPct,
                    'status'                => $status,
                    'entries'               => $dealEntries,
                ]);
            }
        }

        $allDeals = $allDeals->sortByDesc('sale_date')->values();
        $allLedgerEntries = $allLedgerEntries->sortBy('date')->values();

        $totalOutstandingBalance = max(0.0, $totalCommissionAccrued - $totalDisbursementsReleased);

        return view('brokers.commission-ledger', compact(
            'brokers',
            'projects',
            'companyBankAccounts',
            'paymentModes',
            'allDeals',
            'allLedgerEntries',
            'totalCommissionAccrued',
            'totalDisbursementsReleased',
            'totalOutstandingBalance'
        ));
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

            $broker->accrued_commission = $accrued;
            $broker->payable_commission = $accrued + $payable;
            $broker->paid_commission = $paid;
            $broker->total_commission = $accrued + $payable + $paid;
            $broker->available_balance = max(0.0, $accrued + $payable);

            $totalAccrued += $accrued;
            $totalPayable += $payable;
            $totalPaid += $paid;

            $brokerReports[] = (object)[
                'broker' => $broker,
                'accrued' => $accrued,
                'payable' => $payable,
                'paid' => $paid,
                'paid_out' => $paid,
                'total_pending' => $accrued + $payable,
                'pending_deals_count' => $pendingDealsCount,
            ];
        }

        $companyBankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $projects = Project::where('is_active', true)->orderBy('name')->get();

        // Build Chronological Running Ledger for Brokers (Credits = Allocated Commissions, Debits = Payouts Released)
        $runningLedger = collect();

        foreach ($brokers as $broker) {
            foreach ($broker->brokerages as $entry) {
                $sale = $entry->sale;
                $date = $sale?->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') : $entry->created_at->format('Y-m-d');
                $saleNo = $sale?->sale_number ?? ('COMM-#' . $entry->id);
                $unitDoor = $sale?->unit?->door_no ? ("Unit " . $sale->unit->door_no) : '';
                $customerName = $sale?->customer?->name ?? 'Customer';
                $projectName = $sale?->project?->name ?? '';

                $desc = "Commission Allocated for Sale #{$saleNo}" . ($projectName ? " ({$projectName}" . ($unitDoor ? " {$unitDoor}" : "") . " - {$customerName})" : "");

                $runningLedger->push((object)[
                    'id' => 'comm_' . $entry->id,
                    'date' => $date,
                    'broker_id' => $broker->id,
                    'broker_name' => $broker->name,
                    'project_id' => $sale?->project_id,
                    'ref_no' => $saleNo,
                    'description' => $desc,
                    'payment_mode' => null,
                    'credit' => (float)$entry->commission_amount,
                    'debit' => 0.0,
                    'status' => $entry->status ?? 'payable',
                    'company_bank_account_name' => null,
                ]);
            }
        }

        $brokerAccountIds = $brokers->pluck('linked_account_id')->filter()->unique()->toArray();

        $vouchers = \App\Models\Voucher::where('system_id', $systemId)
            ->where('type', 'Payment')
            ->where(function ($q) use ($brokerAccountIds) {
                $q->where('voucher_number', 'LIKE', 'PV-BROKER-%')
                  ->orWhere('narration', 'LIKE', '%Commission payout%')
                  ->orWhere('narration', 'LIKE', '%Broker%');
                if (!empty($brokerAccountIds)) {
                    $q->orWhereHas('lines', function ($lq) use ($brokerAccountIds) {
                        $lq->whereIn('account_id', $brokerAccountIds);
                    });
                }
            })
            ->with(['companyBankAccount', 'lines'])
            ->get();

        foreach ($vouchers as $v) {
            $bId = null;
            if (preg_match('/PV-BROKER-(\d+)/i', $v->voucher_number, $m)) {
                $bId = (int)$m[1];
            }
            if (!$bId) {
                foreach ($v->lines as $line) {
                    $matchedBroker = $brokers->firstWhere('linked_account_id', $line->account_id);
                    if ($matchedBroker) {
                        $bId = $matchedBroker->id;
                        break;
                    }
                }
            }
            if (!$bId) {
                foreach ($brokers as $brk) {
                    if (stripos($v->narration ?? '', $brk->name) !== false) {
                        $bId = $brk->id;
                        break;
                    }
                }
            }

            $broker = $bId ? $brokers->firstWhere('id', $bId) : null;
            $brokerName = $broker?->name ?? 'Broker';

            $debitAmount = (float)$v->lines->where('debit', '>', 0)->sum('debit');
            if ($debitAmount <= 0 && preg_match('/₹\s*([\d,\.]+)/u', $v->narration ?? '', $amtMatch)) {
                $debitAmount = (float)str_replace(',', '', $amtMatch[1]);
            }

            $paymentMode = 'Bank Transfer (NEFT / RTGS / IMPS)';
            if (preg_match('/\[Mode:\s*([^\]]+)\]/i', $v->narration ?? '', $pmMatch)) {
                $paymentMode = trim($pmMatch[1]);
            } elseif (stripos($v->narration ?? '', 'cheque') !== false) {
                $paymentMode = 'Cheque';
            } elseif (stripos($v->narration ?? '', 'cash') !== false) {
                $paymentMode = 'Cash';
            } elseif (stripos($v->narration ?? '', 'upi') !== false || stripos($v->narration ?? '', 'online') !== false) {
                $paymentMode = 'UPI / Online Payment';
            }

            // Extract project_id if narration refers to a specific sale number (e.g. #HID-AP-11A-JHAN)
            $voucherProjectId = null;
            if ($v->narration && preg_match('/#([A-Z0-9\-]+)/i', $v->narration, $matches)) {
                $saleNo = trim($matches[1]);
                $saleObj = \App\Models\Sale::where('sale_number', $saleNo)->first();
                if ($saleObj) {
                    $voucherProjectId = $saleObj->project_id;
                }
            }

            // If not matched via narration, check if broker's sales belong to a single project
            if (!$voucherProjectId && $bId) {
                $brokerProjectIds = \App\Models\Brokerage::where('brokerages.broker_id', $bId)
                    ->join('sales', 'brokerages.sale_id', '=', 'sales.id')
                    ->pluck('sales.project_id')
                    ->filter()
                    ->unique();
                if ($brokerProjectIds->count() === 1) {
                    $voucherProjectId = $brokerProjectIds->first();
                }
            }

            $runningLedger->push((object)[
                'id' => 'vouch_' . $v->id,
                'date' => $v->date ? \Carbon\Carbon::parse($v->date)->format('Y-m-d') : $v->created_at->format('Y-m-d'),
                'broker_id' => $bId,
                'broker_name' => $brokerName,
                'project_id' => $voucherProjectId,
                'ref_no' => $v->reference_no ?: $v->voucher_number,
                'description' => $v->narration ?: ("Commission Payout Released to " . $brokerName),
                'payment_mode' => $paymentMode,
                'credit' => 0.0,
                'debit' => $debitAmount,
                'status' => 'paid',
                'company_bank_account_name' => $v->companyBankAccount?->bank_name ?? 'Source Bank Account',
            ]);
        }

        $runningLedger = $runningLedger->sortBy('date')->values();

        $paymentModes = PaymentMode::where('status', 'active')
            ->orderByRaw("CASE WHEN code = 'BANK_TRANSFER' OR name LIKE '%Bank Transfer%' THEN 0 ELSE 1 END, id ASC")
            ->get();

        return view('brokers.payable-report', compact(
            'brokerReports',
            'brokers',
            'projects',
            'runningLedger',
            'totalAccrued',
            'totalPayable',
            'totalPaid',
            'companyBankAccounts',
            'paymentModes'
        ));
    }

    public function recordPayout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_entry_id'     => ['nullable', 'exists:brokerages,id'],
            'broker_id'               => ['nullable', 'exists:brokers,id'],
            'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id'],
            'amount'                  => ['nullable', 'numeric', 'min:0.01'],
            'payment_mode'            => ['nullable', 'string', 'max:100'],
            'reference_no'            => ['required', 'string', 'max:100'],
            'date'                    => ['nullable', 'date'],
            'remarks'                 => ['nullable', 'string', 'max:500'],
        ]);

        $systemId = Auth::user()->system_id;
        $user = Auth::user();
        $count = 0;
        $totalPaid = 0.0;

        try {
            DB::transaction(function () use ($validated, $systemId, $user, &$count, &$totalPaid, $request) {
                $broker = null;
                $narration = '';

                if (!empty($validated['commission_entry_id'])) {
                    $entry = Brokerage::where('id', $validated['commission_entry_id'])
                        ->firstOrFail();

                    if ($entry->broker->system_id !== $systemId) abort(403);

                    $commAmt = (float)$entry->commission_amount;
                    $currentPaid = (float)($entry->paid_amount ?? 0);
                    $maxPayable = max(0.0, $commAmt - $currentPaid);

                    if ($maxPayable <= 0) {
                        throw new \Exception("This commission deal has already been fully paid.");
                    }

                    $payAmount = $request->filled('amount') ? (float)$request->amount : $maxPayable;
                    $payAmount = min($payAmount, $maxPayable);

                    if ($payAmount <= 0) {
                        throw new \Exception("Please enter a valid payout amount.");
                    }

                    $newPaidAmount = $currentPaid + $payAmount;
                    $newStatus = 'payable';
                    if ($newPaidAmount >= $commAmt - 0.01) {
                        $newStatus = 'paid';
                    } elseif ($newPaidAmount > 0) {
                        $newStatus = 'partial';
                    }

                    $entry->update([
                        'paid_amount' => $newPaidAmount,
                        'status'      => $newStatus,
                    ]);

                    $count = 1;
                    $totalPaid = $payAmount;
                    $broker = $entry->broker;
                    $brokerName = $broker->name ?? 'Broker';
                    $statusLabel = $newStatus === 'paid' ? 'Fully Paid' : 'Partially Paid';
                    $narration = "Commission payout of ₹" . number_format($payAmount, 2) . " ({$statusLabel}) to broker '{$brokerName}' for Sale #{$entry->sale?->sale_number}.";

                    ActivityLog::record('broker.payout', $narration);
                } elseif (!empty($validated['broker_id'])) {
                    $broker = Broker::where('system_id', $systemId)->findOrFail($validated['broker_id']);
                    $entries = Brokerage::where('broker_id', $broker->id)
                        ->get();

                    $amountToDistribute = $request->filled('amount') ? (float)$request->amount : 9999999999.0;

                    foreach ($entries as $entry) {
                        $commAmt = (float)$entry->commission_amount;
                        $currentPaid = (float)($entry->paid_amount ?? 0);
                        $remaining = max(0.0, $commAmt - $currentPaid);

                        if ($remaining <= 0) continue;

                        $payForThis = min($amountToDistribute, $remaining);
                        if ($payForThis <= 0) break;

                        $newPaidAmount = $currentPaid + $payForThis;
                        $newStatus = 'payable';
                        if ($newPaidAmount >= $commAmt - 0.01) {
                            $newStatus = 'paid';
                        } elseif ($newPaidAmount > 0) {
                            $newStatus = 'partial';
                        }

                        $entry->update([
                            'paid_amount' => $newPaidAmount,
                            'status'      => $newStatus,
                        ]);

                        $totalPaid += $payForThis;
                        $amountToDistribute -= $payForThis;
                        $count++;

                        if ($amountToDistribute <= 0) break;
                    }

                    if ($count > 0) {
                        $narration = "Commission payout of ₹" . number_format($totalPaid, 2) . " across {$count} deal(s) to broker '{$broker->name}'.";
                        ActivityLog::record('broker.payout', $narration);
                    }
                }

                if ($broker && $totalPaid > 0) {
                    $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail($validated['company_bank_account_id']);
                    $isHistorical = $request->boolean('is_historical');
                    if (!$isHistorical && (float) $bankAccount->current_balance < $totalPaid) {
                        throw new \Exception("Insufficient balance in the selected bank account. Available: " . $bankAccount->formatted_balance);
                    }

                    // Decrement bank balance (skip for historical entries)
                    if (!$isHistorical) {
                        $bankAccount->decrement('current_balance', $totalPaid);
                    }

                    $paymentMode = $request->input('payment_mode', 'Bank Transfer');
                    $customRefNo = $request->input('reference_no');
                    $systemVoucherNo = 'PV-BROKER-' . $broker->id . '-' . time();
                    $payoutDate = $request->input('date') ?: now()->toDateString();
                    $customRemarks = $request->input('remarks');
                    $fullNarration = ($customRemarks ?: ($narration ?: 'Broker commission payout')) . " [Mode: {$paymentMode}]";
                    if ($isHistorical) {
                        $fullNarration .= ' [Historical]';
                    }

                    // Post Payment Voucher to ledger
                    $voucher = \App\Models\Voucher::create([
                        'system_id' => $systemId,
                        'company_bank_account_id' => $bankAccount->id,
                        'voucher_number' => $systemVoucherNo,
                        'type' => 'Payment',
                        'date' => $payoutDate,
                        'narration' => $fullNarration,
                        'reference_no' => $customRefNo ?: $systemVoucherNo,
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

                    // Post JournalVoucher & JournalEntries (Double Entry System)
                    $allBankNames = CompanyBankAccount::pluck('bank_name')->filter()->unique()->implode(' / ');
                    $bankAccountName = 'Bank Balances (' . ($allBankNames ?: 'Karnataka Bank / HDFC Escrow') . ')';
                    $requiredAccounts = [
                    '2003' => ['name' => 'Agent Payable Liability', 'type' => 'LIABILITY'],
                    '4001' => ['name' => 'Agent Payable Expense', 'type' => 'EXPENSE'],
                    '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET']
                    ];
                foreach ($requiredAccounts as $accCode => $accInfo) {
                    ChartOfAccount::firstOrCreate(
                        ['account_code' => $accCode],
                        [
                            'account_name' => $accInfo['name'],
                            'account_type' => $accInfo['type'],
                            'is_active'    => true,
                        ]
                    );
                }
                    $vt = VoucherType::firstOrCreate(
                        ['code' => 'AGENT_PAYMENT'],
                        [
                            'name'        => 'Agent Payment Disbursement',
                            'prefix'      => 'JV-AP',
                            'description' => 'Generated on paying commission to broker',
                            'is_active'   => true,
                        ]
                    );
                    $vtId = $vt->id;

                    $jvNumber = 'JV-AP-' . date('Y') . '-' . str_pad((string)$broker->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string)mt_rand(10, 99), 2, '0', STR_PAD_LEFT);

                    $jvNarration = $narration ?: ("Full brokerage payout to " . ($broker ? $broker->name : 'Agent'));

                    $journalVoucher = JournalVoucher::create([
                        'voucher_no'      => $jvNumber,
                        'voucher_type_id' => $vtId,
                        'voucher_date'    => now()->toDateString(),
                        'reference_id'    => $broker->id,
                        'narration'       => $jvNarration,
                        'is_active'       => true,
                    ]);

                    // 1. Debit Agent Commission Payables (Account 2003)
                    JournalEntry::create([
                        'voucher_id'     => $journalVoucher->id,
                        'account_id'     => '2003',
                        'debit_amount'   => $totalPaid,
                        'credit_amount'  => 0.00,
                        'line_narration' => 'Agent Commission Payables (' . ($broker ? $broker->name : '') . ' Cleared)',
                        'entity_type'    => 'AGENT',
                        'entity_id'      => $broker ? $broker->id : null,
                    ]);

                    // credit Agent expense payables(4001)
                    JournalEntry::create([
                        'voucher_id'     => $journalVoucher->id,
                        'account_id'     => '4001',
                        'debit_amount'   => 0.00,
                        'credit_amount'  => $totalPaid,
                        'line_narration' => 'Agent Expense Payables (' . ($broker ? $broker->name : '') . ' Cleared)',
                        'entity_type'    => 'AGENT',
                        'entity_id'      => $broker ? $broker->id : null,
                    ]);

                    // 2. Credit Bank Account (Karnataka Bank / Selected Bank Asset)
                    $bankAccountCoa = ChartOfAccount::where('account_name', 'LIKE', '%' . $bankAccount->bank_name . '%')
                        ->orWhere('account_code', '1001')
                        ->value('account_code') ?? '1001';

                    JournalEntry::create([
                        'voucher_id'     => $journalVoucher->id,
                        'account_id'     => $bankAccountCoa,
                        'debit_amount'   => 0.00,
                        'credit_amount'  => $totalPaid,
                        'line_narration' => ($bankAccount->bank_name ?? 'Bank Account') . ' (Bank Asset Decreases)',
                        'entity_type'    => 'BANK',
                        'entity_id'      => $bankAccount->id,
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
