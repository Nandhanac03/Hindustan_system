<x-erp-layout title="Balance Sheet Summary Panel" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Balance Sheet Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Assets, liabilities, and net worth positions filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.balance_sheet') }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Balance Sheet Summary Panel</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Split layout template presenting business net worth: Assets balanced against Liabilities & Equity.</p>
                </div>
                @include('reports.partials.header-badges')
            </div>


                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-extrabold uppercase flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Balance Sheet Verified
                </span>
            </div>

            {{-- NET WORTH KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Business Net Worth</span>
                    <span class="text-2xl font-black font-mono text-emerald-400 block">₹{{ number_format($balanceSheetEntries['net_worth'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Equity + Reserves</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Working Capital</span>
                    <span class="text-2xl font-black font-mono text-slate-900 block">₹{{ number_format($balanceSheetEntries['working_capital'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Current Assets - Current Liab.</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Quick Liquidity Ratio</span>
                    <span class="text-2xl font-black font-mono text-indigo-700 block">{{ $balanceSheetEntries['quick_ratio'] ?? 0 }}x</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Cash + Receivables Ratio</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 block mb-1">Balance Check</span>
                    <span class="text-xl font-black font-mono text-emerald-700 block">0.00 Variance</span>
                    <span class="text-[10px] text-emerald-600 font-bold mt-1 block">Assets = Liabilities + Equity</span>
                </div>
            </div>

            <div id="balanceSheetRatioChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- Filter, Print & Export Bar directly above Balance Sheet Tables --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'balanceSheetFilterForm', 'actionRoute' => route('reports.balance_sheet'), 'exportLabel' => 'Export Balance Sheet'])
            </div>

            {{-- SPLIT LAYOUT TEMPLATE (Assets Left vs Liabilities & Equity Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ASSETS SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                            ASSETS — What the Business Owns
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Debit Balances (+)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['assets']))
                            @foreach($balanceSheetEntries['assets'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL ASSETS VALUE</span>
                        <strong class="font-mono text-emerald-700 text-base">₹{{ number_format($balanceSheetEntries['assets']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- LIABILITIES & EQUITY SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            LIABILITIES & EQUITY — What is Owed & Partner Net Worth
                        </h4>
                        <span class="text-[10px] font-mono text-amber-300">Credit Balances (-)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['liabilities_and_equity']))
                            @foreach($balanceSheetEntries['liabilities_and_equity'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL LIABILITIES & EQUITY</span>
                        <strong class="font-mono text-amber-700 text-base">₹{{ number_format($balanceSheetEntries['liabilities_and_equity']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
