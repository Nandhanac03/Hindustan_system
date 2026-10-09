<x-erp-layout title="Trial Balance Workspace" headerTitle="Accounting & Financial Reports">

<div class="w-full space-y-6" x-data="trialBalanceApp()">

    <!-- ── 1. HEADER & BREADCRUMBS ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span class="text-slate-500 uppercase">ACCOUNTING & FINANCE</span>
                <span>›</span>
                <span class="text-[#a38c29] font-black uppercase tracking-wider">TRIAL BALANCE</span>
            </nav>
            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <div class="p-2 bg-amber-50 rounded-xl text-[#a38c29] border border-amber-200/80 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <span>Trial Balance</span>
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                View consolidated trial balance for the selected period and project.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-center">
            <span class="px-3.5 py-2 rounded-xl text-xs font-black bg-amber-50 text-[#8a7522] border border-amber-200/90 shadow-2xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#a38c29] animate-pulse"></span>
                <span>Audited Ledger Verification</span>
            </span>

            <button type="button" onclick="exportTrialBalanceToExcel()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow-sm transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export to Excel</span>
            </button>

            <button type="button" onclick="window.print()" class="px-4 py-2 bg-white hover:bg-rose-50 border border-rose-200 text-rose-700 hover:text-rose-800 rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow-2xs transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Download PDF</span>
            </button>
        </div>
    </div>

    <!-- ── 2. ULTRA-CLEAN MODERN SEARCH & FILTER PANEL (MATCHING COMMON ERP THEME) ── -->
    <form id="trialBalanceForm" action="{{ route('reports.trial_balance') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 flex-1">
                
                {{-- 1. Search Account Code / Name (Instant in-memory filtering) --}}
                <div class="relative group col-span-1 sm:col-span-2 lg:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Code, Account..." x-model="searchQuery" autocomplete="off"
                           class="w-full erp-search-input pl-10 pr-9">
                    <div x-show="searchQuery" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                        <button type="button" @click="searchQuery = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 2. Project Filter (Custom Gold Popover with Search, Default First) --}}
                <div class="relative w-full" @click.outside="projectDropdownOpen = false">
                    <input type="hidden" name="project_id" :value="selectedProjectId">
                    <button type="button"
                            @click="projectDropdownOpen = !projectDropdownOpen; if(projectDropdownOpen) { $nextTick(() => $refs.projSearchInput?.focus()); }"
                            class="erp-dropdown-trigger"
                            :class="projectDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="selectedProjectId && selectedProjectId !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="selectedProjectName">All Projects</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="selectedProjectId && selectedProjectId !== 'all'">
                                <span @click.stop="selectProject('all', 'All Projects')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="projectDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="projectDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover min-w-[240px]" style="display: none;">
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="projectSearch" x-ref="projSearchInput" placeholder="Search project..."
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="projectDropdownOpen = false">
                                <template x-if="projectSearch">
                                    <button type="button" @click="projectSearch = ''; $refs.projSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-56">
                            <div @click="selectProject('all', 'All Projects')" 
                                 x-show="!projectSearch || 'All Projects'.toLowerCase().includes(projectSearch.toLowerCase())"
                                 class="erp-dropdown-option" :class="selectedProjectId === 'all' ? 'selected-all' : ''">
                                <span>All Projects</span>
                            </div>
                            @foreach($allProjects as $proj)
                                <div @click="selectProject('{{ $proj->id }}', '{{ addslashes($proj->name) }}')"
                                     x-show="!projectSearch || '{{ strtolower(addslashes($proj->name)) }}'.includes(projectSearch.toLowerCase())"
                                     class="erp-dropdown-option" :class="String(selectedProjectId) === '{{ (string)$proj->id }}' ? 'selected' : ''">
                                    <span class="truncate">{{ $proj->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 3. Period Type Dropdown --}}
                <div class="relative w-full" @click.outside="periodDropdownOpen = false">
                    <input type="hidden" name="period_type" :value="periodType">
                    <button type="button" @click="periodDropdownOpen = !periodDropdownOpen" class="erp-dropdown-trigger" :class="periodDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="periodTypeLabel">Financial Year</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="periodDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="periodDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectPeriodType('fy', 'Financial Year')" class="erp-dropdown-option" :class="periodType === 'fy' ? 'selected-all' : ''">
                                <span>Financial Year</span>
                            </div>
                            <div @click="selectPeriodType('quarter', 'Quarter')" class="erp-dropdown-option" :class="periodType === 'quarter' ? 'selected' : ''">
                                <span>Quarter</span>
                            </div>
                            <div @click="selectPeriodType('month', 'Month')" class="erp-dropdown-option" :class="periodType === 'month' ? 'selected' : ''">
                                <span>Month</span>
                            </div>
                            <div @click="selectPeriodType('custom', 'Custom Date Range')" class="erp-dropdown-option" :class="periodType === 'custom' ? 'selected' : ''">
                                <span>Custom Date Range</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. From Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="from_date" id="fromDateInput" x-model="fromDate" @change="submitPeriodDates()"
                           title="From Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 5. To Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="to_date" id="toDateInput" x-model="toDate" @change="submitPeriodDates()"
                           title="To Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 6. Report Level / View Mode (Instant In-Memory Toggle) --}}
                <div class="relative w-full" @click.outside="viewModeDropdownOpen = false">
                    <input type="hidden" name="view_mode" :value="viewMode">
                    <button type="button" @click="viewModeDropdownOpen = !viewModeDropdownOpen" class="erp-dropdown-trigger" :class="viewModeDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="viewMode === 'detailed' ? 'Detailed View' : 'Summary View'">Detailed View</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="viewModeDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="viewModeDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectViewMode('detailed')" class="erp-dropdown-option" :class="viewMode === 'detailed' ? 'selected-all' : ''">
                                <span>Detailed View</span>
                            </div>
                            <div @click="selectViewMode('summary')" class="erp-dropdown-option" :class="viewMode === 'summary' ? 'selected' : ''">
                                <span>Summary View</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- 7. Hide Zero Balance Checkbox (Instant In-Memory Toggle) --}}
                <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200/90 hover:border-[#a38c29] h-[38px] transition cursor-pointer select-none">
                    <input type="checkbox" x-model="hideZero" class="w-4 h-4 rounded text-[#a38c29] border-slate-300 focus:ring-[#a38c29] cursor-pointer">
                    <span class="text-xs font-bold text-slate-700 whitespace-nowrap">Hide Zero</span>
                </label>

                {{-- 8. Signature Gold RESET FILTERS Button --}}
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8C7A2E] hover:bg-[#786826] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>
    </form>

    <!-- ── 3. AUDIT EQUALITY & BALANCE BANNER ── -->
    @if($isBalanced)
    <div class="rounded-2xl bg-emerald-50/90 border-2 border-emerald-500/80 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20 text-xl font-bold">
                ✓
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-emerald-950 uppercase tracking-wide flex items-center gap-2">
                    <span>TRIAL BALANCE EQUAL & BALANCED</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-200/80 text-emerald-900">Audited</span>
                </h3>
                <p class="text-xs text-emerald-800 font-medium mt-0.5">
                    Total debits and credits are equal (<strong class="font-mono font-bold">₹ {{ number_format($grandTotalDebit, 2) }}</strong>) for the selected period.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-emerald-200 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Financial Period</span>
            <span class="text-xs font-mono font-black text-slate-800">{{ $financialYearLabel }}</span>
        </div>
    </div>
    @else
    <div class="rounded-2xl bg-rose-50 border-2 border-rose-500 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20 text-xl font-bold">
                !
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-rose-950 uppercase tracking-wide">
                    TRIAL BALANCE VARIANCE DETECTED
                </h3>
                <p class="text-xs text-rose-800 font-medium mt-0.5">
                    Total debits (₹ {{ number_format($grandTotalDebit, 2) }}) and credits (₹ {{ number_format($grandTotalCredit, 2) }}) differ by ₹ {{ number_format($diff, 2) }}.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-rose-200 shadow-2xs">
            <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider block">Variance Amount</span>
            <span class="text-sm font-mono font-black text-rose-700">₹ {{ number_format($diff, 2) }}</span>
        </div>
    </div>
    @endif

    <!-- ── 4. TRIAL BALANCE DATA GRID (BRAND GOLD HEADER THEME) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="trialBalanceTable" class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="erp-table-header bg-[#17365D] text-white border-b-2 border-slate-700 text-[10.5px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 w-36 text-white font-extrabold tracking-wider">ACCOUNT CODE</th>
                        <th class="px-5 py-3.5 text-white font-extrabold tracking-wider">ACCOUNT NAME / GROUP</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">OPENING BALANCE (₹)</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">PERIOD DEBIT (₹)</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">PERIOD CREDIT (₹)</th>
                        <th class="px-5 py-3.5 text-right w-48 text-white font-extrabold tracking-wider">CLOSING BALANCE (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    
                    @foreach($groupsData as $grpKey => $grp)
                        @php
                            $hasAccounts = !empty($grp['accounts']);
                        @endphp

                        <!-- Group Header Row (Rich Gold/Amber Themed) -->
                        <tr class="bg-amber-50/60 hover:bg-amber-100/60 transition-colors font-bold border-t-2 border-b border-amber-200/70 cursor-pointer select-none"
                            x-show="isGroupVisible('{{ $grp['code'] }}')"
                            @click="toggleGroup('{{ $grp['code'] }}')">
                            
                            <td class="px-5 py-3 font-mono font-black text-slate-900 text-xs">
                                <div class="flex items-center gap-2">
                                    <template x-if="viewMode === 'detailed' && {{ $hasAccounts ? 'true' : 'false' }}">
                                        <div class="w-5 h-5 rounded-md bg-white border border-amber-300 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-3 h-3 transition-transform duration-200"
                                                 :class="isGroupCollapsed('{{ $grp['code'] }}') ? '-rotate-90' : 'rotate-0'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </template>
                                    <span class="px-2.5 py-0.5 bg-[#a38c29] text-white rounded-md text-[11px] font-mono font-black shadow-2xs">
                                        {{ $grp['code'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-3 font-black text-slate-900 uppercase tracking-wide text-xs">
                                <div class="flex items-center gap-2">
                                    <span>{{ $grp['name'] }}</span>
                                    @if($hasAccounts)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-white border border-amber-200 text-[#8a7522] font-extrabold lowercase">
                                            {{ count($grp['accounts']) }} accounts
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['opening_balance'], 2) }} <span class="font-extrabold {{ ($grp['opening_side'] ?? 'Dr') === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">{{ $grp['opening_side'] ?? 'Dr' }}</span>
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_debit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_credit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-xs {{ $grp['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                ₹{{ number_format($grp['closing_balance'], 2) }} <span class="font-extrabold">{{ $grp['closing_side'] }}</span>
                            </td>
                        </tr>

                        <!-- Sub-Accounts (Detailed View) -->
                        @if($hasAccounts)
                            @foreach($grp['accounts'] as $acc)
                            <tr x-show="viewMode === 'detailed' && !isGroupCollapsed('{{ $grp['code'] }}') && isAccountVisible({{ $acc['is_zero'] ? 'true' : 'false' }}, '{{ $acc['code'] }}', '{{ addslashes($acc['name']) }}')"
                                class="hover:bg-amber-50/30 transition-colors bg-white">
                                
                                <td class="px-5 py-2.5 font-mono text-slate-500 pl-12 text-[11px] font-bold">
                                    {{ $acc['code'] }}
                                </td>

                                <td class="px-5 py-2.5">
                                    <a href="{{ route('vouchers.ledger.index', ['account_id' => $acc['id']]) }}" 
                                       class="font-semibold text-slate-800 hover:text-[#a38c29] hover:underline transition flex items-center gap-1.5 group"
                                       title="Click to view detailed general ledger">
                                        <span>{{ $acc['name'] }}</span>
                                        <svg class="w-3 h-3 text-slate-300 group-hover:text-[#a38c29] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-slate-700 text-xs">
                                    {{ number_format(abs($acc['opening_balance']), 2) }}@if($acc['opening_balance'] != 0) <span class="font-extrabold {{ $acc['opening_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">{{ $acc['opening_side'] }}</span>@endif
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-indigo-700 text-xs">
                                    {{ $acc['period_debit'] > 0 ? '₹'.number_format($acc['period_debit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-amber-700 text-xs">
                                    {{ $acc['period_credit'] > 0 ? '₹'.number_format($acc['period_credit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-black text-xs {{ $acc['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                    @if($acc['closing_balance'] > 0)
                                        ₹{{ number_format($acc['closing_balance'], 2) }} <span class="font-extrabold">{{ $acc['closing_side'] }}</span>
                                    @else
                                        0.00
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @endif

                    @endforeach

                </tbody>
                <tfoot>
                    <tr class="bg-white text-slate-900 font-black text-xs uppercase border-t-2 border-b-2 border-[#a38c29] shadow-2xs">
                        <td class="px-5 py-4 font-black tracking-wider text-slate-900 text-xs" colspan="2">
                            TOTAL
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalOpening, 2) }} <span class="font-extrabold">{{ $grandTotalOpeningSide ?? '' }}</span>
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalDebit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalCredit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-400 text-xs">
                            —
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- ── SCRIPTS FOR PERIOD PRESETS, ALPINE APP & EXCEL EXPORT ── -->
<script>
function trialBalanceApp() {
    return {
        collapsedGroups: {},
        searchQuery: '',
        viewMode: '{{ $viewMode }}',
        hideZero: {{ $hideZero ? 'true' : 'false' }},
        selectedProjectId: '{{ (string)$selectedProjectId }}',
        selectedProjectName: '{{ (string)$selectedProjectId === 'all' ? 'All Projects' : addslashes($allProjects->firstWhere('id', $selectedProjectId)?->name ?? 'Select Project') }}',
        projectDropdownOpen: false,
        projectSearch: '',
        periodType: '{{ $periodType }}',
        periodTypeLabel: '{{ $periodType === 'fy' ? 'Financial Year' : ($periodType === 'quarter' ? 'Quarter' : ($periodType === 'month' ? 'Month' : 'Custom Date Range')) }}',
        periodDropdownOpen: false,
        viewModeDropdownOpen: false,
        fromDate: '{{ $fromDate }}',
        toDate: '{{ $toDate }}',
        groups: @json(array_values($groupsData)),

        toggleGroup(code) {
            this.collapsedGroups[code] = !this.collapsedGroups[code];
        },
        isGroupCollapsed(code) {
            return !!this.collapsedGroups[code];
        },
        selectProject(id, name) {
            this.selectedProjectId = id;
            this.selectedProjectName = name;
            this.projectDropdownOpen = false;
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        selectPeriodType(type, label) {
            this.periodType = type;
            this.periodTypeLabel = label;
            this.periodDropdownOpen = false;
            const today = new Date();
            if (type === 'fy') {
                const fyStart = today.getMonth() >= 3 ? today.getFullYear() : today.getFullYear() - 1;
                this.fromDate = fyStart + '-04-01';
                this.toDate = (fyStart + 1) + '-03-31';
            } else if (type === 'quarter') {
                const curMonth = today.getMonth();
                const qStartMonth = Math.floor(curMonth / 3) * 3;
                const qStart = new Date(today.getFullYear(), qStartMonth, 1);
                const qEnd = new Date(today.getFullYear(), qStartMonth + 3, 0);
                this.fromDate = this.formatDateYmd(qStart);
                this.toDate = this.formatDateYmd(qEnd);
            } else if (type === 'month') {
                const mStart = new Date(today.getFullYear(), today.getMonth(), 1);
                const mEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                this.fromDate = this.formatDateYmd(mStart);
                this.toDate = this.formatDateYmd(mEnd);
            }
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        submitPeriodDates() {
            this.periodType = 'custom';
            this.periodTypeLabel = 'Custom Date Range';
            $nextTick(() => { document.getElementById('trialBalanceForm').submit(); });
        },
        formatDateYmd(d) {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        selectViewMode(mode) {
            this.viewMode = mode;
            this.viewModeDropdownOpen = false;
        },
        isAccountVisible(isZero, code, name) {
            if (this.hideZero && isZero) return false;
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase().trim();
                const matchCode = String(code).toLowerCase().includes(q);
                const matchName = String(name).toLowerCase().includes(q);
                if (!matchCode && !matchName) return false;
            }
            return true;
        },
        isGroupVisible(grpCode) {
            const grp = this.groups.find(g => String(g.code) === String(grpCode));
            if (!grp) return true;
            if (!this.searchQuery && !this.hideZero) return true;
            const matchingAccounts = (grp.accounts || []).filter(a => this.isAccountVisible(a.is_zero, a.code, a.name));
            if (matchingAccounts.length > 0) return true;
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase().trim();
                if (String(grp.code).toLowerCase().includes(q) || String(grp.name).toLowerCase().includes(q)) {
                    return true;
                }
            }
            return !this.hideZero && !this.searchQuery;
        },
        resetFilters() {
            this.searchQuery = '';
            this.viewMode = 'detailed';
            this.hideZero = false;
            this.projectDropdownOpen = false;
            this.periodDropdownOpen = false;
            this.viewModeDropdownOpen = false;
            this.projectSearch = '';
        }
    };
}

function exportTrialBalanceToExcel() {
    let table = document.getElementById('trialBalanceTable');
    if (!table) return;

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Trial Balance Summary Report\n";
    csvContent += "Financial Period: {{ $financialYearLabel }}\n\n";

    let rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/,/g, '').replace(/₹/g, 'Rs. ').replace(/\n/g, ' ').trim();
            rowData.push('"' + text + '"');
        });
        if (rowData.length > 0) {
            csvContent += rowData.join(",") + "\n";
        }
    });

    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Trial_Balance_{{ date('Ymd_His') }}.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
@media print {
    aside, header, nav, button, form, .no-print { display: none !important; }
    body { background-color: #ffffff !important; color: #000000 !important; font-size: 10pt; }
    .shadow-sm, .shadow-2xs, .shadow-xs { box-shadow: none !important; }
    .border { border-color: #cbd5e1 !important; }
}
</style>

</x-erp-layout>
