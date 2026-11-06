@extends('layouts.erp')

@section('title', 'Contractor Payment Release Desk')

@section('content')
@php
    $filterProjects = $raBills->pluck('project')->filter()->unique('id')->sortBy('name');
    $defaultProjectId = $filterProjects->first()?->id ?? '';
    $projectsJson = collect($filterProjects ?? [])->map(fn($p) => (object)['id' => $p->id, 'name' => $p->name])->toJson();

    $filterContractors = collect($contractors);
    foreach($raBills as $b) {
        if ($b->contractor_id && !$filterContractors->contains('id', $b->contractor_id)) {
            $cName = $b->contractor->name ?? $b->contractor_name;
            if ($cName) {
                $filterContractors->push((object)['id' => $b->contractor_id, 'name' => $cName]);
            }
        }
    }
    $filterContractorsList = $filterContractors->unique('id')->sortBy('name')->values()->toJson();
    $verifiedBills = $raBills->filter(fn($b) => !empty($b->verified_date));
@endphp

{{-- ExcelJS CDN Library for Executive Client-side Excel Export --}}
<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 6mm 8mm 6mm 8mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-size: 8pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    header, nav, aside, footer, button, select, input, .print\:hidden, [class*="nav"], [class*="sidebar"] {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }

    /* All 4 Metric Cards in a Single Row in PDF/Print */
    #dashboard-stats {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 12px !important;
        page-break-inside: avoid !important;
    }
    #dashboard-stats > div {
        padding: 8px 10px !important;
        border-radius: 10px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-left-width: 5px !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #dashboard-stats .text-xl {
        font-size: 11pt !important;
        line-height: 1.2 !important;
    }
    #dashboard-stats .text-\[10px\] {
        font-size: 6.5pt !important;
        line-height: 1.1 !important;
    }
    #dashboard-stats .w-7.h-7 {
        width: 20px !important;
        height: 20px !important;
    }
    #dashboard-stats svg {
        width: 12px !important;
        height: 12px !important;
    }

    #table-container {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Exact table styling identical to actual page */
    table {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: auto !important;
        border-collapse: collapse !important;
        font-size: 12px !important;
    }
    thead {
        display: table-header-group !important;
    }
    thead th {
        font-size: 10px !important;
        font-weight: 800 !important;
        padding: 8px 6px !important;
        letter-spacing: 0.05em !important;
        background-color: #534E47 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    tr {
        page-break-inside: avoid !important;
    }
    tbody tr td {
        font-size: 12px !important;
        padding: 8px 6px !important;
        border-bottom: 0.5pt solid #e2e8f0 !important;
        vertical-align: middle !important;
        line-height: 1.35 !important;
    }

    /* Hide action column and non-essential buttons in print */
    th:last-child, td:last-child {
        display: none !important;
    }
    button[title*="Toggle Part-by-Part"],
    button[title*="Toggle"] {
        display: none !important;
    }
}
</style>

