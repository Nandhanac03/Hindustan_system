<x-erp-layout title="EMI Collections" headerTitle="EMI Collections Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="emiApp()">

    {{-- Top Stats Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total EMI Collections Received</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-2 block">
                    ₹{{ number_format($totalReceived, 2) }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Pending Receipts</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-2 block">
                    {{ $pendingPaymentsCount }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Ledger Status</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg px-2.5 py-1 mt-2 inline-block">
                    Healthy & Balanced
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-primary-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Main Two-Column view --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left side: Customer EMI Accounts Directory (2/3 width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Customer EMI Directory</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Directory of all active customers with outstanding schedules and payment logs.</p>
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Booking No.</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Customer</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Project & Unit</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-right">Contract Value</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-right">Total Paid</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-right">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sales as $sale)
                            @php
                                $totalPaid = $sale->receipts->sum('amount');
                            @endphp
                            <tr @click="selectedSaleId = (selectedSaleId == {{ $sale->id }} ? '' : {{ $sale->id }}); onSaleSelect()" 
                                class="cursor-pointer transition-colors" 
                                :class="selectedSaleId == {{ $sale->id }} ? 'bg-primary-50 hover:bg-primary-100/50' : 'hover:bg-slate-50'">
                                <td class="px-6 py-4 font-bold text-primary-700">
                                    <a href="{{ route('emi-collections.ledger', $sale->id) }}" class="hover:underline" @click.stop>
                                        {{ $sale->sale_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $sale->customer?->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $sale->customer?->phone ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $sale->project?->name ?? 'N/A' }}</div>
                                    <span class="text-[9px] bg-slate-100 border px-1.5 py-0.5 rounded text-slate-500 font-mono">Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 font-mono text-right">₹{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-6 py-4 font-bold text-emerald-600 font-mono text-right">₹{{ number_format($totalPaid, 2) }}</td>
                                <td class="px-6 py-4 font-bold text-rose-600 font-mono text-right">₹{{ number_format($sale->remaining_balance, 2) }}</td>
                            </tr>
                            <tr x-show="selectedSaleId == {{ $sale->id }}" style="display: none;" x-transition>
                                <td colspan="6" class="p-0 border-b border-slate-100 bg-slate-50/50">
                                    <div class="px-6 py-4 pl-12 border-l-4 border-primary">
                                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3">Payment Logs</h4>
                                        @if($sale->receipts->count() > 0)
                                            <table class="w-full text-left text-[11px] bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                                                <thead class="bg-slate-50 border-b border-slate-200">
                                                    <tr>
                                                        <th class="px-4 py-2 font-bold text-slate-500 uppercase tracking-wider">Receipt Date</th>
                                                        <th class="px-4 py-2 font-bold text-slate-500 uppercase tracking-wider">Payment Mode</th>
                                                        <th class="px-4 py-2 font-bold text-slate-500 uppercase tracking-wider">Ref / Chq</th>
                                                        <th class="px-4 py-2 font-bold text-slate-500 uppercase tracking-wider">Bank Name</th>
                                                        <th class="px-4 py-2 font-bold text-slate-500 uppercase tracking-wider text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach($sale->receipts as $receipt)
                                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                                            <td class="px-4 py-2 text-slate-700 font-mono font-medium">{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') }}</td>
                                                            <td class="px-4 py-2 text-slate-700">{{ $receipt->payment_mode }}</td>
                                                            <td class="px-4 py-2 text-slate-500 font-mono">{{ $receipt->reference_no ?: '—' }}</td>
                                                            <td class="px-4 py-2 text-slate-500">{{ $receipt->bank_name ?: '—' }}</td>
                                                            <td class="px-4 py-2 text-emerald-600 font-extrabold font-mono text-right">₹{{ number_format($receipt->amount, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-xs text-slate-400 italic bg-white p-3 border border-slate-200 rounded-lg">No payments recorded yet.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>

        {{-- Right side: Active Bookings/Sales for quick receipt mapping (1/3 width) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Map New Receipt</h2>
                <p class="text-xs text-slate-400 mt-0.5">Select a customer or sale below to register an incoming payment installment.</p>
            </div>

            {{-- Customer Select Box --}}
            <div class="space-y-1.5" x-data="{ searchOpen: false, searchString: '' }" @click.outside="searchOpen = false">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Select Customer</label>
                <div class="relative">
                    <div @click="searchOpen = !searchOpen" 
                         class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 focus-within:bg-white focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary rounded-xl text-xs font-semibold cursor-pointer flex justify-between items-center transition-all">
                        <span class="text-ellipsis overflow-hidden whitespace-nowrap" 
                              x-text="selectedSale ? ((selectedSale.customer ? selectedSale.customer.name : 'Unknown Customer') + ' — ' + selectedSale.sale_number) : '-- Choose Customer... --'"></span>
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    
                    <div x-show="searchOpen" x-transition class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-64 flex flex-col overflow-hidden" style="display: none;">
                        <div class="p-2 border-b border-slate-100 bg-slate-50/50">
                            <input type="text" x-model="searchString" placeholder="Search name or sale no..." 
                                   class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-primary/50 focus:border-primary/50">
                        </div>
                        <ul class="overflow-y-auto flex-1 p-1">
                            <li @click="selectedSaleId = ''; onSaleSelect(); searchOpen = false; searchString = ''" 
                                class="px-3 py-2 text-xs cursor-pointer hover:bg-slate-50 rounded-lg text-slate-500 font-medium">-- Choose Customer... --</li>
                            <template x-for="s in activeSales.filter(sale => {
                                let searchText = ((sale.customer ? sale.customer.name : 'Unknown Customer') + ' ' + sale.sale_number).toLowerCase();
                                return searchText.includes(searchString.toLowerCase());
                            })" :key="s.id">
                                <li @click="selectedSaleId = s.id; onSaleSelect(); searchOpen = false; searchString = ''"
                                    class="px-3 py-2 text-xs cursor-pointer hover:bg-primary-50 hover:text-primary-700 rounded-lg font-medium"
                                    :class="selectedSaleId == s.id ? 'bg-primary-50 text-primary-700' : 'text-slate-700'">
                                    <span x-text="(s.customer ? s.customer.name : 'Unknown Customer') + ' — ' + s.sale_number"></span>
                                </li>
                            </template>
                            <template x-if="activeSales.filter(sale => {
                                let searchText = ((sale.customer ? sale.customer.name : 'Unknown Customer') + ' ' + sale.sale_number).toLowerCase();
                                return searchText.includes(searchString.toLowerCase());
                            }).length === 0">
                                <li class="px-3 py-4 text-xs text-center text-slate-400 italic">No matches found.</li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                {{-- If a sale is selected --}}
                <template x-if="selectedSale">
                    <div class="p-3.5 bg-blue-50/40 border border-blue-100 rounded-xl space-y-2 hover:shadow-md transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-blue-100 text-blue-750 rounded border border-blue-200 font-mono" x-text="selectedSale.sale_number"></span>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-purple-50 text-purple-700 border-purple-100 rounded border ml-1"
                                      x-text="selectedSale.payment_plan === 'emi' ? (selectedSale.emi_installment_count + ' ' + (selectedSale.emi_frequency ? selectedSale.emi_frequency.charAt(0).toUpperCase() + selectedSale.emi_frequency.slice(1) : 'Monthly')) : 'Lump Sum'">
                                </span>
                                <h3 class="text-xs font-bold text-slate-900 mt-2" x-text="selectedSale.customer ? selectedSale.customer.name : 'Unknown Customer'"></h3>
                                <p class="text-[10px] text-slate-400 mt-0.5" x-text="(selectedSale.project ? selectedSale.project.name : '') + ' · Unit: ' + (selectedSale.unit ? selectedSale.unit.door_no : 'No Unit')"></p>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-900" x-text="'₹' + (Number(selectedSale.total_amount) / 100000).toFixed(1) + 'L'"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <button @click="openCollectModal({ id: selectedSale.id, outstanding: selectedSale.remaining_balance, customer_name: selectedSale.customer ? selectedSale.customer.name : 'Unknown', door_no: selectedSale.unit ? selectedSale.unit.door_no : 'No Unit' })" 
                                    class="py-1.5 bg-primary hover:bg-primary-700 active:scale-95 text-white text-[10px] font-bold rounded-lg transition-all uppercase tracking-wide">
                                Collect
                            </button>
                            <a :href="'/emi-collections/ledger/' + selectedSale.id"
                               class="py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 hover:text-primary text-[10px] font-bold rounded-lg transition uppercase tracking-wide text-center flex items-center justify-center">
                                Ledger &rarr;
                            </a>
                        </div>
                    </div>
                </template>

                {{-- If no sale is selected, show recent list --}}
                <template x-if="!selectedSale">
                    <div class="space-y-4">
                        @forelse($recentBookings as $booking)
                            <div class="p-3.5 bg-slate-50 border border-slate-150 rounded-xl space-y-2 hover:border-primary-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 bg-primary-50 text-primary-700 rounded border border-primary-100 font-mono">{{ $booking->sale_number }}</span>
                                        @if($booking->payment_plan === 'emi')
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 bg-purple-50 text-purple-700 border-purple-100 rounded border ml-1">
                                                {{ $booking->emi_installment_count ?? 12 }} {{ ucfirst($booking->emi_frequency ?? 'Monthly') }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 bg-slate-50 text-slate-600 border-slate-100 rounded border ml-1">
                                                Lump Sum
                                            </span>
                                        @endif
                                        <h3 class="text-xs font-bold text-slate-900 mt-2">{{ $booking->customer?->name ?? 'Unknown Customer' }}</h3>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $booking->project?->name ?? 'Unknown Project' }} · Unit: {{ $booking->unit?->door_no ?? 'No Unit' }}</p>
                                    </div>
                                    <span class="text-[11px] font-extrabold text-slate-900">₹{{ number_format($booking->total_amount / 100000, 1) }}L</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <button @click="openCollectModal({ id: {{ $booking->id }}, outstanding: {{ $booking->remaining_balance }}, customer_name: '{{ addslashes($booking->customer?->name ?? 'Unknown') }}', door_no: '{{ addslashes($booking->unit?->door_no ?? 'No Unit') }}' })" 
                                            class="py-1.5 bg-primary hover:bg-primary-700 active:scale-95 text-white text-[10px] font-bold rounded-lg transition-all uppercase tracking-wide">
                                        Collect
                                    </button>
                                    <a href="{{ route('emi-collections.ledger', $booking->id) }}"
                                       class="py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 hover:text-primary text-[10px] font-bold rounded-lg transition uppercase tracking-wide text-center flex items-center justify-center">
                                        Ledger &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-455 italic text-center py-4">No recent active sales with outstanding balances found.</p>
                        @endforelse
                    </div>
                </template>
            </div>
        </div>

    </div>


    {{-- Toast Notification --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- COLLECTION RECEIPT Modal --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition>
        <div @click.away="closeCollectModal()" class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Collection Receipt</h3>
                    <p class="text-[10px] text-slate-450 mt-0.5 font-medium">Collections must be linked to an active Sale. Select the Sale first.</p>
                </div>
                <button @click="closeCollectModal()" class="text-slate-450 hover:text-slate-700">✕</button>
            </div>
            
            <form @submit.prevent="submitCollection()" class="p-6 space-y-4">
                {{-- Active Sale --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Active Sale *</label>
                    <select x-model="form.booking_id" @change="onModalSaleSelect()" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">-- Select Sale --</option>
                        <template x-for="s in activeSales" :key="s.id">
                            <option :value="s.id" x-text="(s.customer ? s.customer.name : '—') + ' — ' + s.sale_number + ' (' + (s.project ? s.project.name : '—') + ')'"></option>
                        </template>
                    </select>
                </div>

                {{-- Info Box --}}
                <div x-show="form.booking_id && form.project_name" class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-[11px] font-semibold text-slate-650" x-transition>
                    <div class="flex justify-between">
                        <span>Project / Unit:</span>
                        <strong class="text-slate-900" x-text="form.project_name + ' / Unit ' + form.unit_number"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Sale Total:</span>
                        <strong class="text-slate-700 font-mono" x-text="'₹' + Number(form.total_amount).toLocaleString('en-IN')"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Remaining Balance:</span>
                        <strong class="text-rose-600 font-mono" x-text="'₹' + Number(form.outstanding).toLocaleString('en-IN')"></strong>
                    </div>
                </div>

                {{-- Collection Type Field --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Action Type *</label>
                    <div class="grid grid-cols-2 gap-1.5">
                        <button type="button" @click="form.collection_type = 'regular'" :class="form.collection_type === 'regular' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-primary/40'" class="px-2 py-2 border rounded-xl text-[9px] font-bold uppercase tracking-wider transition-all">Regular</button>
                        <button type="button" @click="form.collection_type = 'prepayment'" :class="form.collection_type === 'prepayment' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-primary/40'" class="px-2 py-2 border rounded-xl text-[9px] font-bold uppercase tracking-wider transition-all">Prepayment</button>
                    </div>
                </div>

                {{-- Prepayment Options --}}
                <div class="space-y-1.5" x-show="form.collection_type === 'prepayment'" x-cloak>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Prepayment Option *</label>
                    <select x-model="form.prepayment_option" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="reduce_emi">Reduce EMI amount (keep tenure the same)</option>
                        <option value="reduce_tenure">Reduce Tenure (keep monthly EMI the same)</option>
                    </select>
                </div>


                {{-- Amount & Date --}}
                <div class="grid grid-cols-2 gap-3" x-show="form.collection_type !== 'reschedule'">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount (₹) *</label>
                        <input type="number" step="0.01" x-model.number="form.amount" :required="form.collection_type !== 'reschedule'" min="0.01"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs font-bold focus:outline-none transition-all">
                        <template x-if="errors.amount">
                            <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="errors.amount[0]"></span>
                        </template>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date</label>
                        <input type="date" x-model="form.receipt_date"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="errors.receipt_date">
                            <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="errors.receipt_date[0]"></span>
                        </template>
                    </div>
                </div>

                {{-- Payment Mode Toggles --}}
                <div class="space-y-1.5" x-show="form.collection_type !== 'reschedule'">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode *</label>
                    <div class="grid grid-cols-2 gap-1.5">
                        <template x-for="mode in ['Cash', 'Cheque', 'Bank Transfer', 'Online']" :key="mode">
                            <button type="button" @click="form.payment_mode = mode"
                                    :class="form.payment_mode === mode ? 'bg-primary text-white border-primary shadow-sm shadow-primary-650/20' : 'bg-slate-50 text-slate-655 border-slate-200 hover:border-primary/40'"
                                    class="px-3 py-2 border rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all"
                                    x-text="mode">
                            </button>
                        </template>
                    </div>
                    <template x-if="errors.payment_mode">
                        <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="errors.payment_mode[0]"></span>
                    </template>
                </div>

                {{-- Reference & Bank --}}
                <div class="grid grid-cols-2 gap-3" x-show="form.collection_type !== 'reschedule'">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Ref / Cheque No.</label>
                        <input type="text" x-model="form.reference_no" placeholder="Optional"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Bank Name</label>
                        <select x-model="form.bank_name"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="">-- Optional --</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="space-y-1.5" x-show="form.collection_type !== 'reschedule'">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Remarks</label>
                    <textarea x-model="form.remarks" rows="2"
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full py-3 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-all uppercase tracking-wider flex items-center justify-center gap-2 shadow-md shadow-primary-600/10 active:scale-98">
                    COLLECTION RECEIPT
                </button>

                <p class="text-[10px] text-slate-400 text-center">
                    To register a new sale, go to
                    <a href="{{ route('sales.index') }}" class="text-primary font-bold hover:underline">Sales Module &rarr;</a>
                </p>
            </form>
        </div>
    </div>

</div>

<script>
function emiApp() {
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
            bank_name: '',
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

        activeSales: @json($activeSales),
        selectedSaleId: '',
        selectedSale: null,

        onSaleSelect() {
            this.selectedSale = this.activeSales.find(s => s.id == this.selectedSaleId) || null;
        },

        onModalSaleSelect() {
            const sale = this.activeSales.find(s => s.id == this.form.booking_id);
            if (sale) {
                this.form.customer_name = sale.customer ? sale.customer.name : 'Unknown';
                this.form.unit_number = sale.unit ? sale.unit.door_no : 'No Unit';
                this.form.outstanding = sale.remaining_balance;
                this.form.project_name = sale.project ? sale.project.name : '';
                this.form.total_amount = sale.total_amount;
                this.form.amount = ''; // Leave blank
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
            this.form.booking_id = item.id;
            this.form.amount = ''; // Leave blank
            this.form.payment_mode = 'Cash';
            this.form.receipt_date = new Date().toISOString().split('T')[0];
            this.form.reference_no = '';
            this.form.bank_name = '';
            this.form.remarks = '';
            this.form.collection_type = 'regular';
            this.form.prepayment_option = 'reduce_emi';
            this.form.reschedule_option = 'extend_tenure';
            this.form.reschedule_reason = '';
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
            this.modal.open = true;
        },

        closeCollectModal() {
            this.modal.open = false;
        },

        submitCollection() {
            this.errors = {};
            
            if (this.form.collection_type !== 'reschedule' && (!this.form.amount || !this.form.payment_mode)) {
                this.showToast('Please enter amount and choose payment mode.', 'error');
                return;
            }
            if (this.form.collection_type === 'reschedule' && !this.form.reschedule_reason) {
                this.showToast('Please enter a reason for rescheduling.', 'error');
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
                    bank_name: this.form.bank_name,
                    remarks: this.form.remarks,
                    collection_type: this.form.collection_type,
                    prepayment_option: this.form.prepayment_option,
                    reschedule_option: this.form.reschedule_option,
                    reschedule_reason: this.form.reschedule_reason,
                    new_count: this.form.new_count,
                    shift_months: this.form.shift_months
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.showToast(data.message, 'success');
                    this.closeCollectModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else if (data.errors) {
                    this.errors = data.errors;
                } else if (data.error) {
                    this.showToast(data.error, 'error');
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
            }, 3000);
        }
    };
}
</script>

</x-erp-layout>
