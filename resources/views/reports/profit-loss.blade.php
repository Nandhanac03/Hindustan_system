<x-erp-layout title="Profit & Loss Statement Workspace" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Profit & Loss Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Revenue inflows, direct expenses, and Net Profit outcomes filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.profit_loss') }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Profit & Loss Statement Workspace</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Dual-panel workspace balancing Expenses (Direct, Gross Profit, Indirect) on Left vs Incomes (Direct, Indirect) on Right.</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-mono font-bold">
                        Gross Margin: {{ $profitLossEntries['gross_margin_pct'] ?? 0 }}%
                    </span>
                    <span class="px-3 py-1 bg-primary/10 text-primary-800 border border-primary/20 rounded-xl text-xs font-mono font-bold">
                        Net Margin: {{ $profitLossEntries['net_margin_pct'] ?? 0 }}%
                    </span>
                </div>
            </div>

            {{-- PROMINENT NET PROFIT OUTCOME BOX --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-primary-950 rounded-2xl p-6 text-white shadow-xl border border-primary-500/30 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-primary-500/10 rounded-full blur-2xl"></div>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary-300">Calculated Final Financial Outcome</span>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tight uppercase font-sans">
                            Net Profit Result
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Net surplus generated after all direct site works, brokerage commissions, and bank financing interest.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 text-center min-w-[240px]">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-300 block mb-1">NET PROFIT</span>
                        <span class="text-3xl font-black font-mono text-emerald-400 block tracking-tight">
                            ₹{{ number_format($profitLossEntries['net_profit'] ?? 0, 2) }}
                        </span>
                        <div class="mt-2 pt-2 border-t border-white/10 flex justify-around text-[10px] text-slate-300 font-mono">
                            <span>EBITDA: <strong>₹{{ number_format($profitLossEntries['ebitda'] ?? 0, 0) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DUAL PANEL WORKSPACE LAYOUT (Expenses Left vs Incomes Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEFT PANEL: EXPENSES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            Expenses & Outflows (Left Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-rose-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Construction Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['direct'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex justify-between items-center font-bold">
                            <span class="text-emerald-900 uppercase tracking-wider text-[10px]">GROSS PROFIT (Incomes - Direct Exp.)</span>
                            <span class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['gross_profit'] ?? 0, 2) }}</span>
                        </div>

                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Administrative & Finance Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['indirect'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Expenses Outflow</span>
                        <strong class="font-mono text-rose-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['total_expenses'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- RIGHT PANEL: INCOMES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Incomes & Revenue (Right Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Project Sales Revenue</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['direct'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Surcharges & Retention Fees</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['indirect'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="profitLossMixChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-xl p-3"></div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Revenue Inflows</span>
                        <strong class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['incomes']['total_incomes'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
