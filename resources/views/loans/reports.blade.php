<x-erp-layout title="Loan Outstanding Summary" headerTitle="Loan Outstanding Summary">

<div class="max-w-[1800px] mx-auto space-y-6">
    <style>
        .reports-table thead th {
            background-color: #a38c29 !important;
            color: white !important;
            border-color: #8a7522 !important;
            font-size: 10px !important;
            font-weight: 700 !important;
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

    {{-- Header Options --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Loan Repayment Analytics & Due Alerts</h2>
            <p class="text-xs text-slate-400 mt-0.5">Trace total paid vs outstanding principal balance and view repayment schedules and alerts.</p>
        </div>
        <div>
            <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition uppercase tracking-wider shadow-xs">
                Payment Release
            </a>
        </div>
    </div>

    {{-- Dashboard Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Sanctioned Principal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 border-l-[6px] border-l-blue-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Total Sanctioned Principal</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider">Sanctioned</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-blue-700 font-mono tracking-tight block">₹{{ number_format($totalLoansAmount, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cumulative principal across all active loans.</p>
            </div>
        </div>

        {{-- Card 2: Principal Repaid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Principal Repaid</span>
                </div>
                <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 uppercase tracking-wider">Cleared</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-emerald-700 font-mono tracking-tight block">₹{{ number_format($totalPaidPrincipal, 2) }}</span>
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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Outstanding Principal</span>
                </div>
                <span class="text-[9px] text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 uppercase tracking-wider">Balance</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight block">₹{{ number_format($totalOutstanding, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">
                    @if($totalLoansAmount > 0)
                        <span class="text-rose-650 font-bold">{{ number_format(($totalOutstanding / $totalLoansAmount) * 100, 1) }}% balance</span>
                    @else
                        <span>0% balance</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Card 4: Total Interest Paid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Interest Paid to Date</span>
                </div>
                <span class="text-[9px] text-[#a38c29] font-bold bg-[#a38c29]/10 px-2 py-0.5 rounded-md border border-[#a38c29]/20 uppercase tracking-wider">Expense</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-[#a38c29] font-mono tracking-tight block">₹{{ number_format($totalInterestPaid, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cumulative interest expenses paid.</p>
            </div>
        </div>
    </div>

    {{-- Repayment progress progress bars --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Loan Repayment Progress --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Loan-wise Principal Repayment Progress</h3>
            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                @forelse($loans as $loan)
                    @php
                        $paidPct = 0;
                        $repaid = (float)$loan->principal_amount - (float)$loan->outstanding_balance;
                        if ((float)$loan->principal_amount > 0) {
                            $paidPct = ($repaid / (float)$loan->principal_amount) * 100;
                        }
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-800">{{ $loan->lender_name }} ({{ $loan->loan_account_no }})</span>
                            <span class="font-mono font-bold text-slate-500">₹{{ number_format($repaid, 0) }} / ₹{{ number_format((float)$loan->principal_amount, 0) }} ({{ number_format($paidPct, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-[#a38c29] h-2 rounded-full transition-all" style="width: {{ min(100, max(0, $paidPct)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-6">No bank loans found.</p>
                @endforelse
            </div>
        </div>

        {{-- Interest paid summary breakup --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Loan Interest Summary and Breakup</h3>
                <p class="text-xs text-slate-400 font-medium">Total interest paid expense recorded is <strong class="text-slate-800">₹{{ number_format($totalInterestPaid, 2) }}</strong>. Principal repayments have cleared <strong class="text-slate-800">₹{{ number_format($totalPaidPrincipal, 2) }}</strong>.</p>
            </div>
            <div class="border-t border-dashed border-slate-150 pt-4 space-y-2.5 text-xs font-semibold">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-semibold">Total Capital Released</span>
                    <strong class="text-slate-900 font-mono">₹{{ number_format($totalLoansAmount, 2) }}</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-semibold">Total Principal Balance Outstanding</span>
                    <strong class="text-rose-650 font-mono">₹{{ number_format($totalOutstanding, 2) }}</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-semibold">Total Repayments Made (EMI Cleared)</span>
                    <strong class="text-emerald-700 font-mono">₹{{ number_format($totalPaidPrincipal + $totalInterestPaid, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- EMIs due alert lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Due Today --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                    <span>EMI Payments Due Today</span>
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-50 border border-rose-100 text-rose-700 font-extrabold uppercase">{{ $emiDueToday->count() }} Alert</span>
            </div>
            <div class="max-h-[300px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse reports-table">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522]">
                            <th class="px-4 py-2 border text-center">LOAN ACCOUNT</th>
                            <th class="px-4 py-2 border text-center">BANK</th>
                            <th class="px-4 py-2 border text-right">DUE AMOUNT</th>
                            <th class="px-4 py-2 text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueToday as $item)
                            <tr>
                                <td class="px-4 py-3 border text-slate-900 font-bold font-mono text-center">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3 border text-slate-655 text-center">{{ $item->loan->lender_name }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-800 text-right">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3 border text-center">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="px-2.5 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded text-[10px] font-bold uppercase tracking-wider transition-all cursor-pointer">Pay</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400 italic">No EMIs due today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Due This Month --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">EMI Payments Due This Month</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-extrabold uppercase">{{ $emiDueThisMonth->count() }} Due</span>
            </div>
            <div class="max-h-[300px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse reports-table">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522]">
                            <th class="px-4 py-2 border text-center">LOAN ACCOUNT</th>
                            <th class="px-4 py-2 border text-center">DUE DATE</th>
                            <th class="px-4 py-2 border text-right">DUE AMOUNT</th>
                            <th class="px-4 py-2 text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueThisMonth as $item)
                            <tr>
                                <td class="px-4 py-3 border text-slate-900 font-bold font-mono text-center">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3 border text-slate-655 text-center">{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-800 text-right">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3 border text-center">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded text-[10px] font-bold uppercase tracking-wider transition-all cursor-pointer">Schedule</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400 italic">No EMIs due this month.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</x-erp-layout>
