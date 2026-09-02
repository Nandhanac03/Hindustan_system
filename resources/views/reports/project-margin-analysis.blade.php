<x-erp-layout title="Project Margin Analysis Workspace" headerTitle="Business Reports Center">

<div class="w-full space-y-6">

    {{-- Under Construction Notice --}}
    <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-5 md:p-6 shadow-sm relative overflow-hidden backdrop-blur-sm">
        <div class="flex items-start md:items-center gap-4">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30 text-2xl md:text-3xl">
                🚧
            </div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Work In Progress
                    </span>
                </div>
                <h2 class="text-lg md:text-2xl font-black text-red-950 tracking-tight leading-snug">
                    We're working on this module. It is not yet ready for use and will be released shortly.
                </h2>
                <p class="text-xs md:text-sm font-medium text-red-800">
                    This module is currently being finalized. Please check back soon for full availability.
                </p>
            </div>
        </div>
    </div>

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 md:p-8 space-y-6">

        <!-- ── 1. HEADER & FILTER BAR ── -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                    <a href="/" class="hover:text-slate-600 transition">HOME</a>
                    <span>›</span>
                    <span>FINANCE & ANALYTICS</span>
                    <span>›</span>
                    <span class="text-[#a38c29] font-bold">PROJECT MARGIN ANALYSIS</span>
                </nav>
                <h1 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <div class="p-1.5 bg-amber-50 rounded-lg text-[#a38c29] border border-amber-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span>Project Margin & Financial Intelligence</span>
                </h1>
            </div>

            <!-- Filter Controls -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <form method="GET" action="{{ route('reports.project_margin_analysis') }}" class="flex items-center">
                    <div class="relative">
                        @php $projectsList = $allProjects ?? $projects ?? []; @endphp
                        <select name="project_id" onchange="this.form.submit()" class="h-10 min-w-[240px] max-w-[280px] pl-3.5 pr-8 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:border-[#a38c29] focus:ring-[#a38c29] focus:outline-none cursor-pointer appearance-none truncate shadow-2xs">
                            <option value="all" {{ $selectedProjectId === 'all' || !$selectedProjectId ? 'selected' : '' }}>🌐 All Projects Portfolio (Company Overview)</option>
                            @foreach($projectsList as $p)
                                <option value="{{ $p->id }}" {{ (string)$selectedProjectId === (string)$p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->code ?? 'PRJ-'.$p->id }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── 2. PROJECT MARGIN REPORT CONTENT ── -->
        @foreach($marginAnalysis as $projData)
        <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-6 shadow-2xs">
            
            <!-- Full-Width Project Header Bar -->
            <div class="pb-4 bg-white text-slate-900 flex flex-col xl:flex-row xl:items-center justify-between gap-3 border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-2.5 py-0.5 rounded-md bg-[#a38c29] text-white text-[11px] font-black uppercase tracking-wider shadow-2xs">
                        {{ $projData->code }}
                    </span>
                    <h2 class="text-xs sm:text-sm font-extrabold tracking-tight text-slate-900 uppercase leading-snug">{{ $projData->project_name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold border uppercase {{ $projData->health_badge_class }}">
                        {{ $projData->health_status }} ({{ number_format($projData->net_margin_pct, 1) }}%)
                    </span>
                </div>

                <div class="text-xs font-semibold text-slate-700 flex flex-wrap items-center gap-2.5 bg-white px-3.5 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                    <span class="flex items-center gap-1.5 text-[#a38c29]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <strong>{{ $projData->location }}</strong>
                    </span>
                    <span class="text-slate-300">•</span>
                    <span>Total Area: <strong class="text-slate-900 font-mono">{{ number_format($projData->total_area, 0) }} Sq.Ft.</strong></span>
                    <span class="text-slate-300">•</span>
                    <span class="text-emerald-700 font-bold">Sold: {{ number_format($projData->sold_pct, 1) }}%</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-amber-700 font-bold">Unsold: {{ number_format($projData->unsold_pct, 1) }}%</span>
                </div>
            </div>

            <!-- 4 KPI Metric Cards Row -->
            <div class="bg-white pb-4 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Column 1: Realized Collections -->
                <div class="bg-white p-4 rounded-xl border border-l-[4px] border-l-emerald-500 border-slate-200 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-0.5 hover:shadow-md">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-800">Realized Collections</span>
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        <h4 class="text-lg sm:text-xl font-mono font-extrabold text-emerald-800 tracking-tight whitespace-nowrap">₹{{ number_format($projData->realized_collections, 2) }}</h4>
                    </div>
                    <div class="text-[10.5px] text-emerald-700/80 font-bold pt-1">
                        <span class="truncate">Actual cash in bank from buyers</span>
                    </div>
                </div>

                <!-- Column 2: Pending Receivables -->
                <div class="bg-white p-4 rounded-xl border border-l-[4px] border-l-blue-500 border-slate-200 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-0.5 hover:shadow-md">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-blue-800">Pending Receivables</span>
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h4 class="text-lg sm:text-xl font-mono font-extrabold text-blue-800 tracking-tight whitespace-nowrap">₹{{ number_format($projData->pending_receivables, 2) }}</h4>
                    </div>
                    <div class="text-[10.5px] text-blue-700/80 font-bold pt-1">
                        <span class="truncate">Balance due from booked units</span>
                    </div>
                </div>

                <!-- Column 3: Projected Unsold Inventory -->
                <div class="bg-white p-4 rounded-xl border border-l-[4px] border-l-amber-500 border-slate-200 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-0.5 hover:shadow-md">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-800">Projected Unsold Inventory</span>
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                            </div>
                        </div>
                        <h4 class="text-lg sm:text-xl font-mono font-extrabold text-slate-900 tracking-tight whitespace-nowrap">₹{{ number_format($projData->projected_unsold_val, 2) }}</h4>
                    </div>
                    <div class="text-[10.5px] text-amber-700 font-bold pt-1">
                        <span class="truncate">@ ₹{{ number_format($projData->current_market_rate, 0) }}/Sq.Ft. market rate</span>
                    </div>
                </div>

                <!-- Column 4: Total Gross Revenue -->
                <div class="bg-white p-4 rounded-xl border border-l-[4px] border-l-[#a38c29] border-slate-200 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-0.5 hover:shadow-md">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-[#a38c29]">Total Gross Revenue</span>
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0 border border-amber-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>
                        <h4 class="text-lg sm:text-xl font-mono font-extrabold text-slate-900 tracking-tight whitespace-nowrap">₹{{ number_format($projData->total_gross_revenue, 2) }}</h4>
                    </div>
                    <div class="flex items-center justify-between text-[10.5px] text-[#a38c29] font-bold pt-1">
                        <span>Cost: ₹{{ number_format($projData->cost_per_sqft, 0) }}/Sq.Ft.</span>
                        <span class="text-emerald-700 font-bold">Net: ₹{{ number_format($projData->net_profit / 1000000, 1) }}M</span>
                    </div>
                </div>

            </div>

            <!-- Cost Breakdown Table (Brand Gold Header Theme) -->
            <div class="overflow-x-auto border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                <table class="w-full text-left border-collapse data-matrix-table text-xs">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[10px] sm:text-[11px] font-black uppercase tracking-widest border-b-2 border-[#8a741f]">
                            <th class="w-3/12 px-5 py-3.5 text-left text-white font-black tracking-wider">EXPENSE CATEGORY</th>
                            <th class="w-1/12 px-4 py-3.5 text-center text-white font-black tracking-wider">ACCOUNT CODE</th>
                            <th class="w-2/12 px-5 py-3.5 text-right text-white font-black tracking-wider">
                                <div>TOTAL INCURRED COST</div>
                                <div class="text-[9px] text-amber-100/90 font-medium lowercase tracking-normal">(accrued basis)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 text-right text-white font-black tracking-wider">
                                <div>CASH PAID OUT</div>
                                <div class="text-[9px] text-emerald-100/90 font-medium lowercase tracking-normal">(actual spent)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 text-right text-white font-black tracking-wider">
                                <div>PENDING PAYABLE</div>
                                <div class="text-[9px] text-rose-100/90 font-medium lowercase tracking-normal">(liability balance)</div>
                            </th>
                            <th class="w-2/12 px-5 py-3.5 text-right text-white font-black tracking-wider">
                                <div>COST / SQ.FT.</div>
                                <div class="text-[9px] text-amber-100/90 font-medium lowercase tracking-normal">(rs./sq.ft.)</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                        @foreach($projData->cost_matrix as $row)
                            <tr class="transition-colors hover:bg-amber-50/20 bg-white">
                                <td class="px-5 py-3.5 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $row['category'] }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-3 py-1 bg-amber-50 text-[#7a671b] rounded-lg font-mono text-[11px] font-bold border border-amber-200/80 shadow-2xs">
                                        {{ $row['code'] }}
                                    </span>
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
                    <tfoot class="bg-white text-slate-900 font-black text-xs uppercase border-t-2 border-b border-[#a38c29]">
                        <tr>
                            <td class="px-5 py-3.5 text-slate-900 font-black tracking-wider text-xs" colspan="2">
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

            <!-- ── PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN ── -->
            <div class="py-6 bg-white border-b border-slate-100 space-y-5">
                
                <!-- Section Header Banner -->
                <div class="bg-white py-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0 border border-amber-200 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 uppercase tracking-tight leading-snug">Partner Equity & Profit Distribution Breakdown</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Pool Badge -->
                    <div class="px-4 py-2 bg-amber-50/80 rounded-xl border border-amber-200 text-right shrink-0">
                        <span class="text-[10px] font-extrabold uppercase text-[#7a671b] tracking-wider block">Total Net Profit Pool</span>
                        <span class="text-base font-mono font-black text-emerald-800">₹{{ number_format($projData->net_profit, 2) }}</span>
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

                            <!-- Bottom Row: Calculated Profit -->
                            <div class="pt-2 flex flex-col justify-between bg-white p-3 rounded-xl border border-slate-200">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 block mb-1">Calculated Share of Net Profit</span>
                                <span class="text-lg sm:text-xl font-mono font-black text-emerald-700 whitespace-nowrap">₹{{ number_format($partner->profit_share, 2) }}</span>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

            <!-- ── 3-COLUMN ANALYTICS & CHARTS SECTION ── -->
            <div class="py-6 bg-white space-y-6">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Card A: PROJECT MARGIN OVERVIEW (Radial Gauge) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <div class="p-1 bg-amber-50 text-[#a38c29] rounded border border-amber-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span>PROJECT MARGIN OVERVIEW</span>
                            </h3>
                        </div>
                        
                        <!-- Radial Gauge Container -->
                        <div class="py-2 flex flex-col items-center justify-center">
                            <div id="marginRadialChart_{{ $loop->index }}" class="w-full h-[220px] flex items-center justify-center"></div>
                        </div>

                        <!-- Bottom Metrics Summary -->
                        <div class="grid grid-cols-3 gap-2 pt-3 text-center">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Net Profit</span>
                                <span class="text-xs font-mono font-bold text-slate-900">₹{{ number_format($projData->net_profit / 1000000, 1) }}M</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Revenue</span>
                                <span class="text-xs font-mono font-bold text-slate-900">₹{{ number_format($projData->total_gross_revenue / 1000000, 2) }}M</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Cost</span>
                                <span class="text-xs font-mono font-bold text-slate-900">₹{{ number_format($projData->total_incurred_cost / 1000000, 2) }}M</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card B: REVENUE VS COST ANALYSIS (Bar Chart) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <div class="p-1 bg-amber-50 text-[#a38c29] rounded border border-amber-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <span>REVENUE VS COST ANALYSIS</span>
                            </h3>
                        </div>
                        
                        <div id="revenueCostBarChart_{{ $loop->index }}" class="w-full h-[240px]"></div>

                        <div class="flex items-center justify-center gap-4 pt-2 text-xs font-bold text-slate-700">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Total Revenue</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Total Cost</span>
                        </div>
                    </div>

                    <!-- Card C: INVENTORY BREAKDOWN (Donut Chart) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-2">
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

                        <div class="pt-2 text-center text-xs font-semibold text-slate-500">
                            Total Area: <span class="text-slate-900 font-mono font-bold">{{ number_format($projData->total_area, 0) }} Sq.Ft.</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        @endforeach

    </div>

</div>

<!-- ── EXPORT TO EXCEL SCRIPT & APEXCHARTS ── -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    @foreach($marginAnalysis as $projData)
    // 1. Radial Gauge for Project Margin
    const radialOptions_{{ $loop->index }} = {
        series: [{{ min(100, max(0, round($projData->net_margin_pct, 1))) }}],
        chart: {
            type: 'radialBar',
            height: 220,
            sparkline: { enabled: true }
        },
        plotOptions: {
            radialBar: {
                startAngle: -100,
                endAngle: 100,
                track: {
                    background: "#e2e8f0",
                    strokeWidth: '95%',
                },
                dataLabels: {
                    name: {
                        show: true,
                        text: 'Project Margin',
                        color: '#64748b',
                        fontSize: '11px',
                        fontWeight: '700',
                        offsetY: 15
                    },
                    value: {
                        offsetY: -22,
                        fontSize: '22px',
                        fontWeight: '800',
                        color: '#0f172a',
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            colors: ['#10b981']
        },
        labels: ['Project Margin']
    };
    new ApexCharts(document.querySelector("#marginRadialChart_{{ $loop->index }}"), radialOptions_{{ $loop->index }}).render();

    // 2. Bar Chart for Revenue vs Cost Analysis
    const revCostOptions_{{ $loop->index }} = {
        series: [{
            name: 'Total Revenue',
            data: [{{ round($projData->total_gross_revenue / 1000000, 2) }}]
        }, {
            name: 'Total Cost',
            data: [{{ round($projData->total_incurred_cost / 1000000, 2) }}]
        }],
        chart: {
            type: 'bar',
            height: 200,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '35%',
                borderRadius: 4
            }
        },
        colors: ['#10b981', '#f59e0b'],
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: ['Financial Overview (₹ Millions)'],
            labels: { style: { fontSize: '10px', fontWeight: 600 } }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return '₹' + val + 'M'; },
                style: { fontSize: '10px', fontWeight: 600 }
            }
        },
        legend: { show: false },
        tooltip: {
            y: {
                formatter: function(val) { return '₹' + val + ' Million'; }
            }
        }
    };
    new ApexCharts(document.querySelector("#revenueCostBarChart_{{ $loop->index }}"), revCostOptions_{{ $loop->index }}).render();

    // 3. Donut Chart for Inventory Breakdown
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
    new ApexCharts(document.querySelector("#inventoryDonutChart_{{ $loop->index }}"), inventoryDonutOptions_{{ $loop->index }}).render();
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
