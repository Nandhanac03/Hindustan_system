<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Brokerage;
use App\Models\CompanyBankAccount;
use App\Models\CustomerInstallment;
use App\Models\EmiSchedule;
use App\Models\Employee;
use App\Models\PettyCashBox;
use App\Models\PettyCashTransaction;
use App\Models\Project;
use App\Models\RaBill;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\SiteExpense;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // 3. Strict Forward-Looking Period Boundaries from $asOnDate (Requirement #1 & #9)
        $w1Start = (clone $asOnDate);
        $w1End   = (clone $asOnDate)->addDays(6)->endOfDay();   // Days 0 - 6

        $w2Start = (clone $asOnDate)->addDays(7);
        $w2End   = (clone $asOnDate)->addDays(13)->endOfDay();  // Days 7 - 13

        $w3Start = (clone $asOnDate)->addDays(14);
        $w3End   = (clone $asOnDate)->addDays(20)->endOfDay();  // Days 14 - 20

        $w4Start = (clone $asOnDate)->addDays(21);
        $p1End   = (clone $asOnDate)->addDays(30)->endOfDay();  // Days 21 - 30 (end of P1)

        $p2Start = (clone $asOnDate)->addDays(31);
        $p2End   = (clone $asOnDate)->addDays(60)->endOfDay();  // Days 31 - 60

        $p3Start = (clone $asOnDate)->addDays(61);
        $p3End   = (clone $asOnDate)->addDays(90)->endOfDay();  // Days 61 - 90

        $periods = [
            'overdueLabel' => 'Overdue / Past Due (< ' . $asOnDate->format('d M') . ')',
            'p1Label'      => 'Next 30 Days (' . $asOnDate->format('d M') . ' - ' . $p1End->format('d M') . ')',
            'p2Label'      => '31 - 60 Days (' . $p2Start->format('d M') . ' - ' . $p2End->format('d M') . ')',
            'p3Label'      => '61 - 90 Days (' . $p3Start->format('d M') . ' - ' . $p3End->format('d M') . ')',
            'w1Label'      => 'Week 1 (' . $w1Start->format('d M') . ' - ' . $w1End->format('d M') . ')',
            'w2Label'      => 'Week 2 (' . $w2Start->format('d M') . ' - ' . $w2End->format('d M') . ')',
            'w3Label'      => 'Week 3 (' . $w3Start->format('d M') . ' - ' . $w3End->format('d M') . ')',
            'w4Label'      => 'Week 4 (' . $w4Start->format('d M') . ' - ' . $p1End->format('d M') . ')',
        ];

        // 4. Current Liquid Cash: Split Free Cash vs RERA/Escrow Restricted Funds (Requirement #5)
        $bankAccounts = CompanyBankAccount::orderByDesc('current_balance')->get();
        $freeBankCash = 0.0;
        $restrictedEscrowCash = 0.0;

        foreach ($bankAccounts as $acc) {
            if ($this->isTestRecord($acc->account_name, $acc->bank_name, $acc->account_number)) {
                continue;
            }
            $bal = (float)($acc->current_balance ?? 0);
            $accDescriptor = ($acc->account_type ?? '') . ' ' . ($acc->account_name ?? '') . ' ' . ($acc->bank_name ?? '');
            if (preg_match('/\b(rera|escrow|restricted)\b/i', $accDescriptor)) {
                $restrictedEscrowCash += $bal;
            } else {
                $freeBankCash += $bal;
            }
        }

        $allPettyCashBoxes = PettyCashBox::where('status', 'Active')->get();

        // 5. Fetch All Real Datasets Once
        $allRawInstallments = CustomerInstallment::with(['sale.customer', 'sale.project'])
            ->whereNotIn('status', ['paid'])
            ->whereNotNull('due_date')
            ->get();

        // Only Pending Cheques — NOT Cash receipts (Requirement #3)
        $allRawPendingCheques = Receipt::with(['customer', 'project'])
            ->where('realization_status', 'pending')
            ->where(function ($q) {
                $q->where('payment_mode', 'like', '%cheque%')
                  ->orWhere('payment_mode', 'like', '%chq%')
                  ->orWhere('payment_mode', 'like', '%pdc%');
            })
            ->get();

        $allRawRaBills = RaBill::where('balance_amount', '>', 0)->get();

        $allRawSiteExpenses = SiteExpense::with(['vendor', 'payee'])
            ->where('balance_amount', '>', 0)
            ->get();

        $allRawEmis = EmiSchedule::with('loan')
            ->whereNotIn('status', ['paid'])
            ->whereNotNull('due_date')
            ->get();

        $allRawBrokerages = Brokerage::with(['sale.project', 'sale.customer', 'broker'])
            ->where('status', '!=', 'paid')
            ->get();

        // Customer Refunds from Returns & Cancellations (Requirement #6)
        $allRawCancelledSales = Sale::with(['customer', 'project'])
            ->whereIn('status', ['cancelled', 'returned', 'refund_pending'])
            ->where('refund_amount', '>', 0)
            ->get();

        $refundsPaidBySale = $this->getPaidRefundsBySale();

        // Payroll / Active Employees (Requirement #6)
        $allEmployees = Employee::where('status', 'active')
            ->whereNull('deleted_at')
            ->where('salary', '>', 0)
            ->get();

        // GST / Statutory Dues from Real Sales & Expenses (Requirement #6)
        $allTaxableSales = Sale::with(['customer', 'project'])
            ->where('status', 'active')
            ->where('gst_amount', '>', 0)
            ->get();
        $totalInputGstCredit = (float)SiteExpense::sum('total_gst_amount');

        // Unapproved/Unposted Petty Cash Expenses
        $allRawPendingPettyTx = PettyCashTransaction::with('pettyCashBox')
            ->whereNotIn('status', ['approved', 'Posted'])
            ->where('cash_out', '>', 0)
            ->get();

        // 6. Build Datasets Per Project
        $projectDatasets = [];
        $projectKeys = array_merge(['all'], array_map('strval', array_column($allProjects, 'id')));

        foreach ($projectKeys as $projKey) {
            $projectPettyCash = $projKey === 'all'
                ? (float)$allPettyCashBoxes->sum('current_balance')
                : (float)$allPettyCashBoxes->where('project_id', $projKey)->sum('current_balance');

            $freeCash = $freeBankCash + $projectPettyCash;
            $totalLiquidCash = $freeCash + $restrictedEscrowCash;

            $projectDatasets[$projKey] = $this->buildDatasetForProject(
                $projKey,
                $currency,
                $asOnDate,
                $forecastDays,
                $w1End,
                $w2End,
                $w3End,
                $p1End,
                $p2End,
                $p3End,
                $periods,
                $freeCash,
                $restrictedEscrowCash,
                $totalLiquidCash,
                $allRawInstallments,
                $allRawPendingCheques,
                $allRawRaBills,
                $allRawSiteExpenses,
                $allRawEmis,
                $allRawBrokerages,
                $allRawCancelledSales,
                $refundsPaidBySale,
                $allEmployees,
                $allTaxableSales,
                $totalInputGstCredit,
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

    /**
     * Exclude test / dummy records by inspecting names, references, and notes (Requirement #4).
     */
    private function isTestRecord(?string ...$fields): bool
    {
        foreach ($fields as $val) {
            if ($val === null || trim($val) === '') {
                continue;
            }
            if (preg_match('/\b(test|testing|demo|dummy|sample|sddf|chq12)\b/i', $val)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Classify a Carbon date strictly into Overdue (< asOnDate) or future forecast buckets (Next 30, 31-60, 61-90).
     */
    private function classifyDate(
        Carbon $dt,
        Carbon $asOnDate,
        Carbon $w1End,
        Carbon $w2End,
        Carbon $w3End,
        Carbon $p1End,
        Carbon $p2End,
        Carbon $p3End
    ): ?array {
        $dateStart = (clone $dt)->startOfDay();
        if ($dateStart->lt($asOnDate)) {
            return ['bucket' => 'overdue', 'week' => null, 'is_future' => false];
        }
        if ($dateStart->lte($p1End)) {
            $week = 'w4';
            if ($dateStart->lte($w1End)) {
                $week = 'w1';
            } elseif ($dateStart->lte($w2End)) {
                $week = 'w2';
            } elseif ($dateStart->lte($w3End)) {
                $week = 'w3';
            }
            return ['bucket' => 'p1', 'week' => $week, 'is_future' => true];
        }
        if ($dateStart->lte($p2End)) {
            return ['bucket' => 'p2', 'week' => null, 'is_future' => true];
        }
        if ($dateStart->lte($p3End)) {
            return ['bucket' => 'p3', 'week' => null, 'is_future' => true];
        }
        return null; // Beyond 90-day forecast horizon
    }

    /**
     * Initialize empty bucket accumulator.
     */
    private function emptyBucket(): array
    {
        return [
            'overdue' => 0.0,
            'p1'      => 0.0,
            'p2'      => 0.0,
            'p3'      => 0.0,
            'w1'      => 0.0,
            'w2'      => 0.0,
            'w3'      => 0.0,
            'w4'      => 0.0,
            'total'   => 0.0, // Forward-looking 90-day total (p1 + p2 + p3)
        ];
    }

    private function addToBucket(array &$bucketAcc, array $loc, float $amount): void
    {
        $b = $loc['bucket'];
        $bucketAcc[$b] += $amount;
        if ($loc['week'] !== null) {
            $bucketAcc[$loc['week']] += $amount;
        }
        if ($loc['is_future']) {
            $bucketAcc['total'] += $amount;
        }
    }

    /**
     * Aggregate already-paid cancellation refunds per sale ID from Posted vouchers.
     */
    private function getPaidRefundsBySale(): array
    {
        $postedVouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->pluck('reference_no');

        $paidMap = [];
        foreach ($postedVouchers as $refNo) {
            $refData = json_decode($refNo, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                        $sId = (int)$alloc['target_id'];
                        $paidMap[$sId] = ($paidMap[$sId] ?? 0.0) + (float)($alloc['amount'] ?? 0);
                    }
                }
            }
        }
        return $paidMap;
    }

    private function buildDatasetForProject(
        string $projKey,
        string $currency,
        Carbon $asOnDate,
        int $forecastDays,
        Carbon $w1End,
        Carbon $w2End,
        Carbon $w3End,
        Carbon $p1End,
        Carbon $p2End,
        Carbon $p3End,
        array $periods,
        float $freeCash,
        float $restrictedEscrowCash,
        float $totalLiquidCash,
        $allRawInstallments,
        $allRawPendingCheques,
        $allRawRaBills,
        $allRawSiteExpenses,
        $allRawEmis,
        $allRawBrokerages,
        $allRawCancelledSales,
        array $refundsPaidBySale,
        $allEmployees,
        $allTaxableSales,
        float $totalInputGstCredit,
        $allRawPendingPettyTx
    ): array {
        $allItems = [];

        // Initialize accumulators
        $bMilestones = $this->emptyBucket();
        $bCheques    = $this->emptyBucket();

        $bRaBills    = $this->emptyBucket();
        $bVendor     = $this->emptyBucket();
        $bEmis       = $this->emptyBucket();
        $bBrokerage  = $this->emptyBucket();
        $bRefunds    = $this->emptyBucket();
        $bPayroll    = $this->emptyBucket();
        $bStatutory  = $this->emptyBucket();
        $bLandAppr   = $this->emptyBucket();
        $bPetty      = $this->emptyBucket();

        // ── 1. INFLOW: Customer Milestone Payments ──
        $installments = $projKey === 'all'
            ? $allRawInstallments
            : $allRawInstallments->filter(fn($i) => (string)($i->sale?->project_id ?? '') === $projKey);

        foreach ($installments as $inst) {
            $custName = $inst->sale?->customer?->name ?? 'Customer';
            if ($this->isTestRecord($custName, $inst->label, $inst->sale?->sale_number)) {
                continue;
            }
            $bal = (float)max(0, ($inst->amount - ($inst->paid_amount ?? 0)));
            if ($bal <= 0) {
                continue;
            }
            $due = Carbon::parse($inst->due_date);
            $loc = $this->classifyDate($due, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bMilestones, $loc, $bal);
            $allItems[] = [
                'row_key'     => 'inflow_milestones',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($inst->sale?->project_id ?? ''),
                'date'        => $due->format('d/m/Y'),
                'date_raw'    => $due->format('Y-m-d'),
                'type'        => 'Customer Receipt',
                'flow_label'  => 'Customer Milestone (Inflow)',
                'ref_no'      => 'MS-' . str_pad((string)$inst->id, 4, '0', STR_PAD_LEFT),
                'party_name'  => $custName,
                'description' => ($inst->label ?: 'Milestone Installment #' . $inst->installment_no) . ($inst->sale?->project?->name ? ' - ' . $inst->sale->project->name : ''),
                'amount'      => $bal,
                'flow_type'   => 'inflow',
            ];
        }

        // ── 2. INFLOW: Pending Cheque Realizations ONLY (Cash receipts excluded - Requirement #3) ──
        $cheques = $projKey === 'all'
            ? $allRawPendingCheques
            : $allRawPendingCheques->filter(fn($r) => (string)($r->project_id ?? '') === $projKey);

        foreach ($cheques as $rec) {
            $custName = $rec->customer?->name ?? 'Customer';
            if ($this->isTestRecord($custName, $rec->reference_no, $rec->remarks)) {
                continue;
            }
            $amt = (float)$rec->amount;
            if ($amt <= 0) {
                continue;
            }
            $rDate = Carbon::parse($rec->cheque_date ?: ($rec->receipt_date ?: $rec->created_at));
            $loc = $this->classifyDate($rDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bCheques, $loc, $amt);
            $allItems[] = [
                'row_key'     => 'inflow_cheques',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($rec->project_id ?? ''),
                'date'        => $rDate->format('d/m/Y'),
                'date_raw'    => $rDate->format('Y-m-d'),
                'type'        => 'Pending Cheque',
                'flow_label'  => 'Cheque Clearing (Inflow)',
                'ref_no'      => $rec->reference_no ?: ('CHQ-' . str_pad((string)$rec->id, 4, '0', STR_PAD_LEFT)),
                'party_name'  => $custName,
                'description' => 'Pending Cheque Realization' . ($rec->drawee_bank ? ' (' . $rec->drawee_bank . ')' : ''),
                'amount'      => $amt,
                'flow_type'   => 'inflow',
            ];
        }

        // ── 3. OUTFLOW: Contractor RA Bills ──
        $raBills = $projKey === 'all'
            ? $allRawRaBills
            : $allRawRaBills->filter(fn($ra) => (string)($ra->project_id ?? '') === $projKey);

        foreach ($raBills as $ra) {
            $cName = $ra->contractor_name ?: 'Contractor';
            if ($this->isTestRecord($cName, $ra->ra_bill_number, $ra->remarks)) {
                continue;
            }
            $raBal = (float)$ra->balance_amount;
            if ($raBal <= 0) {
                continue;
            }
            $raDue = Carbon::parse($ra->due_date ?: ($ra->submit_date ?: $ra->created_at));
            $loc = $this->classifyDate($raDue, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bRaBills, $loc, $raBal);
            $allItems[] = [
                'row_key'     => 'outflow_ra',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($ra->project_id ?? ''),
                'date'        => $raDue->format('d/m/Y'),
                'date_raw'    => $raDue->format('Y-m-d'),
                'type'        => 'Contractor Payment',
                'flow_label'  => 'Contractor RA Bill (Outflow)',
                'ref_no'      => $ra->ra_bill_number ?: ('RA-' . str_pad((string)$ra->id, 4, '0', STR_PAD_LEFT)),
                'party_name'  => $cName,
                'description' => 'Contractor RA Bill - ' . ($ra->unit_name ?: 'Site Works'),
                'amount'      => $raBal,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 4. OUTFLOW: Vendor Bills vs Statutory vs Land & Approvals (SiteExpense) ──
        $siteExpenses = $projKey === 'all'
            ? $allRawSiteExpenses
            : $allRawSiteExpenses->filter(fn($se) => (string)($se->project_id ?? '') === $projKey);

        foreach ($siteExpenses as $se) {
            $partyName = $se->vendor?->name ?: ($se->payee?->name ?: ($se->casual_payee_name ?: 'Vendor'));
            if ($this->isTestRecord($partyName, $se->voucher_number, $se->narration)) {
                continue;
            }
            $seBal = (float)$se->balance_amount;
            if ($seBal <= 0) {
                continue;
            }
            $seDate = Carbon::parse($se->voucher_date ?: $se->created_at);
            $loc = $this->classifyDate($seDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }

            $catText = ($se->expense_category_name ?? '') . ' ' . ($se->narration ?? '');
            if (preg_match('/\b(land|approval|permit|license|sanction|municipal|rera|plan)\b/i', $catText)) {
                $rowKey = 'outflow_land';
                $type = 'Land & Approval';
                $flowLabel = 'Land & Approval (Outflow)';
                $this->addToBucket($bLandAppr, $loc, $seBal);
            } elseif (preg_match('/\b(gst|tds|tax|statutory|pf|esi|challan)\b/i', $catText)) {
                $rowKey = 'outflow_gst';
                $type = 'GST / Statutory';
                $flowLabel = 'Statutory Tax (Outflow)';
                $this->addToBucket($bStatutory, $loc, $seBal);
            } else {
                $rowKey = 'outflow_vendor';
                $type = 'Vendor Payment';
                $flowLabel = 'Vendor Payable (Outflow)';
                $this->addToBucket($bVendor, $loc, $seBal);
            }

            $allItems[] = [
                'row_key'     => $rowKey,
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($se->project_id ?? ''),
                'date'        => $seDate->format('d/m/Y'),
                'date_raw'    => $seDate->format('Y-m-d'),
                'type'        => $type,
                'flow_label'  => $flowLabel,
                'ref_no'      => $se->voucher_number ?: ('EXP-' . $se->id),
                'party_name'  => $partyName,
                'description' => ($se->expense_category_name ?: 'Site Expense') . ($se->narration ? ' - ' . $se->narration : ''),
                'amount'      => $seBal,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 5. OUTFLOW: Bank Loan EMIs ──
        $emis = $projKey === 'all'
            ? $allRawEmis
            : $allRawEmis->filter(function ($emi) use ($projKey) {
                $loanProj = $emi->loan?->project_id;
                return $loanProj === null || (string)$loanProj === $projKey;
            });

        foreach ($emis as $emi) {
            $lender = $emi->loan?->lender_name ?: 'Financing Bank';
            if ($this->isTestRecord($lender, $emi->loan?->loan_account_no)) {
                continue;
            }
            $amount = (float)$emi->emi_amount;
            if ($amount <= 0) {
                continue;
            }
            $due = Carbon::parse($emi->due_date);
            $loc = $this->classifyDate($due, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bEmis, $loc, $amount);
            $allItems[] = [
                'row_key'     => 'outflow_emi',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($emi->loan?->project_id ?? ''),
                'date'        => $due->format('d/m/Y'),
                'date_raw'    => $due->format('Y-m-d'),
                'type'        => 'Bank Loan EMI',
                'flow_label'  => 'Loan EMI (Outflow)',
                'ref_no'      => 'EMI-' . $due->format('Y-m') . '-' . $emi->installment_no,
                'party_name'  => $lender,
                'description' => 'Project Loan EMI (Loan #' . ($emi->loan?->loan_account_no ?: $emi->loan_id) . ')',
                'amount'      => $amount,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 6. OUTFLOW: Brokerage & Commission Payables ──
        $brokerages = $projKey === 'all'
            ? $allRawBrokerages
            : $allRawBrokerages->filter(fn($b) => (string)($b->sale?->project_id ?? '') === $projKey);

        foreach ($brokerages as $br) {
            $bName = $br->broker?->name ?? 'Broker';
            $cName = $br->sale?->customer?->name ?? '';
            if ($this->isTestRecord($bName, $cName, $br->sale?->sale_number)) {
                continue;
            }
            $brBal = (float)max(0, ($br->commission_amount - ($br->paid_amount ?? 0)));
            if ($brBal <= 0) {
                continue;
            }
            $brDate = Carbon::parse($br->created_at);
            $loc = $this->classifyDate($brDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bBrokerage, $loc, $brBal);
            $allItems[] = [
                'row_key'     => 'outflow_brokerage',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($br->sale?->project_id ?? ''),
                'date'        => $brDate->format('d/m/Y'),
                'date_raw'    => $brDate->format('Y-m-d'),
                'type'        => 'Brokerage Payout',
                'flow_label'  => 'Brokerage Commission (Outflow)',
                'ref_no'      => 'BRK-' . str_pad((string)$br->id, 4, '0', STR_PAD_LEFT),
                'party_name'  => $bName,
                'description' => 'Commission Payable (Sale #' . ($br->sale?->sale_number ?: $br->sale_id) . ')',
                'amount'      => $brBal,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 7. OUTFLOW: Customer Refunds (Returns & Cancellations) (Requirement #6) ──
        $cancelledSales = $projKey === 'all'
            ? $allRawCancelledSales
            : $allRawCancelledSales->filter(fn($s) => (string)($s->project_id ?? '') === $projKey);

        foreach ($cancelledSales as $cs) {
            $cName = $cs->customer?->name ?? 'Customer';
            if ($this->isTestRecord($cName, $cs->sale_number, $cs->cancellation_reason)) {
                continue;
            }
            $paidRefund = $refundsPaidBySale[$cs->id] ?? 0.0;
            $remRefund = max(0.0, (float)$cs->refund_amount - $paidRefund);
            if ($remRefund <= 0.01) {
                continue;
            }
            $cDate = Carbon::parse($cs->cancelled_at ?: $cs->updated_at);
            $loc = $this->classifyDate($cDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bRefunds, $loc, $remRefund);
            $allItems[] = [
                'row_key'     => 'outflow_refunds',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($cs->project_id ?? ''),
                'date'        => $cDate->format('d/m/Y'),
                'date_raw'    => $cDate->format('Y-m-d'),
                'type'        => 'Customer Refund',
                'flow_label'  => 'Cancellation Refund (Outflow)',
                'ref_no'      => $cs->sale_number ?: ('REF-' . $cs->id),
                'party_name'  => $cName,
                'description' => 'Pending Refund (' . ucfirst($cs->status) . ') - ' . ($cs->cancellation_reason ?: 'Cancellation'),
                'amount'      => $remRefund,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 8. OUTFLOW: Payroll & Employee Salaries (Requirement #6) ──
        $activeEmployees = $allEmployees->filter(fn($e) => !$this->isTestRecord($e->name, $e->employee_id));
        $monthlyPayroll = (float)$activeEmployees->sum('salary');
        if ($monthlyPayroll > 0) {
            // Schedule monthly payroll at end of each calendar month inside [asOnDate, p3End]
            for ($m = 0; $m <= 3; $m++) {
                $payDate = (clone $asOnDate)->startOfMonth()->addMonths($m)->endOfMonth()->startOfDay();
                if ($payDate->lt($asOnDate) || $payDate->gt($p3End)) {
                    continue;
                }
                $loc = $this->classifyDate($payDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
                if ($loc === null) {
                    continue;
                }
                $this->addToBucket($bPayroll, $loc, $monthlyPayroll);
                $allItems[] = [
                    'row_key'     => 'outflow_payroll',
                    'bucket'      => $loc['bucket'],
                    'week'        => $loc['week'],
                    'is_future'   => $loc['is_future'],
                    'project_id'  => '',
                    'date'        => $payDate->format('d/m/Y'),
                    'date_raw'    => $payDate->format('Y-m-d'),
                    'type'        => 'Payroll',
                    'flow_label'  => 'Staff Payroll (Outflow)',
                    'ref_no'      => 'PAY-' . $payDate->format('Y-m'),
                    'party_name'  => $activeEmployees->count() . ' Active Employee(s)',
                    'description' => 'Monthly Salary Disbursement (' . $payDate->format('M Y') . ')',
                    'amount'      => $monthlyPayroll,
                    'flow_type'   => 'outflow',
                ];
            }
        }

        // ── 9. OUTFLOW: Net GST & Statutory Dues (Requirement #6) ──
        $taxableSales = $projKey === 'all'
            ? $allTaxableSales
            : $allTaxableSales->filter(fn($s) => (string)($s->project_id ?? '') === $projKey);

        $gstByMonth = [];
        foreach ($taxableSales as $s) {
            if ($this->isTestRecord($s->customer?->name, $s->sale_number)) {
                continue;
            }
            $gstAmt = (float)$s->gst_amount;
            if ($gstAmt <= 0) {
                continue;
            }
            $sDate = Carbon::parse($s->sale_date ?: $s->created_at);
            $monthKey = $sDate->format('Y-m');
            $gstByMonth[$monthKey] = ($gstByMonth[$monthKey] ?? 0.0) + $gstAmt;
        }
        // Apply Input GST Tax Credit offset chronologically
        $remainingItc = $totalInputGstCredit;
        ksort($gstByMonth);
        foreach ($gstByMonth as $ym => $grossGst) {
            $netMonthGst = max(0.0, $grossGst - $remainingItc);
            $remainingItc = max(0.0, $remainingItc - $grossGst);
            if ($netMonthGst <= 0.01) {
                continue;
            }
            // GST Return due date: 20th of the following month
            $gstDue = Carbon::createFromFormat('Y-m-d', $ym . '-01')->addMonth()->day(20)->startOfDay();
            $loc = $this->classifyDate($gstDue, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bStatutory, $loc, $netMonthGst);
            $allItems[] = [
                'row_key'     => 'outflow_gst',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => $projKey === 'all' ? '' : $projKey,
                'date'        => $gstDue->format('d/m/Y'),
                'date_raw'    => $gstDue->format('Y-m-d'),
                'type'        => 'GST / Statutory',
                'flow_label'  => 'GST Liability (Outflow)',
                'ref_no'      => 'GSTR3B-' . $ym,
                'party_name'  => 'GST Department (CBIC)',
                'description' => 'Net Output GST Liability for ' . Carbon::createFromFormat('Y-m-d', $ym . '-01')->format('M Y') . ' (after ITC)',
                'amount'      => $netMonthGst,
                'flow_type'   => 'outflow',
            ];
        }

        // ── 10. OUTFLOW: Pending Petty Cash Expenses ──
        $pendingPetty = $projKey === 'all'
            ? $allRawPendingPettyTx
            : $allRawPendingPettyTx->filter(fn($pt) => (string)($pt->pettyCashBox?->project_id ?? '') === $projKey);

        foreach ($pendingPetty as $pt) {
            if ($this->isTestRecord($pt->narration, $pt->voucher_number)) {
                continue;
            }
            $ptBal = (float)$pt->cash_out;
            if ($ptBal <= 0) {
                continue;
            }
            $ptDate = Carbon::parse($pt->transaction_date ?: $pt->created_at);
            $loc = $this->classifyDate($ptDate, $asOnDate, $w1End, $w2End, $w3End, $p1End, $p2End, $p3End);
            if ($loc === null) {
                continue;
            }
            $this->addToBucket($bPetty, $loc, $ptBal);
            $allItems[] = [
                'row_key'     => 'outflow_petty',
                'bucket'      => $loc['bucket'],
                'week'        => $loc['week'],
                'is_future'   => $loc['is_future'],
                'project_id'  => (string)($pt->pettyCashBox?->project_id ?? ''),
                'date'        => $ptDate->format('d/m/Y'),
                'date_raw'    => $ptDate->format('Y-m-d'),
                'type'        => 'Site Expense',
                'flow_label'  => 'Petty Cash Expense (Outflow)',
                'ref_no'      => $pt->voucher_number ?: ('PC-' . $pt->id),
                'party_name'  => $pt->pettyCashBox?->box_name ?: 'Petty Cash Box',
                'description' => $pt->narration ?: 'Pending Petty Cash Expense',
                'amount'      => $ptBal,
                'flow_type'   => 'outflow',
            ];
        }

        // ── AGGREGATE TOTALS BY BUCKET ──
        $keys = ['overdue', 'p1', 'p2', 'p3', 'w1', 'w2', 'w3', 'w4', 'total'];
        $totalInflows = [];
        $totalOutflows = [];
        $netCashFlow = [];

        foreach ($keys as $k) {
            $totalInflows[$k]  = $bMilestones[$k] + $bCheques[$k];
            $totalOutflows[$k] = $bRaBills[$k] + $bVendor[$k] + $bEmis[$k] + $bBrokerage[$k] + $bRefunds[$k] + $bPayroll[$k] + $bStatutory[$k] + $bLandAppr[$k] + $bPetty[$k];
            $netCashFlow[$k]   = $totalInflows[$k] - $totalOutflows[$k];
        }

        // Closing Cash Balances (Forward Trajectory from Current Liquid Cash)
        $closingCurrent = $totalLiquidCash;
        $closingW1 = $closingCurrent + $netCashFlow['w1'];
        $closingW2 = $closingW1 + $netCashFlow['w2'];
        $closingW3 = $closingW2 + $netCashFlow['w3'];
        $closingW4 = $closingW3 + $netCashFlow['w4']; // Equals P1 closing
        $closingP1 = $closingCurrent + $netCashFlow['p1'];
        $closingP2 = $closingP1 + $netCashFlow['p2'];
        $closingP3 = $closingP2 + $netCashFlow['p3'];

        // Separate Outflow Category Totals (Forward 90d vs Overall incl. Overdue)
        $catRaw = [
            ['key' => 'outflow_ra',        'name' => 'Contractor RA Bills',   'amount' => $bRaBills['total'],   'with_overdue' => $bRaBills['total'] + $bRaBills['overdue'],   'color' => '#F97316'],
            ['key' => 'outflow_emi',       'name' => 'Bank Loan EMIs',        'amount' => $bEmis['total'],      'with_overdue' => $bEmis['total'] + $bEmis['overdue'],      'color' => '#10B981'],
            ['key' => 'outflow_refunds',   'name' => 'Customer Refunds',      'amount' => $bRefunds['total'],   'with_overdue' => $bRefunds['total'] + $bRefunds['overdue'],   'color' => '#E11D48'],
            ['key' => 'outflow_gst',       'name' => 'GST & Statutory Dues',  'amount' => $bStatutory['total'], 'with_overdue' => $bStatutory['total'] + $bStatutory['overdue'], 'color' => '#EF4444'],
            ['key' => 'outflow_brokerage', 'name' => 'Brokerage Commissions', 'amount' => $bBrokerage['total'], 'with_overdue' => $bBrokerage['total'] + $bBrokerage['overdue'], 'color' => '#8B5CF6'],
            ['key' => 'outflow_vendor',    'name' => 'Vendor & Site Bills',   'amount' => $bVendor['total'],    'with_overdue' => $bVendor['total'] + $bVendor['overdue'],    'color' => '#3B82F6'],
            ['key' => 'outflow_payroll',   'name' => 'Payroll & Salaries',    'amount' => $bPayroll['total'],   'with_overdue' => $bPayroll['total'] + $bPayroll['overdue'],   'color' => '#0EA5E9'],
            ['key' => 'outflow_land',      'name' => 'Land & Approvals',      'amount' => $bLandAppr['total'],  'with_overdue' => $bLandAppr['total'] + $bLandAppr['overdue'],  'color' => '#F59E0B'],
            ['key' => 'outflow_petty',     'name' => 'Petty Cash Expenses',   'amount' => $bPetty['total'],     'with_overdue' => $bPetty['total'] + $bPetty['overdue'],     'color' => '#EC4899'],
        ];

        $donutBase = $totalOutflows['total'] > 0 ? $totalOutflows['total'] : ($totalOutflows['overdue'] > 0 ? $totalOutflows['overdue'] : 0);
        $useWithOverdue = $totalOutflows['total'] <= 0 && $totalOutflows['overdue'] > 0;

        $catDistribution = [];
        foreach ($catRaw as $c) {
            $val = $useWithOverdue ? $c['with_overdue'] : $c['amount'];
            $pct = $donutBase > 0 ? round(($val / $donutBase) * 100, 1) : 0;
            $catDistribution[] = [
                'key'          => $c['key'],
                'name'         => $c['name'],
                'amount'       => $c['amount'],
                'with_overdue' => $c['with_overdue'],
                'display_amt'  => $val,
                'percentage'   => $pct,
                'color'        => $c['color'],
            ];
        }

        // Find Top Outflow Category & Share for Card Sub-label (Requirement #2)
        $sortedCats = $catDistribution;
        usort($sortedCats, fn($a, $b) => $b['display_amt'] <=> $a['display_amt']);
        $topCategory = $sortedCats[0] ?? ['name' => 'None', 'percentage' => 0];

        // Best / Expected / Worst Scenarios for 90-Day Horizon (Requirement #10)
        $bestNet    = ($totalInflows['total'] + $bCheques['overdue']) - $totalOutflows['total'];
        $expNet     = ($bMilestones['total'] * 0.80 + ($bCheques['total'] + $bCheques['overdue']) * 0.80) - $totalOutflows['total'];
        $worstOut   = $totalOutflows['total'] + $totalOutflows['overdue'];
        $worstNet   = ($bMilestones['total'] * 0.60) - $worstOut;

        $scenarios = [
            'best' => [
                'label'       => 'Best Case (100% Collections + Pending Cheques)',
                'inflows'     => $totalInflows['total'] + $bCheques['overdue'],
                'outflows'    => $totalOutflows['total'],
                'ending_cash' => $totalLiquidCash + $bestNet,
                'net_flow'    => $bestNet,
            ],
            'expected' => [
                'label'       => 'Expected Case (80% Milestones & Cheques)',
                'inflows'     => $bMilestones['total'] * 0.80 + ($bCheques['total'] + $bCheques['overdue']) * 0.80,
                'outflows'    => $totalOutflows['total'],
                'ending_cash' => $totalLiquidCash + $expNet,
                'net_flow'    => $expNet,
            ],
            'worst' => [
                'label'       => 'Worst Case (60% Collections + Overdue Payables)',
                'inflows'     => $bMilestones['total'] * 0.60,
                'outflows'    => $worstOut,
                'ending_cash' => $totalLiquidCash + $worstNet,
                'net_flow'    => $worstNet,
            ],
        ];

        // Sort future upcoming transactions chronologically, and overdue transactions chronologically
        $upcomingTransactions = array_values(array_filter($allItems, fn($item) => $item['is_future'] === true));
        $overdueTransactions  = array_values(array_filter($allItems, fn($item) => $item['is_future'] === false));

        usort($upcomingTransactions, fn($a, $b) => strcmp($a['date_raw'], $b['date_raw']));
        usort($overdueTransactions, fn($a, $b) => strcmp($a['date_raw'], $b['date_raw']));

        // Dynamic Insights
        $keyInsights = [
            'Forward 90-day inflows stand at ' . $currency . ' ' . number_format($totalInflows['total'], 2) . ' against scheduled outflows of ' . $currency . ' ' . number_format($totalOutflows['total'], 2) . '.',
            'Top scheduled outflow category is ' . $topCategory['name'] . ' (' . $topCategory['percentage'] . '% of forecast outflows).',
            'Overdue / Past-due backlog: ' . $currency . ' ' . number_format($totalInflows['overdue'], 2) . ' in receivables vs ' . $currency . ' ' . number_format($totalOutflows['overdue'], 2) . ' in unpaid payables.',
            'Free unrestricted cash is ' . $currency . ' ' . number_format($freeCash, 2) . '; RERA / Escrow restricted balance is ' . $currency . ' ' . number_format($restrictedEscrowCash, 2) . '.',
            'Pending uncleared cheques total ' . $currency . ' ' . number_format($bCheques['total'] + $bCheques['overdue'], 2) . ' (Cash receipts excluded from clearing queue).',
        ];

        return [
            'periods' => $periods,
            'kpis'    => [
                'current_liquid_cash'      => $totalLiquidCash,
                'free_cash'                => $freeCash,
                'restricted_escrow_cash'   => $restrictedEscrowCash,
                'total_projected_inflows'  => $totalInflows['total'],
                'total_projected_outflows' => $totalOutflows['total'],
                'overdue_inflows'          => $totalInflows['overdue'],
                'overdue_outflows'         => $totalOutflows['overdue'],
                'net_liquidity'            => $closingP3,
                'total_net_cash_flow'      => $netCashFlow['total'],
                'top_outflow_name'         => $topCategory['name'],
                'top_outflow_pct'          => $topCategory['percentage'],
                'status'                   => $closingP3 >= 0 ? 'Surplus' : 'Deficit',
            ],
            'summaryTable' => [
                'inflows' => [
                    array_merge(['key' => 'inflow_milestones', 'name' => 'Customer Milestone Payments'], $bMilestones),
                    array_merge(['key' => 'inflow_cheques',    'name' => 'Pending Cheque Realizations'], $bCheques),
                ],
                'total_inflows' => $totalInflows,
                'outflows' => [
                    array_merge(['key' => 'outflow_ra',        'name' => 'Contractor RA Bills Payables'], $bRaBills),
                    array_merge(['key' => 'outflow_vendor',    'name' => 'Vendor & Site Expense Payables'], $bVendor),
                    array_merge(['key' => 'outflow_emi',       'name' => 'Bank Loan EMIs & Interest Schedules'], $bEmis),
                    array_merge(['key' => 'outflow_brokerage', 'name' => 'Brokerage & Commission Payables'], $bBrokerage),
                    array_merge(['key' => 'outflow_refunds',   'name' => 'Customer Refunds (Returns & Cancellations)'], $bRefunds),
                    array_merge(['key' => 'outflow_payroll',   'name' => 'Payroll & Employee Salaries'], $bPayroll),
                    array_merge(['key' => 'outflow_gst',       'name' => 'GST, TDS & Statutory Dues'], $bStatutory),
                    array_merge(['key' => 'outflow_land',      'name' => 'Land & Approval Costs'], $bLandAppr),
                    array_merge(['key' => 'outflow_petty',     'name' => 'Pending Petty Cash Expenses'], $bPetty),
                ],
                'total_outflows' => $totalOutflows,
                'net_cash_flow'  => $netCashFlow,
            ],
            'chartData' => [
                'categories'       => ['Current Balance', 'Next 30 Days', '31 - 60 Days', '61 - 90 Days'],
                'inflows'          => [0, round($totalInflows['p1'], 2), round($totalInflows['p2'], 2), round($totalInflows['p3'], 2)],
                'outflows'         => [0, round($totalOutflows['p1'], 2), round($totalOutflows['p2'], 2), round($totalOutflows['p3'], 2)],
                'closing_balances' => [round($closingCurrent, 2), round($closingP1, 2), round($closingP2, 2), round($closingP3, 2)],
                'weekly' => [
                    'categories'       => ['Current', 'Week 1 (0-6d)', 'Week 2 (7-13d)', 'Week 3 (14-20d)', 'Week 4 (21-30d)'],
                    'inflows'          => [0, round($totalInflows['w1'], 2), round($totalInflows['w2'], 2), round($totalInflows['w3'], 2), round($totalInflows['w4'], 2)],
                    'outflows'         => [0, round($totalOutflows['w1'], 2), round($totalOutflows['w2'], 2), round($totalOutflows['w3'], 2), round($totalOutflows['w4'], 2)],
                    'closing_balances' => [round($closingCurrent, 2), round($closingW1, 2), round($closingW2, 2), round($closingW3, 2), round($closingW4, 2)],
                ],
            ],
            'categoryOutflowBreakdown' => $catDistribution,
            'scenarios'                => $scenarios,
            'keyInsights'              => $keyInsights,
            'upcomingTransactions'     => $upcomingTransactions,
            'overdueTransactions'      => $overdueTransactions,
        ];
    }
}
