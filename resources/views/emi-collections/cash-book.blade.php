@php
    $cashInHand = \App\Models\Receipt::where('payment_mode', 'Cash')->sum('amount');
    $chequeVault = \App\Models\Receipt::where('payment_mode', 'Cheque')->sum('amount');
    $bankBalance = \App\Models\Receipt::where('payment_mode', 'Bank Transfer')->sum('amount');
    $onlineGateway = \App\Models\Receipt::whereIn('payment_mode', ['Online', 'UPI', 'Credit Card'])->sum('amount');

    // Build chronological ledger items
    $ledgerItems = [];
    $sortedReceipts = $receipts->sortBy('receipt_date');
    $runningBalance = 0;

    foreach ($sortedReceipts as $receipt) {
        $dateStr = $receipt->receipt_date?->format('Y-m-d') ?? '';
        $refStr = 'REC-' . sprintf("%05d", $receipt->id);
        $custName = $receipt->customer?->name ?? '—';
        $projUnit = ($receipt->sale?->project?->name ?? '—') . ' / Unit ' . ($receipt->sale?->unit?->door_no ?? '—');
        
        if ($receipt->partner_id) {
            // Debit: Customer
            $runningBalance += (float)$receipt->amount;
            $ledgerItems[] = [
                'ref' => $refStr,
                'date' => $dateStr,
                'narrative' => 'Debit: Customer (' . $custName . ')',
                'customer' => $custName,
                'project_unit' => $projUnit,
                'mode' => 'Cash',
                'debit' => (float)$receipt->amount,
                'credit' => 0,
                'balance' => $runningBalance
            ];
            
            // Credit: Partner
            $runningBalance -= (float)$receipt->amount;
            $ledgerItems[] = [
                'ref' => $refStr,
                'date' => $dateStr,
                'narrative' => 'Credit: Partner (' . ($receipt->partner?->name ?? 'Partner') . ')',
                'customer' => $receipt->partner?->name ?? 'Partner',
                'project_unit' => $projUnit,
                'mode' => 'Cash',
                'debit' => 0,
                'credit' => (float)$receipt->amount,
                'balance' => $runningBalance
            ];
        } else {
            // Regular receipt: Debit Cash/Inflow
            $runningBalance += (float)$receipt->amount;
            $ledgerItems[] = [
                'ref' => $refStr,
                'date' => $dateStr,
                'narrative' => 'Collection Receipt',
                'customer' => $custName,
                'project_unit' => $projUnit,
                'mode' => $receipt->payment_mode,
                'debit' => (float)$receipt->amount,
                'credit' => 0,
                'balance' => $runningBalance
            ];
        }
    }
    // Reverse to show latest first in table
    $ledgerItems = array_reverse($ledgerItems);

    // Calculate weekly sums for the trend chart
    $weeklySums = [];
    $weeklyWeeks = [];
    for ($i = 4; $i >= 0; $i--) {
        $date = now()->subWeeks($i);
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        $weeklySums[] = (float)\App\Models\Receipt::whereBetween('receipt_date', [$startOfWeek, $endOfWeek])->sum('amount');
        $weeklyWeeks[] = 'Wk ' . $date->format('W');
    }
@endphp

