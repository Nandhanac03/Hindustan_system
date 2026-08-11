<x-erp-layout title="Customer Ledger Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Customer Ledger Statement</h3>

            {{-- Customer Selection Filter Bar --}}
            <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
                <form id="customerLedgerForm" method="GET" action="{{ route('reports.customer_ledger') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    @if(request('project_id'))
                        <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                    @endif
                    <input type="hidden" name="customer_id" :value="selectedCustomerId">
                    
                    <div class="flex-1 w-full relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Select Customer for Ledger & Account Statement
                        </label>
                        
                        <div class="relative flex-1">
                            <button type="button" 
                                    @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                                    :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                                    class="w-full h-10 px-3.5 py-2 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs">
                                <template x-if="getSelectedCustomer()">
                                    <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#8a7522] font-bold text-xs flex items-center justify-center shrink-0" x-text="getSelectedCustomer().name.charAt(0).toUpperCase()"></div>
                                        <span class="font-extrabold text-slate-900 truncate" x-text="getSelectedCustomer().name"></span>
                                        <span class="text-xs font-bold text-slate-400 font-mono shrink-0" x-show="getSelectedCustomer().phone" x-text="'(' + getSelectedCustomer().phone + ')'"></span>
                                    </div>
                                </template>
                                <template x-if="!getSelectedCustomer()">
                                    <span class="text-slate-500 font-bold">— All Customers —</span>
                                </template>
                                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                    <template x-if="getSelectedCustomer()">
                                        <span @click.stop="selectCustomer(null); search = '';" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selected customer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                                 style="display: none;">
                                
                                <div class="p-2.5 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text"
                                               x-model="search"
                                               x-ref="customerSearchInput"
                                               placeholder="Type name or phone number..."
                                               @keydown.escape="open = false"
                                               class="w-full pl-8 pr-7 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                        <template x-if="search">
                                            <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                        </template>
                                    </div>
                                </div>

                                <button type="button" @click="selectCustomer(null); open = false; search = ''"
                                        class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>— All Customers —</span>
                                </button>

                                <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                    <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                                        <button type="button"
                                                @click="selectCustomer(customer); open = false; search = ''"
                                                :class="selectedCustomerId == customer.id ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div :class="selectedCustomerId == customer.id ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                                     class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors"
                                                     x-text="(customer.name || '?').charAt(0).toUpperCase()"></div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs truncate leading-snug" :class="selectedCustomerId == customer.id ? 'text-[#8a7522]' : 'text-slate-800'" x-text="customer.name"></p>
                                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 font-mono mt-0.5" x-show="customer.phone">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                            <span x-text="customer.phone"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <template x-if="selectedCustomerId == customer.id">
                                                <svg class="w-4 h-4 text-[#a38c29] shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </template>
                                        </button>
                                    </template>
                                    <div x-show="getFilteredCustomersList(search).length === 0"
                                         class="py-6 px-4 text-center">
                                        <p class="text-xs text-slate-400 italic">No matching customers found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/20 self-stretch sm:self-auto shrink-0 flex items-center justify-center gap-2 cursor-pointer h-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Generate Statement</span>
                    </button>
                </form>
            </div>

            @if($selectedCustomer)
            <div id="ledger-results"></div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 border border-[#a38c29]/30 rounded-2xl p-5 bg-gradient-to-r from-[#a38c29]/10 via-[#a38c29]/5 to-transparent space-y-4">
                    <h4 class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider">Statement Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block font-bold">Customer Name</span>
                            <strong class="text-slate-900 text-sm block mt-0.5">{{ $selectedCustomer->name }}</strong>
                            <span class="text-slate-500 block text-[10px] mt-0.5">{{ $selectedCustomer->phone }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] text-slate-400 uppercase tracking-widest block font-bold">Net Outstanding Due</span>
                            <strong class="text-rose-600 font-mono text-lg block mt-0.5">₹{{ number_format($closingBalance, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1 border border-[#a38c29]/30 rounded-2xl p-4 bg-gradient-to-r from-[#a38c29]/10 via-[#a38c29]/5 to-transparent flex flex-col justify-center">
                    <h4 class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider mb-2">History & Ledger Mix</h4>
                    <div id="customerPaymentHistoryChart" class="w-full h-36"></div>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl" id="ledger-table">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-[#a38c29]/10 border-b border-[#a38c29]/30 text-[10px] font-black text-[#8a7522] uppercase tracking-widest">
                            <th class="px-5 py-3">Posting Date</th>
                            <th class="px-5 py-3">Voucher / Ref No.</th>
                            <th class="px-5 py-3">Narrative</th>
                            <th class="px-5 py-3">Mode</th>
                            <th class="px-5 py-3 text-right">Debit (Due)</th>
                            <th class="px-5 py-3 text-right">Credit (Receipt)</th>
                            <th class="px-5 py-3 text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($ledgerEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row['date'] }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $row['description'] }}</td>
                            <td class="px-5 py-3 font-sans text-slate-450">{{ $row['payment_mode'] }}</td>
                            <td class="px-5 py-3 text-right text-rose-600">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700">{{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}</td>
                            <td class="px-5 py-3 text-right text-slate-900 font-extrabold">₹{{ number_format($row['balance'] ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No chronological allocations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator && $ledgerEntries->hasPages())
            <div class="px-5 py-3 border-t border-slate-200 bg-slate-50">
                {{ $ledgerEntries->appends(request()->query())->links() }}
            </div>
            @endif
            @else
            {{-- DEFAULT FULL DISPLAY — ALL CUSTOMERS LEDGER SUMMARY --}}
            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 shadow-2xs space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Total Sales Agreements</span>
                        <div class="text-xl font-mono font-black text-slate-900">₹{{ number_format($totalDebits, 2) }}</div>
                        <span class="text-[10px] text-slate-400 font-semibold block">Combined sales value</span>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-emerald-50/40 shadow-2xs space-y-1">
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest block">Total Collections</span>
                        <div class="text-xl font-mono font-black text-emerald-700">₹{{ number_format($totalCredits, 2) }}</div>
                        <span class="text-[10px] text-emerald-600/80 font-semibold block">Total receipts received</span>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-rose-50/40 shadow-2xs space-y-1">
                        <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest block">Net Outstanding Due</span>
                        <div class="text-xl font-mono font-black text-rose-700">₹{{ number_format($closingBalance, 2) }}</div>
                        <span class="text-[10px] text-rose-600/80 font-semibold block">Overall pending receivables</span>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-amber-50/40 shadow-2xs space-y-1">
                        <span class="text-[9px] font-black text-[#a38c29] uppercase tracking-widest block">Active Customer Accounts</span>
                        <div class="text-xl font-mono font-black text-slate-900">{{ count($customerSummaryList) }}</div>
                        <span class="text-[10px] text-slate-400 font-semibold block">Customers with active sales</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white flex items-center justify-between border-b border-[#8a7522]">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-white">All Customers Account Balances Directory</h4>
                            <p class="text-[10px] text-amber-100 font-medium mt-0.5">Overview of customer agreements, total payments received, and current outstanding dues.</p>
                        </div>
                        <span class="px-3 py-1 bg-white/20 text-white border border-white/30 text-[10px] font-black uppercase tracking-wider rounded-lg">
                            {{ $customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customerSummaryList->total() : count($customerSummaryList) }} Customers
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="bg-[#a38c29]/10 border-b border-[#a38c29]/30 text-[10px] font-black text-[#8a7522] uppercase tracking-widest">
                                    <th class="px-5 py-3.5">SL NO</th>
                                    <th class="px-5 py-3.5">Customer Name & Contact</th>
                                    <th class="px-5 py-3.5">Project / Unit</th>
                                    <th class="px-5 py-3.5 text-right">Total Sale (₹)</th>
                                    <th class="px-5 py-3.5 text-right">Total Paid (₹)</th>
                                    <th class="px-5 py-3.5 text-right">Outstanding (₹)</th>
                                    <th class="px-5 py-3.5 text-center">Last Payment</th>
                                    <th class="px-5 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($customerSummaryList as $idx => $cs)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-4 font-mono font-bold text-slate-400">{{ ($customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($customerSummaryList->currentPage() - 1) * $customerSummaryList->perPage() : 0) + $idx + 1 }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $cs['customer_name'] }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                            {{ $cs['phone'] ?? 'No phone' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800">{{ $cs['project'] }}</div>
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 text-slate-600 inline-block mt-0.5">
                                            Unit: {{ $cs['unit'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-mono font-bold text-slate-900">
                                        ₹{{ number_format($cs['total_amount'], 2) }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-mono font-bold text-emerald-700">
                                        ₹{{ number_format($cs['paid_amount'], 2) }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-mono font-black text-rose-600">
                                        ₹{{ number_format($cs['outstanding'], 2) }}
                                    </td>
                                    <td class="px-5 py-4 text-center font-mono text-[11px] text-slate-500">
                                        {{ $cs['last_payment'] }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('reports.customer_ledger', ['customer_id' => $cs['customer_id'], 'project_id' => request('project_id')]) }}"
                                           class="px-3.5 py-1.5 bg-[#a38c29]/15 hover:bg-[#a38c29] text-[#8a7522] hover:text-white border border-[#a38c29]/30 rounded-xl text-[10px] font-black uppercase tracking-wider transition inline-flex items-center gap-1">
                                            <span>View Ledger</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">No active customer accounts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator && $customerSummaryList->hasPages())
                    <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50">
                        {{ $customerSummaryList->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white flex items-center justify-between border-b border-[#8a7522]">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-white">System-Wide Customer Ledger Transaction Log</h4>
                            <p class="text-[10px] text-amber-100 font-medium mt-0.5">Chronological transaction history combining sale agreements and receipts across all customers.</p>
                        </div>
                        <span class="px-3 py-1 bg-white/20 text-white border border-white/30 text-[10px] font-black uppercase tracking-wider rounded-lg">
                            {{ $ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ledgerEntries->total() : count($ledgerEntries) }} Transactions
                        </span>
                    </div>

                    <div class="overflow-x-auto max-h-[500px]">
                        <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                            <thead class="sticky top-0 z-10 bg-[#a38c29]/15 border-b border-[#a38c29]/30 text-[10px] font-black text-[#8a7522] uppercase tracking-widest">
                                <tr>
                                    <th class="px-5 py-3">Posting Date</th>
                                    <th class="px-5 py-3">Customer Name</th>
                                    <th class="px-5 py-3">Voucher / Ref No.</th>
                                    <th class="px-5 py-3">Narrative Description</th>
                                    <th class="px-5 py-3">Mode</th>
                                    <th class="px-5 py-3 text-right">Debit (Agreement)</th>
                                    <th class="px-5 py-3 text-right">Credit (Receipt)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                                @forelse($ledgerEntries as $row)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 text-slate-500 font-sans text-[11px]">{{ $row['date'] }}</td>
                                    <td class="px-5 py-3.5 font-bold font-sans text-slate-900">{{ $row['customer_name'] ?? '-' }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-800">{{ $row['description'] }}</td>
                                    <td class="px-5 py-3.5 font-sans">
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 text-slate-600 inline-block">{{ $row['payment_mode'] }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-rose-600 font-bold">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                                    <td class="px-5 py-3.5 text-right text-emerald-700 font-bold">{{ $row['credit'] > 0 ? '₹'.number_format($row['credit'], 2) : '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic font-sans">No customer ledger transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator && $ledgerEntries->hasPages())
                    <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50">
                        {{ $ledgerEntries->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('reports.partials.script')

<script>
    // Auto-scroll to ledger results after form submit (page reload)
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        if (params.has('customer_id') && params.get('customer_id')) {
            const target = document.getElementById('ledger-results');
            if (target) {
                setTimeout(function () {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 250);
            }
        }
    });
</script>

</x-erp-layout>
