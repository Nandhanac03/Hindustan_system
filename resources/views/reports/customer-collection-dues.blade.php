<x-erp-layout title="Customer Collection & Dues Report" headerTitle="Customer Collection & Dues Report">

{{-- ExcelJS CDN Library --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<div class="max-w-[1850px] mx-auto space-y-6" 
     x-data="customerCollectionDuesApp()">

    {{-- ── 1. TOP HEADER & PRIMARY FILTERS BAR ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 transition-all">
        <form method="GET" action="{{ route('reports.customer_collection_dues') }}" id="reportFilterForm" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#a38c29]/15 border border-[#a38c29]/30 flex items-center justify-center text-[#8a7522] shadow-inner shrink-0">
                        <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight uppercase">Customer Collection & Dues Report</h2>
                            <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#8a7522] text-[9.5px] font-black uppercase tracking-wider border border-[#a38c29]/20">Customer Portal</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium">Customer receivables, realization status, overdue tracking, and milestone aging.</p>
                    </div>
                </div>

                {{-- Primary Header Controls --}}
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Project Filter --}}
                    <div class="flex flex-col">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Project</label>
                        <select name="project_id" class="h-9 px-3 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 focus:ring-2 focus:ring-[#a38c29]/40 cursor-pointer shadow-2xs min-w-[170px]">
                            <option value="all" {{ ($selectedProjectId === 'all' || !$selectedProjectId) ? 'selected' : '' }}>All Projects</option>
                            @foreach($allProjects as $proj)
                                <option value="{{ $proj->id }}" {{ ((string)$selectedProjectId === (string)$proj->id) ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Unit Status / Type Filter --}}
                    <div class="flex flex-col">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Unit Type</label>
                        <select name="unit_type" class="h-9 px-3 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 focus:ring-2 focus:ring-[#a38c29]/40 cursor-pointer shadow-2xs min-w-[140px]">
                            <option value="all" {{ ($selectedUnitType === 'all' || !$selectedUnitType) ? 'selected' : '' }}>All Units</option>
                            @foreach($unitTypes as $ut)
                                <option value="{{ $ut->name }}" {{ ($selectedUnitType === $ut->name) ? 'selected' : '' }}>
                                    {{ $ut->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- As On Date --}}
                    <div class="flex flex-col">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">As On Date</label>
                        <input type="date" name="as_of_date" value="{{ $asOfDate->format('Y-m-d') }}"
                               class="h-9 px-3 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 focus:ring-2 focus:ring-[#a38c29]/40 shadow-2xs">
                    </div>

                    {{-- Generate / Filter Button --}}
                    <div class="flex flex-col justify-end pt-3 sm:pt-0">
                        <button type="submit" 
                                class="h-9 px-4 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Generate Report</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- ── 2. METRIC KPI CARDS (5 EXECUTIVE CARDS) ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 pt-4">
            {{-- Card 1: Total Customers --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest block">TOTAL CUSTOMERS</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 font-mono tracking-tight block mt-0.5">{{ number_format($kpis['total_customers']) }}</span>
                    <span class="text-[9px] text-blue-600 font-bold uppercase tracking-wider">Active Bookings</span>
                </div>
            </div>

            {{-- Card 2: Total Agreement Value --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest block">TOTAL AGREEMENT VALUE</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-700 font-mono tracking-tight block mt-0.5 truncate" title="₹{{ number_format($kpis['total_agreement_value'], 2) }}">
                        ₹ {{ number_format($kpis['total_agreement_value'], 0) }}
                    </span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Gross Booking Value</span>
                </div>
            </div>

            {{-- Card 3: Total Collected --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest block">TOTAL COLLECTED</span>
                    <span class="text-xl sm:text-2xl font-black text-teal-700 font-mono tracking-tight block mt-0.5 truncate" title="₹{{ number_format($kpis['total_collected'], 2) }}">
                        ₹ {{ number_format($kpis['total_collected'], 0) }}
                    </span>
                    <span class="text-[9.5px] text-teal-600 font-black tracking-wide">{{ $kpis['total_collected_pct'] }}% collected</span>
                </div>
            </div>

            {{-- Card 4: Outstanding Balance --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest block">OUTSTANDING BALANCE</span>
                    <span class="text-xl sm:text-2xl font-black text-amber-700 font-mono tracking-tight block mt-0.5 truncate" title="₹{{ number_format($kpis['total_outstanding'], 2) }}">
                        ₹ {{ number_format($kpis['total_outstanding'], 0) }}
                    </span>
                    <span class="text-[9.5px] text-amber-600 font-black tracking-wide">{{ $kpis['total_outstanding_pct'] }}% pending</span>
                </div>
            </div>

            {{-- Card 5: Overdue Amount --}}
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3.5 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[9.5px] font-extrabold text-slate-400 uppercase tracking-widest block">OVERDUE AMOUNT</span>
                    <span class="text-xl sm:text-2xl font-black text-rose-700 font-mono tracking-tight block mt-0.5 truncate" title="₹{{ number_format($kpis['total_overdue'], 2) }}">
                        ₹ {{ number_format($kpis['total_overdue'], 0) }}
                    </span>
                    <span class="text-[9.5px] text-rose-600 font-black tracking-wide">{{ $kpis['total_overdue_pct'] }}% of outstanding</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. VISUAL CHARTS SECTION (PIE / DONUT BREAKDOWNS) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart 1: Collection vs Outstanding vs Overdue --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Financial Ratio Overview</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Collections vs Current vs Overdue</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
            </div>
            <div id="financialRatioChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

        {{-- Chart 2: Aging Bucket Analysis --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Overdue Aging Distribution</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Overdue breakdown by risk age buckets</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            </div>
            <div id="agingBucketChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>

        {{-- Chart 3: Payment Mode Distribution --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Payment Mode Share</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Receipt amounts by payment instrument</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <div id="paymentModeChart" class="w-full flex items-center justify-center min-h-[220px]"></div>
        </div>
    </div>

    {{-- ── 4. SECONDARY FILTERS & EXPORT TOOLBAR ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5">
            {{-- Search Input --}}
            <div class="relative flex-1 min-w-[260px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Search by customer name, mobile, unit number..."
                       class="w-full pl-10 pr-4 h-10 text-xs font-medium rounded-xl border border-slate-300 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] transition-all">
            </div>

            {{-- Filter Group --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Aging Bucket Filter --}}
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200/90 rounded-xl px-2.5 py-1">
                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Aging:</span>
                    <select x-model="selectedAgingBucket" class="text-xs font-bold bg-transparent border-0 text-slate-700 focus:ring-0 cursor-pointer py-1">
                        <option value="all">All Buckets</option>
                        <option value="0-30">0 - 30 Days</option>
                        <option value="31-60">31 - 60 Days</option>
                        <option value="61-90">61 - 90 Days</option>
                        <option value="90+">90+ Days</option>
                        <option value="current">Current / Not Due</option>
                    </select>
                </div>

                {{-- Payment Mode Filter --}}
                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200/90 rounded-xl px-2.5 py-1">
                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider">Mode:</span>
                    <select x-model="selectedPaymentMode" class="text-xs font-bold bg-transparent border-0 text-slate-700 focus:ring-0 cursor-pointer py-1">
                        <option value="all">All Modes</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="UPI">UPI</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>

                {{-- Overdue Only Toggle --}}
                <button type="button" 
                        @click="overdueOnly = !overdueOnly"
                        :class="overdueOnly ? 'bg-rose-600 text-white border-rose-700 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200/90 hover:bg-slate-100'"
                        class="h-10 px-3.5 rounded-xl border text-xs font-extrabold uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="w-2 h-2 rounded-full" :class="overdueOnly ? 'bg-white animate-pulse' : 'bg-rose-500'"></span>
                    <span>Overdue Only</span>
                </button>

                {{-- Export Excel Button --}}
                <button type="button" 
                        @click="exportToExcel()"
                        class="h-10 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export Excel</span>
                </button>

                {{-- Print Button --}}
                <button type="button" 
                        onclick="window.print()"
                        class="h-10 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── 5. MAIN DATA TABLE (WITH EXPANDABLE ACCORDION DETAIL ROW) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Customer Details</th>
                        <th class="py-3.5 px-4">Unit / Project</th>
                        <th class="py-3.5 px-4 text-right">Agreement Value (₹)</th>
                        <th class="py-3.5 px-4 text-right">Collected Amount (₹)</th>
                        <th class="py-3.5 px-4 text-right">Outstanding Amount (₹)</th>
                        <th class="py-3.5 px-4 text-center">Days Overdue</th>
                        <th class="py-3.5 px-4 text-center">Aging Bucket</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <template x-for="(row, idx) in filteredRows" :key="row.sale_id">
                        <tbody class="divide-y divide-slate-100">
                            {{-- Master Row --}}
                            <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer group"
                                @click="toggleRow(row.sale_id)"
                                :class="expandedRows.includes(row.sale_id) ? 'bg-[#a38c29]/5' : ''">
                                
                                {{-- Index --}}
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400" x-text="idx + 1"></td>

                                {{-- Customer Details --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-black text-xs shrink-0 uppercase border border-slate-200"
                                             x-text="row.customer_name ? row.customer_name.charAt(0) : 'C'"></div>
                                        <div>
                                            <span class="font-extrabold text-slate-900 block group-hover:text-[#a38c29] transition-colors" x-text="row.customer_name"></span>
                                            <span class="text-[10.5px] font-mono text-slate-500 font-bold block" x-text="row.customer_phone"></span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Unit / Project --}}
                                <td class="py-3.5 px-4">
                                    <span class="font-black text-slate-800 font-mono block uppercase" x-text="row.unit_display"></span>
                                    <span class="text-[10px] text-slate-500 font-medium block truncate max-w-[180px]" x-text="row.project_name"></span>
                                </td>

                                {{-- Agreement Value --}}
                                <td class="py-3.5 px-4 text-right font-mono font-black text-slate-900" 
                                    x-text="'₹ ' + formatMoney(row.agreement_value)"></td>

                                {{-- Collected Amount --}}
                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-mono font-black text-emerald-700 block" x-text="'₹ ' + formatMoney(row.collected_amount)"></span>
                                    <span class="text-[9.5px] font-bold text-emerald-600 font-mono" x-text="row.collected_pct + '%'"></span>
                                </td>

                                {{-- Outstanding Amount --}}
                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-mono font-black block" 
                                          :class="row.outstanding_amount > 0 ? 'text-amber-700' : 'text-slate-400'"
                                          x-text="'₹ ' + formatMoney(row.outstanding_amount)"></span>
                                    <template x-if="row.pending_realization > 0">
                                        <span class="text-[9px] font-bold text-amber-600 block" 
                                              x-text="'⏳ ₹ ' + formatMoney(row.pending_realization) + ' unclr'"></span>
                                    </template>
                                </td>

                                {{-- Days Overdue --}}
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-mono font-black px-2 py-0.5 rounded-lg text-xs"
                                          :class="row.days_overdue > 0 ? 'text-rose-700 bg-rose-50 border border-rose-200' : 'text-slate-400'"
                                          x-text="row.days_overdue > 0 ? row.days_overdue + 'd' : '—'"></span>
                                </td>

                                {{-- Aging Bucket --}}
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[9.5px] font-black uppercase tracking-wider border shadow-2xs inline-block"
                                          :class="getBucketBadgeClass(row.aging_bucket)"
                                          x-text="row.aging_bucket_label"></span>
                                </td>

                                {{-- Actions --}}
                                <td class="py-3.5 px-4 text-center" @click.stop>
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Toggle Accordion Button --}}
                                        <button type="button" 
                                                @click="toggleRow(row.sale_id)"
                                                class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-[#a38c29] hover:text-white hover:border-[#a38c29] transition-all flex items-center justify-center cursor-pointer shadow-2xs"
                                                :title="expandedRows.includes(row.sale_id) ? 'Collapse Details' : 'Expand Details'">
                                            <svg class="w-3.5 h-3.5 transform transition-transform" 
                                                 :class="expandedRows.includes(row.sale_id) ? 'rotate-180' : ''"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>

                                        {{-- View Ledger Button --}}
                                        <a :href="'{{ route('reports.customer_ledger') }}?customer_id=' + row.customer_id" 
                                           class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-800 hover:text-white transition-all flex items-center justify-center cursor-pointer shadow-2xs"
                                           title="View Full Ledger Statement">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- ── Expandable Detail Section ── --}}
                            <tr x-show="expandedRows.includes(row.sale_id)" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-slate-50/70 border-b border-slate-200">
                                <td colspan="9" class="p-5">
                                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-5">
                                        {{-- Detail Header Cards --}}
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            {{-- 1. Customer Information --}}
                                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-1.5">
                                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider block">Customer Information</span>
                                                <div class="text-xs space-y-1 font-medium">
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Name:</span> <strong class="text-slate-900" x-text="row.customer_name"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Mobile:</span> <strong class="text-slate-900 font-mono" x-text="row.customer_phone"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Email:</span> <span class="text-slate-700 truncate max-w-[140px]" x-text="row.customer_email"></span></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Address:</span> <span class="text-slate-700 truncate max-w-[140px]" x-text="row.customer_address"></span></p>
                                                </div>
                                            </div>

                                            {{-- 2. Unit Information --}}
                                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-1.5">
                                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider block">Unit Information</span>
                                                <div class="text-xs space-y-1 font-medium">
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Unit Number:</span> <strong class="text-slate-900 font-mono" x-text="row.unit_display"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Project:</span> <strong class="text-slate-900" x-text="row.project_name"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Agreement Date:</span> <span class="text-slate-700 font-mono" x-text="row.agreement_date"></span></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Total Value:</span> <strong class="text-emerald-700 font-mono" x-text="'₹ ' + formatMoney(row.agreement_value)"></strong></p>
                                                </div>
                                            </div>

                                            {{-- 3. Payment Summary --}}
                                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-1.5">
                                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider block">Payment Summary</span>
                                                <div class="text-xs space-y-1 font-medium">
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Total Collected:</span> <strong class="text-emerald-700 font-mono" x-text="'₹ ' + formatMoney(row.collected_amount) + ' (' + row.collected_pct + '%)'"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Outstanding:</span> <strong class="text-amber-700 font-mono" x-text="'₹ ' + formatMoney(row.outstanding_amount)"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Overdue Amount:</span> <strong class="text-rose-700 font-mono" x-text="'₹ ' + formatMoney(row.overdue_amount)"></strong></p>
                                                    <p class="flex items-center justify-between"><span class="text-slate-500">Days Overdue:</span> <strong class="text-rose-700 font-mono" x-text="row.days_overdue + ' Days'"></strong></p>
                                                </div>
                                            </div>

                                            {{-- 4. Payment Mode Breakdown --}}
                                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 space-y-1.5">
                                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider block">Payment Mode Breakdown</span>
                                                <div class="text-xs space-y-1.5">
                                                    <template x-for="(amt, mode) in row.payment_modes" :key="mode">
                                                        <div class="space-y-0.5">
                                                            <div class="flex items-center justify-between text-[11px]">
                                                                <span class="text-slate-600 font-bold" x-text="mode"></span>
                                                                <span class="font-mono font-black text-slate-800" x-text="'₹ ' + formatMoney(amt)"></span>
                                                            </div>
                                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                                <div class="bg-[#a38c29] h-full rounded-full" 
                                                                     :style="'width: ' + (row.collected_amount > 0 ? (amt / row.collected_amount * 100) : 0) + '%'"></div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="Object.keys(row.payment_modes).length === 0">
                                                        <p class="text-[11px] text-slate-400 italic">No payment receipts recorded yet.</p>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ── TAB CONTROLS (Milestone Schedule vs Payment History) ── --}}
                                        <div x-data="{ subTab: 'milestones' }">
                                            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                                <div class="flex items-center gap-2">
                                                    <button type="button" 
                                                            @click="subTab = 'milestones'"
                                                            :class="subTab === 'milestones' ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                                                        Milestone Schedule (<span x-text="row.milestones.length"></span>)
                                                    </button>
                                                    <button type="button" 
                                                            @click="subTab = 'payments'"
                                                            :class="subTab === 'payments' ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                                            class="px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                                                        Payment History (<span x-text="row.receipts.length"></span>)
                                                    </button>
                                                </div>

                                                <a :href="'{{ route('reports.customer_ledger') }}?customer_id=' + row.customer_id" 
                                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#a38c29] hover:underline">
                                                    <span>Open in Customer Ledger Console</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            </div>

                                            {{-- SubTab 1: Milestone Schedule --}}
                                            <div x-show="subTab === 'milestones'" class="pt-3 overflow-x-auto">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-slate-100/80 text-[9.5px] font-black text-slate-500 uppercase tracking-wider border-b">
                                                            <th class="py-2 px-3 w-10 text-center">#</th>
                                                            <th class="py-2 px-3">Milestone Stage</th>
                                                            <th class="py-2 px-3">Due Date</th>
                                                            <th class="py-2 px-3 text-right">Milestone Amount (₹)</th>
                                                            <th class="py-2 px-3 text-center">Status</th>
                                                            <th class="py-2 px-3 text-right">Amount Paid (₹)</th>
                                                            <th class="py-2 px-3 text-right">Balance (₹)</th>
                                                            <th class="py-2 px-3 text-center">Days Overdue</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-medium">
                                                        <template x-for="m in row.milestones" :key="m.installment_no">
                                                            <tr class="hover:bg-slate-50">
                                                                <td class="py-2 px-3 text-center font-mono text-slate-400" x-text="m.installment_no"></td>
                                                                <td class="py-2 px-3 font-bold text-slate-800" x-text="m.label"></td>
                                                                <td class="py-2 px-3 font-mono text-slate-600" x-text="m.due_date"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-bold text-slate-900" x-text="formatMoney(m.amount)"></td>
                                                                <td class="py-2 px-3 text-center">
                                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border"
                                                                          :class="m.status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (m.status === 'Outstanding' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-50 text-slate-600 border-slate-200')"
                                                                          x-text="m.status"></span>
                                                                </td>
                                                                <td class="py-2 px-3 text-right font-mono text-emerald-700 font-bold" x-text="formatMoney(m.paid_amount)"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-black" 
                                                                    :class="m.balance > 0 ? 'text-amber-700' : 'text-slate-400'"
                                                                    x-text="formatMoney(m.balance)"></td>
                                                                <td class="py-2 px-3 text-center font-mono font-bold"
                                                                    :class="m.days_overdue > 0 ? 'text-rose-600' : 'text-slate-400'"
                                                                    x-text="m.days_overdue > 0 ? m.days_overdue + 'd' : '—'"></td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>

                                            {{-- SubTab 2: Payment History --}}
                                            <div x-show="subTab === 'payments'" class="pt-3 overflow-x-auto" style="display: none;">
                                                <table class="w-full text-left border-collapse text-xs">
                                                    <thead>
                                                        <tr class="bg-slate-100/80 text-[9.5px] font-black text-slate-500 uppercase tracking-wider border-b">
                                                            <th class="py-2 px-3 w-10 text-center">#</th>
                                                            <th class="py-2 px-3">Receipt / Ref No</th>
                                                            <th class="py-2 px-3">Receipt Date</th>
                                                            <th class="py-2 px-3">Payment Mode</th>
                                                            <th class="py-2 px-3 text-right">Amount (₹)</th>
                                                            <th class="py-2 px-3 text-center">Realization Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-medium">
                                                        <template x-for="r in row.receipts" :key="r.id">
                                                            <tr class="hover:bg-slate-50">
                                                                <td class="py-2 px-3 text-center font-mono text-slate-400" x-text="r.index"></td>
                                                                <td class="py-2 px-3 font-mono font-bold text-slate-800" x-text="r.receipt_no"></td>
                                                                <td class="py-2 px-3 font-mono text-slate-600" x-text="r.receipt_date"></td>
                                                                <td class="py-2 px-3 font-semibold text-slate-700" x-text="r.payment_mode"></td>
                                                                <td class="py-2 px-3 text-right font-mono font-black text-emerald-700" x-text="'₹ ' + formatMoney(r.amount)"></td>
                                                                <td class="py-2 px-3 text-center">
                                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border"
                                                                          :class="r.realization_status === 'realized' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                                                                          x-text="r.realization_label"></span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <template x-if="row.receipts.length === 0">
                                                            <tr>
                                                                <td colspan="6" class="py-6 text-center text-slate-400 italic">No receipts recorded for this booking.</td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                    <template x-if="filteredRows.length === 0">
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 font-bold uppercase tracking-wider text-xs">
                                No customer collection or dues matching the selected filter criteria.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function customerCollectionDuesApp() {
    return {
        rows: @js($rows),
        kpis: @js($kpis),
        agingSummary: @js($agingSummary),
        paymentModeTotals: @js($paymentModeTotals),
        
        searchQuery: '',
        selectedAgingBucket: 'all',
        selectedPaymentMode: 'all',
        overdueOnly: false,
        expandedRows: [],

        init() {
            this.$nextTick(() => {
                this.initCharts();
            });
        },

        toggleRow(saleId) {
            if (this.expandedRows.includes(saleId)) {
                this.expandedRows = this.expandedRows.filter(id => id !== saleId);
            } else {
                this.expandedRows.push(saleId);
            }
        },

        formatMoney(amount) {
            if (isNaN(amount) || amount === null) return '0.00';
            return Number(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        getBucketBadgeClass(bucket) {
            switch(bucket) {
                case '0-30':
                    return 'bg-emerald-50 text-emerald-700 border-emerald-200';
                case '31-60':
                    return 'bg-amber-50 text-amber-700 border-amber-200';
                case '61-90':
                    return 'bg-orange-50 text-orange-700 border-orange-200';
                case '90+':
                    return 'bg-rose-50 text-rose-700 border-rose-200';
                default:
                    return 'bg-slate-50 text-slate-700 border-slate-200';
            }
        },

        get filteredRows() {
            return this.rows.filter(row => {
                // Search query
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    const matchName = (row.customer_name || '').toLowerCase().includes(q);
                    const matchPhone = (row.customer_phone || '').toLowerCase().includes(q);
                    const matchUnit = (row.unit_display || '').toLowerCase().includes(q);
                    const matchProject = (row.project_name || '').toLowerCase().includes(q);
                    if (!matchName && !matchPhone && !matchUnit && !matchProject) {
                        return false;
                    }
                }

                // Aging Bucket filter
                if (this.selectedAgingBucket !== 'all') {
                    if (row.aging_bucket !== this.selectedAgingBucket) {
                        return false;
                    }
                }

                // Payment Mode filter
                if (this.selectedPaymentMode !== 'all') {
                    if (!row.payment_modes[this.selectedPaymentMode] || row.payment_modes[this.selectedPaymentMode] <= 0) {
                        return false;
                    }
                }

                // Overdue Only filter
                if (this.overdueOnly) {
                    if (!row.overdue_amount || row.overdue_amount <= 0) {
                        return false;
                    }
                }

                return true;
            });
        },

        initCharts() {
            // ── 1. Financial Ratio Donut Chart ──
            const ratioEl = document.getElementById('financialRatioChart');
            if (ratioEl && typeof ApexCharts !== 'undefined') {
                const collected = Number(this.kpis.total_collected || 0);
                const currentOutstanding = Math.max(0, Number(this.kpis.total_outstanding || 0) - Number(this.kpis.total_overdue || 0));
                const overdue = Number(this.kpis.total_overdue || 0);

                const ratioOptions = {
                    series: [collected, currentOutstanding, overdue],
                    labels: ['Total Collected', 'Current Outstanding', 'Overdue Amount'],
                    colors: ['#0D9488', '#F59E0B', '#E11D48'],
                    chart: {
                        type: 'donut',
                        height: 240,
                        fontFamily: 'Inter, sans-serif'
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        markers: { radius: 12 }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => '₹ ' + this.formatMoney(val)
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Agreement',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => '₹ ' + (Number(this.kpis.total_agreement_value) / 10000000).toFixed(2) + ' Cr'
                                    }
                                }
                            }
                        }
                    }
                };
                new ApexCharts(ratioEl, ratioOptions).render();
            }

            // ── 2. Aging Bucket Donut Chart ──
            const agingEl = document.getElementById('agingBucketChart');
            if (agingEl && typeof ApexCharts !== 'undefined') {
                const b030 = Number(this.agingSummary['0-30']?.amount || 0);
                const b3160 = Number(this.agingSummary['31-60']?.amount || 0);
                const b6190 = Number(this.agingSummary['61-90']?.amount || 0);
                const b90p = Number(this.agingSummary['90+']?.amount || 0);

                const agingOptions = {
                    series: [b030, b3160, b6190, b90p],
                    labels: ['0 - 30 Days', '31 - 60 Days', '61 - 90 Days', '90+ Days'],
                    colors: ['#10B981', '#F59E0B', '#F97316', '#EF4444'],
                    chart: {
                        type: 'donut',
                        height: 240,
                        fontFamily: 'Inter, sans-serif'
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        markers: { radius: 12 }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => '₹ ' + this.formatMoney(val)
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Overdue Dues',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => '₹ ' + (Number(this.kpis.total_overdue) / 100000).toFixed(1) + ' L'
                                    }
                                }
                            }
                        }
                    }
                };
                new ApexCharts(agingEl, agingOptions).render();
            }

            // ── 3. Payment Mode Donut Chart ──
            const modeEl = document.getElementById('paymentModeChart');
            if (modeEl && typeof ApexCharts !== 'undefined') {
                const modes = Object.keys(this.paymentModeTotals);
                const values = modes.map(m => Number(this.paymentModeTotals[m] || 0));

                const modeOptions = {
                    series: values.some(v => v > 0) ? values : [1],
                    labels: values.some(v => v > 0) ? modes : ['No Collections'],
                    colors: ['#2563EB', '#F59E0B', '#8B5CF6', '#10B981', '#64748B'],
                    chart: {
                        type: 'donut',
                        height: 240,
                        fontFamily: 'Inter, sans-serif'
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: 'bottom',
                        fontSize: '11px',
                        fontWeight: 600,
                        markers: { radius: 12 }
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => '₹ ' + this.formatMoney(val)
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Collected',
                                        fontSize: '10px',
                                        fontWeight: 800,
                                        formatter: () => '₹ ' + (Number(this.kpis.total_collected) / 10000000).toFixed(2) + ' Cr'
                                    }
                                }
                            }
                        }
                    }
                };
                new ApexCharts(modeEl, modeOptions).render();
            }
        },

        async exportToExcel() {
            try {
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Hindustan ERP';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Customer Collection & Dues');

                // Header Title
                sheet.mergeCells('A1:I1');
                const titleCell = sheet.getCell('A1');
                titleCell.value = 'CUSTOMER COLLECTION & DUES REPORT';
                titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E293B' } };
                titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(1).height = 30;

                // KPI Summary Row
                sheet.mergeCells('A2:I2');
                const kpiCell = sheet.getCell('A2');
                kpiCell.value = `Total Agreement Value: ₹${this.formatMoney(this.kpis.total_agreement_value)}  |  Total Collected: ₹${this.formatMoney(this.kpis.total_collected)} (${this.kpis.total_collected_pct}%)  |  Outstanding: ₹${this.formatMoney(this.kpis.total_outstanding)}  |  Overdue: ₹${this.formatMoney(this.kpis.total_overdue)}`;
                kpiCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF334155' } };
                kpiCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF1F5F9' } };
                kpiCell.alignment = { horizontal: 'center', vertical: 'middle' };
                sheet.getRow(2).height = 24;

                // Table Columns
                sheet.columns = [
                    { header: '#', key: 'idx', width: 6 },
                    { header: 'Customer Name', key: 'name', width: 26 },
                    { header: 'Mobile Phone', key: 'phone', width: 16 },
                    { header: 'Unit / Project', key: 'unit', width: 22 },
                    { header: 'Agreement Value (₹)', key: 'agreement', width: 22 },
                    { header: 'Collected Amount (₹)', key: 'collected', width: 22 },
                    { header: 'Outstanding Amount (₹)', key: 'outstanding', width: 24 },
                    { header: 'Days Overdue', key: 'days', width: 14 },
                    { header: 'Aging Bucket', key: 'bucket', width: 16 }
                ];

                const headerRow = sheet.getRow(4);
                headerRow.values = ['#', 'Customer Name', 'Mobile Phone', 'Unit / Project', 'Agreement Value (₹)', 'Collected Amount (₹)', 'Outstanding Amount (₹)', 'Days Overdue', 'Aging Bucket'];
                headerRow.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                headerRow.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                headerRow.height = 25;

                let rowIdx = 5;
                this.filteredRows.forEach((r, i) => {
                    const row = sheet.getRow(rowIdx);
                    row.values = [
                        i + 1,
                        r.customer_name,
                        r.customer_phone,
                        `${r.unit_display} (${r.project_name})`,
                        r.agreement_value,
                        r.collected_amount,
                        r.outstanding_amount,
                        r.days_overdue > 0 ? r.days_overdue : 0,
                        r.aging_bucket_label
                    ];

                    row.getCell(5).numFormat = '#,##0.00';
                    row.getCell(6).numFormat = '#,##0.00';
                    row.getCell(7).numFormat = '#,##0.00';
                    rowIdx++;
                });

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Customer_Collection_Dues_Report_${new Date().toISOString().slice(0, 10)}.xlsx`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Export error:', err);
                alert('Excel Export failed: ' + err.message);
            }
        }
    };
}
</script>

</x-erp-layout>
