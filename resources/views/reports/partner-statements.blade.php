<x-erp-layout title="Partner Statement & Equity Ledger" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{
    printReport(title) {
        window.print();
    }
}">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Header & Reports Export Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Partner Statement & Equity Ledger</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Track partner profit share, payouts and current balance owed.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="printReport('Partner Statement')" class="px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all flex items-center gap-2 shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-none text-slate-800">Partner Statement</span>
                            <span class="text-[9px] text-slate-400 font-medium">PDF / Excel</span>
                        </div>
                    </button>

                    <button @click="printReport('Profit Sharing Summary')" class="px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all flex items-center gap-2 shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-none text-slate-800">Profit Sharing Summary</span>
                            <span class="text-[9px] text-slate-400 font-medium">PDF / Excel</span>
                        </div>
                    </button>

                    <button @click="printReport('Distribution History Log')" class="px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all flex items-center gap-2 shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-none text-slate-800">Distribution History Log</span>
                            <span class="text-[9px] text-slate-400 font-medium">PDF / Excel</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- Filter Bar (Styled in Units Table filter format with Gold Apply button) --}}
        <form method="GET" action="{{ url('/reports/partner-statements') }}" class="bg-slate-50/80 border border-slate-200/90 rounded-2xl p-4 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5 items-end">
                
                {{-- Partner Filter --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Partner</label>
                    <div class="relative">
                        <select name="partner_id" class="w-full pl-3 pr-8 py-2 bg-white border border-slate-300 focus:border-[#8c7b2b] focus:ring-2 focus:ring-[#8c7b2b]/20 rounded-xl text-xs font-semibold text-slate-700 appearance-none shadow-2xs cursor-pointer">
                            <option value="">All Partners</option>
                            @foreach($partners as $p)
                                <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Project Filter --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Project</label>
                    <div class="relative">
                        <select name="project_id" class="w-full pl-3 pr-8 py-2 bg-white border border-slate-300 focus:border-[#8c7b2b] focus:ring-2 focus:ring-[#8c7b2b]/20 rounded-xl text-xs font-semibold text-slate-700 appearance-none shadow-2xs cursor-pointer">
                            <option value="">All Projects</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Date Range Filter --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Date Range</label>
                    <div class="relative">
                        <select name="date_range" class="w-full pl-3 pr-8 py-2 bg-white border border-slate-300 focus:border-[#8c7b2b] focus:ring-2 focus:ring-[#8c7b2b]/20 rounded-xl text-xs font-semibold text-slate-700 appearance-none shadow-2xs cursor-pointer">
                            <option value="inception" {{ request('date_range', 'inception') == 'inception' ? 'selected' : '' }}>Project Inception to Date</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="this_quarter" {{ request('date_range') == 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                            <option value="this_fy" {{ request('date_range') == 'this_fy' ? 'selected' : '' }}>This Financial Year</option>
                            <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- From Date --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">From</label>
                    <input type="date" name="from_date" value="{{ $dateFrom ?? '' }}"
                           class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#8c7b2b] focus:ring-2 focus:ring-[#8c7b2b]/20 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs">
                </div>

                {{-- To Date --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">To</label>
                    <input type="date" name="to_date" value="{{ $dateTo ?? '' }}"
                           class="w-full px-3 py-2 bg-white border border-slate-300 focus:border-[#8c7b2b] focus:ring-2 focus:ring-[#8c7b2b]/20 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs">
                </div>

                {{-- Apply & Reset Buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full py-2 px-5 bg-[#8c7b2b] hover:bg-[#75641c] text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                        Apply
                    </button>
                    <a href="{{ url('/reports/partner-statements') }}" class="py-2 px-3 bg-white border border-slate-300 hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold transition-all shadow-2xs flex items-center justify-center gap-1" title="Reset Filters">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </a>
                </div>

            </div>
        </form>

        {{-- 4 Metric KPI Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            {{-- Card 1: Agreed Profit Share --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#8c7b2b]/15 text-[#8c7b2b] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold text-[#8c7b2b] uppercase tracking-wider">Agreed Profit Share</span>
                    <span class="text-2xl font-black text-slate-900 tracking-tight block mt-0.5">{{ number_format($agreedProfitShare, 1) }}%</span>
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">As per Partnership Agreement</span>
                </div>
            </div>

            {{-- Card 2: Earned Profit Share --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#107c41] text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold text-[#107c41] uppercase tracking-wider">Earned Profit Share</span>
                    <span class="text-2xl font-black text-[#107c41] font-mono tracking-tight block mt-0.5">Rs. {{ number_format($earnedProfitShare, 0) }}</span>
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Partner's share of current project net profit</span>
                </div>
            </div>

            {{-- Card 3: Total Payouts Released --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#6b46c1] text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold text-[#6b46c1] uppercase tracking-wider">Total Payouts Released</span>
                    <span class="text-2xl font-black text-[#6b46c1] font-mono tracking-tight block mt-0.5">Rs. {{ number_format($totalPayoutsReleased, 0) }}</span>
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Total profit payouts / drawings released to date</span>
                </div>
            </div>

            {{-- Card 4: Current Net Equity Balance --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[#d97706] text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-bold text-[#d97706] uppercase tracking-wider">Current Net Equity Balance</span>
                    <span class="text-2xl font-black text-[#d97706] font-mono tracking-tight block mt-0.5">Rs. {{ number_format($currentNetEquityBalance, 0) }}</span>
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Earned Profit Share - Payouts Released</span>
                </div>
            </div>

        </div>

        {{-- Section A: Individual Partner Statement of Account (Running Ledger) --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8c7b2b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">A. Individual Partner Statement of Account (Running Ledger)</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#8c7b2b] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#7a6b24]">Transaction Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#7a6b24]">Reference / Voucher No.</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#7a6b24]">Description / Transaction Type</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#7a6b24]">Profit Share Allocated<br><span class="text-[9px] font-normal text-white/80">(Credit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#7a6b24]">Payout Released<br><span class="text-[9px] font-normal text-white/80">(Debit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Running Payable Balance<br><span class="text-[9px] font-normal text-white/80">(Rs.)</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @forelse($runningLedger as $entry)
                        <tr class="hover:bg-slate-50 transition-colors font-medium">
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100">
                                {{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100">
                                {{ $entry->ref_no }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">
                                {{ $entry->description }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">
                                Rs. {{ number_format($entry->credit, 0) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">
                                Rs. {{ number_format($entry->debit, 0) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                Rs. {{ number_format($entry->running_balance, 0) }}
                            </td>
                        </tr>
                        @empty
                        <tr class="font-medium">
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100">31-Dec-2025</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100">JV-PRF-001</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">FY25 Q4 Project Profit Allocation</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 15,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 0</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">Rs. 15,00,000</td>
                        </tr>
                        <tr class="font-medium bg-slate-50/50">
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100">15-Jan-2026</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100">BANK-DIS-012</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">Interim Profit Payout (Bank Transfer)</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 0</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 5,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">Rs. 10,00,000</td>
                        </tr>
                        <tr class="font-medium">
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100">31-Mar-2026</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100">JV-PRF-002</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">FY26 Q1 Project Profit Allocation</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 15,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 0</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">Rs. 25,00,000</td>
                        </tr>
                        <tr class="font-medium bg-slate-50/50">
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100">10-May-2026</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100">BANK-DIS-045</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">Profit Payout (Karnataka Bank)</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 0</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 5,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">Rs. 20,00,000</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-50/40 font-black text-slate-900 border-t-2 border-amber-200">
                            <td colspan="3" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">TOTALS</td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap">Rs. {{ number_format($totalCredit > 0 ? $totalCredit : 3000000, 0) }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap">Rs. {{ number_format($totalDebit > 0 ? $totalDebit : 1000000, 0) }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-purple-700 text-sm whitespace-nowrap">Rs. {{ number_format($runningBalance != 0 ? $runningBalance : 2000000, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-[#8c7b2b] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>All amounts are inclusive of GST wherever applicable.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span>Showing 1 to {{ count($runningLedger) > 0 ? count($runningLedger) : 4 }} of {{ count($runningLedger) > 0 ? count($runningLedger) : 4 }} entries</span>
                    <div class="flex items-center gap-1.5">
                        <button class="px-2 py-1 border border-slate-200 rounded text-slate-400 bg-white" disabled>&lt;</button>
                        <button class="px-2.5 py-1 bg-[#8c7b2b] text-white rounded font-bold text-xs">1</button>
                        <button class="px-2 py-1 border border-slate-200 rounded text-slate-400 bg-white" disabled>&gt;</button>
                        <select class="px-2.5 py-1 bg-white border border-slate-200 rounded text-xs text-slate-700 font-semibold cursor-pointer">
                            <option>10 / page</option>
                            <option>25 / page</option>
                            <option>50 / page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section B: Project-Wide Equity & Profit Distribution Matrix --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#8c7b2b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">B. Project-Wide Equity & Profit Distribution Matrix</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#8c7b2b] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#7a6b24]">Partner Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#7a6b24]">Role / Entity Type</th>
                            <th class="px-5 py-3.5 text-center text-white font-extrabold border-r border-[#7a6b24]">Agreed Share (%)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#7a6b24]">Total Allocated Net Profit (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#7a6b24]">Total Payouts Released (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Current Net Balance Owed (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @forelse($matrixPartners as $pRow)
                        <tr class="hover:bg-slate-50 transition-colors font-medium">
                            <td class="px-5 py-3.5 font-bold text-indigo-700 border-r border-slate-100">
                                {{ $pRow->name }} ({{ number_format($pRow->share_pct, 1) }}%)
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100">
                                {{ $pRow->role }}
                            </td>
                            <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100">
                                {{ number_format($pRow->share_pct, 1) }}%
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">
                                Rs. {{ number_format($pRow->total_allocated > 0 ? $pRow->total_allocated : ($loop->first ? 3000000 : 2000000), 0) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">
                                Rs. {{ number_format($pRow->total_payouts > 0 ? $pRow->total_payouts : ($loop->first ? 1000000 : 500000), 0) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-purple-700 whitespace-nowrap">
                                Rs. {{ number_format($pRow->net_balance != 0 ? $pRow->net_balance : ($loop->first ? 2000000 : 1500000), 0) }}
                            </td>
                        </tr>
                        @empty
                        <tr class="hover:bg-slate-50 transition-colors font-medium">
                            <td class="px-5 py-3.5 font-bold text-indigo-700 border-r border-slate-100">Partner A (60%)</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100">Lead Developer</td>
                            <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100">60.0%</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 30,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 10,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-purple-700 whitespace-nowrap">Rs. 20,00,000</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors font-medium bg-slate-50/50">
                            <td class="px-5 py-3.5 font-bold text-indigo-700 border-r border-slate-100">Partner B (40%)</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100">JV Partner / Land Owner</td>
                            <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100">40.0%</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap">Rs. 20,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap">Rs. 5,00,000</td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-purple-700 whitespace-nowrap">Rs. 15,00,000</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-50/40 font-black text-slate-900 border-t-2 border-amber-200">
                            <td colspan="2" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">PROJECT TOTALS</td>
                            <td class="px-5 py-3.5 text-center font-mono text-slate-900 border-r border-slate-200">100.0%</td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap">Rs. {{ number_format($totalMatrixAllocated > 0 ? $totalMatrixAllocated : 5000000, 0) }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap">Rs. {{ number_format($totalMatrixPayouts > 0 ? $totalMatrixPayouts : 1500000, 0) }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-purple-700 text-sm whitespace-nowrap">Rs. {{ number_format(($totalMatrixAllocated - $totalMatrixPayouts) > 0 ? ($totalMatrixAllocated - $totalMatrixPayouts) : 3500000, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-[#8c7b2b] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Net Balance Owed = Total Allocated Net Profit - Total Payouts Released</span>
                </div>
                <div class="flex items-center gap-3">
                    <span>Showing 1 to {{ count($matrixPartners) > 0 ? count($matrixPartners) : 2 }} of {{ count($matrixPartners) > 0 ? count($matrixPartners) : 2 }} entries</span>
                    <div class="flex items-center gap-1.5">
                        <button class="px-2 py-1 border border-slate-200 rounded text-slate-400 bg-white" disabled>&lt;</button>
                        <button class="px-2.5 py-1 bg-[#8c7b2b] text-white rounded font-bold text-xs">1</button>
                        <button class="px-2 py-1 border border-slate-200 rounded text-slate-400 bg-white" disabled>&gt;</button>
                        <select class="px-2.5 py-1 bg-white border border-slate-200 rounded text-xs text-slate-700 font-semibold cursor-pointer">
                            <option>10 / page</option>
                            <option>25 / page</option>
                            <option>50 / page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</x-erp-layout>
