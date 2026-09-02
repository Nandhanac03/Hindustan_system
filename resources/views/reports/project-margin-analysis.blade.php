<x-erp-layout title="Project Margin Analysis Workspace" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6">

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

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">

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
                        <div class="p-1.5 bg-[#a38c29]/10 rounded-lg text-[#a38c29]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <span>Project Margin & Financial Intelligence</span>
                        <span class="text-[10px] bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wide">Real-Time Accrual Basis</span>
                    </h1>
                </div>

                <!-- Filter Controls & Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <form method="GET" action="{{ route('reports.project_margin_analysis') }}" class="flex items-center">
                        <div class="relative">
                            @php $projectsList = $allProjects ?? $projects ?? []; @endphp
                            <select name="project_id" onchange="this.form.submit()" class="h-10 min-w-[240px] max-w-[280px] pl-3.5 pr-8 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:border-[#a38c29] focus:ring-[#a38c29] focus:outline-none cursor-pointer appearance-none truncate shadow-2xs">
                                <option value="all" {{ $selectedProjectId === 'all' || !$selectedProjectId ? 'selected' : '' }}>🏢 All Active Projects Aggregate</option>
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



            <!-- ── 3. COST BREAKDOWN & CASH FLOW MATRIX TABLES (PRIMARY FINANCIAL REPORT) ── -->
            @foreach($marginAnalysis as $projData)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 overflow-hidden space-y-0 print:border-none print:shadow-none">
                
                <!-- Full-Width Project Header Bar (Compact & Sleek Typography) -->
                <div class="p-4 bg-gradient-to-r from-[#faf7eb] via-[#f5eed6] to-[#faf7eb] text-slate-900 flex flex-col xl:flex-row xl:items-center justify-between gap-3 border-b border-amber-300/80 shadow-2xs">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-2.5 py-0.5 rounded-md bg-[#a38c29] text-white text-[11px] font-black uppercase tracking-wider shadow-2xs">
                            {{ $projData->code }}
                        </span>
                        <h2 class="text-xs sm:text-sm font-extrabold tracking-tight text-slate-900 uppercase">{{ $projData->project_name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold border uppercase {{ $projData->health_badge_class }}">
                            {{ $projData->health_status }} ({{ number_format($projData->net_margin_pct, 1) }}%)
                        </span>
                    </div>

                    <div class="text-[11px] font-semibold text-slate-700 flex flex-wrap items-center gap-2.5 bg-white/90 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-amber-200/80 shadow-2xs">
                        <span class="flex items-center gap-1"><span class="text-[#a38c29]">📍</span> <strong>{{ $projData->location }}</strong></span>
                        <span class="text-amber-300">•</span>
                        <span>Total Area: <strong class="text-slate-900 font-mono">{{ number_format($projData->total_area, 0) }} Sq.Ft.</strong></span>
                        <span class="text-amber-300">•</span>
                        <span class="text-emerald-700 font-bold">Sold: {{ number_format($projData->sold_pct, 1) }}%</span>
                        <span class="text-amber-300">|</span>
                        <span class="text-amber-700 font-bold">Unsold: {{ number_format($projData->unsold_pct, 1) }}%</span>
                    </div>
                </div>

                <!-- 100% Aligned 4-Column Metric Cards Row -->
                <div class="bg-slate-50/70 p-4 border-b border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Column 1: Realized Collections -->
                    <div class="bg-gradient-to-br from-emerald-50/70 via-white to-emerald-50/20 p-4 rounded-xl border border-l-[5px] border-l-emerald-500 border-emerald-200/80 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-1 hover:shadow-md">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-slate-500">
                                <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-800">Realized Collections</span>
                                <div class="w-7 h-7 rounded-lg bg-emerald-100/80 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <h4 class="text-lg sm:text-xl font-mono font-extrabold text-emerald-800 tracking-tight whitespace-nowrap">₹{{ number_format($projData->realized_collections, 2) }}</h4>
                        </div>
                        <div class="text-[10.5px] text-emerald-700/80 font-bold pt-2 border-t border-emerald-100 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="truncate">Actual cash in bank from buyers</span>
                        </div>
                    </div>

                    <!-- Column 2: Pending Receivables -->
                    <div class="bg-gradient-to-br from-blue-50/70 via-white to-blue-50/20 p-4 rounded-xl border border-l-[5px] border-l-blue-500 border-blue-200/80 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-1 hover:shadow-md">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-slate-500">
                                <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-blue-800">Pending Receivables</span>
                                <div class="w-7 h-7 rounded-lg bg-blue-100/80 text-blue-700 flex items-center justify-center shrink-0 border border-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <h4 class="text-lg sm:text-xl font-mono font-extrabold text-blue-800 tracking-tight whitespace-nowrap">₹{{ number_format($projData->pending_receivables, 2) }}</h4>
                        </div>
                        <div class="text-[10.5px] text-blue-700/80 font-bold pt-2 border-t border-blue-100 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                            <span class="truncate">Balance due from booked units</span>
                        </div>
                    </div>

                    <!-- Column 3: Projected Unsold Inventory -->
                    <div class="bg-gradient-to-br from-amber-50/70 via-white to-amber-50/20 p-4 rounded-xl border border-l-[5px] border-l-amber-500 border-amber-200/80 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-1 hover:shadow-md">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-slate-500">
                                <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-800">Projected Unsold Inventory</span>
                                <div class="w-7 h-7 rounded-lg bg-amber-100/80 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                                </div>
                            </div>
                            <h4 class="text-lg sm:text-xl font-mono font-extrabold text-amber-800 tracking-tight whitespace-nowrap">₹{{ number_format($projData->projected_unsold_val, 2) }}</h4>
                        </div>
                        <div class="text-[10.5px] text-amber-700/80 font-bold pt-2 border-t border-amber-100 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                            <span class="truncate">@ ₹{{ number_format($projData->current_market_rate, 0) }}/Sq.Ft. market rate</span>
                        </div>
                    </div>

                    <!-- Column 4: Total Gross Revenue & Profit Benchmarks -->
                    <div class="bg-gradient-to-br from-amber-100/60 via-white to-amber-50/30 p-4 rounded-xl border border-l-[5px] border-l-[#a38c29] border-amber-300/80 shadow-2xs flex flex-col justify-between h-full space-y-2.5 transition-all hover:-translate-y-1 hover:shadow-md">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-slate-500">
                                <span class="text-[10.5px] font-extrabold uppercase tracking-wider text-[#a38c29]">Total Gross Revenue</span>
                                <div class="w-7 h-7 rounded-lg bg-amber-100 text-[#a38c29] flex items-center justify-center shrink-0 border border-amber-300/60">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                            </div>
                            <h4 class="text-lg sm:text-xl font-mono font-extrabold text-slate-900 tracking-tight whitespace-nowrap">₹{{ number_format($projData->total_gross_revenue, 2) }}</h4>
                        </div>
                        <div class="flex items-center justify-between text-[10.5px] text-[#a38c29] font-bold pt-2 border-t border-amber-200/60">
                            <span>Cost: ₹{{ number_format($projData->cost_per_sqft, 0) }}/Sq.Ft.</span>
                            <span>Net: ₹{{ number_format($projData->net_profit / 1000000, 1) }}M</span>
                        </div>
                    </div>

                </div>

                <!-- Cost Breakdown & Cash Flow Matrix Table (Rich Brand Gold Header Theme) -->
                <div class="overflow-x-auto border-b border-slate-200">
                    <table class="w-full text-left border-collapse data-matrix-table text-xs">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white text-[11px] font-black uppercase tracking-wider border-b-2 border-[#8a741f]">
                                <th class="w-3/12 px-6 py-4 text-left text-white font-black tracking-wider">EXPENSE CATEGORY</th>
                                <th class="w-1/12 px-4 py-4 text-center text-white font-black tracking-wider">ACCOUNT CODE</th>
                                <th class="w-2/12 px-6 py-4 text-right text-white font-black tracking-wider">
                                    <div>TOTAL INCURRED COST</div>
                                    <div class="text-[9.5px] text-amber-100/90 font-semibold lowercase tracking-normal">(accrued basis)</div>
                                </th>
                                <th class="w-2/12 px-6 py-4 text-right text-white font-black tracking-wider">
                                    <div>CASH PAID OUT</div>
                                    <div class="text-[9.5px] text-emerald-100/90 font-semibold lowercase tracking-normal">(actual spent)</div>
                                </th>
                                <th class="w-2/12 px-6 py-4 text-right text-white font-black tracking-wider">
                                    <div>PENDING PAYABLE</div>
                                    <div class="text-[9.5px] text-rose-100/90 font-semibold lowercase tracking-normal">(liability balance)</div>
                                </th>
                                <th class="w-2/12 px-6 py-4 text-right text-white font-black tracking-wider">
                                    <div>COST / SQ.FT.</div>
                                    <div class="text-[9.5px] text-amber-100/90 font-semibold lowercase tracking-normal">(rs./sq.ft.)</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 font-semibold text-slate-800">
                            @foreach($projData->cost_matrix as $row)
                                <tr class="transition-colors hover:bg-amber-50/40 odd:bg-white even:bg-[#faf8f2]/60">
                                    <td class="px-6 py-4 font-black text-slate-900 flex items-center gap-3">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] shrink-0 shadow-2xs"></span>
                                        <span>{{ $row['category'] }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 bg-amber-50 text-[#7a671b] rounded-lg font-mono text-[11px] font-bold border border-amber-200/80 shadow-2xs">
                                            {{ $row['code'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap">
                                        ₹{{ number_format($row['incurred'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-emerald-700 whitespace-nowrap">
                                        ₹{{ number_format($row['spent'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-bold whitespace-nowrap {{ $row['payable'] > 0 ? 'text-rose-700' : 'text-slate-400' }}">
                                        ₹{{ number_format($row['payable'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                        ₹{{ number_format($row['cost_per_sqft'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-[#faf5e1] text-slate-900 font-black text-xs uppercase border-t-2 border-b-2 border-[#a38c29]">
                            <tr>
                                <td class="px-6 py-4 text-slate-900 font-black tracking-wider text-xs" colspan="2">
                                    TOTAL PROJECT EXPENSES & COST BENCHMARK
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-slate-900 text-xs whitespace-nowrap">
                                    ₹{{ number_format($projData->total_incurred_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-emerald-800 text-xs whitespace-nowrap">
                                    ₹{{ number_format($projData->total_cash_paid, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-rose-700 text-xs whitespace-nowrap">
                                    ₹{{ number_format($projData->total_pending_payable, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-[#a38c29] text-xs whitespace-nowrap">
                                    ₹{{ number_format($projData->cost_per_sqft, 2) }} <span class="text-[10px] text-[#7a671b] font-bold whitespace-nowrap">/ Sq.Ft.</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- ── PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN ── -->
                <div class="p-6 bg-slate-50/70 border-t border-slate-200/80">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="p-1.5 bg-[#a38c29]/10 rounded-lg text-[#a38c29]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span>Partner Equity & Profit Distribution Breakdown</span>
                        </h3>
                        <span class="text-[10px] bg-amber-50 text-[#7a671b] border border-amber-200 px-3 py-1 rounded-full font-extrabold uppercase tracking-wide shrink-0">
                            Auto-Calculated from Project Partner Master
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($projData->partners_breakdown as $partner)
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between space-y-3 transition-all hover:shadow-md">
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $partner->partner_name }}</span>
                                    <span class="px-3 py-1 bg-amber-50 text-[#7a671b] rounded-full text-[10px] font-black border border-amber-200 shrink-0">
                                        {{ number_format($partner->share_pct, 1) }}% Equity Share
                                    </span>
                                </div>
                                <div class="pt-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Calculated Net Profit Share</span>
                                    <span class="text-xl font-mono font-black text-emerald-700 whitespace-nowrap">₹{{ number_format($partner->profit_share, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            @endforeach

            <!-- ── 4. VISUAL ANALYTICS & CHARTS SECTION ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart 1: Revenue vs Cost vs Net Profit Breakdown -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            <span>Financial Performance Comparison (Per Project / Aggregate)</span>
                        </h3>
                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-bold">Rs. Benchmarking</span>
                    </div>
                    <div id="financialComparisonChart" class="w-full min-h-[300px]"></div>
                </div>

                <!-- Chart 2: Expense Category Accrued Cost Distribution -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-2">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            <span>Cost Breakdown</span>
                        </h3>
                        <span class="text-[10px] bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 rounded font-extrabold uppercase shrink-0 whitespace-nowrap">Incurred Cost</span>
                    </div>
                    <div id="costDistributionChart" class="w-full min-h-[290px] flex items-center justify-center"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ── EXPORT TO EXCEL SCRIPT & APEXCHARTS ── -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Chart 1: Financial Comparison Chart (Revenue vs Incurred Cost vs Net Profit)
    const comparisonChartOptions = {
        series: [{
            name: 'Gross Realizable Revenue',
            data: [{{ implode(',', $marginAnalysis->pluck('total_gross_revenue')->toArray()) }}]
        }, {
            name: 'Total Incurred Cost',
            data: [{{ implode(',', $marginAnalysis->pluck('total_incurred_cost')->toArray()) }}]
        }, {
            name: 'Net Profit Amount',
            data: [{{ implode(',', $marginAnalysis->pluck('net_profit')->toArray()) }}]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                borderRadius: 4
            },
        },
        dataLabels: { enabled: false },
        colors: ['#a38c29', '#e11d48', '#059669'],
        stroke: { show: true, width: 2, colors: ['transparent'] },
        grid: {
            padding: { left: 20, right: 20, top: 10, bottom: 0 }
        },
        xaxis: {
            categories: [{!! implode(',', $marginAnalysis->pluck('project_name')->map(fn($n) => "'".addslashes($n)."'")->toArray()) !!}],
            labels: { style: { fontSize: '11px', fontWeight: 600 } }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + (val / 10000000).toFixed(1) + ' Cr';
                },
                style: { fontSize: '11px', fontWeight: 600 }
            }
        },
        fill: { opacity: 1 },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + new Intl.NumberFormat('en-IN').format(val);
                }
            }
        },
        legend: { position: 'top', horizontalAlign: 'right', fontSize: '11px', fontWeight: 600 }
    };
    new ApexCharts(document.querySelector("#financialComparisonChart"), comparisonChartOptions).render();

    // Chart 2: Cost Allocation Breakdown (Account Codes)
    @php
        $sampleCostMatrix = $marginAnalysis->first()?->cost_matrix ?? [];
        $costCategories = array_column($sampleCostMatrix, 'category');
        $costIncurredVals = array_column($sampleCostMatrix, 'incurred');
    @endphp

    const costDistributionOptions = {
        series: [{!! implode(',', $costIncurredVals) !!}],
        labels: [{!! implode(',', array_map(fn($c) => "'".addslashes($c)."'", $costCategories)) !!}],
        chart: {
            type: 'donut',
            height: 290,
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'],
        dataLabels: { enabled: true, style: { fontSize: '10px' } },
        legend: { position: 'bottom', fontSize: '11px' },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + new Intl.NumberFormat('en-IN').format(val);
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#costDistributionChart"), costDistributionOptions).render();

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
