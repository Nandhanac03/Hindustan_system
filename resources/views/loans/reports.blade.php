<x-erp-layout title="Loan Outstanding Summary" headerTitle="Loan Outstanding Summary">

{{-- ExcelJS CDN Library for Executive Client-side Excel Export --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="loanReportsApp()">

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
        <div class="flex items-center gap-2.5">
            <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition uppercase tracking-wider shadow-md shadow-[#a38c29]/20 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Payment Release Console</span>
            </a>
            {{-- EXPORT EXCEL Button Only (placed right after Payment Release Console button) --}}
            <button type="button" @click.stop.prevent="exportToExcel()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-wide transition shadow hover:shadow-md cursor-pointer active:scale-[0.98]"
                    title="Export Loan Outstanding Summary to Excel">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>EXPORT EXCEL</span>
            </button>
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
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="erp-table-header text-white border-b border-slate-700">
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">LOAN ACCOUNT</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">LENDING BANK</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">DUE AMOUNT</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 bg-white">
                        @forelse($emiDueToday as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
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
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="erp-table-header text-white border-b border-slate-700">
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">LOAN ACCOUNT</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">DUE DATE</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">DUE AMOUNT</th>
                            <th class="px-4 py-2.5 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center font-bold text-[10px] uppercase tracking-wider">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 bg-white">
                        @forelse($emiDueThisMonth as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
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

    {{-- Last Section: Loan EMI Payment History (50 Records Per Page) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/60">
            <div>
                <div class="flex items-center gap-2.5">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Loan EMI Payment History
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold uppercase tracking-wide">
                        {{ $emiPaymentHistory->total() }} Records
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">Chronological record of settled EMI payments, principal paid down, and interest settled across all loans.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-700 shadow-2xs">
                    50 records per page
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="erp-table-header text-white border-b border-slate-700 text-center font-bold text-[10px] uppercase tracking-wider">
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">SL NO</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">LOAN ACCOUNT NO.</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-left">LENDING BANK</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">INSTALLMENT</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">DUE DATE</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">PAYMENT DATE</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-right">PRINCIPAL PAID (₹)</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-right">INTEREST PAID (₹)</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-right">TOTAL PAID (₹)</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">STATUS</th>
                        <th class="px-4 py-3 border border-slate-600/50 sticky top-0 erp-table-header text-white text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 bg-white">
                    @forelse($emiPaymentHistory as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 border font-bold text-slate-400 text-center">
                                {{ ($emiPaymentHistory->currentPage() - 1) * $emiPaymentHistory->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold font-mono text-center">
                                {{ $item->loan ? $item->loan->loan_account_no : '—' }}
                            </td>
                            <td class="px-4 py-3.5 border text-slate-800 font-bold text-left">
                                <div>{{ $item->loan ? $item->loan->lender_name : '—' }}</div>
                                @if($item->loan && $item->loan->project)
                                    <div class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $item->loan->project->name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-center font-mono font-bold text-slate-700">
                                Inst #{{ $item->installment_no }}
                            </td>
                            <td class="px-4 py-3.5 border text-center text-slate-600">
                                {{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-4 py-3.5 border text-center font-mono font-bold text-slate-900">
                                {{ $item->paid_date ? \Carbon\Carbon::parse($item->paid_date)->format('d M Y') : ($item->updated_at ? $item->updated_at->format('d M Y') : '—') }}
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono font-bold text-emerald-700">
                                ₹{{ number_format((float)$item->principal_component, 2) }}
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono font-bold text-[#8a7522]">
                                ₹{{ number_format((float)$item->interest_component, 2) }}
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono font-black text-slate-900">
                                ₹{{ number_format((float)($item->amount_paid > 0 ? $item->amount_paid : $item->emi_amount), 2) }}
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 border border-emerald-200 text-emerald-700">
                                    {{ $item->status ?? 'Paid' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                @if($item->loan_id)
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm shadow-[#a38c29]/20 cursor-pointer">
                                        <span>Schedule</span>
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-slate-400 italic">
                                No loan EMI payments recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer (Exact Match to Theme) --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900 font-black">{{ $emiPaymentHistory->total() > 0 ? ($emiPaymentHistory->firstItem() ?? 1) : 0 }}</span> TO 
                <span class="text-slate-900 font-black">{{ $emiPaymentHistory->total() > 0 ? ($emiPaymentHistory->lastItem() ?? 0) : 0 }}</span> OF 
                <span class="text-slate-900 font-black">{{ $emiPaymentHistory->total() }}</span> TRANSACTIONS
            </div>
            
            <div class="flex items-center gap-1.5">
                {{-- PREV Button --}}
                @if ($emiPaymentHistory->onFirstPage() || $emiPaymentHistory->total() == 0)
                    <button type="button" disabled 
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider opacity-50 cursor-not-allowed shadow-2xs select-none">
                        PREV
                    </button>
                @else
                    <a href="{{ $emiPaymentHistory->previousPageUrl() }}" 
                       class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 hover:text-slate-900 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer">
                        PREV
                    </a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $totalPages = max(1, $emiPaymentHistory->lastPage());
                    $currentPage = $emiPaymentHistory->currentPage();
                    
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    if ($currentPage <= 3) {
                        $startPage = 1;
                        $endPage = min(5, $totalPages);
                    } elseif ($currentPage >= $totalPages - 2) {
                        $startPage = max(1, $totalPages - 4);
                        $endPage = $totalPages;
                    }
                @endphp

                @if($startPage > 1)
                    <a href="{{ $emiPaymentHistory->url(1) }}" 
                       class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-[10px] font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer">
                        1
                    </a>
                    @if($startPage > 2)
                        <span class="px-1 text-[10px] text-slate-400 font-bold select-none">...</span>
                    @endif
                @endif

                @for ($p = $startPage; $p <= $endPage; $p++)
                    @if ($p == $currentPage)
                        <span class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-[10px] font-black bg-[#a38c29] text-white border border-[#a38c29] shadow-2xs select-none">
                            {{ $p }}
                        </span>
                    @else
                        <a href="{{ $emiPaymentHistory->url($p) }}" 
                           class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-[10px] font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer">
                            {{ $p }}
                        </a>
                    @endif
                @endfor

                @if($endPage < $totalPages)
                    @if($endPage < $totalPages - 1)
                        <span class="px-1 text-[10px] text-slate-400 font-bold select-none">...</span>
                    @endif
                    <a href="{{ $emiPaymentHistory->url($totalPages) }}" 
                       class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-[10px] font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer">
                        {{ $totalPages }}
                    </a>
                @endif

                {{-- NEXT Button --}}
                @if ($emiPaymentHistory->hasMorePages())
                    <a href="{{ $emiPaymentHistory->nextPageUrl() }}" 
                       class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 hover:text-slate-900 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer">
                        NEXT
                    </a>
                @else
                    <button type="button" disabled 
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider opacity-50 cursor-not-allowed shadow-2xs select-none">
                        NEXT
                    </button>
                @endif
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

    @php
        $exportLoansData = $loans->map(function($l) {
            $paid = (float)$l->principal_amount - (float)$l->outstanding_balance;
            return [
                'id' => $l->id,
                'loan_account_no' => $l->loan_account_no,
                'lender_name' => $l->lender_name,
                'principal_amount' => (float)$l->principal_amount,
                'outstanding_balance' => (float)$l->outstanding_balance,
                'paid_principal' => $paid > 0 ? $paid : 0.0,
                'interest_rate' => (float)$l->interest_rate,
                'tenure_months' => $l->tenure_months,
                'status' => $l->status,
            ];
        })->values();

        $exportEmiDuesData = $emiDueThisMonth->map(function($e) {
            return [
                'loan_account_no' => $e->loan ? $e->loan->loan_account_no : '—',
                'lender_name' => $e->loan ? $e->loan->lender_name : '—',
                'due_date' => $e->due_date ? \Carbon\Carbon::parse($e->due_date)->format('d M Y') : '—',
                'due_amount' => (float)($e->emi_amount - $e->amount_paid),
                'status' => $e->status,
            ];
        })->values();

        $exportPaymentHistory = $emiPaymentHistory->getCollection()->map(function($p) {
            return [
                'loan_account_no' => $p->loan ? $p->loan->loan_account_no : '—',
                'lender_name' => $p->loan ? $p->loan->lender_name : '—',
                'installment_no' => $p->installment_no,
                'due_date' => $p->due_date ? \Carbon\Carbon::parse($p->due_date)->format('d M Y') : '—',
                'paid_date' => $p->paid_date ? \Carbon\Carbon::parse($p->paid_date)->format('d M Y') : ($p->updated_at ? $p->updated_at->format('d M Y') : '—'),
                'principal_paid' => (float)$p->principal_component,
                'interest_paid' => (float)$p->interest_component,
                'amount_paid' => (float)($p->amount_paid > 0 ? $p->amount_paid : $p->emi_amount),
                'status' => $p->status ?? 'Paid',
            ];
        })->values();
    @endphp

    function loanReportsApp() {
        return {
            loans: {!! json_encode($exportLoansData) !!},
            emiDueThisMonth: {!! json_encode($exportEmiDuesData) !!},
            paymentHistory: {!! json_encode($exportPaymentHistory) !!},
            totalLoansAmount: {{ (float)$totalLoansAmount }},
            totalOutstanding: {{ (float)$totalOutstanding }},
            totalPaidPrincipal: {{ (float)$totalPaidPrincipal }},
            totalInterestPaid: {{ (float)$totalInterestPaid }},

            async exportToExcel() {
                try {
                    if (typeof ExcelJS === 'undefined') {
                        alert('ExcelJS library is loading. Please wait a moment and try again.');
                        return;
                    }

                    const loansList = this.loans || [];
                    if (!loansList || loansList.length === 0) {
                        alert('No loan records available to export.');
                        return;
                    }

                    const workbook = new ExcelJS.Workbook();
                    workbook.creator = 'TABASCO ERP';
                    workbook.created = new Date();

                    const worksheet = workbook.addWorksheet('Loan Summary Report', {
                        views: [{ showGridLines: true }]
                    });

                    const reportTitleText = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - LOAN OUTSTANDING & REPAYMENT SUMMARY';
                    const nowStr = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                    const sanctionedFormatted = Number(this.totalLoansAmount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const repaidFormatted = Number(this.totalPaidPrincipal || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const outstandingFormatted = Number(this.totalOutstanding || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const interestFormatted = Number(this.totalInterestPaid || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const subtitleText = `Sanctioned: ₹${sanctionedFormatted} | Repaid: ₹${repaidFormatted} | Balance: ₹${outstandingFormatted} | Interest Paid: ₹${interestFormatted} | Generated On: ${nowStr}`;

                    // 9 Columns with spacious widths for executive visibility
                    worksheet.columns = [
                        { key: 'sl', width: 10 },                     // A: SL NO
                        { key: 'loan_acc', width: 28 },               // B: LOAN ACCOUNT NO.
                        { key: 'lender', width: 34 },                 // C: LENDING BANK
                        { key: 'sanctioned', width: 28 },             // D: SANCTIONED PRINCIPAL (₹)
                        { key: 'repaid', width: 28 },                 // E: PRINCIPAL REPAID (₹)
                        { key: 'balance', width: 28 },                // F: OUTSTANDING BALANCE (₹)
                        { key: 'rate', width: 18 },                   // G: INTEREST RATE
                        { key: 'tenure', width: 20 },                 // H: TENURE (MONTHS)
                        { key: 'status', width: 18 }                  // I: STATUS
                    ];

                    const thinBorder = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };

                    const headerBorder = {
                        top: { style: 'thin', color: { argb: 'FF475569' } },
                        left: { style: 'thin', color: { argb: 'FF475569' } },
                        bottom: { style: 'thin', color: { argb: 'FF475569' } },
                        right: { style: 'thin', color: { argb: 'FF475569' } }
                    };

                    // Row 1: Spacer
                    worksheet.addRow([]);
                    worksheet.getRow(1).height = 14;

                    // Row 2: Top Title Banner (Navy Slate #2C3E50)
                    worksheet.mergeCells('A2:I2');
                    const row2 = worksheet.getRow(2);
                    row2.height = 38;
                    const cellA2 = worksheet.getCell('A2');
                    cellA2.value = reportTitleText;
                    cellA2.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                    cellA2.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                    for (let c = 1; c <= 9; c++) {
                        worksheet.getCell(2, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        worksheet.getCell(2, c).border = headerBorder;
                    }

                    // Row 3: Subtitle Banner (Cyan/Ocean Blue #007398)
                    worksheet.mergeCells('A3:I3');
                    const row3 = worksheet.getRow(3);
                    row3.height = 26;
                    const cellA3 = worksheet.getCell('A3');
                    cellA3.value = subtitleText;
                    cellA3.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    cellA3.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                    for (let c = 1; c <= 9; c++) {
                        worksheet.getCell(3, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                        worksheet.getCell(3, c).border = headerBorder;
                    }

                    // Row 4: Spacer
                    worksheet.addRow([]);
                    worksheet.getRow(4).height = 10;

                    // Row 5: Table Header Row (Slate #34495E)
                    const headerTitles = [
                        'SL NO',
                        'LOAN ACCOUNT NO.',
                        'LENDING BANK',
                        'SANCTIONED PRINCIPAL (₹)',
                        'PRINCIPAL REPAID (₹)',
                        'OUTSTANDING BALANCE (₹)',
                        'INTEREST RATE',
                        'TENURE',
                        'STATUS'
                    ];
                    const row5 = worksheet.addRow(headerTitles);
                    row5.height = 34;
                    for (let c = 1; c <= 9; c++) {
                        const hCell = worksheet.getCell(5, c);
                        hCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                        hCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                        hCell.border = headerBorder;
                        hCell.alignment = {
                            horizontal: 'center',
                            vertical: 'middle',
                            wrapText: true
                        };
                    }

                    // Data Rows (Row 6+)
                    let totalSanctionedSum = 0;
                    let totalRepaidSum = 0;
                    let totalOutstandingSum = 0;
                    let currentRowNum = 6;

                    loansList.forEach((loan, idx) => {
                        const isEven = idx % 2 === 0;
                        const rowBg = isEven ? 'FFFFFFFF' : 'FFF0F8FF';

                        const principal = parseFloat(loan.principal_amount) || 0;
                        const outstanding = parseFloat(loan.outstanding_balance) || 0;
                        const repaid = parseFloat(loan.paid_principal) || Math.max(0, principal - outstanding);

                        totalSanctionedSum += principal;
                        totalRepaidSum += repaid;
                        totalOutstandingSum += outstanding;

                        const mainRowNum = currentRowNum++;
                        worksheet.addRow([
                            idx + 1,
                            loan.loan_account_no || '—',
                            loan.lender_name || '—',
                            principal,
                            repaid,
                            outstanding,
                            (loan.interest_rate ? loan.interest_rate + '% P.A' : '—'),
                            (loan.tenure_months ? loan.tenure_months + ' Months' : '—'),
                            loan.status || 'Active'
                        ]);
                        worksheet.getRow(mainRowNum).height = 30;

                        for (let c = 1; c <= 9; c++) {
                            const cell = worksheet.getCell(mainRowNum, c);
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                            cell.border = thinBorder;
                            cell.font = {
                                name: 'Calibri',
                                size: 10,
                                bold: (c === 1 || c === 2 || c === 4 || c === 5 || c === 6 || c === 9),
                                color: {
                                    argb: (c === 5 ? 'FF047857' : (c === 6 ? 'FFB91C1C' : 'FF000000'))
                                }
                            };

                            cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                            if (c === 4 || c === 5 || c === 6) {
                                cell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                            }
                        }
                    });

                    // Total Summary Row
                    const totalRowNum = currentRowNum++;
                    worksheet.mergeCells(`A${totalRowNum}:C${totalRowNum}`);
                    const totalRow = worksheet.getRow(totalRowNum);
                    totalRow.height = 38;

                    const labelCell = worksheet.getCell(`A${totalRowNum}`);
                    labelCell.value = 'TOTAL SUMMARY';
                    labelCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
                    labelCell.alignment = { horizontal: 'center', vertical: 'middle' };

                    for (let c = 1; c <= 3; c++) {
                        worksheet.getCell(totalRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        worksheet.getCell(totalRowNum, c).border = headerBorder;
                    }

                    // Col D: Sanctioned Total
                    const cellD = worksheet.getCell(totalRowNum, 4);
                    cellD.value = totalSanctionedSum;
                    cellD.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    cellD.alignment = { horizontal: 'center', vertical: 'middle' };
                    cellD.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                    cellD.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cellD.border = headerBorder;

                    // Col E: Repaid Total
                    const cellE = worksheet.getCell(totalRowNum, 5);
                    cellE.value = totalRepaidSum;
                    cellE.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF34D399' } }; // Light emerald
                    cellE.alignment = { horizontal: 'center', vertical: 'middle' };
                    cellE.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                    cellE.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cellE.border = headerBorder;

                    // Col F: Outstanding Total
                    const cellF = worksheet.getCell(totalRowNum, 6);
                    cellF.value = totalOutstandingSum;
                    cellF.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFF87171' } }; // Light rose
                    cellF.alignment = { horizontal: 'center', vertical: 'middle' };
                    cellF.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                    cellF.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    cellF.border = headerBorder;

                    // Col G, H, I: Empty navy cells
                    for (let c = 7; c <= 9; c++) {
                        const emptyCell = worksheet.getCell(totalRowNum, c);
                        emptyCell.value = '';
                        emptyCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        emptyCell.border = headerBorder;
                    }

                    // Section 2: Upcoming EMI Dues This Month (if available)
                    const emiDues = this.emiDueThisMonth || [];
                    if (emiDues.length > 0) {
                        // Spacer
                        worksheet.addRow([]);
                        worksheet.getRow(currentRowNum++).height = 14;

                        // Section Banner
                        const bannerRowNum = currentRowNum++;
                        worksheet.mergeCells(`A${bannerRowNum}:I${bannerRowNum}`);
                        const bannerRow = worksheet.getRow(bannerRowNum);
                        bannerRow.height = 30;
                        const bCell = worksheet.getCell(`A${bannerRowNum}`);
                        bCell.value = `UPCOMING EMI PAYMENTS DUE THIS MONTH (${emiDues.length} UPCOMING)`;
                        bCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                        bCell.alignment = { horizontal: 'center', vertical: 'middle' };
                        for (let c = 1; c <= 9; c++) {
                            worksheet.getCell(bannerRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                            worksheet.getCell(bannerRowNum, c).border = headerBorder;
                        }

                        // Sub-header
                        const subHeaderRowNum = currentRowNum++;
                        const subRow = worksheet.getRow(subHeaderRowNum);
                        subRow.height = 30;
                        
                        worksheet.mergeCells(`B${subHeaderRowNum}:C${subHeaderRowNum}`);
                        worksheet.mergeCells(`D${subHeaderRowNum}:E${subHeaderRowNum}`);
                        worksheet.mergeCells(`F${subHeaderRowNum}:G${subHeaderRowNum}`);

                        worksheet.getCell(`A${subHeaderRowNum}`).value = 'SL NO';
                        worksheet.getCell(`B${subHeaderRowNum}`).value = 'LOAN ACCOUNT NO.';
                        worksheet.getCell(`D${subHeaderRowNum}`).value = 'LENDING BANK';
                        worksheet.getCell(`F${subHeaderRowNum}`).value = 'DUE DATE';
                        worksheet.getCell(`H${subHeaderRowNum}`).value = 'DUE AMOUNT (₹)';
                        worksheet.getCell(`I${subHeaderRowNum}`).value = 'STATUS';

                        for (let c = 1; c <= 9; c++) {
                            const sc = worksheet.getCell(subHeaderRowNum, c);
                            sc.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                            sc.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                            sc.border = headerBorder;
                            sc.alignment = { horizontal: 'center', vertical: 'middle' };
                        }

                        let totalDuesSum = 0;
                        emiDues.forEach((due, dIdx) => {
                            const dRowNum = currentRowNum++;
                            const dRowBg = (dIdx % 2 === 0) ? 'FFFFFFFF' : 'FFF8FAFC';
                            const dueAmt = parseFloat(due.due_amount) || 0;
                            totalDuesSum += dueAmt;

                            worksheet.mergeCells(`B${dRowNum}:C${dRowNum}`);
                            worksheet.mergeCells(`D${dRowNum}:E${dRowNum}`);
                            worksheet.mergeCells(`F${dRowNum}:G${dRowNum}`);

                            worksheet.getCell(`A${dRowNum}`).value = dIdx + 1;
                            worksheet.getCell(`B${dRowNum}`).value = due.loan_account_no || '—';
                            worksheet.getCell(`D${dRowNum}`).value = due.lender_name || '—';
                            worksheet.getCell(`F${dRowNum}`).value = due.due_date || '—';
                            worksheet.getCell(`H${dRowNum}`).value = dueAmt;
                            worksheet.getCell(`I${dRowNum}`).value = due.status || 'Due';

                            worksheet.getRow(dRowNum).height = 26;

                            for (let c = 1; c <= 9; c++) {
                                const cCell = worksheet.getCell(dRowNum, c);
                                cCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: dRowBg } };
                                cCell.border = thinBorder;
                                cCell.font = { name: 'Calibri', size: 10, bold: (c === 1 || c === 2 || c === 8 || c === 9) };
                                
                                cCell.alignment = { horizontal: 'center', vertical: 'middle' };
                                if (c === 8) {
                                    cCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                                    cCell.font.color = { argb: 'FFB91C1C' };
                                }
                            }
                        });

                        // Total Dues Row
                        const totalDueRowNum = currentRowNum++;
                        worksheet.mergeCells(`A${totalDueRowNum}:G${totalDueRowNum}`);
                        worksheet.getRow(totalDueRowNum).height = 32;

                        const dueLabelCell = worksheet.getCell(`A${totalDueRowNum}`);
                        dueLabelCell.value = 'TOTAL UPCOMING DUES THIS MONTH';
                        dueLabelCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                        dueLabelCell.alignment = { horizontal: 'center', vertical: 'middle' };

                        for (let c = 1; c <= 7; c++) {
                            worksheet.getCell(totalDueRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                            worksheet.getCell(totalDueRowNum, c).border = headerBorder;
                        }

                        const dueSumCell = worksheet.getCell(`H${totalDueRowNum}`);
                        dueSumCell.value = totalDuesSum;
                        dueSumCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFF87171' } };
                        dueSumCell.alignment = { horizontal: 'center', vertical: 'middle' };
                        dueSumCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                        dueSumCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        dueSumCell.border = headerBorder;

                        const lastDueCell = worksheet.getCell(`I${totalDueRowNum}`);
                        lastDueCell.value = '';
                        lastDueCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        lastDueCell.border = headerBorder;
                    }

                    // Section 3: Loan EMI Payment History
                    const payHistory = this.paymentHistory || [];
                    if (payHistory.length > 0) {
                        // Spacer
                        worksheet.addRow([]);
                        worksheet.getRow(currentRowNum++).height = 14;

                        // Section Banner
                        const payBannerRowNum = currentRowNum++;
                        worksheet.mergeCells(`A${payBannerRowNum}:I${payBannerRowNum}`);
                        const payBannerRow = worksheet.getRow(payBannerRowNum);
                        payBannerRow.height = 30;
                        const pbCell = worksheet.getCell(`A${payBannerRowNum}`);
                        pbCell.value = `SETTLED LOAN EMI PAYMENT HISTORY (${payHistory.length} RECORDS)`;
                        pbCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                        pbCell.alignment = { horizontal: 'center', vertical: 'middle' };
                        for (let c = 1; c <= 9; c++) {
                            worksheet.getCell(payBannerRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                            worksheet.getCell(payBannerRowNum, c).border = headerBorder;
                        }

                        // Sub-header
                        const histHeaderRowNum = currentRowNum++;
                        const hRow = worksheet.getRow(histHeaderRowNum);
                        hRow.height = 32;

                        worksheet.getCell(`A${histHeaderRowNum}`).value = 'SL NO';
                        worksheet.getCell(`B${histHeaderRowNum}`).value = 'LOAN ACCOUNT NO.';
                        worksheet.getCell(`C${histHeaderRowNum}`).value = 'LENDING BANK';
                        worksheet.getCell(`D${histHeaderRowNum}`).value = 'INSTALLMENT';
                        worksheet.getCell(`E${histHeaderRowNum}`).value = 'DUE DATE';
                        worksheet.getCell(`F${histHeaderRowNum}`).value = 'PAYMENT DATE';
                        worksheet.getCell(`G${histHeaderRowNum}`).value = 'PRINCIPAL (₹)';
                        worksheet.getCell(`H${histHeaderRowNum}`).value = 'INTEREST (₹)';
                        worksheet.getCell(`I${histHeaderRowNum}`).value = 'TOTAL PAID (₹)';

                        for (let c = 1; c <= 9; c++) {
                            const sc = worksheet.getCell(histHeaderRowNum, c);
                            sc.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                            sc.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                            sc.border = headerBorder;
                            sc.alignment = { horizontal: 'center', vertical: 'middle' };
                        }

                        let totalHistPrincipal = 0;
                        let totalHistInterest = 0;
                        let totalHistPaid = 0;

                        payHistory.forEach((item, pIdx) => {
                            const pRowNum = currentRowNum++;
                            const pRowBg = (pIdx % 2 === 0) ? 'FFFFFFFF' : 'FFF0F8FF';

                            const princ = parseFloat(item.principal_paid) || 0;
                            const intVal = parseFloat(item.interest_paid) || 0;
                            const totalVal = parseFloat(item.amount_paid) || (princ + intVal);

                            totalHistPrincipal += princ;
                            totalHistInterest += intVal;
                            totalHistPaid += totalVal;

                            worksheet.addRow([
                                pIdx + 1,
                                item.loan_account_no || '—',
                                item.lender_name || '—',
                                `Inst #${item.installment_no}`,
                                item.due_date || '—',
                                item.paid_date || '—',
                                princ,
                                intVal,
                                totalVal
                            ]);
                            worksheet.getRow(pRowNum).height = 28;

                            for (let c = 1; c <= 9; c++) {
                                const cCell = worksheet.getCell(pRowNum, c);
                                cCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: pRowBg } };
                                cCell.border = thinBorder;
                                cCell.font = { name: 'Calibri', size: 10, bold: (c === 1 || c === 2 || c === 7 || c === 8 || c === 9) };
                                cCell.alignment = { horizontal: 'center', vertical: 'middle' };

                                if (c === 7 || c === 8 || c === 9) {
                                    cCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                                    if (c === 7) cCell.font.color = { argb: 'FF047857' };
                                    if (c === 8) cCell.font.color = { argb: 'FF8A7522' };
                                    if (c === 9) cCell.font.color = { argb: 'FF000000' };
                                }
                            }
                        });

                        // Total Payment History Row
                        const totalHistRowNum = currentRowNum++;
                        worksheet.mergeCells(`A${totalHistRowNum}:F${totalHistRowNum}`);
                        worksheet.getRow(totalHistRowNum).height = 32;

                        const histLabelCell = worksheet.getCell(`A${totalHistRowNum}`);
                        histLabelCell.value = 'TOTAL SETTLED (PAGE)';
                        histLabelCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                        histLabelCell.alignment = { horizontal: 'center', vertical: 'middle' };

                        for (let c = 1; c <= 6; c++) {
                            worksheet.getCell(totalHistRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                            worksheet.getCell(totalHistRowNum, c).border = headerBorder;
                        }

                        // Col G: Principal
                        const hCellG = worksheet.getCell(`G${totalHistRowNum}`);
                        hCellG.value = totalHistPrincipal;
                        hCellG.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF34D399' } };
                        hCellG.alignment = { horizontal: 'center', vertical: 'middle' };
                        hCellG.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                        hCellG.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        hCellG.border = headerBorder;

                        // Col H: Interest
                        const hCellH = worksheet.getCell(`H${totalHistRowNum}`);
                        hCellH.value = totalHistInterest;
                        hCellH.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFBBF24' } };
                        hCellH.alignment = { horizontal: 'center', vertical: 'middle' };
                        hCellH.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                        hCellH.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        hCellH.border = headerBorder;

                        // Col I: Total Paid
                        const hCellI = worksheet.getCell(`I${totalHistRowNum}`);
                        hCellI.value = totalHistPaid;
                        hCellI.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                        hCellI.alignment = { horizontal: 'center', vertical: 'middle' };
                        hCellI.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                        hCellI.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                        hCellI.border = headerBorder;
                    }

                    // Save and Trigger Download
                    const buffer = await workbook.xlsx.writeBuffer();
                    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = 'loan-outstanding-summary-report.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(downloadUrl);

                } catch (err) {
                    console.error('Excel Export Error:', err);
                    alert('An error occurred during Excel export: ' + (err.message || 'Unknown error'));
                }
            }
        };
    }
</script>

</x-erp-layout>
