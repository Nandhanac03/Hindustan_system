<x-erp-layout title="Profit & Loss (P&L) Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="pnlReportApp()">

    {{-- TOP HEADER BAR --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profit & Loss (P&L) Statement</h1>
            <p class="text-xs text-slate-500 mt-1">Track your company's income, expenses and profitability for the selected period.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Action Button 1: P&L Statement PDF/Excel --}}
            <button @click="printReport()" class="flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-amber-50/50 border border-[#E6DEC9] rounded-xl shadow-2xs transition group text-left cursor-pointer hover:-translate-y-0.5 hover:shadow-xs">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[#8C6D27] group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-[#644D17] leading-snug">P&L Statement</div>
                    <div class="text-[10px] text-slate-400 font-medium">PDF / Excel</div>
                </div>
            </button>

            {{-- Action Button 2: P&L Summary PDF/Excel --}}
            <button @click="exportExcel()" class="flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-amber-50/50 border border-[#E6DEC9] rounded-xl shadow-2xs transition group text-left cursor-pointer hover:-translate-y-0.5 hover:shadow-xs">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[#8C6D27] group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-[#644D17] leading-snug">P&L Summary</div>
                    <div class="text-[10px] text-slate-400 font-medium">PDF / Excel</div>
                </div>
            </button>

            {{-- Action Button 3: P&L Detailed Report PDF/Excel --}}
            <button @click="printReport()" class="flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-amber-50/50 border border-[#E6DEC9] rounded-xl shadow-2xs transition group text-left cursor-pointer hover:-translate-y-0.5 hover:shadow-xs">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[#8C6D27] group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 002-2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-[#644D17] leading-snug">P&L Detailed Report</div>
                    <div class="text-[10px] text-slate-400 font-medium">PDF / Excel</div>
                </div>
            </button>
        </div>
    </div>

    @php
        $defaultProjectId = request('project_id', 'all');
        $selectedProj = $projects->firstWhere('id', $defaultProjectId);
        $selectedProjName = $selectedProj ? $selectedProj->name : 'All Projects';
    @endphp

    <!-- ── 2. ULTRA-CLEAN MODERN SEARCH & FILTER PANEL (ERP GLOBAL DESIGN SYSTEM) ── -->
    <form id="pnlFilterForm" action="{{ route('reports.profit_loss') }}" method="GET" @submit.prevent="updateFilters()" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 flex-1">
                
                {{-- 1. Search Particulars / Accounts --}}
                <div class="relative group col-span-1 sm:col-span-2 md:col-span-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search particulars..." x-model="searchQuery" autocomplete="off"
                           class="w-full erp-search-input pl-10 pr-9">
                    <div x-show="searchQuery" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                        <button type="button" @click="searchQuery = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- 2. Project Filter (Custom Gold Popover with Search) --}}
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
                                  :class="selectedProjectId && selectedProjectId !== 'all' ? 'text-slate-900 font-extrabold' : 'text-slate-900 font-bold'"
                                  x-text="selectedProjectName">{{ $selectedProjName }}</span>
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
                                 class="erp-dropdown-option" :class="selectedProjectId === 'all' || !selectedProjectId ? 'selected-all' : ''">
                                <span>All Projects</span>
                            </div>
                            @foreach($projects as $proj)
                                <div @click="selectProject('{{ $proj->id }}', '{{ addslashes($proj->name) }}')"
                                     x-show="!projectSearch || '{{ strtolower(addslashes($proj->name)) }}'.includes(projectSearch.toLowerCase())"
                                     class="erp-dropdown-option" :class="String(selectedProjectId) === '{{ (string)$proj->id }}' ? 'selected' : ''">
                                    <span class="truncate">{{ $proj->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 3. Financial Year Dropdown --}}
                <div class="relative w-full" @click.outside="fyDropdownOpen = false">
                    <input type="hidden" name="financial_year" :value="financialYear">
                    <button type="button" @click="fyDropdownOpen = !fyDropdownOpen" class="erp-dropdown-trigger" :class="fyDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="financialYear">FY 2026-27</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="fyDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="fyDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectFinancialYear('FY 2026-27')" class="erp-dropdown-option" :class="financialYear === 'FY 2026-27' ? 'selected-all' : ''">
                                <span>FY 2026-27</span>
                            </div>
                            <div @click="selectFinancialYear('FY 2025-26')" class="erp-dropdown-option" :class="financialYear === 'FY 2025-26' ? 'selected' : ''">
                                <span>FY 2025-26</span>
                            </div>
                            <div @click="selectFinancialYear('FY 2024-25')" class="erp-dropdown-option" :class="financialYear === 'FY 2024-25' ? 'selected' : ''">
                                <span>FY 2024-25</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. From Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="date_from" id="dateFromInput" x-model="dateFrom" @change="updateFilters()"
                           title="From Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 5. To Date --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="date" name="date_to" id="dateToInput" x-model="dateTo" @change="updateFilters()"
                           title="To Date"
                           class="w-full erp-input erp-date-input">
                </div>

                {{-- 6. Cost Center --}}
                <div class="relative w-full" @click.outside="costCenterDropdownOpen = false">
                    <input type="hidden" name="cost_center" :value="costCenter">
                    <button type="button" @click="costCenterDropdownOpen = !costCenterDropdownOpen" class="erp-dropdown-trigger" :class="costCenterDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="costCenter">All Cost Centers</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="costCenterDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="costCenterDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectCostCenter('All Cost Centers')" class="erp-dropdown-option" :class="costCenter === 'All Cost Centers' ? 'selected-all' : ''">
                                <span>All Cost Centers</span>
                            </div>
                            <div @click="selectCostCenter('Head Office')" class="erp-dropdown-option" :class="costCenter === 'Head Office' ? 'selected' : ''">
                                <span>Head Office</span>
                            </div>
                            <div @click="selectCostCenter('Site Construction')" class="erp-dropdown-option" :class="costCenter === 'Site Construction' ? 'selected' : ''">
                                <span>Site Construction</span>
                            </div>
                            <div @click="selectCostCenter('Marketing & Sales')" class="erp-dropdown-option" :class="costCenter === 'Marketing & Sales' ? 'selected' : ''">
                                <span>Marketing & Sales</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- 7. Report Level (Detailed/Summary) --}}
                <div class="relative min-w-[140px]" @click.outside="reportLevelDropdownOpen = false">
                    <input type="hidden" name="report_level" :value="reportLevel">
                    <button type="button" @click="reportLevelDropdownOpen = !reportLevelDropdownOpen" class="erp-dropdown-trigger" :class="reportLevelDropdownOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span class="truncate text-xs font-bold text-slate-900" x-text="reportLevel">Summary</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="reportLevelDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="reportLevelDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover w-full" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectReportLevel('Summary')" class="erp-dropdown-option" :class="reportLevel === 'Summary' ? 'selected-all' : ''">
                                <span>Summary</span>
                            </div>
                            <div @click="selectReportLevel('Detailed')" class="erp-dropdown-option" :class="reportLevel === 'Detailed' ? 'selected' : ''">
                                <span>Detailed</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 8. Signature Gold RESET FILTERS Button --}}
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8C7A2E] hover:bg-[#786826] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer"
                        title="Reset Filters">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>
    </form>

    {{-- KPI METRICS CARDS GRID (SALES RETURN CARD DESIGN MATCH) --}}
    <div id="pnlKpisContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        {{-- Card 1: Total Income (Gold Theme) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Income</span>
                </div>
                <span class="text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                    {{ request('financial_year', 'FY 2026-27') }}
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-[#385B17] font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['total_income']['curr'] ?? 124580000, 0) }}
                </span>
                <p class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>↑ {{ $pnlData['kpis']['total_income']['var'] ?? '18.42' }}% vs Last Year</span>
                </p>
            </div>
        </div>

        {{-- Card 2: Total Expenses (Emerald Theme) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Expenses</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                    OUTFLOWS
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['total_expenses']['curr'] ?? 81560000, 0) }}
                </span>
                <p class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>↑ {{ $pnlData['kpis']['total_expenses']['var'] ?? '12.08' }}% vs Last Year</span>
                </p>
            </div>
        </div>

        {{-- Card 3: Gross Profit (Purple Theme) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-purple-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-purple-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(147,51,234,0.15)] cursor-default">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100 transition-all duration-300 group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Gross Profit</span>
                </div>
                <span class="text-[9px] text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-purple-300 group-hover:bg-purple-100/60">
                    MARGIN
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-purple-600 font-mono tracking-tight block group-hover:text-purple-700 transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['gross_profit']['curr'] ?? 43020000, 0) }}
                </span>
                <p class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>↑ {{ $pnlData['kpis']['gross_profit']['var'] ?? '28.35' }}% vs Last Year</span>
                </p>
            </div>
        </div>

        {{-- Card 4: Net Profit (Amber/Orange Theme) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-default">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Net Profit</span>
                </div>
                <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                    NET RESULT
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['net_profit']['curr'] ?? 28575000, 0) }}
                </span>
                <p class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>↑ {{ $pnlData['kpis']['net_profit']['var'] ?? '32.71' }}% vs Last Year</span>
                </p>
            </div>
        </div>

        {{-- Card 5: Net Profit Margin (Slate Theme) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-slate-700 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-slate-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(51,65,85,0.15)] cursor-default">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 border border-slate-200 transition-all duration-300 group-hover:bg-slate-700 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 14l6-6m-5.5.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm7 7a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Net Profit Margin</span>
                </div>
                <span class="text-[9px] text-slate-700 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-slate-300 group-hover:bg-slate-100">
                    RATIO
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-slate-900 transition-colors duration-300">
                    {{ $pnlData['kpis']['net_margin']['curr'] ?? '22.94' }}%
                </span>
                <p class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>↑ {{ $pnlData['kpis']['net_margin']['var'] ?? '2.31' }}% vs Last Year</span>
                </p>
            </div>
        </div>
    </div>

    {{-- MAIN P&L STATEMENT TABLE CONTAINER (MATCHING SALES RETURN THEME & TABLE STRUCTURE) --}}
    <div id="pnlTableContainer" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col w-full">
        <div class="overflow-x-auto flex-1">
            <table id="pnlStatementTable" class="w-full text-left border-collapse">
                {{-- TABLE HEADER (EXACT STANDARD ERP THEME) --}}
                <thead class="erp-table-header bg-[#17365D] border-b border-slate-700 font-black text-white uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5 text-left w-2/5">Particulars</th>
                        <th class="px-5 py-3.5 text-right w-1/5">
                            <div>{{ $pnlData['labels']['curr'] ?? 'FY 2026-27 (01/04/2026 - 31/03/2027)' }}</div>
                        </th>
                        <th class="px-5 py-3.5 text-right w-1/5">
                            <div>{{ $pnlData['labels']['prior'] ?? 'FY 2025-26 (01/04/2025 - 31/03/2026)' }}</div>
                        </th>
                        <th class="px-5 py-3.5 text-right w-1/5">Variance (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-705">

                    {{-- SECTION A: INCOME BANNER --}}
                    <tr class="bg-[#EEF7EE] hover:bg-[#e4f3e4] cursor-pointer transition" @click="showIncome = !showIncome">
                        <td colspan="4" class="py-3 px-6 font-extrabold text-[#1E4D2B]">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] transition-transform duration-200" :class="{ '-rotate-90': !showIncome }">▼</span>
                                <span>A. INCOME</span>
                            </div>
                        </td>
                    </tr>

                    {{-- SECTION A SUB-ROWS --}}
                    <template x-if="showIncome">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">1. Revenue from Operations</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['income']['items'][0]['curr'] ?? 118560000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['income']['items'][0]['prior'] ?? 100230000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">
                                {{ $pnlData['income']['items'][0]['var'] ?? '18.27' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showIncome">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">2. Other Income</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['income']['items'][1]['curr'] ?? 6020000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['income']['items'][1]['prior'] ?? 4510000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-emerald-600">
                                {{ $pnlData['income']['items'][1]['var'] ?? '33.44' }}%
                            </td>
                        </tr>
                    </template>

                    {{-- TOTAL INCOME (A) ROW --}}
                    <tr class="bg-emerald-50/40 border-t border-b border-emerald-100 font-bold">
                        <td class="py-3.5 px-6 pl-6 text-[#1E4D2B]">Total Income (A)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['income']['total_curr'] ?? 124580000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['income']['total_prior'] ?? 104740000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-emerald-600">
                            {{ $pnlData['income']['total_var'] ?? '18.95' }}%
                        </td>
                    </tr>

                    {{-- SECTION B: EXPENSES BANNER --}}
                    <tr class="bg-[#FCF0F0] hover:bg-[#fae6e6] cursor-pointer transition" @click="showExpenses = !showExpenses">
                        <td colspan="4" class="py-3 px-6 font-extrabold text-[#881337]">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] transition-transform duration-200" :class="{ '-rotate-90': !showExpenses }">▼</span>
                                <span>B. EXPENSES</span>
                            </div>
                        </td>
                    </tr>

                    {{-- SECTION B SUB-ROWS --}}
                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">1. Cost of Sales / Direct Costs</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][0]['curr'] ?? 62030000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][0]['prior'] ?? 50820000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][0]['var'] ?? '22.05' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">2. Employee Benefits Expense</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][1]['curr'] ?? 8540000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][1]['prior'] ?? 7230000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][1]['var'] ?? '18.13' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">3. Administrative & Office Expenses</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][2]['curr'] ?? 4560000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][2]['prior'] ?? 3870000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][2]['var'] ?? '17.83' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">4. Selling & Marketing Expenses</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][3]['curr'] ?? 2870000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][3]['prior'] ?? 2410000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][3]['var'] ?? '19.09' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">5. Finance Costs</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][4]['curr'] ?? 1230000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][4]['prior'] ?? 980000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][4]['var'] ?? '25.51' }}%
                            </td>
                        </tr>
                    </template>

                    <template x-if="showExpenses">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">6. Depreciation & Amortization</td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($pnlData['expenses']['items'][5]['curr'] ?? 2310000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-600">
                                {{ number_format($pnlData['expenses']['items'][5]['prior'] ?? 2050000, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-bold text-rose-600">
                                {{ $pnlData['expenses']['items'][5]['var'] ?? '12.68' }}%
                            </td>
                        </tr>
                    </template>

                    {{-- TOTAL EXPENSES (B) ROW --}}
                    <tr class="bg-rose-50/40 border-t border-b border-rose-100 font-bold">
                        <td class="py-3.5 px-6 pl-6 text-[#991B1B]">Total Expenses (B)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#DC2626]">
                            {{ number_format($pnlData['expenses']['total_curr'] ?? 81560000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#DC2626]">
                            {{ number_format($pnlData['expenses']['total_prior'] ?? 69390000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-rose-600">
                            {{ $pnlData['expenses']['total_var'] ?? '17.55' }}%
                        </td>
                    </tr>

                    {{-- ROW C: PROFIT BEFORE TAX (A - B) --}}
                    <tr class="bg-emerald-50/30 hover:bg-emerald-50/60 font-extrabold border-b border-slate-100 transition">
                        <td class="py-3.5 px-6 text-[#1E4D2B]">C. Profit Before Tax (A - B)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['profit_before_tax']['curr'] ?? 43020000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['profit_before_tax']['prior'] ?? 35350000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-emerald-600">
                            {{ $pnlData['profit_before_tax']['var'] ?? '21.69' }}%
                        </td>
                    </tr>

                    {{-- ROW D: TAX EXPENSE --}}
                    <tr class="hover:bg-slate-50/80 font-medium border-b border-slate-100 transition">
                        <td class="py-3.5 px-6 text-slate-800">D. Tax Expense</td>
                        <td class="py-3.5 px-6 text-right font-mono text-slate-800">
                            {{ number_format($pnlData['tax_expense']['curr'] ?? 14445000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-slate-600">
                            {{ number_format($pnlData['tax_expense']['prior'] ?? 11880000, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono font-bold text-emerald-600">
                            {{ $pnlData['tax_expense']['var'] ?? '21.59' }}%
                        </td>
                    </tr>

                    {{-- ROW E: NET PROFIT AFTER TAX (C - D) --}}
                    <tr class="bg-emerald-100/40 hover:bg-emerald-100/60 font-extrabold text-sm border-t-2 border-emerald-200 transition">
                        <td class="py-4 px-6 text-[#1E4D2B]">E. Net Profit After Tax (C - D)</td>
                        <td class="py-4 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['net_profit_after_tax']['curr'] ?? 28575000, 0) }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['net_profit_after_tax']['prior'] ?? 23470000, 0) }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-emerald-600">
                            {{ $pnlData['net_profit_after_tax']['var'] ?? '21.76' }}%
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER (MATCHING SALES RETURN EXACT FOOTER UI) --}}
        <div class="px-5 py-3 border-t border-slate-200/80 bg-slate-50/60 flex items-center justify-between">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900 font-extrabold">1</span> TO 
                <span class="text-slate-900 font-extrabold">10</span> OF 
                <span class="text-slate-900 font-extrabold">10</span> ENTRIES
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" disabled
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider disabled:opacity-60 disabled:cursor-not-allowed shadow-2xs">
                    PREV
                </button>
                
                <button type="button"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-[#8C7A2E] text-white border border-[#8C7A2E] shadow-2xs">
                    1
                </button>
                
                <button type="button" disabled
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider disabled:opacity-60 disabled:cursor-not-allowed shadow-2xs">
                    NEXT
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function pnlReportApp() {
    return {
        searchQuery: '',
        financialYear: '{{ request('financial_year', 'FY 2026-27') }}',
        fyDropdownOpen: false,
        dateFrom: '{{ request('date_from', '2026-04-01') }}',
        dateTo: '{{ request('date_to', '2027-03-31') }}',
        selectedProjectId: '{{ $defaultProjectId }}',
        selectedProjectName: '{{ addslashes($selectedProjName) }}',
        projectDropdownOpen: false,
        projectSearch: '',
        costCenter: '{{ request('cost_center', 'All Cost Centers') }}',
        costCenterDropdownOpen: false,
        reportLevel: '{{ request('report_level', 'Summary') }}',
        reportLevelDropdownOpen: false,
        showIncome: true,
        showExpenses: true,
        perPage: '10',
        currentPage: 1,

        selectFinancialYear(val) {
            this.financialYear = val;
            this.fyDropdownOpen = false;
            if (val === 'FY 2026-27') {
                this.dateFrom = '2026-04-01';
                this.dateTo = '2027-03-31';
            } else if (val === 'FY 2025-26') {
                this.dateFrom = '2025-04-01';
                this.dateTo = '2026-03-31';
            } else if (val === 'FY 2024-25') {
                this.dateFrom = '2024-04-01';
                this.dateTo = '2025-03-31';
            }
            this.$nextTick(() => { this.updateFilters(); });
        },

        selectProject(id, name) {
            this.selectedProjectId = id;
            this.selectedProjectName = name;
            this.projectDropdownOpen = false;
            this.$nextTick(() => { this.updateFilters(); });
        },

        selectCostCenter(val) {
            this.costCenter = val;
            this.costCenterDropdownOpen = false;
            this.$nextTick(() => { this.updateFilters(); });
        },

        selectReportLevel(val) {
            this.reportLevel = val;
            this.reportLevelDropdownOpen = false;
            this.$nextTick(() => { this.updateFilters(); });
        },

        matchesSearch(text) {
            if (!this.searchQuery) return true;
            return (text || '').toLowerCase().includes(this.searchQuery.toLowerCase().trim());
        },

        updateFilters() {
            const form = document.getElementById('pnlFilterForm');
            if (!form) return;
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '' && value !== 'all') {
                    params.append(key, value);
                }
            }

            const queryString = params.toString();
            const baseUrl = '{{ route('reports.profit_loss') }}';
            const fetchUrl = baseUrl + (queryString ? '?' + queryString : '');

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newKpis = doc.getElementById('pnlKpisContainer');
                const newTable = doc.getElementById('pnlTableContainer');

                if (newKpis && document.getElementById('pnlKpisContainer')) {
                    document.getElementById('pnlKpisContainer').innerHTML = newKpis.innerHTML;
                }
                if (newTable && document.getElementById('pnlTableContainer')) {
                    document.getElementById('pnlTableContainer').innerHTML = newTable.innerHTML;
                }

                // Re-initialize Alpine on dynamically updated DOM trees
                if (window.Alpine) {
                    if (document.getElementById('pnlKpisContainer')) {
                        window.Alpine.initTree(document.getElementById('pnlKpisContainer'));
                    }
                    if (document.getElementById('pnlTableContainer')) {
                        window.Alpine.initTree(document.getElementById('pnlTableContainer'));
                    }
                }
            })
            .catch(err => console.error('Error fetching P&L data:', err));
        },

        resetFilters() {
            this.searchQuery = '';
            this.financialYear = 'FY 2026-27';
            this.dateFrom = '2026-04-01';
            this.dateTo = '2027-03-31';
            this.selectedProjectId = 'all';
            this.selectedProjectName = 'All Projects';
            this.costCenter = 'All Cost Centers';
            this.reportLevel = 'Summary';
            this.projectSearch = '';
            this.fyDropdownOpen = false;
            this.projectDropdownOpen = false;
            this.costCenterDropdownOpen = false;
            this.reportLevelDropdownOpen = false;
            this.$nextTick(() => {
                this.updateFilters();
            });
        },

        exportExcel() {
            let table = document.getElementById('pnlStatementTable');
            if (!table) return;
            let html = table.outerHTML;
            let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'Profit_and_Loss_Statement.xls';
            a.click();
        },

        printReport() {
            window.print();
        }
    };
}
</script>

</x-erp-layout>
