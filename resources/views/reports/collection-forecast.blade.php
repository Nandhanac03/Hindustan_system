<x-erp-layout title="Collection Forecast & Overdue Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto p-6 space-y-6" x-data="collectionForecastApp()" x-init="init()">

    <!-- Header Section -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Collection Forecast & Overdue Reports</h1>
            <p class="text-sm text-slate-500 mt-1">Ageing analysis of outstanding customer dues with automated reminder generation</p>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (ZERO-RELOAD REACTIVE) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all mb-6">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 flex-1">
                
                {{-- 1. As On Date Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date"
                           x-model="filters.as_of_date"
                           @change="currentPage = 1; updateCharts()"
                           title="As On Date"
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- 2. Project Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <select x-model="filters.project_id" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 3. Customer Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <select x-model="filters.customer_id" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 4. Ageing Bucket Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <select x-model="filters.ageing_bucket" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Ageing Buckets</option>
                        <option value="Current">Current (Not Due)</option>
                        <option value="0-30">0-30 Days</option>
                        <option value="31-60">31-60 Days</option>
                        <option value="61-90">61-90 Days</option>
                        <option value="91-120">91-120 Days</option>
                        <option value="120+">> 120 Days</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 5. Risk Level Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <select x-model="filters.risk_level" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Risk Levels</option>
                        <option value="None">None</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                        <option value="Severe">Severe</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 6. Reminder Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <select x-model="filters.reminder_status" @change="currentPage = 1; updateCharts()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Reminder Statuses</option>
                        <option value="Sent">Sent</option>
                        <option value="Pending">Pending</option>
                        <option value="Failed">Failed</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Outstanding -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Outstanding</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-[#a38c29]" x-text="'₹ ' + formatNumber(kpis.total_outstanding)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-[#a38c29]" x-text="kpis.total_customers + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Total Overdue -->
        <div class="bg-white border-y border-r border-l-4 border-l-rose-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.2)] hover:border-r-rose-500/20 hover:border-y-rose-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Overdue</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-rose-600" x-text="'₹ ' + formatNumber(kpis.total_overdue)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-rose-500" x-text="kpis.overdue_customers + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Current / Not Due -->
        <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Current / Not Due</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-emerald-600" x-text="'₹ ' + formatNumber(kpis.current_not_due)"></h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-emerald-500" x-text="(kpis.total_customers - kpis.overdue_customers) + ' Customers'"></span></p>
            </div>
        </div>

        <!-- Expected Collection -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#3b82f6] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)] hover:border-r-[#3b82f6]/20 hover:border-y-[#3b82f6]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Expected Collection</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-blue-600" x-text="'₹ ' + formatNumber(kpis.expected_collection)"></h3>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#3b82f6] mt-1 inline-block">Probability Weighted</span>
            </div>
        </div>
    </div>

    <!-- Charts (Dual-Mode: Overdue Ageing or 1-Year Upcoming Collection Timeline) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Donut Chart -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800" x-text="isOverdueMode ? 'Ageing Summary (Overdue)' : '1-Year Collection Forecast'"></h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5" x-text="isOverdueMode ? 'Breakdown of dues by overdue age buckets' : 'Upcoming collection timeline horizons'"></p>
                </div>
                <template x-if="!isOverdueMode">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wider shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        No Overdue Dues
                    </span>
                </template>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <div id="donutChart" class="w-48 h-48"></div>
                <div class="flex-1 w-full">
                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="bucket in summaryTableBuckets" :key="bucket.key">
                                <tr class="py-2">
                                    <td class="py-2 flex items-center gap-2 text-slate-600">
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="'background-color: ' + bucket.color"></span>
                                        <span class="font-medium truncate" x-text="bucket.label"></span>
                                    </td>
                                    <td class="py-2 text-right font-semibold text-slate-800 whitespace-nowrap">
                                        <span x-text="'₹ ' + formatNumber(bucket.amount)"></span>
                                        <span class="text-slate-400 font-normal ml-1 text-[11px]" x-text="'(' + bucket.pct + '%)'"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td class="py-3 font-bold text-slate-800" x-text="isOverdueMode ? 'Total Overdue' : 'Total Outstanding'"></td>
                                <td class="py-3 text-right font-bold text-slate-800 whitespace-nowrap" x-text="'₹ ' + formatNumber(isOverdueMode ? kpis.total_overdue : kpis.total_outstanding)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-800" x-text="isOverdueMode ? 'Ageing Distribution' : 'Monthly Inflow Forecast (Next 12 Months)'"></h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5" x-text="isOverdueMode ? 'Overdue exposure grouped by risk buckets' : 'Month-by-month scheduled receivable timeline'"></p>
                </div>
                <template x-if="!isOverdueMode">
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100 uppercase tracking-wider">
                        12-Month Schedule
                    </span>
                </template>
            </div>
            <div id="barChart" class="w-full h-64"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3 bg-white">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Overdue Installments Directory
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all overdue installments and forecast status.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="exportTableToCSV()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-xl shadow-sm hover:bg-slate-50 flex items-center gap-2 cursor-pointer transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </button>
                <button type="button" @click="window.print()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-xl shadow-sm hover:bg-slate-50 flex items-center gap-2 cursor-pointer transition">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-white font-extrabold">Customer</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Sale No.</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Project</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Unit</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Inst. No.</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Due Date</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Outstanding</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Days Overdue</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Ageing</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Risk</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Reminder Level</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Last Reminder</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="inst in paginatedInstallments" :key="inst.id">
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-5 py-3 text-xs font-bold text-slate-500" x-text="inst.customer_name"></td>
                            <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide" x-text="inst.sale_number"></td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500" x-text="inst.project_name"></td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500 leading-tight max-w-[200px] truncate" :title="inst.unit_name" x-text="inst.unit_name"></td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-400" x-text="inst.installment_no"></td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500" x-text="inst.due_date_formatted"></td>
                            <td class="px-5 py-3 text-right text-xs font-black text-slate-800 tracking-tight" x-text="'₹ ' + formatNumber(inst.calculated_outstanding)"></td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-500" x-text="inst.days_overdue > 0 ? inst.days_overdue : '-'"></td>
                            <td class="px-5 py-3 text-center text-xs font-black" :class="getAgeingColor(inst.ageing_bucket)" x-text="inst.ageing_bucket === '120+' ? '> 120 Days' : (inst.ageing_bucket + (inst.ageing_bucket !== 'Current' ? ' Days' : ''))"></td>
                            <td class="px-5 py-3 text-center text-xs font-black" :class="getRiskColor(inst.risk_level)" x-text="inst.risk_level"></td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-600" x-text="inst.reminder_level"></td>
                            <td class="px-5 py-3 text-center text-xs font-bold text-slate-400" x-text="inst.last_reminder_date"></td>
                            <td class="px-5 py-3 text-center">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button type="button" @click.prevent="openModal(inst.modal_payload)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <a :href="'tel:' + (inst.modal_payload ? inst.modal_payload.customer.mobile : '')" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="Call">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredInstallments.length === 0">
                        <td colspan="13" class="px-5 py-8 text-center text-slate-500 italic">No installments found for the given criteria.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Dynamic Reactive Pagination --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500">
            <div class="font-medium">
                Showing <span class="font-bold text-slate-800" x-text="filteredInstallments.length ? (currentPage - 1) * perPage + 1 : 0"></span> to 
                <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredInstallments.length)"></span> of 
                <span class="font-bold text-slate-800" x-text="filteredInstallments.length"></span> entries
            </div>
            <div class="flex items-center gap-1" x-show="totalPages > 1">
                <button type="button" @click="if(currentPage > 1) { currentPage--; window.scrollTo({ top: 400, behavior: 'smooth' }); }" :disabled="currentPage === 1"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition">
                    Previous
                </button>
                <template x-for="p in totalPages" :key="p">
                    <button type="button" @click="currentPage = p; window.scrollTo({ top: 400, behavior: 'smooth' });" 
                            x-show="p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2)"
                            :class="currentPage === p ? 'bg-[#a38c29] text-white border-[#a38c29]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                            class="w-8 h-8 rounded-lg border text-xs font-bold transition flex items-center justify-center cursor-pointer">
                        <span x-text="p"></span>
                    </button>
                </template>
                <button type="button" @click="if(currentPage < totalPages) { currentPage++; window.scrollTo({ top: 400, behavior: 'smooth' }); }" :disabled="currentPage === totalPages"
                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold text-xs transition">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- OVERDUE INSTALLMENT DETAILS MODAL -->
    <div x-show="isModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl my-8 overflow-hidden" @click.outside="closeModal()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Overdue Installment Details</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Detailed view of overdue installment and customer information</p>
                </div>
                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(100vh-200px)] custom-scrollbar space-y-6" x-show="modalData">
                <template x-if="modalData">
                    <div>
                        <!-- Section 1: Customer & Booking Details -->
                        <div class="border border-slate-200 rounded-xl p-5 bg-white relative">
                            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-4 absolute -top-2.5 left-4 bg-white px-2">Customer & Booking Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
                                <div class="grid grid-cols-[120px_auto] gap-2 items-start text-xs">
                                    <span class="font-bold text-slate-500">Customer Name</span>
                                    <span class="font-black text-slate-800 flex gap-2">: <span x-text="modalData.customer.name"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Mobile Number</span>
                                    <span class="font-black text-slate-800 flex gap-2 text-blue-600">: <span x-text="modalData.customer.mobile"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Email ID</span>
                                    <span class="font-black text-slate-800 flex gap-2 text-blue-600">: <span x-text="modalData.customer.email"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Address</span>
                                    <span class="font-medium text-slate-600 flex gap-2 leading-tight">: <span x-text="modalData.customer.address"></span></span>
                                </div>
                                <div class="grid grid-cols-[120px_auto] gap-2 items-start text-xs">
                                    <span class="font-bold text-slate-500">Sale No.</span>
                                    <span class="font-black text-slate-800 flex gap-2">: <span x-text="modalData.booking.sale_no"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Project</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.project"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Unit</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.unit"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Booking Date</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.booking_date"></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Outstanding Summary -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Outstanding Summary</h4>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-indigo-900">₹ <span x-text="modalData.summary.total_outstanding"></span></div>
                                <div class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider mt-1">Total Outstanding</div>
                            </div>
                            <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-amber-900">₹ <span x-text="modalData.summary.total_overdue"></span></div>
                                <div class="text-[9px] font-bold text-amber-700 uppercase tracking-wider mt-1">Total Overdue</div>
                            </div>
                            <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-rose-600"><span x-text="modalData.summary.days_overdue"></span> Days</div>
                                <div class="text-[9px] font-bold text-rose-500 uppercase tracking-wider mt-1">Days Overdue</div>
                            </div>
                            <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-emerald-700"><span x-text="modalData.summary.ageing_bucket"></span></div>
                                <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider mt-1">Ageing Bucket</div>
                            </div>
                            <div class="bg-purple-50/50 border border-purple-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-purple-700" x-text="modalData.summary.risk_level"></div>
                                <div class="text-[9px] font-bold text-purple-600 uppercase tracking-wider mt-1">Risk Level</div>
                            </div>
                        </div>

                        <!-- Section 3: Installment Schedule Breakdown -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Installment Schedule Breakdown</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5">No.</th>
                                        <th class="px-4 py-2.5">Inst. Date</th>
                                        <th class="px-4 py-2.5">Due Date</th>
                                        <th class="px-4 py-2.5 text-right">Amount</th>
                                        <th class="px-4 py-2.5 text-right">Paid</th>
                                        <th class="px-4 py-2.5 text-right">Outstanding</th>
                                        <th class="px-4 py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="inst in modalData.installments" :key="inst.no">
                                        <tr :class="inst.is_current ? 'bg-amber-50/60 font-bold' : 'hover:bg-slate-50'" class="transition-colors">
                                            <td class="px-4 py-2 text-xs font-black text-slate-600" x-text="'#' + inst.no"></td>
                                            <td class="px-4 py-2 text-xs text-slate-600" x-text="inst.inst_date"></td>
                                            <td class="px-4 py-2 text-xs text-slate-600" x-text="inst.due_date"></td>
                                            <td class="px-4 py-2 text-right text-xs font-black text-slate-800" x-text="'₹ ' + inst.amount"></td>
                                            <td class="px-4 py-2 text-right text-xs font-bold text-emerald-600" x-text="'₹ ' + inst.paid"></td>
                                            <td class="px-4 py-2 text-right text-xs font-black text-rose-600" x-text="'₹ ' + inst.outstanding"></td>
                                            <td class="px-4 py-2 text-center">
                                                <span :class="inst.status === 'paid' ? 'bg-emerald-100 text-emerald-700' : (inst.status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')" class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider" x-text="inst.status"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Section 4: Communication History -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Communication History</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5">Reminder No.</th>
                                        <th class="px-4 py-2.5">Date</th>
                                        <th class="px-4 py-2.5">Type</th>
                                        <th class="px-4 py-2.5">Channel</th>
                                        <th class="px-4 py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="modalData.reminders.length === 0">
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-xs font-bold text-slate-400 italic">No reminders sent yet.</td>
                                        </tr>
                                    </template>
                                    <template x-for="rem in modalData.reminders" :key="rem.no">
                                        <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-2 text-xs font-black text-slate-600" x-text="rem.no"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="rem.date"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="rem.type"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-500" x-text="rem.channel"></td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[9px] font-bold uppercase tracking-widest" x-text="rem.status"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" @click="closeModal()" class="px-5 py-2 border border-slate-300 bg-white text-slate-600 text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-slate-50 transition-colors cursor-pointer">
                    Close
                </button>
                <a :href="'tel:' + (modalData ? modalData.customer.mobile : '')" class="px-5 py-2 border border-[#3b82f6] bg-white text-[#3b82f6] text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-blue-50 transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    Call Customer
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function collectionForecastApp() {
    return {
        allInstallments: @json($allInstallmentsFormatted ?? []),
        filters: {
            as_of_date: '{{ request('as_of_date', '') }}',
            project_id: '{{ request('project_id', '') }}',
            customer_id: '{{ request('customer_id', '') }}',
            ageing_bucket: '{{ request('ageing_bucket', '') }}',
            risk_level: '{{ request('risk_level', '') }}',
            reminder_status: '{{ request('reminder_status', '') }}',
        },
        currentPage: 1,
        perPage: 50,
        isModalOpen: false,
        modalData: null,
        donutChartInstance: null,
        barChartInstance: null,

        init() {
            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        get processedInstallments() {
            const asOfStr = this.filters.as_of_date || '{{ now()->format('Y-m-d') }}';
            const asOf = new Date(asOfStr + 'T00:00:00');

            return this.allInstallments.map(inst => {
                let daysOverdue = 0;
                if (inst.due_date_raw) {
                    const dueDate = new Date(inst.due_date_raw + 'T00:00:00');
                    const diffTime = asOf.getTime() - dueDate.getTime();
                    daysOverdue = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                }

                let bucket = 'Current';
                let risk = 'None';
                let prob = 1.0;

                if (daysOverdue > 0) {
                    if (daysOverdue <= 30) { bucket = '0-30'; risk = 'Low'; prob = 0.90; }
                    else if (daysOverdue <= 60) { bucket = '31-60'; risk = 'Medium'; prob = 0.70; }
                    else if (daysOverdue <= 90) { bucket = '61-90'; risk = 'High'; prob = 0.40; }
                    else if (daysOverdue <= 120) { bucket = '91-120'; risk = 'Critical'; prob = 0.20; }
                    else { bucket = '120+'; risk = 'Severe'; prob = 0.05; }
                } else {
                    daysOverdue = 0;
                }

                let targetLevel = 'Pending';
                if (daysOverdue >= 90) targetLevel = 'Legal';
                else if (daysOverdue >= 60) targetLevel = 'Final';
                else if (daysOverdue >= 30) targetLevel = 'Second';
                else if (daysOverdue >= 7) targetLevel = 'First';
                else if (daysOverdue > 0) targetLevel = 'Soft';

                const outAmt = parseFloat(inst.calculated_outstanding) || 0;
                const fAmt = outAmt * prob;

                return {
                    ...inst,
                    days_overdue: daysOverdue,
                    ageing_bucket: bucket,
                    risk_level: risk,
                    forecast_amount: fAmt,
                    suggested_reminder_level: targetLevel,
                    reminder_level: inst.last_reminder_level || (daysOverdue > 0 ? targetLevel : '-')
                };
            });
        },

        get filteredInstallments() {
            return this.processedInstallments.filter(inst => {
                if (this.filters.project_id && String(inst.project_id) !== String(this.filters.project_id)) return false;
                if (this.filters.customer_id && String(inst.customer_id) !== String(this.filters.customer_id)) return false;
                if (this.filters.ageing_bucket && this.filters.ageing_bucket !== 'All' && inst.ageing_bucket !== this.filters.ageing_bucket) return false;
                if (this.filters.risk_level && this.filters.risk_level !== 'All' && inst.risk_level !== this.filters.risk_level) return false;
                if (this.filters.reminder_status && this.filters.reminder_status !== 'All') {
                    if (this.filters.reminder_status === 'Sent' && inst.reminder_status !== 'Sent') return false;
                    if (this.filters.reminder_status === 'Pending' && inst.reminder_status !== 'Pending') return false;
                    if (this.filters.reminder_status === 'Failed' && inst.reminder_status !== 'Failed') return false;
                }
                return true;
            }).sort((a, b) => b.days_overdue - a.days_overdue);
        },

        get kpis() {
            let totalOutstanding = 0;
            let totalOverdue = 0;
            let currentNotDue = 0;
            let expectedCollection = 0;
            const allCusts = new Set();
            const overdueCusts = new Set();

            this.filteredInstallments.forEach(inst => {
                const out = parseFloat(inst.calculated_outstanding) || 0;
                totalOutstanding += out;
                if (inst.customer_id) allCusts.add(inst.customer_id);

                if (inst.days_overdue > 0) {
                    totalOverdue += out;
                    if (inst.customer_id) overdueCusts.add(inst.customer_id);
                    expectedCollection += (parseFloat(inst.forecast_amount) || 0);
                } else {
                    currentNotDue += out;
                    expectedCollection += out;
                }
            });

            return {
                total_outstanding: totalOutstanding,
                total_customers: allCusts.size,
                total_overdue: totalOverdue,
                overdue_customers: overdueCusts.size,
                current_not_due: currentNotDue,
                expected_collection: expectedCollection
            };
        },

        get isOverdueMode() {
            return this.kpis.total_overdue > 0;
        },

        get upcomingScheduleData() {
            const baseDate = this.filters.as_of_date ? new Date(this.filters.as_of_date + 'T00:00:00') : new Date();
            
            // Generate next 12 monthly slots
            const months = [];
            for (let i = 0; i < 12; i++) {
                const d = new Date(baseDate.getFullYear(), baseDate.getMonth() + i, 1);
                const monthKey = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                const label = d.toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
                months.push({
                    key: monthKey,
                    label: label,
                    amount: 0
                });
            }

            // 5 Timeline Horizon Buckets (Exact same color scheme as shown in the image)
            const horizons = {
                '0-3M': { label: '0 - 3 Months', amount: 0, color: '#4f46e5' },
                '3-6M': { label: '3 - 6 Months', amount: 0, color: '#f59e0b' },
                '6-9M': { label: '6 - 9 Months', amount: 0, color: '#22c55e' },
                '9-12M': { label: '9 - 12 Months', amount: 0, color: '#f97316' },
                '>12M': { label: '> 12 Months', amount: 0, color: '#ec4899' }
            };

            this.filteredInstallments.forEach(inst => {
                const out = parseFloat(inst.calculated_outstanding) || 0;
                if (out <= 0) return;

                if (inst.due_date_raw) {
                    const instDate = new Date(inst.due_date_raw + 'T00:00:00');
                    const diffDays = Math.floor((instDate.getTime() - baseDate.getTime()) / (1000 * 60 * 60 * 24));
                    const instMonthKey = instDate.getFullYear() + '-' + String(instDate.getMonth() + 1).padStart(2, '0');

                    const mObj = months.find(m => m.key === instMonthKey);
                    if (mObj) {
                        mObj.amount += out;
                    }

                    if (diffDays <= 90) {
                        horizons['0-3M'].amount += out;
                    } else if (diffDays <= 180) {
                        horizons['3-6M'].amount += out;
                    } else if (diffDays <= 270) {
                        horizons['6-9M'].amount += out;
                    } else if (diffDays <= 365) {
                        horizons['9-12M'].amount += out;
                    } else {
                        horizons['>12M'].amount += out;
                    }
                } else {
                    horizons['0-3M'].amount += out;
                }
            });

            return {
                months: months,
                horizons: Object.values(horizons)
            };
        },

        get donutChartData() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                return {
                    labels: ['0-30 Days', '31-60 Days', '61-90 Days', '91-120 Days', '> 120 Days'],
                    amounts: [summary['0-30'], summary['31-60'], summary['61-90'], summary['91-120'], summary['120+']],
                    colors: ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899']
                };
            } else {
                const hData = this.upcomingScheduleData.horizons;
                const amounts = hData.map(h => h.amount);
                const hasNonZero = amounts.some(a => a > 0);
                return {
                    labels: hData.map(h => h.label),
                    amounts: hasNonZero ? amounts : [1, 1, 1, 1, 1],
                    colors: ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899']
                };
            }
        },

        get barChartData() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                return {
                    seriesName: 'Overdue Amount',
                    labels: ['0-30 Days', '31-60 Days', '61-90 Days', '91-120 Days', '> 120 Days'],
                    amounts: [summary['0-30'], summary['31-60'], summary['61-90'], summary['91-120'], summary['120+']],
                    colors: ['#ef4444']
                };
            } else {
                const mData = this.upcomingScheduleData.months;
                return {
                    seriesName: 'Scheduled Inflow',
                    labels: mData.map(m => m.label),
                    amounts: mData.map(m => m.amount),
                    colors: ['#ef4444']
                };
            }
        },

        get summaryTableBuckets() {
            if (this.isOverdueMode) {
                const summary = {
                    '0-30': 0, '31-60': 0, '61-90': 0, '91-120': 0, '120+': 0
                };
                this.filteredInstallments.forEach(inst => {
                    if (inst.days_overdue > 0 && summary.hasOwnProperty(inst.ageing_bucket)) {
                        summary[inst.ageing_bucket] += (parseFloat(inst.calculated_outstanding) || 0);
                    }
                });
                const total = this.kpis.total_overdue;
                return [
                    { key: '0-30', label: '0-30 Days', amount: summary['0-30'], color: '#4f46e5', pct: total > 0 ? (summary['0-30'] / total * 100).toFixed(1) : '0.0' },
                    { key: '31-60', label: '31-60 Days', amount: summary['31-60'], color: '#f59e0b', pct: total > 0 ? (summary['31-60'] / total * 100).toFixed(1) : '0.0' },
                    { key: '61-90', label: '61-90 Days', amount: summary['61-90'], color: '#22c55e', pct: total > 0 ? (summary['61-90'] / total * 100).toFixed(1) : '0.0' },
                    { key: '91-120', label: '91-120 Days', amount: summary['91-120'], color: '#f97316', pct: total > 0 ? (summary['91-120'] / total * 100).toFixed(1) : '0.0' },
                    { key: '120+', label: '> 120 Days', amount: summary['120+'], color: '#ec4899', pct: total > 0 ? (summary['120+'] / total * 100).toFixed(1) : '0.0' },
                ];
            } else {
                const horizons = this.upcomingScheduleData.horizons;
                const total = this.kpis.total_outstanding;
                return horizons.map(h => ({
                    key: h.label,
                    label: h.label,
                    amount: h.amount,
                    color: h.color,
                    pct: total > 0 ? (h.amount / total * 100).toFixed(1) : '0.0'
                }));
            }
        },

        get totalPages() {
            return Math.ceil(this.filteredInstallments.length / this.perPage) || 1;
        },

        get paginatedInstallments() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredInstallments.slice(start, start + this.perPage);
        },

        resetFilters() {
            this.filters = {
                as_of_date: '',
                project_id: '',
                customer_id: '',
                ageing_bucket: '',
                risk_level: '',
                reminder_status: ''
            };
            this.currentPage = 1;
            this.updateCharts();
        },

        openModal(data) {
            this.modalData = data;
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = '';
            setTimeout(() => { this.modalData = null; }, 300);
        },

        formatNumber(val) {
            if (!val || isNaN(val)) return '0';
            return Number(Math.round(val)).toLocaleString('en-IN');
        },

        getAgeingColor(bucket) {
            switch(bucket) {
                case '31-60': return 'text-amber-500';
                case '61-90': return 'text-orange-500';
                case '91-120': return 'text-rose-500';
                case '120+': return 'text-red-600 font-semibold';
                case 'Current': return 'text-slate-500';
                default: return 'text-emerald-500';
            }
        },

        getRiskColor(risk) {
            switch(risk) {
                case 'Medium': return 'text-amber-500';
                case 'High': return 'text-orange-500';
                case 'Critical': return 'text-rose-500 font-semibold';
                case 'Severe': return 'text-red-600 font-bold';
                case 'None': return 'text-slate-500';
                default: return 'text-emerald-500';
            }
        },

        renderCharts() {
            const donutData = this.donutChartData;
            const barData = this.barChartData;

            // Donut Chart
            const donutEl = document.querySelector("#donutChart");
            if (donutEl) {
                donutEl.innerHTML = '';
                const donutOptions = {
                    series: donutData.amounts,
                    labels: donutData.labels,
                    chart: {
                        type: 'donut',
                        height: 200,
                        fontFamily: 'inherit',
                    },
                    colors: donutData.colors,
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '60%',
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return val.toFixed(0) + "%";
                        },
                        dropShadow: {
                            enabled: false
                        }
                    },
                    stroke: {
                        show: true,
                        colors: '#ffffff',
                        width: 2
                    },
                    legend: {
                        show: false
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                            }
                        }
                    }
                };

                this.donutChartInstance = new ApexCharts(donutEl, donutOptions);
                this.donutChartInstance.render();
            }

            // Bar Chart
            const barEl = document.querySelector("#barChart");
            if (barEl) {
                barEl.innerHTML = '';
                const barOptions = {
                    series: [{
                        name: barData.seriesName || 'Amount',
                        data: barData.amounts
                    }],
                    chart: {
                        type: 'bar',
                        height: 250,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit',
                    },
                    colors: barData.colors || ['#3b82f6'],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '45%',
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            if (!val || val === 0) return '';
                            if (val >= 100000) {
                                return '₹' + (val / 100000).toFixed(1) + 'L';
                            }
                            return '₹' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(val);
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            colors: ["#475569"]
                        }
                    },
                    xaxis: {
                        categories: barData.labels,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: '#64748b',
                                fontSize: '11px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                if (val >= 100000) {
                                    return (val / 100000).toFixed(0) + "L";
                                }
                                return val;
                            },
                            style: {
                                colors: '#64748b',
                                fontSize: '11px'
                            }
                        },
                        title: {
                            text: 'Amount (₹)',
                            style: {
                                color: '#64748b',
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                            }
                        }
                    }
                };

                this.barChartInstance = new ApexCharts(barEl, barOptions);
                this.barChartInstance.render();
            }
        },

        updateCharts() {
            this.$nextTick(() => {
                const donutData = this.donutChartData;
                const barData = this.barChartData;

                if (this.donutChartInstance) {
                    this.donutChartInstance.updateOptions({
                        series: donutData.amounts,
                        labels: donutData.labels,
                        colors: donutData.colors
                    });
                }
                if (this.barChartInstance) {
                    this.barChartInstance.updateOptions({
                        xaxis: {
                            categories: barData.labels
                        },
                        colors: barData.colors
                    });
                    this.barChartInstance.updateSeries([{
                        name: barData.seriesName || 'Amount',
                        data: barData.amounts
                    }]);
                }
            });
        },

        exportTableToCSV() {
            const headers = ["Customer", "Sale No", "Project", "Unit", "Inst No", "Due Date", "Outstanding", "Days Overdue", "Ageing", "Risk", "Reminder Level", "Last Reminder"];
            const rows = this.filteredInstallments.map(i => [
                `"${(i.customer_name || '').replace(/"/g, '""')}"`,
                `"${(i.sale_number || '').replace(/"/g, '""')}"`,
                `"${(i.project_name || '').replace(/"/g, '""')}"`,
                `"${(i.unit_name || '').replace(/"/g, '""')}"`,
                i.installment_no,
                i.due_date_formatted,
                i.calculated_outstanding,
                i.days_overdue,
                i.ageing_bucket,
                i.risk_level,
                `"${(i.reminder_level || '').replace(/"/g, '""')}"`,
                i.last_reminder_date
            ]);

            const csvContent = "data:text/csv;charset=utf-8," + [headers.join(","), ...rows.map(e => e.join(","))].join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `collection_forecast_${this.filters.as_of_date || 'report'}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
}
</script>

</x-erp-layout>
