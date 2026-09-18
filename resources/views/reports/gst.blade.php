<x-erp-layout title="GST & Tax Statutory Executive Ledger" headerTitle="Business Reports Center">

<script>
function gstReportApp() {
    return {
        tab: 'summary',
        filters: {
            search: '',
            fy: '{{ request('fy', '2026-2027') }}',
            date_from: '{{ request('date_from', date('Y-04-01')) }}',
            date_to: '{{ request('date_to', date('Y-m-d')) }}',
            filing_period: '{{ request('filing_period', 'August - 2026') }}',
            section: '{{ request('section', 'all') }}'
        },
        allEntries: @json(($allGstReportEntries ?? $gstReportEntries)->values()),
        
        get filteredEntries() {
            let entries = this.allEntries || [];
            if (this.filters.section && this.filters.section !== 'all') {
                entries = entries.filter(e => e.section_code === this.filters.section);
            }
            if (this.filters.search) {
                const q = this.filters.search.toLowerCase().trim();
                entries = entries.filter(e => 
                    (e.invoice_number && e.invoice_number.toLowerCase().includes(q)) ||
                    (e.entity_name && e.entity_name.toLowerCase().includes(q)) ||
                    (e.customer_name && e.customer_name.toLowerCase().includes(q)) ||
                    (e.project_name && e.project_name.toLowerCase().includes(q)) ||
                    (e.type && e.type.toLowerCase().includes(q)) ||
                    (e.hsn_sac && e.hsn_sac.toLowerCase().includes(q))
                );
            }
            if (this.filters.date_from) {
                entries = entries.filter(e => e.raw_date >= this.filters.date_from);
            }
            if (this.filters.date_to) {
                entries = entries.filter(e => e.raw_date <= this.filters.date_to);
            }
            return entries;
        },
        get outputEntries() {
            return this.filteredEntries.filter(e => e.tax_nature === 'output');
        },
        get inputEntries() {
            return this.filteredEntries.filter(e => e.tax_nature === 'input');
        },
        get totalTaxableSales() {
            return this.outputEntries.reduce((sum, e) => sum + (parseFloat(e.taxable_value) || 0), 0);
        },
        get outputTax() {
            return this.outputEntries.reduce((sum, e) => sum + (parseFloat(e.total_tax) || 0), 0);
        },
        get inputTax() {
            return this.inputEntries.reduce((sum, e) => sum + (parseFloat(e.total_tax) || 0), 0);
        },
        get netPayable() {
            return Math.max(0, this.outputTax - this.inputTax);
        },
        get effectiveTaxRate() {
            return this.totalTaxableSales > 0 ? ((this.outputTax / this.totalTaxableSales) * 100).toFixed(2) : '0.00';
        },
        get totalCgst() {
            return this.outputEntries.reduce((sum, e) => sum + (parseFloat(e.cgst) || 0), 0);
        },
        get totalSgst() {
            return this.outputEntries.reduce((sum, e) => sum + (parseFloat(e.sgst) || 0), 0);
        },
        get totalIgst() {
            return this.outputEntries.reduce((sum, e) => sum + (parseFloat(e.igst) || 0), 0);
        },
        get intraTaxable() {
            return this.outputEntries.filter(e => (parseFloat(e.igst) || 0) === 0).reduce((sum, e) => sum + (parseFloat(e.taxable_value) || 0), 0);
        },
        get interTaxable() {
            return this.outputEntries.filter(e => (parseFloat(e.igst) || 0) > 0).reduce((sum, e) => sum + (parseFloat(e.taxable_value) || 0), 0);
        },
        get tabFilteredEntries() {
            if (this.tab === 'output') return this.outputEntries;
            if (this.tab === 'itc') return this.inputEntries;
            if (this.tab === 'liability') return this.outputEntries;
            return this.filteredEntries;
        },
        resetFilters() {
            this.filters.search = '';
            this.filters.fy = '2026-2027';
            this.filters.date_from = '{{ date('Y-04-01') }}';
            this.filters.date_to = '{{ date('Y-m-d') }}';
            this.filters.filing_period = 'August - 2026';
            this.filters.section = 'all';
        },
        formatCurrency(val) {
            if (val === null || val === undefined || isNaN(val)) return '0.00';
            return Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        formatNumber(val) {
            if (val === null || val === undefined || isNaN(val)) return '0.00';
            return Number(val).toFixed(2);
        },
        exportCurrentTable() {
            if (typeof reportsApp === 'function') {
                const rApp = reportsApp();
                rApp.activeTab = 'gst_report';
                rApp.exportCurrentTable();
            }
        },
        printReport() {
            window.print();
        }
    };
}
</script>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="gstReportApp()">

    @include('reports.partials.nav')

    {{-- 1. Main Header & Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center text-xl shrink-0 shadow-2xs border border-[#a38c29]/30">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    GST & Tax Report
                </h1>
                <p class="text-xs text-slate-500 font-medium">View and analyze GST liability, input tax credit and statutory tax summary.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" @click="exportCurrentTable()" class="h-[42px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Payables
            </button>
            <button type="button" @click="printReport()" class="h-[42px] px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold rounded-xl transition-all shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
        </div>
    </div>

    {{-- 2. Top 5 Metric Cards (Positioned above filter card) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Card 1: Total Sales (Taxable) --}}
        <div class="bg-gradient-to-br from-white via-white to-blue-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-blue-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Sales (Taxable)</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs border border-blue-100 group-hover:scale-105 transition-transform">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-base lg:text-lg font-black text-slate-900 truncate font-mono">
                    ₹<span x-text="formatCurrency(totalTaxableSales)">{{ number_format($gstStats['total_taxable_sales'] ?? $gstStats['total_taxable'], 2) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 block">Taxable Value</span>
            </div>
        </div>

        {{-- Card 2: Output Tax (Liability) --}}
        <div class="bg-gradient-to-br from-white via-white to-emerald-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-emerald-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Output Tax (Liability)</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs border border-emerald-100 group-hover:scale-105 transition-transform">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <div>
                <div class="text-base lg:text-lg font-black text-emerald-600 truncate font-mono">
                    ₹<span x-text="formatCurrency(outputTax)">{{ number_format($gstStats['output_tax'] ?? 0, 2) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 block">Total GST Payable</span>
            </div>
        </div>

        {{-- Card 3: Input Tax Credit (ITC) --}}
        <div class="bg-gradient-to-br from-white via-white to-purple-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-purple-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Input Tax Credit (ITC)</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 shadow-2xs border border-purple-100 group-hover:scale-105 transition-transform">
                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
            <div>
                <div class="text-base lg:text-lg font-black text-purple-600 truncate font-mono">
                    ₹<span x-text="formatCurrency(inputTax)">{{ number_format($gstStats['input_tax'] ?? 0, 2) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 block">Total ITC Available</span>
            </div>
        </div>

        {{-- Card 4: Net GST Payable --}}
        <div class="bg-gradient-to-br from-white via-white to-amber-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Net GST Payable</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] flex items-center justify-center text-xs shrink-0 shadow-2xs font-black border border-amber-200/60 group-hover:scale-105 transition-transform">
                    ₹
                </div>
            </div>
            <div>
                <div class="text-base lg:text-lg font-black text-amber-800 truncate font-mono">
                    ₹<span x-text="formatCurrency(netPayable)">{{ number_format($gstStats['net_payable'] ?? 0, 2) }}</span>
                </div>
                <span class="text-[10px] font-semibold text-slate-400 block">Liability - ITC</span>
            </div>
        </div>

        {{-- Card 5: Effective Tax Rate --}}
        <div class="bg-gradient-to-br from-white via-white to-cyan-50/40 p-4 rounded-2xl border border-slate-200/90 border-l-4 border-l-cyan-500 shadow-2xs space-y-2 hover:-translate-y-1 hover:shadow-md transition-all duration-200 cursor-default group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Effective Tax Rate</span>
                <div class="w-7 h-7 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center text-xs shrink-0 shadow-2xs font-black border border-cyan-100 group-hover:scale-105 transition-transform">
                    %
                </div>
            </div>
            <div>
                <div class="text-base lg:text-lg font-black text-cyan-700 truncate font-mono">
                    <span x-text="effectiveTaxRate">{{ number_format($gstStats['effective_tax_rate'] ?? 18.01, 2) }}</span>%
                </div>
                <span class="text-[10px] font-semibold text-slate-400 block">On Taxable Sales</span>
            </div>
        </div>
    </div>

    {{-- 3. Ultra-Clean Modern Search & Filter Panel (Matching Units page style with icons & fonts) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3 flex-1">
            {{-- Search Input --}}
            <div class="relative group col-span-1 sm:col-span-2 xl:col-span-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search Invoice, Customer, Entity..." 
                       x-model="filters.search"
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                    <button type="button" x-show="filters.search" @click="filters.search = ''"
                            class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Financial Year Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <select x-model="filters.fy"
                        :class="filters.fy ? 'border-[#a38c29] ring-2 ring-[#a38c29]/20' : 'border-slate-250'"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="2026-2027">FY: 2026-2027</option>
                    <option value="2025-2026">FY: 2025-2026</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- From Date --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="date" x-model="filters.date_from"
                       class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
            </div>

            {{-- To Date --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input type="date" x-model="filters.date_to"
                       class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
            </div>

            {{-- Return Filing Period Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <select x-model="filters.filing_period"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Filing Periods</option>
                    <option value="August - 2026">August - 2026</option>
                    <option value="July - 2026">July - 2026</option>
                    <option value="June - 2026">June - 2026</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Report Type Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <select x-model="filters.section"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="all">Summary (All Sections)</option>
                    <option value="sales">Output Tax (Sales)</option>
                    <option value="suppliers">Input Tax Credit (ITC)</option>
                    <option value="extra_works">Extra Works</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button --}}
        <button type="button" @click="resetFilters()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] px-6 py-2.5 text-xs font-black text-white shadow-sm transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>RESET FILTERS</span>
        </button>
    </div>

    {{-- 4. GST Details Section (Interactive Sub-Tabs & Instant Data Display Table) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 space-y-5">
        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">GST Details</h3>

        {{-- Tab Navigation Header Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200/80 pb-3">
            <div class="inline-flex items-center p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/80 gap-1 text-xs font-bold shadow-inner">
                <button type="button" @click="tab = 'summary'" 
                    :class="tab === 'summary' 
                        ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white shadow-sm font-black' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'"
                    class="px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Summary
                </button>
                <button type="button" @click="tab = 'output'" 
                    :class="tab === 'output' 
                        ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white shadow-sm font-black' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'"
                    class="px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Output Tax
                </button>
                <button type="button" @click="tab = 'itc'" 
                    :class="tab === 'itc' 
                        ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white shadow-sm font-black' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'"
                    class="px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Input Tax Credit
                </button>
                <button type="button" @click="tab = 'liability'" 
                    :class="tab === 'liability' 
                        ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white shadow-sm font-black' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'"
                    class="px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tax Liability
                </button>
            </div>
            <div class="text-xs font-semibold text-slate-400 hidden sm:block">
                Showing statutory breakdown by tax category
            </div>
        </div>

        {{-- Tab 1: Summary Table --}}
        <div x-show="tab === 'summary'" class="overflow-x-auto rounded-xl border border-slate-200 shadow-2xs">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-white font-extrabold">Tax Type</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Taxable Value (₹)</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">CGST (₹)</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">SGST (₹)</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Total Tax (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700 bg-white">
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-slate-900">Intra-State (CGST + SGST)</td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">₹<span x-text="formatCurrency(intraTaxable)">{{ number_format($gstStats['intra_taxable'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono">₹<span x-text="formatCurrency(totalCgst)">{{ number_format($gstStats['total_cgst'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono">₹<span x-text="formatCurrency(totalSgst)">{{ number_format($gstStats['total_sgst'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">₹<span x-text="formatCurrency(totalCgst + totalSgst)">{{ number_format(($gstStats['total_cgst'] ?? 0) + ($gstStats['total_sgst'] ?? 0), 2) }}</span></td>
                    </tr>
                    <tr x-show="interTaxable > 0 || totalIgst > 0" class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-slate-900">Inter-State (IGST)</td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">₹<span x-text="formatCurrency(interTaxable)">{{ number_format($gstStats['inter_taxable'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono text-slate-400">0.00</td>
                        <td class="px-5 py-3.5 text-right font-mono text-slate-400">0.00</td>
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">₹<span x-text="formatCurrency(totalIgst)">{{ number_format($gstStats['total_igst'] ?? 0, 2) }}</span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-amber-50/80 border-t-2 border-[#a38c29] font-black text-slate-900 text-xs">
                        <td class="px-5 py-3.5 uppercase">Total</td>
                        <td class="px-5 py-3.5 text-right font-mono">₹<span x-text="formatCurrency(totalTaxableSales)">{{ number_format($gstStats['total_taxable_sales'] ?? $gstStats['total_taxable'], 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono">₹<span x-text="formatCurrency(totalCgst)">{{ number_format($gstStats['total_cgst'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono">₹<span x-text="formatCurrency(totalSgst)">{{ number_format($gstStats['total_sgst'] ?? 0, 2) }}</span></td>
                        <td class="px-5 py-3.5 text-right font-mono text-[#a38c29] text-sm">₹<span x-text="formatCurrency(outputTax)">{{ number_format($gstStats['output_tax'] ?? 0, 2) }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Tab 2, 3, 4: Detailed Transactions Log --}}
        <div x-show="tab !== 'summary'" class="overflow-x-auto rounded-xl border border-slate-200 shadow-2xs">
            <table id="reportsTable" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-4 py-3 text-white font-extrabold">Tax Nature / Section</th>
                        <th class="px-4 py-3 text-white font-extrabold">Invoice No</th>
                        <th class="px-4 py-3 text-center text-white font-extrabold">Date</th>
                        <th class="px-4 py-3 text-white font-extrabold">Customer / Entity</th>
                        <th class="px-4 py-3 text-right text-white font-extrabold">Taxable Base (₹)</th>
                        <th class="px-4 py-3 text-center text-white font-extrabold">GST (%)</th>
                        <th class="px-4 py-3 text-right text-white font-extrabold">GST Amount (₹)</th>
                        <th class="px-4 py-3 text-right text-white font-extrabold">Invoice Total (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-slate-700 bg-white">
                    <template x-for="row in tabFilteredEntries" :key="row.id">
                        <tr class="hover:bg-slate-50 transition-colors font-medium">
                            <td class="px-4 py-2.5 font-sans">
                                <span :class="row.tax_nature === 'output' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200'" class="px-2.5 py-0.5 rounded text-[9px] font-black uppercase inline-block" x-text="row.type || 'GST'"></span>
                            </td>
                            <td class="px-4 py-2.5 font-bold text-slate-900" x-text="row.invoice_number || 'N/A'"></td>
                            <td class="px-4 py-2.5 text-center text-slate-500" x-text="row.date || ''"></td>
                            <td class="px-4 py-2.5 font-sans font-bold text-slate-900" x-text="row.entity_name || row.customer_name || 'N/A'"></td>
                            <td class="px-4 py-2.5 text-right font-bold text-slate-900" x-text="'₹' + formatCurrency(row.taxable_value)"></td>
                            <td class="px-4 py-2.5 text-center font-bold text-[#a38c29]" x-text="formatNumber(row.gst_rate) + '%'"></td>
                            <td class="px-4 py-2.5 text-right font-bold text-[#a38c29]" x-text="'₹' + formatCurrency(row.total_tax)"></td>
                            <td class="px-4 py-2.5 text-right font-bold text-slate-900" x-text="'₹' + formatCurrency(row.grand_total)"></td>
                        </tr>
                    </template>
                    <tr x-show="tabFilteredEntries.length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No GST tax transaction records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Notice Disclaimer Footer --}}
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 pt-2">
            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>This report is for information purpose only and should not be considered as final GST return.</span>
        </div>
    </div>
</div>

{{-- Hidden Excel Export Table --}}
<div class="hidden" style="display: none;">
    <table id="gstExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="160" style="width: 120pt;" />
            <col width="140" style="width: 105pt;" />
            <col width="120" style="width: 90pt;" />
            <col width="240" style="width: 180pt;" />
            <col width="140" style="width: 105pt;" />
            <col width="80"  style="width: 60pt;" />
            <col width="140" style="width: 105pt;" />
            <col width="140" style="width: 105pt;" />
        </colgroup>
        <thead>
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="8" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    HINDUSTAN ERP : GST & TAX STATUTORY REPORT
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="8" bgcolor="#A38C29" style="background-color: #A38C29; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD. — STATUTORY AUDIT
                </th>
            </tr>
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="8" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Tax Nature / Section</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Invoice No</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Date</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px;">Customer / Entity</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Taxable Base (₹)</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">GST (%)</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">GST Amount (₹)</th>
                <th bgcolor="#1E293B" style="background-color: #1E293B; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Invoice Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $exportRows = $allGstReportEntries ?? $gstReportEntries;
            @endphp
            @foreach($exportRows as $index => $row)
                @php 
                    $bgColor = $loop->iteration % 2 == 0 ? '#F8FAFC' : '#FFFFFF';
                    $taxNatureColor = ($row->tax_nature ?? 'output') === 'output' ? '#047857' : '#6B21A8';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: {{ $taxNatureColor }}; font-weight: bold; mso-number-format: '\@';">{{ $row->type ?? 'GST' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000; mso-number-format: '\@';">{{ $row->invoice_number ?? 'N/A' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000; mso-number-format: 'dd-mmm-yyyy';">{{ $row->date ?? '' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: left; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-left: 8px; font-weight: bold; color: #000000; mso-number-format: '\@';">{{ $row->entity_name ?? $row->customer_name ?? 'N/A' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $row->taxable_value ?? 0 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; mso-number-format:'0\.0%'; color: #a38c29;">{{ ($row->gst_rate ?? 0) / 100 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #a38c29;">{{ $row->total_tax ?? 0 }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $row->grand_total ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($exportRows) > 0)
        <tfoot>
            <tr height="30" style="height: 30pt;">
                <td colspan="4" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px; font-size: 11pt;">Total Combined GST Statutory Summary</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $gstStats['total_taxable'] ?? 0 }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #475569; font-size: 11pt;">—</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $gstStats['total_tax'] ?? 0 }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ ($gstStats['total_taxable'] ?? 0) + ($gstStats['total_tax'] ?? 0) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
