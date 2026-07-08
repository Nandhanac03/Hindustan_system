<x-erp-layout title="Cash Book Register" headerTitle="Cash Book & Flow Register">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="cashBookApp()">
    
    {{-- Top Metrics Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Counter Cash-In-Hand</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹1,75,000</span>
                <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 font-mono">Safe Logged</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Physical currency at main registry desk.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Transit Cheque Vault</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹4,20,005</span>
                <span class="text-[9px] text-amber-600 font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 font-mono">To Clear</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Pending presentation at clearing house.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">ICICI Bank Balance</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹1,12,45,000</span>
                <span class="text-[9px] text-indigo-650 font-bold bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 font-mono">Live Sync</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Direct bank reconciliations completed.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Digital Gateway Escrow</span>
            <div class="flex justify-between items-baseline mt-1">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">₹42,50,000</span>
                <span class="text-[9px] text-emerald-650 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 font-mono">Settled</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Automated RERA accounts partition.</p>
        </div>
    </div>

    {{-- Graphical Charts (ApexCharts) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Trend Chart (2/3 Width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Collections Inflow Trend</h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Weekly aggregate of cash, cheques, and transfers.</p>
                </div>
                <span class="text-[9px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">Jul 2026</span>
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

    </div>

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
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Voucher Ref.</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Post Date</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Transaction Narrative / Customer</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Mode</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Debit (-)</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Credit (+)</th>
                        <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Run Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-slate-650">
                    <template x-for="(t, idx) in filteredLedger()" :key="idx">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3.5 text-slate-500 font-sans" x-text="t.ref"></td>
                            <td class="px-6 py-3.5 text-slate-600 font-sans" x-text="t.date"></td>
                            <td class="px-6 py-3.5 font-sans">
                                <div class="font-bold text-slate-900" x-text="t.narrative"></div>
                                <div class="text-[10px] text-slate-400 font-medium" x-text="t.customer"></div>
                            </td>
                            <td class="px-6 py-3.5 font-sans font-semibold text-slate-700" x-text="t.mode"></td>
                            <td class="px-6 py-3.5 text-right text-rose-600 font-semibold" x-text="t.debit ? '₹' + Number(t.debit).toLocaleString('en-IN') : '-'"></td>
                            <td class="px-6 py-3.5 text-right text-emerald-700 font-extrabold" x-text="t.credit ? '₹' + Number(t.credit).toLocaleString('en-IN') : '-'"></td>
                            <td class="px-6 py-3.5 text-right text-slate-800 font-bold" x-text="'₹' + Number(t.balance).toLocaleString('en-IN')"></td>
                        </tr>
                    </template>
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

        // Mock Ledger log items
        ledger: [
            { ref: 'VCH-0210', date: '2026-07-06', narrative: 'Installment Collection - Block A 502', customer: 'Rajesh Kumar', mode: 'Cheque', debit: 0, credit: 115000, balance: 16198005 },
            { ref: 'VCH-0209', date: '2026-07-05', narrative: 'Bank Cash Deposit - ICICI Remittance', customer: 'Hindustan Safe Transfer', mode: 'Cash', debit: 50000, credit: 0, balance: 16083005 },
            { ref: 'VCH-0208', date: '2026-07-05', narrative: 'EMI Milestone 2 Clearance - Villa B-18', customer: 'Subramanian Swamy', mode: 'Bank', debit: 0, credit: 350000, balance: 16133005 },
            { ref: 'VCH-0207', date: '2026-07-03', narrative: 'Booking Advance Receipt - Apt 101', customer: 'Anita Desai', mode: 'Cash', debit: 0, credit: 100000, balance: 15783005 },
            { ref: 'VCH-0206', date: '2026-07-02', narrative: 'UPI Collection - Block B 104', customer: 'Vikas Sharma', mode: 'Online', debit: 0, credit: 90000, balance: 15683005 },
            { ref: 'VCH-0205', date: '2026-07-01', narrative: 'Office Safe Cash Ledger Topup', customer: 'Petty Cash Desk', mode: 'Cash', debit: 10000, credit: 0, balance: 15593005 },
            { ref: 'VCH-0204', date: '2026-06-28', narrative: 'Advance Collection - Penthouse 501', customer: 'Balaji Parthasarathy', mode: 'Bank', debit: 0, credit: 200000, balance: 15603005 },
            { ref: 'VCH-0203', date: '2026-06-25', narrative: 'Counter Installment Cash Payment', customer: 'Nandhini Chidambaram', mode: 'Cash', debit: 0, credit: 25000, balance: 15403005 }
        ],

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
                    matchesMode = t.mode === 'Bank' || t.mode === 'Online';
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
                    data: [180000, 310000, 240000, 520000, 680000]
                }],
                xaxis: {
                    categories: ['Wk 23', 'Wk 24', 'Wk 25', 'Wk 26', 'Wk 27']
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
                series: [175000, 420005, 11245000, 4250000],
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
