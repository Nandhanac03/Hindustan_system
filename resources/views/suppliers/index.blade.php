<x-erp-layout title="Contractor Master - HindustanERP" headerTitle="Masters > Contractor Master">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="contractorDirectoryApp()">

        <!-- Breadcrumb & Top Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Masters</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Contractor </span>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <button type="button" @click="openAddModalFunc()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Contractor</span>
                </button>
            </div>
        </div>

        <!-- Flash & Error Notifications -->
        @if(session('status') || session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('status') ?? session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-800 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Executive KPI Summary Cards (Matched with Unit Exchange Box Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Card 1: Total Registered Contractors --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-[#a38c29] border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Total Contractors</span>
                    <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900 flex items-baseline gap-1.5">
                    <span>{{ $activeContractorsCount ?? $totalContractors ?? count($suppliers) }}</span>
                    <span class="text-[10px] text-emerald-700 font-extrabold uppercase tracking-wide">Active</span>
                    @if(($inactiveContractorsCount ?? 0) > 0)
                        <span class="text-[10px] text-slate-400 font-semibold">({{ $inactiveContractorsCount }} Inactive)</span>
                    @endif
                </div>
                <div class="text-[10px] font-medium text-slate-400">{{ $totalContractors ?? count($suppliers) }} Registered Master Payees</div>
            </div>

            {{-- Card 2: Ledger Integration --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Ledger Integration</span>
                    <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    {{ $totalContractors ?? count($suppliers) }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">SUP-ACC Payables Linked</div>
            </div>

            {{-- Card 3: GST Compliance --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>GST Compliance</span>
                    <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    {{ $gstinCount ?? 0 }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">
                    {{ ($totalContractors ?? count($suppliers)) > 0 ? round((($gstinCount ?? 0) / ($totalContractors ?? count($suppliers))) * 100) : 0 }}% GST Registered
                </div>
            </div>

            {{-- Card 4: RA Billing Total Work --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Total Work Billed</span>
                    <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    ₹{{ number_format($totalBillsAmount ?? 0, 2) }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">RA Bills Approved</div>
            </div>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel (Live Instant Filter - No Page Refresh) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
            <form @submit.prevent="applyFilter()" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full m-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 flex-1 w-full">
                    {{-- 1. Live Instant Search Input --}}
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="filterSearch" @input="applyFilter()" placeholder="Search Ledger Code / Phone / PAN..." autocomplete="off"
                               class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center" x-show="filterSearch && filterSearch.length > 0" style="display: none;">
                            <button type="button" @click="filterSearch = ''; applyFilter()" class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Contractor / Firm Name Dropdown (Instant Live Filter) --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <select x-model="filterContractorId" @change="applyFilter()"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Contractors / Firms</option>
                            @foreach(($allContractorsList ?? []) as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 3. Active / Inactive Status Dropdown (Instant Filter) --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <select x-model="filterStatus" @change="applyFilter()"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Contractors (Default)</option>
                            <option value="active">Active Contractors</option>
                            <option value="inactive">Inactive Contractors</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons: Reset Filters (Instant - No Page Reload) --}}
                <div class="flex items-center gap-2 flex-shrink-0 w-full lg:w-auto">
                    <button type="button" @click="resetFilters()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 whitespace-nowrap cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Filters</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Master Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">

            <!-- Master Table -->
            <style>
                #contractors-master-table thead th { border-color: #8a741f !important; }
                #contractors-master-tbody tr:nth-child(even) { background-color: #faf7eb !important; }
                #contractors-master-tbody tr:hover { background-color: #f5eed6 !important; }
            </style>
            <div class="overflow-x-auto custom-scrollbar">
                <table id="contractors-master-table" class="w-full text-xs text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a741f] text-[9.5px] font-black uppercase tracking-wider text-left">
                            <th class="px-2 py-3 text-center w-[45px] whitespace-nowrap">SL NO</th>
                            <th class="px-2.5 py-3 whitespace-nowrap w-[110px]">LEDGER CODE</th>
                            <th class="px-2.5 py-3 min-w-[160px]">CONTRACTOR / FIRM NAME</th>
                            <th class="px-2 py-3 whitespace-nowrap w-[140px]">TAX IDENTIFIERS</th>
                            <th class="px-2 py-3 whitespace-nowrap w-[135px]">CONTACT DETAILS</th>
                            <th class="px-2.5 py-3 max-w-[150px]">OFFICE ADDRESS</th>
                            <th class="px-2 py-3 whitespace-nowrap text-center w-[100px]">RA BILLS & BILLED</th>
                            <th class="px-2 py-3 whitespace-nowrap text-center w-[75px]">STATUS</th>
                            <th class="px-2.5 py-3 whitespace-nowrap text-right w-[95px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="contractors-master-tbody" class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($suppliers as $index => $sup)
                            <tr class="contractor-table-row transition hover:bg-[#faf7eb] {{ !($sup->is_active ?? true) ? 'bg-slate-50/70' : '' }}"
                                data-id="{{ $sup->id }}"
                                data-status="{{ ($sup->is_active ?? true) ? 'active' : 'inactive' }}"
                                data-search="{{ strtolower($sup->name . ' ' . ($sup->linked_account->code ?? ('SUP-ACC-' . str_pad($sup->id, 4, '0', STR_PAD_LEFT))) . ' ' . ($sup->phone ?? '') . ' ' . ($sup->pan ?? '') . ' ' . ($sup->gstin ?? '') . ' ' . ($sup->email ?? '') . ' ' . ($sup->address ?? '')) }}">
                                <td class="contractor-sl-no px-2 py-2.5 text-center font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-2.5 py-2.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-50 text-[#7a671b] border border-amber-200/80">
                                        {{ $sup->linked_account->code ?? ('SUP-ACC-' . str_pad($sup->id, 4, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </td>
                                <td class="px-2.5 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-[#a38c29] text-white flex items-center justify-center font-black text-[11px] shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($sup->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <strong class="text-slate-900 font-extrabold text-[11.5px] block uppercase leading-tight truncate" title="{{ $sup->name }}">{{ $sup->name }}</strong>
                                            <span class="text-[9px] text-slate-400 font-bold block leading-tight">CONTRACTOR PAYEE</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 whitespace-nowrap">
                                    <div class="space-y-0.5 text-[10.5px]">
                                        <div class="flex items-center gap-1">
                                            <span class="text-[8.5px] font-bold text-slate-400 uppercase w-6">GST:</span>
                                            @if($sup->gstin)
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1 py-0.2 rounded border border-slate-200 text-[10px]">{{ $sup->gstin }}</span>
                                            @else
                                                <span class="text-slate-400 italic text-[10px]">Unregistered</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[8.5px] font-bold text-slate-400 uppercase w-6">PAN:</span>
                                            @if($sup->pan)
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1 py-0.2 rounded border border-slate-200 text-[10px]">{{ $sup->pan }}</span>
                                            @else
                                                <span class="text-slate-400 italic text-[10px]">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 whitespace-nowrap">
                                    <div class="space-y-0.5 text-[11px]">
                                        @if($sup->phone)
                                            <div class="flex items-center gap-1 font-bold text-slate-800">
                                                <svg class="w-3 h-3 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>{{ $sup->phone }}</span>
                                            </div>
                                        @endif
                                        @if($sup->email)
                                            <div class="flex items-center gap-1 text-slate-500 font-semibold text-[10px]">
                                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span class="truncate max-w-[110px]" title="{{ $sup->email }}">{{ $sup->email }}</span>
                                            </div>
                                        @endif
                                        @if(!$sup->phone && !$sup->email)
                                            <span class="text-slate-400 italic text-[10px]">No contact specified</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2.5 py-2.5 max-w-[140px] truncate text-slate-600 font-medium text-[11px]" title="{{ $sup->address }}">
                                    {{ $sup->address ?? '—' }}
                                </td>
                                <td class="px-2 py-2.5 text-center">
                                    <div class="inline-flex flex-col items-center justify-center px-1.5 py-1 bg-slate-50 border border-slate-200/80 rounded-lg">
                                        <span class="text-[9.5px] font-black text-slate-900 font-mono leading-tight">{{ $sup->ra_bills_count ?? 0 }} RA Bills</span>
                                        <span class="text-[8.5px] font-mono font-bold text-slate-500 leading-tight">₹{{ number_format($sup->total_billed ?? 0, 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-2.5 text-center whitespace-nowrap">
                                    @if($sup->is_active ?? true)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Active</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-300 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Inactive</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2.5 py-2.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- View Details Modal Button -->
                                        <button type="button" @click="openViewModalFunc({{ json_encode($sup) }})" class="p-1.5 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Contractor Details">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit Modal Button -->
                                        <button type="button" @click="openEditModalFunc({{ json_encode($sup) }})" class="p-1.5 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Edit Contractor">
                                            <svg class="w-3.5 h-3.5 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        @if($sup->is_active ?? true)
                                            <!-- Delete / Inactivate Button -->
                                            <button type="button" @click="openDeactivateModalFunc({{ json_encode($sup) }})" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Delete Contractor">
                                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @else
                                            <!-- Activate Button (Opens Confirmation Modal) -->
                                            <button type="button" @click="openActivateModalFunc({{ json_encode($sup) }})" class="px-2 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 border border-emerald-200 transition inline-flex items-center gap-1 shadow-2xs cursor-pointer font-bold text-[10px]" title="Activate Contractor (Restore to Active)">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>Activate</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400 italic">
                                    No registered contractors found.
                                </td>
                            </tr>
                        @endforelse

                        {{-- Dynamic No Results Found Row for Live Filtering --}}
                        <tr id="no-contractors-row" style="display: none;">
                            <td colspan="9" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-amber-50 text-[#a38c29] flex items-center justify-center border border-amber-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-extrabold text-slate-700 uppercase tracking-wide">No contractors found matching your filter</p>
                                    <p class="text-xs text-slate-400 font-medium">Try clearing the search box or selecting "All Contractors / Firms".</p>
                                    <button type="button" @click="resetFilters()" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-[#a38c29] hover:text-white text-slate-700 text-xs font-bold transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Reset Filters</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 1. ADD NEW CONTRACTOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="openAddModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openAddModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col border-0">
                {{-- Dark Slate + Gold Header (Matched Theme) --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Contractor Master</p>
                            <h2 class="text-lg font-extrabold text-white">Add New Contractor</h2>
                        </div>
                        <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('contractors.store') }}" method="POST" @submit="submitAdd($event)" class="flex flex-col flex-1">
                    @csrf
                    <div class="px-6 pt-3.5 pb-6 space-y-3.5 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contractor / Firm Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="name" required placeholder="e.g. BuildRight Constructions Pvt Ltd"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="errors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="phone" placeholder="e.g. 9876543210"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" x-model="email" placeholder="e.g. contact@builder.com"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">GSTIN Number</label>
                                <input type="text" name="gstin" x-model="gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                                <template x-if="errors.gstin"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="errors.gstin"></p></template>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PAN Number</label>
                                <input type="text" name="pan" x-model="pan" placeholder="AABCB1234C" maxlength="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Office Address</label>
                            <textarea name="address" x-model="address" rows="2" placeholder="Street, City, Postal Code..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                            <span>SAVE CONTRACTOR</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. EDIT CONTRACTOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="openEditModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openEditModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col border-0">
                {{-- Dark Slate + Gold Header (Matched Theme) --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Contractor Master</p>
                            <h2 class="text-lg font-extrabold text-white">Edit Contractor Details</h2>
                        </div>
                        <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form :action="'{{ url('/contractors') }}/' + editForm.id" method="POST" class="flex flex-col flex-1">
                    @csrf
                    @method('PUT')
                    <div class="px-6 pt-3.5 pb-6 space-y-3.5 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contractor / Firm Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="editForm.name" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone" placeholder="e.g. 9876543210"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" x-model="editForm.email" placeholder="e.g. contact@builder.com"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">GSTIN Number</label>
                                <input type="text" name="gstin" x-model="editForm.gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PAN Number</label>
                                <input type="text" name="pan" x-model="editForm.pan" placeholder="AABCB1234C" maxlength="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Office Address</label>
                            <textarea name="address" x-model="editForm.address" rows="2" placeholder="Street, City, Postal Code..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openEditModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                            <span>UPDATE CONTRACTOR</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 3. VIEW CONTRACTOR PROFILE MODAL -->
        <!-- ========================================== -->
        <div x-show="openViewModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openViewModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col border-0">
                {{-- Dark Slate + Gold Header (Matched Theme) --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Contractor Master</p>
                            <h2 class="text-lg font-extrabold text-white" x-text="viewContractor.name || 'Contractor Details'"></h2>
                        </div>
                        <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 pt-3.5 pb-5 space-y-2.5 text-xs bg-white">
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">LEDGER CODE</span>
                        <span class="font-mono font-bold text-[#a38c29]" x-text="viewContractor.linked_account ? viewContractor.linked_account.code : 'SUP-ACC-xxxx'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CONTRACTOR NAME</span>
                        <span class="font-bold text-slate-900 uppercase" x-text="viewContractor.name"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">GSTIN</span>
                        <span class="font-mono font-bold text-slate-900" x-text="viewContractor.gstin || 'Unregistered'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PAN</span>
                        <span class="font-mono font-bold text-slate-900" x-text="viewContractor.pan || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PHONE</span>
                        <span class="font-bold text-slate-900" x-text="viewContractor.phone || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">EMAIL</span>
                        <span class="font-bold text-slate-900" x-text="viewContractor.email || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                        <template x-if="viewContractor.is_active">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        </template>
                        <template x-if="!viewContractor.is_active">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Inactive
                            </span>
                        </template>
                    </div>
                    <div class="flex justify-between pb-1">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ADDRESS</span>
                        <span class="font-semibold text-slate-800 text-right max-w-[220px]" x-text="viewContractor.address || 'N/A'"></span>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                    <a :href="'{{ route('expenses.ra-bills.ledger') }}?contractor_id=' + viewContractor.id" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold uppercase rounded-xl transition inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>OPEN LEDGER</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <template x-if="!viewContractor.is_active">
                            <button type="button" @click="openActivateModalFunc(viewContractor); openViewModal = false;" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold uppercase rounded-xl transition inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>ACTIVATE</span>
                            </button>
                        </template>
                        <button type="button" @click="openViewModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition cursor-pointer">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 4. DELETE CONTRACTOR CONFIRMATION MODAL    -->
        <!-- ========================================== -->
        <div x-show="openDeactivateModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openDeactivateModal = false" class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col border-0">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                TABASCO HINDUSTAN · CONTRACTOR
                            </p>
                            <h2 class="text-base font-extrabold text-white uppercase tracking-wider">Delete Contractor</h2>
                        </div>
                        <button type="button" @click="openDeactivateModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 text-center bg-white">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-4 border border-rose-200 shadow-xs">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-wider mb-2">Delete Contractor From List?</h3>
                    <p class="text-sm text-slate-600 font-semibold mb-3">
                        Are you sure you want to delete <span class="font-extrabold text-slate-900 uppercase" x-text="contractorToDeactivate.name"></span>?
                    </p>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openDeactivateModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <form :action="'{{ url('/contractors') }}/' + contractorToDeactivate.id + '/toggle-status'" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>YES, DELETE</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 5. ACTIVATE CONTRACTOR CONFIRMATION MODAL  -->
        <!-- ========================================== -->
        <div x-show="openActivateModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openActivateModal = false" class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col border-0">
                {{-- Dark Header with Emerald Accent --}}
                <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#122b1f] px-6 py-5 flex-shrink-0 border-b border-emerald-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                TABASCO HINDUSTAN · CONTRACTOR
                            </p>
                            <h2 class="text-base font-extrabold text-white uppercase tracking-wider">Activate Contractor</h2>
                        </div>
                        <button type="button" @click="openActivateModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 text-center bg-white">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-4 border border-emerald-200 shadow-xs">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-wider mb-2">Activate Contractor?</h3>
                    <p class="text-sm text-slate-600 font-semibold mb-3">
                        Are you sure you want to activate <span class="font-extrabold text-slate-900 uppercase" x-text="contractorToActivate.name"></span>? This will restore the contractor to active status.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openActivateModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <form :action="'{{ url('/contractors') }}/' + contractorToActivate.id + '/toggle-status'" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>YES, ACTIVATE</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function contractorDirectoryApp() {
            return {
                filterSearch: '{{ request("search", "") }}',
                filterContractorId: '{{ request("contractor_id", "") }}',
                filterStatus: '{{ request("status", "") }}',
                openAddModal: false,
                openEditModal: false,
                openViewModal: false,
                openDeactivateModal: false,
                openActivateModal: false,
                name: '',
                phone: '',
                email: '',
                gstin: '',
                pan: '',
                address: '',
                errors: {},
                editForm: {
                    id: '',
                    name: '',
                    phone: '',
                    email: '',
                    gstin: '',
                    pan: '',
                    address: ''
                },
                viewContractor: {},
                contractorToDeactivate: {},
                contractorToActivate: {},

                init() {
                    this.$nextTick(() => {
                        this.applyFilter();
                    });
                },

                applyFilter() {
                    const search = (this.filterSearch || '').trim().toLowerCase();
                    const contractorId = (this.filterContractorId || '').toString().trim();
                    const statusFilter = (this.filterStatus || '').toString().trim().toLowerCase();
                    const rows = document.querySelectorAll('.contractor-table-row');
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const rowId = (row.dataset.id || '').toString();
                        const rowSearch = (row.dataset.search || '').toLowerCase();
                        const rowStatus = (row.dataset.status || '').toLowerCase();

                        const matchesId = !contractorId || rowId === contractorId;
                        const matchesSearch = !search || rowSearch.includes(search);
                        const matchesStatus = !statusFilter || rowStatus === statusFilter;

                        if (matchesId && matchesSearch && matchesStatus) {
                            row.style.display = '';
                            visibleCount++;
                            const slCell = row.querySelector('.contractor-sl-no');
                            if (slCell) slCell.textContent = visibleCount;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const noRowsEl = document.getElementById('no-contractors-row');
                    if (noRowsEl) {
                        noRowsEl.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
                    }

                    // Update browser address bar quietly without reloading
                    try {
                        const url = new URL(window.location.href);
                        if (contractorId) url.searchParams.set('contractor_id', contractorId);
                        else url.searchParams.delete('contractor_id');
                        if (search) url.searchParams.set('search', search);
                        else url.searchParams.delete('search');
                        if (statusFilter) url.searchParams.set('status', statusFilter);
                        else url.searchParams.delete('status');
                        window.history.replaceState({}, '', url.toString());
                    } catch (e) {}
                },

                resetFilters() {
                    this.filterSearch = '';
                    this.filterContractorId = '';
                    this.filterStatus = '';
                    this.applyFilter();
                },

                openAddModalFunc() {
                    this.name = '';
                    this.phone = '';
                    this.email = '';
                    this.gstin = '';
                    this.pan = '';
                    this.address = '';
                    this.errors = {};
                    this.openAddModal = true;
                },

                openEditModalFunc(sup) {
                    this.editForm = {
                        id: sup.id,
                        name: sup.name || '',
                        phone: sup.phone || '',
                        email: sup.email || '',
                        gstin: sup.gstin || '',
                        pan: sup.pan || '',
                        address: sup.address || ''
                    };
                    this.openEditModal = true;
                },

                openViewModalFunc(sup) {
                    this.viewContractor = sup;
                    this.openViewModal = true;
                },

                openDeactivateModalFunc(sup) {
                    this.contractorToDeactivate = sup;
                    this.openDeactivateModal = true;
                },

                openActivateModalFunc(sup) {
                    this.contractorToActivate = sup;
                    this.openActivateModal = true;
                },

                submitAdd(e) {
                    this.errors = {};
                    if (!this.name || !this.name.trim()) {
                        e.preventDefault();
                        this.errors.name = 'The contractor name field is required.';
                        return false;
                    }
                    if (this.gstin && this.gstin.trim().length !== 15) {
                        e.preventDefault();
                        this.errors.gstin = 'GSTIN must be exactly 15 alphanumeric characters.';
                        return false;
                    }
                    return true;
                }
            };
        }
    </script>
</x-erp-layout>
