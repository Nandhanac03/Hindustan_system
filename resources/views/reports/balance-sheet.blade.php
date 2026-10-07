<x-erp-layout title="Balance Sheet (Statement of Financial Position)" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="balanceSheetApp()">
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

    @include('reports.partials.nav')

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Top Header & Action Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Balance Sheet (Statement of Financial Position)</h1>
                    <span class="px-3 py-1 bg-amber-50 text-[#a38c29] border border-[#a38c29]/40 rounded-full text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        BALANCE SHEET EQUAL & VALIDATED
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500 mt-1">Statement of financial position as on the selected date</p>
            </div>

            {{-- Right Action Buttons --}}
            <div class="flex items-center gap-2.5 shrink-0">
                <button @click="exportExcel()" 
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer group active:scale-95">
                    <svg class="w-4 h-4 text-white transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export to Excel</span>
                </button>
            </div>
        </div>

        @php
            $defaultBsProjectId = request('project_id', '');
            $selectedBsProj = $defaultBsProjectId ? $projects->firstWhere('id', $defaultBsProjectId) : null;
            $selectedBsProjName = $selectedBsProj ? $selectedBsProj->name : 'All Projects';
            $defaultDateAsOn = request('date_as_on', '');
        @endphp

        {{-- Filter Bar Panel --}}
        <form id="balanceSheetForm" method="GET" action="{{ route('reports.balance_sheet') }}" @submit.prevent="updateFilters()" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 flex-1 items-center">
                    
                    {{-- 1. Search Particulars / Accounts --}}
                    <div class="relative group col-span-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" placeholder="Search accounts..." x-model="searchQuery" autocomplete="off"
                               class="w-full erp-search-input pl-10 pr-9">
                        <div x-show="searchQuery" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                            <button type="button" @click="searchQuery = ''"
                                    class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Entity / Scope (Project Popover with Search) --}}
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
                                      x-text="selectedBsProjName">{{ $selectedBsProjName }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                <template x-if="selectedProjectId && selectedProjectId !== 'all' && selectedProjectId !== ''">
                                    <span @click.stop="selectProject('', 'All Projects')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition cursor-pointer" title="Clear selection">
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
                                <div @click="selectProject('', 'All Projects')" 
                                     x-show="!projectSearch || 'All Projects'.toLowerCase().includes(projectSearch.toLowerCase())"
                                     class="erp-dropdown-option" :class="!selectedProjectId || selectedProjectId === 'all' ? 'selected-all' : ''">
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

                    {{-- 3. As-On Date --}}
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" name="date_as_on" x-model="dateAsOn" 
                               @change="updateFilters()" 
                               class="erp-input erp-date-input w-full pl-10 pr-3 py-2 text-xs font-bold text-slate-800 cursor-pointer">
                    </div>

                    {{-- 4. View Type Radio Options --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 flex items-center justify-around h-[42px]">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider shrink-0 mr-1">View:</span>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-800 cursor-pointer select-none">
                            <input type="radio" name="view_type" value="vertical" x-model="viewType" class="text-[#a38c29] focus:ring-[#a38c29] h-3.5 w-3.5 border-slate-300 cursor-pointer">
                            <span>Vertical</span>
                        </label>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-800 cursor-pointer select-none">
                            <input type="radio" name="view_type" value="t_format" x-model="viewType" class="text-[#a38c29] focus:ring-[#a38c29] h-3.5 w-3.5 border-slate-300 cursor-pointer">
                            <span>T-Format</span>
                        </label>
                    </div>

                    {{-- 5. Zero-Balance Accounts --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-1.5 flex items-center h-[42px]">
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer select-none w-full">
                            <input type="checkbox" name="hide_zero" value="1" x-model="hideZero" class="rounded text-[#a38c29] focus:ring-[#a38c29] h-4 w-4 border-slate-300 cursor-pointer">
                            <span>Hide Zero Balances</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Reset Button (In-memory, zero page reloads) --}}
                <div class="shrink-0 flex items-center gap-2">
                    <button type="button" @click="resetFilters()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#8C7A2E] hover:bg-[#786826] px-5 py-2.5 text-xs font-black text-white shadow-xs transition duration-200 flex-shrink-0 uppercase tracking-wider group cursor-pointer active:scale-95 whitespace-nowrap">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>RESET FILTERS</span>
                    </button>
                </div>

            </div>
        </form>

        {{-- STATEMENT OF FINANCIAL POSITION WRAPPER (AJAX Dynamic Swap Target) --}}
        <div id="balanceSheetContainer" class="space-y-6">
            
            {{-- STATEMENT OF FINANCIAL POSITION (ASSETS Left vs LIABILITIES & EQUITY Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- 1. ASSETS TABLE CONTAINER --}}
                <div class="border border-slate-300 rounded-xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="erp-table-header text-white px-5 py-2.5 text-center font-black text-xs uppercase tracking-widest border-b border-slate-700">
                        ASSETS
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left text-xs text-slate-800">
                            <thead class="erp-table-header border-b border-slate-700 text-[10px] font-black text-white uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2.5 w-32 border-r border-slate-600 text-white">Account Code / Category</th>
                                    <th class="px-4 py-2.5 border-r border-slate-600 text-white">Description / Account Head</th>
                                    <th class="px-4 py-2.5 text-right border-r border-slate-600 w-32 text-white">Amount (Rs.)</th>
                                    <th class="px-4 py-2.5 text-right w-36 text-white">Total Group Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                
                                {{-- 1000 ASSETS CATEGORY --}}
                                <tr class="bg-emerald-50/40">
                                    <td class="px-4 py-2 font-black text-emerald-700 border-r border-slate-200">1000</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-emerald-700 uppercase tracking-wider">ASSETS</td>
                                </tr>

                                {{-- 1100 CURRENT ASSETS --}}
                                <tr>
                                    <td class="px-4 py-2 font-black text-blue-700 border-r border-slate-200 pl-6">1100</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-blue-700 uppercase tracking-wider">CURRENT ASSETS</td>
                                </tr>
                                @foreach($balanceSheetData['current_assets'] as $idx => $item)
                                <tr x-show="(!hideZero || {{ abs($item['amount']) > 0 ? 'true' : 'false' }}) && matchesSearch('{{ addslashes($item['name']) }} {{ $item['code'] }}')" class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">{{ $item['code'] }}</td>
                                    <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">{{ $item['name'] }}</td>
                                    <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">{{ number_format($item['amount'], 2) }}</td>
                                    <td class="px-4 py-2 text-right font-mono {{ $loop->last ? 'font-bold text-blue-700' : '' }}">
                                        {{ $loop->last ? number_format($balanceSheetData['total_current_assets'], 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-emerald-50/70 border-y border-emerald-200 font-black">
                                    <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase">SUBTOTAL</td>
                                    <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase tracking-wider">Total Current Assets</td>
                                    <td class="px-4 py-2.5 text-right border-r border-emerald-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-emerald-800 font-mono">{{ number_format($balanceSheetData['total_current_assets'], 2) }}</td>
                                </tr>

                                {{-- 1200 NON-CURRENT / FIXED ASSETS --}}
                                <tr>
                                    <td class="px-4 py-2 font-black text-blue-700 border-r border-slate-200 pl-6">1200</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-blue-700 uppercase tracking-wider">NON-CURRENT / FIXED ASSETS</td>
                                </tr>
                                @foreach($balanceSheetData['fixed_assets'] as $idx => $item)
                                <tr x-show="(!hideZero || {{ abs($item['amount']) > 0 ? 'true' : 'false' }}) && matchesSearch('{{ addslashes($item['name']) }} {{ $item['code'] }}')" class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">{{ $item['code'] }}</td>
                                    <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">{{ $item['name'] }}</td>
                                    <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono {{ $item['amount'] < 0 ? 'text-rose-600' : '' }}">
                                        {{ $item['amount'] < 0 ? '('.number_format(abs($item['amount']), 2).')' : number_format($item['amount'], 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono {{ $loop->index === 2 ? 'font-bold text-blue-700' : '' }}">
                                        {{ $loop->index === 2 ? number_format($balanceSheetData['total_fixed_assets'], 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-emerald-50/70 border-y border-emerald-200 font-black">
                                    <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase">SUBTOTAL</td>
                                    <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase tracking-wider">Total Non-Current / Fixed Assets</td>
                                    <td class="px-4 py-2.5 text-right border-r border-emerald-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-emerald-800 font-mono">{{ number_format($balanceSheetData['total_fixed_assets'], 2) }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    {{-- TOTAL ASSETS BANNER ROW --}}
                    <div class="bg-indigo-50 border-t-2 border-indigo-200 px-4 py-3 flex items-center justify-between font-black text-indigo-950 uppercase tracking-wider text-xs">
                        <div>
                            <span class="text-indigo-600 mr-2">TOTAL (A)</span>
                            <span>TOTAL ASSETS</span>
                        </div>
                        <span class="font-mono text-indigo-700 text-sm">{{ number_format($balanceSheetData['total_assets'], 2) }}</span>
                    </div>
                </div>

                {{-- 2. LIABILITIES & EQUITY TABLE CONTAINER --}}
                <div class="border border-slate-300 rounded-xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="erp-table-header text-white px-5 py-2.5 text-center font-black text-xs uppercase tracking-widest border-b border-slate-700">
                        LIABILITIES & EQUITY
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left text-xs text-slate-800">
                            <thead class="erp-table-header border-b border-slate-700 text-[10px] font-black text-white uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2.5 w-32 border-r border-slate-600 text-white">Account Code / Category</th>
                                    <th class="px-4 py-2.5 border-r border-slate-600 text-white">Description / Account Head</th>
                                    <th class="px-4 py-2.5 text-right border-r border-slate-600 w-32 text-white">Amount (Rs.)</th>
                                    <th class="px-4 py-2.5 text-right w-36 text-white">Total Group Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                
                                {{-- 2000 LIABILITIES CATEGORY --}}
                                <tr class="bg-rose-50/40">
                                    <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200">2000</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">LIABILITIES</td>
                                </tr>

                                {{-- 2100 CURRENT LIABILITIES & PAYABLES --}}
                                <tr>
                                    <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200 pl-6">2100</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">CURRENT LIABILITIES & PAYABLES</td>
                                </tr>
                                @foreach($balanceSheetData['current_liabilities'] as $idx => $item)
                                <tr x-show="(!hideZero || {{ abs($item['amount']) > 0 ? 'true' : 'false' }}) && matchesSearch('{{ addslashes($item['name']) }} {{ $item['code'] }}')" class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">{{ $item['code'] }}</td>
                                    <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">{{ $item['name'] }}</td>
                                    <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">{{ number_format($item['amount'], 2) }}</td>
                                    <td class="px-4 py-2 text-right font-mono {{ $loop->last ? 'font-bold text-rose-700' : '' }}">
                                        {{ $loop->last ? number_format($balanceSheetData['total_current_liabilities'], 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-rose-50/70 border-y border-rose-200 font-black">
                                    <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase">SUBTOTAL</td>
                                    <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase tracking-wider">Total Current Liabilities</td>
                                    <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-rose-800 font-mono">{{ number_format($balanceSheetData['total_current_liabilities'], 2) }}</td>
                                </tr>

                                {{-- 2200 NON-CURRENT / LONG-TERM LIABILITIES --}}
                                <tr>
                                    <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200 pl-6">2200</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">NON-CURRENT / LONG-TERM LIABILITIES</td>
                                </tr>
                                @foreach($balanceSheetData['long_term_liabilities'] as $idx => $item)
                                <tr x-show="(!hideZero || {{ abs($item['amount']) > 0 ? 'true' : 'false' }}) && matchesSearch('{{ addslashes($item['name']) }} {{ $item['code'] }}')" class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">{{ $item['code'] }}</td>
                                    <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">{{ $item['name'] }}</td>
                                    <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">{{ number_format($item['amount'], 2) }}</td>
                                    <td class="px-4 py-2 text-right font-mono {{ $loop->last ? 'font-bold text-rose-700' : '' }}">
                                        {{ $loop->last ? number_format($balanceSheetData['total_long_term_liabilities'], 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-rose-50/70 border-y border-rose-200 font-black">
                                    <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase">SUBTOTAL</td>
                                    <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase tracking-wider">Total Long-Term Liabilities</td>
                                    <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-rose-800 font-mono">{{ number_format($balanceSheetData['total_long_term_liabilities'], 2) }}</td>
                                </tr>
                                <tr class="bg-rose-100/60 font-black text-rose-950 uppercase border-b border-rose-200">
                                    <td class="px-4 py-2.5 text-rose-700 border-r border-rose-200">TOTAL (B)</td>
                                    <td class="px-4 py-2.5 border-r border-rose-200 uppercase tracking-wider">TOTAL LIABILITIES</td>
                                    <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-rose-900 font-mono">{{ number_format($balanceSheetData['total_liabilities'], 2) }}</td>
                                </tr>

                                {{-- 3000 EQUITY & PARTNER CAPITAL --}}
                                <tr class="bg-purple-50/40">
                                    <td class="px-4 py-2 font-black text-purple-700 border-r border-slate-200">3000</td>
                                    <td colspan="3" class="px-4 py-2 font-black text-purple-700 uppercase tracking-wider">EQUITY & PARTNER CAPITAL</td>
                                </tr>
                                @foreach($balanceSheetData['equity'] as $idx => $item)
                                <tr x-show="(!hideZero || {{ abs($item['amount']) > 0 ? 'true' : 'false' }}) && matchesSearch('{{ addslashes($item['name']) }} {{ $item['code'] }}')" class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">{{ $item['code'] }}</td>
                                    <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">{{ $item['name'] }}</td>
                                    <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono {{ $item['amount'] < 0 ? 'text-rose-600' : '' }}">
                                        {{ $item['amount'] < 0 ? '('.number_format(abs($item['amount']), 2).')' : number_format($item['amount'], 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono {{ $loop->index === 2 ? 'font-bold text-purple-700' : '' }}">
                                        {{ $loop->index === 2 ? number_format($balanceSheetData['total_equity'], 2) : '' }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="bg-purple-50/70 border-y border-purple-200 font-black">
                                    <td class="px-4 py-2.5 text-purple-800 border-r border-purple-200 uppercase">TOTAL (C)</td>
                                    <td class="px-4 py-2.5 text-purple-800 border-r border-purple-200 uppercase tracking-wider">Total Equity & Owners' Reserves</td>
                                    <td class="px-4 py-2.5 text-right border-r border-purple-200 font-mono"></td>
                                    <td class="px-4 py-2.5 text-right text-purple-800 font-mono">{{ number_format($balanceSheetData['total_equity'], 2) }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    {{-- CHECK TOTAL LIABILITIES & EQUITY BANNER ROW --}}
                    <div class="bg-indigo-50 border-t-2 border-indigo-200 px-4 py-3 flex items-center justify-between font-black text-indigo-950 uppercase tracking-wider text-xs">
                        <div>
                            <span class="text-indigo-600 mr-2">CHECK</span>
                            <span>TOTAL LIABILITIES & EQUITY (B + C)</span>
                        </div>
                        <span class="font-mono text-indigo-700 text-sm">{{ number_format($balanceSheetData['total_liabilities_equity'], 2) }}</span>
                    </div>
                </div>

            </div>

            {{-- FOOTER SUMMARY CARDS (3 Columns) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                
                {{-- Card 1: Report Summary --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                    <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Report Summary</h4>
                    <div class="space-y-2 text-xs font-bold text-slate-700 font-mono">
                        <div class="flex justify-between border-b border-slate-100 pb-1">
                            <span class="font-sans font-semibold text-slate-600">Total Assets</span>
                            <span class="text-slate-900">Rs. {{ number_format($balanceSheetData['total_assets'], 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1">
                            <span class="font-sans font-semibold text-slate-600">Total Liabilities</span>
                            <span class="text-slate-900">Rs. {{ number_format($balanceSheetData['total_liabilities'], 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1">
                            <span class="font-sans font-semibold text-slate-600">Total Equity</span>
                            <span class="text-slate-900">Rs. {{ number_format($balanceSheetData['total_equity'], 2) }}</span>
                        </div>
                    </div>
                    <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-extrabold text-slate-800">Assets = Liabilities + Equity</span>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            BALANCED
                        </span>
                    </div>
                </div>

                {{-- Card 2: Notes --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-2">
                    <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">Notes:</h4>
                    <ol class="space-y-1.5 text-xs font-medium text-slate-600 list-decimal list-inside leading-relaxed">
                        <li>All amounts are in Indian Rupees (Rs.).</li>
                        <li>Figures shown are as per Accrual Basis of Accounting.</li>
                        <li>Click on any account head to view detailed ledger transactions.</li>
                    </ol>
                </div>

                {{-- Card 3: Sign-Off / Verification Metadata --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                    <div class="space-y-2.5 text-xs text-slate-700">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="font-semibold text-slate-500 w-24">Prepared By</span>
                            <span class="text-slate-400">:</span>
                            <span class="font-extrabold text-slate-900">Finance Team</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-semibold text-slate-500 w-24">Prepared On</span>
                            <span class="text-slate-400">:</span>
                            <span class="font-bold text-slate-800 font-mono">{{ date('d/m/Y h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="font-semibold text-slate-500 w-24">Report Type</span>
                            <span class="text-slate-400">:</span>
                            <span class="font-bold text-slate-800">Balance Sheet (Standard Vertical)</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- ── HIDDEN EXCEL EXPORT TABLE ── --}}
        <div class="hidden" style="display: none;">
            <table id="balanceSheetExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt;">
                <colgroup>
                    <col width="140" style="width: 105pt;" />
                    <col width="320" style="width: 240pt;" />
                    <col width="160" style="width: 120pt;" />
                    <col width="180" style="width: 135pt;" />
                </colgroup>
                <thead>
                    <tr height="45" style="height: 45pt;">
                        <th colspan="4" bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle;">
                            HINDUSTAN ERP: BALANCE SHEET (STATEMENT OF FINANCIAL POSITION)
                        </th>
                    </tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="4" bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle;">
                            ASSETS
                        </th>
                    </tr>
                    <tr height="35" style="height: 35pt;">
                        <th bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">ACCOUNT CODE / CATEGORY</th>
                        <th bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">DESCRIPTION / ACCOUNT HEAD</th>
                        <th bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">AMOUNT (RS.)</th>
                        <th bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">TOTAL GROUP AMOUNT (RS.)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #059669;">1000</td><td colspan="3" style="font-weight: bold; color: #059669;">ASSETS</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #1d4ed8;">1100</td><td colspan="3" style="font-weight: bold; color: #1d4ed8;">CURRENT ASSETS</td></tr>
                    @foreach($balanceSheetData['current_assets'] as $idx => $item)
                    <tr height="24" style="height: 24pt;">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">{{ $item['amount'] }}</td>
                        <td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">{{ $loop->last ? $balanceSheetData['total_current_assets'] : '' }}</td>
                    </tr>
                    @endforeach
                    <tr height="28" style="height: 28pt; background-color: #d1fae5;"><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">SUBTOTAL</td><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">Total Current Assets</td><td></td><td bgcolor="#d1fae5" style="text-align: right; font-weight: bold; color: #065f46; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_current_assets'] }}</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #1d4ed8;">1200</td><td colspan="3" style="font-weight: bold; color: #1d4ed8;">NON-CURRENT / FIXED ASSETS</td></tr>
                    @foreach($balanceSheetData['fixed_assets'] as $idx => $item)
                    <tr height="24" style="height: 24pt;">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: right; mso-number-format: '\#\,\#\#0\.00'; {{ $item['amount'] < 0 ? 'color: #e11d48;' : '' }}">{{ $item['amount'] }}</td>
                        <td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">{{ $loop->index === 2 ? $balanceSheetData['total_fixed_assets'] : '' }}</td>
                    </tr>
                    @endforeach
                    <tr height="28" style="height: 28pt; background-color: #d1fae5;"><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">SUBTOTAL</td><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">Total Non-Current / Fixed Assets</td><td></td><td bgcolor="#d1fae5" style="text-align: right; font-weight: bold; color: #065f46; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_fixed_assets'] }}</td></tr>
                    <tr height="30" style="height: 30pt; background-color: #e0e7ff;"><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL (A)</td><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL ASSETS</td><td></td><td bgcolor="#e0e7ff" style="text-align: right; font-weight: bold; color: #3730a3; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_assets'] }}</td></tr>

                    <tr height="15" style="height: 15pt;"><td colspan="4" style="border: none;"></td></tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="4" bgcolor="#534E47" style="background-color: #534E47; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle;">
                            LIABILITIES & EQUITY
                        </th>
                    </tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2000</td><td colspan="3" style="font-weight: bold; color: #e11d48;">LIABILITIES</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2100</td><td colspan="3" style="font-weight: bold; color: #e11d48;">CURRENT LIABILITIES & PAYABLES</td></tr>
                    @foreach($balanceSheetData['current_liabilities'] as $idx => $item)
                    <tr height="24" style="height: 24pt;">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">{{ $item['amount'] }}</td>
                        <td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">{{ $loop->last ? $balanceSheetData['total_current_liabilities'] : '' }}</td>
                    </tr>
                    @endforeach
                    <tr height="28" style="height: 28pt; background-color: #fee2e2;"><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">SUBTOTAL</td><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">Total Current Liabilities</td><td></td><td bgcolor="#fee2e2" style="text-align: right; font-weight: bold; color: #9f1239; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_current_liabilities'] }}</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2200</td><td colspan="3" style="font-weight: bold; color: #e11d48;">NON-CURRENT / LONG-TERM LIABILITIES</td></tr>
                    @foreach($balanceSheetData['long_term_liabilities'] as $idx => $item)
                    <tr height="24" style="height: 24pt;">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">{{ $item['amount'] }}</td>
                        <td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">{{ $loop->last ? $balanceSheetData['total_long_term_liabilities'] : '' }}</td>
                    </tr>
                    @endforeach
                    <tr height="28" style="height: 28pt; background-color: #fee2e2;"><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">SUBTOTAL</td><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">Total Long-Term Liabilities</td><td></td><td bgcolor="#fee2e2" style="text-align: right; font-weight: bold; color: #9f1239; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_long_term_liabilities'] }}</td></tr>
                    <tr height="28" style="height: 28pt; background-color: #ffe4e6;"><td bgcolor="#ffe4e6" style="font-weight: bold; color: #be123c;">TOTAL (B)</td><td bgcolor="#ffe4e6" style="font-weight: bold; color: #be123c;">TOTAL LIABILITIES</td><td></td><td bgcolor="#ffe4e6" style="text-align: right; font-weight: bold; color: #be123c; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_liabilities'] }}</td></tr>

                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #7c3aed;">3000</td><td colspan="3" style="font-weight: bold; color: #7c3aed;">EQUITY & PARTNER CAPITAL</td></tr>
                    @foreach($balanceSheetData['equity'] as $idx => $item)
                    <tr height="24" style="height: 24pt;">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: right; mso-number-format: '\#\,\#\#0\.00'; {{ $item['amount'] < 0 ? 'color: #e11d48;' : '' }}">{{ $item['amount'] }}</td>
                        <td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">{{ $loop->index === 2 ? $balanceSheetData['total_equity'] : '' }}</td>
                    </tr>
                    @endforeach
                    <tr height="28" style="height: 28pt; background-color: #f3e8ff;"><td bgcolor="#f3e8ff" style="font-weight: bold; color: #6b21a8;">TOTAL (C)</td><td bgcolor="#f3e8ff" style="font-weight: bold; color: #6b21a8;">Total Equity & Owners' Reserves</td><td></td><td bgcolor="#f3e8ff" style="text-align: right; font-weight: bold; color: #6b21a8; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_equity'] }}</td></tr>
                    <tr height="30" style="height: 30pt; background-color: #e0e7ff;"><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">CHECK</td><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL LIABILITIES & EQUITY (B + C)</td><td></td><td bgcolor="#e0e7ff" style="text-align: right; font-weight: bold; color: #3730a3; mso-number-format: '\#\,\#\#0\.00';">{{ $balanceSheetData['total_liabilities_equity'] }}</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
function balanceSheetApp() {
    return {
        searchQuery: '',
        selectedProjectId: '{{ $defaultBsProjectId }}',
        selectedBsProjName: '{{ addslashes($selectedBsProjName) }}',
        projectSearch: '',
        projectDropdownOpen: false,
        dateAsOn: '{{ $defaultDateAsOn }}',
        viewType: 'vertical',
        hideZero: {{ request('hide_zero') ? 'true' : 'false' }},

        selectProject(id, name) {
            this.selectedProjectId = id;
            this.selectedBsProjName = name;
            this.projectDropdownOpen = false;
            this.$nextTick(() => {
                this.updateFilters();
            });
        },

        matchesSearch(text) {
            if (!this.searchQuery) return true;
            return (text || '').toLowerCase().includes(this.searchQuery.toLowerCase().trim());
        },

        updateFilters() {
            const form = document.getElementById('balanceSheetForm');
            if (!form) return;
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '' && value !== 'all') {
                    params.append(key, value);
                }
            }

            const queryString = params.toString();
            const baseUrl = '{{ route('reports.balance_sheet') }}';
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

                const newContainer = doc.getElementById('balanceSheetContainer');
                const currentContainer = document.getElementById('balanceSheetContainer');
                if (newContainer && currentContainer) {
                    currentContainer.innerHTML = newContainer.innerHTML;
                }

                const newExcel = doc.getElementById('balanceSheetExcelTable');
                const currentExcel = document.getElementById('balanceSheetExcelTable');
                if (newExcel && currentExcel) {
                    currentExcel.innerHTML = newExcel.innerHTML;
                }

                if (window.Alpine && currentContainer) {
                    window.Alpine.initTree(currentContainer);
                }
            })
            .catch(err => console.error('Error updating balance sheet:', err));
        },

        resetFilters() {
            this.searchQuery = '';
            this.selectedProjectId = '';
            this.selectedBsProjName = 'All Projects';
            this.projectSearch = '';
            this.projectDropdownOpen = false;
            this.dateAsOn = '';
            this.viewType = 'vertical';
            this.hideZero = false;
            this.$nextTick(() => {
                this.updateFilters();
            });
        },

        printReport() {
            window.print();
        },

        exportExcel() {
            const table = document.querySelector('#balanceSheetExcelTable');
            if (!table) return;
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Balance Sheet');
            worksheet.views = [{ showGridLines: true }];
            worksheet.autoFilter = null;

            const cols = table.querySelectorAll('colgroup col');
            if (cols.length > 0) {
                worksheet.columns = Array.from(cols).map((col) => {
                    const widthPt = col.style.width || col.getAttribute('width');
                    let widthVal = 18;
                    if (widthPt) {
                        const match = widthPt.match(/[\d\.]+/);
                        if (match) {
                            const val = parseFloat(match[0]);
                            widthVal = widthPt.includes('pt') ? val / 6.0 : val / 7.0;
                        }
                    }
                    return { width: Math.max(widthVal + 6, 16) };
                });
            }

            function cssColorToHex(cssColor) {
                if (!cssColor) return null;
                cssColor = cssColor.trim();
                if (cssColor.startsWith('#')) {
                    let hex = cssColor.substring(1);
                    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                    return 'FF' + hex.toUpperCase();
                }
                return null;
            }

            const rows = table.querySelectorAll('tr');
            const mergedCells = [];
            function isMerged(r, c) {
                return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
            }

            let sheetRowIdx = 1;
            rows.forEach((tr) => {
                const sheetRow = worksheet.getRow(sheetRowIdx);
                const heightAttr = tr.getAttribute('height') || tr.style.height;
                if (heightAttr) {
                    const match = heightAttr.match(/[\d\.]+/);
                    sheetRow.height = match ? Math.max(parseFloat(match[0]), 26) : 26;
                } else {
                    sheetRow.height = 26;
                }

                const cells = tr.cells;
                let colIdx = 1;
                for (let cIdx = 0; cIdx < cells.length; cIdx++) {
                    const cell = cells[cIdx];
                    while (isMerged(sheetRowIdx, colIdx)) colIdx++;

                    const colspan = parseInt(cell.getAttribute('colspan')) || 1;
                    const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
                    if (colspan > 1 || rowspan > 1) {
                        worksheet.mergeCells(sheetRowIdx, colIdx, sheetRowIdx + rowspan - 1, colIdx + colspan - 1);
                        mergedCells.push({ s: { r: sheetRowIdx, c: colIdx }, e: { r: sheetRowIdx + rowspan - 1, c: colIdx + colspan - 1 } });
                    }

                    const excelCell = worksheet.getCell(sheetRowIdx, colIdx);
                    const rawVal = cell.textContent ? cell.textContent.trim() : '';

                    const bgColorAttr = cell.getAttribute('bgcolor') || cell.style.backgroundColor;
                    const bgColorHex = cssColorToHex(bgColorAttr);
                    const textColorAttr = cell.style.color;
                    const textColorHex = cssColorToHex(textColorAttr) || 'FF000000';
                    const isBold = cell.tagName === 'TH' || cell.style.fontWeight === 'bold';

                    let horizAlign = cell.style.textAlign || (cell.tagName === 'TH' ? 'center' : 'left');
                    if (horizAlign === 'start') horizAlign = 'left';
                    if (horizAlign === 'end') horizAlign = 'right';

                    const numberFormat = cell.style.msoNumberFormat || '';
                    if (numberFormat.includes('\\#\\,\\#\\#0') || numberFormat.includes('#,##0')) {
                        const cleanVal = rawVal.replace(/[^\d\.\-]/g, '');
                        const parsedNum = parseFloat(cleanVal);
                        if (rawVal && !isNaN(parsedNum)) {
                            excelCell.value = parsedNum;
                        } else {
                            excelCell.value = '';
                        }
                        excelCell.numFormat = '#,##0.00';
                    } else {
                        excelCell.value = rawVal;
                    }

                    excelCell.font = { name: 'Calibri', size: 10, bold: isBold, color: { argb: textColorHex } };
                    if (bgColorHex) {
                        excelCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: bgColorHex } };
                    }
                    excelCell.alignment = { horizontal: horizAlign, vertical: 'middle', wrapText: true };
                    excelCell.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };
                    colIdx += colspan;
                }
                sheetRowIdx++;
            });

            workbook.xlsx.writeBuffer().then(function (data) {
                const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                anchor.download = 'Balance_Sheet_Summary.xlsx';
                anchor.click();
                window.URL.revokeObjectURL(url);
            });
        }
    };
}
</script>

</x-erp-layout>
