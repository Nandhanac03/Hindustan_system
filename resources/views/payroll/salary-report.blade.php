<x-erp-layout title="Salary Report Summary">
@php
    $reportDetailsMap = [];
    foreach ($rows as $r) {
        $emp = $r->employee;
        $head = $r->salaryHead;
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
            if ((float) $r->basic_salary > 0) {
                $earnings[] = ['name' => 'Basic Salary', 'percent' => null, 'amount' => (float) $r->basic_salary];
            }
            $earnings[] = ['name' => 'Allowances', 'percent' => null, 'amount' => (float) $r->allowances];
        }
        if ((float) $r->overtime_pay > 0) {
            $earnings[] = ['name' => 'Overtime Pay', 'percent' => null, 'amount' => (float) $r->overtime_pay];
        }

        $deductionsList = [];
        $lopAmt = (float) ($r->lop_amount ?? 0);
        if ($dedComps->isNotEmpty()) {
            foreach ($dedComps as $dc) {
                $deductionsList[] = [
                    'name' => $dc->name,
                    'percent' => $dc->calculation_type === 'Percentage' ? number_format((float)$dc->configured_value, 2) . '%' : null,
                    'amount' => (float) $dc->calculated_amount,
                ];
            }
            if ($lopAmt > 0) {
                $deductionsList[] = ['name' => 'LOP Deduction', 'percent' => null, 'amount' => $lopAmt];
            }
        } elseif ((float) $r->deductions > 0) {
            if ($lopAmt > 0) {
                $deductionsList[] = ['name' => 'LOP Deduction', 'percent' => null, 'amount' => $lopAmt];
                $otherDed = round(max(0, (float) $r->deductions - $lopAmt), 2);
                if ($otherDed > 0) {
                    $deductionsList[] = ['name' => 'Deductions', 'percent' => null, 'amount' => $otherDed];
                }
            } else {
                $deductionsList[] = ['name' => 'Deductions', 'percent' => null, 'amount' => (float) $r->deductions];
            }
        }

        $empQuotas = $leaveQuotasByEmp->get($r->employee_id, collect());
        $quota = $head?->financial_period_id
            ? ($empQuotas->firstWhere('financial_period_id', $head->financial_period_id) ?? $empQuotas->first())
            : $empQuotas->first();

        $allowedLeave = (float) ($quota?->allowed_leave ?? 0);
        $takenLeave = (float) ($quota?->taken_leave ?? 0);

        $empName = $emp?->name ?? 'Employee';
        $initials = collect(explode(' ', trim($empName)))->filter()->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') ?: 'EM';

        $reportDetailsMap[$r->id] = [
            'id' => $r->id,
            'initials' => $initials,
            'employee_name' => $empName,
            'employee_code' => $emp?->employee_id ?? 'N/A',
            'designation' => $emp?->designation ?: 'Staff',
            'status' => $r->status,
            'head_name' => $head?->name ?? ($r->payroll_month ?: 'General'),
            'salary_month' => $r->payroll_month ?: ($head?->name ?? '—'),
            'period_from' => $head?->from_date?->format('d M Y') ?? '—',
            'period_to' => $head?->to_date?->format('d M Y') ?? '—',
            'working_days' => $r->working_days ?? 26,
            'total_earnings' => (float) $r->gross_salary,
            'total_deductions' => (float) $r->deductions,
            'net_salary' => (float) $r->net_salary,
            'earnings' => $earnings,
            'deductions' => $deductionsList,
            'leaves' => [
                [
                    'type' => $quota?->financialPeriod?->name ?: 'Financial Year Leave',
                    'allowed' => $allowedLeave,
                    'taken' => $takenLeave,
                    'balance' => max(0, $allowedLeave - $takenLeave),
                ],
            ],
        ];
    }
@endphp
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script>
    window.__salaryReportDetailsMap = @json($reportDetailsMap);
