<x-erp-layout title="Salary Processing">
@php
    $employeesMapData = $employees->keyBy('id')->map(function ($e) {
        $compAllow = (float) $e->salaryComponents->where('type', 'allowance')->sum('calculated_amount');
        $compDed = (float) $e->salaryComponents->where('type', 'deduction')->sum('calculated_amount');
        return [
            'allowances' => $compAllow > 0 ? $compAllow : (float) ($e->allowances ?? 0),
            'deductions' => $compDed > 0 ? $compDed : (float) ($e->deductions ?? 0),
        ];
    });

    $salaryDetailsMap = [];
    foreach ($salaries as $s) {
        $emp = $s->employee;
        $allowComps = $emp ? $emp->salaryComponents->where('type', 'allowance')->values() : collect();
        $dedComps = $emp ? $emp->salaryComponents->where('type', 'deduction')->values() : collect();

        $earnings = [];
        if ($allowComps->isNotEmpty()) {
            foreach ($allowComps as $ac) {
                $earnings[] = [
                    'name' => $ac->name,
                    'percent' => $ac->calculation_type === 'Percentage' ? number_format((float)$ac->configured_value, 2) . '%' : null,
                    'amount' => (float) $ac->calculated_amount,
                ];
            }
        } else {
            if ((float) $s->basic_salary > 0) {
                $earnings[] = ['name' => 'Basic Salary', 'percent' => null, 'amount' => (float) $s->basic_salary];
            }
            $earnings[] = ['name' => 'Allowances', 'percent' => null, 'amount' => (float) $s->allowances];
        }
        if ((float) $s->overtime_pay > 0) {
            $earnings[] = ['name' => 'Overtime Pay', 'percent' => null, 'amount' => (float) $s->overtime_pay];
        }

        $deductionsList = [];
        if ($dedComps->isNotEmpty()) {
            foreach ($dedComps as $dc) {
                $deductionsList[] = [
                    'name' => $dc->name,
                    'percent' => $dc->calculation_type === 'Percentage' ? number_format((float)$dc->configured_value, 2) . '%' : null,
                    'amount' => (float) $dc->calculated_amount,
                ];
            }
        } else {
            $deductionsList[] = ['name' => 'Deductions', 'percent' => null, 'amount' => (float) $s->deductions];
        }

        $salaryDetailsMap[$s->id] = [
            'id' => $s->id,
            'employee_name' => $emp?->name ?? 'Employee',
            'employee_code' => $emp?->employee_id ?? 'N/A',
            'designation' => $emp?->designation ?: 'Staff',
            'head_name' => $s->salaryHead?->name ?? $s->payroll_month,
            'status' => $s->status,
            'total_earnings' => (float) $s->gross_salary,
            'total_deductions' => (float) $s->deductions,
            'net_salary' => (float) $s->net_salary,
            'earnings' => $earnings,
            'deductions' => $deductionsList,
        ];
    }
@endphp
<script>
    window.__salaryEmployeesMap = @json($employeesMapData);
    window.__salaryDetailsMap = @json($salaryDetailsMap);
