<x-erp-layout title="Bank Loan Dashboard" headerTitle="Bank Loan Repayment Analytics">

<div class="max-w-[1800px] mx-auto space-y-6">
    {{-- Header Options --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Loan Repayment Analytics & Due Alerts</h2>
            <p class="text-xs text-slate-400 mt-0.5">Filter analytics across all projects or select a specific project to trace paid vs outstanding principal.</p>
        </div>
        <div>
            <form method="GET" action="{{ route('loans.reports') }}" class="flex items-center gap-2">
                <select name="project_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all">
                    <option value="">All Projects...</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-[#a38c29] text-white hover:bg-[#8a7522] rounded-xl text-xs font-bold transition uppercase tracking-wide">
                    Bank Loans Master
                </a>
            </form>
        </div>
    </div>

    {{-- Dashboard Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Card 1: Total Loan Value --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Loans Principal</span>
            <strong class="text-base font-black text-slate-900 block mt-2 font-mono">₹{{ number_format($totalLoansAmount, 2) }}</strong>
            <span class="text-[10px] text-slate-400 mt-1 block">Sanctioned capital</span>
        </div>

        {{-- Card 2: Total Principal Repaid --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Principal Repaid</span>
            <strong class="text-base font-black text-emerald-700 block mt-2 font-mono">₹{{ number_format($totalPaidPrincipal, 2) }}</strong>
            @if($totalLoansAmount > 0)
                <span class="text-[10px] text-emerald-600 mt-1 block font-bold">{{ number_format(($totalPaidPrincipal / $totalLoansAmount) * 100, 1) }}% completed</span>
            @else
                <span class="text-[10px] text-slate-400 mt-1 block">0% completed</span>
            @endif
        </div>

        {{-- Card 3: Outstanding Principal --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Outstanding Principal</span>
            <strong class="text-base font-black text-rose-700 block mt-2 font-mono">₹{{ number_format($totalOutstanding, 2) }}</strong>
            @if($totalLoansAmount > 0)
                <span class="text-[10px] text-rose-650 mt-1 block font-bold">{{ number_format(($totalOutstanding / $totalLoansAmount) * 100, 1) }}% balance</span>
            @else
                <span class="text-[10px] text-slate-400 mt-1 block">0% balance</span>
            @endif
        </div>

        {{-- Card 4: Total Interest Paid --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <span class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest block font-bold">Total Interest Paid</span>
            <strong class="text-base font-black text-slate-950 block mt-2 font-mono" style="color: #a38c29;">₹{{ number_format($totalInterestPaid, 2) }}</strong>
            <span class="text-[10px] text-slate-400 mt-1 block">Cumulative interest expense</span>
        </div>
    </div>

    {{-- Repayment progress progress bars --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Loan Repayment Progress --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
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
                            <div class="bg-emerald-600 h-2 rounded-full transition-all" style="width: {{ min(100, max(0, $paidPct)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-6">No bank loans found.</p>
                @endforelse
            </div>
        </div>

        {{-- Interest paid summary breakup --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2">Loan Interest Summary and Breakup</h3>
                <p class="text-xs text-slate-400">Total interest paid expense recorded is <strong class="text-slate-800">₹{{ number_format($totalInterestPaid, 2) }}</strong>. Principal repayments have cleared <strong class="text-slate-800">₹{{ number_format($totalPaidPrincipal, 2) }}</strong>.</p>
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                    <span>EMI Payments Due Today</span>
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-50 border border-rose-100 text-rose-700 font-extrabold uppercase">{{ $emiDueToday->count() }} Alert</span>
            </div>
            <div class="max-h-[300px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2 border">LOAN ACCOUNT</th>
                            <th class="px-4 py-2 border">BANK</th>
                            <th class="px-4 py-2 border text-right">DUE AMOUNT</th>
                            <th class="px-4 py-2 text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueToday as $item)
                            <tr>
                                <td class="px-4 py-3 border text-slate-900 font-bold font-mono">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3 border text-slate-655">{{ $item->loan->lender_name }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-800 text-right">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3 border text-right">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="px-2.5 py-1 bg-[#a38c29] text-white rounded text-[10px] font-bold uppercase tracking-wider">Pay</a>
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">EMI Payments Due This Month</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-extrabold uppercase">{{ $emiDueThisMonth->count() }} Due</span>
            </div>
            <div class="max-h-[300px] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="px-4 py-2 border">LOAN ACCOUNT</th>
                            <th class="px-4 py-2 border">DUE DATE</th>
                            <th class="px-4 py-2 border text-right">DUE AMOUNT</th>
                            <th class="px-4 py-2 text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-semibold text-slate-700">
                        @forelse($emiDueThisMonth as $item)
                            <tr>
                                <td class="px-4 py-3 border text-slate-900 font-bold font-mono">{{ $item->loan->loan_account_no }}</td>
                                <td class="px-4 py-3 border text-slate-655">{{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '—' }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-800 text-right">₹{{ number_format($item->emi_amount - $item->amount_paid, 2) }}</td>
                                <td class="px-4 py-3 border text-right">
                                    <a href="{{ route('loans.schedule', $item->loan_id) }}" class="px-2.5 py-1 bg-slate-900 text-white rounded text-[10px] font-bold uppercase tracking-wider">Schedule</a>
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