</script>
<div class="space-y-6 p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;" x-data="{
    openView: false,
    detailsMap: window.__salaryReportDetailsMap || {},
    viewItem: {
        initials: 'EM', employee_name: '', employee_code: '', designation: '', status: '',
        head_name: '', salary_month: '', period_from: '', period_to: '', working_days: 26,
        total_earnings: 0, total_deductions: 0, net_salary: 0, earnings: [], deductions: [], leaves: []
    },
    initView(id) {
        if (this.detailsMap[id]) {
            this.viewItem = this.detailsMap[id];
            this.openView = true;
        }
    },
    formatMoney(val) {
        return '₹' + Number(parseFloat(val || 0).toFixed(2)).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    formatNum(n) {
        const v = parseFloat(n || 0);
        return Number.isInteger(v) ? String(v) : v.toFixed(1);
    },
    async exportExcelSummary() {
        const list = Object.values(this.detailsMap || {});
        if (!list.length) {
            alert('No salary report records to export.');
            return;
        }
        const workbook = new ExcelJS.Workbook();
        workbook.creator = 'TABASCO ERP';
        const ws = workbook.addWorksheet('Salary Report');
        ws.columns = [
            { width: 6 },
            { width: 28 },
            { width: 14 },
            { width: 24 },
            { width: 18 },
            { width: 14 },
            { width: 18 },
            { width: 18 },
            { width: 18 },
            { width: 14 }
        ];

        ws.mergeCells('A1:J1');
        const titleCell = ws.getCell('A1');
        titleCell.value = 'SALARY REPORT SUMMARY';
        titleCell.font = { name: 'Plus Jakarta Sans', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
        titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2A2415' } };
        titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
        ws.getRow(1).height = 28;

        const headers = ['#', 'PAYROLL HEAD', 'EMP CODE', 'EMPLOYEE NAME', 'DESIGNATION', 'WORKING DAYS', 'TOTAL EARNINGS (₹)', 'DEDUCTIONS (₹)', 'NET PAYABLE (₹)', 'STATUS'];
        const headerRow = ws.addRow(headers);
        headerRow.height = 22;
        headerRow.eachCell((cell) => {
            cell.font = { bold: true, size: 10, color: { argb: 'FFFFFFFF' } };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
            cell.alignment = { horizontal: 'center', vertical: 'middle' };
            cell.border = { top: { style: 'thin' }, bottom: { style: 'thin' }, left: { style: 'thin' }, right: { style: 'thin' } };
        });

        let sumEarn = 0, sumDed = 0, sumNet = 0;
        list.forEach((r, i) => {
            sumEarn += Number(r.total_earnings || 0);
            sumDed += Number(r.total_deductions || 0);
            sumNet += Number(r.net_salary || 0);
            const row = ws.addRow([
                i + 1,
                r.head_name,
                r.employee_code,
                r.employee_name,
                r.designation,
                Number(r.working_days || 26),
                Number(r.total_earnings || 0),
                Number(r.total_deductions || 0),
                Number(r.net_salary || 0),
                r.status
            ]);
            [7, 8, 9].forEach(c => { row.getCell(c).numFmt = '₹#,##0.00'; });
            row.eachCell((cell) => {
                cell.border = { top: { style: 'thin', color: { argb: 'FFE2E8F0' } }, bottom: { style: 'thin', color: { argb: 'FFE2E8F0' } }, left: { style: 'thin', color: { argb: 'FFE2E8F0' } }, right: { style: 'thin', color: { argb: 'FFE2E8F0' } } };
            });
        });

        const totalRow = ws.addRow(['', 'TOTAL', '', list.length + ' Employee(s)', '', '', sumEarn, sumDed, sumNet, '']);
        totalRow.font = { bold: true };
        [7, 8, 9].forEach(c => { totalRow.getCell(c).numFmt = '₹#,##0.00'; });
        totalRow.eachCell((cell) => {
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8FAFC' } };
            cell.border = { top: { style: 'medium', color: { argb: 'FFA38C29' } }, bottom: { style: 'medium', color: { argb: 'FFA38C29' } } };
        });

        const buf = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buf], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Salary_Report_Summary.xlsx';
        a.click();
        window.URL.revokeObjectURL(url);
    },
    async exportEmployeeExcel(id) {
        const item = this.detailsMap[id];
        if (!item) return;
        const workbook = new ExcelJS.Workbook();
        workbook.creator = 'TABASCO ERP';
        const ws = workbook.addWorksheet('Salary Slip');
        ws.columns = [{ width: 32 }, { width: 18 }, { width: 22 }];

        ws.mergeCells('A1:C1');
        const t = ws.getCell('A1');
        t.value = item.employee_name + ' (' + item.employee_code + ') — ' + item.head_name;
        t.font = { bold: true, size: 12, color: { argb: 'FFFFFFFF' } };
        t.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2A2415' } };
        t.alignment = { horizontal: 'center', vertical: 'middle' };
        ws.getRow(1).height = 26;

        ws.addRow(['Period From: ' + item.period_from, 'Period To: ' + item.period_to, 'Working Days: ' + item.working_days]);
        ws.addRow([]);

        const eHdr = ws.addRow(['EARNINGS COMPONENT', 'RATE / %', 'AMOUNT (₹)']);
        eHdr.eachCell(c => {
            c.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF059669' } };
        });
        (item.earnings || []).forEach(e => {
            const r = ws.addRow([e.name, e.percent || 'Fixed', Number(e.amount || 0)]);
            r.getCell(3).numFmt = '₹#,##0.00';
        });
        const eTot = ws.addRow(['TOTAL EARNINGS', '', Number(item.total_earnings || 0)]);
        eTot.font = { bold: true };
        eTot.getCell(3).numFmt = '₹#,##0.00';

        ws.addRow([]);
        const dHdr = ws.addRow(['DEDUCTIONS COMPONENT', 'RATE / %', 'AMOUNT (₹)']);
        dHdr.eachCell(c => {
            c.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE11D48' } };
        });
        (item.deductions || []).forEach(d => {
            const r = ws.addRow([d.name, d.percent || 'Fixed', Number(d.amount || 0)]);
            r.getCell(3).numFmt = '₹#,##0.00';
        });
        const dTot = ws.addRow(['TOTAL DEDUCTIONS', '', Number(item.total_deductions || 0)]);
        dTot.font = { bold: true };
        dTot.getCell(3).numFmt = '₹#,##0.00';

        ws.addRow([]);
        const netRow = ws.addRow(['NET PAYABLE', '', Number(item.net_salary || 0)]);
        netRow.font = { bold: true, size: 12 };
        netRow.getCell(3).numFmt = '₹#,##0.00';

        const buf = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buf], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Salary_Slip_' + (item.employee_code || item.id) + '.xlsx';
        a.click();
        window.URL.revokeObjectURL(url);
    },
    downloadSummaryPdf() {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            window.print();
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape' });
        doc.setFillColor(42, 36, 21);
        doc.rect(0, 0, 297, 22, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(14);
        doc.text('SALARY REPORT SUMMARY', 14, 14);

        const list = Object.values(this.detailsMap || {});
        const body = list.map((r, i) => [
            i + 1,
            r.head_name,
            r.employee_code + ' - ' + r.employee_name,
            r.working_days,
            r.status,
            'Rs. ' + Number(r.total_earnings || 0).toFixed(2),
            'Rs. ' + Number(r.total_deductions || 0).toFixed(2),
            'Rs. ' + Number(r.net_salary || 0).toFixed(2),
        ]);

        doc.autoTable({
            startY: 28,
            head: [['#', 'Payroll Head', 'Employee', 'Days', 'Status', 'Earnings', 'Deductions', 'Net Payable']],
            body,
            headStyles: { fillColor: [163, 140, 41], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 9 },
        });
        doc.save('Salary_Report_Summary.pdf');
    },
    downloadEmployeePdf(item) {
        if (!item || !window.jspdf || !window.jspdf.jsPDF) {
            window.print();
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFillColor(42, 36, 21);
        doc.rect(0, 0, 210, 28, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(14);
        doc.text(item.employee_name + ' (' + item.employee_code + ')', 14, 13);
        doc.setFontSize(10);
        doc.text(item.head_name + ' | Period: ' + item.period_from + ' - ' + item.period_to + ' | Working Days: ' + item.working_days, 14, 21);

        const earnBody = (item.earnings || []).map(e => [e.name + (e.percent ? ' (' + e.percent + ')' : ''), 'Rs. ' + Number(e.amount || 0).toFixed(2)]);
        earnBody.push(['TOTAL EARNINGS', 'Rs. ' + Number(item.total_earnings || 0).toFixed(2)]);

        doc.autoTable({
            startY: 34,
            head: [['Earnings Component', 'Amount']],
            body: earnBody,
            headStyles: { fillColor: [5, 150, 105], textColor: 255 },
            styles: { fontSize: 9 },
        });

        const dedBody = (item.deductions && item.deductions.length)
            ? item.deductions.map(d => [d.name + (d.percent ? ' (' + d.percent + ')' : ''), 'Rs. ' + Number(d.amount || 0).toFixed(2)])
            : [['No deductions', 'Rs. 0.00']];
        dedBody.push(['TOTAL DEDUCTIONS', 'Rs. ' + Number(item.total_deductions || 0).toFixed(2)]);

        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 6,
            head: [['Deductions Component', 'Amount']],
            body: dedBody,
            headStyles: { fillColor: [225, 29, 72], textColor: 255 },
            styles: { fontSize: 9 },
        });

        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 6,
            head: [['Financial Period', 'Allowed', 'Taken (FY)', 'Balance']],
            body: (item.leaves || []).map(l => [l.type, l.allowed, l.taken, l.balance]),
            headStyles: { fillColor: [83, 78, 71], textColor: 255 },
            styles: { fontSize: 9 },
        });

        const y = doc.lastAutoTable.finalY + 12;
        doc.setTextColor(15, 23, 42);
        doc.setFontSize(12);
        doc.text('NET PAYABLE: Rs. ' + Number(item.net_salary || 0).toFixed(2), 14, y);
        doc.save('Salary_Slip_' + (item.employee_code || item.id) + '.pdf');
    }
}">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-xs border border-slate-200">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Salary Report Summary</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Payroll head-wise salary breakdown, earnings, deductions, leave balances, and net payable register</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasModulePermission('payroll.salary_report', 'excel'))
            <button type="button" @click="exportExcelSummary()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <span>Export Excel</span>
            </button>
            @endif
            @if(auth()->user()->hasModulePermission('payroll.salary_report', 'pdf'))
            <button type="button" @click="downloadSummaryPdf()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-xs transition cursor-pointer">
                <span>Download PDF</span>
            </button>
            @endif
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs text-xs">
        <form method="GET" action="{{ route('payroll.salary-report.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Payroll Head</label>
                <select name="head_id" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                    <option value="all">All Payroll Heads</option>
                    @foreach($heads as $h)
                        <option value="{{ $h->id }}" {{ (string)$headId === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Month</label>
                <select name="month" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                    <option value="all">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (string)$monthFilter === (string)$m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Year</label>
                <input type="number" name="year" value="{{ $yearFilter }}" placeholder="e.g. 2026" min="2020" max="2099"
                       class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono text-slate-800">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                <select name="status" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                    <option value="all">All Statuses</option>
                    @foreach(['Pending', 'Processed', 'Paid'] as $st)
                        <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider cursor-pointer transition">
                    Apply
                </button>
                <a href="{{ route('payroll.salary-report.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Totals Bar (Customer Ledger KPI Card Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Employees --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] border border-amber-200/60 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Employees Listed</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm">
                    Workforce
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    {{ $totals['count'] }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Employees in Filtered Register</p>
            </div>
        </div>

        {{-- Card 2: Total Gross / Earnings --}}
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
                    ₹{{ number_format($totals['gross'], 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Combined Gross Earnings</p>
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
                    ₹{{ number_format($totals['deductions'], 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Statutory &amp; Other Deductions</p>
            </div>
        </div>

        {{-- Card 4: Total Net Payable --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Net Payable</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm">
                    Net Pay
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block">
                    ₹{{ number_format($totals['net'], 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Final Disbursement Obligation</p>
            </div>
        </div>
    </div>

    {{-- Grouped Summary Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 erp-table-header w-12 text-center">#</th>
                        <th class="px-4 py-3.5 erp-table-header">Payroll Head</th>
                        <th class="px-4 py-3.5 erp-table-header">Employee</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Working Days</th>
                        <th class="px-4 py-3.5 erp-table-header text-center">Status</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Net Payable (₹)</th>
                        <th class="px-4 py-3.5 erp-table-header text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($summaryByHead as $headName => $group)
                        {{-- Head Group Banner --}}
                        <tr class="bg-amber-50/60 border-b border-amber-200/60">
                            <td colspan="7" class="px-4 py-2.5">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-[#8a7522]">{{ $headName }}</span>
                                        @if($group['from_date'] && $group['to_date'])
                                            <span class="text-[11px] text-slate-500 font-medium">({{ $group['from_date'] }} &ndash; {{ $group['to_date'] }})</span>
                                        @elseif($group['salary_date'])
                                            <span class="text-[11px] text-slate-500 font-medium">Salary Date: {{ $group['salary_date'] }}</span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-600">
                                        {{ $group['employee_count'] }} Employee(s) &bull; Net: <span class="font-mono text-emerald-700">₹{{ number_format($group['total_net'], 2) }}</span>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @foreach($group['rows'] as $idx => $r)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3 text-center text-slate-400 font-bold">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-700">{{ $r->payroll_month ?: $headName }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-[10px] font-mono font-bold text-[#a38c29]">{{ $r->employee?->employee_id ?? 'N/A' }}</div>
                                    <div class="font-bold text-slate-900">{{ $r->employee?->name ?? 'Employee' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-700">{{ $r->working_days ?? 26 }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $r->status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($r->status === 'Processed' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        {{ $r->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900">
                                    ₹{{ number_format((float)$r->net_salary, 2) }}
                                    @if((float)($r->lop_amount ?? 0) > 0)
                                        <div class="text-[9px] text-rose-600 font-bold">LOP: -₹{{ number_format((float)$r->lop_amount, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <button type="button" @click="initView({{ $r->id }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Salary Report">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        @if(auth()->user()->hasModulePermission('payroll.salary_report', 'excel'))
                                        <button type="button" @click="exportEmployeeExcel({{ $r->id }})" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Export Excel (.xlsx)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </button>
                                        @endif
                                        @if(auth()->user()->hasModulePermission('payroll.salary_report', 'pdf'))
                                        <button type="button" @click="downloadEmployeePdf(detailsMap[{{ $r->id }}])" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Download PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No salary report data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 text-[11px] font-semibold text-slate-500">
            Total: <span class="font-bold text-slate-800">{{ $totals['count'] }}</span> record(s)
        </div>
    </div>

    {{-- Detailed Salary Report View Modal --}}
    <div x-show="openView" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openView = false">
            {{-- Modal Header --}}
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-[#a38c29]/30 text-[#f3e5ab] border border-[#a38c29]/40 flex items-center justify-center font-black text-xs shrink-0" x-text="viewItem.initials"></div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-black text-sm uppercase tracking-wider text-white" x-text="viewItem.employee_name"></h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-200 border border-emerald-400/30" x-text="viewItem.status"></span>
                        </div>
                        <p class="text-[11px] text-white/75 mt-0.5" x-text="viewItem.employee_code + ' • ' + viewItem.designation"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="downloadEmployeePdf(viewItem)" class="px-3 py-1.5 rounded-lg bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold uppercase cursor-pointer">
                        Download PDF
                    </button>
                    <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-5 text-xs font-sans max-h-[78vh] overflow-y-auto">
                {{-- Meta Info Row --}}
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Payroll Head</p>
                        <p class="font-bold text-slate-800 mt-0.5" x-text="viewItem.head_name"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Salary Month</p>
                        <p class="font-bold text-slate-800 mt-0.5" x-text="viewItem.salary_month"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Period From</p>
                        <p class="font-bold font-mono text-slate-800 mt-0.5" x-text="viewItem.period_from"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Period To</p>
                        <p class="font-bold font-mono text-slate-800 mt-0.5" x-text="viewItem.period_to"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">Working Days</p>
                        <p class="font-bold font-mono text-slate-800 mt-0.5" x-text="viewItem.working_days"></p>
                    </div>
                </div>

                {{-- 3-Column Financial Summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-white">
                        <p class="text-[10px] font-bold uppercase text-slate-400">Total Earnings</p>
                        <p class="text-base font-extrabold font-mono text-slate-900 mt-1" x-text="formatMoney(viewItem.total_earnings)"></p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Gross per month</p>
                    </div>
                    <div class="p-3.5 rounded-xl border border-rose-200 bg-rose-50/40">
                        <p class="text-[10px] font-bold uppercase text-rose-500">Deductions</p>
                        <p class="text-base font-extrabold font-mono text-rose-600 mt-1" x-text="'-' + formatMoney(viewItem.total_deductions)"></p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Statutory &amp; other</p>
                    </div>
                    <div class="p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/50">
                        <p class="text-[10px] font-bold uppercase text-emerald-700">Payable Amount</p>
                        <p class="text-base font-extrabold font-mono text-emerald-700 mt-1" x-text="formatMoney(viewItem.net_salary)"></p>
                        <p class="text-[10px] text-emerald-600 mt-0.5">Final take-home</p>
                    </div>
                </div>

                {{-- 2-Column Earnings & Deductions --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- EARNINGS --}}
                    <div class="rounded-xl border border-emerald-200/80 overflow-hidden flex flex-col">
                        <div class="px-3.5 py-2 bg-emerald-50/70 border-b border-emerald-200/70 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800">&uarr; Earnings</span>
                            <span class="text-[10px] font-bold uppercase text-emerald-700">Amount</span>
                        </div>
                        <div class="divide-y divide-slate-100 bg-white flex-1">
                            <template x-for="(e, idx) in viewItem.earnings" :key="idx">
                                <div class="px-3.5 py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
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
                    <div class="rounded-xl border border-rose-200/80 overflow-hidden flex flex-col">
                        <div class="px-3.5 py-2 bg-rose-50/70 border-b border-rose-200/70 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase tracking-wider text-rose-800">&darr; Deductions</span>
                            <span class="text-[10px] font-bold uppercase text-rose-700">Amount</span>
                        </div>
                        <div class="divide-y divide-slate-100 bg-white flex-1">
                            <template x-if="viewItem.deductions.length === 0">
                                <div class="px-3.5 py-6 text-center text-slate-400 italic">No deductions found</div>
                            </template>
                            <template x-for="(d, idx) in viewItem.deductions" :key="idx">
                                <div class="px-3.5 py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
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
                </div>

                {{-- Leave Balance Breakdown --}}
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-3.5 py-2 bg-slate-100 border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-700">
                        Leave Quota &amp; Balance Breakdown
                    </div>
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-3.5 py-2">Financial Period</th>
                                <th class="px-3.5 py-2 text-center">Allowed</th>
                                <th class="px-3.5 py-2 text-center">Taken (FY)</th>
                                <th class="px-3.5 py-2 text-center">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(lv, idx) in viewItem.leaves" :key="idx">
                                <tr>
                                    <td class="px-3.5 py-2 font-bold text-slate-800" x-text="lv.type"></td>
                                    <td class="px-3.5 py-2 text-center">
                                        <span class="inline-block min-w-[26px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-mono font-bold" x-text="formatNum(lv.allowed)"></span>
                                    </td>
                                    <td class="px-3.5 py-2 text-center font-mono font-semibold text-slate-700" x-text="formatNum(lv.taken)"></td>
                                    <td class="px-3.5 py-2 text-center">
                                        <span class="inline-block min-w-[26px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-mono font-bold" x-text="formatNum(lv.balance)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
