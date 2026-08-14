<x-erp-layout title="Cheque Clearance Queue" headerTitle="Cheque Clearance Queue">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{
        realizeModalOpen: false,
        bounceModalOpen: false,
        targetReceipt: null,
        openRealizeModal(r) { this.targetReceipt = r; this.realizeModalOpen = true; },
        openBounceModal(r) { this.targetReceipt = r; this.bounceModalOpen = true; }
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

        {{-- Header & Action Bar matching Receipt Management UI --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div>
                <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-300">Finance & Accounting</span>
                    <!-- <a href="{{ route('receipt-management.index') }}" class="hover:text-slate-600 transition">Receipt Management</a> -->
                    <span class="text-slate-300">›</span>
                    <span class="text-[#a38c29] font-black">Cheque Clearance Queue</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 mt-1">⏳ Cheque Clearance Queue</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Instruments awaiting bank clearance. Mark as Realized to credit the treasury balance.</p>
            </div>
        </div>

        {{-- ── KPI EXECUTIVE CARDS BAR ── --}}
        {{-- ── KPI EXECUTIVE CARDS BAR ── --}}
        @if(request()->routeIs('cheque-realization.realized'))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Card 1: Total Realized Amount (Emerald Accent) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Total Realized Amount</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalPendingAmount ?? 0, 2) }}</h3>
                        <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">Value of all realized instruments</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                {{-- Card 2: Total Instruments (Sky Accent) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-sky-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-sky-700">Realized Instruments</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $pendingReceipts->total() }}</h3>
                        <span class="text-[10px] text-sky-700 font-bold block mt-0.5">Total count of realized cheques</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Card 1: Total Pending Amount (Gold Accent) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#a38c29]">Total Pending Amount</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalPendingAmount ?? 0, 2) }}</h3>
                        <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">{{ $pendingReceipts->total() }} Instruments Uncleared</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>

                {{-- Card 2: Pending Action (Amber/Orange Accent) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-orange-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-orange-700">Pending Action</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $pendingReceipts->where('realization_status', 'pending')->count() }}</h3>
                        <span class="text-[10px] text-orange-700 font-bold block mt-0.5">Newly received, awaiting process</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 border border-orange-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                {{-- Card 3: In Progress (Blue/Indigo Accent) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-blue-600 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">In Progress</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $pendingReceipts->whereIn('realization_status', ['cheque_in_hand', 'deposited', 'in_clearing'])->count() }}</h3>
                        <span class="text-[10px] text-blue-700 font-bold block mt-0.5">In hand or sent to bank</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
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
                                    'ref' => $receipt->reference_no ?: 'REC-' . str_pad((string)$receipt->id, 5, '0', STR_PAD_LEFT),
                                    'customer_name' => $receipt->customer?->name ?? '—',
                                    'company_bank_account_name' => $receipt->companyBankAccount?->bank_name ?? '—',
                                    'amount' => $receipt->amount,
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
                                        $statusConfig = [
                                            'pending'        => ['bg-slate-100 text-slate-800 border-slate-300', '⏳'],
                                            'cheque_in_hand' => ['bg-amber-100 text-amber-800 border-amber-300', '🖐'],
                                            'deposited'      => ['bg-blue-100 text-blue-800 border-blue-300', '🏦'],
                                            'in_clearing'    => ['bg-indigo-100 text-indigo-800 border-indigo-300', '🔄'],
                                            'realized'       => ['bg-emerald-100 text-emerald-800 border-emerald-300', '✅'],
                                            'bounced'        => ['bg-rose-100 text-rose-800 border-rose-300', '❌'],
                                            'cancelled'      => ['bg-slate-200 text-slate-800 border-slate-400', '🚫'],
                                        ];
                                        $sc = $statusConfig[$receipt->realization_status] ?? ['bg-slate-100 text-slate-600 border-slate-300', '•'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full {{ $sc[0] }} border text-[10px] font-black uppercase tracking-wider inline-block">
                                        {{ $sc[1] }} {{ \App\Models\Receipt::STATUSES[$receipt->realization_status] ?? $receipt->realization_status }}
                                    </span>
                                </td>
                                @unless(request()->routeIs('cheque-realization.realized'))
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if(!in_array($receipt->realization_status, ['bounced', 'cancelled']))
                                                <a href="{{ route('cheque-realization.process', $receipt->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors group relative" title="Process Instrument">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                                        Process
                                                    </span>
                                                </a>
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

        {{-- Custom Realize Confirmation Modal --}}
        <div x-show="realizeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-emerald-500" @click.away="realizeModalOpen = false">
                <div class="bg-gradient-to-r from-emerald-700 to-emerald-900 text-white px-6 py-5 flex items-center justify-between border-b border-emerald-600">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 text-white border border-white/30 flex items-center justify-center text-lg font-black shrink-0">
                            ✅
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wider">CONFIRM CHEQUE REALIZATION</h3>
                            <p class="text-[11px] text-emerald-100 font-medium">Clear instrument &amp; credit bank balance</p>
                        </div>
                    </div>
                    <button type="button" @click="realizeModalOpen = false" class="w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white transition flex items-center justify-center font-bold text-sm">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="targetReceipt">
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2">
                            <div class="flex justify-between items-center text-emerald-950 font-bold border-b border-emerald-200 pb-2">
                                <span class="text-[10px] text-emerald-700 uppercase tracking-wider font-black">Instrument Reference</span>
                                <span class="font-mono font-black" x-text="targetReceipt ? targetReceipt.ref : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Customer</span>
                                <span class="font-bold text-slate-900" x-text="targetReceipt ? targetReceipt.customer_name : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Company Bank</span>
                                <span class="font-extrabold text-slate-900" x-text="targetReceipt ? targetReceipt.company_bank_account_name : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-900 pt-1 border-t border-emerald-200">
                                <span class="text-[10px] text-emerald-800 uppercase tracking-wider font-black">Realization Amount</span>
                                <span class="font-mono font-black text-emerald-900 text-base" x-text="targetReceipt ? '₹' + parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : ''"></span>
                            </div>
                        </div>
                    </template>

                    <p class="text-xs font-semibold text-slate-600">
                        Are you sure you want to mark this instrument as <strong class="text-emerald-700">REALIZED</strong>? This will credit the funds directly to the company bank account treasury balance.
                    </p>

                    <form :action="targetReceipt ? `/receipt-management/${targetReceipt.id}/realize` : '#'" method="POST" class="pt-2 flex items-center justify-end gap-3">
                        @csrf
                        <button type="button" @click="realizeModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
                            <span>✅ Confirm Realize</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Custom Bounce Confirmation Modal --}}
        <div x-show="bounceModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-rose-500" @click.away="bounceModalOpen = false">
                <div class="bg-gradient-to-r from-rose-700 to-rose-900 text-white px-6 py-5 flex items-center justify-between border-b border-rose-600">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 text-white border border-white/30 flex items-center justify-center text-lg font-black shrink-0">
                            ❌
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wider">CONFIRM CHEQUE BOUNCE</h3>
                            <p class="text-[11px] text-rose-100 font-medium">Mark instrument as dishonoured</p>
                        </div>
                    </div>
                    <button type="button" @click="bounceModalOpen = false" class="w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white transition flex items-center justify-center font-bold text-sm">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="targetReceipt">
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs space-y-2">
                            <div class="flex justify-between items-center text-rose-950 font-bold border-b border-rose-200 pb-2">
                                <span class="text-[10px] text-rose-700 uppercase tracking-wider font-black">Instrument Reference</span>
                                <span class="font-mono font-black" x-text="targetReceipt ? targetReceipt.ref : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Customer</span>
                                <span class="font-bold text-slate-900" x-text="targetReceipt ? targetReceipt.customer_name : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-900 pt-1 border-t border-rose-200">
                                <span class="text-[10px] text-rose-800 uppercase tracking-wider font-black">Amount</span>
                                <span class="font-mono font-black text-rose-900 text-base" x-text="targetReceipt ? '₹' + parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : ''"></span>
                            </div>
                        </div>
                    </template>

                    <p class="text-xs font-semibold text-slate-600">
                        Are you sure you want to mark this instrument as <strong class="text-rose-700">BOUNCED / DISHONOURED</strong>? No balance change will occur.
                    </p>

                    <form :action="targetReceipt ? `/receipt-management/${targetReceipt.id}/bounced` : '#'" method="POST" class="pt-2 flex items-center justify-end gap-3">
                        @csrf
                        <button type="button" @click="bounceModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
                            <span>❌ Mark Bounced</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-erp-layout>
