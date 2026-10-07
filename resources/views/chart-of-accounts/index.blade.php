<x-erp-layout title="Chart of Accounts Master">
<div class="space-y-6 p-6" x-data="chartOfAccountsMasterData({{ json_encode($allAccounts ?? $accounts ?? []) }})">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Chart of Accounts Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage accounting head categories (Assets, Liabilities, Revenue & Expenses)</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Account Head</span>
            </button>
        </div>
    </div>

    <!-- Flash Success Message -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Executive KPI Metric Cards (Matching Standard Executive Reference Format) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Card 1: Total Accounts (Gold) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Accounts</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                    Accounts
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    {{ $totalAccounts }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">All Ledger Heads</p>
            </div>
        </div>

        {{-- Card 2: Assets (Blue) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(37,99,235,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Assets</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-blue-300 group-hover:bg-blue-100/60">
                    Asset
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-blue-600 font-mono tracking-tight block group-hover:text-blue-700 transition-colors duration-300">
                    {{ $assetCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Current & Fixed Assets</p>
            </div>
        </div>

        {{-- Card 3: Liabilities (Amber) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7h6m6 1l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9h-6M6 7H3m15 0h3"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Liabilities</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                    Liability
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                    {{ $liabilityCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Current & Long-Term</p>
            </div>
        </div>

        {{-- Card 4: Revenue (Emerald) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Revenue</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                    Revenue
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                    {{ $revenueCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Income & Sales Heads</p>
            </div>
        </div>

        {{-- Card 5: Expenses (Rose) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 00-2 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Expenses</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:bg-rose-100/60">
                    Expense
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                    {{ $expenseCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Direct & Indirect Costs</p>
            </div>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel (Pure Client-Side - Zero Page Reload) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Search by Code or Account Name..." autocomplete="off"
                           class="w-full erp-search-input pl-10 pr-9">
                    <div x-show="search" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                        <button type="button" @click="search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Account Type Filter (Custom Gold Popover) --}}
                <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button"
                            @click="open = !open"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="accountTypeFilter ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="accountTypeFilter ? accountTypeFilter : '— All Account Types —'">— All Account Types —</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="accountTypeFilter">
                                <span @click.stop="accountTypeFilter = ''" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="accountTypeFilter = ''; open = false" class="erp-dropdown-option" :class="!accountTypeFilter ? 'selected-all' : ''">
                                <span>— All Account Types —</span>
                            </div>
                            <div @click="accountTypeFilter = 'ASSET'; open = false" class="erp-dropdown-option" :class="accountTypeFilter === 'ASSET' ? 'selected' : ''">
                                <span>Assets (ASSET)</span>
                            </div>
                            <div @click="accountTypeFilter = 'LIABILITY'; open = false" class="erp-dropdown-option" :class="accountTypeFilter === 'LIABILITY' ? 'selected' : ''">
                                <span>Liabilities (LIABILITY)</span>
                            </div>
                            <div @click="accountTypeFilter = 'REVENUE'; open = false" class="erp-dropdown-option" :class="accountTypeFilter === 'REVENUE' ? 'selected' : ''">
                                <span>Revenue (REVENUE)</span>
                            </div>
                            <div @click="accountTypeFilter = 'EXPENSE'; open = false" class="erp-dropdown-option" :class="accountTypeFilter === 'EXPENSE' ? 'selected' : ''">
                                <span>Expenses (EXPENSE)</span>
                            </div>
                            <div @click="accountTypeFilter = 'EQUITY'; open = false" class="erp-dropdown-option" :class="accountTypeFilter === 'EQUITY' ? 'selected' : ''">
                                <span>Equity (EQUITY)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Filter (Custom Gold Popover) --}}
                <div class="relative w-full" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button"
                            @click="open = !open"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="statusFilter ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="statusFilter === 'active' ? 'Active Accounts' : (statusFilter === 'inactive' ? 'Inactive Accounts' : '— All Statuses —')">— All Statuses —</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="statusFilter">
                                <span @click.stop="statusFilter = ''" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="statusFilter = ''; open = false" class="erp-dropdown-option" :class="!statusFilter ? 'selected-all' : ''">
                                <span>— All Statuses —</span>
                            </div>
                            <div @click="statusFilter = 'active'; open = false" class="erp-dropdown-option" :class="statusFilter === 'active' ? 'selected' : ''">
                                <span>Active Accounts</span>
                            </div>
                            <div @click="statusFilter = 'inactive'; open = false" class="erp-dropdown-option" :class="statusFilter === 'inactive' ? 'selected' : ''">
                                <span>Inactive Accounts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="erp-table-header bg-[#17365D] text-white border-b border-slate-700 text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">ACCOUNT CODE</th>
                        <th class="px-4 py-3.5">ACCOUNT NAME</th>
                        <th class="px-4 py-3.5">ACCOUNT TYPE</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="acc in filteredAccounts" :key="acc.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]" x-text="acc.account_code"></td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900" x-text="acc.account_name"></td>
                            <td class="px-4 py-3.5">
                                <template x-if="acc.account_type === 'ASSET'">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full font-extrabold text-[10px]">ASSET</span>
                                </template>
                                <template x-if="acc.account_type === 'LIABILITY'">
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full font-extrabold text-[10px]">LIABILITY</span>
                                </template>
                                <template x-if="acc.account_type === 'REVENUE'">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-extrabold text-[10px]">REVENUE</span>
                                </template>
                                <template x-if="acc.account_type === 'EXPENSE'">
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full font-extrabold text-[10px]">EXPENSE</span>
                                </template>
                                <template x-if="acc.account_type === 'EQUITY'">
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-full font-extrabold text-[10px]">EQUITY</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <form :action="'/chart-of-accounts/' + acc.id + '/toggle-status'" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Click to toggle status" class="px-2.5 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition"
                                            :class="acc.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'"
                                            x-text="acc.is_active ? 'Active' : 'Inactive'">
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    {{-- View Trigger --}}
                                    <button type="button" @click="initView(acc)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    {{-- Edit Trigger --}}
                                    <button type="button" @click="initEdit(acc)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Account">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    {{-- Delete Trigger --}}
                                    <button type="button" @click="initDelete(acc)" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Account">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredAccounts.length === 0">
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium italic">
                                No Chart of Accounts found matching the selected filter criteria.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all" @click.outside="openViewModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CHART OF ACCOUNTS</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ACCOUNT HEAD DETAILS</h3>
                </div>
                <button type="button" @click="openViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-3.5 text-xs">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT CODE</span>
                    <span class="font-bold font-mono text-[#a38c29] text-sm" x-text="viewAccount.account_code"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewAccount.account_name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACCOUNT TYPE</span>
                    <span class="font-bold" x-text="viewAccount.account_type"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACTIVE STATUS</span>
                    <span class="font-bold" :class="viewAccount.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewAccount.is_active ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all" @click.outside="openAddModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CHART OF ACCOUNTS</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD ACCOUNT HEAD</h3>
                </div>
                <button type="button" @click="openAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('chart-of-accounts.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT CODE <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="account_code" required placeholder="e.g., 1005" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT NAME <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="account_name" required placeholder="e.g., Office Reserve Fund" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT TYPE <span class="text-rose-500 font-bold">*</span></label>
                    <select name="account_type" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                        <option value="ASSET">ASSET</option>
                        <option value="LIABILITY">LIABILITY</option>
                        <option value="REVENUE">REVENUE</option>
                        <option value="EXPENSE">EXPENSE</option>
                        <option value="EQUITY">EQUITY</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE ACCOUNT</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all" @click.outside="openEditModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CHART OF ACCOUNTS</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT ACCOUNT HEAD</h3>
                </div>
                <button type="button" @click="openEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'/chart-of-accounts/' + editAccount.id" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT CODE <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="account_code" x-model="editAccount.account_code" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT NAME <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="account_name" x-model="editAccount.account_name" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ACCOUNT TYPE <span class="text-rose-500 font-bold">*</span></label>
                    <select name="account_type" x-model="editAccount.account_type" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                        <option value="ASSET">ASSET</option>
                        <option value="LIABILITY">LIABILITY</option>
                        <option value="REVENUE">REVENUE</option>
                        <option value="EXPENSE">EXPENSE</option>
                        <option value="EQUITY">EQUITY</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">UPDATE ACCOUNT</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-rose-950 p-5 text-white flex items-center justify-between">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-rose-900/40 text-rose-200 text-[9px] font-black uppercase tracking-wider rounded border border-rose-800 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE ACCOUNT HEAD</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteAccount.account_name"></span>?</p>
                <form :action="'/chart-of-accounts/' + deleteAccount.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function chartOfAccountsMasterData(initialAccounts) {
    return {
        allAccounts: initialAccounts || [],
        search: '',
        accountTypeFilter: '',
        statusFilter: '',
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        openViewModal: false,
        viewAccount: { id: null, account_code: '', account_name: '', account_type: 'ASSET', is_active: true },
        editAccount: { id: null, account_code: '', account_name: '', account_type: 'ASSET', is_active: true },
        deleteAccount: { id: null, account_name: '' },

        init() {
            if (window.location.search) {
                window.history.replaceState(null, '', window.location.pathname);
            }
        },

        get filteredAccounts() {
            let list = this.allAccounts || [];
            if (this.accountTypeFilter) {
                list = list.filter(a => a.account_type === this.accountTypeFilter);
            }
            if (this.statusFilter) {
                if (this.statusFilter === 'active') {
                    list = list.filter(a => !!a.is_active);
                } else if (this.statusFilter === 'inactive') {
                    list = list.filter(a => !a.is_active);
                }
            }
            if (this.search && this.search.trim() !== '') {
                const q = this.search.trim().toLowerCase();
                list = list.filter(a => {
                    const code = (a.account_code || '').toLowerCase();
                    const name = (a.account_name || '').toLowerCase();
                    const type = (a.account_type || '').toLowerCase();
                    return code.includes(q) || name.includes(q) || type.includes(q);
                });
            }
            return list;
        },

        initView(acc) {
            this.viewAccount = Object.assign({}, acc);
            this.openViewModal = true;
        },
        initEdit(acc) {
            this.editAccount = Object.assign({}, acc);
            this.openEditModal = true;
        },
        initDelete(acc) {
            this.deleteAccount = Object.assign({}, acc);
            this.openDeleteModal = true;
        },
        resetFilters() {
            this.search = '';
            this.accountTypeFilter = '';
            this.statusFilter = '';
        }
    };
}
</script>
</x-erp-layout>
