<x-erp-layout title="Cheque & Receipt Entry Desk" headerTitle="Cheque & Receipt Entry Workspace">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="chequeReceiptEntryDesk()" x-init="init()">
        

        
        {{-- Flash Notifications --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black">✕</button>
            </div>
        @endif

        <!-- Top Header & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Customer Management & Collections</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Cheque & Receipt Entry Display</span>
            </div>

            <!-- Collect Receipt Modal Button (Brand Gold Matching) -->
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" @click="openCollectModal()" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-[#a38c29]/40 cursor-pointer">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>COLLECT RECEIPT</span>
                </button>
            </div>
        </div>

        <!-- ── TOP EXECUTIVE KPI SUMMARY CARDS GRID ── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Realized Collections -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'realized']) }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer block">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Realized</span>
                    </div>
                    <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                        {{ $realizedCount ?? 0 }} Cleared
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        ₹{{ number_format($totalCollectionAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">{{ $totalReceiptsCount ?? 0 }} total receipts registered</p>
                </div>
            </a>

            <!-- Card 2: Cheques Pending Clearance -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'cheques']) }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-pointer block">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Pending Clearance</span>
                    </div>
                    <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                        {{ $pendingRealizationCount ?? 0 }} Pending
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                        ₹{{ number_format($pendingRealizationAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Awaiting bank deposit & realization</p>
                </div>
            </a>

            <!-- Card 3: Total Treasury Liquidity -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 21h18M4 18h16M6 18v-7m4 7v-7m4 7v-7m4 7v-7M4 10l8-6 8 6"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Treasury Liquidity</span>
                    </div>
                    <span class="text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                        {{ count($companyBankAccounts ?? []) }} Accounts
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        ₹{{ number_format($totalLiquidity ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Liquid balances in active company banks</p>
                </div>
            </div>

            <!-- Card 4: Bounced Cheques -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'bounced']) }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer block">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Bounced Cheques</span>
                    </div>
                    <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:bg-rose-100/60">
                        {{ $bouncedCount ?? 0 }} Bounced
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                        ₹{{ number_format($bouncedAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Dishonored entries requiring follow-up</p>
                </div>
            </a>
        </div>

        <!-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL ── -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">

                    {{-- 2. Customer Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select x-model="filters.customer_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Customers</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 3. Payment Mode Filter (Only Cash, Cheque, Bank Transfer, Online) --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <select x-model="filters.payment_mode" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Payment Modes</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Online">Online</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 4. Status Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                        </div>
                        <select x-model="filters.realization_status" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Statuses</option>
                            @foreach($chequeStatusesMap as $stKey => $stData)
                                <option value="{{ $stKey }}">
                                    {{ $stData['name'] }} ({{ $stData['count'] }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 5. DATE Input --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.date" @change="currentPage = 1"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
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

        <!-- ── MAIN CHEQUE & RECEIPT REGISTER TABLE (READ-ONLY DISPLAY WITH STATUS) ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Cheque & Receipt Display Register
                    </span>
                    <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold"
                          x-text="filteredReceipts.length + ' Records'">
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                {{-- 📋 MAIN RECEIPTS DISPLAY REGISTER TABLE --}}
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-extrabold uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                        <tr class="text-left">
                            <th class="px-3.5 py-3.5 w-[110px]">RECEIPT #</th>
                            <th class="px-3.5 py-3.5 w-[100px]">DATE</th>
                            <th class="px-3.5 py-3.5 w-[200px]">CUSTOMER / PAYER</th>
                            <th class="px-3.5 py-3.5 w-[200px]">COMPANY BANK ACCOUNT</th>
                            <th class="px-3.5 py-3.5 text-right w-[120px]">AMOUNT</th>
                            <th class="px-3.5 py-3.5 text-center w-[110px]">MODE</th>
                            <th class="px-3.5 py-3.5 text-center w-[150px]">REALIZATION STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold bg-white text-slate-700">
                        <template x-for="r in paginatedReceipts" :key="r.id">
                            <tr @click="openViewModal(r)" class="hover:bg-amber-50/40 transition-colors border-b border-slate-100 cursor-pointer">
                                <td class="px-3.5 py-3.5 text-left font-mono font-bold text-slate-900" x-text="r.ref"></td>

                                <td class="px-3.5 py-3.5 text-left text-slate-600 font-medium" x-text="r.date"></td>

                                <td class="px-3.5 py-3.5 text-left">
                                    <div class="font-black text-slate-900 text-[11.5px]" x-text="r.customer_name"></div>
                                    <div class="text-[9.5px] text-slate-500 font-semibold mt-0.5" 
                                         x-text="r.project_name + (r.unit_name && r.unit_name !== '—' ? ' (' + r.unit_name + ')' : '')">
                                    </div>
                                </td>

                                <td class="px-3.5 py-3.5 text-left">
                                    <div class="font-extrabold text-slate-800 text-[11px]" x-text="r.company_bank_account_name"></div>
                                    <template x-if="r.company_bank_account_number">
                                        <div class="text-[9.5px] font-mono text-slate-500" x-text="'A/C: ' + r.company_bank_account_number"></div>
                                    </template>
                                </td>

                                <td class="px-3.5 py-3.5 text-right font-mono font-black text-slate-950 text-sm" 
                                    x-text="'₹' + Number(r.amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                </td>

                                <td class="px-3.5 py-3.5 text-center">
                                    <span class="px-2.5 py-0.5 rounded-md text-[9.5px] font-black uppercase tracking-wider inline-block"
                                          :class="r.payment_mode === 'Cheque' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-200'"
                                          x-text="r.payment_mode || 'Cash'">
                                    </span>
                                </td>

                                <td class="px-3.5 py-3.5 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9.5px] font-black uppercase tracking-wider border inline-flex items-center justify-center whitespace-nowrap"
                                          :class="r.status_badge_classes"
                                          x-text="r.status_display_name">
                                    </span>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredReceipts.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                No Cheque & Receipt entries found matching your filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Footer Pagination Bar --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span x-text="filteredReceipts.length ? (currentPage - 1) * perPage + 1 : 0"></span> TO <span x-text="Math.min(currentPage * perPage, filteredReceipts.length)"></span> OF <span x-text="filteredReceipts.length"></span> RECORDS
                </div>
                <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                    <button type="button" 
                            @click="if(currentPage > 1) currentPage--"
                            :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-100'"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-2xs transition">
                        PREV
                    </button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                x-show="p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2)"
                                @click="currentPage = p"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white border-[#a38c29]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold border shadow-2xs transition cursor-pointer"
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="if(currentPage < totalPages) currentPage++"
                            :disabled="currentPage >= totalPages"
                            :class="currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-slate-100'"
                            class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-2xs transition">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast notification -->
        <div x-show="toast.open" 
             x-transition 
             class="fixed bottom-5 right-5 z-[999999] px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-bold uppercase tracking-wider border"
             :class="toast.type === 'success' ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-rose-500 text-white border-rose-400'"
             style="display: none;">
            <span x-text="toast.message"></span>
            <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
        </div>

        <!-- ── MODAL: COLLECTION / RECEIPT ENTRY ── -->
        <template x-teleport="body">
            <div x-show="modal.open" 
                 class="fixed inset-0 top-0 left-0 w-screen h-screen z-[99999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" 
                 style="display: none;" 
                 x-transition.opacity>
                <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all my-auto" @click.away="closeCollectModal()">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-7 py-5 text-white flex items-center justify-between relative overflow-hidden">
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 text-[#f3e5ab] flex items-center justify-center text-lg font-black shadow-inner border border-[#a38c29]/30">
                                ₹
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40">EMI & PAYMENTS</span>
                                    <span class="text-[10px] text-slate-400 font-semibold">Payment Intake</span>
                                </div>
                                <h3 class="font-black text-base uppercase tracking-wider text-white mt-0.5">Collection Receipt Entry</h3>
                            </div>
                        </div>
                        <button type="button" @click="closeCollectModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-sm transition cursor-pointer relative z-10">✕</button>
                    </div>
                    
                    <form @submit.prevent="submitCollection()" novalidate class="flex flex-col">
                        <div class="p-6 md:p-7 space-y-4 max-h-[78vh] overflow-y-auto font-sans text-xs bg-white">
                            {{-- Active Sale / Customer Searchable Select Field --}}
                            <div class="space-y-1.5 relative" @click.outside="modalSaleDropdownOpen = false">
                                <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                                    <span>Active Sale / Customer <span class="text-rose-500">*</span></span>
                                    <span x-show="form.booking_id" class="text-[9px] font-semibold text-emerald-600 flex items-center gap-1" style="display: none;">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Selected
                                    </span>
                                </label>

                                {{-- Dropdown Trigger Button --}}
                                <button type="button" 
                                        @click="modalSaleDropdownOpen = !modalSaleDropdownOpen; if(modalSaleDropdownOpen) { modalSaleSearch = ''; $nextTick(() => $refs.modalSaleSearchInput?.focus()); }"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-between gap-2 cursor-pointer text-left"
                                        :class="errors.booking_id ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : (form.booking_id ? 'bg-amber-50/30 border-[#a38c29]/50 text-slate-900' : 'text-slate-400')">
                                    
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <div class="w-6 h-6 rounded-lg bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <template x-if="form.booking_id && selectedModalSaleDisplay">
                                            <span class="truncate text-slate-900 font-bold" x-text="selectedModalSaleDisplay"></span>
                                        </template>
                                        <template x-if="!form.booking_id">
                                            <span class="text-slate-400 font-medium">-- Search & Choose Active Customer & Sale --</span>
                                        </template>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <template x-if="form.booking_id">
                                            <span @click.stop="clearModalSale()" class="p-1 rounded-md hover:bg-rose-100 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Clear selection">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </span>
                                        </template>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="modalSaleDropdownOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>

                                <template x-if="errors.booking_id">
                                    <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.booking_id) ? errors.booking_id[0] : errors.booking_id"></span>
                                </template>

                                {{-- Dropdown Popover List --}}
                                <div x-show="modalSaleDropdownOpen" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                     class="absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden max-h-72 flex flex-col"
                                     style="display: none;">
                                    
                                    {{-- Search Input inside Popover --}}
                                    <div class="p-2.5 bg-slate-50/90 border-b border-slate-100 sticky top-0 z-10">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            </div>
                                            <input type="text" 
                                                   x-model="modalSaleSearch" 
                                                   @input="modalSaleSearch = $event.target.value"
                                                   x-ref="modalSaleSearchInput"
                                                   placeholder="Type customer name, sale no, unit, or phone..." 
                                                   class="w-full pl-9 pr-3.5 py-2 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] placeholder:text-slate-400">
                                        </div>
                                    </div>

                                    {{-- Results List --}}
                                    <div class="overflow-y-auto p-1.5 space-y-1 divide-y divide-slate-100 max-h-56">
                                        <template x-for="s in filteredModalSales" :key="s.id">
                                            <div @click="selectModalSale(s)"
                                                 class="p-2.5 rounded-xl cursor-pointer transition-all flex items-center justify-between gap-3 text-left"
                                                 :class="form.booking_id == s.id ? 'bg-[#a38c29]/15 border border-[#a38c29]/40' : 'hover:bg-slate-50 border border-transparent'">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <strong class="text-slate-900 font-black text-xs truncate" x-text="s.customer ? s.customer.name : 'Unknown Customer'"></strong>
                                                        <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[9px] font-bold" x-text="s.sale_number"></span>
                                                    </div>
                                                    <div class="text-[10px] text-slate-500 font-medium truncate mt-0.5">
                                                        <span x-text="s.project ? s.project.name : '—'"></span>
                                                        <template x-if="s.unit && s.unit.door_no">
                                                            <span x-text="' · Unit ' + s.unit.door_no"></span>
                                                        </template>
                                                        <template x-if="s.customer && (s.customer.phone || s.customer.phone_number)">
                                                            <span class="text-slate-400" x-text="' · 📞 ' + (s.customer.phone || s.customer.phone_number)"></span>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="text-right shrink-0">
                                                    <span class="text-[9px] uppercase font-bold text-slate-400 block">Due Bal</span>
                                                    <strong class="text-rose-600 font-mono font-bold text-xs" x-text="'₹' + Number(s.remaining_balance || 0).toLocaleString('en-IN')"></strong>
                                                </div>
                                            </div>
                                        </template>

                                        <div x-show="filteredModalSales.length === 0" class="py-6 px-4 text-center text-slate-400 text-xs font-semibold">
                                            <svg class="w-7 h-7 mx-auto text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>No active sales or customers found matching your search.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Box (Horizontal 3-column summary) --}}
                            <div x-show="form.booking_id && form.project_name" class="p-4 bg-gradient-to-r from-amber-50/80 via-white to-amber-50/80 rounded-2xl border border-amber-200/70 shadow-xs" x-transition>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-semibold">
                                    <div class="bg-white/80 p-2.5 rounded-xl border border-amber-100">
                                        <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-0.5">Project / Unit</span>
                                        <strong class="text-slate-900 font-bold text-xs" x-text="form.project_name + ' · Unit ' + form.unit_number"></strong>
                                    </div>
                                    <div class="bg-white/80 p-2.5 rounded-xl border border-amber-100">
                                        <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-0.5">Total Contract Value</span>
                                        <strong class="text-slate-900 font-mono font-bold text-xs" x-text="'₹' + Number(form.total_amount).toLocaleString('en-IN')"></strong>
                                    </div>
                                    <div class="bg-white/80 p-2.5 rounded-xl border border-rose-100">
                                        <span class="text-[10px] uppercase font-extrabold text-rose-500 block mb-0.5">Remaining Balance</span>
                                        <strong class="text-rose-600 font-mono font-bold text-xs" x-text="'₹' + Number(form.outstanding).toLocaleString('en-IN')"></strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Row 2: Action Type (33.3% width) & Payment Mode (66.7% width) --}}
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 pt-1">
                                {{-- Action Type (4 cols = 33.3%) --}}
                                <div class="md:col-span-4 space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Action Type <span class="text-rose-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <button type="button" @click="form.collection_type = 'regular'" 
                                                :class="form.collection_type === 'regular' ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#a38c29]/40'" 
                                                class="px-2 py-2.5 border rounded-xl text-[11px] font-black uppercase tracking-wider transition-all cursor-pointer text-center">Regular</button>
                                        <button type="button" @click="form.collection_type = 'prepayment'" 
                                                :class="form.collection_type === 'prepayment' ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#a38c29]/40'" 
                                                class="px-2 py-2.5 border rounded-xl text-[11px] font-black uppercase tracking-wider transition-all cursor-pointer text-center">Prepayment</button>
                                    </div>
                                </div>

                                {{-- Payment Mode (8 cols = 66.7%) --}}
                                <div class="md:col-span-8 space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Payment Mode <span class="text-rose-500">*</span></label>
                                    <div class="grid grid-cols-4 gap-1.5">
                                        <template x-for="mode in ['Cash', 'Cheque', 'Bank Transfer', 'Online']" :key="mode">
                                            <button type="button" @click="form.payment_mode = mode; if(errors.payment_mode) delete errors.payment_mode;"
                                                    :class="form.payment_mode === mode ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-sm font-black' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-[#a38c29]/40 font-bold'"
                                                    class="px-1.5 py-2.5 border rounded-xl text-[10px] uppercase tracking-tight transition-all cursor-pointer text-center whitespace-nowrap overflow-hidden"
                                                    x-text="mode">
                                            </button>
                                        </template>
                                    </div>
                                    <template x-if="errors.payment_mode">
                                        <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.payment_mode) ? errors.payment_mode[0] : errors.payment_mode"></span>
                                    </template>
                                </div>
                            </div>

                            {{-- Prepayment Options --}}
                            <div class="space-y-1.5" x-show="form.collection_type === 'prepayment'" x-cloak x-transition>
                                <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Prepayment Option <span class="text-rose-500">*</span></label>
                                <select x-model="form.prepayment_option" @change="if(errors.prepayment_option) delete errors.prepayment_option;"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs text-slate-900 font-bold transition-all shadow-xs cursor-pointer"
                                        :class="errors.prepayment_option ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : ''">
                                    <option value="reduce_emi">Reduce EMI amount (keep tenure the same)</option>
                                    <option value="reduce_tenure">Reduce Tenure (keep monthly EMI the same)</option>
                                </select>
                                <template x-if="errors.prepayment_option">
                                    <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.prepayment_option) ? errors.prepayment_option[0] : errors.prepayment_option"></span>
                                </template>
                            </div>

                            {{-- Row 3: Amount, Date, and Reference (3 Columns) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Amount (₹) <span class="text-rose-500">*</span></label>
                                    <input type="number" step="1" x-model.number="form.amount" min="1"
                                           @input="if(errors.amount) delete errors.amount; if(form.amount && form.amount.toString().includes('.')) { form.amount = Math.floor(form.amount); }"
                                           placeholder="0"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs"
                                           :class="errors.amount ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : ''">
                                    <template x-if="errors.amount">
                                        <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.amount) ? errors.amount[0] : errors.amount"></span>
                                    </template>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Receipt Date <span class="text-rose-500">*</span></label>
                                    <input type="date" x-model="form.receipt_date"
                                           @input="if(errors.receipt_date) delete errors.receipt_date;"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs"
                                           :class="errors.receipt_date ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : ''">
                                    <template x-if="errors.receipt_date">
                                        <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.receipt_date) ? errors.receipt_date[0] : errors.receipt_date"></span>
                                    </template>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Ref / Cheque / UTR No.</label>
                                    <input type="text" x-model="form.reference_no" placeholder="Optional Reference..."
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs">
                                </div>
                            </div>

                            {{-- Row 4: Bank Name & Remarks (2 Columns) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Bank Name</label>
                                    <select x-model="form.bank_id"
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs cursor-pointer">
                                        <option value="">-- Optional / Select Bank --</option>
                                        @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Remarks / Notes</label>
                                    <input type="text" x-model="form.remarks" placeholder="Optional notes regarding this receipt..."
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-semibold text-slate-900 transition-all shadow-xs">
                                </div>
                            </div>
                        </div>

                        <div class="px-7 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50">
                            <button type="button" @click="closeCollectModal()" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">
                                CANCEL
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/25 flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>COLLECT RECEIPT</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- ── MODAL: READ-ONLY RECEIPT DISPLAY DETAILS ── -->
        <template x-teleport="body">
            <div x-show="viewModalOpen" 
                 class="fixed inset-0 top-0 left-0 w-screen h-screen z-[99999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" 
                 style="display: none;" 
                 x-transition.opacity>
                <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all my-auto" @click.away="viewModalOpen = false">
                    <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-6 py-5 text-white flex items-center justify-between relative overflow-hidden">
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 text-[#f3e5ab] flex items-center justify-center text-lg font-black shadow-inner border border-[#a38c29]/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <div>
                                <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-0.5">RECEIPT DETAILS DISPLAY</span>
                                <h3 class="font-black text-base uppercase tracking-wider text-white" x-text="selectedReceipt ? selectedReceipt.ref : 'Receipt Details'"></h3>
                            </div>
                        </div>
                        <button type="button" @click="viewModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-sm transition cursor-pointer relative z-10">✕</button>
                    </div>

                    <template x-if="selectedReceipt">
                        <div class="p-6 space-y-4 text-xs text-slate-800">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Amount</span>
                                    <span class="text-2xl font-black text-slate-900 font-mono" x-text="'₹' + Number(selectedReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Realization Status</span>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border inline-flex items-center justify-center"
                                          :class="{
                                              'bg-emerald-50 text-emerald-800 border-emerald-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'realized',
                                              'bg-amber-50 text-amber-800 border-amber-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'cheque_in_hand',
                                              'bg-blue-50 text-blue-800 border-blue-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'deposited',
                                              'bg-rose-50 text-rose-800 border-rose-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'bounced',
                                              'bg-slate-100 text-slate-700 border-slate-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'pending'
                                          }">
                                        <span x-text="
                                            (selectedReceipt.realization_status || selectedReceipt.status) === 'cheque_in_hand' ? 'CHEQUE IN HAND' :
                                            ((selectedReceipt.realization_status || selectedReceipt.status) === 'realized' ? 'REALIZED' :
                                            ((selectedReceipt.realization_status || selectedReceipt.status) === 'deposited' ? 'DEPOSITED' :
                                            ((selectedReceipt.realization_status || selectedReceipt.status) === 'bounced' ? 'BOUNCED' : 'PENDING')))
                                        "></span>
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Customer / Payer</span>
                                    <span class="font-black text-slate-900 text-sm" x-text="selectedReceipt.customer_name || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Receipt Date</span>
                                    <span class="font-bold text-slate-800" x-text="selectedReceipt.date || '—'"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Site Project</span>
                                    <span class="font-bold text-slate-800" x-text="selectedReceipt.project_name || '—'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Payment Mode</span>
                                    <span class="font-bold text-slate-800" x-text="selectedReceipt.payment_mode || '—'"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Company Bank Account</span>
                                    <span class="font-bold text-slate-900" x-text="selectedReceipt.company_bank_account_name || '—'"></span>
                                    <span class="text-[10px] font-mono text-slate-500 block" x-show="selectedReceipt.company_bank_account_number" x-text="'A/C: ' + selectedReceipt.company_bank_account_number"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Cheque / Reference No</span>
                                    <span class="font-mono font-bold text-slate-900" x-text="selectedReceipt.reference_no || '—'"></span>
                                </div>
                            </div>

                            <template x-if="selectedReceipt.drawee_bank">
                                <div class="border-b border-slate-100 pb-3">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Drawee Bank</span>
                                    <span class="font-semibold text-slate-800" x-text="selectedReceipt.drawee_bank"></span>
                                </div>
                            </template>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Remarks / Narrative</span>
                                <p class="p-3 bg-slate-50 rounded-xl text-slate-700 italic border border-slate-200" x-text="selectedReceipt.remarks || 'No remarks recorded.'"></p>
                            </div>

                            <div class="pt-3 flex justify-end border-t border-slate-100">
                                <button type="button" @click="viewModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">CLOSE DISPLAY</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </div>

    <script>
    function chequeReceiptEntryDesk() {
        return {
            modal: {
                open: false
            },
            form: {
                booking_id: '',
                amount: '',
                payment_mode: 'Cash',
                receipt_date: new Date().toISOString().split('T')[0],
                reference_no: '',
                bank_id: '',
                remarks: '',
                customer_name: '',
                unit_number: '',
                outstanding: 0,
                project_name: '',
                total_amount: 0,
                collection_type: 'regular',
                prepayment_option: 'reduce_tenure',
                reschedule_option: 'extend_tenure',
                reschedule_reason: '',
                new_count: 12,
                shift_months: 1,
            },
            toast: {
                open: false,
                message: '',
                type: 'success'
            },
            errors: {},

            allReceipts: @json($allReceiptsFormatted ?? []),
            filters: {
                search: '{{ request('search', '') }}',
                customer_id: '{{ request('customer_id', '') }}',
                payment_mode: '{{ request('payment_mode', '') }}',
                realization_status: '{{ request('realization_status', '') }}',
                date: '{{ request('date', '') }}'
            },
            currentPage: 1,
            perPage: 20,

            get filteredReceipts() {
                let list = this.allReceipts || [];

                if (this.filters.customer_id) {
                    list = list.filter(r => r.customer_id == this.filters.customer_id);
                }

                if (this.filters.payment_mode) {
                    const pm = this.filters.payment_mode.toLowerCase().replace(/[\s\/-]/g, '');
                    list = list.filter(r => {
                        const rpm = (r.payment_mode || '').toLowerCase().replace(/[\s\/-]/g, '');
                        if (pm === 'banktransfer') {
                            return rpm.includes('bank') || rpm.includes('neft') || rpm.includes('rtgs') || rpm.includes('transfer');
                        }
                        if (pm === 'online') {
                            return rpm.includes('online') || rpm.includes('upi') || rpm.includes('gpay');
                        }
                        return rpm === pm;
                    });
                }

                if (this.filters.realization_status) {
                    list = list.filter(r => (r.realization_status || '').toLowerCase() === this.filters.realization_status.toLowerCase());
                }

                if (this.filters.date) {
                    list = list.filter(r => r.date === this.filters.date);
                }

                if (this.filters.search && this.filters.search.trim() !== '') {
                    const q = this.filters.search.trim().toLowerCase();
                    list = list.filter(r => {
                        const ref = (r.ref || '').toLowerCase();
                        const cust = (r.customer_name || '').toLowerCase();
                        const payer = (r.payer_name || '').toLowerCase();
                        const proj = (r.project_name || '').toLowerCase();
                        const unit = (r.unit_name || '').toLowerCase();
                        const bank = (r.company_bank_account_name || '').toLowerCase();
                        const acc = (r.company_bank_account_number || '').toLowerCase();
                        const remarks = (r.remarks || '').toLowerCase();
                        const chequeNo = (r.reference_no || '').toLowerCase();
                        const drawee = (r.drawee_bank || '').toLowerCase();
                        const amount = (r.amount || '').toString();
                        return ref.includes(q) || cust.includes(q) || payer.includes(q) || proj.includes(q) || unit.includes(q) || bank.includes(q) || acc.includes(q) || remarks.includes(q) || chequeNo.includes(q) || drawee.includes(q) || amount.includes(q);
                    });
                }

                return list;
            },

            get totalPages() {
                return Math.ceil(this.filteredReceipts.length / this.perPage) || 1;
            },

            get paginatedReceipts() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredReceipts.slice(start, start + this.perPage);
            },

            resetFilters() {
                this.filters.search = '';
                this.filters.customer_id = '';
                this.filters.payment_mode = '';
                this.filters.realization_status = '';
                this.filters.date = '';
                this.currentPage = 1;
            },

            activeSales: @json($activeSales ?? []),
            modalSaleSearch: '',
            modalSaleDropdownOpen: false,

            viewModalOpen: false,
            selectedReceipt: null,

            get filteredModalSales() {
                const rawQuery = (this.modalSaleSearch || '').toString().trim();
                if (!rawQuery) {
                    return this.activeSales;
                }
                const q = rawQuery.toLowerCase();
                
                return this.activeSales.filter(s => {
                    if (!s) return false;
                    const customerName = (s.customer && s.customer.name) ? String(s.customer.name).toLowerCase() : '';
                    const customerPhone = s.customer ? String(s.customer.phone || s.customer.phone_number || s.customer.mobile || '').toLowerCase() : '';
                    const saleNo = s.sale_number ? String(s.sale_number).toLowerCase() : '';
                    const unitDoor = (s.unit && s.unit.door_no) ? String(s.unit.door_no).toLowerCase() : '';
                    const saleUnits = (s.sale_units && s.sale_units.length) 
                        ? s.sale_units.map(su => (su.unit && su.unit.door_no) ? String(su.unit.door_no).toLowerCase() : '').join(' ')
                        : '';
                    const projectName = (s.project && s.project.name) ? String(s.project.name).toLowerCase() : '';

                    // Primary matching: Customer Name, Phone, Sale Number, Unit Number
                    const matchesPrimary = customerName.includes(q) || 
                                           customerPhone.includes(q) || 
                                           saleNo.includes(q) || 
                                           unitDoor.includes(q) ||
                                           saleUnits.includes(q);

                    // Project name matching only when query is 3+ letters
                    const matchesProject = q.length >= 3 && projectName.includes(q);

                    return matchesPrimary || matchesProject;
                }).sort((a, b) => {
                    const nameA = (a.customer && a.customer.name) ? String(a.customer.name).toLowerCase() : '';
                    const nameB = (b.customer && b.customer.name) ? String(b.customer.name).toLowerCase() : '';
                    const aStarts = nameA.startsWith(q);
                    const bStarts = nameB.startsWith(q);
                    if (aStarts && !bStarts) return -1;
                    if (!aStarts && bStarts) return 1;
                    return 0;
                });
            },

            get selectedModalSaleDisplay() {
                if (!this.form.booking_id) return '';
                const s = this.activeSales.find(s => s.id == this.form.booking_id);
                if (!s) return '';
                const cust = s.customer ? s.customer.name : 'Unknown Customer';
                const saleNo = s.sale_number || '';
                const proj = s.project ? s.project.name : '';
                return `${cust} — ${saleNo} (${proj})`;
            },

            selectModalSale(sale) {
                this.form.booking_id = sale ? sale.id : '';
                this.onModalSaleSelect();
                if (this.errors.booking_id) delete this.errors.booking_id;
                this.modalSaleDropdownOpen = false;
                this.modalSaleSearch = '';
            },

            clearModalSale() {
                this.form.booking_id = '';
                this.onModalSaleSelect();
                this.modalSaleSearch = '';
                this.modalSaleDropdownOpen = false;
            },

            init() {
                this.openCollectModal();
            },

            onModalSaleSelect() {
                const sale = this.activeSales.find(s => s.id == this.form.booking_id);
                if (sale) {
                    this.form.customer_name = sale.customer ? sale.customer.name : '-';
                    this.form.unit_number = sale.unit ? sale.unit.door_no : 'No Unit';
                    this.form.outstanding = sale.remaining_balance;
                    this.form.project_name = sale.project ? sale.project.name : '';
                    this.form.total_amount = sale.total_amount;
                    this.form.amount = '';
                } else {
                    this.form.customer_name = '';
                    this.form.unit_number = '';
                    this.form.outstanding = 0;
                    this.form.project_name = '';
                    this.form.total_amount = 0;
                    this.form.amount = '';
                }
            },

            openCollectModal(item) {
                this.errors = {};
                if (item && item.id) {
                    this.form.booking_id = item.id;
                    this.form.customer_name = item.customer_name;
                    this.form.unit_number = item.door_no;
                    this.form.outstanding = item.outstanding;
                    
                    const sale = this.activeSales.find(s => s.id == item.id);
                    if (sale) {
                        this.form.project_name = sale.project ? sale.project.name : '';
                        this.form.total_amount = sale.total_amount;
                    } else {
                        this.form.project_name = '';
                        this.form.total_amount = 0;
                    }
                } else {
                    this.form.booking_id = '';
                    this.form.customer_name = '';
                    this.form.unit_number = '';
                    this.form.outstanding = 0;
                    this.form.project_name = '';
                    this.form.total_amount = 0;
                }

                this.form.amount = '';
                this.form.payment_mode = 'Cash';
                this.form.receipt_date = new Date().toISOString().split('T')[0];
                this.form.reference_no = '';
                this.form.bank_id = '';
                this.form.remarks = '';
                this.form.collection_type = 'regular';
                this.form.prepayment_option = 'reduce_emi';
                this.form.reschedule_option = 'extend_tenure';
                this.form.reschedule_reason = '';
                this.modalSaleSearch = '';
                this.modalSaleDropdownOpen = false;
                this.modal.open = true;
            },

            closeCollectModal() {
                this.modal.open = false;
                this.modalSaleDropdownOpen = false;
                this.modalSaleSearch = '';
            },

            openViewModal(receipt) {
                this.selectedReceipt = receipt;
                this.viewModalOpen = true;
            },

            submitCollection() {
                this.errors = {};
                let hasError = false;

                if (!this.form.booking_id) {
                    this.errors.booking_id = ['please select active sale'];
                    hasError = true;
                }

                if (this.form.collection_type !== 'reschedule') {
                    if (this.form.amount === '' || this.form.amount === null || this.form.amount === undefined || parseFloat(this.form.amount) <= 0 || isNaN(parseFloat(this.form.amount))) {
                        this.errors.amount = ['please enter amount'];
                        hasError = true;
                    }
                    if (!this.form.payment_mode) {
                        this.errors.payment_mode = ['please select payment mode'];
                        hasError = true;
                    }
                    if (!this.form.receipt_date) {
                        this.errors.receipt_date = ['please select receipt date'];
                        hasError = true;
                    }
                } else {
                    if (!this.form.reschedule_reason) {
                        this.errors.reschedule_reason = ['please enter reschedule reason'];
                        hasError = true;
                    }
                }

                if (hasError) {
                    return;
                }
                
                fetch('{{ route('emi-collections.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: this.form.booking_id,
                        amount: this.form.amount,
                        payment_mode: this.form.payment_mode,
                        receipt_date: this.form.receipt_date,
                        reference_no: this.form.reference_no,
                        bank_id: this.form.bank_id,
                        remarks: this.form.remarks,
                        collection_type: this.form.collection_type,
                        prepayment_option: this.form.prepayment_option,
                        reschedule_option: this.form.reschedule_option,
                        reschedule_reason: this.form.reschedule_reason,
                        new_count: this.form.new_count,
                        shift_months: this.form.shift_months
                    })
                })
                .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
                .then(({ ok, status, data }) => {
                    if (ok && data.success) {
                        this.showToast(data.message, 'success');
                        this.closeCollectModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else if (data.errors) {
                        this.errors = data.errors;
                    } else if (data.error || data.message) {
                        this.showToast(data.error || data.message, 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.showToast('Something went wrong. Please try again.', 'error');
                });
            },

            showToast(message, type = 'success') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.open = true;
                setTimeout(() => {
                    this.toast.open = false;
                }, 4000);
            }
        };
    }
    </script>
</x-erp-layout>
