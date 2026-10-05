@extends('layouts.erp')

@section('title', 'RA Bill Verification & Sign-off')

@section('content')
<div x-data="raBillVerification()" 
     @open-new-ra-bill.window="openAddModal()" 
     @close-new-ra-bill.window="closeAddModal()" 
     class="space-y-6">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>CONTRACTOR OPERATIONS</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">RA BILL VERIFICATION</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>RA Progress Bills Verification Desk</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Inward Claims & Engineer Sign-Off</span>
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto">
            <!-- Export Excel Button -->
            <button type="button" @click="exportExcel('classic')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-emerald-500/40">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export Excel</span>
            </button>

            <!-- New RA Progress Bill Button -->
            <button type="button" @click="openAddModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>New RA Progress Bill</span>
            </button>
        </div>
    </div>

    <!-- ── SUCCESS & ERROR ALERTS ── -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
        </div>
    @endif

    <!-- Executive KPI Metrics Bar (Upgraded with Icons & Hover Effects) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total RA Claimed -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-slate-800 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-slate-400 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(30,41,59,0.15)] cursor-default">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-700 border border-slate-200 transition-all duration-300 group-hover:bg-slate-800 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL RA CLAIMED</span>
                </div>
                <span class="text-[9px] text-slate-700 font-bold bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider">Inward</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-slate-800 transition-colors duration-300">₹{{ number_format((float) $totalGross, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">{{ $raBills->count() }} Inward RA Progress Bills</p>
            </div>
        </div>

        <!-- Card 2: Engineer Deductions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-default">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">ENGINEER DEDUCTIONS</span>
                </div>
                <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider">Applied</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">-₹{{ number_format((float) $totalCorrections, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total Corrections Applied</p>
            </div>
        </div>

        <!-- Card 3: Net Approved Liabilities -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.15)] cursor-default">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">NET APPROVED</span>
                </div>
                <span class="text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider">Payable</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-blue-600 font-mono tracking-tight block group-hover:text-blue-700 transition-colors duration-300">₹{{ number_format((float) $totalNetApproved, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Verified Payable Claimed</p>
            </div>
        </div>

        <!-- Card 4: Verification Sign-offs -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 11l3 3L22 4m-10 12h8m-8 4h8m-16 0h.01M3 16h.01M3 12h.01M3 8h.01M3 4h.01"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">SIGN-OFFS</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider">Completed</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">{{ $raBills->whereNotNull('verified_date')->count() }} / {{ $raBills->count() }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Completed Engineer Sign-Offs</p>
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
                            if (!filterContractorId) return 'All Contractors';
                            const c = this.contractorsList.find(x => x.id == filterContractorId);
                            return c ? c.name : 'All Contractors';
                        },
                        getFilteredContractorsList() {
                            if (!this.search) return this.contractorsList;
                            const s = this.search.toLowerCase();
                            return this.contractorsList.filter(c => c.name.toLowerCase().includes(s));
                        }
                     }" 
                     @click.outside="open = false">
                     
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>

                    <button type="button" @click="open = !open" 
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none shadow-2xs flex items-center justify-between transition hover:border-[#a38c29]/60 hover:bg-white text-left">
                        <span class="truncate" x-text="getSelectedContractorName()"></span>
                        <div class="flex items-center gap-1 shrink-0 absolute right-2.5 top-1/2 -translate-y-1/2">
                            <template x-if="filterContractorId">
                                <span @click.stop="filterContractorId = ''; search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-200 transition cursor-pointer" title="Clear selection">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Searchable Dropdown Menu -->
                    <div x-show="open" x-transition.opacity.duration.150ms 
                         class="absolute top-full left-0 mt-1 w-full bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                         style="display: none;">
                        
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" x-model="search" placeholder="Search contractor..." 
                                   class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:bg-white transition"
                                   @keydown.escape="open = false">
                            <template x-if="search">
                                <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                            </template>
                        </div>

                        <div class="max-h-56 overflow-y-auto space-y-0.5 text-xs font-semibold">
                            <button type="button" @click="filterContractorId = ''; open = false; search = '';" 
                                    class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                    :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': !filterContractorId }">
                                <span>All Contractors</span>
                            </button>
                            
                            <template x-for="cont in getFilteredContractorsList()" :key="cont.id">
                                <button type="button" @click="filterContractorId = cont.id; open = false; search = '';" 
                                        class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                        :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': filterContractorId == cont.id }">
                                    <span class="truncate" x-text="cont.name"></span>
                                </button>
                            </template>
                            
                            <div x-show="getFilteredContractorsList().length === 0" class="px-3 py-3 text-center text-slate-400 text-xs italic">
                                No contractors found.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Project Filter (1st Project Default Selected) --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <select x-model="filterProjectId"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($filterProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- 3. Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="filterStatus"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="verified">Verified / Signed Off</option>
                        <option value="submitted">Pending Verification</option>
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

    <!-- Excel-Matched RA Progress Bills Register Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">RA Progress Bills & Verification Sign-Off Register</span>
                <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold"
                      x-text="getVisibleCount() + ' Records'">{{ $raBills->count() }} Records</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1240px] text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                    <tr class="text-left">
                        <th class="px-3 py-3 text-left w-[85px]">RA BILL NO</th>
                        <th class="px-3 py-3 text-left w-[170px]">CONTRACTOR / PROJECT</th>
                        <th class="px-3 py-3 text-left w-[115px]">SUBMIT / VERIFIED</th>
                        <th class="px-3 py-3 text-right w-[110px]">RA BILL AMOUNT</th>
                        <th class="px-3 py-3 text-right w-[100px]">CORRECTION</th>
                        <th class="px-3 py-3 text-right w-[110px]">AFTER CORRECTION</th>
                        <th class="px-3 py-3 text-right w-[110px]">ADDITIONAL %</th>
                        <th class="px-3 py-3 text-right bg-[#8a7522]/40 w-[115px]">NET RA PAYABLE</th>
                        <th class="px-3 py-3 text-center w-[95px]">DUE DATE</th>
                        <th class="px-3 py-3 text-center w-[85px]">STATUS</th>
                        <th class="px-3 py-3 text-right w-[105px]">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    @forelse($raBills as $bill)
                        @php
                            $statusVal = $bill->verified_date ? 'verified' : 'submitted';
                        @endphp
                        <tr x-show="matchesFilter('{{ $bill->contractor_id }}', '{{ $bill->project_id }}', '{{ $statusVal }}')"
                            class="hover:bg-amber-50/20 transition-colors border-b border-slate-100">
                            <td class="px-3 py-3 text-left align-middle border-r border-slate-200/50 bg-slate-50/50">
                                <span class="inline-block px-2 py-0.5 bg-slate-200/80 text-slate-900 rounded font-mono font-extrabold text-[10.5px] whitespace-nowrap shadow-2xs">{{ $bill->ra_bill_number }}</span>
                            </td>

                            <td class="px-3 py-3 align-middle">
                                <div class="font-black text-slate-900 text-[11.5px] leading-tight">{{ $bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor') }}</div>
                                <div class="text-[10px] text-slate-500 font-semibold mt-0.5 leading-tight">{{ $bill->project->name ?? 'Site Project' }}</div>
                            </td>

                            <td class="px-3 py-3 text-left font-mono align-middle">
                                <div class="text-slate-700 font-bold text-[10.5px]">
                                    {{ $bill->submit_date ? $bill->submit_date->format('d/m/Y') : '—' }}
                                </div>
                                @if($bill->verified_date)
                                    <div class="text-[9.5px] text-emerald-700 font-bold mt-0.5 whitespace-nowrap" title="Verified By: {{ $bill->engineer_name }}">
                                        Ver: {{ $bill->verified_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[8.5px] text-slate-500 font-semibold truncate max-w-[100px]">
                                        By: {{ $bill->engineer_name ?: 'Engineer' }}
                                    </div>
                                @else
                                    <div class="text-[9.5px] text-amber-600 italic font-medium mt-0.5">Unverified</div>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right font-mono font-bold text-slate-900 align-middle">
                                ₹{{ number_format((float) $bill->gross_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-right font-mono text-amber-700 font-bold align-middle">
                                {{ (float)$bill->correction_amount > 0 ? '-₹' . number_format((float)$bill->correction_amount, 2) : '₹0.00' }}
                            </td>

                            <td class="px-3 py-3 text-right font-mono font-bold text-slate-800 bg-slate-50/50 align-middle">
                                ₹{{ number_format(max(0, (float)$bill->gross_amount - (float)$bill->correction_amount), 2) }}
                            </td>

                            <td class="px-3 py-3 text-right font-mono align-middle">
                                @php
                                    $afterCorr = max(0, (float)$bill->gross_amount - (float)$bill->correction_amount);
                                    $addAmt = (float)$bill->additional_amount;
                                    $pct = (float)($bill->additional_percentage > 0 ? $bill->additional_percentage : ($afterCorr > 0 ? round(($addAmt / $afterCorr) * 100, 2) : 0));
                                    $formattedPct = ($pct == (int)$pct) ? (int)$pct : $pct;
                                @endphp
                                @if($addAmt > 0)
                                    <div class="font-bold text-slate-900 text-[11px]">
                                        +₹{{ number_format($addAmt, 2) }}
                                    </div>
                                    <div class="text-[9.5px] font-black text-amber-700 mt-0.5 whitespace-nowrap">
                                        ({{ $formattedPct }}%)
                                    </div>
                                @else
                                    <span class="text-slate-400 font-bold">—</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right font-mono font-black text-blue-900 bg-blue-50/30 align-middle">
                                ₹{{ number_format((float) $bill->net_approved_amount, 2) }}
                            </td>

                            <td class="px-3 py-3 text-center font-mono align-middle">
                                <div class="text-slate-700 font-bold text-[10.5px]">
                                    {{ $bill->due_date ? $bill->due_date->format('d/m/Y') : '—' }}
                                </div>
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap align-middle">
                                @if($bill->verified_date)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                                        <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>VERIFIED</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center shadow-2xs uppercase tracking-wider">
                                        SUBMITTED
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-right whitespace-nowrap align-middle">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($bill->verified_date)
                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Verified By: {{ $bill->engineer_name }}. Click to view or update sign-off.">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    @else
                                        <button type="button" @click="openVerifyModal({{ json_encode($bill) }})"
                                                class="p-1.5 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Engineer Sign-off & Apply Correction">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    @endif

                                    <button type="button" @click="openViewModal({{ json_encode($bill) }})" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Bill Details">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                No Contractor RA Progress Bills recorded yet. Click "+ Log New RA Progress Bill" to create one.
                            </td>
                        </tr>
                    @endforelse

                    @if($raBills->isNotEmpty())
                        <tr x-show="getVisibleCount() === 0" x-cloak>
                            <td colspan="10" class="px-4 py-12 text-center text-slate-400">
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
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── MODAL 0: VIEW CONTRACTOR / BILL DETAILS (READ-ONLY) ── -->
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
                            <span class="text-xs sm:text-sm font-black text-slate-900 block" x-text="viewBillDetails?.contractor_name || viewBillDetails?.contractor?.name"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Project Name</span>
                            <span class="text-xs font-bold text-slate-700 block" x-text="viewBillDetails?.project_name || viewBillDetails?.project?.name"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Verified Date</span>
                            <span class="text-xs font-mono font-bold" :class="viewBillDetails?.verified_date ? 'text-emerald-700' : 'text-amber-600 italic'" x-text="viewBillDetails?.verified_date ? (new Date(viewBillDetails.verified_date).toLocaleDateString('en-GB')) : 'Verification Pending'"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border"
                                  :class="{
                                      'bg-emerald-50 text-emerald-700 border-emerald-200': viewBillDetails?.verified_date,
                                      'bg-amber-50 text-amber-800 border-amber-200': !viewBillDetails?.verified_date
                                  }"
                                  x-text="viewBillDetails?.verified_date ? 'Verified' : 'Submitted'"></span>
                        </div>
                    </div>
                </div>

                <!-- Financial Breakdown Card -->
                <div class="border border-[#a38c29]/30 rounded-xl overflow-hidden shadow-2xs">
                    <div class="bg-amber-50/50 px-3.5 py-2.5 flex items-center justify-between border-b border-[#a38c29]/20">
                        <span class="text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Financial Breakdown
                        </span>
                    </div>
                    <div class="p-3 bg-white grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Box 1: Approvals -->
                        <div class="border border-slate-200/90 rounded-lg p-2.5 bg-slate-50/30">
                            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Claim Details</h4>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-semibold text-slate-500">Gross Claimed:</span>
                                <span class="text-xs font-mono font-black text-slate-800" x-text="'₹ ' + numberFormat(viewBillDetails?.gross_amount || 0)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-semibold text-slate-500">Corrections:</span>
                                <span class="text-xs font-mono font-black text-rose-700" x-text="'-₹ ' + numberFormat(viewBillDetails?.correction_amount || 0)"></span>
                            </div>
                        </div>

                        <!-- Box 2: Payment -->
                        <div class="border border-[#a38c29]/20 rounded-lg p-2.5 bg-[#faf8f0]">
                            <h4 class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider mb-2 border-b border-[#a38c29]/10 pb-1.5">Final Payable</h4>
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-semibold text-slate-600">Additional Work:</span>
                                <span class="text-xs font-mono font-black text-slate-700" x-text="'+₹ ' + numberFormat(viewBillDetails?.additional_amount || 0)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-semibold text-slate-600">Net Payable:</span>
                                <span class="text-xs font-mono font-black text-blue-800" x-text="'₹ ' + numberFormat(viewBillDetails?.net_approved_amount || 0)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-1 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Clicking 'Go To Payment Desk' will redirect you.</p>
                    <a :href="'{{ route('expenses.ra-bills.payment-release') }}'" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-extrabold uppercase tracking-wider rounded-lg transition cursor-pointer shadow-md shadow-[#a38c29]/20">
                        <span>Go To Payment Desk</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ── MODAL 1: LOG NEW CONTRACTOR RA BILL ── -->
    <div x-show="addModalOpen" x-cloak @keydown.escape.window="if(addModalOpen) closeAddModal()" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 overflow-hidden">
        <div class="bg-white rounded-2xl w-full shadow-2xl overflow-hidden flex flex-col my-auto max-h-[94vh]" style="max-width: 920px; max-height: 94vh;" @click.away="closeAddModal()">
            {{-- Dark Header with Gold Glow (Fixed at Top) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 px-6 py-3.5 border-b border-[#a38c29]/30 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-0.5">Contractor RA Bills · New Progress Claim</p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight">Log New Contractor RA Progress Bill</h2>
                    </div>
                    <button type="button" @click="closeAddModal()" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form id="addRaBillForm" action="{{ route('expenses.ra-bills.store') }}" method="POST" @submit.prevent="if(submitAdd($event)) $el.submit()" novalidate style="display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; margin-bottom: 0; overflow: hidden;">
                @csrf

                <!-- Scrollable Body (Only scrolls if screen is very short, otherwise fits without scrollbar) -->
                <div class="p-5 space-y-3" style="overflow-y: auto; flex: 1 1 auto; min-height: 0;">
                    <!-- Row 1: Bill No, Submit Date, Due Date (3 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-start">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 {{ $errors->has('ra_bill_number') ? 'text-rose-600' : '' }}">
                                RA Bill No <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input type="text" name="ra_bill_number" x-model="addRaBillNumber" placeholder="e.g. 1 or RA-001" required
                                   class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs text-slate-900 border"
                                   :class="(hasAttemptedAddSubmit && !addRaBillNumber) || {{ $errors->has('ra_bill_number') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                            <p x-show="hasAttemptedAddSubmit && !addRaBillNumber" class="mt-1 text-[10px] font-bold text-rose-600">The RA bill number field is required.</p>
                            @error('ra_bill_number')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 {{ $errors->has('submit_date') ? 'text-rose-600' : '' }}">
                                Submit Date <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input type="date" name="submit_date" x-model="addSubmitDate" @click="$el.showPicker && $el.showPicker()" required
                                   class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs text-slate-900 border cursor-pointer bg-slate-50 hover:bg-white focus:bg-white"
                                   :class="(hasAttemptedAddSubmit && !addSubmitDate) || {{ $errors->has('submit_date') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                            <p x-show="hasAttemptedAddSubmit && !addSubmitDate" class="mt-1 text-[10px] font-bold text-rose-600">The submit date field is required.</p>
                            @error('submit_date')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                RA Bill Due Date
                            </label>
                            <input type="date" name="due_date" x-model="addDueDate" @click="$el.showPicker && $el.showPicker()"
                                   class="w-full h-[38px] px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs cursor-pointer">
                        </div>
                    </div>

                    <!-- Row 2: Contractor & Project (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 {{ $errors->has('contractor_id') ? 'text-rose-600' : '' }}">
                                Contractor Name <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <select name="contractor_id" x-model="selectedContractorId" required
                                    class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs cursor-pointer text-slate-900 border"
                                    :class="(hasAttemptedAddSubmit && !selectedContractorId) || {{ $errors->has('contractor_id') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                                <option value="">Select Contractor</option>
                                @foreach($contractors as $contractor)
                                    <option value="{{ $contractor->id }}" {{ (old('contractor_id') == $contractor->id || (empty(old('contractor_id')) && count($contractors) === 1)) ? 'selected' : '' }}>
                                        {{ $contractor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="hasAttemptedAddSubmit && !selectedContractorId" class="mt-1 text-[10px] font-bold text-rose-600">The contractor name field is required.</p>
                            @error('contractor_id')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 {{ $errors->has('project_id') ? 'text-rose-600' : '' }}">
                                Site Project <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <select name="project_id" x-model="selectedProjectId" @change="filterUnits()" required
                                    class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs cursor-pointer text-slate-900 border"
                                    :class="(hasAttemptedAddSubmit && !selectedProjectId) || {{ $errors->has('project_id') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                                <option value="">Select Project</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ (old('project_id') == $proj->id || (empty(old('project_id')) && count($projects) === 1)) ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="hasAttemptedAddSubmit && !selectedProjectId" class="mt-1 text-[10px] font-bold text-rose-600">The site project field is required.</p>
                            @error('project_id')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3: Gross Amount & Remarks (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1 {{ $errors->has('gross_amount') ? 'text-rose-600' : '' }}">
                                RA Bill Gross Amount (₹) <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-extrabold">₹</span>
                                <input type="number" step="0.01" name="gross_amount" x-model="addGrossAmount" placeholder="5000000" required
                                       class="w-full h-[38px] pl-7 pr-3 rounded-xl text-xs font-mono font-bold focus:outline-none transition-all shadow-2xs text-slate-900 border"
                                       :class="(hasAttemptedAddSubmit && (addGrossAmount === '' || addGrossAmount === null)) || {{ $errors->has('gross_amount') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                            </div>
                            <p x-show="hasAttemptedAddSubmit && (addGrossAmount === '' || addGrossAmount === null)" class="mt-1 text-[10px] font-bold text-rose-600">The RA bill gross amount field is required.</p>
                            @error('gross_amount')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Remarks / Notes
                            </label>
                            <input type="text" name="remarks" value="{{ old('remarks') }}" placeholder="Notes regarding progress work done..."
                                   class="w-full h-[38px] px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs">
                        </div>
                    </div>
                </div>

                <!-- Sticky / Default-Displayed Footer Buttons (ALWAYS 100% visible by default, never cut off) -->
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5 flex-shrink-0">
                    <button type="button" @click="closeAddModal()" class="px-5 py-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/30 border border-[#a38c29]/40 cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Save RA Bill</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── MODAL 2: SITE ENGINEER VERIFICATION & CORRECTIONS ── -->
    <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 overflow-hidden">
        <div class="bg-white rounded-2xl w-full shadow-2xl overflow-hidden flex flex-col my-auto max-h-[94vh]" style="max-width: 920px; max-height: 94vh;" @click.away="verifyModalOpen = false">
            {{-- Dark Header with Gold Glow (Fixed at Top) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-850 to-slate-800 px-6 py-3.5 border-b border-[#a38c29]/30 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-0.5">Contractor RA Bills · Final Verification</p>
                        <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight">Site Engineer Verification & Correction Sign-Off</h2>
                    </div>
                    <button type="button" @click="verifyModalOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="selectedBill ? '{{ url('expenses/ra-bills') }}/' + selectedBill.id + '/verify' : '#'" method="POST" @submit.prevent="if(submitVerify($event)) $el.submit()" novalidate style="display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; margin-bottom: 0; overflow: hidden;">
                @csrf

                <!-- Scrollable Body (Spacious & Free Layout) -->
                <div class="p-5 sm:p-6 space-y-3.5" style="overflow-y: auto; flex: 1 1 auto; min-height: 0;">

                    <!-- KPI Summary Bar (Polished 4-Col Card) -->
                    <div class="px-4 py-2.5 bg-slate-50/80 border border-slate-200/90 rounded-xl grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs items-center shadow-2xs">
                        <div class="sm:border-r border-slate-200 sm:pr-3">
                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">RA BILL NO.</span>
                            <div class="mt-0.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-extrabold bg-slate-200/70 text-slate-800 border border-slate-300/60" x-text="selectedBill ? selectedBill.ra_bill_number : '—'"></span>
                            </div>
                        </div>

                        <div class="border-l sm:border-l-0 sm:border-r border-slate-200 pl-3 sm:pl-0 sm:pr-3">
                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">GROSS CLAIMED</span>
                            <span class="text-xs font-mono font-extrabold text-slate-900 truncate block mt-0.5" x-text="selectedBill ? '₹ ' + numberFormat(selectedBill.gross_amount) : '₹ 0.00'"></span>
                        </div>

                        <div class="border-t sm:border-t-0 sm:border-r border-slate-200 pt-1 sm:pt-0 sm:pr-3">
                            <span class="block text-[9px] font-bold text-[#7a671b] uppercase tracking-wider">AFTER CORRECTION</span>
                            <span class="text-xs font-mono font-extrabold text-[#a38c29] truncate block mt-0.5" x-text="'₹ ' + numberFormat(calculatedAfterCorrection)"></span>
                        </div>

                        <div class="border-t sm:border-t-0 border-l sm:border-l-0 border-slate-200 pt-1 sm:pt-0 pl-3 sm:pl-0">
                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">STATUS</span>
                            <template x-if="selectedBill && selectedBill.status === 'cleared'">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Cleared</span>
                                </span>
                            </template>
                            <template x-if="selectedBill && selectedBill.status !== 'cleared' && selectedBill.verified_date">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300 inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Verified</span>
                                </span>
                            </template>
                            <template x-if="selectedBill && !selectedBill.verified_date">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 inline-flex items-center gap-1 shadow-2xs">
                                    <span>Submitted</span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <!-- Verification Already Done Banner (If verified) -->
                    <div x-show="selectedBill && selectedBill.verified_date" class="py-2 px-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs flex items-center justify-between shadow-2xs" style="display: none;">
                        <div class="flex items-center gap-2 text-emerald-800 font-extrabold text-[11px]">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>VERIFICATION COMPLETED</span>
                        </div>
                        <div class="text-slate-700 text-[11px] font-medium">
                            Verified By: <span class="font-bold text-slate-900" x-text="selectedBill ? (selectedBill.engineer_name || 'Engineer') : ''"></span>
                        </div>
                    </div>

                    <!-- Row 1: Verified Date, Site Engineer & Due Date (3 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-start">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('verified_date') ? 'text-rose-600' : 'text-slate-700' }}">
                                Verified Date <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <input type="date" name="verified_date" x-model="verifyDateInput" @click="$el.showPicker && $el.showPicker()" required
                                   class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs text-slate-900 border cursor-pointer bg-slate-50 hover:bg-white focus:bg-white"
                                   :class="(hasAttemptedVerifySubmit && !verifyDateInput) || {{ $errors->has('verified_date') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                            <p x-show="hasAttemptedVerifySubmit && !verifyDateInput" class="mt-1 text-[10px] font-bold text-rose-600">The verified date field is required.</p>
                            @error('verified_date')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('engineer_id') ? 'text-rose-600' : 'text-slate-700' }}">
                                Site Engineer <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <select name="engineer_id" x-model="selectedEngineerId" required
                                    class="w-full h-[38px] px-3.5 rounded-xl text-xs font-bold focus:outline-none transition-all shadow-2xs cursor-pointer text-slate-900 border"
                                    :class="(hasAttemptedVerifySubmit && !selectedEngineerId) || {{ $errors->has('engineer_id') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'">
                                <option value="">Select Verifying Engineer</option>
                                @foreach($engineers as $eng)
                                    <option value="{{ $eng->id }}" :selected="selectedEngineerId == {{ $eng->id }}">
                                        {{ $eng->name }} {{ $eng->designation ? '('.$eng->designation.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p x-show="hasAttemptedVerifySubmit && !selectedEngineerId" class="mt-1 text-[10px] font-bold text-rose-600">The engineer id field is required.</p>
                            @error('engineer_id')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                RA Bill Due Date
                            </label>
                            <input type="date" name="due_date" x-model="verifyDueDateInput" @click="$el.showPicker && $el.showPicker()"
                                   class="w-full h-[38px] px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs cursor-pointer">
                        </div>
                    </div>

                    <!-- Row 2: Correction of Bill & Amount After Correction (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-start">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider mb-1 {{ $errors->has('correction_amount') ? 'text-rose-600' : 'text-slate-700' }}">
                                Correction of Bill (Deduction ₹) <span class="text-rose-500 font-bold">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-extrabold">₹</span>
                                <input type="number" step="0.01" name="correction_amount" x-model="correctionInput" @input="recalcVerification()" required
                                       class="w-full h-[38px] pl-7 pr-3 rounded-xl text-xs font-mono font-bold focus:outline-none transition-all shadow-2xs text-slate-900 border"
                                       :class="(hasAttemptedVerifySubmit && (correctionInput === '' || correctionInput === null)) || {{ $errors->has('correction_amount') ? 'true' : 'false' }} ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'bg-slate-50 hover:bg-white focus:bg-white border-slate-200 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]'"
                                       placeholder="0.00">
                            </div>
                            <p x-show="hasAttemptedVerifySubmit && (correctionInput === '' || correctionInput === null)" class="mt-1 text-[10px] font-bold text-rose-600">The correction amount field is required.</p>
                            @error('correction_amount')
                                <p class="mt-1 text-[10px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-0.5 text-[9.5px] font-medium text-slate-400 h-3.5 flex items-center" x-text="selectedBill ? 'Max Deduction: ₹ ' + numberFormat(selectedBill.gross_amount) : ''"></p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Amount After Correction (₹)
                            </label>
                            <div class="w-full h-[38px] pl-7 pr-3 bg-slate-100/90 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 flex items-center shadow-2xs relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-extrabold">₹</span>
                                <span x-text="numberFormat(calculatedAfterCorrection)"></span>
                            </div>
                            <p class="mt-0.5 text-[9.5px] font-medium text-slate-400 h-3.5 flex items-center">Gross Claimed − Correction Deduction</p>
                            <div x-show="calculatedAfterCorrection > 0" class="text-[10px] text-amber-800 font-extrabold capitalize mt-1.5 px-2.5 py-1 rounded-lg bg-amber-50/90 border border-amber-200/80 tracking-wide transition-all leading-snug break-words block w-full shadow-xs" x-text="window.convertNumberToWords ? window.convertNumberToWords(calculatedAfterCorrection) : ''"></div>
                        </div>
                    </div>

                    <!-- Row 3: Additional Work (%), Additional Work (₹) & Net RA Payable (3 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-start">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Additional Percentage (%)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" name="additional_percentage" x-model="verifyAdditionalPercent" @input="calcAdditionalFromPercent()" placeholder="0.00"
                                       data-no-words="true"
                                       class="w-full h-[38px] pl-3 pr-7 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs">
                                <span class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400 text-xs font-extrabold">%</span>
                            </div>
                            <p class="mt-0.5 text-[9.5px] font-medium text-slate-400 h-3.5 flex items-center truncate" x-text="verifyAdditionalPercent ? verifyAdditionalPercent + '% of ₹ ' + numberFormat(calculatedAfterCorrection) : 'Applied on After-Correction base'"></p>
                            <div class="text-[10px] text-slate-700 font-bold mt-1.5 px-2.5 py-1 rounded-lg bg-slate-100/90 border border-slate-250 tracking-wide transition-all leading-snug flex items-center justify-between shadow-xs">
                                <span class="text-slate-500 font-medium text-[9px] uppercase tracking-wider">Markup Share:</span>
                                <span class="font-mono font-extrabold text-[#8a7522]" x-text="verifyAdditionalPercent ? '+' + verifyAdditionalPercent + '%' : '0.00%'"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Additional Amount (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-extrabold">₹</span>
                                <input type="number" step="0.01" name="additional_amount" x-model="verifyAdditionalAmount" @input="calcPercentFromAdditional()" placeholder="0.00"
                                       class="w-full h-[38px] pl-7 pr-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs">
                            </div>
                            <p class="mt-0.5 text-[9.5px] font-medium text-slate-400 h-3.5 flex items-center">Added to After-Correction base</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Net RA Payable (Final Claim)
                            </label>

                            <div class="w-full h-[38px] pl-7 pr-3 bg-gradient-to-r from-amber-50/60 via-white to-amber-50/40 border-2 border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-900 flex items-center justify-between shadow-2xs relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#a38c29] text-xs font-extrabold">₹</span>
                                <span class="text-sm sm:text-[15px] font-mono font-black text-slate-900" x-text="numberFormat(calculatedNet)"></span>
                                <span class="px-2 py-0.5 rounded text-[8.5px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 shadow-2xs flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 text-emerald-600 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Approved
                                </span>
                            </div>

                            <p class="mt-0.5 text-[9.5px] font-medium text-slate-400 h-3.5 flex items-center">After Corr. + Add. Work</p>

                            {{-- Matched Amber Words Chip (Identical to other columns & modal create) --}}
                            <div x-show="calculatedNet > 0" class="text-[10px] text-amber-800 font-extrabold capitalize mt-1.5 px-2.5 py-1 rounded-lg bg-amber-50/90 border border-amber-200/80 tracking-wide transition-all leading-snug break-words block w-full shadow-xs" x-text="window.convertNumberToWords ? window.convertNumberToWords(calculatedNet) : ''"></div>
                        </div>
                    </div>

                    <!-- Row 4: Remarks (1 col) -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Verification Remarks
                        </label>
                        <input type="text" name="remarks" x-model="verifyRemarksInput" placeholder="Details of measurements checked, corrections or retentions applied..."
                               class="w-full h-[36px] px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition shadow-2xs">
                    </div>

                </div>

                <!-- Sticky / Default-Displayed Footer Buttons (ALWAYS 100% visible by default, never cut off) -->
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5 flex-shrink-0">
                    <button type="button" @click="verifyModalOpen = false" class="px-5 py-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/30 border border-[#a38c29]/40 cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="selectedBill && selectedBill.verified_date ? 'UPDATE VERIFICATION SIGN-OFF' : 'CONFIRM SIGN-OFF'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>



</div>

<script>
function raBillVerification() {
    const defaultProjectId = '{{ $defaultProjectId }}';
    return {
        filterContractorId: '',
        filterProjectId: defaultProjectId,
        filterStatus: '',
        allBills: [
            @foreach($raBills as $bill)
            {
                contractor_id: '{{ $bill->contractor_id }}',
                project_id: '{{ $bill->project_id }}',
                status: '{{ $bill->verified_date ? "verified" : "submitted" }}',
            },
            @endforeach
        ],

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

        getVisibleCount() {
            return this.allBills.filter(b => {
                if (this.filterContractorId && String(b.contractor_id) !== String(this.filterContractorId)) {
                    return false;
                }
                if (this.filterProjectId && String(b.project_id) !== String(this.filterProjectId)) {
                    return false;
                }
                if (this.filterStatus && b.status !== this.filterStatus) {
                    return false;
                }
                return true;
            }).length;
        },

        searchQuery: '',
        addModalOpen: {{ (!session('success') && (request('new') == '1' || request('create') == '1' || request('action') == 'new' || $errors->has('ra_bill_number') || $errors->has('contractor_id') || $errors->has('gross_amount'))) ? 'true' : 'false' }},
        verifyModalOpen: false,
        viewModalOpen: false,
        viewBillDetails: null,
        openViewModal(bill) {
            this.viewBillDetails = bill;
            this.viewModalOpen = true;
        },
        hasAttemptedVerifySubmit: false,
        selectedBill: null,
        correctionInput: 0,
        calculatedAfterCorrection: 0,
        verifyAdditionalPercent: '',
        verifyAdditionalAmount: '0.00',
        calculatedNet: 0,
        allContractors: @json($contractors),
        allProjects: @json($projects),
        selectedContractorId: '{{ old('contractor_id') }}',
        selectedProjectId: '{{ old('project_id') }}',
        selectedUnitId: '{{ old('unit_id') }}',
        allUnits: @json($units),
        availableUnits: [],
        allEngineers: @json($engineers),
        selectedEngineerId: '',
        addRaBillNumber: '{{ old('ra_bill_number') }}',
        addSubmitDate: '{{ old('submit_date', date('Y-m-d')) }}',
        addDueDate: '{{ old('due_date', '') }}',
        addGrossAmount: '{{ old('gross_amount') }}',
        hasAttemptedAddSubmit: false,
        verifyDateInput: '{{ date("Y-m-d") }}',
        verifyRemarksInput: '',
        verifyDueDateInput: '',

        // Custom Datepicker state (Reference UI design)
        datePickerOpen: false,
        datePickerTarget: '',
        datePickerTitle: 'Select Date',
        datePickerSelectedDate: null,
        datePickerViewYear: new Date().getFullYear(),
        datePickerViewMonth: new Date().getMonth(),
        datePickerActiveQuickSelect: '',
        datePickerShowMonthYearDropdown: false,
        datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datePickerMonthShortNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datePickerDayNames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

        openCustomDatePicker(targetField, title = 'Select Date') {
            this.datePickerTarget = targetField;
            this.datePickerTitle = title;
            this.datePickerShowMonthYearDropdown = false;
            let currentVal = this[targetField];
            if (currentVal && /^\d{4}-\d{2}-\d{2}$/.test(currentVal)) {
                this.datePickerSelectedDate = currentVal;
                const parts = currentVal.split('-').map(Number);
                this.datePickerViewYear = parts[0];
                this.datePickerViewMonth = parts[1] - 1;
            } else {
                const now = new Date();
                const yr = now.getFullYear();
                const mo = String(now.getMonth() + 1).padStart(2, '0');
                const da = String(now.getDate()).padStart(2, '0');
                this.datePickerSelectedDate = `${yr}-${mo}-${da}`;
                this.datePickerViewYear = yr;
                this.datePickerViewMonth = now.getMonth();
            }
            this.detectActiveQuickSelect();
            this.datePickerOpen = true;
        },

        closeDatePicker() {
            this.datePickerOpen = false;
            this.datePickerShowMonthYearDropdown = false;
        },

        datePickerConfirm() {
            if (this.datePickerTarget && this.datePickerSelectedDate) {
                this[this.datePickerTarget] = this.datePickerSelectedDate;
            }
            this.closeDatePicker();
        },

        datePickerPrevMonth() {
            if (this.datePickerViewMonth === 0) {
                this.datePickerViewMonth = 11;
                this.datePickerViewYear--;
            } else {
                this.datePickerViewMonth--;
            }
        },

        datePickerNextMonth() {
            if (this.datePickerViewMonth === 11) {
                this.datePickerViewMonth = 0;
                this.datePickerViewYear++;
            } else {
                this.datePickerViewMonth++;
            }
        },

        datePickerGetDaysInMonth() {
            const count = new Date(this.datePickerViewYear, this.datePickerViewMonth + 1, 0).getDate();
            const arr = [];
            for (let i = 1; i <= count; i++) arr.push(i);
            return arr;
        },

        datePickerGetLeadingBlanks() {
            const firstDay = new Date(this.datePickerViewYear, this.datePickerViewMonth, 1).getDay();
            const arr = [];
            for (let i = 0; i < firstDay; i++) arr.push(i);
            return arr;
        },

        datePickerIsSelected(day) {
            if (!this.datePickerSelectedDate) return false;
            const parts = this.datePickerSelectedDate.split('-').map(Number);
            return parts[0] === this.datePickerViewYear && (parts[1] - 1) === this.datePickerViewMonth && parts[2] === day;
        },

        datePickerIsToday(day) {
            const now = new Date();
            return now.getFullYear() === this.datePickerViewYear && now.getMonth() === this.datePickerViewMonth && now.getDate() === day;
        },

        datePickerSelectDay(day) {
            const m = String(this.datePickerViewMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            this.datePickerSelectedDate = `${this.datePickerViewYear}-${m}-${d}`;
            this.detectActiveQuickSelect();
        },

        applyQuickSelect(type) {
            this.datePickerActiveQuickSelect = type;
            const now = new Date();
            let targetDate = new Date();
            if (type === 'today') {
                targetDate = new Date();
            } else if (type === 'yesterday') {
                targetDate.setDate(targetDate.getDate() - 1);
            } else if (type === 'this_month') {
                targetDate.setDate(1);
            } else if (type === 'last_month') {
                targetDate.setMonth(targetDate.getMonth() - 1);
                targetDate.setDate(1);
            } else if (type === 'this_year') {
                targetDate.setMonth(0);
                targetDate.setDate(1);
            } else if (type === 'last_year') {
                targetDate.setFullYear(targetDate.getFullYear() - 1);
                targetDate.setMonth(0);
                targetDate.setDate(1);
            }

            const yr = targetDate.getFullYear();
            const mo = String(targetDate.getMonth() + 1).padStart(2, '0');
            const da = String(targetDate.getDate()).padStart(2, '0');
            this.datePickerSelectedDate = `${yr}-${mo}-${da}`;
            this.datePickerViewYear = yr;
            this.datePickerViewMonth = targetDate.getMonth();
        },

        detectActiveQuickSelect() {
            if (!this.datePickerSelectedDate) {
                this.datePickerActiveQuickSelect = '';
                return;
            }
            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
            const yDate = new Date();
            yDate.setDate(yDate.getDate() - 1);
            const yesterdayStr = `${yDate.getFullYear()}-${String(yDate.getMonth()+1).padStart(2,'0')}-${String(yDate.getDate()).padStart(2,'0')}`;
            const thisMonthFirstStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-01`;
            const lastMonthDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            const lastMonthFirstStr = `${lastMonthDate.getFullYear()}-${String(lastMonthDate.getMonth()+1).padStart(2,'0')}-01`;
            const thisYearFirstStr = `${now.getFullYear()}-01-01`;
            const lastYearFirstStr = `${now.getFullYear()-1}-01-01`;

            if (this.datePickerSelectedDate === todayStr) {
                this.datePickerActiveQuickSelect = 'today';
            } else if (this.datePickerSelectedDate === yesterdayStr) {
                this.datePickerActiveQuickSelect = 'yesterday';
            } else if (this.datePickerSelectedDate === thisMonthFirstStr) {
                this.datePickerActiveQuickSelect = 'this_month';
            } else if (this.datePickerSelectedDate === lastMonthFirstStr) {
                this.datePickerActiveQuickSelect = 'last_month';
            } else if (this.datePickerSelectedDate === thisYearFirstStr) {
                this.datePickerActiveQuickSelect = 'this_year';
            } else if (this.datePickerSelectedDate === lastYearFirstStr) {
                this.datePickerActiveQuickSelect = 'last_year';
            } else {
                this.datePickerActiveQuickSelect = '';
            }
        },

        get datePickerFormattedPreview() {
            if (!this.datePickerSelectedDate) return 'No date selected';
            const parts = this.datePickerSelectedDate.split('-').map(Number);
            if (parts.length !== 3) return this.datePickerSelectedDate;
            const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
            const dayName = this.datePickerDayNames[dateObj.getDay()];
            const dayNum = String(parts[2]).padStart(2, '0');
            const monthName = this.datePickerMonthShortNames[parts[1] - 1];
            return `${dayName}, ${dayNum} ${monthName} ${parts[0]}`;
        },

        openAddModal() {
            this.hasAttemptedAddSubmit = false;
            this.resetAddModal();
            this.addModalOpen = true;
            window.dispatchEvent(new CustomEvent('ra-bill-modal-state', { detail: { isOpen: true } }));
        },

        closeAddModal() {
            this.addModalOpen = false;
            this.hasAttemptedAddSubmit = false;
            this.resetAddModal();
            window.dispatchEvent(new CustomEvent('ra-bill-modal-state', { detail: { isOpen: false } }));
        },

        resetAddModal() {
            this.addRaBillNumber = '';
            this.addSubmitDate = '{{ date("Y-m-d") }}';
            this.addDueDate = '';
            this.addGrossAmount = '';
            this.hasAttemptedAddSubmit = false;
            const form = document.getElementById('addRaBillForm');
            if (form) {
                const words = form.querySelector('.amount-in-words-label');
                if (words) {
                    words.textContent = '';
                    words.style.display = 'none';
                }
            }
        },

        updateVerifyWords() {
            this.$nextTick(() => {
                const corrEl = document.querySelector('input[name="correction_amount"]');
                if (corrEl && typeof window.updateAmountInWordsForInput === 'function') {
                    window.updateAmountInWordsForInput(corrEl);
                }
                const addEl = document.querySelector('input[name="additional_amount"]');
                if (addEl && typeof window.updateAmountInWordsForInput === 'function') {
                    window.updateAmountInWordsForInput(addEl);
                }
            });
        },

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            if (!{{ session('success') ? 'true' : 'false' }} && (urlParams.get('new') === '1' || urlParams.get('create') === '1' || urlParams.get('action') === 'new')) {
                this.$nextTick(() => {
                    this.openAddModal();
                });
            }

            if (this.addModalOpen) {
                window.dispatchEvent(new CustomEvent('ra-bill-modal-state', { detail: { isOpen: true } }));
            }

            this.$watch('addModalOpen', (val) => {
                window.dispatchEvent(new CustomEvent('ra-bill-modal-state', { detail: { isOpen: !!val } }));
                if (!val) {
                    this.resetAddModal();
                    const url = new URL(window.location);
                    if (url.searchParams.has('new') || url.searchParams.has('create') || url.searchParams.has('action')) {
                        url.searchParams.delete('new');
                        url.searchParams.delete('create');
                        url.searchParams.delete('action');
                        window.history.replaceState({}, '', url.pathname + (url.search ? url.search : ''));
                    }
                } else {
                    const url = new URL(window.location);
                    if (!url.searchParams.has('new')) {
                        url.searchParams.set('new', '1');
                        window.history.replaceState({}, '', url.pathname + (url.search ? url.search : ''));
                    }
                }
            });
            this.$watch('verifyModalOpen', (val) => {
                if (val) {
                    this.updateVerifyWords();
                } else {
                    const corrWords = document.querySelector('input[name="correction_amount"]')?.closest('.relative')?.parentElement?.querySelector('.amount-in-words-label');
                    if (corrWords) corrWords.style.display = 'none';
                    const addWords = document.querySelector('input[name="additional_amount"]')?.closest('.relative')?.parentElement?.querySelector('.amount-in-words-label');
                    if (addWords) addWords.style.display = 'none';
                }
            });
            if (!this.selectedContractorId && this.allContractors && this.allContractors.length === 1) {
                this.selectedContractorId = String(this.allContractors[0].id);
            }
            if (!this.selectedProjectId && this.allProjects && this.allProjects.length === 1) {
                this.selectedProjectId = String(this.allProjects[0].id);
            }
            this.filterUnits();
        },

        filterUnits() {
            if (!this.selectedProjectId) {
                this.availableUnits = this.allUnits;
            } else {
                this.availableUnits = this.allUnits.filter(u => u.project_id == this.selectedProjectId);
            }
            if (!this.selectedUnitId && this.availableUnits && this.availableUnits.length === 1) {
                this.selectedUnitId = String(this.availableUnits[0].id);
            }
        },

        submitVerify(e) {
            this.hasAttemptedVerifySubmit = true;
            if (!this.verifyDateInput || !this.selectedEngineerId || this.correctionInput === '' || this.correctionInput === null) {
                e.preventDefault();
                return false;
            }
            return true;
        },

        submitAdd(e) {
            this.hasAttemptedAddSubmit = true;
            if (!this.addRaBillNumber || !this.addSubmitDate || !this.selectedContractorId || !this.selectedProjectId || this.addGrossAmount === '' || this.addGrossAmount === null) {
                e.preventDefault();
                return false;
            }
            return true;
        },

        openVerifyModal(bill) {
            this.selectedBill = bill;
            this.hasAttemptedVerifySubmit = false;
            const gross = parseFloat(bill.gross_amount) || 0;
            const corr = parseFloat(bill.correction_amount) || 0;
            this.correctionInput = corr;
            this.calculatedAfterCorrection = Math.max(0, gross - corr);

            const addAmt = parseFloat(bill.additional_amount) || 0;
            const addPct = parseFloat(bill.additional_percentage) || 0;
            this.verifyAdditionalAmount = addAmt > 0 ? addAmt.toFixed(2) : '0.00';

            if (addPct > 0) {
                this.verifyAdditionalPercent = (addPct == parseInt(addPct)) ? parseInt(addPct) : addPct;
            } else if (addAmt > 0 && this.calculatedAfterCorrection > 0) {
                const computedPct = (addAmt / this.calculatedAfterCorrection) * 100;
                this.verifyAdditionalPercent = (computedPct == parseInt(computedPct)) ? parseInt(computedPct) : computedPct.toFixed(2);
            } else {
                this.verifyAdditionalPercent = '';
            }

            this.calculatedNet = Math.max(0, this.calculatedAfterCorrection + (parseFloat(this.verifyAdditionalAmount) || 0));
            this.verifyRemarksInput = bill.remarks || '';
            this.verifyDueDateInput = bill.due_date ? String(bill.due_date).substring(0, 10) : '';

            if (bill.verified_date) {
                this.verifyDateInput = String(bill.verified_date).substring(0, 10);
            } else {
                this.verifyDateInput = '{{ date("Y-m-d") }}';
            }

            let matchedEng = this.allEngineers.find(e => bill.engineer_name && bill.engineer_name.toLowerCase().includes(e.name.toLowerCase()));
            this.selectedEngineerId = matchedEng ? matchedEng.id : (bill.engineer_id || '');

            this.verifyModalOpen = true;
            this.updateVerifyWords();
        },

        recalcVerification() {
            if (!this.selectedBill) return;
            const gross = parseFloat(this.selectedBill.gross_amount) || 0;
            let corr = parseFloat(this.correctionInput) || 0;
            if (corr < 0) {
                corr = 0;
                this.correctionInput = 0;
            }
            if (corr > gross) {
                corr = gross;
                this.correctionInput = gross;
            }
            this.calculatedAfterCorrection = Math.max(0, gross - corr);

            // Recalculate additional amount with respect to new after-correction base
            const pct = parseFloat(this.verifyAdditionalPercent);
            if (!isNaN(pct) && pct > 0 && this.calculatedAfterCorrection > 0) {
                this.verifyAdditionalAmount = ((this.calculatedAfterCorrection * pct) / 100).toFixed(2);
            } else if (parseFloat(this.verifyAdditionalAmount) > 0 && this.calculatedAfterCorrection > 0) {
                const computedPct = (parseFloat(this.verifyAdditionalAmount) / this.calculatedAfterCorrection) * 100;
                this.verifyAdditionalPercent = (computedPct == parseInt(computedPct)) ? parseInt(computedPct) : computedPct.toFixed(2);
            }

            const addAmt = parseFloat(this.verifyAdditionalAmount) || 0;
            this.calculatedNet = Math.max(0, this.calculatedAfterCorrection + addAmt);
            this.updateVerifyWords();
        },

        calcAdditionalFromPercent() {
            const base = parseFloat(this.calculatedAfterCorrection) || 0;
            const pct = parseFloat(this.verifyAdditionalPercent);
            if (!isNaN(pct) && pct >= 0 && base > 0) {
                this.verifyAdditionalAmount = ((base * pct) / 100).toFixed(2);
            } else if (isNaN(pct) || pct === 0) {
                this.verifyAdditionalAmount = '0.00';
            }
            const addAmt = parseFloat(this.verifyAdditionalAmount) || 0;
            this.calculatedNet = Math.max(0, base + addAmt);
            this.updateVerifyWords();
        },

        calcPercentFromAdditional() {
            const base = parseFloat(this.calculatedAfterCorrection) || 0;
            const amt = parseFloat(this.verifyAdditionalAmount);
            if (!isNaN(amt) && amt > 0 && base > 0) {
                const computedPct = (amt / base) * 100;
                this.verifyAdditionalPercent = (computedPct == parseInt(computedPct)) ? parseInt(computedPct) : computedPct.toFixed(2);
            } else {
                this.verifyAdditionalPercent = '';
            }
            const addAmt = parseFloat(this.verifyAdditionalAmount) || 0;
            this.calculatedNet = Math.max(0, base + addAmt);
            this.updateVerifyWords();
        },

        calcPercentage(additional, gross) {
            const add = parseFloat(additional) || 0;
            const g = parseFloat(gross) || 0;
            if (g <= 0 || add <= 0) return '0';
            const pct = Math.round((add / g) * 1000) / 10;
            return pct % 1 === 0 ? pct.toFixed(0) : pct.toFixed(1);
        },

        numberFormat(val) {
            return (parseFloat(val) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        exportExcel(theme = 'gold') {
            const tableId = theme === 'classic' ? '#raBillsExcelTable' : '#raBillsExcelTableGold';
            const table = document.querySelector(tableId);
            if (!table) {
                alert("No data available to export.");
                return;
            }

            // Determine active selected project name for header title
            let activeProjName = '';
            if (this.filterProjectId) {
                const projObj = (this.allProjects || []).find(p => String(p.id) === String(this.filterProjectId));
                if (projObj && projObj.name) {
                    activeProjName = projObj.name.toUpperCase();
                }
            }
            if (!activeProjName) {
                const projSelect = document.querySelector('select[x-model="filterProjectId"]');
                if (projSelect && projSelect.selectedIndex >= 0) {
                    const opt = projSelect.options[projSelect.selectedIndex];
                    if (opt && opt.value && opt.text && opt.text.trim().toLowerCase() !== 'all projects') {
                        activeProjName = opt.text.trim().toUpperCase();
                    }
                }
            }
            if (!activeProjName && this.allProjects && this.allProjects.length > 0) {
                activeProjName = (this.allProjects[0].name || '').toUpperCase();
            }
            if (!activeProjName) {
                activeProjName = 'SITE PROJECT';
            }

            const headerTitleText = activeProjName + ' - CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER';
            const filename = 'ra-bills-verification-report.xlsx';

            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            const sheetName = 'RA Bills Verification';
            const worksheet = workbook.addWorksheet(sheetName);

            const frozenRow = theme === 'classic' ? 3 : 7;
            worksheet.views = [{ state: 'frozen', xSplit: 2, ySplit: frozenRow, activePane: 'bottomRight' }];
            worksheet.pageSetup = {
                paperSize: 9, // A4 landscape
                orientation: 'landscape',
                fitToPage: true,
                fitToWidth: 1,
                fitToHeight: 0
            };
            worksheet.pageSetup.printTitles = theme === 'classic' ? '1:3' : '5:7';

            worksheet.columns = [
                { width: 8 },  // SL NO
                { width: 16 }, // RA BILL NO
                { width: 40 }, // CONTRACTOR NAME
                { width: 40 }, // SITE PROJECT
                { width: 16 }, // SUBMIT DATE
                { width: 22 }, // RA BILL AMOUNT (₹)
                { width: 24 }, // CORRECTION / DEDUCTION (₹)
                { width: 24 }, // AFTER CORRECTION (₹)
                { width: 22 }, // ADDITIONAL WORK (₹)
                { width: 26 }, // NET RA PAYABLE (₹)
                { width: 16 }, // DUE DATE
                { width: 16 }, // VERIFIED DATE
                { width: 32 }, // VERIFYING ENGINEER
                { width: 18 }  // STATUS
            ];

            function cssColorToHex(cssColor) {
                if (!cssColor) return null;
                cssColor = cssColor.trim();
                if (cssColor.startsWith('#')) {
                    let hex = cssColor.substring(1);
                    if (hex.length === 3) {
                        hex = hex.split('').map(c => c + c).join('');
                    }
                    return 'FF' + hex.toUpperCase();
                }
                if (cssColor.startsWith('rgb')) {
                    const parts = cssColor.match(/\d+/g);
                    if (parts && parts.length >= 3) {
                        const r = parseInt(parts[0]).toString(16).padStart(2, '0');
                        const g = parseInt(parts[1]).toString(16).padStart(2, '0');
                        const b = parseInt(parts[2]).toString(16).padStart(2, '0');
                        return 'FF' + (r + g + b).toUpperCase();
                    }
                }
                const nameMap = {
                    'white': 'FFFFFFFF',
                    'black': 'FF000000',
                    'red': 'FFFF0000',
                    'green': 'FF00FF00',
                    'blue': 'FF0000FF'
                };
                return nameMap[cssColor.toLowerCase()] || null;
            }

            const rows = table.querySelectorAll("tr");
            const mergedCells = [];

            function isMerged(r, c) {
                return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
            }

            rows.forEach((tr, rIdx) => {
                const sheetRow = worksheet.getRow(rIdx + 1);
                
                const heightAttr = tr.getAttribute("height") || tr.style.height;
                if (heightAttr) {
                    const match = heightAttr.match(/[\d\.]+/);
                    if (match) {
                        sheetRow.height = Math.max(parseFloat(match[0]), 26);
                    }
                } else {
                    sheetRow.height = 26;
                }

                const cells = tr.cells;
                let colIdx = 1;

                for (let cIdx = 0; cIdx < cells.length; cIdx++) {
                    const cell = cells[cIdx];

                    while (isMerged(rIdx + 1, colIdx)) {
                        colIdx++;
                    }

                    const colspan = parseInt(cell.getAttribute("colspan")) || 1;
                    const rowspan = parseInt(cell.getAttribute("rowspan")) || 1;

                    if (colspan > 1 || rowspan > 1) {
                        worksheet.mergeCells(rIdx + 1, colIdx, rIdx + rowspan, colIdx + colspan - 1);
                        mergedCells.push({
                            s: { r: rIdx + 1, c: colIdx },
                            e: { r: rIdx + rowspan, c: colIdx + colspan - 1 }
                        });
                    }

                    const excelCell = worksheet.getCell(rIdx + 1, colIdx);
                    let rawVal = cell.textContent ? cell.textContent.trim() : '';
                    const isTitleHeader = rawVal.includes('CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER');

                    if (isTitleHeader) {
                        rawVal = headerTitleText;
                    }

                    let bgColorAttr = cell.getAttribute("bgcolor") || cell.style.backgroundColor;
                    if (isTitleHeader) {
                        bgColorAttr = '#17365D'; // Deep Blue matching Contractor Bill Info
                    }
                    let bgColorHex = cssColorToHex(bgColorAttr);
                    
                    let textColorAttr = cell.style.color;
                    if (isTitleHeader) {
                        textColorAttr = '#FFFFFF';
                    }
                    const textColorHex = cssColorToHex(textColorAttr) || 'FF000000';

                    const isBold = cell.tagName === 'TH' || cell.style.fontWeight === 'bold' || (cell.style.fontWeight && parseInt(cell.style.fontWeight) >= 700) || (rIdx === rows.length - 1);
                    const fontSizeMatch = (cell.style.fontSize || '').match(/[\d\.]+/);
                    const isFooterCell = (rIdx === rows.length - 1) || (cell.parentElement && cell.parentElement.rowIndex === rows.length - 1);
                    const fontSize = isTitleHeader ? 14 : (isFooterCell ? 12 : (fontSizeMatch ? parseFloat(fontSizeMatch[0]) : 10));

                    let horizAlign = cell.style.textAlign || (cell.tagName === 'TH' ? 'center' : 'left');
                    if (horizAlign === 'start') horizAlign = 'left';
                    if (horizAlign === 'end') horizAlign = 'right';

                    let vertAlign = cell.style.verticalAlign || 'middle';
                    const isCurrency = cell.getAttribute("data-format") === "currency" || (cell.style.msoNumberFormat && cell.style.msoNumberFormat.includes('#,##0'));
                    const isDate = cell.getAttribute("data-format") === "date" || (cell.style.msoNumberFormat && cell.style.msoNumberFormat.includes('dd-mmm-yyyy'));
                    
                    // Parse values and apply clean formatting
                    if (isDate || (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal))) {
                        if (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal)) {
                            const [yyyy, mm, dd] = rawVal.split('-');
                            excelCell.value = new Date(parseInt(yyyy), parseInt(mm) - 1, parseInt(dd));
                        } else {
                            excelCell.value = rawVal;
                        }
                        excelCell.numFormat = 'DD-MMM-YYYY';
                    } else if (isCurrency || (rawVal && /^[\-₹\s\d\,\.]+\.?\d*$/.test(rawVal) && cell.tagName !== 'TH')) {
                        const cleanVal = rawVal.replace(/[^\d\.\-]/g, '');
                        const parsedNum = parseFloat(cleanVal);
                        if (rawVal && !isNaN(parsedNum)) {
                            excelCell.value = parsedNum;
                        } else {
                            excelCell.value = 0;
                        }
                        excelCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                    } else {
                        if (rawVal && /^\-?\d+(\.\d+)?$/.test(rawVal) && !cell.getAttribute("colspan")) {
                            excelCell.value = parseFloat(rawVal);
                        } else {
                            excelCell.value = rawVal;
                        }
                    }

                    excelCell.font = {
                        name: 'Calibri',
                        size: fontSize,
                        bold: isBold,
                        color: { argb: textColorHex }
                    };

                    if (bgColorHex) {
                        excelCell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: { argb: bgColorHex }
                        };
                    }

                    excelCell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle',
                        wrapText: true
                    };

                    excelCell.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };

                    colIdx += colspan;
                }
            });

            workbook.xlsx.writeBuffer().then(function (data) {
                const blob = new Blob([data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement("a");
                anchor.href = url;
                anchor.download = filename;
                anchor.click();
                window.URL.revokeObjectURL(url);
            });
        }
    };
}
</script>

<div class="hidden" style="display: none;">
    <!-- ── EXCEL DESIGN OPTION 1: EXECUTIVE LUXURY GOLD & CHARCOAL THEME (WITH KPIS & SEPARATE VERIFIER COLS) ── -->
    <table id="raBillsExcelTableGold" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="55" style="width: 40pt;" />
            <col width="105" style="width: 80pt;" />
            <col width="230" style="width: 175pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="155" style="width: 115pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="175" style="width: 135pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="190" style="width: 145pt;" />
            <col width="115" style="width: 85pt;" />
        </colgroup>
        <thead>
            <tr height="24" style="height: 24pt;">
                <th colspan="13" bgcolor="#1e293b" style="background-color: #1e293b; color: #f59e0b; font-weight: bold; font-size: 10pt; text-align: left; padding-left: 12px; vertical-align: middle; border: 1px solid #334155; font-family: 'Calibri', 'Aptos', sans-serif;">
                    EXECUTIVE SUMMARY KPI METRICS & AUDIT SIGN-OFF DESK
                </th>
            </tr>
            <tr height="36" style="height: 36pt;">
                <td colspan="3" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #0f172a; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #cbd5e1; font-family: 'Calibri', 'Aptos', sans-serif;">
                    TOTAL RA CLAIMED: ₹{{ number_format((float)$totalGross, 2) }}
                </td>
                <td colspan="3" bgcolor="#fef2f2" style="background-color: #fef2f2; color: #991b1b; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #fca5a5; font-family: 'Calibri', 'Aptos', sans-serif;">
                    ENGINEER DEDUCTIONS: -₹{{ number_format((float)$totalCorrections, 2) }}
                </td>
                <td colspan="3" bgcolor="#eff6ff" style="background-color: #eff6ff; color: #1e3a8a; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #93c5fd; font-family: 'Calibri', 'Aptos', sans-serif;">
                    NET APPROVED LIABILITIES: ₹{{ number_format((float)$totalNetApproved, 2) }}
                </td>
                <td colspan="4" bgcolor="#f0fdf4" style="background-color: #f0fdf4; color: #166534; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1.5pt solid #86efac; font-family: 'Calibri', 'Aptos', sans-serif;">
                    VERIFICATION SIGN-OFFS: {{ $raBills->whereNotNull('verified_date')->count() }} / {{ $raBills->count() }} Completed
                </td>
            </tr>
            <tr height="14" style="height: 14pt;">
                <td colspan="14" style="border: none;"></td>
            </tr>
            <tr height="46" style="height: 46pt;">
                <th colspan="14" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    @php
                        $projTitle = 'SITE PROJECT';
                        if (request('project_id')) {
                            $p = $filterProjects->firstWhere('id', request('project_id'));
                            if ($p && $p->name) $projTitle = strtoupper($p->name);
                        } elseif (isset($filterProjects) && $filterProjects->first() && $filterProjects->first()->name) {
                            $projTitle = strtoupper($filterProjects->first()->name);
                        }
                    @endphp
                    {{ $projTitle }} - CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="5" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. CONTRACTOR & RA BILL IDENTIFICATION</th>
                <th colspan="5" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. INWARD FINANCIAL CLAIMS & ENGINEER DEDUCTIONS</th>
                <th colspan="4" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. SITE ENGINEER VERIFICATION & AUDIT SIGN-OFF</th>
            </tr>
            <tr height="40" style="height: 40pt;">
                <th width="55" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 40pt;">SL NO</th>
                <th width="105" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 80pt;">RA BILL NO</th>
                <th width="230" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 175pt;">CONTRACTOR NAME</th>
                <th width="200" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 150pt;">SITE PROJECT</th>
                <th width="125" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">SUBMIT DATE</th>
                <th width="155" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">RA BILL AMOUNT (₹)</th>
                <th width="145" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">CORRECTION / DEDUCTION (₹)</th>
                <th width="155" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">AFTER CORRECTION (₹)</th>
                <th width="145" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">ADDITIONAL %</th>
                <th width="175" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 135pt;">NET RA PAYABLE (₹)</th>
                <th width="125" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">DUE DATE</th>
                <th width="125" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">VERIFIED DATE</th>
                <th width="190" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 145pt;">VERIFYING ENGINEER</th>
                <th width="115" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 85pt;">SIGN-OFF STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $goldAdditionalSum = 0;
            @endphp
            @foreach($raBills as $bill)
                @php
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
                    $grossAmt = (float)$bill->gross_amount;
                    $corrAmt = (float)$bill->correction_amount;
                    $afterCorrAmt = max(0, $grossAmt - $corrAmt);
                    $addAmt = (float)$bill->additional_amount;
                    $goldAdditionalSum += $addAmt;
                    $netAmt = (float)$bill->net_approved_amount;
                    $subDate = $bill->submit_date ? $bill->submit_date->format('Y-m-d') : '';
                    $dueDate = $bill->due_date ? $bill->due_date->format('Y-m-d') : '';
                    $verDate = $bill->verified_date ? $bill->verified_date->format('Y-m-d') : '';
                    
                    $corrStyle = $corrAmt > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    $statusStyle = $bill->verified_date ? 'background-color: #dcfce7; color: #166534; font-weight: bold;' : 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                @endphp
                <tr height="26" style="height: 26pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->ra_bill_number }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor')) }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->project->name ?? 'Site Project') }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $subDate }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $grossAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $corrStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $corrAmt > 0 ? ('-' . $corrAmt) : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $afterCorrAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $addAmt > 0 ? $addAmt : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #eff6ff; color: #1e3a8a; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $netAmt }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $dueDate }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $verDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->engineer_name ?: ($bill->verified_date ? 'Site Engineer' : '—') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $statusStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->verified_date ? 'VERIFIED' : 'SUBMITTED' }}</td>
                </tr>
            @endforeach
            <tr height="34" style="height: 34pt; font-weight: bold; color: #ffffff;">
                <td colspan="5" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL REGISTER SUMMARY</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalGross }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #fee2e2; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)-$totalCorrections }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalGross - $totalCorrections) }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$goldAdditionalSum }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalNetApproved }}</td>
                <td colspan="4" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; border: 0.5pt solid #047857; font-size: 12pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            </tr>
        </tbody>
    </table>

    <!-- ── EXCEL DESIGN OPTION 2: CLASSIC SALES REPORT MULTI-COLOR THEME ── -->
    <table id="raBillsExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="55" style="width: 40pt;" />
            <col width="105" style="width: 80pt;" />
            <col width="230" style="width: 175pt;" />
            <col width="200" style="width: 150pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="155" style="width: 115pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="155" style="width: 115pt;" />
            <col width="145" style="width: 110pt;" />
            <col width="175" style="width: 135pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="125" style="width: 95pt;" />
            <col width="190" style="width: 145pt;" />
            <col width="115" style="width: 85pt;" />
        </colgroup>
        <thead>
            <tr height="45" style="height: 45pt;">
                <th colspan="14" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    {{ $projTitle }} - CONTRACTOR RA PROGRESS BILLS & VERIFICATION REGISTER
                </th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="5" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. CONTRACTOR & BILL INFORMATION</th>
                <th colspan="5" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. FINANCIAL CLAIMS & ENGINEER DEDUCTIONS</th>
                <th colspan="4" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. ENGINEER VERIFICATION & AUDIT SIGN-OFF</th>
            </tr>
            <tr height="40" style="height: 40pt;">
                <th width="55" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 40pt;">SL NO</th>
                <th width="105" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 80pt;">RA BILL NO</th>
                <th width="230" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 175pt;">CONTRACTOR NAME</th>
                <th width="200" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 150pt;">SITE PROJECT</th>
                <th width="125" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #0f2942; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">SUBMIT DATE</th>
                <th width="155" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">RA BILL AMOUNT (₹)</th>
                <th width="145" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">CORRECTION / DEDUCTION (₹)</th>
                <th width="155" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 115pt;">AFTER CORRECTION (₹)</th>
                <th width="145" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 110pt;">ADDITIONAL AMOUNT (₹)</th>
                <th width="175" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #6b5a19; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 135pt;">NET RA PAYABLE (₹)</th>
                <th width="125" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">DUE DATE</th>
                <th width="125" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 95pt;">VERIFIED DATE</th>
                <th width="190" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 145pt;">VERIFYING ENGINEER</th>
                <th width="115" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #047857; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; width: 85pt;">SIGN-OFF STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAdditionalSum = 0;
            @endphp
            @foreach($raBills as $bill)
                @php
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
                    $grossAmt = (float)$bill->gross_amount;
                    $corrAmt = (float)$bill->correction_amount;
                    $afterCorrAmt = max(0, $grossAmt - $corrAmt);
                    $addAmt = (float)$bill->additional_amount;
                    $totalAdditionalSum += $addAmt;
                    $netAmt = (float)$bill->net_approved_amount;
                    $subDate = $bill->submit_date ? $bill->submit_date->format('Y-m-d') : '';
                    $dueDate = $bill->due_date ? $bill->due_date->format('Y-m-d') : '';
                    $verDate = $bill->verified_date ? $bill->verified_date->format('Y-m-d') : '';
                    
                    $corrStyle = $corrAmt > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    $statusStyle = $bill->verified_date ? 'background-color: #dcfce7; color: #166534; font-weight: bold;' : 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                @endphp
                <tr height="26" style="height: 26pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->ra_bill_number }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->contractor_name ?: ($bill->contractor->name ?? 'General Contractor')) }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($bill->project->name ?? 'Site Project') }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $subDate }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $grossAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $corrStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $corrAmt > 0 ? ('-' . $corrAmt) : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $afterCorrAmt }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $addAmt > 0 ? $addAmt : '0.00' }}</td>
                    <td data-format="currency" style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #eff6ff; color: #1e3a8a; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ $netAmt }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $dueDate }}</td>
                    <td data-format="date" style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $verDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->engineer_name ?: ($bill->verified_date ? 'Site Engineer' : '—') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $statusStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $bill->verified_date ? 'VERIFIED' : 'SUBMITTED' }}</td>
                </tr>
            @endforeach
            <tr height="34" style="height: 34pt; font-weight: bold; color: #ffffff;">
                <td colspan="5" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalGross }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #fee2e2; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)-$totalCorrections }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($totalGross - $totalCorrections) }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalAdditionalSum }}</td>
                <td data-format="currency" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; text-align: center; border: 0.5pt solid #047857; font-size: 12pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0\.00';">{{ (float)$totalNetApproved }}</td>
                <td colspan="4" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; border: 0.5pt solid #047857; font-size: 12pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
@endsection
