<x-erp-layout title="Loan Outstanding Summary" headerTitle="Loan Outstanding Summary">

<div class="max-w-[1800px] mx-auto space-y-6">
    <style>
        .reports-table thead th {
            background-color: #a38c29 !important;
            color: white !important;
            border-color: #8a7522 !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 10px 16px !important;
        }
        .reports-table tbody tr:nth-child(even) {
            background-color: #F6F3E9 !important;
        }
        .reports-table tbody tr:hover {
            background-color: #ebe5d0 !important;
        }
        .reports-table tbody td {
            border-color: #e2e8f0 !important;
            padding: 10px 16px !important;
        }
    </style>

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bank Loans & Treasury</span>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest">Analytics & Dues</span>
            </div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Loan Repayment Analytics & Due Alerts</h1>
            <p class="text-xs text-slate-500 mt-0.5">Trace total paid vs outstanding principal balance and view repayment schedules and alerts.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition uppercase tracking-wider shadow-md shadow-[#a38c29]/20 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Payment Release Console</span>
            </a>
        </div>
    </div>

    {{-- Dashboard Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Total Sanctioned Principal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#0D9488] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#0D9488]/40 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(13,148,136,0.15)]">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#0D9488]/10 flex items-center justify-center text-[#0D9488] border border-[#0D9488]/20 transition-all duration-300 group-hover:bg-[#0D9488] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Total Sanctioned Principal</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#0D9488]/40 group-hover:text-[#0D9488] group-hover:bg-[#0D9488]/5">Sanctioned</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#0D9488] transition-colors duration-300">₹{{ number_format($totalLoansAmount, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cumulative principal across all active loans.</p>
            </div>
        </div>

        {{-- Card 2: Principal Repaid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)]">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Principal Repaid</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:text-emerald-700 group-hover:bg-emerald-50/50">Cleared</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">₹{{ number_format($totalPaidPrincipal, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">
                    @if($totalLoansAmount > 0)
                        <span class="text-emerald-600 font-bold">{{ number_format(($totalPaidPrincipal / $totalLoansAmount) * 100, 1) }}% completed</span>
                    @else
                        <span>0% completed</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Card 3: Outstanding Principal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)]">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Outstanding Principal</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:text-rose-700 group-hover:bg-rose-50/50">Balance</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight block group-hover:text-rose-800 transition-colors duration-300">₹{{ number_format($totalOutstanding, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">
                    @if($totalLoansAmount > 0)
                        <span class="text-rose-600 font-bold">{{ number_format(($totalOutstanding / $totalLoansAmount) * 100, 1) }}% balance</span>
                    @else
                        <span>0% balance</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Card 4: Total Interest Paid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/40 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)]">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Interest Paid to Date</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/40 group-hover:text-[#8a7522] group-hover:bg-[#a38c29]/5">Expense</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">₹{{ number_format($totalInterestPaid, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cumulative interest expenses paid.</p>
            </div>
        </div>
    </div>

    {{-- Repayment progress progress bars --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Active Loans List --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col h-full">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3 shrink-0">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Active Bank Loans
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Direct overview and repayment schedule access per active loan</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 uppercase tracking-wider font-mono">
                    {{ count($loans) }} {{ count($loans) == 1 ? 'Loan' : 'Loans' }}
                </span>
            </div>

            {{-- Loans List --}}
            <div class="space-y-2 overflow-y-auto pr-1.5 flex-1 max-h-[370px]">
                @forelse($loans as $loan)
                    @php
                        $paidPct = 0;
                        $repaid = (float)$loan->principal_amount - (float)$loan->outstanding_balance;
                        if ((float)$loan->principal_amount > 0) {
                            $paidPct = ($repaid / (float)$loan->principal_amount) * 100;
                        }
                    @endphp
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/80 hover:bg-white hover:border-[#a38c29]/40 border border-slate-200/80 text-xs transition-all shadow-2xs hover:shadow-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-black text-[11px] shrink-0">
                                {{ strtoupper(substr($loan->lender_name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-bold text-slate-900 text-xs truncate">{{ $loan->lender_name }}</h4>
                                    <span class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-slate-600 font-mono text-[10px] font-bold shadow-2xs">{{ $loan->loan_account_no }}</span>
                                </div>
                                @if($loan->project && !empty($loan->project->project_name))
                                    <p class="text-[10px] text-slate-500 truncate mt-0.5">
                                        {{ $loan->project->project_name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 shrink-0">
                            <span class="font-mono text-[10px] text-[#8a7522] font-bold bg-[#a38c29]/10 border border-[#a38c29]/20 px-2 py-0.5 rounded-md">
                                {{ number_format($paidPct, 1) }}% Paid
                            </span>
                            <a href="{{ route('loans.schedule', $loan->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm shadow-[#a38c29]/20 cursor-pointer">
                                <span>Schedule</span>
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-xs text-slate-400 font-medium">No active bank loans found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Interest paid summary breakup & Interactive Donut Chart --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                            Debt Capital & Interest Breakdown
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Visual split of repaid principal, outstanding liability & interest</p>
                    </div>
                    <span class="text-[10px] font-bold text-[#a38c29] bg-[#a38c29]/10 px-2.5 py-1 rounded-full uppercase tracking-wider font-mono border border-[#a38c29]/20">Composition</span>
                </div>

                {{-- Chart + Stats Container --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    {{-- Apex Donut Chart --}}
                    <div class="sm:col-span-6 flex flex-col items-center justify-center relative">
                        <div id="debtCompositionChart" class="w-full min-h-[220px] flex items-center justify-center"></div>
                    </div>

                    {{-- Compact KPI Pills --}}
                    <div class="sm:col-span-6 space-y-2 text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Total Capital Borrowed</span>
                            <span class="font-mono font-black text-slate-900 text-sm">₹{{ number_format($totalLoansAmount, 2) }}</span>
                        </div>

                        <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100/90 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">
                                    Principal Repaid
                                </span>
                                <span class="font-mono font-black text-emerald-700 text-xs">₹{{ number_format($totalPaidPrincipal, 2) }}</span>
                            </div>
                            <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-white text-emerald-800 border border-emerald-200 shadow-2xs">
                                {{ $totalLoansAmount > 0 ? number_format(($totalPaidPrincipal / $totalLoansAmount) * 100, 1) : 0 }}%
                            </span>
                        </div>

                        <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-100/80 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider block">
                                    Outstanding Principal
                                </span>
                                <span class="font-mono font-black text-rose-700 text-xs">₹{{ number_format($totalOutstanding, 2) }}</span>
                            </div>
                            <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-white text-rose-700 border border-rose-200 shadow-2xs">
                                {{ $totalLoansAmount > 0 ? number_format(($totalOutstanding / $totalLoansAmount) * 100, 1) : 0 }}%
                            </span>
                        </div>

                        <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-100/80 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider block">
                                    Interest Paid
                                </span>
                                <span class="font-mono font-black text-[#8a7522] text-xs">₹{{ number_format($totalInterestPaid, 2) }}</span>
                            </div>
                            <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-white text-[#8a7522] border border-amber-200 shadow-2xs">
                                Expense
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Total cashflow paid bottom banner --}}
            <div class="border-t border-dashed border-slate-200 pt-3">
                <div class="flex justify-between items-center bg-slate-50/80 p-3 rounded-xl border border-slate-200/80">
                    <div>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">Total Cashflow Paid (Principal + Interest)</span>
                        <span class="text-[11px] text-slate-400">Total treasury outflow settled to lenders</span>
                    </div>
                    <strong class="text-slate-900 font-mono font-black text-base">₹{{ number_format($totalPaidPrincipal + $totalInterestPaid, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- EMIs due alert lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Due Today --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    EMI Payments Due Today
                </h3>
                <span class="px-2.5 py-1 rounded-full text-[10px] bg-rose-50 border border-rose-200 text-rose-700 font-extrabold uppercase tracking-wide">
                    {{ $emiDueToday->count() }} Due
                </span>
            </div>
            <div class="max-h-[320px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse reports-table">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522]">
                            <th class="px-4 py-2.5 border text-center font-black">LOAN ACCOUNT</th>
                            <th class="px-4 py-2.5 border text-center font-black">LENDING BANK</th>
                            <th class="px-4 py-2.5 border text-center font-black">DUE AMOUNT</th>
                            <th class="px-4 py-2.5 text-center font-black">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueToday as $item)
                            <tr>
                                <td class="px-4 py-3.5 border text-slate-900 font-bold font-mono text-center">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3.5 border text-slate-700 text-center font-bold">{{ $item->loan->lender_name }}</td>
                                <td class="px-4 py-3.5 border font-mono text-rose-700 font-extrabold text-center">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3.5 border text-center">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm shadow-[#a38c29]/20 cursor-pointer">
                                        <span>Pay EMI</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400 italic">No EMI payments due today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Due This Month --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    EMI Payments Due This Month
                </h3>
                <span class="px-2.5 py-1 rounded-full text-[10px] bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#8a7522] font-extrabold uppercase tracking-wide">
                    {{ $emiDueThisMonth->count() }} Due
                </span>
            </div>
            <div class="max-h-[320px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse reports-table">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522]">
                            <th class="px-4 py-2.5 border text-center font-black">LOAN ACCOUNT</th>
                            <th class="px-4 py-2.5 border text-center font-black">DUE DATE</th>
                            <th class="px-4 py-2.5 border text-center font-black">DUE AMOUNT</th>
                            <th class="px-4 py-2.5 text-center font-black">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueThisMonth as $item)
                            <tr>
                                <td class="px-4 py-3.5 border text-slate-900 font-bold font-mono text-center">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3.5 border text-slate-700 text-center">{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}</td>
                                <td class="px-4 py-3.5 border font-mono text-slate-900 font-extrabold text-center">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3.5 border text-center">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm shadow-[#a38c29]/20 cursor-pointer">
                                        <span>Schedule</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400 italic">No EMI payments due this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Debt Capital & Interest Composition Donut Chart
        const chartEl = document.querySelector("#debtCompositionChart");
        if (!chartEl) return;

        const repaidVal = {{ (float)$totalPaidPrincipal }};
        const outstandingVal = {{ (float)$totalOutstanding }};
        const interestVal = {{ (float)$totalInterestPaid }};
        const totalSettledVal = repaidVal + interestVal;

        const options = {
            series: [repaidVal, outstandingVal, interestVal],
            labels: ['Principal Repaid', 'Principal Outstanding', 'Interest Paid'],
            colors: ['#10b981', '#f43f5e', '#a38c29'],
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '10px',
                                fontWeight: 700,
                                color: '#64748b'
                            },
                            value: {
                                show: true,
                                fontSize: '13px',
                                fontWeight: 900,
                                fontFamily: 'monospace',
                                color: '#0f172a',
                                formatter: function (val) {
                                    return '₹' + Number(val).toLocaleString('en-IN', { maximumFractionDigits: 0 });
                                }
                            },
                            total: {
                                show: true,
                                label: 'Paid Out',
                                fontSize: '10px',
                                fontWeight: 800,
                                color: '#64748b',
                                formatter: function () {
                                    return '₹' + totalSettledVal.toLocaleString('en-IN', { maximumFractionDigits: 0 });
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            stroke: {
                width: 2,
                colors: ['#ffffff']
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return '₹' + Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };

        const chart = new ApexCharts(chartEl, options);
        chart.render();
    });
</script>

</x-erp-layout>
