<x-erp-layout title="Customer Ledger Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" 
     x-data="{
         ...reportsApp(),
         isLoading: false,
         
         fetchCustomerLedger() {
             this.isLoading = true;
             const form = document.getElementById('customerLedgerForm');
             if (!form) {
                 this.isLoading = false;
                 return;
             }
             const formData = new FormData(form);
             const params = new URLSearchParams();
             for (const [k, v] of formData.entries()) {
                 if (v) params.append(k, v);
             }
             const url = '{{ route('reports.customer_ledger') }}?' + params.toString();
             window.history.pushState({}, '', url);

             fetch(url, {
                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
             })
             .then(r => r.text())
             .then(html => {
                 const parser = new DOMParser();
                 const doc = parser.parseFromString(html, 'text/html');
                 const newContent = doc.getElementById('ledger-results-wrapper');
                 const currentContent = document.getElementById('ledger-results-wrapper');
                 if (newContent && currentContent) {
                     currentContent.innerHTML = newContent.innerHTML;
                 }
                 this.$nextTick(() => {
                     if (window.renderCustomerLedgerChart) {
                         window.renderCustomerLedgerChart();
                     }
                 });
             })
             .catch(err => console.error('AJAX Ledger load error:', err))
             .finally(() => {
                 this.isLoading = false;
             });
         }
     }"
     @filter-changed="fetchCustomerLedger()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        @php
            $activeCustomerName = null;
            if(isset($selectedCustomers) && $selectedCustomers->isNotEmpty()) {
                if($selectedCustomers->count() === 1) {
                    $activeCustomerName = $selectedCustomers->first()->name;
                } else {
                    $activeCustomerName = $selectedCustomers->count() . ' Customers Selected';
                }
            } elseif(request('customer_id')) {
                $reqIds = is_array(request('customer_id')) ? request('customer_id') : [request('customer_id')];
                $activeCustomers = \App\Models\Customer::whereIn('id', $reqIds)->get();
                if($activeCustomers->count() === 1) {
                    $activeCustomerName = $activeCustomers->first()->name;
                } elseif($activeCustomers->count() > 1) {
                    $activeCustomerName = $activeCustomers->count() . ' Customers Selected';
                }
            }
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Customer Ledger & Accounts Statement</h3>
            
            <div class="flex flex-wrap items-center gap-2.5">
                @if($activeCustomerName)
                <span id="customerBadgeName" class="px-4 py-1.5 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border border-[#8a7522] rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    Customer: {{ $activeCustomerName }}
                </span>
                @endif

                <button type="button" @click="exportCurrentTable()" 
                        class="h-[42px] px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export Excel</span>
                </button>
            </div>
        </div>


        {{-- Results Container (Refreshed dynamically via AJAX) --}}
        <div id="ledger-results-wrapper" class="space-y-6 relative min-h-[200px]">
            <div x-show="isLoading" class="absolute inset-0 bg-white/70 backdrop-blur-xs z-30 flex items-center justify-center rounded-2xl transition-opacity duration-200" style="display: none;">
                <div class="flex items-center gap-3 px-5 py-3 bg-white border border-slate-200 shadow-xl rounded-2xl">
                    <svg class="w-5 h-5 animate-spin text-[#a38c29]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-xs font-bold text-slate-800">Updating Customer Ledger Statement...</span>
                </div>
            </div>

            @if($selectedCustomers && $selectedCustomers->isNotEmpty())
            <div id="ledger-results"></div>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    {{-- Card 1: Customer Statement & Financial Overview --}}
                    <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-[#a38c29]/30 shadow-2xs flex flex-col justify-between space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 border border-[#a38c29]/30 flex items-center justify-center text-[#8a7522] font-bold shrink-0">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-black text-[#8a7522] uppercase tracking-widest block">Statement Target</span>
                                    <h4 class="text-sm font-extrabold text-slate-900 truncate max-w-md">
                                        {{ $selectedCustomers->pluck('name')->implode(', ') }}
                                    </h4>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200/80 text-[10px] font-extrabold text-[#8a7522] uppercase tracking-wider shrink-0 ml-2">
                                {{ $selectedCustomers->count() > 1 ? $selectedCustomers->count() . ' Customers Selected' : ($selectedCustomers->first()->phone ?? 'Single Account') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Total Billed / Sale</span>
                                <span class="text-sm font-mono font-black text-slate-900 block">₹{{ number_format($totalDebits, 2) }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Total Paid Receipts (Realized)</span>
                                <span class="text-sm font-mono font-black text-emerald-700 block">₹{{ number_format($totalCredits, 2) }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200/80 space-y-1">
                                <span class="text-[9px] font-bold text-rose-700 uppercase tracking-wider block">Net Outstanding (Realized)</span>
                                <span class="text-sm font-mono font-black text-rose-700 block">₹{{ number_format($closingBalance, 2) }}</span>
                            </div>
                        </div>

                        @if(isset($totalPendingCredits) && $totalPendingCredits > 0)
                        <div class="p-3 rounded-xl bg-amber-50/80 border border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                                <span class="font-extrabold text-amber-900 uppercase tracking-wider text-[10px]">Pending Realization / Under Clearance:</span>
                                <span class="font-mono font-black text-amber-800 text-xs">₹{{ number_format($totalPendingCredits, 2) }}</span>
                            </div>
                            <span class="text-[10px] text-amber-700 font-semibold">
                                Balance after realization: <strong class="font-mono text-slate-900">₹{{ number_format($projectedBalance ?? ($closingBalance - $totalPendingCredits), 2) }}</strong>
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Card 2: Financial Ledger Mix & Recovery Donut Visualizer --}}
                    <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-800">Financial Ledger Mix</h4>
                                <p class="text-[10px] text-slate-400 font-medium">Collections vs Pending Dues Balance</p>
                            </div>
                            @php
                                $pct = $totalDebits > 0 ? min(100, round(($totalCredits / $totalDebits) * 100, 1)) : 0;
                            @endphp
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black">
                                {{ $pct }}% Realized
                            </span>
                        </div>

                        <div class="grid grid-cols-12 items-center gap-2">
                            <div class="col-span-6 flex justify-center">
                                <div id="customerLedgerDonutChart" class="w-full h-32" 
                                     data-credits="{{ $totalCredits }}" 
                                     data-dues="{{ $closingBalance }}"></div>
                            </div>
                            <div class="col-span-6 space-y-2 text-[11px] font-medium pl-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Realized</span>
                                        <span class="font-mono font-bold text-slate-800 truncate block">₹{{ number_format($totalCredits, 0) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                                    <div class="min-w-0">
                                        <span class="text-slate-400 block text-[9px] uppercase font-bold">Outstanding</span>
                                        <span class="font-mono font-bold text-slate-800 truncate block">₹{{ number_format($closingBalance, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1 pt-1">
                            <div class="w-full h-2 rounded-full bg-rose-100 overflow-hidden flex">
                                <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $pct }}%"></div>
                                <div class="h-full bg-rose-500 transition-all duration-500" style="width: {{ 100 - $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl" id="ledger-table">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 text-white font-extrabold">Posting Date</th>
                                @if($selectedCustomers->count() > 1)
                                    <th class="px-5 py-3.5 text-white font-extrabold">Customer Name</th>
                                @endif
                                <th class="px-5 py-3.5 text-white font-extrabold">Voucher / Ref No.</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Narrative</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Mode</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Debit (Due)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Credit (Receipt)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                            @forelse($ledgerEntries as $row)
                            <tr class="hover:bg-slate-50/60 font-semibold {{ (!empty($row['is_pending'])) ? 'bg-amber-50/20' : '' }}">
                                <td class="px-5 py-3.5 text-slate-500 font-sans whitespace-nowrap">{{ $row['date'] }}</td>
                                @if($selectedCustomers->count() > 1)
                                    <td class="px-5 py-3.5 font-bold font-sans text-slate-900">{{ $row['customer_name'] ?? '-' }}</td>
                                @endif
                                <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row['ref_no'] }}</td>
                                <td class="px-5 py-3.5 font-sans text-slate-800">{{ $row['description'] }}</td>
                                <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row['payment_mode'] }}</td>
                                <td class="px-5 py-3.5 text-right text-rose-600 font-mono">{{ $row['debit'] > 0 ? '₹'.number_format($row['debit'], 2) : '—' }}</td>
                                <td class="px-5 py-3.5 text-right font-mono">
                                    @if($row['credit'] > 0)
                                        <span class="{{ (!empty($row['is_pending'])) ? 'text-amber-700 font-bold' : ((!empty($row['is_bounced'])) ? 'text-rose-400 line-through' : 'text-emerald-700 font-bold') }}">
                                            ₹{{ number_format($row['credit'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono">
                                    <div class="text-slate-900 font-black text-xs">
                                        ₹{{ number_format($row['balance'] ?? 0, 2) }}
                                    </div>
                                    @if(!empty($row['is_pending']))
                                        <div class="mt-1">
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-300 text-[10px] font-sans font-bold whitespace-nowrap shadow-2xs">
                                                Pending realization
                                            </span>
                                        </div>
                                    @elseif(!empty($row['is_bounced']))
                                        <div class="mt-1">
                                            <span class="inline-block px-2 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200 text-[10px] font-sans font-bold whitespace-nowrap shadow-2xs">
                                                Bounced
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $selectedCustomers->count() > 1 ? 8 : 7 }}" class="px-5 py-12 text-center text-slate-400 italic">No chronological allocations found.</td>
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
                    {{-- Top 4 KPI Metric Cards (Top-Aligned Small Icons with Background Reflections) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Card 1: Total Sales Agreements --}}
                        <div class="bg-gradient-to-br from-white via-white to-blue-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-blue-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Sales Agreements</span>
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono">₹{{ number_format($totalDebits, 2) }}</div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Combined Sales Value</span>
                            </div>
                        </div>

                        {{-- Card 2: Total Collections --}}
                        <div class="bg-gradient-to-br from-white via-white to-emerald-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-emerald-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Collections</span>
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-emerald-700 truncate font-mono">₹{{ number_format($totalCredits, 2) }}</div>
                                <span class="text-[10px] text-emerald-600/80 font-semibold block">Total Receipts Received</span>
                            </div>
                        </div>

                        {{-- Card 3: Net Outstanding Due --}}
                        <div class="bg-gradient-to-br from-white via-white to-rose-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-rose-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Net Outstanding Due</span>
                                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs border border-rose-100 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-rose-700 truncate font-mono">₹{{ number_format($closingBalance, 2) }}</div>
                                <span class="text-[10px] text-rose-600/80 font-semibold block">Overall Pending Receivables</span>
                            </div>
                        </div>

                        {{-- Card 4: Active Customer Accounts --}}
                        <div class="bg-gradient-to-br from-white via-white to-amber-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Active Customer Accounts</span>
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs border border-amber-200/60 group-hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <div>
                                <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono">{{ count($customerSummaryList) }}</div>
                                <span class="text-[10px] text-slate-400 font-semibold block">Customers with Active Sales</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-white flex items-center justify-between border-b border-slate-200/80">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">All Customers Account Balances Directory</h4>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Overview of customer agreements, total payments received, and current outstanding dues.</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-50 text-[#8a7522] border border-amber-200/80 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                {{ $customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customerSummaryList->total() : count($customerSummaryList) }} Customers
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-5 py-3.5 text-white font-extrabold">SL NO</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Customer Name & Contact</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Project / Unit</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Sale (₹)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Paid (₹)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Outstanding (₹)</th>
                                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Last Payment</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Action</th>
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
                                        <td class="px-5 py-4 text-right font-mono">
                                            <span class="font-bold text-emerald-700 block">₹{{ number_format($cs['paid_amount'], 2) }}</span>
                                            @if(!empty($cs['pending_amount']) && $cs['pending_amount'] > 0)
                                                <span class="text-[9px] font-bold text-amber-600 block">⏳ ₹{{ number_format($cs['pending_amount'], 2) }} uncleared</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-black text-rose-600">
                                            ₹{{ number_format($cs['outstanding'], 2) }}
                                        </td>
                                        <td class="px-5 py-4 text-center font-mono text-[11px] text-slate-500">
                                            {{ $cs['last_payment'] }}
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <a href="{{ route('reports.customer_ledger', ['customer_id' => $cs['customer_id'], 'project_id' => request('project_id')]) }}"
                                               class="px-4 py-2 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] hover:from-[#8a7522] hover:to-[#8a7522] text-white border border-[#8a7522] rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-2xs hover:shadow-md inline-flex items-center gap-1.5 whitespace-nowrap">
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
                        <div class="px-6 py-4 bg-white flex items-center justify-between border-b border-slate-200/80">
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-900">System-Wide Customer Ledger Transaction Log</h4>
                                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Chronological transaction history combining sale agreements and receipts across all customers.</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-50 text-[#8a7522] border border-amber-200/80 text-[10px] font-black uppercase tracking-wider rounded-lg">
                                {{ $ledgerEntries instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ledgerEntries->total() : count($ledgerEntries) }} Transactions
                            </span>
                        </div>

                        <div class="overflow-x-auto max-h-[500px]">
                            <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                                <thead class="sticky top-0 z-10">
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-5 py-3.5 text-white font-extrabold">Posting Date</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Customer Name</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Voucher / Ref No.</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Narrative Description</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Mode</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Debit (Agreement)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Credit (Receipt)</th>
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

                {{-- Hidden Formatted Excel Export Table --}}
                <div class="hidden">
                    <table id="customerLedgerExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
                        @if($selectedCustomers && $selectedCustomers->isNotEmpty())
                            <colgroup>
                                <col width="70" style="width: 55pt;" />   {{-- SL NO --}}
                                <col width="220" style="width: 165pt;" /> {{-- INSTALLMENT / MILESTONE --}}
                                <col width="140" style="width: 105pt;" /> {{-- DUE DATE --}}
                                <col width="240" style="width: 180pt;" /> {{-- PROJECT NAME --}}
                                <col width="120" style="width: 90pt;" />  {{-- UNIT NO --}}
                                <col width="180" style="width: 135pt;" /> {{-- INSTALLMENT AMOUNT (₹) --}}
                                <col width="180" style="width: 135pt;" /> {{-- PAID AMOUNT (₹) --}}
                                <col width="180" style="width: 135pt;" /> {{-- OUTSTANDING (₹) --}}
                                <col width="140" style="width: 105pt;" /> {{-- STATUS --}}
                            </colgroup>
                            <thead>
                                {{-- Row 1: Spacer --}}
                                <tr height="20" style="height: 20pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="38" style="height: 38pt;">
                                    <th colspan="9" bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #8A7522; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        HINDUSTAN REAL ESTATE & INFRASTRUCTURE - CUSTOMER EMI & ACCOUNT STATEMENT
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        Target Customer(s): {{ $selectedCustomers->pluck('name')->implode(', ') }} | Generated On: {{ date('d M Y, h:i A') }}
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #6B5B1E; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        ACCOUNT SUMMARY & EMI INSTALLMENTS
                                    </th>
                                </tr>
                                <tr height="15" style="height: 15pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="30" style="height: 30pt;">
                                    <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #8A7522;">TOTAL SALES AGREEMENTS:</td>
                                    <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #0F172A;" data-format="₹#,##0.00">₹{{ number_format($totalDebits, 2) }}</td>
                                    <td colspan="2" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #047857;">TOTAL COLLECTIONS:</td>
                                    <td colspan="3" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #047857;" data-format="₹#,##0.00">₹{{ number_format($totalCredits, 2) }}</td>
                                </tr>
                                <tr height="30" style="height: 30pt;">
                                    <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #BE123C;">NET OUTSTANDING DUES:</td>
                                    <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #BE123C;" data-format="₹#,##0.00">₹{{ number_format($closingBalance, 2) }}</td>
                                    <td colspan="2" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #B45309;">PENDING REALIZATION:</td>
                                    <td colspan="3" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #B45309;" data-format="₹#,##0.00">₹{{ number_format($totalPendingCredits, 2) }}</td>
                                </tr>
                                <tr height="15" style="height: 15pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="32" style="height: 32pt;">
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">SL NO</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">INSTALLMENT / MILESTONE</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">DUE DATE</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">PROJECT NAME</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">UNIT NO</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">INSTALLMENT (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">PAID AMOUNT (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">OUTSTANDING (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($customerInstallmentsList) && $customerInstallmentsList->isNotEmpty())
                                    @foreach($customerInstallmentsList as $idx => $inst)
                                    @php $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F8FAF5'; @endphp
                                    <tr height="25" style="height: 25pt;">
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $idx + 1 }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $inst['label'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $inst['due_date'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $inst['project'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $inst['unit'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $inst['amount'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #047857;">{{ $inst['paid_amount'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $inst['outstanding'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: {{ strtolower($inst['status']) === 'paid' ? '#047857' : (strtolower($inst['status']) === 'overdue' ? '#be123c' : '#b45309') }};">{{ $inst['status'] }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach($customerSummaryList as $idx => $cs)
                                    @php $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F8FAF5'; @endphp
                                    <tr height="25" style="height: 25pt;">
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $idx + 1 }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $cs['customer_name'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'\@'; color: #000000;" data-type="text" data-format="@">{{ $cs['phone'] ?? '-' }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['project'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['unit'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $cs['total_amount'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #047857;">{{ $cs['paid_amount'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $cs['outstanding'] }}</td>
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['last_payment'] }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr height="40" style="height: 40pt; font-weight: bold; color: #ffffff;">
                                    <td colspan="5" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalDebits }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalCredits }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $closingBalance }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; text-align: center; vertical-align: middle;"></td>
                                </tr>
                            </tfoot>
                        @else
                            <colgroup>
                                <col width="70" style="width: 55pt;" />   {{-- SL NO --}}
                                <col width="240" style="width: 180pt;" /> {{-- Customer Name --}}
                                <col width="160" style="width: 120pt;" /> {{-- Phone Number --}}
                                <col width="260" style="width: 195pt;" /> {{-- Project Name --}}
                                <col width="140" style="width: 105pt;" /> {{-- Unit No --}}
                                <col width="180" style="width: 135pt;" /> {{-- Total Sale --}}
                                <col width="180" style="width: 135pt;" /> {{-- Total Paid --}}
                                <col width="180" style="width: 135pt;" /> {{-- Outstanding --}}
                                <col width="160" style="width: 120pt;" /> {{-- Last Payment --}}
                            </colgroup>
                            <thead>
                                {{-- Row 1: Spacer --}}
                                <tr height="20" style="height: 20pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="38" style="height: 38pt;">
                                    <th colspan="9" bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #8A7522; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        HINDUSTAN REAL ESTATE & INFRASTRUCTURE - ALL CUSTOMERS ACCOUNTS DIRECTORY
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        Comprehensive Accounts Balances & Receivables Overview | Generated On: {{ date('d M Y, h:i A') }}
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#8A7522" style="background-color: #8A7522; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #6B5B1E; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        ACCOUNT SUMMARY & RECEIVABLES
                                    </th>
                                </tr>
                                <tr height="15" style="height: 15pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="30" style="height: 30pt;">
                                    <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #8A7522;">TOTAL SALES AGREEMENTS:</td>
                                    <td colspan="2" bgcolor="#FEF9C3" style="background-color: #FEF9C3; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #0F172A;" data-format="₹#,##0.00">₹{{ number_format($totalDebits, 2) }}</td>
                                    <td colspan="2" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #047857;">TOTAL COLLECTIONS:</td>
                                    <td colspan="3" bgcolor="#ECFDF5" style="background-color: #ECFDF5; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #047857;" data-format="₹#,##0.00">₹{{ number_format($totalCredits, 2) }}</td>
                                </tr>
                                <tr height="30" style="height: 30pt;">
                                    <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #BE123C;">NET OUTSTANDING DUES:</td>
                                    <td colspan="2" bgcolor="#FFF1F2" style="background-color: #FFF1F2; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #BE123C;" data-format="₹#,##0.00">₹{{ number_format($closingBalance, 2) }}</td>
                                    <td colspan="2" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 10pt; color: #B45309;">ACTIVE CUSTOMER ACCOUNTS:</td>
                                    <td colspan="3" bgcolor="#FEF3C7" style="background-color: #FEF3C7; font-weight: bold; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-size: 11pt; color: #B45309;">{{ count($customerSummaryList) }} Accounts</td>
                                </tr>
                                <tr height="15" style="height: 15pt;" data-no-border="true">
                                    <th colspan="9" style="background-color: #ffffff; border: none;"></th>
                                </tr>
                                <tr height="32" style="height: 32pt;">
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">SL NO</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">CUSTOMER NAME</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">PHONE NUMBER</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">PROJECT NAME</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">UNIT NO</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">TOTAL SALE (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">TOTAL PAID (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">OUTSTANDING (₹)</th>
                                    <th bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #047857;">LAST PAYMENT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customerSummaryList as $idx => $cs)
                                @php $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F8FAF5'; @endphp
                                <tr height="25" style="height: 25pt;">
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $idx + 1 }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $cs['customer_name'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'\@'; color: #000000;" data-type="text" data-format="@">{{ $cs['phone'] ?? '-' }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['project'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['unit'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $cs['total_amount'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #047857;">{{ $cs['paid_amount'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #be123c;">{{ $cs['outstanding'] }}</td>
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['last_payment'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr height="40" style="height: 40pt; font-weight: bold; color: #ffffff;">
                                    <td colspan="5" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalDebits }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $totalCredits }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format:'\#\,\#\#0\.00';">{{ $closingBalance }}</td>
                                    <td bgcolor="#0B3B2E" style="background-color: #0B3B2E; border: 1px solid #047857; font-size: 14pt; font-family: 'Calibri', 'Aptos', sans-serif; text-align: center; vertical-align: middle;"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('reports.partials.script')

</x-erp-layout>
