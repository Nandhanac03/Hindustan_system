<x-erp-layout title="Project Margin Analysis Workspace" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Sub Navigation Bar --}}
    @include('reports.partials.nav')

    {{-- Top Executive Header & Action Bar (Matches Treasury Dashboard Style) --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29]/20 to-[#a38c29]/5 text-[#8a7522] flex items-center justify-center text-xl shrink-0 shadow-2xs border border-[#a38c29]/30">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-black text-slate-900 tracking-tight">Project Margin & Financial Intelligence</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Real-Time Analytics</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Comprehensive project profitability, cash flow matrix, revenue stream breakdown, and partner equity analysis.</p>
            </div>
        </div>
    </div>

    {{-- PROJECT MARGIN REPORT CONTENT LOOP --}}
    @foreach($marginAnalysis as $projData)
    <div class="space-y-6">
        
        {{-- Full-Width Project Location & Area Header Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-2xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] border border-amber-200 flex items-center justify-center font-black text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="text-sm font-black text-slate-900 uppercase tracking-wide">{{ $projData->name ?? 'Project Portfolio' }}</span>
            </div>

            <div class="text-xs font-semibold text-slate-700 flex flex-wrap items-center gap-2.5 bg-slate-50 px-4 py-1.5 rounded-xl border border-slate-200">
                <span class="flex items-center gap-1.5 text-[#a38c29] font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <strong>{{ $projData->location }}</strong>
                </span>
                <span class="text-slate-300">•</span>
                <span>Total Area: <strong class="text-slate-900 font-mono">{{ number_format($projData->total_area, 0) }} Sq.Ft.</strong></span>
                <span class="text-slate-300">•</span>
                <span class="text-emerald-700 font-extrabold">Sold: {{ number_format($projData->sold_pct, 1) }}%</span>
                <span class="text-slate-300">|</span>
                <span class="text-amber-700 font-extrabold">Unsold: {{ number_format($projData->unsold_pct, 1) }}%</span>
            </div>
        </div>

        {{-- 4 Executive KPI Summary Cards Grid (Exact Treasury Dashboard Design) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Total Collections --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Collections</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        ₹ {{ number_format($projData->realized_collections, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Received from buyers to date</p>
                </div>
            </div>

            {{-- Card 2: Pending Receivables --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-indigo-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(99,102,241,0.15)] cursor-default">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/60 transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Pending Receivables</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-indigo-600 font-mono tracking-tight block group-hover:text-indigo-700 transition-colors duration-300">
                        ₹ {{ number_format($projData->pending_receivables, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Remaining balance due from buyers</p>
                </div>
            </div>

            {{-- Card 3: Available Flats Market Value --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-default">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Available Flats Market Value</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-amber-600 transition-colors duration-300">
                        ₹ {{ number_format($projData->projected_unsold_val, 2) }}
                    </span>
                    <p class="text-[9px] text-amber-700 font-bold mt-1.5">
                        <span class="bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60 inline-block">{{ number_format($projData->unsold_area, 0) }} Sq.Ft left @ ₹{{ number_format($projData->current_market_rate, 0) }}/Sq.Ft</span>
                    </p>
                </div>
            </div>

            {{-- Card 4: Total Project Expected Value --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Project Expected Value</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        ₹ {{ number_format($projData->total_gross_revenue, 2) }}
                    </span>
                    <div class="flex items-center justify-between text-[9px] text-slate-400 mt-1.5 font-medium">
                        <span>Cost: ₹{{ number_format($projData->cost_per_sqft, 0) }}/Sq.Ft</span>
                        <span class="text-emerald-600 font-extrabold">Net Profit: ₹{{ number_format($projData->net_profit / 10000000, 2) }} Cr</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- COST BREAKDOWN & CASH FLOW MATRIX TABLE CARD (Styled like Treasury Directory Table) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Cost Breakdown & Cash Flow Matrix</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Detailed financial matrix of incurred costs, cash paid out, pending liabilities, and per sq.ft benchmarks.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left data-matrix-table border-collapse">
                    <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider sticky top-0 z-10">
                        <tr class="erp-table-header border-b border-slate-700 text-left">
                            <th class="w-4/12 px-5 py-3.5 erp-table-header border-r border-slate-600 text-left text-white font-extrabold tracking-wider">EXPENSE CATEGORY</th>
                            <th class="w-2/12 px-5 py-3.5 erp-table-header border-r border-slate-600 text-right text-white font-extrabold tracking-wider">
                                <div>TOTAL INCURRED COST</div>
                                <div class="text-[9px] text-amber-200/90 font-medium lowercase tracking-normal">(total billed cost)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 erp-table-header border-r border-slate-600 text-right text-white font-extrabold tracking-wider">
                                <div>CASH PAID OUT</div>
                                <div class="text-[9px] text-emerald-300 font-medium lowercase tracking-normal">(total paid out)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 erp-table-header border-r border-slate-600 text-right text-white font-extrabold tracking-wider">
                                <div>PENDING PAYABLE</div>
                                <div class="text-[9px] text-rose-300 font-medium lowercase tracking-normal">(liability balance)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 erp-table-header border-r border-slate-600 text-right text-white font-extrabold tracking-wider">
                                <div>COST / SQ.FT.</div>
                                <div class="text-[9px] text-amber-200/90 font-medium lowercase tracking-normal">(rs./sq.ft.)</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                        @foreach($projData->cost_matrix as $row)
                            <tr class="hover:bg-amber-50/40 transition-colors bg-white">
                                <td class="px-5 py-3.5 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $row['category'] }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                    ₹{{ number_format($row['incurred'], 2) }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-700 whitespace-nowrap">
                                    ₹{{ number_format($row['spent'], 2) }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold whitespace-nowrap {{ $row['payable'] > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                    ₹{{ number_format($row['payable'], 2) }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                    ₹{{ number_format($row['cost_per_sqft'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-amber-50/80 text-slate-900 font-black text-xs uppercase border-t-2 border-b border-[#a38c29]">
                        <tr>
                            <td class="px-5 py-3.5 text-slate-900 font-black tracking-wider text-xs">
                                TOTAL PROJECT EXPENSES & COST BENCHMARK
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 text-xs whitespace-nowrap">
                                ₹{{ number_format($projData->total_incurred_cost, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-emerald-800 text-xs whitespace-nowrap">
                                ₹{{ number_format($projData->total_cash_paid, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-rose-700 text-xs whitespace-nowrap">
                                ₹{{ number_format($projData->total_pending_payable, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono font-black text-[#a38c29] text-xs whitespace-nowrap">
                                ₹{{ number_format($projData->cost_per_sqft, 2) }} <span class="text-[10px] text-[#7a671b] font-bold whitespace-nowrap">/ Sq.Ft.</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 space-y-5">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Equity ownership shares, projected net profit pool, and live partner ledger balances.</p>
                    </div>
                </div>

                {{-- Summary Pool Badges --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="px-3.5 py-1.5 bg-slate-50 rounded-xl border border-slate-200 text-right">
                        <span class="text-[8.5px] font-extrabold uppercase text-slate-500 tracking-wider block">Realized Collections</span>
                        <span class="text-xs font-mono font-black text-slate-800">₹{{ number_format($projData->realized_collections, 2) }}</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-amber-50/80 rounded-xl border border-amber-200 text-right">
                        <span class="text-[8.5px] font-extrabold uppercase text-[#7a671b] tracking-wider block">Total Net Profit Pool</span>
                        <span class="text-xs font-mono font-black text-emerald-800">₹{{ number_format($projData->net_profit, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Partner Cards Grid -->
            @php
                $partnerCount = count($projData->partners_breakdown);
                $gridColsClass = $partnerCount === 1 ? 'grid-cols-1' : ($partnerCount === 2 ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3');
            @endphp
            <div class="grid {{ $gridColsClass }} gap-5">
                @foreach($projData->partners_breakdown as $index => $partner)
                    @php
                        $badgeColor = $index % 2 === 0 ? 'bg-amber-50 text-[#7a671b] border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        $accentBar = $index % 2 === 0 ? 'bg-[#a38c29]' : 'bg-emerald-600';
                    @endphp
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col justify-between space-y-4 transition-all hover:border-[#a38c29] hover:shadow-lg relative overflow-hidden group">
                        
                        <!-- Top Row: Partner Info -->
                        <div class="flex items-center justify-between gap-3 pb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#a38c29] font-mono font-black text-xs flex items-center justify-center border border-amber-200 group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($partner->partner_name, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $partner->partner_name }}</h4>
                                    <span class="text-[10px] font-semibold text-slate-400">Equity Partner</span>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 rounded-full text-xs font-black border uppercase {{ $badgeColor }}">
                                {{ number_format($partner->share_pct, 1) }}% Share
                            </span>
                        </div>

                        <!-- Partner Progress Line -->
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase text-slate-400">
                                <span>Profit Allocation Ratio</span>
                                <span class="font-mono text-slate-700 font-bold">{{ number_format($partner->share_pct, 1) }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $accentBar }} rounded-full" style="width: {{ $partner->share_pct }}%"></div>
                            </div>
                        </div>

                        <!-- 1. Projected Lifetime Profit (100% Sold Projection) -->
                        <div class="p-3 bg-gradient-to-r from-amber-50/60 to-white rounded-xl border border-amber-200/80">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-600">Projected Lifetime Net Profit</span>
                                <span class="text-[9px] font-bold text-[#8a7522] uppercase bg-[#a38c29]/15 px-2 py-0.5 rounded border border-[#a38c29]/30">100% Sold Projection</span>
                            </div>
                            <div class="text-lg sm:text-xl font-mono font-black text-slate-900 mt-1 whitespace-nowrap">
                                ₹{{ number_format($partner->profit_share, 2) }}
                            </div>
                        </div>

                        <!-- 2. Real-Time Cash & Bank Liquidity (From Partner Management) -->
                        <div class="pt-1 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-700">Realized Collections & Bank Balance</span>
                                <span class="text-[9px] font-bold text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Live Bank Ledger</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80">
                                    <span class="text-[8.5px] font-extrabold uppercase tracking-wider text-slate-500 block">Total Collected</span>
                                    <span class="text-xs font-mono font-black text-emerald-700 block mt-0.5 truncate" title="₹{{ number_format($partner->total_collected ?? 0, 2) }}">
                                        ₹{{ number_format($partner->total_collected ?? 0, 2) }}
                                    </span>
                                </div>
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80">
                                    <span class="text-[8.5px] font-extrabold uppercase tracking-wider text-slate-500 block">Total Payouts</span>
                                    <span class="text-xs font-mono font-black text-rose-700 block mt-0.5 truncate" title="₹{{ number_format($partner->payouts_released ?? 0, 2) }}">
                                        ₹{{ number_format($partner->payouts_released ?? 0, 2) }}
                                    </span>
                                </div>
                                <div class="p-2.5 bg-[#F6F3E9] rounded-xl border border-[#a38c29]/40">
                                    <span class="text-[8.5px] font-extrabold uppercase tracking-wider text-[#7a671b] block">Net Bank Balance</span>
                                    <span class="text-xs font-mono font-black text-[#5c4a10] block mt-0.5 truncate" title="₹{{ number_format($partner->net_balance ?? 0, 2) }}">
                                        ₹{{ number_format($partner->net_balance ?? 0, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

            <!-- ── DYNAMIC REAL-DATA ANALYTICS & CHARTS SECTION ── -->
            <div class="py-6 bg-white space-y-6">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Card 1: EXPENSE BREAKDOWN BY CATEGORY (Horizontal Bar Chart) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <div class="p-1.5 bg-amber-50 text-[#a38c29] rounded-lg border border-amber-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <span>EXPENSE BREAKDOWN BY CATEGORY</span>
                            </h3>
                        </div>
                        
                        <div class="py-3">
                            <div id="expenseCategoryChart_{{ $loop->index }}" class="w-full h-[240px]"></div>
                        </div>

                        <div class="pt-2 text-center text-[10.5px] font-semibold text-slate-500 border-t border-slate-100">
                            Total Incurred Cost: <span class="text-slate-900 font-mono font-bold">₹{{ number_format($projData->total_incurred_cost, 2) }}</span>
                        </div>
                    </div>

                    <!-- Card 2: REVENUE STREAM COMPOSITION (Donut Chart) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                </div>
                                <span>REVENUE STREAM COMPOSITION</span>
                            </h3>
                        </div>
                        
                        <div class="py-3 flex items-center justify-center">
                            <div id="revenueCompositionChart_{{ $loop->index }}" class="w-full h-[240px]"></div>
                        </div>

                        <div class="pt-2 text-center text-[10.5px] font-semibold text-slate-500 border-t border-slate-100">
                            Total Expected Value: <span class="text-emerald-700 font-mono font-extrabold">₹{{ number_format($projData->total_gross_revenue, 2) }}</span>
                        </div>
                    </div>

                    <!-- Card 3: INVENTORY BREAKDOWN (Donut Chart with Side Cards) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <div class="p-1 bg-amber-50 text-[#a38c29] rounded border border-amber-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                </div>
                                <span>INVENTORY BREAKDOWN</span>
                            </h3>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-2">
                            <div id="inventoryDonutChart_{{ $loop->index }}" class="w-1/2 min-w-[160px] h-[190px]"></div>
                            <div class="w-1/2 space-y-3 text-xs font-semibold">
                                <div class="p-3 rounded-xl bg-white border-2 border-emerald-500 shadow-2xs">
                                    <div class="flex items-center gap-1.5 text-emerald-800 font-bold">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Sold Area
                                    </div>
                                    <div class="text-slate-900 font-mono font-extrabold mt-1 text-sm">{{ number_format($projData->sold_area, 0) }} Sq.Ft.</div>
                                    <div class="text-xs text-emerald-700 font-bold">({{ number_format($projData->sold_pct, 1) }}%)</div>
                                </div>
                                <div class="p-3 rounded-xl bg-white border-2 border-[#a38c29] shadow-2xs">
                                    <div class="flex items-center gap-1.5 text-[#7a671b] font-bold">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29]"></span> Unsold Area
                                    </div>
                                    <div class="text-slate-900 font-mono font-extrabold mt-1 text-sm">{{ number_format($projData->unsold_area, 0) }} Sq.Ft.</div>
                                    <div class="text-xs text-[#7a671b] font-bold">({{ number_format($projData->unsold_pct, 1) }}%)</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-center text-xs font-semibold text-slate-500 border-t border-slate-100">
                            Total Area: <span class="text-slate-900 font-mono font-bold">{{ number_format($projData->total_area, 0) }} Sq.Ft.</span>
                        </div>
                    </div>

    </div>
    @endforeach

</div>

<!-- ── EXPORT TO EXCEL SCRIPT & APEXCHARTS ── -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    @foreach($marginAnalysis as $projData)
    
    // 1. Expense Breakdown by Category (Horizontal Bar Chart from real COA Cost Matrix)
    @php
        $costCategories = array_map(fn($row) => $row['category'], $projData->cost_matrix);
        $costIncurred   = array_map(fn($row) => round($row['incurred'], 2), $projData->cost_matrix);
        $costSpent      = array_map(fn($row) => round($row['spent'], 2), $projData->cost_matrix);
    @endphp

    const costCategories_{{ $loop->index }} = @json($costCategories);
    const costIncurred_{{ $loop->index }}   = @json($costIncurred);
    const costSpent_{{ $loop->index }}      = @json($costSpent);

    const expenseChartOptions_{{ $loop->index }} = {
        series: [
            { name: 'Total Incurred Cost', data: costIncurred_{{ $loop->index }} },
            { name: 'Cash Paid Out', data: costSpent_{{ $loop->index }} }
        ],
        chart: {
            type: 'bar',
            height: 240,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '42%',
                borderRadius: 4
            }
        },
        colors: ['#a38c29', '#10b981'], // Brand Gold (Incurred) & Emerald Green (Paid Out)
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: costCategories_{{ $loop->index }},
            labels: {
                style: { fontSize: '9px', fontWeight: 700, colors: '#475569' },
                rotate: -20,
                trim: true
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val >= 10000000) return '₹' + (val / 10000000).toFixed(1) + ' Cr';
                    if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + ' L';
                    if (val === 0) return '₹0';
                    return '₹' + val.toLocaleString('en-IN');
                },
                style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }
            }
        },
        legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', fontWeight: 600 },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                }
            }
        }
    };
    if (document.querySelector("#expenseCategoryChart_{{ $loop->index }}")) {
        new ApexCharts(document.querySelector("#expenseCategoryChart_{{ $loop->index }}"), expenseChartOptions_{{ $loop->index }}).render();
    }

    // 2. Revenue Stream Composition (Donut Chart from real Revenue metrics)
    const revStreamOptions_{{ $loop->index }} = {
        series: [
            {{ round($projData->realized_collections, 2) }},
            {{ round($projData->pending_receivables, 2) }},
            {{ round($projData->projected_unsold_val, 2) }}
        ],
        labels: ['Realized Collections', 'Pending Receivables', 'Unsold Market Value'],
        chart: {
            type: 'donut',
            height: 240
        },
        colors: ['#10b981', '#6366f1', '#f59e0b'],
        dataLabels: { enabled: false },
        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Revenue',
                            fontSize: '10px',
                            fontWeight: '700',
                            color: '#64748b',
                            formatter: function () {
                                return '₹' + {{ round($projData->total_gross_revenue / 10000000, 2) }} + ' Cr';
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                }
            }
        }
    };
    if (document.querySelector("#revenueCompositionChart_{{ $loop->index }}")) {
        new ApexCharts(document.querySelector("#revenueCompositionChart_{{ $loop->index }}"), revStreamOptions_{{ $loop->index }}).render();
    }

    // 3. Inventory Breakdown Chart (Donut Chart from real inventory metrics)
    const inventoryDonutOptions_{{ $loop->index }} = {
        series: [{{ round($projData->sold_pct, 1) }}, {{ round($projData->unsold_pct, 1) }}],
        labels: ['Sold Area', 'Unsold Area'],
        chart: {
            type: 'donut',
            height: 190
        },
        colors: ['#10b981', '#a38c29'],
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Unsold Area',
                            fontSize: '10px',
                            fontWeight: '700',
                            color: '#64748b',
                            formatter: function () {
                                return '{{ number_format($projData->unsold_pct, 1) }}%';
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) { return val + '%'; }
            }
        }
    };
    if (document.querySelector("#inventoryDonutChart_{{ $loop->index }}")) {
        new ApexCharts(document.querySelector("#inventoryDonutChart_{{ $loop->index }}"), inventoryDonutOptions_{{ $loop->index }}).render();
    }
    @endforeach

});

function exportToExcel() {
    let tables = document.querySelectorAll('.data-matrix-table');
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Project Margin Analysis & Profitability Report\n\n";

    tables.forEach((table, index) => {
        let rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            let cols = row.querySelectorAll('th, td');
            let rowData = [];
            cols.forEach(col => {
                let text = col.innerText.replace(/,/g, '').replace(/₹/g, 'Rs. ').trim();
                rowData.push('"' + text + '"');
            });
            csvContent += rowData.join(",") + "\n";
        });
        csvContent += "\n";
    });

    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Project_Margin_Analysis_Report.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
@media print {
    aside, header, button, nav, .no-print { display: none !important; }
    body { background-color: #ffffff !important; color: #000000 !important; font-size: 10pt; }
    .p-4, .p-6 { padding: 0 !important; }
    .space-y-6 > * + * { margin-top: 1rem !important; }
    .shadow-sm, .shadow-md, .shadow-2xs { box-shadow: none !important; }
    .border { border-color: #cbd5e1 !important; }
}
</style>

</x-erp-layout>