<x-erp-layout title="Cash Book Register" headerTitle="Cash Book & Flow Register">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="cashBookApp()">
    
    {{-- Top Metrics Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Cash Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-colors">
            <div class="absolute right-0 top-0 w-32 h-full bg-gradient-to-l from-emerald-50/50 to-transparent pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Counter Cash</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 uppercase tracking-widest shadow-sm">Safe Logged</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block">₹{{ number_format($cashInHand, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Physical currency at main registry desk.</p>
            </div>
        </div>

        <!-- Cheque Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-colors">
            <div class="absolute right-0 top-0 w-32 h-full bg-gradient-to-l from-amber-50/50 to-transparent pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Cheque Vault</span>
                </div>
                <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100 uppercase tracking-widest shadow-sm">To Clear</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block">₹{{ number_format($chequeVault, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Pending presentation at clearing house.</p>
            </div>
        </div>

        <!-- Bank & Digital Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-colors">
            <div class="absolute right-0 top-0 w-32 h-full bg-gradient-to-l from-blue-50/50 to-transparent pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Bank & Digital</span>
                </div>
                <span class="text-[9px] text-blue-700 font-bold bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100 uppercase tracking-widest shadow-sm">Live Sync</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block">₹{{ number_format($bankBalance + $onlineGateway, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Consolidated bank transfers and gateways.</p>
            </div>
        </div>
    </div>

    <!-- {{-- Graphical Charts (ApexCharts) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Trend Chart (2/3 Width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Collections Inflow Trend</h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Weekly aggregate of cash, cheques, and transfers.</p>
                </div>
                <span class="text-[9px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">{{ now()->format('M Y') }}</span>
            </div>
            <div id="cashFlowTrendChart" class="h-64"></div>
        </div>

        {{-- Share Chart (1/3 Width) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Payment Mode Share</h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Clearing percentage breakdown.</p>
                </div>
            </div>
            <div id="paymentModePieChart" class="h-64 flex items-center justify-center"></div>
        </div>

    </div> -->

    {{-- Book Log Ledger --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-md overflow-hidden flex flex-col mt-4">
        <style>
            #ledger-table thead th {
                border-color: #8a7522 !important;
                position: sticky;
                top: 0;
                z-index: 10;
            }
            #ledger-table tbody tr:nth-child(even) {
                background-color: #F6F3E9;
            }
            #ledger-table tbody tr:hover {
                background-color: #ebe5d0;
                transition: background-color 0.2s ease;
            }
        </style>
        
        {{-- Toolbar --}}
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-[#a38c29] rounded-full shrink-0"></div>
                <div>
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Transaction Statement Ledger</h3>
                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Continuous cash book ledger journal entries.</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <div class="border border-slate-200/90 rounded-xl p-0.5 bg-white flex gap-0.5 text-[10px] font-extrabold uppercase tracking-wider shadow-xs">
                    <button @click="setLedgerMode('All')" 
                            class="px-3 py-1.5 rounded-lg transition font-bold" 
                            :class="activeMode === 'All' ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-500 hover:text-[#a38c29] hover:bg-slate-50'">All</button>
                    <button @click="setLedgerMode('Cash')" 
                            class="px-3 py-1.5 rounded-lg transition font-bold" 
                            :class="activeMode === 'Cash' ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-500 hover:text-[#a38c29] hover:bg-slate-50'">Cash</button>
                    <button @click="setLedgerMode('Cheque')" 
                            class="px-3 py-1.5 rounded-lg transition font-bold" 
                            :class="activeMode === 'Cheque' ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-500 hover:text-[#a38c29] hover:bg-slate-50'">Cheque</button>
                    <button @click="setLedgerMode('Bank/Online')" 
                            class="px-3 py-1.5 rounded-lg transition font-bold" 
                            :class="activeMode === 'Bank/Online' ? 'bg-[#a38c29] text-white shadow-xs' : 'text-slate-500 hover:text-[#a38c29] hover:bg-slate-50'">Bank/Online</button>
                </div>
                
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search narrative..."
                           class="pl-9 pr-3 py-1.5 bg-white border border-slate-200/90 rounded-xl text-xs focus:ring-4 focus:ring-[#a38c29]/15 focus:border-[#a38c29] shadow-xs font-semibold focus:outline-none w-56 transition-all">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto flex-1">
            <table id="ledger-table" class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white font-extrabold text-[9.5px] uppercase tracking-widest border-b border-[#8a7522]">
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Voucher Ref</th>
                        <th class="px-5 py-3.5">Narrative / Customer</th>
                        <th class="px-5 py-3.5">Project / Unit</th>
                        <th class="px-5 py-3.5 text-center">Mode</th>
                        <th class="px-5 py-3.5 text-right">Debit</th>
                        <th class="px-5 py-3.5 text-right">Credit</th>
                        <th class="px-5 py-3.5 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAE3CD] font-mono text-slate-700 bg-white">
                    <template x-for="(t, idx) in paginatedLedger" :key="idx">
                        <tr class="transition-colors border-b border-[#e8dfc3]">
                            <td class="px-5 py-4 text-slate-700 font-sans font-bold" x-text="t.date"></td>
                            <td class="px-5 py-4 text-slate-500 font-sans" x-text="t.ref"></td>
                            <td class="px-5 py-4 font-sans">
                                <div class="font-extrabold text-slate-900" x-text="t.narrative"></div>
                                <div class="text-[10px] text-[#a38c29] font-bold mt-0.5" x-text="t.customer"></div>
                            </td>
                            <td class="px-5 py-4 font-sans text-slate-600 font-semibold" x-text="t.project_unit"></td>
                            <td class="px-5 py-4 font-sans text-center">
                                <span class="px-2 py-1 rounded text-[9px] font-bold tracking-wider uppercase border"
                                      :class="{'bg-[#FAF0D7] text-[#9C6D3B] border-[#EAE3CD]': t.mode === 'Cash', 'bg-blue-50 text-blue-700 border-blue-100': t.mode === 'Cheque', 'bg-emerald-50 text-emerald-700 border-emerald-100': t.mode === 'Online' || t.mode === 'UPI' || t.mode === 'Credit Card', 'bg-purple-50 text-purple-700 border-purple-100': t.mode === 'Bank Transfer' || t.mode === 'Bank'}"
                                      x-text="t.mode"></span>
                            </td>
                            <td class="px-5 py-4 text-right text-rose-600 font-bold" x-text="t.debit ? '₹' + Number(t.debit).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '-'"></td>
                            <td class="px-5 py-4 text-right text-emerald-700 font-extrabold" x-text="t.credit ? '₹' + Number(t.credit).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '-'"></td>
                            <td class="px-5 py-4 text-right text-slate-900 font-extrabold" x-text="'₹' + Number(t.balance).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                        </tr>
                    </template>
                    <tr x-show="filteredLedger().length === 0">
                        <td colspan="8" class="px-6 py-12 text-center text-[#a38c29] font-bold italic font-sans bg-slate-50/50">No transactions match current filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Controls --}}
        <div class="px-6 py-4 border-t border-[#e8dfc3] bg-[#F6F3E9] flex flex-col sm:flex-row items-center justify-between gap-4" x-show="filteredLedger().length > 0">
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                Showing <span class="text-slate-900" x-text="startIndex + 1"></span> to 
                <span class="text-slate-900" x-text="Math.min(endIndex, filteredLedger().length)"></span> of 
                <span class="text-slate-900" x-text="filteredLedger().length"></span> results
            </span>
            <div class="flex items-center gap-1.5">
                <button @click="prevPage()" :disabled="currentPage === 1" class="px-3 py-1.5 border border-[#e0d6b6] bg-white text-slate-500 hover:text-[#a38c29] rounded-md text-[10px] font-bold uppercase disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition shadow-sm">Previous</button>
                <div class="hidden sm:flex items-center gap-1 text-[10px] font-bold font-sans">
                    <template x-for="page in getPageNumbers()" :key="page">
                        <button @click="if(page !== '...') goToPage(page)" 
                                x-text="page" 
                                :disabled="page === '...'"
                                class="w-8 py-1.5 border border-[#e0d6b6] rounded-md transition shadow-sm" 
                                :class="page === currentPage ? 'bg-[#a38c29] text-white border-[#a38c29]' : (page === '...' ? 'bg-transparent border-none shadow-none text-slate-400 cursor-default' : 'bg-white text-slate-500 hover:bg-slate-50 hover:text-[#a38c29]')">
                        </button>
                    </template>
                </div>
                <button @click="nextPage()" :disabled="currentPage === totalPages" class="px-3 py-1.5 border border-[#e0d6b6] bg-white text-slate-500 hover:text-[#a38c29] rounded-md text-[10px] font-bold uppercase disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition shadow-sm">Next</button>
            </div>
        </div>
    </div>

</div>

<script>
function cashBookApp() {
    return {
        activeMode: 'All',
        searchQuery: '',
        currentPage: 1,
        perPage: 50,

        ledger: @json($ledgerItems),

        init() {
            // Render beautiful charts inside Alpine's lifecycle hook
            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        setLedgerMode(mode) {
            this.activeMode = mode;
            this.currentPage = 1;
        },

        filteredLedger() {
            return this.ledger.filter(t => {
                // Filter by mode
                let matchesMode = true;
                if (this.activeMode === 'Cash') {
                    matchesMode = t.mode === 'Cash';
                } else if (this.activeMode === 'Cheque') {
                    matchesMode = t.mode === 'Cheque';
                } else if (this.activeMode === 'Bank/Online') {
                    matchesMode = t.mode === 'Bank' || t.mode === 'Online' || t.mode === 'Bank Transfer' || t.mode === 'UPI' || t.mode === 'Credit Card';
                }

                // Filter by search narrative
                let matchesQuery = this.searchQuery === '' ||
                    t.narrative.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    t.customer.toLowerCase().includes(this.searchQuery.toLowerCase());

                return matchesMode && matchesQuery;
            });
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredLedger().length / this.perPage));
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage;
        },

        get endIndex() {
            return this.startIndex + this.perPage;
        },

        get paginatedLedger() {
            return this.filteredLedger().slice(this.startIndex, this.endIndex);
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        getPageNumbers() {
            const pages = [];
            const total = this.totalPages;
            const current = this.currentPage;
            
            if (total <= 7) {
                for (let i = 1; i <= total; i++) pages.push(i);
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(total);
                } else if (current >= total - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = total - 4; i <= total; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(total);
                }
            }
            return pages;
        },

        renderCharts() {
            // Cash Flow Trend (Weekly Line Chart)
            const trendOptions = {
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                series: [{
                    name: 'Weekly Inflow',
                    data: @json($weeklySums)
                }],
                xaxis: {
                    categories: @json($weeklyWeeks)
                },
                stroke: {
                    curve: 'smooth',
                    colors: ['#a38c29'],
                    width: 3
                },
                markers: {
                    size: 4,
                    colors: ['#a38c29']
                },
                grid: {
                    borderColor: '#f1f1f1'
                },
                colors: ['#a38c29']
            };
            const trendChart = new ApexCharts(document.querySelector("#cashFlowTrendChart"), trendOptions);
            trendChart.render();

            // Mode Share Donut Chart
            const shareOptions = {
                chart: {
                    type: 'donut',
                    height: '100%',
                    fontFamily: 'Inter, sans-serif'
                },
                series: [
                    {{ (float)$cashInHand }}, 
                    {{ (float)$chequeVault }}, 
                    {{ (float)$bankBalance }}, 
                    {{ (float)$onlineGateway }}
                ],
                labels: ['Cash', 'Cheques', 'Bank Accounts', 'Online Gateways'],
                colors: ['#a38c29', '#e3d183', '#4a4014', '#bebab0'],
                legend: {
                    position: 'bottom',
                    fontSize: '10px'
                },
                dataLabels: {
                    enabled: false
                }
            };
            const shareChart = new ApexCharts(document.querySelector("#paymentModePieChart"), shareOptions);
            shareChart.render();
        }
    };
}
</script>

</x-erp-layout>