<div x-data="raBillPaymentRelease()" class="space-y-6">

    {{-- ── EXECUTIVE PRINT HEADER (ONLY VISIBLE IN PRINT/PDF) ── --}}
    <div class="print-exec-header hidden print:block mb-5 border-b-2 border-[#a38c29] pb-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black px-2.5 py-0.5 bg-[#a38c29] text-white rounded uppercase tracking-widest">TABASCO ERP</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Treasury & Contractor Disbursements</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight mt-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.</h1>
                <h2 class="text-xs font-bold text-[#a38c29] uppercase tracking-wider mt-0.5">CONTRACTOR PAYMENT RELEASE & DISBURSEMENT REGISTER</h2>
            </div>
            <div class="text-right text-[9.5px] text-slate-600 space-y-1">
                <div><span class="font-bold text-slate-400 uppercase">Run Date:</span> <span class="font-mono font-bold text-slate-800">{{ now()->format('d M Y, H:i') }}</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Project:</span> <span class="font-bold text-slate-800" x-text="getProjectFilterLabel()">All Projects</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Contractor:</span> <span class="font-bold text-[#a38c29]" x-text="getContractorFilterLabel()">All Contractors</span></div>
                <div><span class="font-bold text-slate-400 uppercase">Total Records:</span> <span class="font-mono font-bold text-slate-800" x-text="getVisibleCount()">{{ $verifiedBills->count() }}</span></div>
            </div>
        </div>
    </div>

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>CONTRACTOR OPERATIONS</span>
                <span>›</span>
                <span class="text-emerald-700 font-bold">CONTRACTOR PAYMENT RELEASE</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Contractor Treasury Payment Release Desk</span>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-bold">Disbursements & Payment Vouchers</span>
            </h1>
        </div>
    </div>

    <!-- Executive Treasury KPI Metrics Bar (Upgraded with Icons & Hover Effects) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="dashboard-stats">
        <!-- Card 1: Verified Payable Claims -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">VERIFIED PAYABLE CLAIMS</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors">₹{{ number_format((float) $totalNetApproved, 2) }}</div>
                <div class="text-[10px] text-blue-600 font-bold mt-1.5 pt-1.5 ">Total Net Approved Liability</div>
            </div>
        </div>

        <!-- Card 2: Total Disbursed (Paid) -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL DISBURSED (PAID)</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors">₹{{ number_format((float) $totalPaid, 2) }}</div>
                <div class="text-[10px] text-emerald-600 font-bold mt-1.5 pt-1.5 ">Corporate Bank Account Outflows</div>
            </div>
        </div>

        <!-- Card 3: Pending Disbursement Balances -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-500 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">PENDING DISBURSEMENT BALANCES</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors">₹{{ number_format((float) $totalBalance, 2) }}</div>
                <div class="text-[10px] text-rose-600 font-bold mt-1.5 pt-1.5">Outstanding Balance Remaining</div>
            </div>
        </div>

        <!-- Card 4: Ready For Payment -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-slate-800 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">READY FOR PAYMENT</span>
                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-800 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-slate-900 tracking-tight group-hover:text-slate-800 transition-colors">{{ $raBills->whereNotNull('verified_date')->where('balance_amount', '>', 0)->count() }} Bills</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 ">Verified & Unpaid RA Bills</div>
            </div>
        </div>
    </div>

    <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (MATCHING CHEQUE RECEIPT ENTRY) ── -->
    @php
        $filterProjects = $raBills->pluck('project')->filter()->unique('id')->sortBy('name');
        $defaultProjectId = $filterProjects->first()?->id ?? '';
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">

                {{-- 1. Contractor Filter (Searchable) --}}
                @php
                    $filterContractors = collect($contractors);
                    foreach($raBills as $b) {
                        if ($b->contractor_id && !$filterContractors->contains('id', $b->contractor_id)) {
                            $cName = $b->contractor->name ?? $b->contractor_name;
                            if ($cName) {
                                $filterContractors->push((object)['id' => $b->contractor_id, 'name' => $cName]);
                            }
                        }
                    }
                    $filterContractorsList = $filterContractors->unique('id')->sortBy('name')->values()->toJson();
                @endphp
                <div class="relative w-full" 
                     x-data="{ 
                        open: false, 
                        search: '',
                        contractorsList: {{ $filterContractorsList }},
                        getSelectedContractorName() {
                            const currentId = (typeof filterContractorId !== 'undefined' && filterContractorId) ? filterContractorId : this.filterContractorId;
                            if (!currentId) return 'All Contractors';
                            const c = this.contractorsList.find(x => String(x.id) === String(currentId));
                            return c ? c.name : 'All Contractors';
                        },
                        getFilteredContractorsList() {
                            if (!this.search) return this.contractorsList;
                            const s = this.search.toLowerCase();
                            return this.contractorsList.filter(c => (c.name || '').toLowerCase().includes(s));
                        },
                        select(id) {
                            filterContractorId = id;
                            this.filterContractorId = id;
                            this.open = false;
                            this.search = '';
                            applyFilter();
                        },
                        clear() {
                            filterContractorId = '';
                            this.filterContractorId = '';
                            this.open = false;
                            this.search = '';
                            applyFilter();
                        }
                     }" 
                     @click.outside="open = false">
                     
                    <button type="button"
                            @click="open = !open; if (open) { $nextTick(() => $refs.contractorSearchInput?.focus()); }" 
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                            <svg class="w-4 h-4 shrink-0 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="truncate text-xs font-bold"
                                  :class="filterContractorId ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                  x-text="getSelectedContractorName()">All Contractors</span>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="filterContractorId">
                                <span @click.stop="filterContractorId = ''; clear()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Searchable Dropdown Menu -->
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="erp-dropdown-popover" 
                         style="display: none;">
                        
                        <div class="p-2 bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" x-model="search" x-ref="contractorSearchInput" placeholder="Search contractor..." 
                                       class="w-full pl-8 pr-7 py-1.5 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                       @keydown.escape="open = false">
                                <template x-if="search">
                                    <button type="button" @click="search = ''; $refs.contractorSearchInput?.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                </template>
                            </div>
                        </div>

                        {{-- All Contractors Option --}}
                        <button type="button" @click="filterContractorId = ''; clear()" 
                                class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer"
                                :class="!filterContractorId ? 'bg-[#a38c29]/10 text-[#8a7522] font-black' : ''">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>— All Contractors —</span>
                        </button>
                        
                        {{-- Options List --}}
                        <div class="overflow-y-auto flex-1 p-1 space-y-0.5 max-h-52">
                            <template x-for="cont in getFilteredContractorsList()" :key="cont.id">
                                <button type="button" @click="filterContractorId = cont.id; select(cont.id)" 
                                        class="w-full px-2.5 py-1.5 text-left text-xs rounded-xl transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium"
                                        :class="String(filterContractorId) === String(cont.id) ? 'bg-[#a38c29]/15 text-[#8a7522] font-black' : 'hover:bg-slate-50 text-slate-700'">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div :class="String(filterContractorId) === String(cont.id) ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                             class="w-5 h-5 rounded-full font-bold text-[9px] flex items-center justify-center shrink-0 transition-colors"
                                             x-text="(cont.name || '?').charAt(0).toUpperCase()">
                                        </div>
                                        <span class="truncate text-xs" :class="String(filterContractorId) === String(cont.id) ? 'text-[#8a7522] font-bold' : 'text-slate-800'" x-text="cont.name"></span>
                                    </div>
                                </button>
                            </template>
                            
                            <div x-show="getFilteredContractorsList().length === 0" class="py-4 text-center text-slate-400 text-xs">
                                No contractors found
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Project Filter (ERP Standardized Dropdown) --}}
                @php
                    $projectsJson = collect($filterProjects ?? [])->map(fn($p) => (object)['id' => $p->id, 'name' => $p->name])->toJson();
                @endphp
                <div class="relative"
                     x-data="{
                        open: false,
                        projectsList: {{ $projectsJson }},
                        getSelectedProjectName() {
                            if (!filterProjectId) return 'All Projects';
                            const p = this.projectsList.find(x => x.id == filterProjectId);
                            return p ? p.name : 'All Projects';
                        }
                     }"
                     @click.outside="open = false">
                    <button type="button" @click="open = !open"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="truncate" x-text="getSelectedProjectName()">All Projects</span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29]" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="erp-dropdown-popover">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="filterProjectId = ''; applyFilter(); open = false"
                                 class="erp-dropdown-option"
                                 :class="!filterProjectId ? 'selected-all' : ''">
                                <span>All Projects</span>
                            </div>
                            <template x-for="p in projectsList" :key="p.id">
                                <div @click="filterProjectId = p.id; applyFilter(); open = false"
                                     class="erp-dropdown-option"
                                     :class="filterProjectId == p.id ? 'selected' : ''">
                                    <span x-text="p.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 3. Status Filter --}}
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                            class="erp-dropdown-trigger"
                            :class="open ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                            <span class="truncate" x-text="filterStatus === 'pending' ? 'Pending' : (filterStatus === 'partially_paid' ? 'Partially Paid' : (filterStatus === 'cleared' ? 'Cleared / Paid' : 'All Statuses'))">All Statuses</span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29]" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="erp-dropdown-popover">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="filterStatus = ''; applyFilter(); open = false"
                                 class="erp-dropdown-option"
                                 :class="!filterStatus ? 'selected-all' : ''">
                                <span>All Statuses</span>
                            </div>
                            <div @click="filterStatus = 'pending'; applyFilter(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filterStatus === 'pending' ? 'selected' : ''">
                                <span>Pending</span>
                            </div>
                            <div @click="filterStatus = 'partially_paid'; applyFilter(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filterStatus === 'partially_paid' ? 'selected' : ''">
                                <span>Partially Paid</span>
                            </div>
                            <div @click="filterStatus = 'cleared'; applyFilter(); open = false"
                                 class="erp-dropdown-option"
                                 :class="filterStatus === 'cleared' ? 'selected' : ''">
                                <span>Cleared / Paid</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl theme-btn px-5 h-[38px] text-xs font-extrabold flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </button>
        </div>
    </div>

    <!-- Payment Disbursal Desk Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" id="table-container">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Contractor Payment Release Register
                </span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold"
                      x-text="getVisibleCount() + ' Records'">
                    {{ $verifiedBills->count() }} Records
                </span>
            </div>

            {{-- Table Head Export Buttons (Export Excel & Export PDF) --}}
            <div class="flex items-center gap-2.5 print:hidden">
                <button type="button" @click.stop.prevent="exportToExcel()" 
                        class="h-[36px] px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]"
                        title="Export Filtered Contractor Payment Release Register to Excel">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EXPORT EXCEL</span>
                </button>
                <button type="button" @click.stop.prevent="exportToPdf()" 
                        class="h-[36px] px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]"
                        title="Export / Print Contractor Payment Release Register to PDF">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>EXPORT PDF</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="erp-table-header text-white uppercase tracking-wider text-[10px] font-bold sticky top-0 z-10 shadow-2xs">
                    <tr class="erp-table-header border-b border-slate-700 text-left">
                        <th class="px-3 py-3 text-left w-[140px] erp-table-header">RA BILL NO</th>
                        <th class="px-3 py-3 text-left min-w-[190px] erp-table-header">CONTRACTOR / PROJECT</th>
                        <th class="px-3 py-3 text-left w-[130px] erp-table-header">VERIFIED DATE</th>
                        <th class="px-3 py-3 text-left w-[130px] erp-table-header">NET APPROVED (₹)</th>
                        <th class="px-3 py-3 text-left text-emerald-100 w-[130px] erp-table-header">AMOUNT (₹)</th>
                        <th class="px-3 py-3 text-left text-rose-100 w-[130px] erp-table-header">BALANCE DUE (₹)</th>
                        <th class="px-3 py-3 text-left w-[110px] erp-table-header">STATUS</th>
                        <th class="px-3 py-3 text-right w-[120px] erp-table-header">ACTION</th>
                    </tr>
                </thead>
                    @forelse($verifiedBills as $bill)
                        @php
                            $isCleared = ((float)$bill->balance_amount <= 0.001);
                            $isVerified = true; // Always true because of the filter
                            $isPartiallyPaid = ($isVerified && !$isCleared && (float)$bill->paid_amount > 0);
                            $paymentCount = $bill->payments->count();
                            $statusVal = $isCleared ? 'cleared' : ($isPartiallyPaid ? 'partially_paid' : ($isVerified ? 'pending' : 'unverified'));
                        @endphp
                        <tbody x-data="{ showHistory: false }"
                               x-show="matchesFilter('{{ $bill->contractor_id }}', '{{ $bill->project_id }}', '{{ $statusVal }}')"
                               class="border-b border-slate-100 divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-3.5 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-block px-2.5 py-1 bg-slate-200/80 text-slate-900 rounded-md font-mono font-bold text-xs whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                                        @if($paymentCount > 0)
                                            <button type="button" @click="showHistory = !showHistory"
                                                    class="px-2 py-0.5 bg-[#a38c29]/15 hover:bg-[#a38c29]/30 text-[#7a681d] rounded-md font-bold text-[10px] cursor-pointer inline-flex items-center gap-1 transition shadow-2xs border border-[#a38c29]/40"
                                                    title="Toggle Part-by-Part Payment History">
                                                <span x-text="showHistory ? '▲ Hide History' : '▼ ' + {{ $paymentCount }} + ' Part Paid'"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-3 py-3.5 align-middle">
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                    <div class="text-xs text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                                </td>

                                <td class="px-3 py-3.5 text-left font-mono align-middle">
                                    @if($bill->verified_date)
                                        <div class="text-xs text-emerald-700 font-bold">
                                            {{ $bill->verified_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-medium truncate max-w-[120px]">By: {{ $bill->engineer_name ?: 'Engineer' }}</div>
                                    @else
                                        <span class="text-amber-600 text-xs italic font-semibold">Verification Pending</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3.5 text-left font-mono font-black text-blue-900 text-xs bg-blue-50/30 align-middle">
                                    ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                                </td>

                                <td class="px-3 py-3.5 text-left font-mono font-bold text-emerald-700 text-xs align-middle">
                                    <div>₹{{ number_format((float) $bill->paid_amount, 2) }}</div>
                                    @if($paymentCount > 0)
                                        <div class="text-[10px] text-[#7a681d] font-bold">{{ $paymentCount }} Installment(s)</div>
                                    @endif
                                </td>

                                <td class="px-3 py-3.5 text-left font-mono font-black text-xs align-middle {{ $isCleared ? 'text-slate-400' : 'text-rose-700' }}">
                                    ₹{{ number_format((float) $bill->balance_amount, 2) }}
                                </td>

                                <td class="px-3 py-3.5 text-left whitespace-nowrap align-middle">
                                    @if($isCleared)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#ECFDF3] text-[#065F46] border border-[#A7F3D0] inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <svg class="w-3 h-3 text-[#087443]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            <span>CLEARED</span>
                                        </span>
                                    @elseif($isPartiallyPaid)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span>PARTIALLY PAID</span>
                                        </span>
                                    @elseif($isVerified)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                            <span>PENDING RELEASE</span>
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wider">UNVERIFIED</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3.5 text-right whitespace-nowrap align-middle">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($isVerified && !$isCleared)
                                            <!-- Disburse Payment Button -->
                                            <button type="button" @click="openDisburseModal({{ json_encode($bill) }})"
                                                    class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                                    title="Disburse Payment">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </button>
                                        @elseif($isCleared)
                                            <!-- Print Voucher directly if only 1 payment was made -->
                                            @if($paymentCount === 1 && $bill->payments->first()->voucher_id)
                                                <a href="{{ url('/vouchers/' . $bill->payments->first()->voucher_id . '/payment-voucher-print') }}" target="_blank"
                                                   class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                                   title="Print Payment Voucher">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </a>
                                            @else
                                                <!-- If multiple part-payments, prompt them to expand -->
                                                <span class="p-2 rounded-lg bg-slate-100 text-slate-400 border-0 inline-flex items-center justify-center shadow-2xs cursor-help" title="Multiple part-payments exist. Expand the history (▼) to print specific vouchers.">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </span>
                                            @endif
                                        @else
                                            <!-- Requires Verification Icon -->
                                            <span class="p-2 rounded-lg bg-amber-50 text-amber-500 border-0 inline-flex items-center justify-center shadow-2xs cursor-not-allowed" title="Requires Verification">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </span>
                                        @endif
                                        
                                        <!-- View Details Button -->
                                        <button type="button" @click="openViewModal({
                                            ra_bill_number: '{{ $bill->ra_bill_number }}',
                                            contractor_name: '{{ addslashes($bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor')) }}',
                                            project_name: '{{ addslashes($bill->project->name ?? 'Site Project') }}',
                                            verified_date: '{{ $bill->verified_date ? $bill->verified_date->format('d/m/Y') : '' }}',
                                            gross_amount: {{ (float)$bill->gross_amount }},
                                            net_approved: {{ (float)$bill->net_approved_amount }},
                                            paid_amount: {{ (float)$bill->paid_amount }},
                                            balance_amount: {{ (float)$bill->balance_amount }},
                                            status: '{{ $isCleared ? 'Cleared' : ($isPartiallyPaid ? 'Partially Paid' : ($isVerified ? 'Pending Release' : 'Unverified')) }}'
                                        })" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Contractor Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Part-by-Part Payment History Expandable Accordion -->
                            @if($paymentCount > 0)
                                <tr x-show="showHistory" x-cloak class="bg-amber-50/20 border-b border-[#a38c29]/30" x-transition.opacity>
                                    <td colspan="8" class="p-4">
                                        <div class="bg-white rounded-xl p-4 border border-[#a38c29]/30 shadow-sm space-y-3">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                                <span class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span>PART-BY-PART PAYMENT DISBURSEMENT HISTORY — RA BILL #{{ $bill->ra_bill_number }}</span>
                                                </span>
                                                <span class="text-[10.5px] font-bold text-slate-600">Total Outflow Disbursed: <strong class="text-emerald-700 font-mono font-black text-xs">₹{{ number_format((float)$bill->paid_amount, 2) }}</strong></span>
                                            </div>

                                            <div class="overflow-x-auto rounded-lg border border-[#c8ddf2]">
                                                <table class="w-full text-left border-collapse text-[10.5px]">
                                                    <thead class="bg-[#e9f2fb]" style="background-color: #e9f2fb;">
                                                        <tr class="bg-[#e9f2fb] text-slate-800 text-[9px] font-black uppercase tracking-wider border-b border-[#c8ddf2]" style="background-color: #e9f2fb;">
                                                            <th class="px-3 py-2 text-slate-800 font-black">INSTALLMENT #</th>
                                                            <th class="px-3 py-2 text-slate-800 font-black">DISBURSEMENT DATE</th>
                                                            <th class="px-3 py-2 text-slate-800 font-black">CORPORATE BANK ACCOUNT</th>
                                                            <th class="px-3 py-2 text-slate-800 font-black">PAYMENT MODE & REF #</th>
                                                            <th class="px-3 py-2 text-right text-slate-800 font-black">DISBURSED AMOUNT (₹)</th>
                                                            <th class="px-3 py-2 text-right text-slate-800 font-black">ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-800">
                                                        @foreach($bill->payments as $index => $pay)
                                                            <tr class="hover:bg-amber-50/30">
                                                                <td class="px-3 py-2 font-black text-slate-800">
                                                                    <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#a38c29] rounded font-mono text-[9.5px] font-bold">Part {{ $index + 1 }}</span>
                                                                </td>
                                                                <td class="px-3 py-2 font-mono text-slate-900 font-bold">
                                                                    {{ $pay->payment_date ? $pay->payment_date->format('d/m/Y') : '—' }}
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <div class="font-bold text-slate-900">{{ $pay->companyBankAccount->bank_name ?? 'Corporate Bank Account' }}</div>
                                                                    <div class="text-[9px] text-slate-500 font-mono">A/C: {{ $pay->companyBankAccount->account_number ?? '—' }}</div>
                                                                </td>
                                                                <td class="px-3 py-2">
                                                                    <span class="px-1.5 py-0.2 rounded bg-blue-100 text-blue-900 text-[8.5px] font-black uppercase">{{ $pay->payment_mode }}</span>
                                                                    <span class="font-mono text-slate-700 font-bold ml-1">{{ $pay->reference_no ?: '—' }}</span>
                                                                </td>
                                                                <td class="px-3 py-2 text-right font-mono font-black text-emerald-800 bg-emerald-50/30">
                                                                    ₹{{ number_format((float)$pay->paid_amount, 2) }}
                                                                </td>
                                                                <td class="px-3 py-2 text-right">
                                                                    @if($pay->voucher_id)
                                                                       <a href="{{ url('/vouchers/' . $pay->voucher_id . '/payment-voucher-print') }}" target="_blank"
                                                                           class="p-1.5 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                                                           title="Print Voucher for Part {{ $index + 1 }}">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @empty
                        <tbody class="border-b border-slate-100">
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                    No Contractor RA Progress Bills pending for payment release.
                                </td>
                            </tr>
                        </tbody>
                    @endforelse

                    @if($raBills->isNotEmpty())
                        <tbody x-show="getVisibleCount() === 0" x-cloak class="border-b border-slate-100">
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <div class="font-bold text-xs text-slate-600">No RA bills found matching the selected filters.</div>
                                        <button type="button" @click="resetFilters()" class="mt-1 text-xs text-[#a38c29] hover:underline font-bold inline-flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Reset Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endif
            </table>
        </div>
    </div>

    <!-- ── MODAL: VIEW CONTRACTOR / BILL DETAILS ── -->
    <div x-show="viewModalOpen" x-cloak class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border-0 ring-0 outline-none flex flex-col max-h-[90vh] my-auto bg-white" @click.away="viewModalOpen = false">
            {{-- Header (Matching All Other Modals) --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-5 sm:px-6 py-3.5 sm:py-4 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-0.5">
                            RA Bill Details
                        </p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight" x-text="'RA Bill #' + (viewBillDetails?.ra_bill_number || '')"></h2>
                    </div>
                    <button type="button" @click="viewModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-4 sm:p-5 flex flex-col gap-3 overflow-y-auto">
                <!-- General Information Card -->
                <div class="border border-slate-200/90 rounded-xl overflow-hidden shadow-2xs">
                    <div class="bg-slate-50 px-3.5 py-2.5 border-b border-slate-200/80">
                        <span class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            General Information
                        </span>
                    </div>
                    <div class="p-3.5 bg-white grid grid-cols-2 gap-y-4 gap-x-4">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Contractor Name</span>
                            <span class="text-xs sm:text-sm font-black text-slate-900 block" x-text="viewBillDetails?.contractor_name"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Project Name</span>
                            <span class="text-xs font-bold text-slate-700 block" x-text="viewBillDetails?.project_name"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Verified Date</span>
                            <span class="text-xs font-mono font-bold" :class="viewBillDetails?.verified_date ? 'text-emerald-700' : 'text-amber-600 italic'" x-text="viewBillDetails?.verified_date || 'Verification Pending'"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border"
                                  :class="{
                                      'bg-[#ECFDF3] text-[#065F46] border-[#A7F3D0]': viewBillDetails?.status === 'Cleared',
                                      'bg-blue-50 text-blue-800 border-blue-200': viewBillDetails?.status === 'Partially Paid',
                                      'bg-amber-50 text-amber-900 border-amber-300': viewBillDetails?.status === 'Pending Release',
                                      'bg-slate-50 text-slate-600 border-slate-200': viewBillDetails?.status === 'Unverified'
                                  }"
                                  x-text="viewBillDetails?.status"></span>
                        </div>
                    </div>
                </div>

                <!-- Financial Breakdown Card (Matching Disburse Modal) -->
                <div class="border border-[#a38c29]/30 rounded-xl overflow-hidden shadow-2xs">
                    <div class="bg-amber-50/50 px-3.5 py-2.5 flex items-center justify-between border-b border-[#a38c29]/20">
                        <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Financial Breakdown
                        </span>
                    </div>
                    <div class="p-3 bg-white grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Box 1: Approved Amounts -->
                        <div class="border border-slate-200/90 rounded-lg p-2.5 bg-slate-50/30">
                            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Approved Amounts</h4>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-semibold text-slate-500">Gross Amount:</span>
                                <span class="text-xs font-mono font-black text-slate-800" x-text="'₹ ' + numberFormat(viewBillDetails?.gross_amount || 0)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-semibold text-slate-500">Net Approved:</span>
                                <span class="text-xs font-mono font-black text-blue-800" x-text="'₹ ' + numberFormat(viewBillDetails?.net_approved || 0)"></span>
                            </div>
                        </div>

                        <!-- Box 2: Payment Status -->
                        <div class="border border-[#a38c29]/20 rounded-lg p-2.5 bg-[#faf8f0]">
                            <h4 class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider mb-2 border-b border-[#a38c29]/10 pb-1.5">Payment Status</h4>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-semibold text-slate-600">Paid Amount:</span>
                                <span class="text-xs font-mono font-black text-emerald-700" x-text="'₹ ' + numberFormat(viewBillDetails?.paid_amount || 0)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-semibold text-slate-600">Balance Due:</span>
                                <span class="text-xs font-mono font-black text-rose-700" x-text="'₹ ' + numberFormat(viewBillDetails?.balance_amount || 0)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-1 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Clicking 'Go To Verification' will redirect you to the primary desk.</p>
                    <a :href="'{{ route('expenses.ra-bills.verification') }}'" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-extrabold uppercase tracking-wider rounded-lg transition cursor-pointer shadow-md shadow-[#a38c29]/20">
                        <span>Go To Verification Desk</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ── MODAL: STAGGERED DISBURSEMENT RELEASE (COMPACT & SLEEK LAPTOP-OPTIMIZED) ── -->
    <div x-show="disburseModalOpen" x-cloak class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
        <div class="relative w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border-0 ring-0 outline-none flex flex-col max-h-[90vh] my-auto" @click.away="disburseModalOpen = false">
            {{-- Dark Header (Zero White Border / Fringe) --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-5 sm:px-6 py-3.5 sm:py-4 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-0.5">
                            Payment Disbursement
                        </p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight">Disburse Staggered Contractor Payment</h2>
                    </div>
                    <button type="button" @click="disburseModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1 rounded-lg hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="selectedBill ? '{{ url('expenses/ra-bills') }}/' + selectedBill.id + '/disburse' : '#'" method="POST" novalidate target="_blank" @submit="if(!validateDisburse()) { $event.preventDefault(); } else { disburseModalOpen = false; setTimeout(() => window.location.reload(), 1200); }" class="bg-white p-4 sm:p-5 flex flex-col gap-2.5 sm:gap-3 overflow-y-auto flex-1 rounded-b-2xl">
                @csrf

                <!-- Summary Card -->
                <div class="p-2.5 sm:p-3 bg-slate-50 border border-slate-200/90 rounded-xl grid grid-cols-3 gap-2 text-center shadow-2xs">
                    <div class="border-r border-slate-200/80 pr-1 sm:pr-2">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">RA BILL NO.</span>
                        <span class="text-xs sm:text-sm font-mono font-black text-slate-900 mt-0.5 block" x-text="selectedBill ? selectedBill.ra_bill_number : ''"></span>
                    </div>
                    <div class="border-r border-slate-200/80 pr-1 sm:pr-2">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">NET APPROVED</span>
                        <span class="text-xs sm:text-sm font-mono font-black text-blue-900 mt-0.5 block" x-text="selectedBill ? '₹ ' + numberFormat(selectedBill.net_approved_amount) : ''"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-rose-700 uppercase tracking-wider">OUTSTANDING BAL.</span>
                        <span class="text-xs sm:text-sm font-mono font-black text-rose-700 mt-0.5 block" x-text="selectedBill ? '₹ ' + numberFormat(selectedBill.balance_amount) : ''"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">DISBURSEMENT DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="payment_date" x-model="disbursePaymentDate" required
                               class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs"
                               :class="(hasAttemptedDisburseSubmit && !disbursePaymentDate) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'">
                        <p x-show="hasAttemptedDisburseSubmit && !disbursePaymentDate" class="mt-1 text-[10px] font-bold text-rose-600">The disbursement date field is required.</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                            <button type="button" 
                                    @click="disbursePaidAmount = selectedBill ? selectedBill.balance_amount : ''; $nextTick(() => { const el = $el.closest('form').querySelector('input[name=\'paid_amount\']'); if(el && window.updateAmountInWordsForInput) window.updateAmountInWordsForInput(el); })"
                                    class="text-[10px] font-bold text-[#a38c29] hover:underline cursor-pointer">
                                Pay Full Balance
                            </button>
                        </div>
                        <input type="number" step="0.01" min="0.01" name="paid_amount" x-model="disbursePaidAmount" :max="selectedBill ? selectedBill.balance_amount : null" placeholder="Enter amount to disburse..." required
                               class="w-full px-3 py-2 border rounded-xl text-xs sm:text-sm font-mono font-black text-slate-900 focus:outline-none transition-all shadow-2xs"
                               :class="(hasAttemptedDisburseSubmit && (!disbursePaidAmount || parseFloat(disbursePaidAmount) <= 0)) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'"
                                oninput="window.updateAmountInWordsForInput && window.updateAmountInWordsForInput(this)">
                        <p x-show="hasAttemptedDisburseSubmit && (!disbursePaidAmount || parseFloat(disbursePaidAmount) <= 0)" class="mt-1 text-[10px] font-bold text-rose-600">The amount field is required.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                    <div class="relative" @click.outside="bankOpen = false">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                        <input type="hidden" name="company_bank_account_id" :value="selectedBankId" required>

                        <!-- Trigger Button -->
                        <div @click="bankOpen = !bankOpen; if(bankOpen) $nextTick(() => $refs.payBankSearch?.focus())"
                             class="w-full min-h-[38px] px-3 py-2 border rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs"
                             :class="(hasAttemptedDisburseSubmit && !selectedBankId) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white border-slate-200 hover:border-[#a38c29]/60'">
                            <template x-if="selectedAccount">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="px-2 py-0.5 bg-[#a38c29]/15 text-[#8a7522] rounded-md font-bold text-[10px]" x-text="selectedAccount.bank_name"></span>
                                    <span class="font-bold text-slate-800 truncate" x-text="selectedAccount.account_name || selectedAccount.bank_name"></span>
                                    <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (selectedAccount.account_number || '—') + ')'"></span>
                                </div>
                            </template>
                            <template x-if="!selectedAccount">
                                <span class="text-slate-400 font-normal">Select Company Bank Account...</span>
                            </template>
                            <svg class="w-4 h-4 text-slate-400 transition-transform shrink-0 ml-1.5" :class="bankOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <p x-show="hasAttemptedDisburseSubmit && !selectedBankId" class="mt-1 text-[10px] font-bold text-rose-600">The bank account field is required.</p>

                        {{-- Selected Bank Balance in Words Only --}}
                        <div class="mt-1.5 flex items-baseline justify-between gap-2 text-[11px]" x-show="selectedAccount">
                            <span class="text-slate-500 font-medium shrink-0">Selected Bank Balance:</span>
                            <span class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                  x-text="numberToWords(selectedAccount?.current_balance || 0)"></span>
                        </div>

                        <!-- Dropdown Search Menu -->
                        <div x-show="bankOpen" x-transition class="absolute z-50 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden max-h-56 flex flex-col" style="display: none;">
                            <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                                <div class="relative">
                                    <input type="text" x-ref="payBankSearch" x-model="bankSearch" placeholder="Search bank name, account no, branch..." class="w-full pl-7 pr-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                    <svg class="w-3 h-3 text-slate-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            <div class="overflow-y-auto divide-y divide-slate-100">
                                <template x-for="acc in filteredBankAccounts" :key="acc.id">
                                    <div @click="selectedBankId = acc.id; bankOpen = false; bankSearch = ''"
                                         class="px-3 py-2 hover:bg-[#a38c29]/10 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                         :class="selectedBankId == acc.id ? 'bg-[#a38c29]/10 font-bold border-l-4 border-l-[#a38c29]' : ''">
                                        <div class="flex flex-col min-w-0 pr-2">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                            </div>
                                            <div class="text-[9px] text-slate-400 font-mono mt-0.5" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                        </div>
                                        <div class="text-right font-mono shrink-0">
                                            <div class="text-[8px] text-slate-400 uppercase font-sans">Current Balance</div>
                                            <div class="font-bold text-slate-800 text-[11px]" x-text="'₹ ' + numberFormat(acc.current_balance || 0)"></div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="filteredBankAccounts.length === 0">
                                    <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                        <select name="payment_mode" x-model="disbursePaymentMode" required 
                                class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs"
                                :class="(hasAttemptedDisburseSubmit && !disbursePaymentMode) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'">
                            @foreach(($paymentModes ?? []) as $pm)
                                @php
                                    $pmCode = is_object($pm) ? ($pm->code ?? $pm->name) : $pm;
                                    $pmName = is_object($pm) ? ($pm->name ?? $pm->code) : $pm;
                                @endphp
                                <option value="{{ $pmCode }}">{{ $pmName }}</option>
                            @endforeach
                        </select>
                        <p x-show="hasAttemptedDisburseSubmit && !disbursePaymentMode" class="mt-1 text-[10px] font-bold text-rose-600">The payment mode field is required.</p>
                    </div>
                </div>

                <!-- ── LIVE BANK BALANCE & BILL SETTLEMENT INTELLIGENCE STRIP ── -->
                <div class="p-2.5 sm:p-3 bg-slate-50 border border-slate-200/90 rounded-xl shadow-2xs space-y-2">
                    <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-800">Bank Balance &amp; Bill Settlement Analysis</span>
                        </div>
                        <div>
                            <span x-show="isBankSufficient()" class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>✓ Sufficient Bank Balance</span>
                            </span>
                            <span x-show="!isBankSufficient()" class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300 inline-flex items-center gap-1 shadow-2xs">
                                <span>⚠️ Insufficient Funds (Shortfall: ₹ <span x-text="numberFormat(getShortfall())"></span>)</span>
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <!-- 1. Bank Account Balance -->
                        <div class="p-2.5 sm:p-3 bg-white rounded-xl border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs flex flex-col justify-between transition-all">
                            <span class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1.5">BANK ACCOUNT BALANCE</span>
                            
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500">Current:</span>
                                    <span class="font-mono font-black text-slate-900 text-sm sm:text-base" x-text="'₹ ' + numberFormat(getBankBalance())"></span>
                                </div>
                                <div class="flex items-center justify-between pt-1.5 border-t border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-500 whitespace-nowrap">Post-Payment:</span>
                                    <span class="font-mono font-black text-sm sm:text-base" :class="getPostBankBalance() >= 0 ? 'text-emerald-700' : 'text-rose-600'" x-text="'₹ ' + numberFormat(getPostBankBalance())"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. RA Bill Balance -->
                        <div class="p-2.5 sm:p-3 bg-white rounded-xl border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs flex flex-col justify-between transition-all">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">RA BILL OUTSTANDING</span>
                                <span x-show="parseFloat(disbursePaidAmount) > 0 && getBillRemaining() == 0" class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Fully Settled
                                </span>
                                <span x-show="parseFloat(disbursePaidAmount) > 0 && getBillRemaining() > 0" class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Part Due
                                </span>
                                <span x-show="!parseFloat(disbursePaidAmount)" class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                    Pending Entry
                                </span>
                            </div>
                            
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500">Current Due:</span>
                                    <span class="font-mono font-black text-slate-900 text-sm sm:text-base" x-text="'₹ ' + numberFormat(selectedBill ? selectedBill.balance_amount : 0)"></span>
                                </div>
                                <div class="flex items-center justify-between pt-1.5 border-t border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-500 whitespace-nowrap">Post-Payment:</span>
                                    <span class="font-mono font-black text-sm sm:text-base" :class="getBillRemaining() == 0 ? 'text-emerald-700' : 'text-amber-700'" x-text="'₹ ' + numberFormat(getBillRemaining())"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">REFERENCE NO (CHEQUE # / UTR #) <span class="text-rose-500 font-bold">*</span></label>
                    <input type="text" name="reference_no" x-model="disburseRefNo" placeholder="e.g. UTR123456789 or Chq #000123" required
                           class="w-full px-3 py-2 border rounded-xl text-xs font-bold text-slate-900 focus:outline-none transition-all shadow-2xs"
                           :class="(hasAttemptedDisburseSubmit && !disburseRefNo) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20'">
                    <p x-show="hasAttemptedDisburseSubmit && !disburseRefNo" class="mt-1 text-[10px] font-bold text-rose-600">The reference number field is required.</p>
                </div>

                <div>
                    <label class="flex items-start gap-2 cursor-pointer p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100/50 transition">
                        <div class="pt-0.5">
                            <input type="checkbox" name="is_historical" value="1" x-model="disburseIsHistorical" class="w-4 h-4 text-[#a38c29] bg-white border-slate-300 rounded focus:ring-[#a38c29] focus:ring-2 cursor-pointer">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Is Historical <span class="text-slate-400 normal-case font-medium tracking-normal ml-1">(Bypass Bank Balance)</span></span>
                            <span class="text-[10px] text-slate-500 font-medium">Voucher is created but the company bank balance is not updated and it will not reflect on the Treasury.</span>
                        </div>
                    </label>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100 shrink-0">
                    <button type="button" @click="disburseModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/25 border border-[#a38c29]/40 cursor-pointer active:scale-98">
                        RELEASE PAYMENT &amp; PRINT VOUCHER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POPUP ERROR ALERT MODAL (CENTERE OVERLAY POPUP ON ERROR) ── -->
    <div x-show="openErrorModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/75 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-rose-200 p-6 text-center transform transition-all" @click.away="openErrorModal = false">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4 border border-rose-200 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-[#a38c29] text-[10px] font-black uppercase tracking-widest mb-1">TREASURY ALERT</p>
            <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-2">Disbursement Failed</h3>
            <div class="text-xs text-rose-800 font-bold bg-rose-50/90 p-4 rounded-2xl border border-rose-200/80 mb-5 text-center leading-relaxed shadow-xs">
                @if(session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if($errors->any())
                    @foreach($errors->all() as $err)
                        <p>{{ $err }}</p>
                    @endforeach
                @endif
            </div>
            <button type="button" @click="openErrorModal = false"
                    class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition cursor-pointer">
                CLOSE ALERT & SELECT VALID BANK
            </button>
        </div>
    </div>

</div>

<script>
let _globalRaPaymentPrintLock = false;
window.exportRaBillPaymentReleasePdf = function() {
    if (_globalRaPaymentPrintLock) return;
    _globalRaPaymentPrintLock = true;
    window.print();
    setTimeout(() => {
        _globalRaPaymentPrintLock = false;
    }, 1200);
};

function raBillPaymentRelease() {
    const defaultProjectId = '{{ $defaultProjectId }}';
    return {
        filterContractorId: '',
        filterProjectId: defaultProjectId,
        filterStatus: '',
        projectsList: {!! $projectsJson !!},
        contractorsList: {!! $filterContractorsList !!},
        allBills: [
            @foreach($verifiedBills as $bill)
            @php
                $isCleared = ((float)$bill->balance_amount <= 0.001);
                $isVerified = true;
                $isPartiallyPaid = ($isVerified && !$isCleared && (float)$bill->paid_amount > 0);
                $statusVal = $isCleared ? 'cleared' : ($isPartiallyPaid ? 'partially_paid' : 'pending');
                $statusText = $isCleared ? 'Cleared' : ($isPartiallyPaid ? 'Partially Paid' : 'Pending Release');

                $paidDates = $bill->payments->map(function($p) {
                    return $p->payment_date ? $p->payment_date->format('d/m/Y') : null;
                })->filter()->unique()->values()->join(', ');
            @endphp
            {
                id: {{ $bill->id }},
                contractor_id: '{{ $bill->contractor_id }}',
                contractor_name: '{{ addslashes($bill->contractor_name ?: ($bill->contractor->name ?? "General Contractor")) }}',
                project_id: '{{ $bill->project_id }}',
                project_name: '{{ addslashes($bill->project->name ?? "Site Project") }}',
                ra_bill_number: '{{ addslashes($bill->ra_bill_number) }}',
                submit_date: '{{ $bill->submit_date ? $bill->submit_date->format("d/m/Y") : "—" }}',
                gross_amount: {{ (float)$bill->gross_amount }},
                verified_date: '{{ $bill->verified_date ? $bill->verified_date->format("d/m/Y") : "—" }}',
                correction_amount: {{ (float)$bill->correction_amount }},
                net_approved_amount: {{ (float)$bill->net_approved_amount }},
                due_date: '{{ $bill->due_date ? $bill->due_date->format("d/m/Y") : "—" }}',
                paid_date: '{{ $paidDates ?: "—" }}',
                paid_amount: {{ (float)$bill->paid_amount }},
                balance_amount: {{ (float)$bill->balance_amount }},
                status: '{{ $statusVal }}',
                status_text: '{{ $statusText }}',
                payments_list: [
                    @foreach($bill->payments as $p)
                    {
                        paid_date: '{{ $p->payment_date ? $p->payment_date->format("d/m/Y") : "—" }}',
                        paid_amount: {{ (float)$p->paid_amount }}
                    },
                    @endforeach
                ]
            },
            @endforeach
        ],

        applyFilter() {},

        resetFilters() {
            this.filterContractorId = '';
            this.filterProjectId = defaultProjectId;
            this.filterStatus = '';
        },

        matchesFilter(contractorId, projectId, status) {
            if (this.filterContractorId && String(contractorId) !== String(this.filterContractorId)) {
                return false;
            }
            if (this.filterProjectId && String(projectId) !== String(this.filterProjectId)) {
                return false;
            }
            if (this.filterStatus && status !== this.filterStatus) {
                return false;
            }
            return true;
        },

        getFilteredBills() {
            return this.allBills.filter(b => this.matchesFilter(b.contractor_id, b.project_id, b.status));
        },

        getVisibleCount() {
            return this.getFilteredBills().length;
        },

        getProjectFilterLabel() {
            if (!this.filterProjectId) return 'All Projects';
            const p = (this.projectsList || []).find(x => x.id == this.filterProjectId);
            return p ? p.name : 'All Projects';
        },

        getContractorFilterLabel() {
            if (!this.filterContractorId) return 'All Contractors';
            const c = (this.contractorsList || []).find(x => String(x.id) === String(this.filterContractorId));
            if (c && c.name) return c.name;
            const b = (this.allBills || []).find(x => String(x.contractor_id) === String(this.filterContractorId));
            if (b && b.contractor_name) return b.contractor_name;
            return 'All Contractors';
        },

        exportToPdf() {
            window.exportRaBillPaymentReleasePdf();
        },

        async exportToExcel() {
            try {
                if (typeof ExcelJS === 'undefined') {
                    alert('ExcelJS library is still loading. Please wait a moment and try again.');
                    return;
                }

                const list = this.getFilteredBills();
                if (!list || list.length === 0) {
                    alert('No records available to export for the current filters.');
                    return;
                }

                const selectedContractor = this.getContractorFilterLabel();
                const isContractorFiltered = Boolean(this.filterContractorId && selectedContractor && selectedContractor !== 'All Contractors');

                const selectedProject = this.getProjectFilterLabel();
                const isProjectFiltered = Boolean(this.filterProjectId && selectedProject && selectedProject !== 'All Projects');

                // 1. Initialize Workbook & Worksheet
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'TABASCO ERP';
                workbook.created = new Date();

                // Worksheet Name: Particular Contractor Name if filtered, otherwise 'Payment Release'
                let sheetName = 'Payment Release';
                if (isContractorFiltered) {
                    sheetName = selectedContractor.replace(/[\\/?*[\]:]/g, '').trim().substring(0, 31) || 'Payment Release';
                }
                const worksheet = workbook.addWorksheet(sheetName, {
                    views: [{ showGridLines: true }]
                });

                // 2. Active Project / Contractor Name for Header (Row 2 & Row 3)
                let reportTitleText = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD';
                if (isProjectFiltered) {
                    const cleanProj = selectedProject.toUpperCase();
                    if (!cleanProj.includes('TABASCO HINDUSTAN INFRA DEVELOPERS')) {
                        reportTitleText += ' - ' + cleanProj;
                    }
                }
                if (isContractorFiltered) {
                    const cleanCont = selectedContractor.toUpperCase();
                    if (!cleanCont.includes('TABASCO HINDUSTAN INFRA DEVELOPERS')) {
                        reportTitleText += ' - ' + cleanCont;
                    }
                    reportTitleText += ' - CONTRACTOR PAYMENT RELEASE STATEMENT';
                } else {
                    reportTitleText += ' - CONTRACTOR PAYMENT RELEASE REGISTER';
                }

                // Subtitle (Row 3)
                const nowStr = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                let subtitleText = '';
                if (isContractorFiltered) {
                    subtitleText = `Contractor: ${selectedContractor} | Project: ${selectedProject} | Generated On: ${nowStr}`;
                } else {
                    subtitleText = `Contractor Treasury Payment Release & Disbursement Register | Generated On: ${nowStr}`;
                }

                // 3. Column Definitions (13 Columns: SL, RA Bill, Contractor, Submit Date, Amount, Verified Date, Correction, Net After Correction, Due Date, Paid Date, Paid Amount, Balance Due, Status)
                worksheet.columns = [
                    { key: 'sl', width: 9 },                 // A: SL NO
                    { key: 'bill_no', width: 18 },            // B: RA BILL NO
                    { key: 'contractor', width: 32 },         // C: CONTRACTOR
                    { key: 'submit_date', width: 18 },        // D: RA BILL SUBMIT DATE
                    { key: 'gross_amount', width: 20 },       // E: RA BILL AMOUNT (₹)
                    { key: 'verified_date', width: 18 },      // F: RA BILL VERIFIED DATE
                    { key: 'correction_amount', width: 22 },  // G: CORRECTION OF BILL (₹)
                    { key: 'after_correction', width: 24 },   // H: RA BILL AFTER CORRECTION (₹)
                    { key: 'due_date', width: 18 },           // I: RA BILL DUE DATE
                    { key: 'paid_date', width: 20 },          // J: RA BILL PAID DATE
                    { key: 'paid_amount', width: 20 },        // K: PAID AMOUNT (₹)
                    { key: 'balance_amount', width: 20 },     // L: BALANCE DUE (₹)
                    { key: 'status', width: 18 }              // M: STATUS
                ];

                const thinBorder = {
                    top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };

                const headerBorder = {
                    top: { style: 'thin', color: { argb: 'FF475569' } },
                    left: { style: 'thin', color: { argb: 'FF475569' } },
                    bottom: { style: 'thin', color: { argb: 'FF475569' } },
                    right: { style: 'thin', color: { argb: 'FF475569' } }
                };

                // Row 1: Spacer
                worksheet.addRow([]);
                worksheet.getRow(1).height = 14;

                // Row 2: Top Title Banner (Navy Slate #2C3E50)
                worksheet.mergeCells('A2:M2');
                const row2 = worksheet.getRow(2);
                row2.height = 36;
                const cellA2 = worksheet.getCell('A2');
                cellA2.value = reportTitleText;
                cellA2.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
                cellA2.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= 13; c++) {
                    worksheet.getCell(2, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    worksheet.getCell(2, c).border = headerBorder;
                }

                // Row 3: Subtitle Banner (Cyan/Ocean Blue #007398)
                worksheet.mergeCells('A3:M3');
                const row3 = worksheet.getRow(3);
                row3.height = 25;
                const cellA3 = worksheet.getCell('A3');
                cellA3.value = subtitleText;
                cellA3.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                cellA3.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                for (let c = 1; c <= 13; c++) {
                    worksheet.getCell(3, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF007398' } };
                    worksheet.getCell(3, c).border = headerBorder;
                }

                // Row 4: Spacer
                worksheet.addRow([]);
                worksheet.getRow(4).height = 10;

                // Row 5: Table Header Row (Slate #34495E)
                const headerTitles = [
                    'SL NO',
                    'RA BILL NO.',
                    'CONTRACTOR',
                    'RA BILL SUBMIT DATE',
                    'RA BILL AMOUNT (₹)',
                    'RA BILL VERIFIED DATE',
                    'CORRECTION OF BILL (₹)',
                    'RA BILL AFTER CORRECTION (₹)',
                    'RA BILL DUE DATE',
                    'RA BILL PAID DATE',
                    'PAID AMOUNT (₹)',
                    'BALANCE DUE (₹)',
                    'STATUS'
                ];
                const row5 = worksheet.addRow(headerTitles);
                row5.height = 30;
                for (let c = 1; c <= 13; c++) {
                    const hCell = worksheet.getCell(5, c);
                    hCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF34495E' } };
                    hCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FFFFFFFF' } };
                    hCell.border = headerBorder;
                    hCell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };
                }

                // Data Rows (Row 6+)
                let totalGross = 0;
                let totalCorrection = 0;
                let totalNet = 0;
                let totalPaid = 0;
                let totalBal = 0;
                let currentRowNum = 6;

                list.forEach((bill, idx) => {
                    const isEven = idx % 2 === 0;
                    const rowBg = isEven ? 'FFFFFFFF' : 'FFF0F8FF';

                    const gross = parseFloat(bill.gross_amount) || 0;
                    const correction = parseFloat(bill.correction_amount) || 0;
                    const net = parseFloat(bill.net_approved_amount) || 0;
                    const paid = parseFloat(bill.paid_amount) || 0;
                    const bal = parseFloat(bill.balance_amount) || 0;

                    totalGross += gross;
                    totalCorrection += correction;
                    totalNet += net;
                    totalPaid += paid;
                    totalBal += bal;

                    const payments = (Array.isArray(bill.payments_list) && bill.payments_list.length > 0)
                        ? bill.payments_list
                        : null;

                    let runningPaid = 0;
                    let firstPaymentDate = '—';
                    let firstPaymentAmount = 0;
                    let firstBal = bal;
                    let firstStatus = bill.status_text || '';

                    if (payments) {
                        firstPaymentDate = payments[0].paid_date || '—';
                        firstPaymentAmount = parseFloat(payments[0].paid_amount) || 0;
                        runningPaid += firstPaymentAmount;

                        firstBal = Math.max(0, Math.round((net - runningPaid) * 100) / 100);
                        if (payments.length === 1 && Math.abs(firstBal - bal) < 0.05) {
                            firstBal = bal;
                        }
                        firstStatus = (firstBal <= 0.001) ? 'Cleared' : (runningPaid > 0 ? 'Partially Paid' : (bill.status_text || 'Pending Release'));
                    }

                    // Main Bill Row
                    const mainRowNum = currentRowNum++;
                    worksheet.addRow([
                        idx + 1,
                        bill.ra_bill_number || '',
                        bill.contractor_name || '',
                        bill.submit_date || '—',
                        gross,
                        bill.verified_date || '—',
                        correction,
                        net,
                        bill.due_date || '—',
                        firstPaymentDate,
                        firstPaymentAmount,
                        firstBal,
                        firstStatus
                    ]);
                    worksheet.getRow(mainRowNum).height = 25;

                    for (let c = 1; c <= 13; c++) {
                        const cell = worksheet.getCell(mainRowNum, c);
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                        cell.border = thinBorder;
                        cell.font = {
                            name: 'Calibri',
                            size: 10,
                            bold: (c === 1 || c === 2 || c === 5 || c === 7 || c === 8 || c === 11 || c === 12 || c === 13),
                            color: {
                                argb: (c === 5 ? 'FF0F172A' : (c === 7 ? 'FFB91C1C' : (c === 8 ? 'FF1E40AF' : (c === 11 ? 'FF047857' : (c === 12 ? 'FFB91C1C' : 'FF000000')))))
                            }
                        };
                        cell.alignment = {
                            horizontal: 'center',
                            vertical: 'middle',
                            wrapText: true
                        };
                        if (c === 5 || c === 7 || c === 8 || c === 11 || c === 12) {
                            cell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                        }
                    }

                    // Subsequent Payment Rows (if multiple payments exist)
                    if (payments && payments.length > 1) {
                        for (let p = 1; p < payments.length; p++) {
                            const subRowNum = currentRowNum++;
                            const subPayAmount = parseFloat(payments[p].paid_amount) || 0;
                            runningPaid += subPayAmount;

                            let subBal = Math.max(0, Math.round((net - runningPaid) * 100) / 100);
                            if (p === payments.length - 1 && Math.abs(subBal - bal) < 0.05) {
                                subBal = bal;
                            }
                            const subStatus = (subBal <= 0.001) ? 'Cleared' : (runningPaid > 0 ? 'Partially Paid' : 'Pending Release');

                            worksheet.addRow([
                                '', // 1: SL NO
                                '', // 2: RA Bill No
                                '', // 3: Contractor
                                '', // 4: Submit Date
                                '', // 5: RA Bill Amount
                                '', // 6: Verified Date
                                '', // 7: Correction
                                '', // 8: After Correction
                                '', // 9: Due Date
                                payments[p].paid_date || '—', // 10: Paid Date
                                subPayAmount,                 // 11: Paid Amount
                                subBal,                       // 12: Balance Due
                                subStatus                     // 13: Status
                            ]);
                            worksheet.getRow(subRowNum).height = 25;

                            for (let c = 1; c <= 13; c++) {
                                const subCell = worksheet.getCell(subRowNum, c);
                                subCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: rowBg } };
                                subCell.border = thinBorder;
                                subCell.font = {
                                    name: 'Calibri',
                                    size: 10,
                                    bold: (c === 11 || c === 12 || c === 13),
                                    color: {
                                        argb: (c === 11 ? 'FF047857' : (c === 12 ? 'FFB91C1C' : 'FF000000'))
                                    }
                                };
                                subCell.alignment = {
                                    horizontal: 'center',
                                    vertical: 'middle',
                                    wrapText: true
                                };
                                if (c === 11 || c === 12) {
                                    subCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                                }
                            }
                        }
                    }
                });

                // Total Summary Row
                const totalRowNum = currentRowNum;
                worksheet.mergeCells(`A${totalRowNum}:D${totalRowNum}`);
                const totalRow = worksheet.getRow(totalRowNum);
                totalRow.height = 36;

                const labelCell = worksheet.getCell(`A${totalRowNum}`);
                labelCell.value = 'TOTAL SUMMARY';
                labelCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                labelCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };

                for (let c = 1; c <= 4; c++) {
                    worksheet.getCell(totalRowNum, c).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                    worksheet.getCell(totalRowNum, c).border = headerBorder;
                }

                // Col E (5): Gross Amount Total
                const grossCell = worksheet.getCell(totalRowNum, 5);
                grossCell.value = totalGross;
                grossCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                grossCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                grossCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                grossCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                grossCell.border = headerBorder;

                // Col F (6): Verified Date blank fill
                const vDateCell = worksheet.getCell(totalRowNum, 6);
                vDateCell.value = '';
                vDateCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                vDateCell.border = headerBorder;

                // Col G (7): Correction Amount Total
                const corrCell = worksheet.getCell(totalRowNum, 7);
                corrCell.value = totalCorrection;
                corrCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                corrCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                corrCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                corrCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                corrCell.border = headerBorder;

                // Col H (8): Net Approved Total (After Correction)
                const netCell = worksheet.getCell(totalRowNum, 8);
                netCell.value = totalNet;
                netCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                netCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                netCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                netCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                netCell.border = headerBorder;

                // Col I (9): Due Date blank fill
                const dDateCell = worksheet.getCell(totalRowNum, 9);
                dDateCell.value = '';
                dDateCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                dDateCell.border = headerBorder;

                // Col J (10): Paid Date blank fill
                const pDateCell = worksheet.getCell(totalRowNum, 10);
                pDateCell.value = '';
                pDateCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                pDateCell.border = headerBorder;

                // Col K (11): Total Paid
                const paidCell = worksheet.getCell(totalRowNum, 11);
                paidCell.value = totalPaid;
                paidCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                paidCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                paidCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                paidCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                paidCell.border = headerBorder;

                // Col L (12): Total Balance Due
                const balCell = worksheet.getCell(totalRowNum, 12);
                balCell.value = totalBal;
                balCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                balCell.font = { name: 'Calibri', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
                balCell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                balCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                balCell.border = headerBorder;

                // Col M (13): Blank fill for aesthetic consistency
                const statusCell = worksheet.getCell(totalRowNum, 13);
                statusCell.value = '';
                statusCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C3E50' } };
                statusCell.border = headerBorder;

                // Download XLSX
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                let fileName = 'contractor-payment-release-register.xlsx';
                if (isContractorFiltered) {
                    const safeContractor = selectedContractor.replace(/[/\\?%*:|"<>]/g, '').trim().replace(/\s+/g, '_');
                    fileName = `${safeContractor}_Payment_Release_Register.xlsx`;
                }
                anchor.download = fileName;
                anchor.click();
                window.URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Export Excel Error:', err);
                alert('An error occurred during Excel export: ' + err.message);
            }
        },

        disburseModalOpen: false,
        viewModalOpen: false,
        viewBillDetails: null,
        openErrorModal: {{ ($errors->any() || session('error')) ? 'true' : 'false' }},
        selectedBill: null,
        selectedBankId: '{{ $companyBankAccounts->first()?->id ?? "" }}',
        disbursePaidAmount: '',
        companyBankAccounts: @json($companyBankAccounts ?? []),
        contractorLedgerSummaries: @json($contractorLedgerSummaries ?? []),
        bankOpen: false,
        bankSearch: '',
        
        hasAttemptedDisburseSubmit: false,
        disbursePaymentDate: '{{ date("Y-m-d") }}',
        disbursePaymentMode: '{!! isset($paymentModes[0]) ? (is_object($paymentModes[0]) ? ($paymentModes[0]->code ?? $paymentModes[0]->name) : $paymentModes[0]) : "" !!}',
        disburseRefNo: '',
        disburseIsHistorical: false,

        validateDisburse() {
            this.hasAttemptedDisburseSubmit = true;
            if (!this.disbursePaymentDate || !this.disbursePaidAmount || parseFloat(this.disbursePaidAmount) <= 0 || !this.selectedBankId || !this.disbursePaymentMode || !this.disburseRefNo) {
                return false;
            }
            return true;
        },

        get selectedAccount() {
            if (!this.selectedBankId) return null;
            return this.companyBankAccounts.find(x => x.id == this.selectedBankId) || null;
        },

        get filteredBankAccounts() {
            if (!this.bankSearch) return this.companyBankAccounts;
            const q = this.bankSearch.toLowerCase().trim();
            return this.companyBankAccounts.filter(b => 
                (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                (b.account_number && b.account_number.toLowerCase().includes(q)) ||
                (b.branch_name && b.branch_name.toLowerCase().includes(q))
            );
        },

        openViewModal(details) {
            this.viewBillDetails = details;
            this.viewModalOpen = true;
        },

        openDisburseModal(bill) {
            this.selectedBill = bill;
            this.disbursePaidAmount = '';
            this.bankOpen = false;
            this.bankSearch = '';
            this.hasAttemptedDisburseSubmit = false;
            this.disbursePaymentDate = '{{ date("Y-m-d") }}';
            this.disbursePaymentMode = '{!! isset($paymentModes[0]) ? (is_object($paymentModes[0]) ? ($paymentModes[0]->code ?? $paymentModes[0]->name) : $paymentModes[0]) : "" !!}';
            this.disburseRefNo = '';
            
            if (!this.selectedBankId && this.companyBankAccounts.length > 0) {
                this.selectedBankId = this.companyBankAccounts[0].id;
            }
            this.disburseModalOpen = true;

            this.$nextTick(() => {
                const inputEl = document.querySelector('input[name="paid_amount"]');
                if (inputEl) {
                    inputEl.focus();
                    if (window.updateAmountInWordsForInput) {
                        window.updateAmountInWordsForInput(inputEl);
                    }
                }
            });
        },

        numberToWords(val) {
            let num = parseFloat(val) || 0;
            if (num <= 0) return '';
            let integerPart = Math.floor(num);
            let decimalPart = Math.round((num - integerPart) * 100);

            const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            function toWords(n) {
                if (n < 20) return a[n];
                let digit = n % 10;
                return b[Math.floor(n / 10)] + (digit ? ' ' + a[digit] : '');
            }

            let str = '';
            let crore = Math.floor(integerPart / 10000000);
            integerPart %= 10000000;
            let lakh = Math.floor(integerPart / 100000);
            integerPart %= 100000;
            let thousand = Math.floor(integerPart / 1000);
            integerPart %= 1000;
            let hundred = Math.floor(integerPart / 100);
            let rest = integerPart % 100;

            if (crore > 0) str += toWords(crore) + ' Crore ';
            if (lakh > 0) str += toWords(lakh) + ' Lakh ';
            if (thousand > 0) str += toWords(thousand) + ' Thousand ';
            if (hundred > 0) str += toWords(hundred) + ' Hundred ';
            if (rest > 0) str += (str !== '' ? 'and ' : '') + toWords(rest) + ' ';

            let res = str.trim() ? str.trim() + ' Rupees' : '';
            if (decimalPart > 0) {
                let paiseStr = toWords(decimalPart) + ' Paise';
                res = res ? res + ' and ' + paiseStr : paiseStr;
            }
            return res ? res + ' Only' : '';
        },

        getBankBalance() {
            if (!this.selectedBankId) return 0;
            const b = this.companyBankAccounts.find(x => x.id == this.selectedBankId);
            return b ? parseFloat(b.current_balance) || 0 : 0;
        },

        getPostBankBalance() {
            const current = this.getBankBalance();
            if (this.disburseIsHistorical) return current;
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return current - paid;
        },

        isBankSufficient() {
            if (this.disburseIsHistorical) return true;
            return this.getPostBankBalance() >= 0;
        },

        getShortfall() {
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            const current = this.getBankBalance();
            return Math.max(0, paid - current);
        },

        getBillRemaining() {
            const billBal = parseFloat(this.selectedBill?.balance_amount) || 0;
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return Math.max(0, billBal - paid);
        },

        getContractorTotalDues() {
            if (!this.selectedBill) return 0;
            const cId = this.selectedBill.contractor_id;
            if (cId) {
                const summary = this.contractorLedgerSummaries.find(x => x.id == cId);
                if (summary) {
                    return parseFloat(summary.total_balance) || 0;
                }
            }
            return parseFloat(this.selectedBill.balance_amount) || 0;
        },

        getContractorPostDues() {
            const currentTotal = this.getContractorTotalDues();
            const paid = parseFloat(this.disbursePaidAmount) || 0;
            return Math.max(0, currentTotal - paid);
        },

        numberFormat(val) {
            return (parseFloat(val) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    };
}
</script>
@endsection
