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
                    Rs. {{ number_format($pnlData['kpis']['total_income']['curr'] ?? 0, 0) }}
                </span>
                <div class="text-[10px] font-bold mt-1.5 flex items-center gap-1.5">
                    @if(!empty($pnlData['kpis']['total_income']['var']['is_new']))
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold">
                            ★ Founding Period
                        </span>
                    @elseif(($pnlData['kpis']['total_income']['var']['type'] ?? '') === 'up')
                        <span class="text-emerald-600 font-bold">↑ {{ $pnlData['kpis']['total_income']['var']['text'] ?? '' }} vs Prior</span>
                    @elseif(($pnlData['kpis']['total_income']['var']['type'] ?? '') === 'down')
                        <span class="text-rose-600 font-bold">↓ {{ $pnlData['kpis']['total_income']['var']['text'] ?? '' }} vs Prior</span>
                    @else
                        <span class="text-slate-400 font-medium">— Baseline Period</span>
                    @endif
                    <span class="text-slate-400 font-normal">| {{ sprintf('%.0f', $pnlData['kpis']['total_income']['realization'] ?? 0) }}% Collected</span>
                </div>
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
                    Rs. {{ number_format($pnlData['kpis']['total_expenses']['curr'] ?? 0, 0) }}
                </span>
                <div class="text-[10px] font-bold mt-1.5 flex items-center gap-1.5">
                    @if(!empty($pnlData['kpis']['total_expenses']['var']['is_new']))
                        <span class="text-slate-500 font-semibold">{{ $pnlData['expenses']['total_pct_of_rev'] ?? '39.54%' }} of Revenue</span>
                    @elseif(($pnlData['kpis']['total_expenses']['var']['type'] ?? '') === 'up')
                        <span class="text-rose-600 font-bold">↑ {{ $pnlData['kpis']['total_expenses']['var']['text'] ?? '' }} vs Prior</span>
                    @else
                        <span class="text-slate-400 font-medium">— Baseline Period</span>
                    @endif
                </div>
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
                    {{ sprintf('%.1f', $pnlData['kpis']['gross_profit']['margin'] ?? 64.7) }}% MARGIN
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-purple-600 font-mono tracking-tight block group-hover:text-purple-700 transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['gross_profit']['curr'] ?? 0, 0) }}
                </span>
                <div class="text-[10px] font-bold mt-1.5 flex items-center gap-1.5">
                    <span class="text-purple-600 font-semibold">{{ sprintf('%.1f', $pnlData['kpis']['gross_profit']['margin'] ?? 0) }}% Gross Return</span>
                </div>
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
                    AFTER TAX
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                    Rs. {{ number_format($pnlData['kpis']['net_profit']['curr'] ?? 0, 0) }}
                </span>
                <div class="text-[10px] font-bold mt-1.5 flex items-center gap-1.5">
                    <span class="text-amber-600 font-semibold">Net Bottom Line (30% Tax Applied)</span>
                </div>
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
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100">
                    EXCELLENT
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-slate-900 transition-colors duration-300">
                    {{ $pnlData['kpis']['net_margin']['curr'] ?? '0.00' }}%
                </span>
                <div class="text-[10px] font-bold text-emerald-600 mt-1.5 flex items-center gap-1">
                    <span>★ Real Estate Target: >25%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- EXECUTIVE PERFORMANCE & EXPENSE DISTRIBUTION BANNER --}}
    <div id="pnlExecutiveBanner" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col xl:flex-row gap-6 justify-between items-stretch">
        {{-- Left Widget: Profitability Flow & Realization --}}
        <div class="flex-1 flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Profitability & Margin Overview</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $pnlData['labels']['curr'] ?? 'Current Period' }}
                    </span>
                    @if(!empty($pnlData['analytics']['is_first_year']))
                        <span class="text-[10px] font-bold px-3 py-1 rounded-full bg-amber-50 text-[#8C6D27] border border-[#E6DEC9]">
                            ★ Baseline Operating Year (DB Real Data)
                        </span>
                    @endif
                </div>
            </div>

            {{-- Visual Multi-Segment Waterfall Progress Bar --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                    <span class="uppercase tracking-wider">Revenue Flow Distribution</span>
                    <span class="text-emerald-700 font-mono font-extrabold text-xs">Net Profit Margin: {{ $pnlData['kpis']['net_margin']['curr'] ?? '0' }}%</span>
                </div>
                <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden flex p-0.5 gap-0.5 border border-slate-200/80">
                    @php
                        $costPct = (float)rtrim($pnlData['expenses']['total_pct_of_rev'] ?? '39.54%', '%');
                        $profitPct = (float)rtrim($pnlData['profit_before_tax']['pct_of_rev'] ?? '60.46%', '%');
                    @endphp
                    <div class="h-full bg-rose-500 rounded-l-full transition-all duration-500" style="width: {{ min(100, max(5, $costPct)) }}%" title="Operating Expenses: {{ $costPct }}%"></div>
                    <div class="h-full bg-emerald-500 rounded-r-full transition-all duration-500" style="width: {{ min(100, max(5, $profitPct)) }}%" title="Profit Before Tax: {{ $profitPct }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-600 pt-0.5">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span> Total Expenses: <strong class="text-slate-800">{{ $pnlData['expenses']['total_pct_of_rev'] ?? '39.54%' }}</strong></span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Profit Before Tax: <strong class="text-slate-800">{{ $pnlData['profit_before_tax']['pct_of_rev'] ?? '60.46%' }}</strong></span>
                    </div>
                    <span class="text-slate-800 font-bold">Gross Margin: <strong class="text-slate-900 font-mono">{{ sprintf('%.2f', $pnlData['kpis']['gross_profit']['margin'] ?? 64.67) }}%</strong></span>
                </div>
            </div>

            {{-- Realization / Cash Collections Sub-bar (Matching Screenshot 2 Realized Collections Cards) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 shadow-2xs">
                    <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider block">Customer Cash Collected</span>
                    <span class="text-base font-black text-slate-900 font-mono mt-0.5 block">Rs. {{ number_format($pnlData['kpis']['total_income']['cash_collected'] ?? 0, 0) }}</span>
                </div>
                <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-xl p-3 shadow-2xs">
                    <span class="text-emerald-800 text-[10px] font-bold uppercase tracking-wider block">Cash Realization Rate</span>
                    <span class="text-base font-black text-emerald-700 font-mono mt-0.5 block">{{ sprintf('%.1f', $pnlData['kpis']['total_income']['realization'] ?? 0) }}%</span>
                </div>
                <div class="bg-[#FDFBF7] border border-[#D5C28A] rounded-xl p-3 shadow-2xs">
                    <span class="text-[#8C6D27] text-[10px] font-bold uppercase tracking-wider block">Accounts Receivable</span>
                    <span class="text-base font-black text-[#8C6D27] font-mono mt-0.5 block">Rs. {{ number_format(max(0, ($pnlData['kpis']['total_income']['curr'] ?? 0) - ($pnlData['kpis']['total_income']['cash_collected'] ?? 0)), 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Right Widget: Expense Allocation Breakdown (Matching Screenshot 2 Champagne/Gold Hero Box Theme) --}}
        <div class="xl:w-[480px] bg-[#FEFDF9] rounded-xl p-4 border border-[#E6DEC9] flex flex-col justify-between space-y-3 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-wider text-slate-800">Expense Allocation Breakdown</span>
                <span class="text-xs font-black text-[#8C6D27] font-mono px-2.5 py-0.5 rounded-full bg-amber-50 border border-[#E6DEC9]">
                    Rs. {{ number_format($pnlData['kpis']['total_expenses']['curr'] ?? 0, 0) }}
                </span>
            </div>

            {{-- Segmented Bar --}}
            <div class="h-2.5 w-full bg-slate-200/70 rounded-full overflow-hidden flex gap-0.5 p-0.5">
                @foreach($pnlData['analytics']['expense_distribution'] ?? [] as $dist)
                    <div class="h-full {{ $dist['bg'] }} transition-all duration-500 first:rounded-l-full last:rounded-r-full"
                         style="width: {{ $dist['pct'] }}%" title="{{ $dist['label'] }}: {{ $dist['pct'] }}%"></div>
                @endforeach
            </div>

            {{-- Legend List --}}
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                @foreach($pnlData['analytics']['expense_distribution'] ?? [] as $dist)
                    <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-slate-200/80 shadow-2xs">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $dist['color'] }}"></span>
                            <span class="truncate text-slate-700 font-semibold">{{ $dist['label'] }}</span>
                        </div>
                        <span class="font-black text-slate-900 font-mono ml-2 shrink-0">{{ $dist['pct'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MAIN P&L STATEMENT TABLE CONTAINER (MATCHING SALES RETURN THEME & TABLE STRUCTURE) --}}
    <div id="pnlTableContainer" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col w-full">
        <div class="overflow-x-auto flex-1">
            <table id="pnlStatementTable" class="w-full text-left border-collapse">
                {{-- TABLE HEADER (EXACT STANDARD ERP THEME WITH % OF REVENUE) --}}
                <thead class="erp-table-header bg-[#17365D] border-b border-slate-700 font-black text-white uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5 text-left w-2/5">Particulars & Account Details</th>
                        <th class="px-5 py-3.5 text-right w-1/10">% of Revenue</th>
                        <th class="px-5 py-3.5 text-right w-1/5">
                            <div>{{ $pnlData['labels']['curr'] ?? 'Current Period' }}</div>
                        </th>
                        <th class="px-5 py-3.5 text-right w-1/5">
                            <div>{{ $pnlData['labels']['prior'] ?? 'Prior Period' }}</div>
                        </th>
                        <th class="px-5 py-3.5 text-right w-1/10">Variance / Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-705">

                    {{-- SECTION A: INCOME BANNER --}}
                    <tr class="bg-[#EEF7EE] hover:bg-[#e4f3e4] cursor-pointer transition" @click="showIncome = !showIncome">
                        <td colspan="5" class="py-3 px-6 font-extrabold text-[#1E4D2B]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] transition-transform duration-200" :class="{ '-rotate-90': !showIncome }">▼</span>
                                    <span>A. INCOME</span>
                                </div>
                                <span class="text-[10px] font-mono text-[#1E4D2B]/80 font-bold">Rs. {{ number_format($pnlData['income']['total_curr'] ?? 0, 0) }}</span>
                            </div>
                        </td>
                    </tr>

                    {{-- SECTION A SUB-ROWS --}}
                    @foreach($pnlData['income']['items'] as $item)
                        <tr class="hover:bg-slate-50/80 transition" x-show="showIncome && matchesSearch('{{ addslashes($item['name']) }}')">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">
                                <div class="flex items-center gap-2">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-[9px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $item['account'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-medium text-slate-500">
                                {{ $item['pct_of_rev'] ?? '0.00%' }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($item['curr'] ?? 0, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-500">
                                {{ number_format($item['prior'] ?? 0, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right">
                                @if(!empty($item['var']['is_new']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ★ New
                                    </span>
                                @elseif(($item['var']['type'] ?? '') === 'up')
                                    <span class="font-bold text-emerald-600 font-mono text-xs">↑ {{ $item['var']['text'] ?? '' }}</span>
                                @elseif(($item['var']['type'] ?? '') === 'down')
                                    <span class="font-bold text-rose-600 font-mono text-xs">↓ {{ $item['var']['text'] ?? '' }}</span>
                                @else
                                    <span class="text-slate-400 font-mono text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- TOTAL INCOME (A) ROW --}}
                    <tr class="bg-emerald-50/40 border-t border-b border-emerald-100 font-bold">
                        <td class="py-3.5 px-6 pl-6 text-[#1E4D2B]">Total Income (A)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ $pnlData['income']['total_pct_of_rev'] ?? '100.00%' }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['income']['total_curr'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]/80">
                            {{ number_format($pnlData['income']['total_prior'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            @if(!empty($pnlData['income']['total_var']['is_new']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    ★ New Period
                                </span>
                            @elseif(($pnlData['income']['total_var']['type'] ?? '') === 'up')
                                <span class="font-bold text-emerald-600 font-mono text-xs">↑ {{ $pnlData['income']['total_var']['text'] ?? '' }}</span>
                            @elseif(($pnlData['income']['total_var']['type'] ?? '') === 'down')
                                <span class="font-bold text-rose-600 font-mono text-xs">↓ {{ $pnlData['income']['total_var']['text'] ?? '' }}</span>
                            @else
                                <span class="text-slate-400 font-mono text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- SECTION B: EXPENSES BANNER --}}
                    <tr class="bg-[#FCF0F0] hover:bg-[#fae6e6] cursor-pointer transition" @click="showExpenses = !showExpenses">
                        <td colspan="5" class="py-3 px-6 font-extrabold text-[#881337]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] transition-transform duration-200" :class="{ '-rotate-90': !showExpenses }">▼</span>
                                    <span>B. EXPENSES</span>
                                </div>
                                <span class="text-[10px] font-mono text-[#881337]/80 font-bold">Rs. {{ number_format($pnlData['expenses']['total_curr'] ?? 0, 0) }}</span>
                            </div>
                        </td>
                    </tr>

                    {{-- SECTION B SUB-ROWS --}}
                    @foreach($pnlData['expenses']['items'] as $item)
                        <tr class="hover:bg-slate-50/80 transition" x-show="showExpenses && matchesSearch('{{ addslashes($item['name']) }}')">
                            <td class="py-3 px-6 pl-10 text-slate-700 font-medium">
                                <div class="flex items-center gap-2">
                                    <span>{{ $item['name'] }}</span>
                                    <span class="text-[9px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $item['account'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-medium text-slate-500">
                                {{ $item['pct_of_rev'] ?? '0.00%' }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-800">
                                {{ number_format($item['curr'] ?? 0, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right font-mono font-semibold text-slate-500">
                                {{ number_format($item['prior'] ?? 0, 0) }}
                            </td>
                            <td class="py-3 px-6 text-right">
                                @if(!empty($item['var']['is_new']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ★ New
                                    </span>
                                @elseif(($item['var']['type'] ?? '') === 'up')
                                    <span class="font-bold text-rose-600 font-mono text-xs">↑ {{ $item['var']['text'] ?? '' }}</span>
                                @elseif(($item['var']['type'] ?? '') === 'down')
                                    <span class="font-bold text-emerald-600 font-mono text-xs">↓ {{ $item['var']['text'] ?? '' }}</span>
                                @else
                                    <span class="text-slate-400 font-mono text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    {{-- TOTAL EXPENSES (B) ROW --}}
                    <tr class="bg-rose-50/40 border-t border-b border-rose-100 font-bold">
                        <td class="py-3.5 px-6 pl-6 text-[#991B1B]">Total Expenses (B)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#DC2626]">
                            {{ $pnlData['expenses']['total_pct_of_rev'] ?? '0.00%' }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#DC2626]">
                            {{ number_format($pnlData['expenses']['total_curr'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#DC2626]/80">
                            {{ number_format($pnlData['expenses']['total_prior'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            @if(!empty($pnlData['expenses']['total_var']['is_new']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ★ New
                                </span>
                            @elseif(($pnlData['expenses']['total_var']['type'] ?? '') === 'up')
                                <span class="font-bold text-rose-600 font-mono text-xs">↑ {{ $pnlData['expenses']['total_var']['text'] ?? '' }}</span>
                            @else
                                <span class="text-slate-400 font-mono text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- ROW C: PROFIT BEFORE TAX (A - B) --}}
                    <tr class="bg-emerald-50/30 hover:bg-emerald-50/60 font-extrabold border-b border-slate-100 transition">
                        <td class="py-3.5 px-6 text-[#1E4D2B]">C. Profit Before Tax (A - B)</td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ $pnlData['profit_before_tax']['pct_of_rev'] ?? '0.00%' }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['profit_before_tax']['curr'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-[#1E4D2B]/80">
                            {{ number_format($pnlData['profit_before_tax']['prior'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            @if(!empty($pnlData['profit_before_tax']['var']['is_new']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ★ New
                                </span>
                            @elseif(($pnlData['profit_before_tax']['var']['type'] ?? '') === 'up')
                                <span class="font-bold text-emerald-600 font-mono text-xs">↑ {{ $pnlData['profit_before_tax']['var']['text'] ?? '' }}</span>
                            @else
                                <span class="text-slate-400 font-mono text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- ROW D: TAX EXPENSE --}}
                    <tr class="hover:bg-slate-50/80 font-medium border-b border-slate-100 transition">
                        <td class="py-3.5 px-6 text-slate-800">
                            <div class="flex items-center gap-2">
                                <span>D. Tax Provision (30% Corporate Tax Estimate)</span>
                                <span class="text-[9px] font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">Statutory Tax</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-slate-600">
                            {{ $pnlData['tax_expense']['pct_of_rev'] ?? '0.00%' }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-slate-800">
                            {{ number_format($pnlData['tax_expense']['curr'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-mono text-slate-500">
                            {{ number_format($pnlData['tax_expense']['prior'] ?? 0, 0) }}
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            @if(!empty($pnlData['tax_expense']['var']['is_new']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ★ New
                                </span>
                            @elseif(($pnlData['tax_expense']['var']['type'] ?? '') === 'up')
                                <span class="font-bold text-rose-600 font-mono text-xs">↑ {{ $pnlData['tax_expense']['var']['text'] ?? '' }}</span>
                            @else
                                <span class="text-slate-400 font-mono text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- ROW E: NET PROFIT AFTER TAX (C - D) --}}
                    <tr class="bg-emerald-100/40 hover:bg-emerald-100/60 font-extrabold text-sm border-t-2 border-emerald-300 transition">
                        <td class="py-4 px-6 text-[#1E4D2B]">
                            <div class="flex items-center gap-2">
                                <span>E. Net Profit After Tax (C - D)</span>
                                <span class="text-[9px] font-mono text-emerald-800 bg-emerald-200/60 px-2 py-0.5 rounded-full font-black">NET BOTTOM LINE</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ $pnlData['net_profit_after_tax']['pct_of_rev'] ?? '0.00%' }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-[#1E4D2B]">
                            {{ number_format($pnlData['net_profit_after_tax']['curr'] ?? 0, 0) }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-[#1E4D2B]/80">
                            {{ number_format($pnlData['net_profit_after_tax']['prior'] ?? 0, 0) }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if(!empty($pnlData['net_profit_after_tax']['var']['is_new']))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-emerald-600 text-white shadow-xs">
                                    ★ New Period
                                </span>
                            @elseif(($pnlData['net_profit_after_tax']['var']['type'] ?? '') === 'up')
                                <span class="font-black text-emerald-700 font-mono text-sm">↑ {{ $pnlData['net_profit_after_tax']['var']['text'] ?? '' }}</span>
                            @else
                                <span class="text-slate-400 font-mono text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER (MATCHING SALES RETURN EXACT FOOTER UI) --}}
        <div class="px-5 py-3 border-t border-slate-200/80 bg-slate-50/60 flex items-center justify-between">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                SHOWING <span class="text-slate-900 font-extrabold">1</span> TO 
                <span class="text-slate-900 font-extrabold">{{ count($pnlData['income']['items']) + count($pnlData['expenses']['items']) }}</span> OF 
                <span class="text-slate-900 font-extrabold">{{ count($pnlData['income']['items']) + count($pnlData['expenses']['items']) }}</span> ENTRIES
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

                const newBanner = doc.getElementById('pnlExecutiveBanner');
                if (newKpis && document.getElementById('pnlKpisContainer')) {
                    document.getElementById('pnlKpisContainer').innerHTML = newKpis.innerHTML;
                }
                if (newBanner && document.getElementById('pnlExecutiveBanner')) {
                    document.getElementById('pnlExecutiveBanner').innerHTML = newBanner.innerHTML;
                }
                if (newTable && document.getElementById('pnlTableContainer')) {
                    document.getElementById('pnlTableContainer').innerHTML = newTable.innerHTML;
                }

                // Re-initialize Alpine on dynamically updated DOM trees
                if (window.Alpine) {
                    if (document.getElementById('pnlKpisContainer')) {
                        window.Alpine.initTree(document.getElementById('pnlKpisContainer'));
                    }
                    if (document.getElementById('pnlExecutiveBanner')) {
                        window.Alpine.initTree(document.getElementById('pnlExecutiveBanner'));
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
