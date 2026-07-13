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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Counter Cash-In-Hand</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹{{ number_format($cashInHand, 2) }}</span>
                <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 font-mono">Safe Logged</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Physical currency at main registry desk.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Transit Cheque Vault</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹{{ number_format($chequeVault, 2) }}</span>
                <span class="text-[9px] text-amber-600 font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 font-mono">To Clear</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Pending presentation at clearing house.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">ICICI Bank Balance</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹{{ number_format($bankBalance, 2) }}</span>
                <span class="text-[9px] text-primary-700 font-bold bg-primary-50 px-1.5 py-0.5 rounded border border-primary-200/40 font-mono">Live Sync</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Direct bank reconciliations completed.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Digital Gateway Escrow</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹{{ number_format($onlineGateway, 2) }}</span>
                <span class="text-[9px] text-emerald-650 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 font-mono">Settled</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Automated RERA accounts partition.</p>
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
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-slate-50/20">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Transaction Statement Ledger</h3>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Continuous cash book ledger journal entries.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <div class="border rounded-lg p-0.5 bg-slate-100 flex gap-0.5 text-[10px] font-bold uppercase tracking-wider">
                    <button @click="setLedgerMode('All')" 
                            class="px-2.5 py-1 rounded transition" 
                            :class="activeMode === 'All' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'">All</button>
                    <button @click="setLedgerMode('Cash')" 
                            class="px-2.5 py-1 rounded transition" 
                            :class="activeMode === 'Cash' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'">Cash</button>
                    <button @click="setLedgerMode('Cheque')" 
                            class="px-2.5 py-1 rounded transition" 
                            :class="activeMode === 'Cheque' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'">Cheque</button>
                    <button @click="setLedgerMode('Bank/Online')" 
                            class="px-2.5 py-1 rounded transition" 
                            :class="activeMode === 'Bank/Online' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'">Bank/Online</button>
                </div>
                
                <input type="text" x-model="searchQuery" placeholder="Search narrative..."
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none w-44">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-left">
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Date</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Voucher Ref</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Narrative / Customer</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Project / Unit</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Mode</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Debit</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Credit</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-slate-650">
                    <template x-for="(t, idx) in filteredLedger()" :key="idx">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3.5 text-slate-600 font-sans" x-text="t.date"></td>
                            <td class="px-6 py-3.5 text-slate-500 font-sans" x-text="t.ref"></td>
                            <td class="px-6 py-3.5 font-sans">
                                <div class="font-bold text-slate-900" x-text="t.narrative"></div>
                                <div class="text-[10px] text-slate-400 font-medium" x-text="t.customer"></div>
                            </td>
                            <td class="px-6 py-3.5 font-sans text-slate-600" x-text="t.project_unit"></td>
                            <td class="px-6 py-3.5 font-sans font-semibold text-slate-700" x-text="t.mode"></td>
                            <td class="px-6 py-3.5 text-right text-rose-600 font-semibold" x-text="t.debit ? '₹' + Number(t.debit).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '-'"></td>
                            <td class="px-6 py-3.5 text-right text-emerald-700 font-extrabold" x-text="t.credit ? '₹' + Number(t.credit).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '-'"></td>
                            <td class="px-6 py-3.5 text-right text-slate-800 font-bold" x-text="'₹' + Number(t.balance).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                        </tr>
                    </template>
                    <tr x-show="filteredLedger().length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic font-sans">No transactions match current filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function cashBookApp() {
    return {
        activeMode: 'All',
        searchQuery: '',

        ledger: @json($ledgerItems),

        init() {
            // Render beautiful charts inside Alpine's lifecycle hook
            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        setLedgerMode(mode) {
            this.activeMode = mode;
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
