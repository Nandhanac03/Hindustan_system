<x-erp-layout title="Cheque Clearance Queue" headerTitle="Cheque Clearance Queue">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{
        processModalOpen: false,
        targetReceipt: null,
        selectedStatusId: '',
        statusName: '',
        selectedBankId: '',
        remarksText: '',
        banks: {{ json_encode($companyBankAccounts->map->only('id', 'bank_name', 'account_name', 'account_number', 'branch_name')) }},
        chequeStatuses: {{ json_encode($chequeStatuses->map->only('id', 'name', 'system_name')) }},
        formAction: '#',
        
        allReceipts: {{ json_encode($allReceiptsFormatted) }},
        filters: {
            customer_id: '{{ request('customer_id') }}',
            company_bank_account_id: '{{ request('company_bank_account_id') }}',
            status: '{{ request('status') }}',
            payment_mode: '{{ request('payment_mode') }}',
            date: '{{ request('date') }}',
        },
        currentPage: 1,
        perPage: 25,
        
        get filteredReceipts() {
            return this.allReceipts.filter(r => {
                if (this.filters.customer_id && String(r.customer_id) !== String(this.filters.customer_id)) return false;
                if (this.filters.company_bank_account_id && String(r.company_bank_account_id) !== String(this.filters.company_bank_account_id)) return false;
                if (this.filters.status && r.realization_status !== this.filters.status) return false;
                if (this.filters.payment_mode && r.payment_mode !== this.filters.payment_mode) return false;
                if (this.filters.date && r.date !== this.filters.date) return false;
                return true;
            });
        },
        
        get totalPages() {
            return Math.ceil(this.filteredReceipts.length / this.perPage) || 1;
        },
        
        get paginatedReceipts() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredReceipts.slice(start, start + this.perPage);
        },
        
        resetFilters() {
            this.filters = {
                customer_id: '',
                company_bank_account_id: '',
                status: '',
                payment_mode: '',
                date: '',
            };
            this.currentPage = 1;
        },
        
        openProcessModal(r) { 
            this.targetReceipt = {
                ...r,
                instrument_date: r.cheque_date_formatted || r.date_formatted || 'N/A'
            };
            this.selectedBankId = r.company_bank_account_id || '';
            this.remarksText = r.remarksText || '';
            
            let statusLower = (r.realization_status || '').replace(/_/g, ' ').toLowerCase();
            let matched = this.chequeStatuses.find(s => s.name.toLowerCase() === statusLower || s.system_name === r.realization_status);
            this.selectedStatusId = matched ? matched.id : '';
            this.updateStatusName();
            
            this.processModalOpen = true; 
        },
        
        updateStatusName() {
            let status = this.chequeStatuses.find(s => s.id == this.selectedStatusId);
            this.statusName = status ? status.name.toLowerCase() : '';
            
            if(!this.targetReceipt) return;
            
            if(this.statusName === 'realized') {
                this.formAction = `/cheque-realization/${this.targetReceipt.id}/realize`;
            } else if(this.statusName === 'bounced') {
                this.formAction = `/cheque-realization/${this.targetReceipt.id}/bounced`;
            } else {
                this.formAction = `/cheque-realization/${this.targetReceipt.id}/advance-status`;
            }
        },
        
        get mappedNewStatus() {
            const map = {'pending': 'pending', 'cancelled': 'cancelled', 'cheque in hand': 'cheque_in_hand', 'deposited': 'deposited', 'in clearing': 'in_clearing'};
            return map[this.statusName] || '';
        }
    }">

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
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold uppercase tracking-wide shadow-xs mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Validation Errors:</span>
                </div>
                <ul class="list-disc pl-8 lowercase normal-case text-[11px] font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── TOP EXECUTIVE KPI SUMMARY CARDS GRID ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Uncleared Instruments Amount --}}
            <a href="{{ route('cheque-realization.queue') }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer block">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Uncleared Amount</span>
                    </div>
                    <span class="text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                        {{ $totalPendingCount ?? 0 }} Pending
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        ₹{{ number_format($totalPendingAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Awaiting deposit & bank realization</p>
                </div>
            </a>

            {{-- Card 2: In Clearing / Deposited --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-default">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">In Clearing</span>
                    </div>
                    <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                        {{ $inProgressCount ?? 0 }} In Transit
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                        ₹{{ number_format($inProgressAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Instruments in hand or sent to bank</p>
                </div>
            </div>

            {{-- Card 3: Total Realized Collections --}}
            <a href="{{ route('cheque-realization.realized') }}"
               class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer block">
                <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Realized</span>
                    </div>
                    <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                        {{ $realizedCount ?? 0 }} Cleared
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        ₹{{ number_format($realizedAmount ?? 0, 2) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cleared & credited to company treasury</p>
                </div>
            </a>

            {{-- Card 4: Bounced Cheques --}}
            <a href="{{ route('cheque-realization.queue', ['status' => 'bounced']) }}"
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
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Dishonored entries requiring action</p>
                </div>
            </a>
        </div>

        {{-- ── ULTRA-CLEAN MODERN LIGHT SEARCH & FILTER PANEL (ZERO-RELOAD REACTIVE) ── --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
                    
                    {{-- 1. Customer Filter --}}
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

                    {{-- 2. Company Bank Account Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18v-7m4 7v-7m4 7v-7m4 7v-7M4 10l8-6 8 6"/></svg>
                        </div>
                        <select x-model="filters.company_bank_account_id" @change="currentPage = 1"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                            <option value="">All Bank Accounts</option>
                            @foreach($companyBankAccounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->bank_name }} {{ $acc->account_number ? '('.$acc->account_number.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- 3. Status Filter (Only on Queue page) --}}
                    @unless(request()->routeIs('cheque-realization.realized'))
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                            </div>
                            <select x-model="filters.status" @change="currentPage = 1"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                <option value="">All Realization Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="cheque_in_hand">Cheque In Hand</option>
                                <option value="deposited">Deposited</option>
                                <option value="in_clearing">In Clearing</option>
                                <option value="bounced">Bounced</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    @else
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <select x-model="filters.payment_mode" @change="currentPage = 1"
                                    class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                                <option value="">All Payment Modes</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Online">Online</option>
                                <option value="Cash">Cash</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    @endunless

                    {{-- 4. Date Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.date" @change="currentPage = 1"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
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

        {{-- Status Legend & Action Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 text-xs">
                <span class="font-bold text-slate-500 uppercase tracking-widest text-[10px]">Status Legend:</span>
                <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-600 font-bold border border-amber-200/60 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Pending</span>
                <span class="px-2 py-1 rounded-md bg-sky-50 text-sky-600 font-bold border border-sky-200/60 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg> Cheque in Hand</span>
                <span class="px-2 py-1 rounded-md bg-indigo-50 text-indigo-600 font-bold border border-indigo-200/60 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg> Deposited</span>
                <span class="text-slate-400 font-medium ml-2 flex items-center gap-1.5"><span class="text-slate-300">→</span> Mark Realized to credit bank balance</span>
            </div>
            
            <div class="flex items-center">
                @if(request()->routeIs('cheque-realization.realized'))
                    <a href="{{ route('cheque-realization.queue') }}" class="px-4 py-2 bg-[#a38c29] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md hover:bg-[#8a7522] transition-colors flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 text-white/80 group-hover:text-white group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        View Pending Queue
                    </a>
                @else
                    <a href="{{ route('cheque-realization.realized') }}" class="px-4 py-2 bg-[#a38c29] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md hover:bg-[#8a7522] transition-colors flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 text-white/80 group-hover:text-white group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        View Realized
                    </a>
                @endif
            </div>
        </div>

        {{-- Main Table matching Receipt Management Table Styling (Original Gold Header & Clean Rows) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 text-slate-900 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">
                        {{ request()->routeIs('cheque-realization.realized') ? 'REALIZED INSTRUMENTS' : 'INSTRUMENTS AWAITING CLEARANCE' }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5" x-text="`Showing ${filteredReceipts.length > 0 ? (currentPage - 1) * perPage + 1 : 0}–${Math.min(currentPage * perPage, filteredReceipts.length)} of ${filteredReceipts.length} instruments`"></p>
                </div>
                <span class="px-3 py-1 rounded-full {{ request()->routeIs('cheque-realization.realized') ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200' }} text-xs font-black border uppercase tracking-wider"
                      x-text="`${filteredReceipts.length} {{ request()->routeIs('cheque-realization.realized') ? 'REALIZED' : 'PENDING' }}`">
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[11px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-4 py-3.5 text-white">RECEIPT #</th>
                            <th class="px-4 py-3.5 text-white">DATE</th>
                            <th class="px-4 py-3.5 text-white">CUSTOMER</th>
                            <th class="px-4 py-3.5 text-white">COMPANY BANK ACCOUNT</th>
                            <th class="px-4 py-3.5 text-white">DRAWEE BANK</th>
                            <th class="px-4 py-3.5 text-white">CHEQUE DATE</th>
                            <th class="px-4 py-3.5 text-right text-white">AMOUNT</th>
                            <th class="px-4 py-3.5 text-center text-white">MODE</th>
                            <th class="px-4 py-3.5 text-center text-white">STATUS</th>
                            @unless(request()->routeIs('cheque-realization.realized'))
                                <th class="px-4 py-3.5 text-center text-white">ACTIONS</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                        <template x-for="receipt in paginatedReceipts" :key="receipt.id">
                            <tr class="hover:bg-slate-50 transition-all duration-150 border-l-4 border-l-amber-500">
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-900" x-text="receipt.ref"></td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium" x-text="receipt.date_formatted"></td>
                                <td class="px-4 py-3.5 font-bold text-slate-900" x-text="receipt.customer_name"></td>
                                <td class="px-4 py-3.5">
                                    <template x-if="receipt.company_bank_account_name && receipt.company_bank_account_name !== '—'">
                                        <div>
                                            <div class="font-extrabold text-slate-800" x-text="receipt.company_bank_account_name"></div>
                                            <div x-show="receipt.company_bank_account_number" class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="'Acc: ' + receipt.company_bank_account_number"></div>
                                        </div>
                                    </template>
                                    <template x-if="!receipt.company_bank_account_name || receipt.company_bank_account_name === '—'">
                                        <span class="text-slate-400 italic">—Not Assigned—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-700" x-text="receipt.drawee_bank"></td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium" x-text="receipt.cheque_date_formatted"></td>
                                <td class="px-4 py-3.5 font-mono font-black text-slate-950 text-right text-sm" x-text="'₹' + Number(receipt.amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-block"
                                          :class="['Cheque', 'CHEQUE', 'DD', 'Demand Draft (DD)'].includes(receipt.payment_mode) ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800'"
                                          x-text="receipt.payment_mode">
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-full border text-[10px] font-black uppercase tracking-wider inline-block"
                                          :class="receipt.status_badge_classes"
                                          x-text="`${receipt.status_icon} ${receipt.status_display_name}`">
                                    </span>
                                </td>
                                @unless(request()->routeIs('cheque-realization.realized'))
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center">
                                            <template x-if="!receipt.is_terminal">
                                                <button type="button" @click.prevent="openProcessModal(receipt)"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 border border-[#a38c29]/30 hover:shadow-sm group cursor-pointer">
                                                    <span>Process</span>
                                                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </button>
                                            </template>
                                            <template x-if="receipt.is_terminal">
                                                <span class="text-xs text-slate-400 italic">Terminal State</span>
                                            </template>
                                        </div>
                                    </td>
                                @endunless
                            </tr>
                        </template>
                        <tr x-show="filteredReceipts.length === 0">
                            <td colspan="{{ request()->routeIs('cheque-realization.realized') ? 9 : 10 }}" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-200">
                                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">All Clear!</h3>
                                    <p class="text-xs text-slate-500 font-medium">No pending cheques awaiting realization. All instruments have been processed.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Controls (Zero Reload) --}}
            <div x-show="totalPages > 1" class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <div class="text-xs font-semibold text-slate-500">
                    Page <span class="font-black text-slate-800" x-text="currentPage"></span> of <span class="font-black text-slate-800" x-text="totalPages"></span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="currentPage--" :disabled="currentPage === 1"
                            class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        ‹ Prev
                    </button>
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" @click="currentPage = p"
                                x-show="p === 1 || p === totalPages || Math.abs(p - currentPage) <= 2"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'"
                                x-text="p">
                        </button>
                    </template>
                    <button type="button" @click="currentPage++" :disabled="currentPage === totalPages"
                            class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Next ›
                    </button>
                </div>
            </div>
        </div>

        {{-- Unified Process Modal (Matched to Collection Receipt Entry Modal) --}}
        <template x-teleport="body">
            <div x-show="processModalOpen" 
                 class="fixed inset-0 top-0 left-0 w-screen h-screen z-[99999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto" 
                 style="display: none;" 
                 x-transition.opacity>
                <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all my-auto" @click.away="processModalOpen = false">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-slate-950 via-[#2a2415] to-slate-950 px-7 py-5 text-white flex items-center justify-between relative overflow-hidden">
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 text-[#f3e5ab] flex items-center justify-center text-lg font-black shadow-inner border border-[#a38c29]/30">
                                ₹
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40">CHEQUE REALIZATION</span>
                                    <span class="text-[10px] text-slate-400 font-semibold">Clearance Queue</span>
                                </div>
                                <h3 class="font-black text-base uppercase tracking-wider text-white mt-0.5">Process Cheque Clearance</h3>
                            </div>
                        </div>
                        <button type="button" @click="processModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-sm transition cursor-pointer relative z-10" title="Close Modal">✕</button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="bg-white p-6 sm:p-7 space-y-5">
                        <form :action="formAction" method="POST" id="processModalForm">
                            @csrf
                            
                            {{-- 1. Top Receipt & Cheque Overview Strip --}}
                            <div class="bg-slate-50/90 rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
                                <div class="flex items-center justify-between pb-3 mb-3.5 border-b border-slate-200/70">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Cheque & Receipt Details</span>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-[#a38c29]/10 text-[#a38c29] font-mono text-[11px] font-extrabold border border-[#a38c29]/30 shadow-2xs" x-text="targetReceipt ? ('Cheque #' + targetReceipt.ref) : ''"></span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Customer</span>
                                        <span class="text-xs font-black text-slate-900 block truncate" x-text="targetReceipt ? targetReceipt.customer_name : '—'"></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Voucher No.</span>
                                        <span class="text-xs font-mono font-bold text-slate-700 block whitespace-nowrap overflow-hidden text-ellipsis" x-text="targetReceipt ? targetReceipt.receipt_no : '—'"></span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Amount</span>
                                        <span class="text-base font-black font-mono text-[#a38c29] block" x-text="targetReceipt ? '₹' + parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : '₹0.00'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Realization & Status Action Form Grid --}}
                            <div class="pt-2">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                        Clearance Details & Actions
                                    </h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-xs">
                                    
                                    {{-- Left Column --}}
                                    <div class="space-y-4">
                                        {{-- Target Status --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]">
                                                Target Status <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <select x-model="selectedStatusId" @change="updateStatusName()" 
                                                        class="w-full h-10 pl-3.5 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition shadow-2xs appearance-none" required>
                                                <option value="">-- Select Status --</option>
                                                <template x-for="s in chequeStatuses" :key="s.id">
                                                    <option :value="s.id" x-text="s.name"></option>
                                                </template>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>
                                            <input type="hidden" name="new_status" :value="mappedNewStatus" x-bind:disabled="!mappedNewStatus">
                                        </div>

                                        {{-- Realization Date --}}
                                        <div x-show="!['bounced', 'cancelled'].includes(statusName)">
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]">
                                                Realization / Deposit Date <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="date" name="realization_date" value="{{ date('Y-m-d') }}" 
                                                   class="w-full h-10 px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition shadow-2xs" 
                                                   x-bind:required="!['bounced', 'cancelled'].includes(statusName)">
                                        </div>

                                        {{-- Deposit To Account --}}
                                        <div x-show="!['bounced', 'cancelled'].includes(statusName)">
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]">
                                                Deposit To Company Account <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <select name="company_bank_account_id" x-model="selectedBankId" 
                                                        class="w-full h-10 pl-3.5 pr-8 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition shadow-2xs appearance-none" 
                                                        x-bind:required="!['bounced', 'cancelled'].includes(statusName)">
                                                    <option value="">-- Select Company Account --</option>
                                                    <template x-for="b in banks" :key="b.id">
                                                        <option :value="b.id" x-text="b.bank_name + (b.account_number ? ' - ' + b.account_number.slice(-4) : '')"></option>
                                                    </template>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Company Bank Account Details Card (Clean 2x2 Grid) --}}
                                        <div x-show="selectedBankId && !['bounced', 'cancelled'].includes(statusName)" class="rounded-xl p-3.5 bg-slate-50 border border-slate-200/70 shadow-2xs mt-2" style="display: none;" x-transition>
                                            <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-[11px]">
                                                <div>
                                                    <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider block">Bank</span>
                                                    <span class="font-bold text-slate-800 truncate block" x-text="banks.find(b => b.id == selectedBankId)?.bank_name || '—'"></span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider block">Account No</span>
                                                    <span class="font-mono font-bold text-slate-800 block" x-text="banks.find(b => b.id == selectedBankId)?.account_number || '—'"></span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider block">Account Name</span>
                                                    <span class="font-semibold text-slate-700 truncate block" x-text="banks.find(b => b.id == selectedBankId)?.account_name || '—'"></span>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 font-bold text-[9px] uppercase tracking-wider block">Branch</span>
                                                    <span class="font-semibold text-slate-700 truncate block" x-text="banks.find(b => b.id == selectedBankId)?.branch_name || '—'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column --}}
                                    <div class="space-y-4">
                                        {{-- Reference No --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]">
                                                Bank Reference / UTR No.
                                            </label>
                                            <input type="text" name="bank_reference_no" placeholder="e.g. NEFT/INWARD/5187 or CTS Ref" 
                                                   class="w-full h-10 px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                                        </div>

                                        {{-- Realized By --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]">
                                                Processed By
                                            </label>
                                            <input type="text" value="{{ auth()->user()->name ?? 'Owner' }}" 
                                                   class="w-full h-10 px-3.5 bg-slate-100/90 border border-slate-200/80 rounded-xl text-xs font-bold text-slate-700 cursor-not-allowed" readonly>
                                        </div>

                                        {{-- Remarks / Reason (Exact Same Height h-10 as Left Column) --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wide text-[10px]" 
                                                   x-text="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Reason for ' + (statusName.charAt(0).toUpperCase() + statusName.slice(1)) : 'Audit Remarks (Optional)'">Remarks</label>
                                            <input type="text" name="remarks" 
                                                   class="w-full h-10 px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-medium text-slate-800 focus:outline-none transition shadow-2xs" 
                                                   :placeholder="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Enter reason for this state...' : 'Enter any clearing notes or remarks...'" x-model="remarksText">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- 3. Modal Footer Action Bar (Aligned Heights) --}}
                            <div class="mt-6 pt-5 flex items-center justify-between border-t border-slate-100">
                                <button type="button" @click="processModalOpen = false" 
                                        class="h-10 px-6 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center cursor-pointer shadow-2xs">
                                    ← Back to Queue
                                </button>
                                <button type="submit" 
                                        class="h-10 px-8 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 shadow-md shadow-[#a38c29]/20 hover:shadow-lg active:scale-95 flex items-center justify-center cursor-pointer">
                                    SUBMIT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

    </div>

</x-erp-layout>
