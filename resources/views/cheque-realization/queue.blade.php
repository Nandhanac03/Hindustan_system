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
        
        openProcessModal(r) { 
            this.targetReceipt = r;
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

        {{-- Header & Action Bar matching Receipt Management UI --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div>
                <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-300">Customer Management & Collections</span>
                    <span class="text-slate-300">›</span>
                    <span class="text-[#a38c29] font-black">Cheque Clearance Queue</span>
                </div>
                <p class="text-[11px] text-slate-500 font-medium mt-1.5">Instruments awaiting bank clearance. Mark as Realized to credit the treasury balance.</p>
            </div>
        </div>

        {{-- ── KPI EXECUTIVE CARDS BAR ── --}}
        @if(request()->routeIs('cheque-realization.realized'))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Card 1: Total Realized Amount (Emerald Accent) --}}
                <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20 cursor-default group">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                        <span>Total Realized Amount</span>
                        <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-slate-900">
                        ₹{{ number_format($totalPendingAmount ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-medium text-slate-400">Value of all realized instruments</div>
                </div>

                {{-- Card 2: Total Instruments (Sky Accent) --}}
                <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-sky-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(14,165,233,0.2)] hover:border-r-sky-500/20 hover:border-y-sky-500/20 cursor-default group">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                        <span>Realized Instruments</span>
                        <div class="w-6 h-6 rounded-md bg-sky-50 text-sky-600 border border-sky-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-sky-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-slate-900">
                        {{ $pendingReceipts->total() }}
                    </div>
                    <div class="text-[10px] font-medium text-slate-400">Total count of realized cheques</div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {{-- Card 1: Total Pending Amount (Gold Accent) --}}
                <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-[#a38c29] border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20 cursor-default group">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                        <span>Total Pending Amount</span>
                        <div class="w-6 h-6 rounded-md bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-slate-900">
                        ₹{{ number_format($totalPendingAmount ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-medium text-slate-400">{{ $pendingReceipts->total() }} Instruments Uncleared</div>
                </div>

                {{-- Card 2: Pending Action (Amber/Orange Accent) --}}
                <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-orange-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(249,115,22,0.2)] hover:border-r-orange-500/20 hover:border-y-orange-500/20 cursor-default group">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                        <span>Pending Action</span>
                        <div class="w-6 h-6 rounded-md bg-orange-50 text-orange-600 border border-orange-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-slate-900">
                        {{ $pendingReceipts->where('realization_status', 'pending')->count() }}
                    </div>
                    <div class="text-[10px] font-medium text-slate-400">Newly received, awaiting process</div>
                </div>

                {{-- Card 3: In Progress (Blue/Indigo Accent) --}}
                <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-600 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(37,99,235,0.2)] hover:border-r-blue-600/20 hover:border-y-blue-600/20 cursor-default group">
                    <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                        <span>In Progress</span>
                        <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </div>
                    </div>
                    <div class="text-base font-black font-mono text-slate-900">
                        {{ $pendingReceipts->whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->count() }}
                    </div>
                    <div class="text-[10px] font-medium text-slate-400">In hand or sent to bank</div>
                </div>
            </div>
        @endif

        {{-- Filters Bar --}}
        <form method="GET" action="{{ route('cheque-realization.queue') }}" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Company Bank Account</label>
                    <select name="company_bank_account_id" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                        <option value="">All Bank Accounts</option>
                        @foreach($companyBankAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ request('company_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                {{ $acc->bank_name }} {{ $acc->account_number ? '('.$acc->account_number.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Customer</label>
                    <div class="relative" x-data="{
                        open: false,
                        search: '',
                        selectedId: '{{ request('customer_id') }}',
                        customers: {{ $customers->map(function($c) { return ['id' => $c->id, 'name' => $c->name]; })->toJson() }},
                        get filteredCustomers() {
                            if (!this.search) return this.customers;
                            const term = this.search.toLowerCase();
                            return this.customers.filter(c => c.name.toLowerCase().includes(term));
                        },
                        get selectedCustomerName() {
                            const c = this.customers.find(c => c.id == this.selectedId);
                            return c ? c.name : 'All Customers';
                        }
                    }" @click.outside="open = false">
                        <input type="hidden" name="customer_id" :value="selectedId">
                        
                        <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.search.focus())" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] flex justify-between items-center text-left">
                            <span x-text="selectedCustomerName" class="truncate"></span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" 
                             x-transition
                             style="display: none;" 
                             class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 flex flex-col overflow-hidden">
                            <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0">
                                <input type="text" x-model="search" x-ref="search" placeholder="Search customer..." class="w-full px-2.5 py-2 text-xs font-medium border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#a38c29] focus:border-[#a38c29]">
                            </div>
                            <ul class="overflow-y-auto flex-1 p-1">
                                <li @click="selectedId = ''; open = false" class="px-3 py-2 text-xs cursor-pointer hover:bg-slate-50 rounded-lg transition-colors" :class="selectedId === '' ? 'bg-[#a38c29]/10 text-[#a38c29] font-bold' : 'text-slate-700'">
                                    All Customers
                                </li>
                                <template x-for="customer in filteredCustomers" :key="customer.id">
                                    <li @click="selectedId = customer.id; open = false" 
                                        class="px-3 py-2 text-xs cursor-pointer hover:bg-slate-50 rounded-lg truncate transition-colors"
                                        :class="selectedId == customer.id ? 'bg-[#a38c29]/10 text-[#a38c29] font-bold' : 'text-slate-700 font-medium'"
                                        x-text="customer.name"></li>
                                </template>
                                <li x-show="filteredCustomers.length === 0" class="px-3 py-4 text-xs text-center text-slate-400 font-medium">
                                    No customers found.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-[#a38c29] cursor-pointer">
                        🔍 FILTER
                    </button>
                </div>
            </div>
        </form>

        {{-- Status Legend --}}
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Status Legend:</span>
            <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[10px] font-black">⏳ Pending</span>
            <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 border border-sky-300 text-[10px] font-black">🖐 Cheque in Hand</span>
            <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 border border-blue-300 text-[10px] font-black">🏦 Deposited</span>
            <span class="text-[10px] text-slate-400 font-medium ml-2">→ Mark Realized to credit bank balance</span>
        </div>

        {{-- Main Table matching Receipt Management Table Styling --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 text-slate-900 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">
                        {{ request()->routeIs('cheque-realization.realized') ? 'REALIZED INSTRUMENTS' : 'INSTRUMENTS AWAITING CLEARANCE' }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Showing {{ $pendingReceipts->firstItem() }}–{{ $pendingReceipts->lastItem() }} of {{ $pendingReceipts->total() }} instruments</p>
                </div>
                <span class="px-3 py-1 rounded-full {{ request()->routeIs('cheque-realization.realized') ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200' }} text-xs font-black border uppercase tracking-wider">
                    {{ $pendingReceipts->total() }} {{ request()->routeIs('cheque-realization.realized') ? 'REALIZED' : 'PENDING' }}
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
                        @forelse($pendingReceipts as $receipt)
                            @php
                                $rObj = [
                                    'id' => $receipt->id,
                                    'receipt_no' => $receipt->receipt_no ?? 'RV/2025-26/'.str_pad($receipt->id, 6, '0', STR_PAD_LEFT),
                                    'ref' => $receipt->reference_no ?: 'REC-' . str_pad((string)$receipt->id, 5, '0', STR_PAD_LEFT),
                                    'customer_name' => $receipt->customer?->name ?? '—',
                                    'company_bank_account_name' => $receipt->companyBankAccount?->bank_name ?? '—',
                                    'amount' => $receipt->amount,
                                    'instrument_date' => $receipt->cheque_date ? $receipt->cheque_date->format('d/m/Y') : 'N/A',
                                    'drawee_bank' => $receipt->drawee_bank ?? 'N/A',
                                    'realization_status' => $receipt->realization_status,
                                    'company_bank_account_id' => $receipt->company_bank_account_id,
                                    'remarksText' => $receipt->realizationLogs->first()?->remarks ?? $receipt->remarks ?? '',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-all duration-150 border-l-4 border-l-amber-500">
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-900">
                                    {{ $rObj['ref'] }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900">{{ $receipt->customer?->name ?? '—' }}</td>
                                <td class="px-4 py-3.5">
                                    @if($receipt->companyBankAccount)
                                        <div class="font-extrabold text-slate-800">{{ $receipt->companyBankAccount->bank_name }}</div>
                                        @if($receipt->companyBankAccount->account_number)
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">Acc: {{ $receipt->companyBankAccount->account_number }}</div>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">—Not Assigned—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-700">{{ $receipt->drawee_bank ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $receipt->cheque_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3.5 font-mono font-black text-slate-950 text-right text-sm">
                                    ₹{{ number_format($receipt->amount, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-block
                                        {{ in_array($receipt->payment_mode, ['Cheque', 'CHEQUE', 'DD', 'Demand Draft (DD)']) ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $receipt->payment_mode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @php
                                        $statusNameLower = strtolower(str_replace('_', ' ', $receipt->realization_status));
                                        $matchedStatus = $chequeStatuses->first(function($item) use ($statusNameLower, $receipt) {
                                            return strtolower($item->name) === $statusNameLower || 
                                                   strtolower($item->name) === strtolower($receipt->realization_status);
                                        });

                                        $icons = [
                                            'pending'        => '⏳',
                                            'cheque_in_hand' => '🖐',
                                            'deposited'      => '🏦',
                                            'in_clearing'    => '🔄',
                                            'realized'       => '✅',
                                            'bounced'        => '❌',
                                            'cancelled'      => '🚫',
                                        ];
                                        $icon = $icons[$receipt->realization_status] ?? '•';

                                        $label = $matchedStatus ? $matchedStatus->name : (\App\Models\Receipt::STATUSES[$receipt->realization_status] ?? $receipt->realization_status);
                                        
                                        $colorCode = $matchedStatus ? strtolower($matchedStatus->color_code) : 'slate-500';
                                        if (!str_contains($colorCode, '-')) {
                                            $colorCode .= '-500';
                                        }
                                        $baseColor = explode('-', $colorCode)[0];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full bg-{{ $baseColor }}-100 text-{{ $baseColor }}-800 border border-{{ $baseColor }}-300 text-[10px] font-black uppercase tracking-wider inline-block">
                                        {{ $icon }} {{ $label }}
                                    </span>
                                </td>
                                @unless(request()->routeIs('cheque-realization.realized'))
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if(!in_array($receipt->realization_status, ['bounced', 'cancelled']))
                                                <button type="button" @click.prevent="openProcessModal({{ json_encode($rObj) }})" class="p-2 text-[#a38c29] hover:bg-[#a38c29]/10 hover:text-[#8a7522] rounded-lg transition-colors group relative cursor-pointer" title="Process Instrument">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-50">
                                                        Process
                                                    </span>
                                                </button>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Terminal State</span>
                                            @endif
                                        </div>
                                    </td>
                                @endunless
                            </tr>
                        @empty
                            <tr>
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
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pendingReceipts->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $pendingReceipts->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Unified Process Modal --}}
        <div x-show="processModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] border border-[#a38c29]" @click.away="processModalOpen = false">
                <div class="relative overflow-hidden rounded-t-xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-slate-700">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Cheque Clearance Queue</p>
                            <h2 class="text-lg font-extrabold text-white">Process Instrument</h2>
                        </div>
                        <button type="button" @click="processModalOpen = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form :action="formAction" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            
                            {{-- Left Column (Instrument Info) --}}
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Receipt No.</label>
                                    <input type="text" x-bind:value="targetReceipt ? targetReceipt.receipt_no : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                                </div>
                                
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Customer</label>
                                    <input type="text" x-bind:value="targetReceipt ? targetReceipt.customer_name : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                                </div>
                                
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Instrument No.</label>
                                    <input type="text" x-bind:value="targetReceipt ? targetReceipt.ref : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                                </div>
                                
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Instrument Date</label>
                                    <input type="text" x-bind:value="targetReceipt ? targetReceipt.instrument_date : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                                </div>
                                
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Bank of Instrument</label>
                                    <input type="text" x-bind:value="targetReceipt ? targetReceipt.drawee_bank : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                                </div>
                                
                                <div class="flex items-center">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Amount (₹)</label>
                                    <input type="text" x-bind:value="targetReceipt ? parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : ''" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700 font-semibold" readonly>
                                </div>
                            </div>

                            {{-- Right Column (Realization Info) --}}
                            <div class="space-y-4">
                                <div class="flex items-center" x-show="!['bounced', 'cancelled'].includes(statusName)">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Realization Date</label>
                                    <input type="date" name="realization_date" value="{{ date('Y-m-d') }}" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" x-bind:required="!['bounced', 'cancelled'].includes(statusName)">
                                </div>
                                
                                <div class="flex items-center" x-show="!['bounced', 'cancelled'].includes(statusName)">
                                    <label class="w-1/3 text-xs font-medium text-slate-600">Deposit To Account</label>
                                    <select name="company_bank_account_id" x-model="selectedBankId" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" x-bind:required="!['bounced', 'cancelled'].includes(statusName)">
                                        <option value="">Select Account</option>
                                        <template x-for="b in banks" :key="b.id">
                                            <option :value="b.id" x-text="b.bank_name + (b.account_number ? ' - ' + b.account_number.slice(-4) : '')"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Company Bank Account Details Card --}}
                                <div x-show="selectedBankId && !['bounced', 'cancelled'].includes(statusName)" class="mt-4 border border-slate-200 rounded-lg p-4 bg-slate-50" style="display: none;" x-transition>
                                    <h4 class="text-[11px] font-bold text-slate-800 mb-3 uppercase tracking-wide">Company Bank Account</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-xs text-slate-500">Bank Name</span>
                                            <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.bank_name || 'N/A'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-xs text-slate-500">Account Name</span>
                                            <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.account_name || 'N/A'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-xs text-slate-500">Account No.</span>
                                            <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.account_number || 'N/A'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-xs text-slate-500">Branch</span>
                                            <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.branch_name || 'N/A'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-8 border-slate-200">

                        {{-- Realization Details --}}
                        <h3 class="text-sm font-bold text-slate-800 mb-4">Realization Details</h3>
                        
                        <div class="space-y-4 max-w-3xl">
                            <div class="flex items-center">
                                <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Status</label>
                                <div class="w-2/3 md:w-3/4">
                                    <select x-model="selectedStatusId" @change="updateStatusName()" class="w-full px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Status</option>
                                        <template x-for="s in chequeStatuses" :key="s.id">
                                            <option :value="s.id" x-text="s.name"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="new_status" :value="mappedNewStatus" x-bind:disabled="!mappedNewStatus">
                                </div>
                            </div>
                            <div class="flex items-start">
                                <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600 pt-2" 
                                       x-text="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Reason for ' + (statusName.charAt(0).toUpperCase() + statusName.slice(1)) : 'Remarks'">Remarks</label>
                                <textarea name="remarks" rows="2" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" 
                                          :placeholder="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Enter reason...' : 'Enter remarks (Optional)'" x-model="remarksText"></textarea>
                            </div>
                            <div class="flex items-center">
                                <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Reference No.</label>
                                <input type="text" name="bank_reference_no" placeholder="e.g. NEFT/INWARD/5187" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="flex items-center">
                                <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Realized By</label>
                                <input type="text" value="{{ auth()->user()->name ?? 'Owner' }}" class="w-2/3 md:w-3/4 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-between pt-4 border-t border-slate-100">
                            <button type="button" @click="processModalOpen = false" class="px-5 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition cursor-pointer">
                                ← Back to Queue
                            </button>
                            <button type="submit" class="px-6 py-2 bg-[#a38c29] text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-[#8a7522] transition shadow-sm border border-[#a38c29] cursor-pointer">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-erp-layout>
