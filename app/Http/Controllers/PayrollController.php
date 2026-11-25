<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\CompanyBankAccount;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeLeaveQuota;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\PayrollFinancialPeriod;
use App\Models\PayrollLeaveQuota;
use App\Models\PayrollSalary;
use App\Models\PayrollSalaryHead;
use App\Models\SalaryComponent;
use App\Models\System;
use App\Models\VoucherType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayrollController extends Controller
{
    private function scopeBySystem($query)
    {
        $user = Auth::user();
        if ($user && !$user->hasMultiSystemAccess() && $user->system_id) {
            $query->where('system_id', $user->system_id);
        }
        return $query;
    }

    private function resolveSystemId(): int
    {
        $user = Auth::user();
        if ($user && $user->system_id) {
            return (int) $user->system_id;
        }
        return (int) (System::where('is_active', true)->value('id') ?? 1);
    }

    // =========================================================================
    // 1. FINANCIAL PERIOD
    // =========================================================================

    public function financialPeriodIndex(): View
    {
        $periods = $this->scopeBySystem(PayrollFinancialPeriod::query())
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return view('payroll.financial-period', compact('periods'));
    }

    public function storeFinancialPeriod(Request $request): RedirectResponse
    {
        $systemId = $this->resolveSystemId();
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'working_days' => 'nullable|integer|min:1|max:31',
            'status' => 'required|in:active,closed,inactive',
            'notes' => 'nullable|string|max:255',
        ]);

        $isCurrent = $request->boolean('is_current');
        if ($isCurrent) {
            PayrollFinancialPeriod::where('system_id', $systemId)->update(['is_current' => false]);
        }

        PayrollFinancialPeriod::create([
            'system_id' => $systemId,
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'working_days' => (int) ($data['working_days'] ?? 26),
            'is_current' => $isCurrent,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('payroll.financial-period.index')->with('status', 'Financial period created successfully.');
    }

    public function updateFinancialPeriod(Request $request, int $id): RedirectResponse
    {
        $period = $this->scopeBySystem(PayrollFinancialPeriod::query())->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'working_days' => 'nullable|integer|min:1|max:31',
            'status' => 'required|in:active,closed,inactive',
            'notes' => 'nullable|string|max:255',
        ]);

        $isCurrent = $request->boolean('is_current');
        if ($isCurrent) {
            PayrollFinancialPeriod::where('system_id', $period->system_id)->where('id', '!=', $period->id)->update(['is_current' => false]);
        }

        $period->update([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'working_days' => (int) ($data['working_days'] ?? $period->working_days ?? 26),
            'is_current' => $isCurrent,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('payroll.financial-period.index')->with('status', 'Financial period updated successfully.');
    }

    public function destroyFinancialPeriod(int $id): RedirectResponse
    {
        $period = $this->scopeBySystem(PayrollFinancialPeriod::query())->findOrFail($id);
        $period->delete();

        return redirect()->route('payroll.financial-period.index')->with('status', 'Financial period deleted.');
    }

    // =========================================================================
    // 2. LEAVE QUOTA
    // =========================================================================

    public function leaveQuotaIndex(Request $request): View
    {
        $periods = $this->scopeBySystem(PayrollFinancialPeriod::query())
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        $selectedPeriodId = $request->query('financial_period_id');
        if (!$selectedPeriodId && $periods->isNotEmpty()) {
            $selectedPeriodId = $periods->firstWhere('is_current', true)?->id ?? $periods->first()->id;
        }
        $selectedPeriod = $periods->firstWhere('id', (int) $selectedPeriodId);

        $employees = $this->scopeBySystem(Employee::query())
            ->where('status', 'active')
            ->orderBy('id')
            ->get(['id', 'employee_id', 'name', 'system_id']);

        $quotaMap = $selectedPeriod
            ? EmployeeLeaveQuota::where('financial_period_id', $selectedPeriod->id)
                ->get()
                ->keyBy('employee_id')
            : collect();

        $leavesQuery = EmployeeLeave::whereIn('employee_id', $employees->pluck('id'))
            ->where('status', '!=', 'Rejected');
        if ($selectedPeriod) {
            $leavesQuery->whereBetween('from_date', [
                $selectedPeriod->start_date->toDateString(),
                $selectedPeriod->end_date->toDateString(),
            ]);
        }
        $leavesByEmp = $leavesQuery->get()->groupBy('employee_id');

        $rows = $employees->map(function ($emp) use ($quotaMap, $leavesByEmp) {
            $saved = $quotaMap->get($emp->id);
            $empLeaves = $leavesByEmp->get($emp->id, collect());

            $casualTakenFromLogs = (float) $empLeaves
                ->filter(fn($l) => str_contains(strtolower((string) $l->leave_type), 'casual') || strtolower((string) $l->leave_type) === 'cl')
                ->sum('days');
            $medicalTakenFromLogs = (float) $empLeaves
                ->filter(fn($l) => str_contains(strtolower((string) $l->leave_type), 'medical') || str_contains(strtolower((string) $l->leave_type), 'sick') || strtolower((string) $l->leave_type) === 'ml')
                ->sum('days');

            $casualTaken = $saved ? (float) $saved->casual_taken : $casualTakenFromLogs;
            $medicalTaken = $saved ? (float) $saved->medical_taken : $medicalTakenFromLogs;

            return [
                'employee_id' => $emp->id,
                'employee_code' => $emp->employee_id,
                'name' => $emp->name,
                'casual_allowed' => (float) ($saved?->casual_allowed ?? 15),
                'casual_taken' => $casualTaken,
                'medical_allowed' => (float) ($saved?->medical_allowed ?? 10),
                'medical_taken' => $medicalTaken,
            ];
        })->values()->all();

        return view('payroll.leave-quota', compact('periods', 'selectedPeriod', 'selectedPeriodId', 'rows'));
    }

    public function saveLeaveQuotas(Request $request)
    {
        $systemId = $this->resolveSystemId();
        $data = $request->validate([
            'financial_period_id' => 'nullable|integer',
            'quotas' => 'required|array',
            'quotas.*.casual_allowed' => 'required|numeric|min:0|max:365',
            'quotas.*.medical_allowed' => 'required|numeric|min:0|max:365',
            'quotas.*.casual_taken' => 'nullable|numeric|min:0|max:365',
            'quotas.*.medical_taken' => 'nullable|numeric|min:0|max:365',
        ]);

        $periodId = !empty($data['financial_period_id']) ? (int) $data['financial_period_id'] : null;

        foreach ($data['quotas'] as $empId => $vals) {
            $updateData = [
                'system_id' => $systemId,
                'casual_allowed' => (float) $vals['casual_allowed'],
                'medical_allowed' => (float) $vals['medical_allowed'],
            ];
            if (array_key_exists('casual_taken', $vals) && $vals['casual_taken'] !== null) {
                $updateData['casual_taken'] = (float) $vals['casual_taken'];
            }
            if (array_key_exists('medical_taken', $vals) && $vals['medical_taken'] !== null) {
                $updateData['medical_taken'] = (float) $vals['medical_taken'];
            }

            EmployeeLeaveQuota::updateOrCreate(
                [
                    'financial_period_id' => $periodId,
                    'employee_id' => (int) $empId,
                ],
                $updateData
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('payroll.leave-quota.index', ['financial_period_id' => $periodId])
            ->with('status', 'Leave quotas saved successfully.');
    }

    public function storeLeaveQuota(Request $request): RedirectResponse
    {
        $systemId = $this->resolveSystemId();
        $data = $request->validate([
            'financial_period_id' => 'nullable|integer',
            'leave_type' => 'required|string|max:100',
            'annual_quota' => 'required|numeric|min:0',
            'monthly_accrual' => 'nullable|numeric|min:0',
            'max_carry_forward' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        PayrollLeaveQuota::create([
            'system_id' => $systemId,
            'financial_period_id' => $data['financial_period_id'] ?: null,
            'leave_type' => $data['leave_type'],
            'annual_quota' => $data['annual_quota'],
            'monthly_accrual' => $data['monthly_accrual'] ?? round((float) $data['annual_quota'] / 12, 2),
            'carry_forward' => $request->boolean('carry_forward'),
            'max_carry_forward' => $data['max_carry_forward'] ?? 0,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('payroll.leave-quota.index')->with('status', 'Leave quota added successfully.');
    }

    public function updateLeaveQuota(Request $request, int $id): RedirectResponse
    {
        $quota = $this->scopeBySystem(PayrollLeaveQuota::query())->findOrFail($id);
        $data = $request->validate([
            'financial_period_id' => 'nullable|integer',
            'leave_type' => 'required|string|max:100',
            'annual_quota' => 'required|numeric|min:0',
            'monthly_accrual' => 'nullable|numeric|min:0',
            'max_carry_forward' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $quota->update([
            'financial_period_id' => $data['financial_period_id'] ?: null,
            'leave_type' => $data['leave_type'],
            'annual_quota' => $data['annual_quota'],
            'monthly_accrual' => $data['monthly_accrual'] ?? round((float) $data['annual_quota'] / 12, 2),
            'carry_forward' => $request->boolean('carry_forward'),
            'max_carry_forward' => $data['max_carry_forward'] ?? 0,
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('payroll.leave-quota.index')->with('status', 'Leave quota updated successfully.');
    }

    public function destroyLeaveQuota(int $id): RedirectResponse
    {
        $quota = $this->scopeBySystem(PayrollLeaveQuota::query())->findOrFail($id);
        $quota->delete();

        return redirect()->route('payroll.leave-quota.index')->with('status', 'Leave quota deleted.');
    }

    // =========================================================================
    // 3. SALARY HEAD
    // =========================================================================

    public function salaryHeadIndex(): View
    {
        $heads = $this->scopeBySystem(PayrollSalaryHead::with('financialPeriod'))
            ->orderByDesc('from_date')
            ->orderByDesc('id')
            ->get();
        $periods = $this->scopeBySystem(PayrollFinancialPeriod::query())
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();
        $defaultPeriodId = $periods->firstWhere('is_current', true)?->id ?? $periods->first()?->id;

        return view('payroll.salary-head', compact('heads', 'periods', 'defaultPeriodId'));
    }

    public function storeSalaryHead(Request $request): RedirectResponse
    {
        $systemId = $this->resolveSystemId();
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'financial_period_id' => 'nullable|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = \Carbon\Carbon::parse($data['from_date']);
        $periodId = !empty($data['financial_period_id'])
            ? (int) $data['financial_period_id']
            : ($this->scopeBySystem(PayrollFinancialPeriod::query())->where('is_current', true)->value('id')
                ?? $this->scopeBySystem(PayrollFinancialPeriod::query())->orderByDesc('start_date')->value('id'));

        $head = PayrollSalaryHead::create([
            'system_id' => $systemId,
            'financial_period_id' => $periodId ?: null,
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'name' => $data['name'],
            'month' => (int) $from->month,
            'year' => (int) $from->year,
            'status' => 'Draft',
        ]);

        return redirect()->route('payroll.salary-head.index')->with('status', 'Salary Head created successfully.');
    }

    public function updateSalaryHead(Request $request, int $id): RedirectResponse
    {
        $head = $this->scopeBySystem(PayrollSalaryHead::query())->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'financial_period_id' => 'nullable|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = \Carbon\Carbon::parse($data['from_date']);

        $head->update([
            'financial_period_id' => !empty($data['financial_period_id']) ? (int) $data['financial_period_id'] : $head->financial_period_id,
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'name' => $data['name'],
            'month' => (int) $from->month,
            'year' => (int) $from->year,
        ]);

        return redirect()->route('payroll.salary-head.index')->with('status', 'Salary Head updated successfully.');
    }

    public function destroySalaryHead(int $id): RedirectResponse
    {
        $head = $this->scopeBySystem(PayrollSalaryHead::query())->findOrFail($id);
        PayrollSalary::where('salary_head_id', $head->id)->delete();
        $head->delete();

        return redirect()->route('payroll.salary-head.index')->with('status', 'Salary Head deleted.');
    }

    // =========================================================================
    // 4. SALARY
    // =========================================================================

    public function salaryIndex(Request $request): View
    {
        $headId = $request->query('head_id');
        $statusFilter = $request->query('status');

        $heads = $this->scopeBySystem(PayrollSalaryHead::query())
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->get();

        $employees = $this->scopeBySystem(Employee::with('salaryComponents'))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $query = $this->scopeBySystem(PayrollSalary::with(['employee.salaryComponents', 'salaryHead', 'companyBankAccount']));
        if ($headId && $headId !== 'all') {
            $query->where('salary_head_id', (int) $headId);
        }
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $salaries = $query->orderByDesc('id')->get();
        $bankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $totalGross = (float) $salaries->sum('gross_salary');
        $totalDeductions = (float) $salaries->sum('deductions');
        $totalNet = (float) $salaries->sum('net_salary');
        $paidCount = $salaries->where('status', 'Paid')->count();

        return view('payroll.salary', compact(
            'salaries',
            'heads',
            'employees',
            'bankAccounts',
            'headId',
            'statusFilter',
            'totalGross',
            'totalDeductions',
            'totalNet',
            'paidCount'
        ));
    }

    public function storeSalary(Request $request): RedirectResponse
    {
        $systemId = $this->resolveSystemId();
        $data = $request->validate([
            'salary_head_id' => 'nullable|integer',
            'employee_id' => 'required|exists:employees,id',
            'payroll_month' => 'nullable|string|max:60',
            'working_days' => 'nullable|numeric|min:0|max:31',
            'present_days' => 'nullable|numeric|min:0|max:31',
            'leave_days' => 'nullable|numeric|min:0|max:31',
            'overtime_hours' => 'nullable|numeric|min:0',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_mode' => 'nullable|string|max:50',
            'status' => 'required|in:Pending,Processed,Paid',
            'remarks' => 'nullable|string|max:255',
        ]);

        $basic = (float) $data['basic_salary'];
        $hra = (float) ($data['hra'] ?? 0);
        $allow = (float) ($data['allowances'] ?? 0);
        $otPay = (float) ($data['overtime_pay'] ?? 0);
        $ded = (float) ($data['deductions'] ?? 0);
        $gross = $basic + $hra + $allow + $otPay;
        $net = max(0, $gross - $ded);

        $head = !empty($data['salary_head_id']) ? PayrollSalaryHead::find((int) $data['salary_head_id']) : null;
        $monthLabel = $data['payroll_month'] ?: ($head?->name ?? now()->format('F Y'));

        PayrollSalary::create([
            'system_id' => $systemId,
            'salary_head_id' => $head?->id,
            'employee_id' => (int) $data['employee_id'],
            'payroll_month' => $monthLabel,
            'working_days' => $data['working_days'] ?? 26,
            'present_days' => $data['present_days'] ?? 26,
            'leave_days' => $data['leave_days'] ?? 0,
            'overtime_hours' => $data['overtime_hours'] ?? 0,
            'basic_salary' => $basic,
            'hra' => $hra,
            'allowances' => $allow,
            'overtime_pay' => $otPay,
            'gross_salary' => $gross,
            'deductions' => $ded,
            'net_salary' => $net,
            'payment_date' => $data['payment_date'] ?? null,
            'payment_mode' => $data['payment_mode'] ?: 'Bank Transfer',
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? null,
        ]);

        if ($head) {
            $head->recalculateTotals();
        }

        return redirect()->route('payroll.salary.index')->with('status', 'Salary record saved successfully.');
    }

    public function updateSalary(Request $request, int $id): RedirectResponse
    {
        $salary = $this->scopeBySystem(PayrollSalary::query())->findOrFail($id);
        $data = $request->validate([
            'working_days' => 'nullable|numeric|min:0|max:31',
            'present_days' => 'nullable|numeric|min:0|max:31',
            'leave_days' => 'nullable|numeric|min:0|max:31',
            'overtime_hours' => 'nullable|numeric|min:0',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_mode' => 'nullable|string|max:50',
            'status' => 'required|in:Pending,Processed,Paid',
            'remarks' => 'nullable|string|max:255',
        ]);

        $basic = (float) $data['basic_salary'];
        $hra = (float) ($data['hra'] ?? 0);
        $allow = (float) ($data['allowances'] ?? 0);
        $otPay = (float) ($data['overtime_pay'] ?? 0);
        $ded = (float) ($data['deductions'] ?? 0);
        $gross = $basic + $hra + $allow + $otPay;
        $net = max(0, $gross - $ded);

        $salary->update([
            'working_days' => $data['working_days'] ?? $salary->working_days,
            'present_days' => $data['present_days'] ?? $salary->present_days,
            'leave_days' => $data['leave_days'] ?? $salary->leave_days,
            'overtime_hours' => $data['overtime_hours'] ?? $salary->overtime_hours,
            'basic_salary' => $basic,
            'hra' => $hra,
            'allowances' => $allow,
            'overtime_pay' => $otPay,
            'gross_salary' => $gross,
            'deductions' => $ded,
            'net_salary' => $net,
            'payment_date' => $data['payment_date'] ?? null,
            'payment_mode' => $data['payment_mode'] ?: 'Bank Transfer',
            'status' => $data['status'],
            'remarks' => $data['remarks'] ?? null,
        ]);

        if ($salary->salaryHead) {
            $salary->salaryHead->recalculateTotals();
        }

        return redirect()->route('payroll.salary.index')->with('status', 'Salary record updated.');
    }

    public function paySalary(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'company_bank_account_id' => 'required|exists:company_bank_accounts,id',
            'payment_date' => 'required|date',
            'payment_mode' => 'nullable|string|max:50',
            'payment_reference' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        $salary = $this->scopeBySystem(PayrollSalary::with(['employee', 'salaryHead']))->findOrFail($id);

        if ($salary->status === 'Paid') {
            return back()->withErrors(['payment' => 'This salary record has already been paid.']);
        }

        $netAmount = (float) $salary->net_salary;
        if ($netAmount <= 0) {
            return back()->withErrors(['payment' => 'Net salary must be greater than zero to process a bank payment.']);
        }

        try {
            DB::transaction(function () use ($salary, $data, $netAmount) {
                $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail((int) $data['company_bank_account_id']);
                if ((float) $bankAccount->current_balance < $netAmount) {
                    throw new \RuntimeException(
                        "Insufficient balance in {$bankAccount->bank_name}. Available: ₹" . number_format((float) $bankAccount->current_balance, 2)
                    );
                }

                $bankAccount->decrement('current_balance', $netAmount);

                $requiredAccounts = [
                    '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET'],
                    '2011' => ['name' => 'Salary Payable', 'type' => 'LIABILITY'],
                ];
                foreach ($requiredAccounts as $accCode => $accInfo) {
                    ChartOfAccount::firstOrCreate(
                        ['account_code' => $accCode],
                        [
                            'account_name' => $accInfo['name'],
                            'account_type' => $accInfo['type'],
                            'is_active' => true,
                        ]
                    );
                }

                $voucherType = VoucherType::firstOrCreate(
                    ['code' => 'SALARY_PAYMENT'],
                    [
                        'name' => 'Salary Payment',
                        'prefix' => 'JV-SAL',
                        'description' => 'Generated on employee salary disbursement',
                        'is_active' => true,
                    ]
                );

                $payDate = \Carbon\Carbon::parse($data['payment_date'])->format('Y-m-d');
                $year = date('Y', strtotime($payDate));
                $baseVoucherNo = 'JV-SAL-' . $year . '-' . str_pad((string) $salary->id, 4, '0', STR_PAD_LEFT);
                $voucherNo = $baseVoucherNo;
                $suffix = 1;
                while (JournalVoucher::where('voucher_no', $voucherNo)->exists()) {
                    $voucherNo = $baseVoucherNo . '-' . $suffix;
                    $suffix++;
                }

                $empName = $salary->employee?->name ?? 'Employee';
                $empCode = $salary->employee?->employee_id ?? ('#' . $salary->employee_id);
                $periodLabel = $salary->salaryHead?->name ?: ($salary->payroll_month ?: 'Salary');
                $refNo = !empty($data['payment_reference']) ? trim((string) $data['payment_reference']) : null;

                $companyBankLabel = trim(
                    ($bankAccount->account_name ? $bankAccount->account_name . ' - ' : '')
                    . $bankAccount->bank_name
                    . ($bankAccount->account_number ? ' (A/C ' . $bankAccount->account_number . ')' : '')
                );
                $empBankParts = array_filter([
                    $salary->employee?->bank_name,
                    $salary->employee?->bank_account_number ? 'A/C ' . $salary->employee->bank_account_number : null,
                    $salary->employee?->ifsc_code ? 'IFSC ' . $salary->employee->ifsc_code : null,
                ]);
                $empBankLabel = !empty($empBankParts) ? implode(' - ', $empBankParts) : null;

                $journalVoucher = JournalVoucher::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date' => $payDate,
                    'reference_id' => $salary->id,
                    'narration' => "Salary Payment to {$empName} ({$empCode}) for {$periodLabel} from {$companyBankLabel}" . ($empBankLabel ? " to {$empBankLabel}" : ''),
                    'is_active' => true,
                ]);

                // Debit: 2011 - Salary Payable (LIABILITY) with Entity Type = STAFF, Entity ID = employee_id
                JournalEntry::create([
                    'voucher_id' => $journalVoucher->id,
                    'account_id' => '2011',
                    'entity_type' => 'STAFF',
                    'entity_id' => $salary->employee_id,
                    'debit_amount' => $netAmount,
                    'credit_amount' => 0.00,
                    'line_narration' => "Salary Payable cleared for {$empName} ({$empCode}) - {$periodLabel}" . ($empBankLabel ? " | Employee Bank: {$empBankLabel}" : ''),
                ]);

                // Credit: 1001 - Bank Balances (ASSET)
                JournalEntry::create([
                    'voucher_id' => $journalVoucher->id,
                    'account_id' => '1001',
                    'entity_type' => 'BANK',
                    'entity_id' => $bankAccount->id,
                    'debit_amount' => 0.00,
                    'credit_amount' => $netAmount,
                    'line_narration' => "Salary paid to {$empName} ({$empCode}) from {$companyBankLabel}" . ($empBankLabel ? " to {$empBankLabel}" : '') . ($refNo ? " (Ref: {$refNo})" : ''),
                ]);

                $salary->update([
                    'status' => 'Paid',
                    'company_bank_account_id' => $bankAccount->id,
                    'payment_date' => $payDate,
                    'payment_mode' => $data['payment_mode'] ?: 'Bank Transfer',
                    'payment_reference' => $refNo,
                    'journal_voucher_id' => $journalVoucher->id,
                    'remarks' => $data['remarks'] ?? $salary->remarks,
                ]);

                if ($salary->salaryHead) {
                    $salary->salaryHead->recalculateTotals();
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['company_bank_account_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('payroll.salary.index')->with('status', 'Salary paid via bank account and accounting entries posted.');
    }

    public function destroySalary(int $id): RedirectResponse
    {
        $salary = $this->scopeBySystem(PayrollSalary::query())->findOrFail($id);
        $head = $salary->salaryHead;
        $salary->delete();
        if ($head) {
            $head->recalculateTotals();
        }

        return redirect()->route('payroll.salary.index')->with('status', 'Salary record deleted.');
    }

    public function generateSalaries(Request $request): RedirectResponse
    {
        $request->validate(['salary_head_id' => 'required|integer']);
        $head = $this->scopeBySystem(PayrollSalaryHead::query())->findOrFail((int) $request->input('salary_head_id'));
        $created = $this->generateForHead($head);

        return redirect()->route('payroll.salary.index', ['head_id' => $head->id])
            ->with('status', "Generated {$created} salary record(s) for {$head->name}.");
    }

    private function generateForHead(PayrollSalaryHead $head): int
    {
        $employees = Employee::with('salaryComponents')
            ->where('system_id', $head->system_id)
            ->where('status', 'active')
            ->get();
        $created = 0;

        foreach ($employees as $emp) {
            $compAllow = (float) $emp->salaryComponents->where('type', 'allowance')->sum('calculated_amount');
            $compDed = (float) $emp->salaryComponents->where('type', 'deduction')->sum('calculated_amount');
            $allow = $compAllow > 0 ? $compAllow : (float) ($emp->allowances ?? 0);
            $ded = $compDed > 0 ? $compDed : (float) ($emp->deductions ?? 0);
            $gross = $allow;
            $net = max(0, $gross - $ded);

            $existing = PayrollSalary::where('salary_head_id', $head->id)->where('employee_id', $emp->id)->first();
            if ($existing) {
                if ($existing->status !== 'Paid' && (float) $existing->gross_salary <= 0 && $gross > 0) {
                    $existing->update([
                        'allowances' => $allow,
                        'gross_salary' => $gross,
                        'deductions' => $ded,
                        'net_salary' => $net,
                    ]);
                    $created++;
                }
                continue;
            }

            PayrollSalary::create([
                'system_id' => $head->system_id,
                'salary_head_id' => $head->id,
                'employee_id' => $emp->id,
                'payroll_month' => $head->name,
                'working_days' => $head->financialPeriod?->working_days ?? 26,
                'present_days' => $head->financialPeriod?->working_days ?? 26,
                'leave_days' => 0,
                'overtime_hours' => 0,
                'basic_salary' => 0,
                'hra' => 0,
                'allowances' => $allow,
                'overtime_pay' => 0,
                'gross_salary' => $gross,
                'deductions' => $ded,
                'net_salary' => $net,
                'payment_date' => $head->payment_date,
                'payment_mode' => 'Bank Transfer',
                'status' => $head->status === 'Paid' ? 'Paid' : 'Pending',
            ]);
            $created++;
        }

        $head->recalculateTotals();
        return $created;
    }

    // =========================================================================
    // 5. SALARY REPORT
    // =========================================================================

    public function salaryReportIndex(Request $request): View
    {
        $headId = $request->query('head_id', 'all');
        $statusFilter = $request->query('status', 'all');
        $monthFilter = $request->query('month', 'all');
        $yearFilter = $request->query('year', '');

        $heads = $this->scopeBySystem(PayrollSalaryHead::query())
            ->orderByDesc('from_date')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->get();

        $query = $this->scopeBySystem(PayrollSalary::with(['employee.salaryComponents', 'salaryHead.financialPeriod']));
        if ($headId !== 'all' && $headId !== '') {
            $query->where('salary_head_id', (int) $headId);
        }
        if ($statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }
        if ($monthFilter !== 'all' && $monthFilter !== '') {
            $m = (int) $monthFilter;
            $query->whereHas('salaryHead', fn($q) => $q->where('month', $m));
        }
        if ($yearFilter !== '' && $yearFilter !== 'all') {
            $y = (int) $yearFilter;
            $query->whereHas('salaryHead', fn($q) => $q->where('year', $y));
        }

        $rows = $query->orderByDesc('id')->get();

        $empIds = $rows->pluck('employee_id')->filter()->unique()->values();
        $leaveQuotasByEmp = $empIds->isNotEmpty()
            ? EmployeeLeaveQuota::whereIn('employee_id', $empIds)->get()->groupBy('employee_id')
            : collect();

        $summaryByHead = $rows->groupBy(fn($r) => $r->salaryHead?->name ?? ($r->payroll_month ?: 'General'))
            ->map(function ($group, $headName) {
                $firstHead = $group->first()?->salaryHead;
                return [
                    'head_name' => $headName,
                    'from_date' => $firstHead?->from_date?->format('d M Y'),
                    'to_date' => $firstHead?->to_date?->format('d M Y'),
                    'salary_date' => $firstHead?->payment_date?->format('d M Y'),
                    'employee_count' => $group->count(),
                    'total_basic' => (float) $group->sum('basic_salary'),
                    'total_allowances' => (float) $group->sum('hra') + (float) $group->sum('allowances') + (float) $group->sum('overtime_pay'),
                    'total_gross' => (float) $group->sum('gross_salary'),
                    'total_deductions' => (float) $group->sum('deductions'),
                    'total_net' => (float) $group->sum('net_salary'),
                    'rows' => $group,
                ];
            });

        $totals = [
            'count' => $rows->count(),
            'gross' => (float) $rows->sum('gross_salary'),
            'deductions' => (float) $rows->sum('deductions'),
            'net' => (float) $rows->sum('net_salary'),
        ];

        return view('payroll.salary-report', compact(
            'heads',
            'headId',
            'statusFilter',
            'monthFilter',
            'yearFilter',
            'rows',
            'summaryByHead',
            'leaveQuotasByEmp',
            'totals'
        ));
    }

    public function exportSalaryReportCsv(Request $request): StreamedResponse
    {
        $headId = $request->query('head_id', 'all');
        $statusFilter = $request->query('status', 'all');

        $query = $this->scopeBySystem(PayrollSalary::with(['employee', 'salaryHead']));
        if ($headId !== 'all' && $headId !== '') {
            $query->where('salary_head_id', (int) $headId);
        }
        if ($statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }
        $rows = $query->orderByDesc('id')->get();

        $filename = 'salary-report-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Payroll Head',
                'Employee Code',
                'Employee Name',
                'Designation',
                'Department',
                'Working Days',
                'Present Days',
                'Basic Salary',
                'HRA',
                'Allowances',
                'Overtime Pay',
                'Gross Salary',
                'Deductions',
                'Net Salary',
                'Payment Mode',
                'Status',
            ]);
            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r->salaryHead?->name ?? $r->payroll_month,
                    $r->employee?->employee_id ?? '',
                    $r->employee?->name ?? '',
                    $r->employee?->designation ?? '',
                    $r->employee?->department ?? '',
                    $r->working_days,
                    $r->present_days,
                    $r->basic_salary,
                    $r->hra,
                    $r->allowances,
                    $r->overtime_pay,
                    $r->gross_salary,
                    $r->deductions,
                    $r->net_salary,
                    $r->payment_mode,
                    $r->status,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // =========================================================================
    // 6. ALLOWANCE & DEDUCTION MASTER
    // =========================================================================

    public function componentsIndex(Request $request): View
    {
        $typeFilter = $request->query('type', 'all');
        $statusFilter = $request->query('status', 'all');

        $components = SalaryComponent::orderBy('type')->orderBy('name')->get();
        $allowanceCount = $components->where('type', 'allowance')->count();
        $deductionCount = $components->where('type', 'deduction')->count();
        $activeCount = $components->where('status', 'active')->count();

        return view('payroll.components', compact(
            'components',
            'typeFilter',
            'statusFilter',
            'allowanceCount',
            'deductionCount',
            'activeCount'
        ));
    }

    public function storeComponent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:allowance,deduction',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        SalaryComponent::create([
            'system_id' => $this->resolveSystemId(),
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('payroll.components.index')
            ->with('status', ucfirst($data['type']) . ' master component added.');
    }

    public function updateComponent(Request $request, int $id): RedirectResponse
    {
        $component = SalaryComponent::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:allowance,deduction',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $component->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        return redirect()->route('payroll.components.index')
            ->with('status', 'Salary component updated.');
    }

    public function destroyComponent(int $id): RedirectResponse
    {
        SalaryComponent::where('id', $id)->delete();

        return redirect()->route('payroll.components.index')
            ->with('status', 'Salary component deleted.');
    }
}
