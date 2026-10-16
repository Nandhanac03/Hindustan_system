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
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] text-amber-700 font-semibold">
                                    Balance after realization: <strong class="font-mono text-slate-900">₹{{ number_format($projectedBalance ?? ($closingBalance - $totalPendingCredits), 2) }}</strong>
                                </span>
                                <a href="{{ route('cheque-realization.queue', array_filter(['customer_id' => request('customer_id')])) }}" 
                                   class="px-2.5 py-1 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-black uppercase tracking-wider transition-all shadow-2xs inline-flex items-center gap-1.5 shrink-0"
                                   title="Go to Cheque Realization Console">
                                    <span>Cheque Realization Console</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
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
                        <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <tr class="erp-table-header border-b border-slate-700">
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
                                            <a href="{{ route('cheque-realization.queue', array_filter(['customer_id' => $row['customer_id'] ?? request('customer_id')])) }}" 
                                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 text-[10px] font-sans font-extrabold whitespace-nowrap shadow-2xs transition-all group"
                                               title="Click to view & process in Cheque Realization Queue">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                                <span>Pending realization</span>
                                                <svg class="w-3 h-3 text-amber-700 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>
                                    @elseif(!empty($row['is_bounced']))
                                        <div class="mt-1">
                                            <a href="{{ route('cheque-realization.queue', array_filter(['customer_id' => $row['customer_id'] ?? request('customer_id'), 'status' => 'bounced'])) }}" 
                                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 hover:bg-rose-200 text-rose-900 border border-rose-300 text-[10px] font-sans font-extrabold whitespace-nowrap shadow-2xs transition-all group"
                                               title="Click to view bounced cheque in Cheque Realization Queue">
                                                <span>Bounced</span>
                                                <svg class="w-3 h-3 text-rose-700 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
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
                    {{-- Top 4 KPI Metric Cards (Matching Cheque Realization Format) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Card 1: Total Sales Agreements --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(37,99,235,0.15)] cursor-default h-full">
                            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Sales Agreements</span>
                                </div>
                                <span class="shrink-0 whitespace-nowrap text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-blue-300 group-hover:bg-blue-100/60">
                                    Sales Value
                                </span>
                            </div>
                            <div class="relative z-10 mt-1">
                                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-blue-600 transition-colors duration-300">
                                    ₹{{ number_format($totalDebits, 2) }}
                                </span>
                                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Combined Sales Value</p>
                            </div>
                        </div>

                        {{-- Card 2: Total Collections --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default h-full">
                            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Collections</span>
                                </div>
                                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                                    Realized
                                </span>
                            </div>
                            <div class="relative z-10 mt-1">
                                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                                    ₹{{ number_format($totalCredits, 2) }}
                                </span>
                                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total Receipts Received</p>
                            </div>
                        </div>

                        {{-- Card 3: Net Outstanding Due --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-default h-full">
                            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Net Outstanding Due</span>
                                </div>
                                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:bg-rose-100/60">
                                    Pending
                                </span>
                            </div>
                            <div class="relative z-10 mt-1">
                                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                                    ₹{{ number_format($closingBalance, 2) }}
                                </span>
                                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Overall Pending Receivables</p>
                            </div>
                        </div>

                        {{-- Card 4: Active Customer Accounts --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default h-full">
                            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Customer Accounts</span>
                                </div>
                                <span class="shrink-0 whitespace-nowrap text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                                    Accounts
                                </span>
                            </div>
                            <div class="relative z-10 mt-1">
                                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                                    {{ $customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator ? $customerSummaryList->total() : count($customerSummaryList) }}
                                </span>
                                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Customers with Active Sales</p>
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
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr class="erp-table-header border-b border-slate-700">
                                        <th class="px-5 py-3.5 text-white font-extrabold">SL NO</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Customer Name & Contact</th>
                                        <th class="px-5 py-3.5 text-white font-extrabold">Unit Details</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Sale (₹)</th>
                                        <th class="px-5 py-3.5 text-right text-white font-extrabold whitespace-nowrap">Total Paid (₹)</th>
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
                                            <!-- <div class="font-bold text-slate-800">{{ $cs['project'] }}</div> -->
                                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 text-slate-700 inline-block mt-0.5">
                                                Unit: {{ $cs['unit_formatted'] ?? $cs['unit'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-bold text-slate-900">
                                            ₹{{ number_format($cs['total_amount'], 2) }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-mono whitespace-nowrap">
                                            <div class="flex flex-col items-end justify-center gap-1">
                                                <span class="font-bold text-xs {{ $cs['paid_amount'] > 0 ? 'text-emerald-700 font-extrabold' : 'text-slate-400 font-semibold' }}">
                                                    ₹{{ number_format($cs['paid_amount'], 2) }}
                                                </span>
                                                @if(!empty($cs['pending_amount']) && $cs['pending_amount'] > 0)
                                                    <a href="{{ route('cheque-realization.queue', ['customer_id' => $cs['customer_id']]) }}" 
                                                       class="mt-1 flex flex-col items-end px-2.5 py-1 rounded-lg bg-gradient-to-r from-amber-50 to-amber-100/90 hover:from-amber-100 hover:to-amber-200 text-amber-950 border border-amber-300 hover:border-amber-400 transition-all group shadow-2xs hover:shadow-xs"
                                                       title="View Cheque Realization Queue for {{ $cs['customer_name'] }}">
                                                        <div class="inline-flex items-center gap-1 font-mono font-black text-amber-950 text-xs">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                                                            <span class="text-xs">⏳</span>
                                                            <span>₹{{ number_format($cs['pending_amount'], 2) }}</span>
                                                        </div>
                                                        <div class="inline-flex items-center gap-1 text-[9px] uppercase tracking-wider font-extrabold text-amber-800 group-hover:text-amber-950 mt-0.5">
                                                            <span>Pending Realization</span>
                                                            <svg class="w-2.5 h-2.5 text-amber-700 transition-transform group-hover:translate-x-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-right font-mono font-black text-rose-600">
                                            ₹{{ number_format($cs['outstanding'], 2) }}
                                        </td>
                                        <td class="px-5 py-4 text-center font-mono text-[11px] text-slate-500">
                                            {{ $cs['last_payment'] }}
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('reports.customer_ledger', ['customer_id' => $cs['customer_id'], 'project_id' => request('project_id')]) }}"
                                                   class="px-4 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white border border-[#8a7522] rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-2xs hover:shadow-md inline-flex items-center gap-1.5 whitespace-nowrap">
                                                    <span>View Ledger</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>

                                                @if(!empty($cs['sale_id']))
                                                <button type="button" @click.stop="openCustomerModalById({{ $cs['sale_id'] }})"
                                                        class="w-8 h-8 rounded-xl bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#a38c29] hover:text-white transition-all inline-flex items-center justify-center shadow-2xs cursor-pointer group"
                                                        title="View Customer & Sale Details">
                                                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <script id="customer-sale-data-{{ $cs['sale_id'] }}" type="application/json">{!! json_encode($cs['modal_payload'] ?? $cs) !!}</script>
                                                @endif
                                            </div>
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
                        @php
                            $isPaginator = $customerSummaryList instanceof \Illuminate\Pagination\LengthAwarePaginator;
                            $hasCustomerPages = $isPaginator && $customerSummaryList->hasPages();
                            $totalCustomerCount = $isPaginator ? $customerSummaryList->total() : count($customerSummaryList);
                            $currentCustomerCount = $isPaginator ? $customerSummaryList->count() : count($customerSummaryList);
                            $firstCustomerItem = $isPaginator ? ($customerSummaryList->firstItem() ?? ($totalCustomerCount > 0 ? 1 : 0)) : ($totalCustomerCount > 0 ? 1 : 0);
                            $lastCustomerItem = $isPaginator ? ($customerSummaryList->lastItem() ?? $currentCustomerCount) : $currentCustomerCount;
                        @endphp
                        @if($totalCustomerCount > 0)
                        <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50">
                            @if($hasCustomerPages)
                                {{ $customerSummaryList->appends(request()->query())->links() }}
                            @else
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    {{-- Mobile View --}}
                                    <div class="flex items-center justify-between w-full sm:hidden">
                                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl uppercase tracking-wider">
                                            Previous
                                        </span>
                                        <span class="inline-flex items-center px-4 py-2 text-[10px] font-extrabold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-xl uppercase tracking-wider">
                                            Next
                                        </span>
                                    </div>

                                    {{-- Desktop View --}}
                                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between w-full">
                                        <div>
                                            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                                Showing
                                                <span class="font-extrabold text-slate-800">{{ $firstCustomerItem }}</span>
                                                to
                                                <span class="font-extrabold text-slate-800">{{ $lastCustomerItem }}</span>
                                                of
                                                <span class="font-extrabold text-slate-800">{{ $totalCustomerCount }}</span>
                                                results
                                            </p>
                                        </div>

                                        <div>
                                            <span class="inline-flex gap-1.5 shadow-sm rounded-xl p-1.5 bg-slate-50/80 border border-slate-200/80 backdrop-blur-sm">
                                                {{-- Previous Disabled --}}
                                                <span aria-disabled="true" aria-label="Previous">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 bg-transparent cursor-not-allowed rounded-lg" aria-hidden="true">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                                    </span>
                                                </span>

                                                {{-- Active Page 1 --}}
                                                <span aria-current="page">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-black text-white bg-[#a38c29] border border-[#a38c29] rounded-lg shadow-sm shadow-[#a38c29]/40">1</span>
                                                </span>

                                                {{-- Next Disabled --}}
                                                <span aria-disabled="true" aria-label="Next">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400 bg-transparent cursor-not-allowed rounded-lg" aria-hidden="true">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                    </span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </nav>
                            @endif
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
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider sticky top-0 z-10">
                                    <tr class="erp-table-header border-b border-slate-700">
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
                                        <td class="px-5 py-3.5 text-right font-mono whitespace-nowrap">
                                            @if($row['credit'] > 0)
                                                <div class="flex flex-col items-end gap-0.5">
                                                    <span class="{{ (!empty($row['is_pending'])) ? 'text-amber-800 font-black' : ((!empty($row['is_bounced'])) ? 'text-rose-400 line-through' : 'text-emerald-700 font-bold') }}">
                                                        ₹{{ number_format($row['credit'], 2) }}
                                                    </span>
                                                    @if(!empty($row['is_pending']))
                                                        <a href="{{ route('cheque-realization.queue', array_filter(['customer_id' => $row['customer_id'] ?? null])) }}" 
                                                           class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 text-[9px] font-sans font-extrabold whitespace-nowrap shadow-2xs transition-all group"
                                                           title="View in Cheque Realization Queue">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                            <span>Pending realization</span>
                                                            <svg class="w-2.5 h-2.5 text-amber-700 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
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
                                        HINDUSTAN REAL ESTATE & INFRASTRUCTURE @if(request('project_id') && ($activeProj = \App\Models\Project::find(request('project_id')))) - {{ strtoupper($activeProj->name) }} @endif - CUSTOMER EMI & ACCOUNT STATEMENT
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        Target Customer(s): {{ $selectedCustomers->pluck('name')->implode(', ') }} @if(request('project_id') && ($activeProj = \App\Models\Project::find(request('project_id')))) | Project: {{ $activeProj->name }} @else | Project: All Projects @endif | Generated On: {{ date('d M Y, h:i A') }}
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
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">SL NO</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">INSTALLMENT / MILESTONE</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">DUE DATE</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PROJECT NAME</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">UNIT NO</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">INSTALLMENT (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PAID AMOUNT (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">OUTSTANDING (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">STATUS</th>
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
                                        <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['unit_formatted'] ?? $cs['unit'] }}</td>
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
                                        HINDUSTAN REAL ESTATE & INFRASTRUCTURE @if(request('project_id') && ($activeProj = \App\Models\Project::find(request('project_id')))) - {{ strtoupper($activeProj->name) }} @endif - ALL CUSTOMERS ACCOUNTS DIRECTORY
                                    </th>
                                </tr>
                                <tr height="26" style="height: 26pt;">
                                    <th colspan="9" bgcolor="#0B3B2E" style="background-color: #0B3B2E; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #047857; font-family: 'Calibri', 'Aptos', sans-serif;">
                                        @if(request('project_id') && ($activeProj = \App\Models\Project::find(request('project_id')))) Project: {{ $activeProj->name }} | @endif Comprehensive Accounts Balances & Receivables Overview | Generated On: {{ date('d M Y, h:i A') }}
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
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">SL NO</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">CUSTOMER NAME</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PHONE NUMBER</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">PROJECT NAME</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">UNIT NO</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">TOTAL SALE (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">TOTAL PAID (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">OUTSTANDING (₹)</th>
                                    <th bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #8A7522;">LAST PAYMENT</th>
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
                                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cs['unit_formatted'] ?? $cs['unit'] }}</td>
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

        {{-- VIEW CUSTOMER ACCOUNT & SALE DETAILS MODAL POPUP (COMMON MODAL UI) --}}
        <div x-show="showCustomerModal" 
             x-cloak
             class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm print:hidden" 
             style="display: none;" 
             x-transition.opacity
             @keydown.escape.window="closeCustomerModal()">

            <div class="w-full max-w-4xl max-h-[90vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-fade-in my-auto" 
                 @click.away="closeCustomerModal()">
                
                {{-- Executive Dark Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-4 shrink-0">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Customer Ledger</span>
                                <span class="px-2 py-0.5 rounded bg-white/10 text-slate-300 text-[9px] font-bold uppercase tracking-wider whitespace-nowrap" x-text="selectedCustomerSale?.status || 'Active'"></span>
                            </div>
                            <h2 class="text-base sm:text-lg font-extrabold text-white tracking-wider uppercase">CUSTOMER ACCOUNT & SALE STATEMENT</h2>
                        </div>
                        <button type="button" @click="closeCustomerModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 cursor-pointer" title="Close">✕</button>
                    </div>
                </div>
                
                {{-- Scrollable Modal Body --}}
                <div class="p-3.5 sm:p-4 space-y-3 font-sans text-xs bg-slate-50/50 flex-1 min-h-0 overflow-y-auto">
                        
                    {{-- Primary Customer & Transaction Details Overview Grid --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-2.5 shadow-2xs">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                            {{-- Customer Name --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Customer</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedCustomerSale?.customer?.name || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedCustomerSale?.customer?.phone" x-text="selectedCustomerSale?.customer?.phone || ''"></span>
                                </div>
                            </div>

                            {{-- Sale Reference --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Sale Ref</span>
                                    <span class="text-slate-800 font-mono text-xs font-bold truncate block mt-0.5" x-text="selectedCustomerSale?.sale_number || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block" x-show="selectedCustomerSale?.sale_date" x-text="'Date: ' + (selectedCustomerSale?.sale_date || '')"></span>
                                </div>
                            </div>

                            {{-- Project --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Project</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedCustomerSale?.project?.name || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedCustomerSale?.project?.location" x-text="selectedCustomerSale?.project?.location || ''"></span>
                                </div>
                            </div>

                            {{-- Unit Details --}}
                            <div class="flex items-center gap-2.5 bg-slate-50/80 rounded-xl p-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block leading-tight">Unit Details</span>
                                    <span class="text-slate-800 text-xs font-bold truncate block mt-0.5" x-text="selectedCustomerSale?.unit?.formatted_name || selectedCustomerSale?.unit?.door_no || '—'"></span>
                                    <span class="text-[9px] text-slate-500 font-medium block truncate" x-show="selectedCustomerSale?.unit?.built_up_area" x-text="(selectedCustomerSale?.unit?.built_up_area || '') + ' Sq.Ft'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Overview 4-Stat Box --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                            <div class="text-center p-1.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Agreement Value</span>
                                <span class="text-sm font-extrabold text-slate-900 font-mono block mt-1" x-text="fmtMoney(selectedCustomerSale?.financials?.contract_value)"></span>
                            </div>
                            <div class="text-center p-1.5">
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider block">Total Paid (Realized)</span>
                                <span class="text-sm font-extrabold text-emerald-600 font-mono block mt-1" x-text="fmtMoney(selectedCustomerSale?.financials?.total_paid)"></span>
                            </div>
                            <div class="text-center p-1.5">
                                <span class="text-[9px] font-bold text-amber-700 uppercase tracking-wider block">Pending Realization</span>
                                <span class="text-sm font-extrabold font-mono block mt-1" 
                                      :class="(selectedCustomerSale?.financials?.pending_amount > 0) ? 'text-amber-700 font-black' : 'text-slate-400'"
                                      x-text="fmtMoney(selectedCustomerSale?.financials?.pending_amount)"></span>
                            </div>
                            <div class="text-center p-1.5">
                                <span class="text-[9px] font-bold text-rose-700 uppercase tracking-wider block">Net Outstanding Due</span>
                                <span class="text-sm font-extrabold text-rose-600 font-mono block mt-1" x-text="fmtMoney(selectedCustomerSale?.financials?.outstanding)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Horizontal Notes Bar (if exists) --}}
                    <template x-if="selectedCustomerSale?.notes && selectedCustomerSale?.notes !== '—'">
                        <div class="bg-white border border-slate-200/90 rounded-xl px-4 py-2 text-xs flex items-center gap-2 shadow-2xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest shrink-0">NOTES / NARRATION:</span>
                            <span class="text-slate-800 font-semibold truncate" x-text="selectedCustomerSale?.notes"></span>
                        </div>
                    </template>

                    {{-- Tab Navigation for Audit Logs --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-3.5 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="customerModalTab = 'receipts'"
                                        :class="customerModalTab === 'receipts' ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                        class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer">
                                    <span>Receipts History</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono"
                                          :class="customerModalTab === 'receipts' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                          x-text="selectedCustomerSale?.receipts?.length || 0"></span>
                                </button>

                                <button type="button" @click="customerModalTab = 'installments'"
                                        :class="customerModalTab === 'installments' ? 'bg-[#a38c29] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                        class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 cursor-pointer">
                                    <span>Milestones & EMI Schedule</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono"
                                          :class="customerModalTab === 'installments' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                          x-text="selectedCustomerSale?.installments?.length || 0"></span>
                                </button>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono font-bold uppercase" x-text="(customerModalTab === 'receipts') ? 'Advance & Payment Records' : 'Scheduled Allocations'"></span>
                        </div>

                        {{-- Tab 1: Receipts History Table --}}
                        <div x-show="customerModalTab === 'receipts'" class="space-y-2">
                            <div class="overflow-x-auto rounded-xl border border-slate-200 max-h-[300px]">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead class="bg-slate-100 text-slate-700 uppercase text-[9px] font-bold sticky top-0 z-10">
                                        <tr>
                                            <th class="py-2 px-3 text-center w-10">#</th>
                                            <th class="py-2 px-3">Receipt Date</th>
                                            <th class="py-2 px-3">Voucher / Ref No</th>
                                            <th class="py-2 px-3">Payment Mode</th>
                                            <th class="py-2 px-3">Bank / Account</th>
                                            <th class="py-2 px-3 text-center">Realization Status</th>
                                            <th class="py-2 px-3 text-right">Amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-sans">
                                        <template x-for="(rc, i) in (selectedCustomerSale?.receipts || [])" :key="i">
                                            <tr class="hover:bg-slate-50/80 transition-colors" :class="rc.is_pending ? 'bg-amber-50/30' : ''">
                                                <td class="py-2 px-3 text-center text-slate-400 font-mono text-[10px]" x-text="i + 1"></td>
                                                <td class="py-2 px-3 font-semibold text-slate-700" x-text="rc.date"></td>
                                                <td class="py-2 px-3 font-mono font-bold text-indigo-700 text-[11px]" x-text="rc.ref_no"></td>
                                                <td class="py-2 px-3 text-slate-600" x-text="rc.payment_mode"></td>
                                                <td class="py-2 px-3 text-slate-600 truncate max-w-[150px]" x-text="rc.bank_name"></td>
                                                <td class="py-2 px-3 text-center">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider inline-block"
                                                          :class="rc.is_pending ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300'"
                                                          x-text="rc.status"></span>
                                                </td>
                                                <td class="py-2 px-3 text-right font-mono font-bold" 
                                                    :class="rc.is_pending ? 'text-amber-800' : 'text-emerald-700'"
                                                    x-text="fmtMoney(rc.amount)"></td>
                                            </tr>
                                        </template>
                                        <template x-if="!selectedCustomerSale?.receipts || selectedCustomerSale?.receipts?.length === 0">
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-slate-400 italic text-xs">
                                                    <div class="flex flex-col items-center justify-center gap-1">
                                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span>No customer payment receipts recorded for this sale.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Tab 2: EMI Schedule Table --}}
                        <div x-show="customerModalTab === 'installments'" class="space-y-2">
                            <div class="overflow-x-auto rounded-xl border border-slate-200 max-h-[300px]">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead class="bg-slate-100 text-slate-700 uppercase text-[9px] font-bold sticky top-0 z-10">
                                        <tr>
                                            <th class="py-2 px-3 text-center w-10">#</th>
                                            <th class="py-2 px-3">Milestone / Installment</th>
                                            <th class="py-2 px-3">Due Date</th>
                                            <th class="py-2 px-3 text-right">Installment (₹)</th>
                                            <th class="py-2 px-3 text-right">Paid (₹)</th>
                                            <th class="py-2 px-3 text-right">Outstanding (₹)</th>
                                            <th class="py-2 px-3 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-sans">
                                        <template x-for="(inst, i) in (selectedCustomerSale?.installments || [])" :key="i">
                                            <tr class="hover:bg-slate-50/80 transition-colors">
                                                <td class="py-2 px-3 text-center text-slate-400 font-mono text-[10px]" x-text="i + 1"></td>
                                                <td class="py-2 px-3 font-semibold text-slate-800" x-text="inst.label"></td>
                                                <td class="py-2 px-3 text-slate-600 font-mono text-[11px]" x-text="inst.due_date"></td>
                                                <td class="py-2 px-3 text-right font-mono font-bold text-slate-800" x-text="fmtMoney(inst.amount)"></td>
                                                <td class="py-2 px-3 text-right font-mono font-bold text-emerald-600" x-text="fmtMoney(inst.paid_amount)"></td>
                                                <td class="py-2 px-3 text-right font-mono font-bold" 
                                                    :class="(inst.outstanding > 0) ? 'text-rose-600' : 'text-slate-400'"
                                                    x-text="fmtMoney(inst.outstanding)"></td>
                                                <td class="py-2 px-3 text-center">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider inline-block"
                                                          :class="inst.status.toLowerCase() === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : (inst.is_overdue ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-amber-100 text-amber-800 border border-amber-300')"
                                                          x-text="inst.status"></span>
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-if="!selectedCustomerSale?.installments || selectedCustomerSale?.installments?.length === 0">
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-slate-400 italic text-xs">
                                                    <div class="flex flex-col items-center justify-center gap-1">
                                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <span>No customized installment schedule registered for this account.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Sticky Modal Footer (Outside scrolling body) --}}
                <div class="px-5 py-2.5 bg-white border-t border-slate-200/80 flex items-center justify-between shrink-0 shadow-2xs">
                    <div class="flex items-center gap-2 text-[11px] text-slate-500 min-w-0">
                        <span class="font-medium shrink-0">Account:</span>
                        <span class="font-bold text-slate-800 truncate" x-text="selectedCustomerSale?.customer?.name || '—'"></span>
                        <span class="text-slate-300">•</span>
                        <span class="font-medium text-slate-600 truncate" x-text="selectedCustomerSale?.unit?.formatted_name || selectedCustomerSale?.unit?.door_no || '—'"></span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a :href="'{{ route('reports.customer_ledger') }}?customer_id=' + (selectedCustomerSale?.customer?.id || '')" 
                           class="px-3.5 py-1.5 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-2xs inline-flex items-center gap-1.5 cursor-pointer">
                            <span>Open Full Ledger</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <button type="button" @click="closeCustomerModal()"
                                class="px-4 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase flex items-center gap-1.5 cursor-pointer shadow-2xs">
                            <span>✕</span>
                            <span>CLOSE</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

@include('reports.partials.script')

</x-erp-layout>
