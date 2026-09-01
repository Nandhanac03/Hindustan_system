<x-erp-layout title="Project Margin Analysis Workspace" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6">

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
                    <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <div class="p-2 bg-[#a38c29]/10 rounded-xl text-[#a38c29]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <span>Project Margin & Financial Intelligence</span>
                        <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-3 py-1 rounded-full font-extrabold uppercase tracking-wide">Real-Time Accrual Basis</span>
                    </h1>
                </div>

                <!-- Filter Controls & Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <form method="GET" action="{{ route('reports.project_margin_analysis') }}" class="flex items-center">
                        <div class="relative">
                            @php $projectsList = $allProjects ?? $projects ?? []; @endphp
                            <select name="project_id" onchange="this.form.submit()" class="h-11 min-w-[240px] max-w-[280px] pl-4 pr-8 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:border-[#a38c29] focus:ring-[#a38c29] focus:outline-none cursor-pointer appearance-none truncate shadow-2xs">
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

                    <!-- <button type="button" onclick="exportToExcel()" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Export Excel</span>
                    </button>

                    <button type="button" onclick="window.print()" class="h-11 px-5 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print</span>
                    </button> -->
                </div>
            </div>

            <!-- ── 2. EXECUTIVE KPIS TOP SUMMARY CARDS (RESTORED 4 CARDS) ── -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Card 1: Project Status Card -->
                <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-2xl p-5 shadow-xs relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Project Status & Inventory</span>
                        <div class="w-7 h-7 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 space-y-2">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight font-mono whitespace-nowrap group-hover:text-[#a38c29] transition-colors">
                            {{ number_format($summaryTotals->total_area, 0) }} <span class="text-xs font-sans font-bold text-slate-500">Sq.Ft.</span>
                        </h3>
                        <div class="flex items-center justify-between text-[11px] font-bold pt-1">
                            <span class="text-emerald-700">Sold: {{ number_format($summaryTotals->sold_area, 0) }} Sq.Ft.</span>
                            <span class="text-amber-700">Unsold: {{ number_format($summaryTotals->unsold_area, 0) }} Sq.Ft.</span>
                        </div>
                        @php
                            $soldPctSummary = $summaryTotals->total_area > 0 ? ($summaryTotals->sold_area / $summaryTotals->total_area) * 100 : 0;
                        @endphp
                        <div class="w-full bg-amber-100 rounded-full h-2 overflow-hidden flex">
                            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ $soldPctSummary }}%"></div>
                            <div class="bg-amber-400 h-full transition-all duration-500" style="width: {{ 100 - $soldPctSummary }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Gross Realizable Revenue Card -->
                <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-2xl p-5 shadow-xs relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Gross Realizable Revenue</span>
                        <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 space-y-1.5">
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono whitespace-nowrap group-hover:text-emerald-600 transition-colors">
                            ₹{{ number_format($summaryTotals->gross_revenue, 0) }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400">Avg Selling Price: <strong class="text-slate-800 font-mono">₹{{ number_format($summaryTotals->avg_selling_price, 2) }}/Sq.Ft.</strong></p>
                        <div class="pt-2 border-t border-slate-100 text-[10px] space-y-1 font-bold">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Bank Collections:</span>
                                <span class="font-mono text-emerald-700">₹{{ number_format($marginAnalysis->sum('realized_collections'), 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pending Receivables:</span>
                                <span class="font-mono text-blue-700">₹{{ number_format($marginAnalysis->sum('pending_receivables'), 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Incurred Cost Card -->
                <div class="bg-white border-y border-r border-l-4 border-l-rose-500 border-slate-200 rounded-2xl p-5 shadow-xs relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Incurred Cost (Accrued)</span>
                        <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 space-y-1.5">
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono whitespace-nowrap group-hover:text-rose-600 transition-colors">
                            ₹{{ number_format($summaryTotals->incurred_cost, 0) }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400">Incurred Cost / Sq.Ft.: <strong class="text-rose-700 font-mono">₹{{ number_format($summaryTotals->cost_per_sqft, 2) }}/Sq.Ft.</strong></p>
                        <div class="pt-2 border-t border-slate-100 text-[10px] space-y-1 font-bold">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Actual Cash Spent:</span>
                                <span class="font-mono text-slate-900">₹{{ number_format($summaryTotals->cash_paid, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pending Liability:</span>
                                <span class="font-mono text-rose-700">₹{{ number_format($summaryTotals->pending_payable, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Net Profit Margin Card -->
                <div class="bg-white border-y border-r border-l-4 border-l-[#3b82f6] border-slate-200 rounded-2xl p-5 shadow-xs relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Net Profit & Margin</span>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border uppercase {{ $summaryTotals->net_margin_pct >= 20 ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($summaryTotals->net_margin_pct >= 10 ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-rose-100 text-rose-800 border-rose-300') }}">
                                {{ number_format($summaryTotals->net_margin_pct, 1) }}%
                            </span>
                            <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 space-y-1.5">
                        <h3 class="text-xl sm:text-2xl font-black text-emerald-700 tracking-tight font-mono whitespace-nowrap group-hover:text-blue-600 transition-colors">
                            ₹{{ number_format($summaryTotals->net_profit, 0) }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400">Profit / Sq.Ft.: <strong class="text-emerald-700 font-mono">₹{{ number_format($summaryTotals->net_profit_per_sqft, 2) }}/Sq.Ft.</strong></p>
                        <div class="pt-2 border-t border-slate-100 text-[10px] space-y-1 font-bold">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Gross Profit:</span>
                                <span class="font-mono text-slate-900">₹{{ number_format($summaryTotals->gross_profit, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Accounting:</span>
                                <span class="text-[#a38c29]">Accrual Basis (RA)</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── 3. COST BREAKDOWN & CASH FLOW MATRIX TABLES (PRIMARY FINANCIAL REPORT) ── -->
            @foreach($marginAnalysis as $projData)
            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 overflow-hidden space-y-0 print:border-none print:shadow-none">
                
                <!-- Full-Width Project Header Bar -->
                <div class="p-4 bg-gradient-to-r from-amber-50/90 via-white to-slate-50 text-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded bg-[#a38c29] text-white text-[10px] font-black uppercase tracking-wider">
                            {{ $projData->code }}
                        </span>
                        <h2 class="text-base font-black tracking-tight text-slate-900">{{ $projData->project_name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border uppercase {{ $projData->health_badge_class }}">
                            {{ $projData->health_status }} ({{ number_format($projData->net_margin_pct, 1) }}%)
                        </span>
                    </div>

                    <div class="text-xs font-semibold text-slate-600 flex items-center gap-3">
                        <span>📍 <strong>{{ $projData->location }}</strong></span>
                        <span>•</span>
                        <span>Total Area: <strong class="text-slate-900 font-mono">{{ number_format($projData->total_area, 0) }} Sq.Ft.</strong></span>
                        <span>(Sold: {{ number_format($projData->sold_pct, 1) }}% | Unsold: {{ number_format($projData->unsold_pct, 1) }}%)</span>
                    </div>
                </div>

                <!-- 100% Aligned 4-Column Metric Cards Row -->
                <div class="bg-slate-50/70 p-4 border-b border-slate-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs font-semibold">
                    
                    <!-- Column 1: Realized Collections -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between h-full space-y-2">
                        <div>
                            <div class="flex items-center justify-between text-slate-500 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider">Realized Collections</span>
                            
                            </div>
                            <span class="text-base sm:text-lg font-mono font-black text-emerald-800 block whitespace-nowrap">₹{{ number_format($projData->realized_collections, 2) }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium block pt-1 border-t border-slate-100">Actual cash in bank from buyers</span>
                    </div>

                    <!-- Column 2: Pending Receivables -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between h-full space-y-2">
                        <div>
                            <div class="flex items-center justify-between text-slate-500 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider">Pending Receivables</span>
                               
                            </div>
                            <span class="text-base sm:text-lg font-mono font-black text-blue-800 block whitespace-nowrap">₹{{ number_format($projData->pending_receivables, 2) }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium block pt-1 border-t border-slate-100">Balance due from booked units</span>
                    </div>

                    <!-- Column 3: Projected Unsold Inventory -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between h-full space-y-2">
                        <div>
                            <div class="flex items-center justify-between text-slate-500 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider">Projected Unsold Inventory</span>
                             
                            </div>
                            <span class="text-base sm:text-lg font-mono font-black text-amber-800 block whitespace-nowrap">₹{{ number_format($projData->projected_unsold_val, 2) }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-medium block pt-1 border-t border-slate-100">@ ₹{{ number_format($projData->current_market_rate, 0) }}/Sq.Ft. market rate</span>
                    </div>

                    <!-- Column 4: Total Gross Revenue & Profit Benchmarks -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between h-full space-y-2">
                        <div>
                            <div class="flex items-center justify-between text-slate-500 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider">Total Gross Revenue</span>
                          
                            </div>
                            <span class="text-base sm:text-lg font-mono font-black text-slate-900 block whitespace-nowrap">₹{{ number_format($projData->total_gross_revenue, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-[#a38c29] font-bold pt-1 border-t border-slate-100">
                            <span>Cost: ₹{{ number_format($projData->cost_per_sqft, 0) }}/Sq.Ft.</span>
                            <span>Net: ₹{{ number_format($projData->net_profit / 1000000, 1) }}M</span>
                        </div>
                    </div>

                </div>

                <!-- Cost Breakdown & Cash Flow Matrix Table (Fixed Width Columns for Exact Alignment) -->
                <div class="overflow-x-auto border-b border-slate-200">
                    <table class="w-full text-left border-collapse data-matrix-table text-xs table-fixed">
                        <thead class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white text-[10px] font-black uppercase tracking-widest border-b-2 border-[#8a7522]">
                            <tr>
                                <th class="w-2/6 px-5 py-3.5 text-left text-white font-extrabold">Expense Category</th>
                                <th class="w-1/12 px-4 py-3.5 text-center text-white font-extrabold">Account Code</th>
                                <th class="w-2/12 px-5 py-3.5 text-right text-white font-extrabold">Total Incurred Cost (Accrued)</th>
                                <th class="w-2/12 px-5 py-3.5 text-right text-white font-extrabold">Cash Paid Out (Actual Spent)</th>
                                <th class="w-2/12 px-5 py-3.5 text-right text-white font-extrabold">Pending Payable (Liability)</th>
                                <th class="w-2/12 px-5 py-3.5 text-right text-white font-extrabold">Cost Per Sq.Ft. (Rs./Sq.Ft.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/80 font-semibold text-slate-800">
                            @foreach($projData->cost_matrix as $row)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 font-extrabold text-slate-900 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#a38c29] shrink-0"></span>
                                        <span class="truncate">{{ $row['category'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded font-mono text-[11px] font-bold">
                                            {{ $row['code'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        ₹{{ number_format($row['incurred'], 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-emerald-800 whitespace-nowrap">
                                        ₹{{ number_format($row['spent'], 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold whitespace-nowrap {{ $row['payable'] > 0 ? 'text-rose-700' : 'text-slate-500' }}">
                                        ₹{{ number_format($row['payable'], 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                        ₹{{ number_format($row['cost_per_sqft'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-to-r from-amber-50/90 via-white to-amber-50/90 text-slate-900 font-black text-xs uppercase border-t-2 border-[#a38c29]/50">
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
                                    ₹{{ number_format($projData->cost_per_sqft, 2) }} <span class="text-[10px] text-amber-700 font-bold whitespace-nowrap">/ Sq.Ft.</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- ── PARTNER EQUITY & PROFIT DISTRIBUTION BREAKDOWN ── -->
                <div class="p-5 bg-slate-50/60">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Partner Equity & Profit Distribution Breakdown</span>
                        </h3>
                        <span class="text-[10px] bg-[#a38c29]/15 text-[#a38c29] border border-[#a38c29]/30 px-3 py-1 rounded-full font-extrabold uppercase tracking-wide whitespace-nowrap">
                            Auto-Calculated from Project Partner Master
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($projData->partners_breakdown as $partner)
                            <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-extrabold text-slate-900">{{ $partner->partner_name }}</span>
                                    <span class="px-2.5 py-0.5 bg-amber-100 text-[#a38c29] rounded-full text-[10px] font-black border border-amber-300">
                                        {{ number_format($partner->share_pct, 1) }}% Equity Share
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Calculated Net Profit Share</span>
                                    <span class="text-lg font-mono font-black text-emerald-700 whitespace-nowrap">₹{{ number_format($partner->profit_share, 2) }}</span>
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
