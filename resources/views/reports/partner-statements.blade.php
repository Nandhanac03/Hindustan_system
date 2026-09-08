<x-erp-layout title="Partner Statement & Equity Ledger" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="partnerStatementApp()" x-init="init()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Header & Reports Export Bar --}}
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Partner Statement & Equity Ledger</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Track partner profit share, payouts and current balance owed.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                
                {{-- 1. Partner Statement Export --}}
                <button @click="exportExcel('partner_statement')" 
                        class="px-3.5 py-2.5 bg-[#009661] hover:bg-[#008254] text-white text-[11px] font-extrabold rounded-2xl transition-all duration-300 shadow hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2 uppercase tracking-wider cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Partner Statement</span>
                </button>

                {{-- 2. Profit Sharing Summary Export --}}
                <button @click="exportExcel('profit_sharing_summary')" 
                        class="px-3.5 py-2.5 bg-[#009661] hover:bg-[#008254] text-white text-[11px] font-extrabold rounded-2xl transition-all duration-300 shadow hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2 uppercase tracking-wider cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Profit Sharing Summary</span>
                </button>

                {{-- 3. Distribution History Log Export --}}
                <button @click="exportExcel('distribution_history_log')" 
                        class="px-3.5 py-2.5 bg-[#009661] hover:bg-[#008254] text-white text-[11px] font-extrabold rounded-2xl transition-all duration-300 shadow hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2 uppercase tracking-wider cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <polyline points="9 15 12 18 15 15"></polyline>
                    </svg>
                    <span>Distribution History Log</span>
                </button>

                {{-- 4. Record Partner Payout Button --}}
                <button @click="openPayoutModal()" 
                        class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white font-bold rounded-2xl transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg hover:-translate-y-0.5 cursor-pointer whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span class="text-xs font-black tracking-wide">Record Partner Payout</span>
                </button>

            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-[#a38c29]/10 border border-[#a38c29]/30 rounded-2xl flex items-center justify-between text-[#7c691c] text-xs font-bold shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

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
                                <td class="px-5 py-3.5 font-semibold text-slate-800 border-r border-slate-100">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span x-text="entry.description"></span>
                                        <template x-if="entry.payment_mode">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold tracking-wider uppercase border shadow-2xs"
                                                  :class="{
                                                      'bg-emerald-50 text-emerald-700 border-emerald-200': entry.payment_mode === 'Bank Transfer',
                                                      'bg-blue-50 text-blue-700 border-blue-200': entry.payment_mode === 'Cheque',
                                                      'bg-amber-50 text-amber-700 border-amber-200': entry.payment_mode === 'Cash',
                                                      'bg-purple-50 text-purple-700 border-purple-200': entry.payment_mode === 'UPI / Online' || entry.payment_mode === 'Online'
                                                  }"
                                                  x-text="entry.payment_mode">
                                            </span>
                                        </template>
                                    </div>
                                </td>
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
                            <th class="px-5 py-3.5 text-right text-white font-extrabold border-r border-[#8e7a23]">Current Net Balance Owed (Rs.)</th>
                            <th class="px-4 py-3.5 text-center text-white font-extrabold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        <template x-for="(pRow, pIdx) in filteredMatrixList" :key="pIdx">
                            <tr class="hover:bg-slate-50 transition-colors font-medium" :class="filters.partner_id && String(pRow.id) === String(filters.partner_id) ? 'bg-[#a38c29]/5' : ''">
                                <td class="px-5 py-3.5 font-bold text-slate-900 border-r border-slate-100">
                                    <span x-text="pRow.name"></span>
                                    <span class="text-xs text-slate-500 font-normal" x-text="' (' + Number(pRow.share_pct).toFixed(1) + '%)'"></span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-600 border-r border-slate-100" x-text="pRow.role"></td>
                                <td class="px-5 py-3.5 text-center font-bold text-slate-900 border-r border-slate-100" x-text="Number(pRow.share_pct).toFixed(1) + '%'"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_allocated)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.total_payouts)"></td>
                                <td class="px-5 py-3.5 text-right font-mono font-black text-[#a38c29] border-r border-slate-100 whitespace-nowrap" x-text="formatCurrency(pRow.net_balance)"></td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <button type="button" @click="openPayoutModal(pRow.id)" class="px-3 py-1 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white font-extrabold text-[10px] uppercase rounded-lg border border-[#a38c29]/30 transition-all cursor-pointer">
                                        Record Payout
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#a38c29]/10 font-black text-slate-900 border-t-2 border-[#a38c29]/30">
                            <td colspan="2" class="px-5 py-3.5 uppercase tracking-wider text-slate-900 border-r border-slate-200">PROJECT TOTALS</td>
                            <td class="px-5 py-3.5 text-center font-mono text-slate-900 border-r border-slate-200" x-text="totalMatrixAgreedPct.toFixed(1) + '%'"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-rose-600 border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixPayouts)"></td>
                            <td class="px-5 py-3.5 text-right font-mono text-slate-900 font-black text-sm border-r border-slate-200 whitespace-nowrap" x-text="formatCurrency(totalMatrixAllocated - totalMatrixPayouts)"></td>
                            <td></td>
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
                    <span x-text="'Showing ' + filteredMatrixList.length + ' entries'"></span>
                </div>
            </div>
        </div>

        {{-- ── RECORD PARTNER PAYOUT MODAL ── --}}
        <div x-show="showPayoutModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;">
            <div @click.away="showPayoutModal = false" 
                 class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col transform transition-all">
                
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">PARTNER PAYOUT SETUP</p>
                            <h2 class="text-lg font-extrabold text-white">Record Partner Payout</h2>
                        </div>
                        <button type="button" @click="showPayoutModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body Form --}}
                <form action="{{ route('reports.partner_statements.payout') }}" method="POST" class="flex flex-col overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-4 overflow-y-auto max-h-[calc(95vh-130px)]">
                        
                        {{-- 1. Partner Selection --}}
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">SELECT PARTNER <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <select name="partner_id" x-model="modalData.partner_id" required class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                    <option value="">-- Choose Partner --</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->role ?? 'Partner' }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Project Selection --}}
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">SELECT PROJECT <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <select name="project_id" x-model="modalData.project_id" required class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                    <option value="">-- Choose Project --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Payment Mode & Pay From Account --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">PAYMENT MODE <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="payment_mode" x-model="modalData.payment_mode" required class="w-full pl-3 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                        <option value="">-- Choose Payment Mode --</option>
                                        @if(isset($paymentModes) && count($paymentModes) > 0)
                                            @foreach($paymentModes as $pm)
                                                <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank Transfer (NEFT / RTGS / IMPS)">Bank Transfer (NEFT / RTGS / IMPS)</option>
                                            <option value="UPI / Online Payment">UPI / Online Payment</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">PAY FROM ACCOUNT <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="company_bank_account_id" x-model="modalData.company_bank_account_id" required class="w-full pl-3 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                        <option value="">-- Choose Account --</option>
                                        @if(isset($companyBankAccounts) && count($companyBankAccounts) > 0)
                                            @foreach($companyBankAccounts as $cBank)
                                                <option value="{{ $cBank->id }}">{{ $cBank->bank_name }} Account ({{ $cBank->account_number ? 'BANK-'.substr($cBank->account_number, 0, 8).'...' : $cBank->account_name }}) — Avail: Rs. {{ number_format((float)($cBank->current_balance ?? $cBank->opening_balance ?? 0), 2) }}</option>
                                            @endforeach
                                        @else
                                            @foreach($bankAccounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }} {{ isset($acc->code) ? '('.$acc->code.')' : '' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Payout Amount & Payout Date --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">PAYOUT AMOUNT (RS.) <span class="text-rose-500">*</span></label>
                                <input type="number" name="allocated_amount" x-model="modalData.allocated_amount" step="0.01" min="1" required placeholder="50,000" class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">PAYOUT DATE <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" x-model="modalData.date" required class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer" />
                            </div>
                        </div>

                        {{-- 5. Reference / Narration --}}
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">REFERENCE / NARRATION <span class="text-rose-500">*</span></label>
                            <textarea name="remarks" x-model="modalData.remarks" rows="2" required placeholder="Partner Profit Payout - Q2 Distribution" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs"></textarea>
                        </div>

                        {{-- Live Error Banner --}}
                        <template x-if="modalErrorMessage">
                            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-700 text-xs font-bold shadow-2xs">
                                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="modalErrorMessage"></span>
                            </div>
                        </template>

                        {{-- 6. Live Dynamic Balance Summary Box --}}
                        <div class="bg-slate-50/90 border border-slate-200/90 rounded-2xl p-4 space-y-2 shadow-2xs text-xs">
                            <template x-if="modalSelectedBankAccount">
                                <div class="space-y-1.5 pb-2 border-b border-slate-200/80">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-bold text-slate-600">Selected Bank Account Balance (<span x-text="modalSelectedBankAccount?.bank_name"></span>)</span>
                                        <span class="font-mono font-extrabold text-blue-600 text-sm shrink-0" x-text="formatCurrency(modalSelectedBankBalance)">Rs. 0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-bold text-slate-600">Bank Balance After Payout</span>
                                        <span class="font-mono font-bold text-sm shrink-0" :class="modalBankBalanceAfterPayout < 0 ? 'text-rose-600 font-extrabold' : 'text-slate-800'" x-text="formatCurrency(modalBankBalanceAfterPayout)">Rs. 0</span>
                                    </div>
                                </div>
                            </template>

                            <div class="flex items-center justify-between gap-3 pt-1">
                                <span class="font-bold text-slate-600">Available Partner Balance</span>
                                <span class="font-mono font-extrabold text-emerald-600 text-sm shrink-0" x-text="formatCurrency(modalSelectedPartnerBalance)">Rs. 0</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-bold text-slate-600">Payout Amount</span>
                                <span class="font-mono font-extrabold text-rose-500 text-sm shrink-0" x-text="formatCurrency(modalPayoutAmount)">Rs. 0</span>
                            </div>
                            <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between gap-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider pr-2">Partner Balance After Payout</span>
                                <span class="font-mono font-black text-slate-900 text-base shrink-0" x-text="formatCurrency(modalBalanceAfterPayout)">Rs. 0</span>
                            </div>
                        </div>

                    </div>

                    {{-- Actions Footer --}}
                    <div class="px-6 py-4 border-t border-slate-200/80 flex items-center justify-end gap-3 bg-white flex-shrink-0">
                        <button type="button" @click="showPayoutModal = false" class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-extrabold rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer">
                            CANCEL
                        </button>
                        <button type="submit"
                                :disabled="Boolean(modalErrorMessage)"
                                :class="modalErrorMessage ? 'opacity-50 cursor-not-allowed bg-slate-400 hover:bg-slate-400' : 'bg-[#a38c29] hover:bg-[#8e7a23] cursor-pointer'"
                                class="px-6 py-2.5 text-white font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                            CONFIRM & POST PAYOUT
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function partnerStatementApp() {
    return {
        showPayoutModal: false,
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
        bankAccounts: @json($bankAccounts) || [],
        companyBankAccounts: @json($companyBankAccounts ?? []) || [],
        paymentModes: @json($paymentModes ?? []) || [],

        modalData: {
            partner_id: '',
            project_id: '',
            payment_mode: '',
            company_bank_account_id: '',
            allocated_amount: '',
            date: '{{ date('Y-m-d') }}',
            remarks: ''
        },

        openPayoutModal(partnerId = null, projectId = null) {
            this.modalData.partner_id = partnerId ? String(partnerId) : (this.filters.partner_id ? String(this.filters.partner_id) : (this.partners[0] ? String(this.partners[0].id) : ''));
            this.modalData.project_id = projectId ? String(projectId) : (this.filters.project_id ? String(this.filters.project_id) : (this.projects[0] ? String(this.projects[0].id) : ''));
            this.modalData.payment_mode = (this.paymentModes && this.paymentModes[0]) ? this.paymentModes[0].name : 'Bank Transfer';
            this.modalData.company_bank_account_id = (this.companyBankAccounts && this.companyBankAccounts[0]) ? String(this.companyBankAccounts[0].id) : ((this.bankAccounts && this.bankAccounts[0]) ? String(this.bankAccounts[0].id) : '');
            this.modalData.allocated_amount = '';
            this.modalData.date = new Date().toISOString().split('T')[0];
            this.modalData.remarks = '';
            this.showPayoutModal = true;
        },

        get modalSelectedPartnerBalance() {
            if (!this.modalData.partner_id) return 0;
            const p = this.matrixList.find(m => String(m.id) === String(this.modalData.partner_id));
            return p ? Number(p.net_balance || 0) : 0;
        },

        get modalSelectedBankAccount() {
            if (!this.modalData.company_bank_account_id) return null;
            return this.companyBankAccounts.find(b => String(b.id) === String(this.modalData.company_bank_account_id)) || null;
        },

        get modalSelectedBankBalance() {
            const b = this.modalSelectedBankAccount;
            if (!b) return 0;
            return Number(b.current_balance !== null && b.current_balance !== undefined ? b.current_balance : (b.opening_balance || 0));
        },

        get modalBankBalanceAfterPayout() {
            return this.modalSelectedBankBalance - this.modalPayoutAmount;
        },

        get modalPayoutAmount() {
            return Number(this.modalData.allocated_amount || 0);
        },

        get modalBalanceAfterPayout() {
            return this.modalSelectedPartnerBalance - this.modalPayoutAmount;
        },

        get isBankInsufficient() {
            if (!this.modalSelectedBankAccount) return false;
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > this.modalSelectedBankBalance;
        },

        get isPartnerInsufficient() {
            return this.modalPayoutAmount > 0 && this.modalPayoutAmount > this.modalSelectedPartnerBalance;
        },

        get modalErrorMessage() {
            if (this.isBankInsufficient) {
                return `Insufficient Bank Funds! Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds available balance in ${this.modalSelectedBankAccount?.bank_name || 'selected bank'} (${this.formatCurrency(this.modalSelectedBankBalance)}).`;
            }
            if (this.isPartnerInsufficient) {
                return `Payout amount (${this.formatCurrency(this.modalPayoutAmount)}) exceeds available partner balance (${this.formatCurrency(this.modalSelectedPartnerBalance)}).`;
            }
            return '';
        },

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

        get filteredMatrixList() {
            if (!this.filters.partner_id) return this.matrixList;
            return this.matrixList.filter(p => String(p.id) === String(this.filters.partner_id));
        },

        get totalMatrixAgreedPct() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.share_pct || 0), 0);
        },

        get totalMatrixAllocated() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.total_allocated || 0), 0);
        },

        get totalMatrixPayouts() {
            return this.filteredMatrixList.reduce((sum, p) => sum + Number(p.total_payouts || 0), 0);
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
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                    EXECUTIVE SUMMARY & METRIC KPIS
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Agreed Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)($agreedProfitShare ?? 0), 2) }}%</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Earned Profit Share:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($earnedProfitShare ?? $totalCredit ?? 0) }}</th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Payouts Released:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalPayoutsReleased ?? $totalDebit ?? 0) }}</th>
                <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Current Net Equity Balance:</th>
                <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($currentNetEquityBalance ?? $runningBalance ?? 0) }}</th>
            </tr>
            <tr height="15" style="height: 15pt;"><th colspan="8" bgcolor="#ffffff" style="border: none;"></th></tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                    A. INDIVIDUAL PARTNER STATEMENT OF ACCOUNT (RUNNING LEDGER)
                </th>
            </tr>
            <tr height="35" style="height: 35pt;">
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">TRANSACTION DATE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">VOUCHER / REF NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">DESCRIPTION / TRANSACTION TYPE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PROFIT SHARE ALLOCATED (CREDIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">RUNNING PAYABLE BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ (is_object($entry) ? $entry->description : ($entry['description'] ?? '')) . (!empty(is_object($entry) ? ($entry->payment_mode ?? '') : ($entry['payment_mode'] ?? '')) ? ' ('.(is_object($entry) ? $entry->payment_mode : $entry['payment_mode']).')' : '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL LEDGER BALANCE</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($runningBalance ?? 0) }}</td>
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
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">ROLE / ENTITY TYPE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">AGREED SHARE (%)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">TOTAL ALLOCATED NET PROFIT</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">TOTAL PAYOUTS RELEASED</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">CURRENT NET BALANCE OWED</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrixPartners as $pIdx => $pRow)
                <tr height="25" style="height: 25pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $pIdx + 1 }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->name : ($pRow['name'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($pRow) ? $pRow->role : ($pRow['role'] ?? '') }}</td>
                    <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)(is_object($pRow) ? $pRow->share_pct : ($pRow['share_pct'] ?? 0)), 2) }}%</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->total_allocated : ($pRow['total_allocated'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->total_payouts : ($pRow['total_payouts'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #17365D; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($pRow) ? $pRow->net_balance : ($pRow['net_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="3" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">PROJECT TOTALS</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '0.00%';">{{ number_format((float)($totalMatrixAgreedPct ?? 0), 2) }}%</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalMatrixAllocated ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalMatrixPayouts ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(($totalMatrixAllocated ?? 0) - ($totalMatrixPayouts ?? 0)) }}</td>
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
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">DATE</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PARTNER NAME</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">REF / VOUCHER NO</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">DESCRIPTION</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">ALLOCATED PROFIT (CREDIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">PAYOUT RELEASED (DEBIT)</th>
                <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">RUNNING BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($runningLedger as $index => $entry)
                <tr height="24" style="height: 24pt;">
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ is_object($entry) ? $entry->date : ($entry['date'] ?? '') }}</td>
                    <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->partner_name : ($entry['partner_name'] ?? '') }}</td>
                    <td style="text-align: center; font-family: monospace; vertical-align: middle; border: 1px solid #cbd5e1;">{{ is_object($entry) ? $entry->ref_no : ($entry['ref_no'] ?? '') }}</td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ (is_object($entry) ? $entry->description : ($entry['description'] ?? '')) . (!empty(is_object($entry) ? ($entry->payment_mode ?? '') : ($entry['payment_mode'] ?? '')) ? ' ('.(is_object($entry) ? $entry->payment_mode : $entry['payment_mode']).')' : '') }}</td>
                    <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->credit : ($entry['credit'] ?? 0)) }}</td>
                    <td style="text-align: right; color: #e11d48; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->debit : ($entry['debit'] ?? 0)) }}</td>
                    <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)(is_object($entry) ? $entry->running_balance : ($entry['running_balance'] ?? 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr height="28" style="height: 28pt; background-color: #ffffff;">
                <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL DISTRIBUTION LOG SUMMARY</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalCredit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #e11d48; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalDebit ?? 0) }}</td>
                <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #17365D; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($runningBalance ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>

</div>

@include('reports.partials.script')

</x-erp-layout>
