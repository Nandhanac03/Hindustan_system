<x-erp-layout title="Partner Statement & Equity Ledger" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="partnerStatementApp()" x-init="init()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Header & Reports Export Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Partner Statement & Equity Ledger</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Track partner profit share, payouts and current balance owed.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="flex flex-wrap items-center gap-2.5">
                    
                    {{-- 1. Partner Statement Export --}}
                    <button @click="exportExcel('partner_statement')" 
                            class="p-2 pr-3.5 bg-white border border-slate-200/90 rounded-2xl hover:bg-slate-50/80 hover:border-[#a38c29]/50 transition-all duration-300 flex items-center gap-3 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer group">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-sm group-hover:scale-105">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-black text-slate-800 group-hover:text-[#a38c29] transition-colors leading-tight">Partner Statement</span>
                            <div class="flex items-center gap-1 mt-1">
                                <span @click.stop="printReport('Partner Statement')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-rose-50 text-rose-600 border border-rose-100/80 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer">PDF</span>
                                <span @click.stop="exportExcel('partner_statement')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/80 hover:bg-emerald-600 hover:text-white transition-colors cursor-pointer">EXCEL</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#a38c29] group-hover:translate-x-0.5 transition-all ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>

                    {{-- 2. Profit Sharing Summary Export --}}
                    <button @click="exportExcel('profit_sharing_summary')" 
                            class="p-2 pr-3.5 bg-white border border-slate-200/90 rounded-2xl hover:bg-slate-50/80 hover:border-emerald-300 transition-all duration-300 flex items-center gap-3 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-sm group-hover:scale-105">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-black text-slate-800 group-hover:text-emerald-700 transition-colors leading-tight">Profit Sharing Summary</span>
                            <div class="flex items-center gap-1 mt-1">
                                <span @click.stop="printReport('Profit Sharing Summary')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-rose-50 text-rose-600 border border-rose-100/80 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer">PDF</span>
                                <span @click.stop="exportExcel('profit_sharing_summary')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/80 hover:bg-emerald-600 hover:text-white transition-colors cursor-pointer">EXCEL</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>

                    {{-- 3. Distribution History Log Export --}}
                    <button @click="exportExcel('distribution_history_log')" 
                            class="p-2 pr-3.5 bg-white border border-slate-200/90 rounded-2xl hover:bg-slate-50/80 hover:border-indigo-300 transition-all duration-300 flex items-center gap-3 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer group">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-sm group-hover:scale-105">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-black text-slate-800 group-hover:text-indigo-700 transition-colors leading-tight">Distribution History Log</span>
                            <div class="flex items-center gap-1 mt-1">
                                <span @click.stop="printReport('Distribution History Log')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-rose-50 text-rose-600 border border-rose-100/80 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer">PDF</span>
                                <span @click.stop="exportExcel('distribution_history_log')" class="px-1.5 py-0.5 rounded text-[8px] font-extrabold tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/80 hover:bg-emerald-600 hover:text-white transition-colors cursor-pointer">EXCEL</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>

                </div>
            </div>
        </div>

        {{-- 4 Metric KPI Cards Grid (Placed Above Filter) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Card 1: Agreed Profit Share --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Agreed Profit Share</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300" x-text="agreedProfitShareFormatted">
                        {{ number_format($agreedProfitShare, 1) }}%
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">As per Partnership Agreement</p>
                </div>
            </div>

            {{-- Card 2: Earned Profit Share --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Earned Profit Share</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300" x-text="formatCurrency(totalCredit)">
                        Rs. {{ number_format($earnedProfitShare, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Partner's share of current project net profit</p>
                </div>
            </div>

            {{-- Card 3: Total Payouts Released --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Payouts Released</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300" x-text="formatCurrency(totalDebit)">
                        Rs. {{ number_format($totalPayoutsReleased, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total profit payouts / drawings released to date</p>
                </div>
            </div>

            {{-- Card 4: Current Net Equity Balance --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-3 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Current Net Equity Balance</span>
                    </div>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-[#a38c29] font-mono tracking-tight block group-hover:text-[#8e7a23] transition-colors duration-300" x-text="formatCurrency(totalRunningBalance)">
                        Rs. {{ number_format($currentNetEquityBalance, 0) }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Earned Profit Share - Payouts Released</p>
                </div>
            </div>

        </div>

        {{-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (NO PAGE REFRESH NEEDED) ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 w-full">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 flex-1">
                    
                    {{-- 1. Partner Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select x-model="filters.partner_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Partners</option>
                            @foreach($partners as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 2. Project Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <select x-model="filters.project_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Projects</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 3. Date Range Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <select x-model="filters.date_range" @change="handleDateRangeChange()"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="inception">Project Inception to Date</option>
                            <option value="this_month">This Month</option>
                            <option value="this_quarter">This Quarter</option>
                            <option value="this_fy">This Financial Year</option>
                            <option value="custom">Custom Date Range</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 4. From Date --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.from_date" @change="filters.date_range = 'custom'; currentPage = 1"
                               title="From Date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer">
                    </div>

                    {{-- 5. To Date --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.to_date" @change="filters.date_range = 'custom'; currentPage = 1"
                               title="To Date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer">
                    </div>

                </div>

                {{-- Single Reset Filters Button (No Refresh, No Apply Button) --}}
                <div class="shrink-0 flex items-center">
                    <button type="button" @click="resetFilters()"
                            class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer whitespace-nowrap group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Section A: Individual Partner Statement of Account (Running Ledger) --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">A. Individual Partner Statement of Account (Running Ledger)</h2>
                </div>
                <span class="text-xs font-bold text-slate-400" x-text="filteredLedger.length + ' records found'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Transaction Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Partner Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Reference / Voucher No.</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Description / Transaction Type</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Profit Share Allocated<br><span class="text-[9px] font-normal text-white/80">(Credit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Payout Released<br><span class="text-[9px] font-normal text-white/80">(Debit - Rs.)</span></th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Running Payable Balance<br><span class="text-[9px] font-normal text-white/80">(Rs.)</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(entry, index) in pagedLedger" :key="index">
                            <tr class="hover:bg-slate-50 transition-colors font-medium">
                                <td class="px-5 py-3.5 whitespace-nowrap text-slate-700 font-semibold border-r border-slate-100" x-text="formatDate(entry.date)"></td>
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100 whitespace-nowrap" x-text="entry.partner_name"></td>
                                <td class="px-5 py-3.5 font-mono text-slate-700 font-bold border-r border-slate-100" x-text="entry.ref_no"></td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100" x-text="entry.description"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(entry.credit)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(entry.debit)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap" x-text="formatCurrency(entry.running_balance)"></td>
                            </tr>
                        </template>

                        <tr x-show="filteredLedger.length === 0">
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400 font-semibold text-xs">
                                No transactions found matching the selected filter criteria.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#a38c29]/10 font-black text-slate-900 border-t-2 border-[#a38c29]/30">
                            <td colspan="4" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">TOTALS</td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalCredit)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalDebit)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-slate-900 font-black text-sm whitespace-nowrap" x-text="formatCurrency(totalRunningBalance)"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    SHOWING <span class="text-slate-900 font-black" x-text="filteredLedger.length > 0 ? ((currentPage - 1) * pageSize + 1) : 0"></span> TO <span class="text-slate-900 font-black" x-text="Math.min(currentPage * pageSize, filteredLedger.length)"></span> OF <span class="text-slate-900 font-black" x-text="filteredLedger.length"></span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" 
                            @click="if(currentPage > 1) currentPage--" 
                            :disabled="currentPage <= 1" 
                            class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors shadow-2xs">
                        PREV
                    </button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="currentPage = p" 
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'" 
                                class="w-8 h-8 rounded-lg font-bold text-xs flex items-center justify-center transition-colors cursor-pointer" 
                                x-text="p">
                        </button>
                    </template>
                    
                    <button type="button" 
                            @click="if(currentPage < totalPages) currentPage++" 
                            :disabled="currentPage >= totalPages" 
                            class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer transition-colors shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

        {{-- Section B: Project-Wide Equity & Profit Distribution Matrix --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs space-y-0">
            <div class="bg-white px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wide">B. Project-Wide Equity & Profit Distribution Matrix</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-bold tracking-wide">
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Partner Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold border-r border-[#8e7a23]">Role / Entity Type</th>
                            <th class="px-5 py-3.5 text-center text-white font-extrabold border-r border-[#8e7a23]">Agreed Share (%)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Total Allocated Net Profit (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Total Payouts Released (Rs.)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Current Net Balance Owed (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(pRow, pIdx) in matrixList" :key="pIdx">
                            <tr class="hover:bg-slate-50 transition-colors font-medium" :class="filters.partner_id && String(pRow.id) === String(filters.partner_id) ? 'bg-[#a38c29]/5' : ''">
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100">
                                    <span x-text="pRow.name"></span>
                                    <span class="text-xs text-slate-500 font-normal" x-text="' (' + Number(pRow.share_pct).toFixed(1) + '%)'"></span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100" x-text="pRow.role"></td>
                                <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100" x-text="Number(pRow.share_pct).toFixed(1) + '%'"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_allocated)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_payouts)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-black text-[#a38c29] whitespace-nowrap" x-text="formatCurrency(pRow.net_balance)"></td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#a38c29]/10 font-black text-slate-900 border-t-2 border-[#a38c29]/30">
                            <td colspan="2" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">PROJECT TOTALS</td>
                            <td class="px-5 py-3.5 text-center font-mono text-slate-900 border-r border-slate-200" x-text="totalMatrixAgreedPct.toFixed(1) + '%'"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixPayouts)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-slate-900 font-black text-sm whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated - totalMatrixPayouts)"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
                <div class="flex items-center gap-1.5 text-slate-500">
                    <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Net Balance Owed = Total Allocated Net Profit - Total Payouts Released</span>
                </div>
                <div class="flex items-center gap-3">
                    <span x-text="'Showing ' + matrixList.length + ' entries'"></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function partnerStatementApp() {
    return {
        filters: {
            partner_id: '{{ request('partner_id', '') }}',
            project_id: '{{ request('project_id', '') }}',
            date_range: '{{ request('date_range', 'inception') }}',
            from_date: '{{ $dateFrom ?? '' }}',
            to_date: '{{ $dateTo ?? '' }}',
        },
        pageSize: 10,
        currentPage: 1,

        rawLedger: @json($runningLedger) || [],
        matrixList: @json($matrixPartners) || [],

        partners: @json($partners) || [],
        projects: @json($projects) || [],

        init() {
            // Live reactive initialization
        },

        get filteredLedger() {
            let list = this.rawLedger;

            if (this.filters.partner_id) {
                list = list.filter(r => String(r.partner_id) === String(this.filters.partner_id));
            }

            if (this.filters.from_date) {
                list = list.filter(r => r.date >= this.filters.from_date);
            }

            if (this.filters.to_date) {
                list = list.filter(r => r.date <= this.filters.to_date);
            }

            // Recalculate running balance live
            let bal = 0;
            return list.map(item => {
                bal += (Number(item.credit || 0) - Number(item.debit || 0));
                return {
                    ...item,
                    running_balance: bal
                };
            });
        },

        get pagedLedger() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredLedger.slice(start, start + this.pageSize);
        },

        get totalPages() {
            return Math.ceil(this.filteredLedger.length / this.pageSize) || 1;
        },

        get totalCredit() {
            return this.filteredLedger.reduce((sum, r) => sum + Number(r.credit || 0), 0);
        },

        get totalDebit() {
            return this.filteredLedger.reduce((sum, r) => sum + Number(r.debit || 0), 0);
        },

        get totalRunningBalance() {
            return this.totalCredit - this.totalDebit;
        },

        get agreedProfitShareFormatted() {
            if (this.filters.partner_id) {
                const p = this.matrixList.find(m => String(m.id) === String(this.filters.partner_id));
                if (p) return Number(p.share_pct).toFixed(1) + '%';
            }
            return (this.matrixList[0] ? Number(this.matrixList[0].share_pct).toFixed(1) : '57.5') + '%';
        },

        get totalMatrixAgreedPct() {
            return this.matrixList.reduce((sum, p) => sum + Number(p.share_pct || 0), 0);
        },

        get totalMatrixAllocated() {
            return this.matrixList.reduce((sum, p) => sum + Number(p.total_allocated || 0), 0);
        },

        get totalMatrixPayouts() {
            return this.matrixList.reduce((sum, p) => sum + Number(p.total_payouts || 0), 0);
        },

        handleDateRangeChange() {
            const now = new Date();
            const y = now.getFullYear();
            const m = now.getMonth();

            if (this.filters.date_range === 'this_month') {
                const start = new Date(y, m, 1);
                const end = new Date(y, m + 1, 0);
                this.filters.from_date = start.toISOString().split('T')[0];
                this.filters.to_date = end.toISOString().split('T')[0];
            } else if (this.filters.date_range === 'this_quarter') {
                const q = Math.floor(m / 3);
                const start = new Date(y, q * 3, 1);
                const end = new Date(y, q * 3 + 3, 0);
                this.filters.from_date = start.toISOString().split('T')[0];
                this.filters.to_date = end.toISOString().split('T')[0];
            } else if (this.filters.date_range === 'this_fy') {
                const fyStartYear = m >= 3 ? y : y - 1;
                this.filters.from_date = `${fyStartYear}-04-01`;
                this.filters.to_date = `${fyStartYear + 1}-03-31`;
            } else if (this.filters.date_range === 'inception') {
                this.filters.from_date = '';
                this.filters.to_date = '';
            }
            this.currentPage = 1;
        },

        resetFilters() {
            this.filters.partner_id = '';
            this.filters.project_id = '';
            this.filters.date_range = 'inception';
            this.filters.from_date = '';
            this.filters.to_date = '';
            this.currentPage = 1;
        },

        formatCurrency(num) {
            return 'Rs. ' + Number(num || 0).toLocaleString('en-IN');
        },

        formatDate(d) {
            if (!d) return '-';
            const dt = new Date(d);
            if (isNaN(dt.getTime())) return d;
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const day = String(dt.getDate()).padStart(2, '0');
            const month = months[dt.getMonth()];
            const year = dt.getFullYear();
            return `${day}-${month}-${year}`;
        },

        printReport(title) {
            window.print();
        },

        exportExcel(type = 'partner_statement') {
            if (typeof reportsApp === 'function') {
                const rApp = reportsApp();
                if (rApp && typeof rApp.exportCurrentTable === 'function') {
                    rApp.exportCurrentTable(type);
                    return;
                }
            }
        }
    }
}
</script>

{{-- ── HIDDEN EXCEL EXPORT TABLES (STYLED TO MATCH SALES REPORT EXCEL DESIGN) ── --}}
<div class="hidden" style="display: none;">

    {{-- 1. PARTNER STATEMENT EXCEL TABLE --}}
    <table id="partnerStatementExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="130" style="width: 98pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="140" style="width: 105pt;" />
            <col width="320" style="width: 240pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PARTNER STATEMENT & EQUITY LEDGER REPORT
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    EXECUTIVE SUMMARY & METRIC KPIS
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #334155; font-weight: bold; font-size: 9pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Agreed Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #a38c29; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.0%';">{{ number_format((float)($agreedProfitShare ?? 0), 1) }}%</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #334155; font-weight: bold; font-size: 9pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Earned Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #059669; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($earnedProfitShare ?? $totalCredit ?? 0) }}</th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #334155; font-weight: bold; font-size: 9pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Payouts Released:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #e11d48; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalPayoutsReleased ?? $totalDebit ?? 0) }}</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #334155; font-weight: bold; font-size: 9pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Current Net Equity Balance:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #a38c29; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($currentNetEquityBalance ?? $runningBalance ?? 0) }}</th>
            </tr>
            <tr height="15" style="height: 15pt;"><th colspan="8" bgcolor="#ffffff" style="border: none;"></th></tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    A. INDIVIDUAL PARTNER STATEMENT OF ACCOUNT (RUNNING LEDGER)
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">SL NO</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23; mso-number-format: 'dd\-mmm\-yyyy';">TRANSACTION DATE</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">PARTNER NAME</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">VOUCHER / REF NO</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">DESCRIPTION / TRANSACTION TYPE</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">PROFIT SHARE ALLOCATED (CREDIT)</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #8e7a23;">RUNNING PAYABLE BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'dd\-mmm\-yyyy';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->description : ($entry['description'] ?? '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #fef08a;">
                <td colspan="5" bgcolor="#fef08a" style="background-color: #fef08a; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL LEDGER BALANCE</td>
                <td bgcolor="#fef08a" style="background-color: #fef08a; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#fef08a" style="background-color: #fef08a; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#fef08a" style="background-color: #fef08a; font-weight: bold; text-align: right; color: #1e293b; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($runningBalance ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- 2. PROFIT SHARING SUMMARY EXCEL TABLE --}}
    <table id="profitSharingExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="120" style="width: 90pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="7" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PROJECT PROFIT SHARING & EQUITY DISTRIBUTION SUMMARY
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">SL NO</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">PARTNER NAME</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">ROLE / ENTITY TYPE</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">AGREED SHARE (%)</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">TOTAL ALLOCATED NET PROFIT</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">TOTAL PAYOUTS RELEASED</th>
                <th bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #065f46;">CURRENT NET BALANCE OWED</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrixPartners as $pIdx => $pRow)
                <tr height="25" style="height: 25pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $pIdx + 1 }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->name : ($pRow['name'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->role : ($pRow['role'] ?? '') }}</td>
                    <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.0%';">{{ number_format((float)(is_object($pRow) ? $pRow->share_pct : ($pRow['share_pct'] ?? 0)), 1) }}%</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($pRow) ? $pRow->total_allocated : ($pRow['total_allocated'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($pRow) ? $pRow->total_payouts : ($pRow['total_payouts'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #a38c29; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($pRow) ? $pRow->net_balance : ($pRow['net_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #d1fae5;">
                <td colspan="3" bgcolor="#d1fae5" style="background-color: #d1fae5; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">PROJECT TOTALS</td>
                <td bgcolor="#d1fae5" style="background-color: #d1fae5; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.0%';">{{ number_format((float)($totalMatrixAgreedPct ?? 0), 1) }}%</td>
                <td bgcolor="#d1fae5" style="background-color: #d1fae5; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalMatrixAllocated ?? 0) }}</td>
                <td bgcolor="#d1fae5" style="background-color: #d1fae5; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalMatrixPayouts ?? 0) }}</td>
                <td bgcolor="#d1fae5" style="background-color: #d1fae5; font-weight: bold; text-align: right; color: #047857; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(($totalMatrixAllocated ?? 0) - ($totalMatrixPayouts ?? 0)) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- 3. DISTRIBUTION HISTORY LOG EXCEL TABLE --}}
    <table id="distributionHistoryExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="60" style="width: 45pt;" />
            <col width="130" style="width: 98pt;" />
            <col width="180" style="width: 135pt;" />
            <col width="150" style="width: 113pt;" />
            <col width="320" style="width: 240pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="160" style="width: 120pt;" />
            <col width="180" style="width: 135pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: PARTNER PROFIT ALLOCATION & PAYOUT DISTRIBUTION HISTORY LOG
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">SL NO</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3; mso-number-format: 'dd\-mmm\-yyyy';">DATE</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">PARTNER NAME</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">REF / VOUCHER NO</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">DESCRIPTION</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">ALLOCATED PROFIT (CREDIT)</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#4338ca" style="background-color: #4338ca; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #3730a3;">RUNNING BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'dd\-mmm\-yyyy';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->description : ($entry['description'] ?? '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #e0e7ff;">
                <td colspan="5" bgcolor="#e0e7ff" style="background-color: #e0e7ff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL DISTRIBUTION LOG SUMMARY</td>
                <td bgcolor="#e0e7ff" style="background-color: #e0e7ff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#e0e7ff" style="background-color: #e0e7ff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#e0e7ff" style="background-color: #e0e7ff; font-weight: bold; text-align: right; color: #3730a3; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0';">{{ (float)($runningBalance ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>

</div>

@include('reports.partials.script')

</x-erp-layout>
