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
        <div class="flex items-center justify-end gap-4 -mt-2">
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

                    {{-- 3. Payment Mode Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <select x-model="filters.payment_mode" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Payment Modes</option>
                            @if(isset($paymentModes) && count($paymentModes) > 0)
                                @foreach($paymentModes as $pm)
                                    <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                @endforeach
                            @else
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Online">Online</option>
                            @endif
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
                            <th class="px-3.5 py-3.5 w-[190px]">CUSTOMER</th>
                            <th class="px-3.5 py-3.5 w-[170px]">COMPANY BANK ACCOUNT</th>
                            <th class="px-3.5 py-3.5 w-[150px]">CUSTOMER BANK</th>
                            <th class="px-3.5 py-3.5 text-right w-[120px]">AMOUNT</th>
                            <th class="px-3.5 py-3.5 text-center w-[110px]">MODE</th>
                            <th class="px-3.5 py-3.5 text-center w-[150px]">REALIZATION STATUS</th>
                            <th class="px-3.5 py-3.5 text-center w-[100px]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold bg-white text-slate-700">
                        <template x-for="r in paginatedReceipts" :key="r.id">
                            <tr @click="openViewModal(r)" class="hover:bg-amber-50/40 transition-colors border-b border-slate-100 cursor-pointer">
                                <td class="px-3.5 py-3.5 text-left font-mono font-bold text-slate-900" x-text="r.ref"></td>

                                <td class="px-3.5 py-3.5 text-left text-slate-600 font-medium" x-text="r.date"></td>

                                <td class="px-3.5 py-3.5 text-left">
                                    <div class="font-black text-slate-900 text-[11.5px]" x-text="r.customer_name"></div>
                                    <template x-if="r.unit_floor_info">
                                        <div class="text-[9.5px] text-slate-500 font-semibold mt-0.5" 
                                             x-text="r.unit_floor_info">
                                        </div>
                                    </template>
                                </td>

                                <td class="px-3.5 py-3.5 text-left">
                                    <template x-if="r.company_bank_account_name && r.company_bank_account_name !== '—'">
                                        <div>
                                            <div class="font-extrabold text-slate-800 text-[11px]" x-text="r.company_bank_account_name"></div>
                                            <template x-if="r.company_bank_account_number">
                                                <div class="text-[9.5px] font-mono text-slate-500" x-text="'A/C: ' + r.company_bank_account_number"></div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!r.company_bank_account_name || r.company_bank_account_name === '—'">
                                        <span class="text-slate-400 italic text-[11px]">—Not Assigned—</span>
                                    </template>
                                </td>

                                <td class="px-3.5 py-3.5 text-left">
                                    <span class="font-bold text-slate-700 text-[11px]" x-text="r.customer_bank || r.drawee_bank || '—'"></span>
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

                                <td class="px-3.5 py-3.5 text-center" @click.stop>
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        {{-- View Details Icon Button (Theme Color) --}}
                                        <button type="button" 
                                                @click="openViewModal(r)" 
                                                title="View Receipt Details"
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] border border-[#a38c29]/20 hover:border-[#a38c29]/40 transition inline-flex items-center justify-center shadow-sm cursor-pointer"
                                                aria-label="View Receipt">
                                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        {{-- PDF Download Icon Button (Green Color) --}}
                                        <button type="button" 
                                                @click="downloadReceiptPdf(r)" 
                                                title="Download Receipt PDF"
                                                class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] border border-[#09876B]/20 hover:border-[#09876B]/40 transition inline-flex items-center justify-center shadow-sm cursor-pointer"
                                                aria-label="Download Receipt PDF">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredReceipts.length === 0">
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 italic font-medium">
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
                <div class="w-full max-w-4xl bg-slate-950 rounded-3xl shadow-2xl overflow-hidden border border-slate-800/80 transform transition-all my-auto" @click.away="closeCollectModal()">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-7 py-5 text-white flex items-center justify-between relative overflow-hidden rounded-t-3xl">
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
                    
                    <form @submit.prevent="submitCollection()" novalidate class="flex flex-col bg-white rounded-b-3xl">
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

                            {{-- Row 2: Action Type & Payment Mode --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                                {{-- Action Type --}}
                                <div class="space-y-1.5">
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

                                {{-- Payment Mode Dropdown --}}
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Payment Mode <span class="text-rose-500">*</span></label>
                                    <select x-model="form.payment_mode" @change="if(errors.payment_mode) delete errors.payment_mode;"
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs cursor-pointer"
                                            :class="errors.payment_mode ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : ''">
                                        @if(isset($paymentModes) && count($paymentModes) > 0)
                                            @foreach($paymentModes as $pm)
                                                <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Online">Online</option>
                                        @endif
                                    </select>
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
                                    <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider block">Ref / Cheque / UTR No. <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="form.reference_no" placeholder="Enter Ref / Cheque / UTR No..."
                                           @input="if(errors.reference_no) delete errors.reference_no;"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none rounded-xl text-xs font-bold text-slate-900 transition-all shadow-xs"
                                           :class="errors.reference_no ? 'border-rose-500 bg-rose-50/20 ring-2 ring-rose-500/20' : ''">
                                    <template x-if="errors.reference_no">
                                        <span class="text-[10px] text-rose-600 font-bold block mt-1" x-text="Array.isArray(errors.reference_no) ? errors.reference_no[0] : errors.reference_no"></span>
                                    </template>
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

        <!-- ── MODAL: READ-ONLY RECEIPT DISPLAY DETAILS (STANDARD ERP THEME) ── -->
        <template x-teleport="body">
            <div x-show="viewModalOpen" 
                 class="fixed inset-0 top-0 left-0 w-screen h-screen z-[99999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" 
                 style="display: none;" 
                 x-transition.opacity>
                <div class="w-full max-w-2xl bg-slate-950 rounded-2xl shadow-2xl overflow-hidden border border-slate-800/80 animate-fade-in-up my-auto" @click.away="viewModalOpen = false">
                    {{-- Header --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 border-b border-[#a38c29]/10 rounded-t-2xl">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between gap-4">
                            <div>
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Receipt Voucher</span>
                                <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5" x-text="selectedReceipt ? selectedReceipt.ref : 'Receipt Details'"></h2>
                            </div>
                            <button type="button" @click="viewModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer">✕</button>
                        </div>
                    </div>

                    <template x-if="selectedReceipt">
                        <div class="bg-white rounded-b-2xl">
                            <div class="p-5 space-y-3 bg-slate-50/50 text-xs font-sans">
                                {{-- Card 1: Top Hero Summary (Customer, Amount & Status in 1 Horizontal Card) --}}
                                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                    <div class="sm:col-span-5">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Customer / Payer</span>
                                        <span class="text-sm font-extrabold text-slate-900 block truncate" x-text="selectedReceipt?.customer_name || 'General Payer'"></span>
                                        <span class="text-[10px] text-slate-500 font-medium block truncate mt-0.5" 
                                              x-text="(selectedReceipt?.project_name || '—') + (selectedReceipt?.unit_name && selectedReceipt?.unit_name !== '—' ? ' (' + selectedReceipt.unit_name + ')' : '')"></span>
                                    </div>
                                    <div class="sm:col-span-4 sm:border-l sm:border-slate-100 sm:pl-3">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Amount</span>
                                        <span class="text-base font-extrabold text-slate-900 font-mono mt-0.5 block" x-text="'₹' + Number(selectedReceipt?.amount || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                                    </div>
                                    <div class="sm:col-span-3 sm:text-right sm:border-l sm:border-slate-100 sm:pl-3">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status</span>
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block border"
                                              :class="selectedReceipt?.status_badge_classes || 'bg-slate-100 text-slate-700 border-slate-200'"
                                              x-text="selectedReceipt?.status_display_name || 'PENDING'"></span>
                                    </div>
                                </div>

                                {{-- Card 2: 4-Column Metadata Grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Date</span>
                                        <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedReceipt?.date || '—'"></span>
                                    </div>
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</span>
                                        <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedReceipt?.payment_mode || 'Cash'"></span>
                                    </div>
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Cheque / Ref No</span>
                                        <span class="text-xs font-mono font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedReceipt?.reference_no || '—'"></span>
                                    </div>
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Company Bank A/C</span>
                                        <span class="text-xs font-bold text-slate-900 mt-0.5 block truncate" x-text="selectedReceipt?.company_bank_account_name || '—Not Assigned—'"></span>
                                        <span class="text-[9px] font-mono text-slate-500 block truncate" x-show="selectedReceipt?.company_bank_account_number" x-text="'A/C: ' + selectedReceipt?.company_bank_account_number"></span>
                                    </div>
                                </div>

                                {{-- Card 3: Customer Bank & Remarks Details --}}
                                <div class="grid grid-cols-1" :class="(selectedReceipt?.customer_bank || selectedReceipt?.drawee_bank) ? 'sm:grid-cols-12 gap-2.5' : ''">
                                    <template x-if="selectedReceipt?.customer_bank || selectedReceipt?.drawee_bank">
                                        <div class="sm:col-span-4 p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Customer Bank</span>
                                            <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="selectedReceipt?.customer_bank || selectedReceipt?.drawee_bank"></span>
                                        </div>
                                    </template>
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-sm" :class="(selectedReceipt?.customer_bank || selectedReceipt?.drawee_bank) ? 'sm:col-span-8' : 'w-full'">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Remarks Details</span>
                                        <span class="text-xs font-medium text-slate-700 mt-0.5 block italic truncate" x-text="selectedReceipt?.remarks || 'No remarks provided'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between bg-slate-50 rounded-b-2xl">
                                <button type="button" 
                                        @click="downloadReceiptPdf(selectedReceipt)" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/>
                                    </svg>
                                    <span>Download Receipt PDF</span>
                                </button>
                                <button type="button" @click="viewModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide cursor-pointer">Close</button>
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

            paymentModesList: @json($paymentModes ?? []),
            banksList: @json($banks ?? []),
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

            isPaymentModeExpanded(mode) {
                if (!mode) return false;
                const m = String(mode).toLowerCase().trim();
                if (m === 'cash') return false;

                if (Array.isArray(this.paymentModesList) && this.paymentModesList.length > 0) {
                    const found = this.paymentModesList.find(p => 
                        (p.name && p.name.toLowerCase() === m) || 
                        (p.code && p.code.toLowerCase() === m)
                    );
                    if (found) {
                        return Boolean(found.requires_reference || found.requires_bank || (found.code && found.code.toUpperCase() !== 'CASH'));
                    }
                }
                return !m.includes('cash');
            },

            isUpiMode(mode) {
                if (!mode) return false;
                const m = String(mode).toLowerCase().trim();
                return m.includes('upi');
            },

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
                        const unitFloor = (r.unit_floor_info || '').toLowerCase();
                        const doorNo = (r.door_no || '').toLowerCase();
                        const floorName = (r.floor_name || '').toLowerCase();
                        const bank = (r.company_bank_account_name || '').toLowerCase();
                        const acc = (r.company_bank_account_number || '').toLowerCase();
                        const remarks = (r.remarks || '').toLowerCase();
                        const chequeNo = (r.reference_no || '').toLowerCase();
                        const drawee = (r.customer_bank || r.drawee_bank || '').toLowerCase();
                        const amount = (r.amount || '').toString();
                        return ref.includes(q) || cust.includes(q) || payer.includes(q) || proj.includes(q) || unit.includes(q) || unitFloor.includes(q) || doorNo.includes(q) || floorName.includes(q) || bank.includes(q) || acc.includes(q) || remarks.includes(q) || chequeNo.includes(q) || drawee.includes(q) || amount.includes(q);
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

                    const matchesPrimary = customerName.includes(q) || 
                                           customerPhone.includes(q) || 
                                           saleNo.includes(q) || 
                                           unitDoor.includes(q) ||
                                           saleUnits.includes(q);

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

            downloadReceiptPdf(receipt) {
                if (!receipt) return;

                const escapeHtml = (str) => {
                    if (!str) return '';
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                };

                const amountNum = parseFloat(receipt.amount) || 0;
                const amountFormatted = '₹' + amountNum.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                let amountWords = typeof window.convertNumberToWords === 'function' ? window.convertNumberToWords(amountNum) : '';
                if (amountWords) {
                    amountWords = amountWords.trim();
                    if (amountWords.toLowerCase().endsWith('only')) {
                        amountWords = amountWords.slice(0, -4).trim();
                    }
                    amountWords = amountWords.charAt(0).toUpperCase() + amountWords.slice(1) + ' Only';
                }

                const refNo = escapeHtml(receipt.ref || 'RECEIPT');
                const dateVal = escapeHtml(receipt.date || '—');
                const custName = escapeHtml(receipt.customer_name || 'General Payer');
                const custPhone = escapeHtml(receipt.customer_phone || '—');
                const projName = escapeHtml(receipt.project_name || '—');
                const projectCompany = escapeHtml(receipt.project_company_name || receipt.project_name || 'HINDUSTAN REAL ESTATE & INFRASTRUCTURE DEVELOPERS PVT. LTD.');
                const projectSubtitle = escapeHtml(receipt.project_company_subtitle || 'Real Estate Development, Project Management & Infrastructure Solutions');
                const projectLogo = receipt.project_logo || '';
                const unitName = escapeHtml(receipt.unit_name && receipt.unit_name !== '—' ? receipt.unit_name : 'General Unit');
                const payMode = escapeHtml(receipt.payment_mode || 'Cash');
                const statusName = escapeHtml(receipt.status_display_name || receipt.realization_status || 'Realized');
                const bankName = escapeHtml(receipt.company_bank_account_name || '—Not Assigned—');
                const bankAccNo = receipt.company_bank_account_number ? escapeHtml(receipt.company_bank_account_number) : '';
                const instRefNo = escapeHtml(receipt.reference_no || 'N/A');
                const draweeBank = (receipt.customer_bank || receipt.drawee_bank) ? escapeHtml(receipt.customer_bank || receipt.drawee_bank) : '';
                const realizedAt = receipt.realized_at ? escapeHtml(receipt.realized_at) : '';
                const remarksText = escapeHtml(receipt.remarks || 'Initial payment at sale creation / property installment.');

                const printWin = window.open('', '_blank', 'width=940,height=960,top=30,left=100');
                if (!printWin) {
                    alert('Please allow popups to preview and download the receipt PDF.');
                    return;
                }

                const html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt — ${refNo}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    ` + '<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></scr' + 'ipt>' + `
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 24px 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* Top Page Navigation Bar */
        .top-nav {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            color: #334155;
        }
        .nav-title {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.3px;
        }
        .nav-sub {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #a38c29;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(163, 140, 41, 0.25);
            transition: all 0.2s ease;
        }
        .btn-download:hover {
            background: #8e7921;
            transform: translateY(-1px);
        }
        .btn-download:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        .btn-close {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-close:hover {
            background: #f1f5f9;
        }

        /* White Receipt Sheet Card */
        .receipt-card {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2dcd0;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 6px 24px -4px rgba(15, 23, 42, 0.08);
        }

        /* Sleek Slate Header Banner - Flush with top and side corners */
        .company-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 0;
            padding: 22px 28px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            border-bottom: 1.5px solid #334155;
        }
        .hero-left {
            display: flex;
            align-items: center;
            max-width: 65%;
        }
        .company-name {
            font-size: 15.5px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.35;
        }
        .hero-right {
            text-align: right;
        }
        .receipt-pill-title {
            font-size: 15px;
            font-weight: 900;
            color: #e2b855;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .receipt-no-row {
            margin-top: 4px;
            font-size: 12.5px;
        }
        .no-lbl {
            color: #94a3b8;
            font-weight: 500;
            margin-right: 6px;
        }
        .no-val {
            color: #ffffff;
            font-weight: 900;
            font-size: 14.5px;
        }

        /* Inner Receipt Padding Container */
        .receipt-body {
            padding: 22px 24px;
        }

        /* 4 Metadata Horizontal Strip */
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            background: #fcfbf8;
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 18px;
        }
        .meta-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .meta-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }
        .meta-label {
            font-size: 8.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .status-pill {
            display: inline-block;
            background: #e6f7ec;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-size: 9.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* 2 Column Details Grid */
        .grid-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 18px;
        }
        .detail-box {
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }
        .box-head {
            background: #fcfbf8;
            padding: 10px 16px;
            font-size: 11.5px;
            font-weight: 800;
            color: #8c733e;
            border-bottom: 1.5px solid #ebe5d8;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .box-body {
            padding: 8px 16px;
        }
        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f6f2ea;
            font-size: 11.5px;
        }
        .field-row:last-child {
            border-bottom: none;
        }
        .f-lbl {
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
        }
        .f-val {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
            max-width: 62%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Metallic Golden Amount Received Banner */
        .amount-banner {
            background: linear-gradient(135deg, #dfb858 0%, #fae69e 45%, #d1a038 100%);
            border: 1.5px solid #c99b32;
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            box-shadow: 0 4px 12px rgba(184, 138, 37, 0.18);
        }
        .amount-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }
        .coin-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #fff2a8, #d4af37 45%, #96741b 85%, #634d10 100%);
            border: 1.5px solid #ffea88;
            box-shadow: 0 4px 8px rgba(150, 116, 27, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .coin-inner {
            font-size: 21px;
            font-weight: 900;
            color: #4a3809;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8), 0 -1px 1px rgba(0, 0, 0, 0.4);
        }
        .amt-words-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-words-val {
            font-size: 12.5px;
            font-weight: 800;
            color: #1c1505;
            font-style: italic;
            margin-top: 2px;
            line-height: 1.35;
        }
        .amount-divider {
            width: 1.5px;
            height: 42px;
            background: #a98020;
            margin: 0 20px;
            flex-shrink: 0;
        }
        .amount-right {
            text-align: right;
            flex-shrink: 0;
        }
        .amt-total-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-total-val {
            font-size: 25px;
            font-weight: 900;
            color: #110e05;
            margin-top: 2px;
            letter-spacing: -0.5px;
        }

        /* Print Media Styling */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #ffffff;
                padding: 0;
            }
            .top-nav {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                border: 1.5px solid #ebe5d8;
                border-radius: 14px;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <div class="nav-left">
            <div class="nav-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div>
                <h1 class="nav-title">Payment Receipt</h1>
                <p class="nav-sub">View and manage customer payment receipt details</p>
            </div>
        </div>
        <div class="nav-actions">
            <button onclick="downloadDirectPdf()" class="btn-download">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Download PDF</span>
            </button>
            <button onclick="window.close()" class="btn-close">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="receipt-card">
        <div class="company-hero">
            <div class="hero-left">
                <div class="company-name">${projectCompany}</div>
            </div>

            <div class="hero-right">
                <div class="receipt-pill-title">PAYMENT RECEIPT</div>
                <div class="receipt-no-row">
                    <span class="no-lbl">Receipt No.</span>
                    <span class="no-val mono">${refNo}</span>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="meta-strip">
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#ecfdf5; color:#059669;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">RECEIPT NUMBER</span>
                        <span class="meta-value mono">${refNo}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fff1f2; color:#f43f5e;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">RECEIPT DATE</span>
                        <span class="meta-value">${dateVal}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fffbeb; color:#d97706;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">PAYMENT MODE</span>
                        <span class="meta-value">${payMode}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#f0fdfa; color:#0d9488;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">REALIZATION STATUS</span>
                        <span class="status-pill">${statusName}</span>
                    </div>
                </div>
            </div>

            <div class="grid-details">
                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Customer &amp; Property Details</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Received From</span>
                            <span class="f-val">${custName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Contact Number</span>
                            <span class="f-val">${custPhone}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Project / Site</span>
                            <span class="f-val">${projName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Unit / Door No</span>
                            <span class="f-val">${unitName}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="5 10 12 3 19 10"></polyline><line x1="6" y1="10" x2="6" y2="21"></line><line x1="10" y1="10" x2="10" y2="21"></line><line x1="14" y1="10" x2="14" y2="21"></line><line x1="18" y1="10" x2="18" y2="21"></line></svg>
                        <span>Payment &amp; Banking Information</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Cheque / Ref / UTR</span>
                            <span class="f-val mono">${instRefNo}</span>
                        </div>
                        ${draweeBank ? `
                        <div class="field-row">
                            <span class="f-lbl">Customer Bank</span>
                            <span class="f-val">${draweeBank}</span>
                        </div>` : ''}
                        ${realizedAt ? `
                        <div class="field-row">
                            <span class="f-lbl">Realized Date</span>
                            <span class="f-val">${realizedAt}</span>
                        </div>` : ''}
                    </div>
                </div>
            </div>

            <div class="amount-banner">
                <div class="amount-left">
                    <div class="coin-badge">
                        <div class="coin-inner">₹</div>
                    </div>
                    <div>
                        <div class="amt-words-lbl">Amount Received (in Words)</div>
                        <div class="amt-words-val">${amountWords || '—'}</div>
                    </div>
                </div>
                <div class="amount-divider"></div>
                <div class="amount-right">
                    <div class="amt-total-lbl">Total Received</div>
                    <div class="amt-total-val mono">${amountFormatted}</div>
                </div>
            </div>
        </div>
    </div>

    ` + '<scr' + 'ipt>' + `
    function downloadDirectPdf() {
        const element = document.querySelector('.receipt-card');
        const btn = document.querySelector('.btn-download');
        if (!element) return;

        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Downloading...</span>';
        btn.disabled = true;

        const opt = {
            margin: [8, 8, 8, 8],
            filename: 'Payment_Receipt_${refNo}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        if (typeof html2pdf !== 'undefined') {
            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = '<span>✓ Downloaded</span>';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2500);
            }).catch(err => {
                console.error('PDF Error:', err);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            alert('PDF generator is loading. Please try again.');
        }
    }
    ` + '</scr' + 'ipt>' + `
</body>
</html>`;

                printWin.document.open();
                printWin.document.write(html);
                printWin.document.close();
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
                    if (!this.form.reference_no || !this.form.reference_no.toString().trim()) {
                        this.errors.reference_no = ['please enter ref / cheque / utr no'];
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