</script>
<div class="space-y-6 p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{
    openAdd: false,
    openEdit: false,
    openView: false,
    openPay: false,
    openDeleteModal: false,
    deleteAction: '',
    deleteName: '',
    search: '',
    employeesMap: window.__salaryEmployeesMap || {},
    detailsMap: window.__salaryDetailsMap || {},
    viewItem: { employee_name: '', employee_code: '', designation: '', head_name: '', status: '', total_earnings: 0, total_deductions: 0, net_salary: 0, earnings: [], deductions: [] },
    addForm: {
        employee_id: '',
        allowances: 0,
        overtime_pay: 0,
        deductions: 0
    },
    onSelectEmployee() {
        const emp = this.employeesMap[this.addForm.employee_id];
        if (emp) {
            this.addForm.allowances = emp.allowances;
            this.addForm.deductions = emp.deductions;
        } else {
            this.addForm.allowances = 0;
            this.addForm.deductions = 0;
        }
    },
    get addNetPreview() {
        const gross = parseFloat(this.addForm.allowances || 0) + parseFloat(this.addForm.overtime_pay || 0);
        return Math.max(0, gross - parseFloat(this.addForm.deductions || 0)).toFixed(2);
    },
    editItem: { id: null, employee_name: '', working_days: 26, present_days: 26, leave_days: 0, overtime_hours: 0, basic_salary: 0, hra: 0, allowances: 0, overtime_pay: 0, deductions: 0, payment_date: '', payment_mode: 'Bank Transfer', status: 'Pending', remarks: '' },
    payItem: { id: null, employee_name: '', employee_code: '', head_name: '', net_salary: 0, company_bank_account_id: '{{ $bankAccounts->first()?->id ?? '' }}', payment_date: '{{ now()->toDateString() }}', payment_mode: 'Bank Transfer', payment_reference: '', remarks: '' },
    initView(id) {
        if (this.detailsMap[id]) {
            this.viewItem = this.detailsMap[id];
            this.openView = true;
        }
    },
    initPay(id) {
        const d = this.detailsMap[id];
        if (d) {
            this.payItem = {
                id: d.id,
                employee_name: d.employee_name,
                employee_code: d.employee_code,
                head_name: d.head_name,
                net_salary: d.net_salary,
                company_bank_account_id: '{{ $bankAccounts->first()?->id ?? '' }}',
                payment_date: '{{ now()->toDateString() }}',
                payment_mode: 'Bank Transfer',
                payment_reference: '',
                remarks: ''
            };
            this.openPay = true;
        }
    },
    initEdit(item) {
        this.editItem = {
            id: item.id,
            employee_name: item.employee ? item.employee.name : '',
            working_days: item.working_days || 26,
            present_days: item.present_days || 26,
            leave_days: item.leave_days || 0,
            overtime_hours: item.overtime_hours || 0,
            basic_salary: item.basic_salary || 0,
            hra: item.hra || 0,
            allowances: item.allowances || 0,
            overtime_pay: item.overtime_pay || 0,
            deductions: item.deductions || 0,
            payment_date: item.payment_date ? String(item.payment_date).substring(0, 10) : '',
            payment_mode: item.payment_mode || 'Bank Transfer',
            status: item.status || 'Pending',
            remarks: item.remarks || ''
        };
        this.openEdit = true;
    },
    openDeleteConfirm(actionUrl, name) {
        this.deleteAction = actionUrl;
        this.deleteName = name || 'this salary record';
        this.openDeleteModal = true;
    },
    matchesSearch(name, code) {
        const q = (this.search || '').trim().toLowerCase();
        if (!q) return true;
        return (name || '').toLowerCase().includes(q) || (code || '').toLowerCase().includes(q);
    },
    formatMoney(val) {
        return '₹' + Number(parseFloat(val || 0).toFixed(2)).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-xs border border-slate-200">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Salary</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Process employee monthly salaries, earnings, deductions, and payment status</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasModulePermission('payroll.salary', 'create'))
            <button type="button" @click="openAdd = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm transition cursor-pointer">
                <span>+ Add Salary</span>
            </button>
            @endif
        </div>
    </div>

    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold">{{ $errors->first() }}</div>
    @endif

    {{-- CV / Summary KPI Cards (Customer Ledger KPI Card Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Employees --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] border border-amber-200/60 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Employees</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm">
                    {{ $paidCount }} Paid
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    {{ $salaries->count() }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Employees Processed</p>
            </div>
        </div>

        {{-- Card 2: Total Earnings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-300 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Earnings</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider shadow-sm">
                    Gross
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-blue-600 transition-colors duration-300">
                    ₹{{ number_format($totalGross, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Gross Salary Obligation</p>
            </div>
        </div>

        {{-- Card 3: Total Deductions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-300 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Deductions</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm">
                    Deducted
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">
                    ₹{{ number_format($totalDeductions, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">All Statutory &amp; Other</p>
            </div>
        </div>

        {{-- Card 4: Net Payable --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Net Payable</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm">
                    Net Pay
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block">
                    ₹{{ number_format($totalNet, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Earnings &minus; Deductions</p>
            </div>
        </div>
    </div>

    {{-- Filter & Batch Generate Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col xl:flex-row xl:items-center justify-between gap-3 text-xs">
        <div class="flex flex-wrap items-center gap-2.5 flex-1">
            <form method="GET" action="{{ route('payroll.salary.index') }}" class="flex flex-wrap items-center gap-2.5">
                <select name="head_id" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700 cursor-pointer">
                    <option value="all">All Payroll Heads</option>
                    @foreach($heads as $h)
                        <option value="{{ $h->id }}" {{ (string)$headId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700 cursor-pointer">
                    <option value="all">All Statuses</option>
                    @foreach(['Pending', 'Processed', 'Paid'] as $st)
                        <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </form>

            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Search employee name or code..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30">
            </div>
        </div>

        @if(auth()->user()->hasModulePermission('payroll.salary', 'create') && $heads->count() > 0)
        <form action="{{ route('payroll.salary.generate') }}" method="POST" class="flex flex-wrap items-center gap-2">
            @csrf
            <select name="salary_head_id" required class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-700">
                @foreach($heads as $h)
                    <option value="{{ $h->id }}" {{ (string)$headId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-[#2a2415] hover:bg-slate-900 text-white text-xs font-black uppercase tracking-wider cursor-pointer transition">
                Generate Active Employees
            </button>
        </form>
        @endif
    </div>

    {{-- Employees Salary Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 erp-table-header w-12 text-center">#</th>
                        <th class="px-4 py-3.5 erp-table-header">Payroll Head</th>
                        <th class="px-4 py-3.5 erp-table-header">Employee</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Total Earnings (₹)</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Deductions (₹)</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Net Salary (₹)</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Status</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($salaries as $idx => $s)
                        @php
                            $empName = $s->employee?->name ?? 'Employee';
                            $empCode = $s->employee?->employee_id ?? '';
                        @endphp
                        <tr x-show="matchesSearch({{ Js::from($empName) }}, {{ Js::from($empCode) }})" class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 text-center text-slate-400 font-bold">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $s->salaryHead?->name ?? $s->payroll_month }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-900">{{ $empName }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $empCode }} &bull; {{ $s->employee?->designation ?: 'Staff' }}</div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700">₹{{ number_format((float)$s->gross_salary, 2) }}</td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-rose-600">-₹{{ number_format((float)$s->deductions, 2) }}</td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900">₹{{ number_format((float)$s->net_salary, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $s->status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($s->status === 'Processed' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ $s->status }}
                                </span>
                                @if($s->status === 'Paid' && $s->companyBankAccount)
                                    <div class="text-[9px] text-slate-400 font-semibold mt-0.5 truncate max-w-[140px] mx-auto" title="{{ $s->companyBankAccount->bank_name }}">
                                        {{ $s->companyBankAccount->bank_name }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if($s->status !== 'Paid' && (float)$s->net_salary > 0 && auth()->user()->hasModulePermission('payroll.salary', 'edit'))
                                    <button type="button" @click="initPay({{ $s->id }})" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-extrabold uppercase tracking-wider transition inline-flex items-center gap-1 shadow-xs cursor-pointer" title="Pay Salary via Company Bank Account">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>Pay</span>
                                    </button>
                                    @endif
                                    {{-- View Breakdown Icon --}}
                                    <button type="button" @click="initView({{ $s->id }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Salary Breakdown">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    @if(auth()->user()->hasModulePermission('payroll.salary', 'edit'))
                                    <button type="button" @click="initEdit({{ $s->toJson() }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Salary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @endif
                                    @if(auth()->user()->hasModulePermission('payroll.salary', 'delete'))
                                    <button type="button" @click="openDeleteConfirm('{{ route('payroll.salary.destroy', $s->id) }}', {{ Js::from($empName) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Salary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic">No salary records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- View Salary Details Modal --}}
    <div x-show="openView" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openView = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1" x-text="viewItem.head_name"></span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">
                        <span x-text="viewItem.employee_name"></span>
                        <span class="text-[#f3e5ab] font-mono text-xs ml-1" x-text="'(' + viewItem.employee_code + ')'"></span>
                    </h3>
                    <p class="text-[11px] text-white/70 mt-0.5" x-text="viewItem.designation"></p>
                </div>
                <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-xs font-sans max-h-[75vh] overflow-y-auto">
                {{-- EARNINGS --}}
                <div class="rounded-xl border border-emerald-200/80 overflow-hidden">
                    <div class="px-3.5 py-2 bg-emerald-50/70 border-b border-emerald-200/70 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                            <span>&uarr;</span>
                            <span>Earnings</span>
                        </span>
                        <span class="text-[10px] font-bold uppercase text-emerald-700">Amount</span>
                    </div>
                    <div class="divide-y divide-slate-100 bg-white">
                        <template x-for="(e, idx) in viewItem.earnings" :key="idx">
                            <div class="px-3.5 py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-800" x-text="e.name"></span>
                                    <template x-if="e.percent">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700" x-text="e.percent"></span>
                                    </template>
                                </div>
                                <span class="font-mono font-bold text-slate-800" x-text="formatMoney(e.amount)"></span>
                            </div>
                        </template>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-50/80 border-t border-emerald-200/70 flex items-center justify-between font-extrabold text-emerald-800">
                        <span class="text-[10px] uppercase tracking-wider">Total Earnings</span>
                        <span class="font-mono" x-text="formatMoney(viewItem.total_earnings)"></span>
                    </div>
                </div>

                {{-- DEDUCTIONS --}}
                <div class="rounded-xl border border-rose-200/80 overflow-hidden">
                    <div class="px-3.5 py-2 bg-rose-50/70 border-b border-rose-200/70 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-rose-800 flex items-center gap-1.5">
                            <span>&darr;</span>
                            <span>Deductions</span>
                        </span>
                        <span class="text-[10px] font-bold uppercase text-rose-700">Amount</span>
                    </div>
                    <div class="divide-y divide-slate-100 bg-white">
                        <template x-for="(d, idx) in viewItem.deductions" :key="idx">
                            <div class="px-3.5 py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-800" x-text="d.name"></span>
                                    <template x-if="d.percent">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700" x-text="d.percent"></span>
                                    </template>
                                </div>
                                <span class="font-mono font-bold text-rose-600" x-text="formatMoney(d.amount)"></span>
                            </div>
                        </template>
                    </div>
                    <div class="px-3.5 py-2 bg-rose-50/80 border-t border-rose-200/70 flex items-center justify-between font-extrabold text-rose-800">
                        <span class="text-[10px] uppercase tracking-wider">Total Deductions</span>
                        <span class="font-mono" x-text="formatMoney(viewItem.total_deductions)"></span>
                    </div>
                </div>

                {{-- Net Summary --}}
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-2 bg-slate-50 flex items-center justify-between border-b border-slate-200/70">
                        <span class="font-semibold text-slate-600">Total Earnings</span>
                        <span class="font-mono font-bold text-slate-800" x-text="formatMoney(viewItem.total_earnings)"></span>
                    </div>
                    <div class="px-4 py-2 bg-slate-50 flex items-center justify-between border-b border-slate-200/70">
                        <span class="font-semibold text-slate-600">Total Deductions</span>
                        <span class="font-mono font-bold text-rose-600" x-text="'-' + formatMoney(viewItem.total_deductions)"></span>
                    </div>
                    <div class="px-4 py-3 bg-[#2a2415] text-white flex items-center justify-between">
                        <span class="font-black uppercase tracking-wider text-xs">Net Salary</span>
                        <span class="font-mono font-black text-sm text-[#f3e5ab]" x-text="formatMoney(viewItem.net_salary)"></span>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="openView = false" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Salary Modal --}}
    <div x-show="openAdd" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openAdd = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider text-white">Add Employee Salary</h3>
                <button type="button" @click="openAdd = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('payroll.salary.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <input type="hidden" name="basic_salary" value="0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payroll Head</label>
                        <select name="salary_head_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="">-- Standalone --</option>
                            @foreach($heads as $h)
                                <option value="{{ $h->id }}" {{ (string)$headId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Employee *</label>
                        <select name="employee_id" required x-model="addForm.employee_id" @change="onSelectEmployee()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="">Select Employee...</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Allowances / Earnings (₹)</label>
                        <input type="number" step="0.01" min="0" name="allowances" x-model="addForm.allowances" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Overtime Pay (₹)</label>
                        <input type="number" step="0.01" min="0" name="overtime_pay" x-model="addForm.overtime_pay" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Deductions (₹)</label>
                        <input type="number" step="0.01" min="0" name="deductions" x-model="addForm.deductions" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Status *</label>
                        <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="Pending">Pending</option>
                            <option value="Processed">Processed</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-100">
                    <div class="text-xs font-bold text-slate-600">
                        Net Salary Preview: <span class="font-mono font-extrabold text-emerald-700">₹<span x-text="addNetPreview"></span></span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="openAdd = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Salary</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Salary Modal --}}
    <div x-show="openEdit" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openEdit = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Salary — <span x-text="editItem.employee_name"></span></h3>
                <button type="button" @click="openEdit = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'{{ url('/payroll/salary') }}/' + editItem.id + '/update'" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <input type="hidden" name="basic_salary" :value="editItem.basic_salary">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Allowances / Earnings (₹)</label>
                        <input type="number" step="0.01" min="0" name="allowances" x-model="editItem.allowances" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Overtime Pay (₹)</label>
                        <input type="number" step="0.01" min="0" name="overtime_pay" x-model="editItem.overtime_pay" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Deductions (₹)</label>
                        <input type="number" step="0.01" min="0" name="deductions" x-model="editItem.deductions" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Status *</label>
                        <select name="status" x-model="editItem.status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="Pending">Pending</option>
                            <option value="Processed">Processed</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Update Salary</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pay Salary Modal --}}
    <div x-show="openPay" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openPay = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BANK DISBURSEMENT</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Pay Salary &mdash; <span x-text="payItem.employee_name"></span></h3>
                </div>
                <button type="button" @click="openPay = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'{{ url('/payroll/salary') }}/' + payItem.id + '/pay'" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3.5 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-slate-900" x-text="payItem.employee_name"></div>
                        <div class="text-[10px] font-mono text-slate-500"><span x-text="payItem.employee_code"></span> &bull; <span x-text="payItem.head_name"></span></div>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Net Payable</div>
                        <div class="text-lg font-black font-mono text-emerald-700" x-text="formatMoney(payItem.net_salary)"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Company Bank Account *</label>
                        <select name="company_bank_account_id" required x-model="payItem.company_bank_account_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            @forelse($bankAccounts as $ba)
                                <option value="{{ $ba->id }}">
                                    {{ $ba->bank_name }}{{ $ba->account_number ? ' (A/C ' . $ba->account_number . ')' : '' }} &mdash; Bal: ₹{{ number_format((float)$ba->current_balance, 2) }}
                                </option>
                            @empty
                                <option value="">No active company bank accounts found</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payment Date *</label>
                        <input type="date" name="payment_date" required x-model="payItem.payment_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Payment Mode</label>
                        <select name="payment_mode" x-model="payItem.payment_mode" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="NEFT">NEFT</option>
                            <option value="RTGS">RTGS</option>
                            <option value="IMPS">IMPS</option>
                            <option value="UPI">UPI</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Reference / UTR No.</label>
                        <input type="text" name="payment_reference" x-model="payItem.payment_reference" placeholder="e.g. UTR12345678" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Remarks</label>
                        <input type="text" name="remarks" x-model="payItem.remarks" placeholder="Optional note" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="openPay = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider cursor-pointer shadow-sm">Confirm &amp; Pay</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE SALARY</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center font-sans">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove salary for <span class="font-bold text-slate-900" x-text="deleteName"></span>?</p>
                <form :action="deleteAction" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
