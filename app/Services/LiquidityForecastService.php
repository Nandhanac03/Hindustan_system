<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brokerage;
use App\Models\CompanyBankAccount;
use App\Models\CustomerInstallment;
use App\Models\EmiSchedule;
use App\Models\PettyCashBox;
use App\Models\PettyCashTransaction;
use App\Models\Project;
use App\Models\RaBill;
use App\Models\Receipt;
use App\Models\SiteExpense;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LiquidityForecastService
{
    public function getForecastData(array $filters = []): array
    {
        $systemId = Auth::user()->system_id ?? 1;
        $system = System::find($systemId);
        $currencyCode = $system?->currency_code ?: 'INR';
        $currency = $currencyCode === 'INR' ? '₹' : $currencyCode;

        // 1. Projects lookup (from real Projects table)
        $allProjects = Project::where('is_active', true)->orderBy('name')->get(['id', 'name'])->toArray();
        if (empty($allProjects)) {
            $allProjects = Project::orderBy('name')->get(['id', 'name'])->toArray();
        }

        // 2. Base Filters
        $selectedProjectId = (string)($filters['project_id'] ?? ($allProjects[0]['id'] ?? 'all'));
        $asOnDateInput = $filters['as_on_date'] ?? Carbon::now()->format('d/m/Y');
        try {
            $asOnDate = Carbon::createFromFormat('d/m/Y', $asOnDateInput)->startOfDay();
        } catch (\Exception $e) {
            $asOnDate = Carbon::now()->startOfDay();
        }

        $forecastDays = 90;

        // 3. Define Periods (3 buckets)
        $p1Start = (clone $asOnDate);
        $p1End = (clone $asOnDate)->addDays(30)->endOfDay();

        $p2Start = (clone $asOnDate)->addDays(31);
        $p2End = (clone $asOnDate)->addDays(60)->endOfDay();

        $p3Start = (clone $asOnDate)->addDays(61);
        $p3End = (clone $asOnDate)->addDays(90)->endOfDay();

        $p1Label = 'Next 30 Days (Up to ' . $p1End->format('d M') . ')';
        $p2Label = '31 - 60 Days (' . $p2Start->format('d M') . ' - ' . $p2End->format('d M') . ')';
        $p3Label = '61 - 90 Days (' . $p3Start->format('d M') . ' - ' . $p3End->format('d M') . ')';

        // 4. Current Liquid Cash (Real Bank Accounts + Active Petty Cash Boxes)
        $bankAccounts = CompanyBankAccount::orderByDesc('current_balance')->get();
        $bankBalance = (float)$bankAccounts->sum('current_balance');

        $allPettyCashBoxes = PettyCashBox::where('status', 'Active')->get();

        // 5. Fetch all real datasets once from database
        $allRawInstallments = CustomerInstallment::with(['sale.customer', 'sale.project'])
            ->whereNotIn('status', ['paid'])
            ->whereNotNull('due_date')
            ->get();

        $allRawPendingReceipts = Receipt::with(['customer', 'project'])
            ->where('realization_status', 'pending')
            ->get();

        $allRawRaBills = RaBill::where('balance_amount', '>', 0)->get();

        $allRawSiteExpenses = SiteExpense::with(['vendor', 'payee'])
            ->where('balance_amount', '>', 0)
            ->get();

        $allRawEmis = EmiSchedule::with('loan')
            ->whereNotIn('status', ['paid'])
            ->whereNotNull('due_date')
            ->get();

        $allRawBrokerages = Brokerage::with(['sale.project', 'broker'])
            ->where('status', '!=', 'paid')
            ->get();

        $allRawPendingPettyTx = PettyCashTransaction::with('pettyCashBox')
            ->whereNotIn('status', ['approved', 'Posted'])
            ->where('cash_out', '>', 0)
            ->get();

        // 6. Compute datasets for 'all' and for each real project
        $projectDatasets = [];
        $projectKeys = array_merge(['all'], array_map('strval', array_column($allProjects, 'id')));

        foreach ($projectKeys as $projKey) {
            $projectPettyCash = $projKey === 'all'
                ? (float)$allPettyCashBoxes->sum('current_balance')
                : (float)$allPettyCashBoxes->where('project_id', $projKey)->sum('current_balance');

            $currentLiquidCash = $bankBalance + $projectPettyCash;

            $projectDatasets[$projKey] = $this->buildDatasetForProject(
                $projKey,
                $currency,
                $asOnDate,
                $forecastDays,
                $p1End,
                $p2End,
                $p3End,
                $p1Label,
                $p2Label,
                $p3Label,
                $currentLiquidCash,
                $allRawInstallments,
                $allRawPendingReceipts,
                $allRawRaBills,
                $allRawSiteExpenses,
                $allRawEmis,
                $allRawBrokerages,
                $allRawPendingPettyTx
            );
        }

        $activeDataset = $projectDatasets[$selectedProjectId] ?? ($projectDatasets['all'] ?? reset($projectDatasets));

        return array_merge($activeDataset, [
            'currency'          => $currency,
            'asOnDate'          => $asOnDate->format('d/m/Y'),
            'forecastDays'      => $forecastDays,
            'selectedProjectId' => $selectedProjectId,
            'allProjects'       => $allProjects,
            'bankAccounts'      => $bankAccounts,
            'pettyCashBoxes'    => $allPettyCashBoxes,
            'projectDatasets'   => $projectDatasets,
        ]);
    }

    private function buildDatasetForProject(
        string $projKey,
        string $currency,
        Carbon $asOnDate,
        int $forecastDays,
        Carbon $p1End,
        Carbon $p2End,
        Carbon $p3End,
        string $p1Label,
        string $p2Label,
        string $p3Label,
        float $currentLiquidCash,
        $allRawInstallments,
        $allRawPendingReceipts,
        $allRawRaBills,
        $allRawSiteExpenses,
        $allRawEmis,
        $allRawBrokerages,
        $allRawPendingPettyTx
    ): array {
        $allTimeItems = [];

        // 1. Real Inflow: Unpaid Customer Milestone Installments
        $installments = $allRawInstallments;
        if ($projKey !== 'all') {
            $installments = $installments->filter(fn($i) => (string)($i->sale?->project_id ?? '') === $projKey);
        }

        $cMilestonesP1 = 0.0;
        $cMilestonesP2 = 0.0;
        $cMilestonesP3 = 0.0;
        $upcomingCustomerReceipts = [];

        foreach ($installments as $inst) {
            $due = Carbon::parse($inst->due_date);
            $bal = (float)max(0, ($inst->amount - ($inst->paid_amount ?? 0)));
            if ($bal <= 0) {
                continue;
            }

            if ($due <= $p1End) {
                $cMilestonesP1 += $bal;
            } elseif ($due <= $p2End) {
                $cMilestonesP2 += $bal;
            } elseif ($due <= $p3End) {
                $cMilestonesP3 += $bal;
            }

            $item = [
                'source_key'   => 'milestones',
                'project_id'   => (string)($inst->sale?->project_id ?? ''),
                'date'         => $due->format('d/m/Y'),
                'date_raw'     => $due->format('Y-m-d'),
                'type'         => 'Customer Receipt',
                'ref_no'       => 'MS-' . str_pad((string)$inst->id, 4, '0', STR_PAD_LEFT),
                'party_name'   => $inst->sale?->customer?->name ?? 'Customer',
                'description'  => ($inst->label ?: 'Milestone Installment #' . $inst->installment_no) . ($inst->sale?->project?->name ? ' (' . $inst->sale->project->name . ')' : ''),
                'amount'       => $bal,
                'category'     => 'Customer Inflow',
                'flow_type'    => 'inflow',
            ];
            $allTimeItems[] = $item;

            if ($due <= $p3End) {
                $upcomingCustomerReceipts[] = $item;
            }
        }

        // 2. Real Inflow: Pending Receipts / Cheques Awaiting Bank Realization
        $pendingReceipts = $allRawPendingReceipts;
        if ($projKey !== 'all') {
            $pendingReceipts = $pendingReceipts->filter(fn($r) => (string)($r->project_id ?? '') === $projKey);
        }

        $pendingReceiptsP1 = 0.0;
        $pendingReceiptsP2 = 0.0;
        $pendingReceiptsP3 = 0.0;
        $upcomingPendingReceipts = [];

        foreach ($pendingReceipts as $rec) {
            $rDate = Carbon::parse($rec->cheque_date ?: ($rec->receipt_date ?: $rec->created_at));
            $amt = (float)$rec->amount;
            if ($amt <= 0) {
                continue;
            }

            if ($rDate <= $p1End) {
                $pendingReceiptsP1 += $amt;
            } elseif ($rDate <= $p2End) {
                $pendingReceiptsP2 += $amt;
            } elseif ($rDate <= $p3End) {
                $pendingReceiptsP3 += $amt;
            }

            $item = [
                'source_key'   => 'receipts',
                'project_id'   => (string)($rec->project_id ?? ''),
                'date'         => $rDate->format('d/m/Y'),
                'date_raw'     => $rDate->format('Y-m-d'),
                'type'         => 'Customer Receipt',
                'ref_no'       => $rec->reference_no ?: ('REC-' . str_pad((string)$rec->id, 4, '0', STR_PAD_LEFT)),
                'party_name'   => $rec->customer?->name ?? 'Customer',
                'description'  => 'Pending Realization (' . ($rec->payment_mode ?: 'Receipt') . ')' . ($rec->project?->name ? ' - ' . $rec->project->name : ''),
                'amount'       => $amt,
                'category'     => 'Realization Inflow',
                'flow_type'    => 'inflow',
            ];
            $allTimeItems[] = $item;

            if ($rDate <= $p3End) {
                $upcomingPendingReceipts[] = $item;
            }
        }

        $totalInflowP1 = $cMilestonesP1 + $pendingReceiptsP1;
        $totalInflowP2 = $cMilestonesP2 + $pendingReceiptsP2;
        $totalInflowP3 = $cMilestonesP3 + $pendingReceiptsP3;
        $totalProjectedInflows = $totalInflowP1 + $totalInflowP2 + $totalInflowP3;

        // 3. Real Outflow: Contractor RA Bills
        $raBills = $allRawRaBills;
        if ($projKey !== 'all') {
            $raBills = $raBills->filter(fn($ra) => (string)($ra->project_id ?? '') === $projKey);
        }

        $raBillsP1 = 0.0;
        $raBillsP2 = 0.0;
        $raBillsP3 = 0.0;
        $upcomingContractorPayments = [];

        foreach ($raBills as $ra) {
            $raDue = Carbon::parse($ra->due_date ?: ($ra->submit_date ?: $ra->created_at));
            $raBal = (float)$ra->balance_amount;
            if ($raBal <= 0) {
                continue;
            }

            if ($raDue <= $p1End) {
                $raBillsP1 += $raBal;
            } elseif ($raDue <= $p2End) {
                $raBillsP2 += $raBal;
            } elseif ($raDue <= $p3End) {
                $raBillsP3 += $raBal;
            }

            $item = [
                'source_key'   => 'ra_bills',
                'project_id'   => (string)($ra->project_id ?? ''),
                'date'         => $raDue->format('d/m/Y'),
                'date_raw'     => $raDue->format('Y-m-d'),
                'type'         => 'Contractor Payment',
                'ref_no'       => $ra->ra_bill_number ?: ('RA-' . str_pad((string)$ra->id, 4, '0', STR_PAD_LEFT)),
                'party_name'   => $ra->contractor_name ?: 'Contractor',
                'description'  => 'Contractor RA Bill - ' . ($ra->unit_name ?: 'Site Works'),
                'amount'       => $raBal,
                'category'     => 'Contractor Outflow',
                'flow_type'    => 'outflow',
            ];
            $allTimeItems[] = $item;

            if ($raDue <= $p3End) {
                $upcomingContractorPayments[] = $item;
            }
        }

        // 4. Real Outflow: Vendor Material & Site Expense Bills
        $siteExpenses = $allRawSiteExpenses;
        if ($projKey !== 'all') {
            $siteExpenses = $siteExpenses->filter(fn($se) => (string)($se->project_id ?? '') === $projKey);
        }

        $vendorBillsP1 = 0.0;
        $vendorBillsP2 = 0.0;
        $vendorBillsP3 = 0.0;
        $upcomingVendorPayments = [];

        foreach ($siteExpenses as $se) {
            $seDate = Carbon::parse($se->voucher_date ?: $se->created_at);
            $seBal = (float)$se->balance_amount;
            if ($seBal <= 0) {
                continue;
            }

            if ($seDate <= $p1End) {
                $vendorBillsP1 += $seBal;
            } elseif ($seDate <= $p2End) {
                $vendorBillsP2 += $seBal;
            } elseif ($seDate <= $p3End) {
                $vendorBillsP3 += $seBal;
            }

            $partyName = $se->vendor?->name ?: ($se->payee?->name ?: ($se->casual_payee_name ?: 'Vendor'));
            $item = [
                'source_key'   => 'vendor_bills',
                'project_id'   => (string)($se->project_id ?? ''),
                'date'         => $seDate->format('d/m/Y'),
                'date_raw'     => $seDate->format('Y-m-d'),
                'type'         => 'Vendor Payment',
                'ref_no'       => $se->voucher_number ?: ('EXP-' . $se->id),
                'party_name'   => $partyName,
                'description'  => 'Site Expense / Material Supply (' . ($se->expense_category_name ?: 'Materials') . ')',
                'amount'       => $seBal,
                'category'     => 'Vendor Outflow',
                'flow_type'    => 'outflow',
            ];
            $allTimeItems[] = $item;

            if ($seDate <= $p3End) {
                $upcomingVendorPayments[] = $item;
            }
        }

        // 5. Real Outflow: Bank Loan EMIs
        $emis = $allRawEmis;
        if ($projKey !== 'all') {
            $emis = $emis->filter(function ($emi) use ($projKey) {
                $loanProj = $emi->loan?->project_id;
                return $loanProj === null || (string)$loanProj === $projKey;
            });
        }

        $loanEmisP1 = 0.0;
        $loanEmisP2 = 0.0;
        $loanEmisP3 = 0.0;
        $upcomingLoanPayments = [];

        foreach ($emis as $emi) {
            $due = Carbon::parse($emi->due_date);
            $amount = (float)$emi->emi_amount;
            if ($amount <= 0) {
                continue;
            }

            if ($due <= $p1End) {
                $loanEmisP1 += $amount;
            } elseif ($due <= $p2End) {
                $loanEmisP2 += $amount;
            } elseif ($due <= $p3End) {
                $loanEmisP3 += $amount;
            }

            $item = [
                'source_key'   => 'loan_emis',
                'project_id'   => (string)($emi->loan?->project_id ?? ''),
                'date'         => $due->format('d/m/Y'),
                'date_raw'     => $due->format('Y-m-d'),
                'type'         => 'Bank Loan EMI',
                'ref_no'       => 'EMI-' . $due->format('Y-m') . '-' . $emi->installment_no,
                'party_name'   => $emi->loan?->lender_name ?: 'Financing Bank',
                'description'  => 'Project Loan EMI (Loan #' . ($emi->loan?->loan_account_no ?: $emi->loan_id) . ')',
                'amount'       => $amount,
                'category'     => 'Loan Outflow',
                'flow_type'    => 'outflow',
            ];
            $allTimeItems[] = $item;

            if ($due <= $p3End) {
                $upcomingLoanPayments[] = $item;
            }
        }

        // 6. Real Outflow: Brokerage & Commission Payables
        $brokerages = $allRawBrokerages;
        if ($projKey !== 'all') {
            $brokerages = $brokerages->filter(fn($b) => (string)($b->sale?->project_id ?? '') === $projKey);
        }

        $brokerageP1 = 0.0;
        $brokerageP2 = 0.0;
        $brokerageP3 = 0.0;
        $upcomingBrokerages = [];

        foreach ($brokerages as $br) {
            $brBal = (float)max(0, ($br->commission_amount - ($br->paid_amount ?? 0)));
            if ($brBal <= 0) {
                continue;
            }

            $brDate = Carbon::parse($br->created_at);
            if ($brDate <= $p1End) {
                $brokerageP1 += $brBal;
            } elseif ($brDate <= $p2End) {
                $brokerageP2 += $brBal;
            } elseif ($brDate <= $p3End) {
                $brokerageP3 += $brBal;
            }

            $item = [
                'source_key'   => 'brokerage',
                'project_id'   => (string)($br->sale?->project_id ?? ''),
                'date'         => $brDate->format('d/m/Y'),
                'date_raw'     => $brDate->format('Y-m-d'),
                'type'         => 'Brokerage Payout',
                'ref_no'       => 'BRK-' . str_pad((string)$br->id, 4, '0', STR_PAD_LEFT),
                'party_name'   => $br->broker?->name ?? 'Broker',
                'description'  => 'Commission Payable (Sale #' . ($br->sale?->sale_number ?: $br->sale_id) . ')',
                'amount'       => $brBal,
                'category'     => 'Brokerage Outflow',
                'flow_type'    => 'outflow',
            ];
            $allTimeItems[] = $item;

            if ($brDate <= $p3End) {
                $upcomingBrokerages[] = $item;
            }
        }

        // 7. Real Outflow: Unposted / Pending Petty Cash Transactions
        $pendingPetty = $allRawPendingPettyTx;
        if ($projKey !== 'all') {
            $pendingPetty = $pendingPetty->filter(fn($pt) => (string)($pt->pettyCashBox?->project_id ?? '') === $projKey);
        }

        $pettyCashP1 = 0.0;
        $pettyCashP2 = 0.0;
        $pettyCashP3 = 0.0;
        $upcomingPettyTx = [];

        foreach ($pendingPetty as $pt) {
            $ptBal = (float)$pt->cash_out;
            if ($ptBal <= 0) {
                continue;
            }
            $ptDate = Carbon::parse($pt->transaction_date ?: $pt->created_at);
            if ($ptDate <= $p1End) {
                $pettyCashP1 += $ptBal;
            } elseif ($ptDate <= $p2End) {
                $pettyCashP2 += $ptBal;
            } elseif ($ptDate <= $p3End) {
                $pettyCashP3 += $ptBal;
            }

            $item = [
                'source_key'   => 'petty_cash',
                'project_id'   => (string)($pt->pettyCashBox?->project_id ?? ''),
                'date'         => $ptDate->format('d/m/Y'),
                'date_raw'     => $ptDate->format('Y-m-d'),
                'type'         => 'Site Expense',
                'ref_no'       => $pt->voucher_number ?: ('PC-' . $pt->id),
                'party_name'   => $pt->pettyCashBox?->box_name ?: 'Petty Cash Box',
                'description'  => $pt->narration ?: 'Pending Petty Cash Expense',
                'amount'       => $ptBal,
                'category'     => 'Site Expense',
                'flow_type'    => 'outflow',
            ];
            $allTimeItems[] = $item;

            if ($ptDate <= $p3End) {
                $upcomingPettyTx[] = $item;
            }
        }

        // Total Outflows by Period
        $totalOutflowP1 = $raBillsP1 + $vendorBillsP1 + $loanEmisP1 + $brokerageP1 + $pettyCashP1;
        $totalOutflowP2 = $raBillsP2 + $vendorBillsP2 + $loanEmisP2 + $brokerageP2 + $pettyCashP2;
        $totalOutflowP3 = $raBillsP3 + $vendorBillsP3 + $loanEmisP3 + $brokerageP3 + $pettyCashP3;
        $totalProjectedOutflows = $totalOutflowP1 + $totalOutflowP2 + $totalOutflowP3;

        // Net Cash Flows & Closing Balances
        $netCashFlowP1 = $totalInflowP1 - $totalOutflowP1;
        $netCashFlowP2 = $totalInflowP2 - $totalOutflowP2;
        $netCashFlowP3 = $totalInflowP3 - $totalOutflowP3;
        $totalNetCashFlow = $totalProjectedInflows - $totalProjectedOutflows;

        $closingBalanceCurrent = $currentLiquidCash;
        $closingBalanceP1 = $closingBalanceCurrent + $netCashFlowP1;
        $closingBalanceP2 = $closingBalanceP1 + $netCashFlowP2;
        $closingBalanceP3 = $closingBalanceP2 + $netCashFlowP3;

        $netLiquidity = $closingBalanceP3;

        // Outflow Category Breakdown (100% Real Figures)
        $totalRa = $raBillsP1 + $raBillsP2 + $raBillsP3;
        $totalVendor = $vendorBillsP1 + $vendorBillsP2 + $vendorBillsP3;
        $totalLoans = $loanEmisP1 + $loanEmisP2 + $loanEmisP3;
        $totalBrokerage = $brokerageP1 + $brokerageP2 + $brokerageP3;
        $totalPetty = $pettyCashP1 + $pettyCashP2 + $pettyCashP3;

        $catDistribution = [
            [
                'name'       => 'Contractor RA Bills',
                'amount'     => $totalRa,
                'percentage' => $totalProjectedOutflows > 0 ? round(($totalRa / $totalProjectedOutflows) * 100, 1) : 0,
                'color'      => '#F97316',
            ],
            [
                'name'       => 'Bank Loan EMIs',
                'amount'     => $totalLoans,
                'percentage' => $totalProjectedOutflows > 0 ? round(($totalLoans / $totalProjectedOutflows) * 100, 1) : 0,
                'color'      => '#10B981',
            ],
            [
                'name'       => 'Brokerage Commissions',
                'amount'     => $totalBrokerage,
                'percentage' => $totalProjectedOutflows > 0 ? round(($totalBrokerage / $totalProjectedOutflows) * 100, 1) : 0,
                'color'      => '#8B5CF6',
            ],
            [
                'name'       => 'Vendor Material Bills',
                'amount'     => $totalVendor,
                'percentage' => $totalProjectedOutflows > 0 ? round(($totalVendor / $totalProjectedOutflows) * 100, 1) : 0,
                'color'      => '#3B82F6',
            ],
            [
                'name'       => 'Pending Petty Cash Expenses',
                'amount'     => $totalPetty,
                'percentage' => $totalProjectedOutflows > 0 ? round(($totalPetty / $totalProjectedOutflows) * 100, 1) : 0,
                'color'      => '#EC4899',
            ],
        ];

        // Dynamic Real Key Insights
        $peakOutflowPeriod = 'Next 30 Days';
        if ($totalOutflowP2 > $totalOutflowP1 && $totalOutflowP2 >= $totalOutflowP3) {
            $peakOutflowPeriod = '31 - 60 Days';
        } elseif ($totalOutflowP3 > $totalOutflowP1 && $totalOutflowP3 > $totalOutflowP2) {
            $peakOutflowPeriod = '61 - 90 Days';
        }

        $totalPendingReceipts = $pendingReceiptsP1 + $pendingReceiptsP2 + $pendingReceiptsP3;

        $keyInsights = [
            $netLiquidity >= 0 
                ? 'Projected ending cash reserve stands at ' . $currency . ' ' . number_format($netLiquidity, 2) . ' (' . ($totalNetCashFlow >= 0 ? 'Net Surplus' : 'Net Deficit') . ').'
                : 'Attention needed: Projected deficit of ' . $currency . ' ' . number_format(abs($netLiquidity), 2) . '; collection acceleration recommended.',
            'Highest scheduled outflow concentration falls in ' . $peakOutflowPeriod . ' (' . $currency . ' ' . number_format(max($totalOutflowP1, $totalOutflowP2, $totalOutflowP3), 2) . ').',
            'Pending cheque / receipt realizations total ' . $currency . ' ' . number_format($totalPendingReceipts, 2) . ' awaiting bank clearing.',
            'Total contractor RA payables stand at ' . $currency . ' ' . number_format($totalRa, 2) . ' and unpaid brokerage commissions total ' . $currency . ' ' . number_format($totalBrokerage, 2) . '.',
            'Scheduled bank loan EMI obligations over the next 90 days total ' . $currency . ' ' . number_format($totalLoans, 2) . '.'
        ];

        // Combined Upcoming Key Transactions Table (100% real records)
        $allUpcoming = array_merge(
            $upcomingCustomerReceipts,
            $upcomingPendingReceipts,
            $upcomingContractorPayments,
            $upcomingVendorPayments,
            $upcomingLoanPayments,
            $upcomingBrokerages,
            $upcomingPettyTx
        );

        usort($allUpcoming, fn($a, $b) => strcmp($a['date_raw'], $b['date_raw']));
        usort($allTimeItems, fn($a, $b) => strcmp($a['date_raw'], $b['date_raw']));

        return [
            'periods'                   => [
                'p1Label' => $p1Label,
                'p2Label' => $p2Label,
                'p3Label' => $p3Label,
            ],
            'kpis'                      => [
                'current_liquid_cash'      => $currentLiquidCash,
                'total_projected_inflows'  => $totalProjectedInflows,
                'total_projected_outflows' => $totalProjectedOutflows,
                'net_liquidity'            => $netLiquidity,
                'total_net_cash_flow'      => $totalNetCashFlow,
                'status'                   => $netLiquidity >= 0 ? 'Surplus' : 'Deficit',
            ],
            'summaryTable'              => [
                'inflows' => [
                    [
                        'name'  => 'Customer Milestone Payments (Installments)',
                        'p1'    => $cMilestonesP1,
                        'p2'    => $cMilestonesP2,
                        'p3'    => $cMilestonesP3,
                        'total' => $cMilestonesP1 + $cMilestonesP2 + $cMilestonesP3,
                    ],
                    [
                        'name'  => 'Pending Cheque / Receipt Realizations',
                        'p1'    => $pendingReceiptsP1,
                        'p2'    => $pendingReceiptsP2,
                        'p3'    => $pendingReceiptsP3,
                        'total' => $totalPendingReceipts,
                    ],
                ],
                'total_inflows' => [
                    'p1'    => $totalInflowP1,
                    'p2'    => $totalInflowP2,
                    'p3'    => $totalInflowP3,
                    'total' => $totalProjectedInflows,
                ],
                'outflows' => [
                    [
                        'name'  => 'Contractor RA Bills Payables',
                        'p1'    => $raBillsP1,
                        'p2'    => $raBillsP2,
                        'p3'    => $raBillsP3,
                        'total' => $totalRa,
                    ],
                    [
                        'name'  => 'Vendor & Site Expense Payables',
                        'p1'    => $vendorBillsP1,
                        'p2'    => $vendorBillsP2,
                        'p3'    => $vendorBillsP3,
                        'total' => $totalVendor,
                    ],
                    [
                        'name'  => 'Bank Loan EMIs & Interest Schedules',
                        'p1'    => $loanEmisP1,
                        'p2'    => $loanEmisP2,
                        'p3'    => $loanEmisP3,
                        'total' => $totalLoans,
                    ],
                    [
                        'name'  => 'Brokerage & Commission Payables',
                        'p1'    => $brokerageP1,
                        'p2'    => $brokerageP2,
                        'p3'    => $brokerageP3,
                        'total' => $totalBrokerage,
                    ],
                    [
                        'name'  => 'Pending Petty Cash Expenses',
                        'p1'    => $pettyCashP1,
                        'p2'    => $pettyCashP2,
                        'p3'    => $pettyCashP3,
                        'total' => $totalPetty,
                    ],
                ],
                'total_outflows' => [
                    'p1'    => $totalOutflowP1,
                    'p2'    => $totalOutflowP2,
                    'p3'    => $totalOutflowP3,
                    'total' => $totalProjectedOutflows,
                ],
                'net_cash_flow' => [
                    'p1'    => $netCashFlowP1,
                    'p2'    => $netCashFlowP2,
                    'p3'    => $netCashFlowP3,
                    'total' => $totalNetCashFlow,
                ]
            ],
            'chartData'                 => [
                'categories'       => ['Current Balance', 'Next 30 Days', '31 - 60 Days', '61 - 90 Days'],
                'inflows'          => [0, round($totalInflowP1, 2), round($totalInflowP2, 2), round($totalInflowP3, 2)],
                'outflows'         => [0, round($totalOutflowP1, 2), round($totalOutflowP2, 2), round($totalOutflowP3, 2)],
                'closing_balances' => [
                    round($closingBalanceCurrent, 2),
                    round($closingBalanceP1, 2),
                    round($closingBalanceP2, 2),
                    round($closingBalanceP3, 2),
                ],
                'closing_bars'     => [
                    round($closingBalanceCurrent, 2),
                    round($closingBalanceP1, 2),
                    round($closingBalanceP2, 2),
                    round($closingBalanceP3, 2),
                ],
            ],
            'categoryOutflowBreakdown'  => $catDistribution,
            'keyInsights'               => $keyInsights,
            'upcomingTransactions'      => $allUpcoming,
            'allTimeTransactions'       => $allTimeItems,
        ];
    }
}
