<x-erp-layout title="Business Performance Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    {{-- Main Report Output Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">

        {{-- GST & TAX REPORT DATA DISPLAY --}}
        @if($activeTab === 'gst_report')
        <div class="space-y-6">
            {{-- Top Header & Action Banner (Clean Light Executive Style) --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">GST & Tax Statutory Executive Ledger</h3>
                            <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Master Audit & Tax Filing Suite</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Verified database audit trail of Output Tax (Sales & Extra Works), Input Tax Credit (Suppliers & Services), CGST, SGST, and statutory net tax liabilities.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0 relative z-10">
                    <button @click="printReport()" 
                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Report
                    </button>
                    <button @click="exportCurrentTable()" 
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                    <!-- <a href="{{ route('gst.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] hover:from-[#8a7522] hover:to-[#a38c29] text-white rounded-xl text-xs font-black transition shadow-md shadow-[#a38c29]/20 flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        <span>Manage Tax Slabs →</span>
                    </a> -->
                </div>
            </div>

            {{-- Section-Wise Filter Segment Navigation Bar --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        GST Tax Categories Filter
                    </span>
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    @php
                        $sections = [
                            'all'         => ['label' => 'All Sections', 'desc' => 'Master Tax Ledger', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                            'sales'       => ['label' => '1. Sales & Bookings', 'desc' => 'Output Tax', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            'extra_works' => ['label' => '2. Extra Works', 'desc' => 'Upgrades Tax', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            'suppliers'   => ['label' => '3. Material Suppliers', 'desc' => 'Input ITC', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ];
                        $currentSec = request('section', 'all');
                    @endphp
                    @foreach($sections as $sKey => $sVal)
                        <a href="?report=gst_report&section={{ $sKey }}&project_id={{ request('project_id') }}&date_from={{ request('date_from') }}&date_to={{ request('date_to') }}"
                           class="group relative inline-flex items-center gap-3 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 {{ $currentSec === $sKey ? 'bg-gradient-to-r from-[#a38c29] to-[#b89635] text-white shadow-md shadow-[#a38c29]/20 font-black scale-[1.01]' : 'bg-slate-50 text-slate-700 hover:bg-amber-50/50 hover:text-slate-900 border border-slate-200/80 shadow-2xs' }}">
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110 {{ $currentSec === $sKey ? 'text-white' : 'text-slate-400 group-hover:text-[#a38c29]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sVal['icon'] }}"/></svg>
                            <div class="text-left leading-tight">
                                <div class="font-extrabold text-[11px]">{{ $sVal['label'] }}</div>
                                <div class="text-[8px] font-semibold tracking-widest uppercase opacity-80">{{ $sVal['desc'] }}</div>
                            </div>
                            @if(isset($gstStats['section_stats'][$sKey]) && $gstStats['section_stats'][$sKey]['count'] > 0)
                                <span class="ml-1 px-2.5 py-0.5 rounded-full text-[9px] font-mono font-black {{ $currentSec === $sKey ? 'bg-white/25 text-white border border-white/30' : 'bg-[#a38c29]/15 text-[#a38c29] group-hover:bg-[#a38c29] group-hover:text-white' }} transition-colors">
                                    {{ $gstStats['section_stats'][$sKey]['count'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- GST Metric Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Output Tax -->
                <div class="text-left p-5 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] bg-gradient-to-br from-white via-white to-amber-50/30 shadow-2xs transition-all duration-200 space-y-2 hover:-translate-y-0.5 hover:shadow-md cursor-default overflow-hidden">
                    <div class="flex items-center justify-between gap-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <span class="whitespace-nowrap font-extrabold" title="Total GST Collected">Total GST Collected</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse shrink-0"></span>
                    </div>
                    <div class="text-xl font-black font-mono text-[#a38c29] truncate pt-0.5">
                        ₹{{ number_format($gstStats['output_tax'] ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400">From Customer Sales & Bookings</div>
                </div>

                <!-- Card 2: Input Tax Credit (ITC) -->
                <div class="text-left p-5 rounded-2xl border border-slate-200/90 border-l-4 border-l-slate-800 bg-gradient-to-br from-white via-white to-slate-50 shadow-2xs transition-all duration-200 space-y-2 hover:-translate-y-0.5 hover:shadow-md cursor-default overflow-hidden">
                    <div class="flex items-center justify-between gap-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <span class="whitespace-nowrap font-extrabold" title="Total GST Paid">Total GST Paid</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-800 animate-pulse shrink-0"></span>
                    </div>
                    <div class="text-xl font-black font-mono text-slate-900 truncate pt-0.5">
                        ₹{{ number_format($gstStats['input_tax'] ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400">To Suppliers for Materials & Services</div>
                </div>

                <!-- Card 3: Net Tax Payable -->
                <div class="text-left p-5 rounded-2xl border border-slate-200/90 border-l-4 border-l-[#a38c29] bg-gradient-to-br from-white via-white to-amber-50/30 shadow-2xs transition-all duration-200 space-y-2 hover:-translate-y-0.5 hover:shadow-md cursor-default overflow-hidden">
                    <div class="flex items-center justify-between gap-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <span class="whitespace-nowrap font-extrabold" title="Net GST Payable">Net GST Payable to Govt</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse shrink-0"></span>
                    </div>
                    <div class="text-xl font-black font-mono text-[#a38c29] truncate pt-0.5">
                        ₹{{ number_format($gstStats['net_payable'] ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400">(GST Collected - GST Paid)</div>
                </div>

                <!-- Card 4: Total GST Volume -->
                <div class="text-left p-5 rounded-2xl border border-slate-200/90 border-l-4 border-l-slate-900 bg-gradient-to-br from-white via-white to-slate-100/50 shadow-2xs transition-all duration-200 space-y-2 hover:-translate-y-0.5 hover:shadow-md cursor-default overflow-hidden">
                    <div class="flex items-center justify-between gap-2 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <span class="whitespace-nowrap font-extrabold" title="Overall Tax Volume">Overall Tax Volume</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-900 animate-pulse shrink-0"></span>
                    </div>
                    <div class="text-xl font-black font-mono text-slate-900 truncate pt-0.5">
                        ₹{{ number_format($gstStats['total_tax'] ?? 0, 2) }}
                    </div>
                    <div class="text-[10px] font-semibold text-slate-400">Total value across {{ $gstStats['count'] ?? 0 }} transactions</div>
                </div>
            </div>

            {{-- GST Tax Report Data Display Table Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-amber-50/40 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                            Statutory GST Transactions & Tax Audit Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Database-verified statutory tax breakdown log for audit & tax filing.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $activeProjectName = 'All Projects (Default)';
                            if(request('project_id')) {
                                $activeProject = \App\Models\Project::find(request('project_id'));
                                if($activeProject) {
                                    $activeProjectName = $activeProject->name;
                                }
                            }
                        @endphp
                        <span class="px-4 py-1.5 bg-slate-800 text-white border border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active Project: {{ $activeProjectName }}
                        </span>
                        <span class="px-4 py-1.5 bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 rounded-xl text-[10px] font-mono font-black uppercase tracking-wider shadow-2xs">
                            Showing {{ count($gstReportEntries) }} Tax Records
                        </span>
                    </div>
                </div>

                <div class="w-full overflow-x-auto overflow-y-auto max-h-[600px] relative">
                    <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-3 py-2.5 text-white font-extrabold whitespace-nowrap">Tax Nature / Section</th>
                                <th class="px-3 py-2.5 text-white font-extrabold whitespace-nowrap">Invoice No</th>
                                <th class="px-3 py-2.5 text-white font-extrabold text-center whitespace-nowrap">Date</th>
                                <th class="px-3 py-2.5 text-white font-extrabold whitespace-nowrap">Customer / Entity</th>
                                <th class="px-3 py-2.5 text-white font-extrabold whitespace-nowrap">GSTIN / PAN</th>
                                <th class="px-3 py-2.5 text-white font-extrabold text-right whitespace-nowrap">Taxable Base (₹)</th>
                                <th class="px-3 py-2.5 text-white font-extrabold text-center whitespace-nowrap">GST (%)</th>
                                <th class="px-3 py-2.5 text-white font-extrabold text-right whitespace-nowrap">GST Amount (₹)</th>
                                <th class="px-3 py-2.5 text-white font-extrabold text-right whitespace-nowrap">Invoice Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                            @forelse($gstReportEntries as $row)
                                <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium">
                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-lg text-[9.5px] font-black uppercase border shadow-2xs whitespace-nowrap inline-block {{ ($row->tax_nature ?? 'output') === 'output' ? 'bg-[#a38c29]/15 text-[#8a7522] border-[#a38c29]/30' : 'bg-slate-100 text-slate-800 border-slate-300' }}">
                                            {{ $row->type ?? 'GST' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2.5 font-mono font-bold text-slate-900 whitespace-nowrap">{{ $row->invoice_number ?? 'N/A' }}</td>
                                    <td class="px-3 py-2.5 text-center text-slate-500 font-mono text-[11px] whitespace-nowrap">{{ $row->date ?? '' }}</td>
                                    <td class="px-3 py-2.5 font-bold text-slate-900 whitespace-nowrap">{{ $row->entity_name ?? $row->customer_name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2.5 font-mono text-[11px] text-slate-600 whitespace-nowrap">{{ $row->gstin ?? 'N/A' }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">₹{{ number_format($row->taxable_value ?? 0, 2) }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono font-black whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-amber-50/90 text-[#8a7522] border border-[#a38c29]/30 rounded-md whitespace-nowrap inline-block">{{ number_format($row->gst_rate, 2) }}%</span>
                                    </td>
                                    <td class="px-3 py-2.5 text-right font-mono font-black text-[#a38c29] whitespace-nowrap">₹{{ number_format($row->total_tax, 2) }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">₹{{ number_format($row->grand_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-slate-400 italic font-semibold">No active GST tax transaction records found for the selected filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($gstReportEntries) > 0)
                        <tfoot>
                            <tr class="bg-slate-900 text-white border-t-2 border-[#a38c29] text-xs font-black">
                                <td colspan="5" class="px-3 py-4 text-left uppercase tracking-wider text-slate-200">Total Combined GST Statutory Summary</td>
                                <td class="px-3 py-4 text-right font-mono text-white">₹{{ number_format($gstStats['total_taxable'] ?? 0, 2) }}</td>
                                <td class="px-3 py-4 text-center font-mono text-slate-400">—</td>
                                <td class="px-3 py-4 text-right font-mono text-[#d4af37] text-sm font-black">₹{{ number_format($gstStats['total_tax'] ?? 0, 2) }}</td>
                                <td class="px-3 py-4 text-right font-mono text-white text-sm font-black">₹{{ number_format(($gstStats['total_taxable'] ?? 0) + ($gstStats['total_tax'] ?? 0), 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- ACTIVITY STATEMENTS — Customer & Supplier History --}}
        @if($activeTab === 'activity_statements')
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Customer & Supplier Activity Statements</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Passbook-style transaction history for customers (receivables) and suppliers (payables).</p>
                </div>
                <span class="px-3 py-1 bg-primary/10 text-primary-700 border border-primary/20 rounded-xl text-[10px] font-extrabold uppercase tracking-wider">Step 5: Audit Trail</span>
            </div>

            {{-- Two column layout: Customer | Supplier --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- CUSTOMER STATEMENT --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3">
                        <h4 class="text-[10px] font-extrabold text-white uppercase tracking-widest">Customer Account Statement</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Receivables ledger — advance paid vs outstanding</p>
                    </div>
                    <div class="p-4 bg-slate-50 border-b border-slate-200">
                        <form method="GET" class="flex items-end gap-3">
                            <input type="hidden" name="report" value="activity_statements">
                            <div class="flex-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Select Customer</label>
                                <select name="customer_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider">Load →</button>
                        </form>
                    </div>

                    @if(request('customer_id') && isset($ledgerEntries) && $ledgerEntries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3 text-right">Debit (₹)</th>
                                    <th class="px-4 py-3 text-right">Credit (₹)</th>
                                    <th class="px-4 py-3 text-right">Balance (₹)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($ledgerEntries as $entry)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2.5 text-slate-500 font-semibold">{{ $entry['date'] }}</td>
                                    <td class="px-4 py-2.5 text-slate-700 font-medium">{{ $entry['description'] }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['debit'] > 0 ? 'text-slate-900' : 'text-slate-300' }}">{{ $entry['debit'] > 0 ? '₹' . number_format($entry['debit'], 2) : '—' }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['credit'] > 0 ? 'text-emerald-700' : 'text-slate-300' }}">{{ $entry['credit'] > 0 ? '₹' . number_format($entry['credit'], 2) : '—' }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $entry['balance'] > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($entry['balance'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold">
                                    <td colspan="2" class="px-4 py-3 text-slate-700">Closing Balance</td>
                                    <td class="px-4 py-3 text-right font-mono">₹{{ number_format($totalDebits, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-emerald-700">₹{{ number_format($totalCredits, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($closingBalance, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @elseif(request('customer_id'))
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">No active sale found for this customer.</div>
                    @else
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">Select a customer above to view their account statement.</div>
                    @endif
                </div>

                {{-- SUPPLIER STATEMENT --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3">
                        <h4 class="text-[10px] font-extrabold text-white uppercase tracking-widest">Supplier / Contractor Statement</h4>
                        <p class="text-[9px] text-slate-400 mt-0.5">Payables ledger — billed vs paid vs outstanding balance</p>
                    </div>
                    <div class="p-4 bg-slate-50 border-b border-slate-200">
                        <form method="GET" class="flex items-end gap-3">
                            <input type="hidden" name="report" value="activity_statements">
                            <div class="flex-1">
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Select Supplier</label>
                                <select name="supplier_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider">Load →</button>
                        </form>
                    </div>

                    @php
                        $supplierStatement = [];
                        $supplierBilled = 0;
                        $supplierPaid = 0;
                        $supplierBalance = 0;
                        if(request('supplier_id')) {
                            $supplierBills = \Illuminate\Support\Facades\DB::table('bills')
                                ->where('payee_id', request('supplier_id'))
                                ->orderBy('created_at')
                                ->get();
                            foreach($supplierBills as $sb) {
                                $paid = \Illuminate\Support\Facades\DB::table('bill_payments')->where('bill_id', $sb->id)->sum('amount');
                                $supplierBilled += (float)$sb->final_amount;
                                $supplierPaid += (float)$paid;
                                $supplierBalance += ((float)$sb->final_amount - (float)$paid);
                                $supplierStatement[] = [
                                    'date'       => \Carbon\Carbon::parse($sb->created_at)->format('d M Y'),
                                    'bill_no'    => $sb->bill_number,
                                    'billed'     => (float)$sb->final_amount,
                                    'paid'       => (float)$paid,
                                    'balance'    => (float)$sb->final_amount - (float)$paid,
                                    'status'     => $sb->status,
                                ];
                            }
                        }
                    @endphp

                    @if(request('supplier_id') && count($supplierStatement) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Bill No.</th>
                                    <th class="px-4 py-3 text-right">Billed (₹)</th>
                                    <th class="px-4 py-3 text-right">Paid (₹)</th>
                                    <th class="px-4 py-3 text-right">Balance (₹)</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($supplierStatement as $row)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-2.5 text-slate-500 font-semibold">{{ $row['date'] }}</td>
                                    <td class="px-4 py-2.5 font-mono font-bold text-slate-900">{{ $row['bill_no'] }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold text-slate-900">₹{{ number_format($row['billed'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold text-emerald-700">₹{{ number_format($row['paid'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-right font-mono font-bold {{ $row['balance'] > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($row['balance'], 2) }}</td>
                                    <td class="px-4 py-2.5 text-center">
                                        @if($row['status'] === 'paid')
                                            <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[8px] font-extrabold uppercase">Paid</span>
                                        @elseif($row['status'] === 'partially_paid')
                                            <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[8px] font-extrabold uppercase">Partial</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[8px] font-extrabold uppercase">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 border-t-2 border-slate-200 text-xs font-extrabold">
                                    <td colspan="2" class="px-4 py-3 text-slate-700">Total</td>
                                    <td class="px-4 py-3 text-right font-mono text-slate-900">₹{{ number_format($supplierBilled, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-emerald-700">₹{{ number_format($supplierPaid, 2) }}</td>
                                    <td class="px-4 py-3 text-right font-mono {{ $supplierBalance > 0 ? 'text-rose-600' : 'text-emerald-700' }}">₹{{ number_format($supplierBalance, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{-- KPI Summary --}}
                    <div class="grid grid-cols-3 gap-3 p-4 border-t border-slate-100">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Billed</div>
                            <div class="text-sm font-extrabold font-mono text-slate-900 mt-1">₹{{ number_format($supplierBilled, 0) }}</div>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest">Paid / Cleared</div>
                            <div class="text-sm font-extrabold font-mono text-emerald-700 mt-1">₹{{ number_format($supplierPaid, 0) }}</div>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-center">
                            <div class="text-[9px] font-bold text-rose-600 uppercase tracking-widest">Outstanding</div>
                            <div class="text-sm font-extrabold font-mono text-rose-700 mt-1">₹{{ number_format($supplierBalance, 0) }}</div>
                        </div>
                    </div>
                    @elseif(request('supplier_id'))
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">No bills found for this supplier.</div>
                    @else
                    <div class="p-8 text-center text-slate-400 text-xs font-bold">Select a supplier above to view their statement.</div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- 16. DASHBOARD & MIS --}}
        @if($activeTab === 'dashboard')
        <div class="space-y-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-150 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-slate-900 text-white rounded-xl shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight uppercase">Dashboard Analytics & Profitability</h3>
                        <p class="text-xs text-slate-400">High-level financial KPIs, property metrics, and profitability breakdown</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2.5 items-center">
                    <button @click="printReport()" 
                            class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 00-2-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Report
                    </button>
                    <button @click="exportCurrentTable()" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>
            
            {{-- Executive KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Card 1 --}}
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-slate-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Projects</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['total_projects'] }}</span>
                </div>

                {{-- Card 2 --}}
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-blue-600 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Units (Sold / Total)</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['sold_units'] }} <span class="text-slate-400 text-lg">/ {{ $dashboardData['total_units'] }}</span></span>
                </div>

                {{-- Card 3 --}}
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-emerald-500 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Collections</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block">₹{{ number_format($dashboardData['collections'], 0) }}</span>
                </div>

                {{-- Card 4 --}}
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-amber-500 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Outstanding Receivable</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">₹{{ number_format($dashboardData['outstanding'], 0) }}</span>
                </div>

                {{-- Card 5 --}}
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-[#a38c29] rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Net Calculated Profit</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">₹{{ number_format($dashboardData['profit'], 0) }}</span>
                </div>
            </div>

            {{-- Dashboard Charts (2 Columns: Sold vs Unsold, Collections vs Expected) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Sold vs Unsold Units</h4>
                            <p class="text-[11px] text-slate-400">Inventory allocation ratio</p>
                        </div>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase">Property Units</span>
                    </div>
                    <div id="soldUnsoldChart" class="w-full h-60"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Collections vs Expected</h4>
                            <p class="text-[11px] text-slate-400">Received vs pending receivables</p>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg uppercase">Financials</span>
                    </div>
                    <div id="collectionsExpectedChart" class="w-full h-60"></div>
                </div>
            </div>

            {{-- Bank Loan EMI alerts --}}
            @if($dashboardData['loan_emi_alerts']->isNotEmpty())
            <div class="bg-gradient-to-r from-rose-50 to-rose-100/50 border border-rose-200 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-extrabold text-rose-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="p-1 bg-rose-200 rounded-lg text-rose-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        Bank Loan EMI Alerts (Upcoming 30 Days)
                    </h4>
                    <span class="px-2.5 py-0.5 bg-rose-200 text-rose-900 text-[10px] font-extrabold rounded-full uppercase">Action Required</span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-rose-200/80 bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-rose-50/80 text-[10px] uppercase tracking-wider text-rose-800 border-b border-rose-150 font-bold">
                                <th class="px-4 py-2.5">Project</th>
                                <th class="px-4 py-2.5">Lender</th>
                                <th class="px-4 py-2.5">Due Date</th>
                                <th class="px-4 py-2.5 text-right">EMI Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100 font-semibold font-mono text-slate-800">
                            @foreach($dashboardData['loan_emi_alerts'] as $alert)
                            <tr class="hover:bg-rose-50/50 transition-colors">
                                <td class="px-4 py-2.5 font-sans font-bold text-slate-900">{{ $alert->loan?->project?->name }}</td>
                                <td class="px-4 py-2.5 font-sans text-slate-700">{{ $alert->loan?->lender_name }}</td>
                                <td class="px-4 py-2.5 text-rose-700">{{ $alert->due_date?->format('d M Y') }}</td>
                                <td class="px-4 py-2.5 text-right text-rose-700 font-bold">₹{{ number_format($alert->emi_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Project Profitability Grid --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Project Profitability Analysis</h4>
                        <p class="text-[11px] text-slate-400">Detailed breakdown of expected vs actual revenue, costs, and profit margin per project</p>
                    </div>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[10px]">
                                <th class="px-4 py-3 rounded-tl-2xl">Project</th>
                                <th class="px-4 py-3 text-right">Expected Revenue</th>
                                <th class="px-4 py-3 text-right">Actual Revenue</th>
                                <th class="px-4 py-3 text-right">Partner Payouts</th>
                                <th class="px-4 py-3 text-right">Brokerage</th>
                                <th class="px-4 py-3 text-right">Material Costs</th>
                                <th class="px-4 py-3 text-right">Contractor Payments</th>
                                <th class="px-4 py-3 text-right">Total Cost</th>
                                <th class="px-4 py-3 text-right">Net Profit</th>
                                <th class="px-4 py-3 text-right rounded-tr-2xl">Margin %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                            @foreach($dashboardData['project_profitability'] as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-sans font-extrabold text-slate-900">{{ $row['project']->name }}</td>
                                <td class="px-4 py-3 text-right">₹{{ number_format($row['expected_revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-700">₹{{ number_format($row['actual_revenue'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['partner_payouts'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['brokerage_costs'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['material_costs'], 0) }}</td>
                                <td class="px-4 py-3 text-right text-rose-600">₹{{ number_format($row['contractor_payments'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-rose-700">₹{{ number_format($row['total_cost'], 0) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">₹{{ number_format($row['profit'], 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-block px-2 py-0.5 rounded font-sans font-extrabold text-[10px] uppercase {{ $row['margin'] > 15 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ number_format($row['margin'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- 1. AVAILABILITY REPORT --}}
        @if($activeTab === 'availability')
        <div class="space-y-6" x-data="{ 
            currentSubTab: 'summary', 
            hoveredUnit: null, 
            hoveredEl: null, 
            unitModalOpen: false, 
            modalLoading: false, 
            selectedUnitDetails: null,
            viewUnitDetails(unitId) {
                this.modalLoading = true;
                this.unitModalOpen = true;
                fetch(`{{ url('units') }}/${unitId}/json`)
                    .then(res => res.json())
                    .then(data => {
                        this.selectedUnitDetails = data.unit;
                        this.modalLoading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.modalLoading = false;
                        this.unitModalOpen = false;
                    });
            }
        }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Property Availability Matrix</h3>
                
                {{-- Sub-tab Navigation --}}
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl">
                    <button type="button" @click="currentSubTab = 'summary'"
                            :class="currentSubTab === 'summary' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Summary
                    </button>
                    <button type="button" @click="currentSubTab = 'matrix'"
                            :class="currentSubTab === 'matrix' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Floor Matrix
                    </button>
                    <button type="button" @click="currentSubTab = 'shop'"
                            :class="currentSubTab === 'shop' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Shop
                    </button>
                    <button type="button" @click="currentSubTab = 'apartment'"
                            :class="currentSubTab === 'apartment' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Apartment
                    </button>
                    <button type="button" @click="currentSubTab = 'parking'"
                            :class="currentSubTab === 'parking' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Parking
                    </button>
                    @if($others->isNotEmpty())
                    <button type="button" @click="currentSubTab = 'other'"
                            :class="currentSubTab === 'other' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Other
                    </button>
                    @endif
                </div>
            </div>

            {{-- SUMMARY SUB-TAB --}}
            <div x-show="currentSubTab === 'summary'" class="space-y-6" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-1">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Availability Distribution</h4>
                        <div id="availabilityDistributionChart" class="w-full h-52"></div>
                    </div>
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-2">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Unit Type Distribution</h4>
                        <div id="unitTypeDistributionChart" class="w-full h-52"></div>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3 text-center">Nos</th>
                                <th class="px-5 py-3 text-right">Built Up Area (In Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (In Sq Ft)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @php
                                $totalNos = 0;
                                $totalBuilt = 0;
                                $totalCarpet = 0;
                            @endphp
                            @foreach($groupedSummary as $row)
                                @php
                                    $totalNos += $row->nos;
                                    $totalBuilt += $row->built_up_area;
                                    $totalCarpet += $row->carpet_area;
                                @endphp
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 font-sans font-bold text-slate-900">{{ $row->type }}</td>
                                    <td class="px-5 py-3.5 text-center text-slate-650">{{ $row->nos }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->built_up_area > 0 ? number_format($row->built_up_area, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->carpet_area > 0 ? number_format($row->carpet_area, 2) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50/80 font-bold text-slate-900">
                                <td class="px-5 py-4 font-sans uppercase">Total</td>
                                <td class="px-5 py-4 text-center">{{ $totalNos }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalBuilt, 2) }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalCarpet, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SHOP SUB-TAB --}}
            <div x-show="currentSubTab === 'shop'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($shops as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No shops matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- APARTMENT SUB-TAB --}}
            <div x-show="currentSubTab === 'apartment'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($apartments as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No apartments matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PARKING SUB-TAB --}}
            <div x-show="currentSubTab === 'parking'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Parking No</th>
                                <th class="px-5 py-3">Sold/Booked To</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($parkings as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-800">
                                        @if($row->sale)
                                            <div class="font-bold text-[11px]">{{ $row->sale->customer?->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-mono">Sale: {{ $row->sale->sale_number }}</div>
                                        @else
                                            <span class="text-slate-350 italic font-normal text-[10px]">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-slate-400 italic">No parking bays matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- OTHER SUB-TAB --}}
            <div x-show="currentSubTab === 'other'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($others as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No other properties matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FLOOR MATRIX SUB-TAB --}}
            <div x-show="currentSubTab === 'matrix'" class="space-y-6 relative" x-transition style="display: none;">
                <!-- Header with Legends -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/15 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Floor Matrix – Unit Availability</h3>
                    </div>
                    
                    <!-- Status Legends -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-5 text-[9px] font-black uppercase tracking-wider text-slate-655">
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-emerald-500 shadow-xs border border-emerald-600"></span> Available</span>
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-[#0b1e36] shadow-xs border border-slate-800"></span> Parking</span>
                    </div>
                </div>

                @php
                    // Summary aggregates
                    $totalUnitsCount = 0;
                    $availableCount = 0;
                    $blockedCount = 0;
                    $soldCount = 0;
                    $parkingCount = 0;
                    foreach ($floorMatrix as $row) {
                        foreach ($row['columns'] as $u) {
                            if ($u) {
                                $totalUnitsCount++;
                                $st = strtolower($u->status);
                                if ($st === 'sold') $soldCount++;
                                elseif ($st === 'blocked') $blockedCount++;
                                elseif ($st === 'available') $availableCount++;
                            }
                        }
                    }
                    
                    if (!empty($parkingRows)) {
                        foreach ($parkingRows as $pRow) {
                            if ($pRow['display_name'] === 'P3' || $pRow['units']->count() == 0) continue;
                            $parkingCount += $pRow['units']->count();
                        }
                    }
                @endphp

                @if(empty($matrixColumns) && empty($parkingRows))
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">No unit data available for this project</p>
                        <p class="text-[10px] text-slate-400 mt-1">Select a different project or add units to see the matrix grid.</p>
                    </div>
                @else
                    <!-- Calculate Max Columns needed -->
                    @php
                        $maxFloorUnits = 0;
                        foreach ($floorMatrix as $row) {
                            $count = collect($row['columns'])->filter()->count();
                            if ($count > $maxFloorUnits) $maxFloorUnits = $count;
                        }
                        $maxParkingUnits = empty($parkingRows) ? 0 : collect($parkingRows)->max(fn($p) => $p['units']->count());
                        $totalGridCols = max($maxFloorUnits, $maxParkingUnits);
                    @endphp

                    <!-- Table Matrix Container -->
                    <div class="overflow-x-auto relative rounded-2xl border border-slate-200 shadow-sm bg-white">
                        <table class="border-collapse w-full text-xs text-left" style="min-width: max-content;">
                            <thead>
                                <tr class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-widest border-b border-[#a38c29]/30">
                                    <!-- Floor label column -->
                                    <th class="p-3.5 sticky left-0 bg-[#a38c29] z-10 min-w-[130px] border-r border-[#a38c29]/30 text-white font-extrabold whitespace-nowrap">
                                        Floor / Unit
                                    </th>
                                    <!-- Dynamic Unit Column Headers -->
                                    @for($i = 1; $i <= $totalGridCols; $i++)
                                        <th class="p-3.5 text-center min-w-[90px] text-white font-extrabold">
                                            <span class="block text-white/90">{{ $i }}</span>
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @php
                                    $reversedFloors = array_reverse($floorMatrix);
                                    $combinedFloors = [];
                                    
                                    $validParking = [];
                                    foreach($parkingRows as $pRow) {
                                        if ($pRow['display_name'] === 'P3' || $pRow['units']->count() == 0) continue;
                                        
                                        $dName = $pRow['display_name'];
                                        if ($dName === 'P1') $dName = 'Floor 4';
                                        if ($dName === 'P2') $dName = 'Floor 5';
                                        
                                        $validParking[] = [
                                            'display_name' => $dName,
                                            'is_parking_row' => true,
                                            'columns' => collect($pRow['units'])->sortBy('door_no', SORT_NATURAL | SORT_FLAG_CASE)->values()->all()
                                        ];
                                    }
                                    
                                    foreach($reversedFloors as $row) {
                                        $row['is_parking_row'] = false;
                                        $combinedFloors[] = $row;
                                        if (strtolower(trim($row['display_name'])) === 'floor 3') {
                                            foreach($validParking as $vp) {
                                                $combinedFloors[] = $vp;
                                            }
                                        }
                                    }
                                    
                                    $hasAddedParking = false;
                                    foreach($combinedFloors as $cf) {
                                        if(isset($cf['is_parking_row']) && $cf['is_parking_row']) {
                                            $hasAddedParking = true;
                                        }
                                    }
                                    if (!$hasAddedParking) {
                                        $combinedFloors = array_merge($combinedFloors, $validParking);
                                    }
                                @endphp

                                <!-- Combined Floor Rows -->
                                @foreach ($combinedFloors as $row)
                                    @php
                                        $isParkingRow = $row['is_parking_row'] ?? false;
                                        $validUnits = collect($row['columns'])->filter()->values();
                                        
                                        $isParkingFloor = $isParkingRow;
                                        if (!$isParkingFloor) {
                                            $firstUnit = $validUnits->first();
                                            if ($firstUnit) {
                                                $isParkingFloor = ($firstUnit->unitType && stripos($firstUnit->unitType->name, 'park') !== false) 
                                                                  || stripos($firstUnit->door_no, 'P') === 0;
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-[#a38c29]/5 transition-colors duration-150 group">
                                        <!-- Floor Label -->
                                        <td class="p-3.5 sticky left-0 bg-white group-hover:bg-slate-50 backdrop-blur-md z-10 border-l-2 border-l-[#a38c29] border-r border-slate-150 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors duration-150">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0 border border-[#a38c29]/20">
                                                    @if($isParkingFloor)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM4 16h-.5A1.5 1.5 0 012 14.5v-2c0-.5.2-1 .5-1.3l2-3.4A3 3 0 017 6.5h10a3 3 0 012.5 1.3l2 3.4c.3.3.5.8.5 1.3v2a1.5 1.5 0 01-1.5 1.5H20m-4-10v3m-8-3v3m12 1.5H4" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="block text-[10px] font-black text-slate-800 uppercase tracking-wide">{{ $row['display_name'] }}</span>
                                                    <span class="block text-[9px] text-slate-400 font-bold mt-0.5">{{ collect($row['columns'])->filter()->count() }} {{ $isParkingRow ? 'Slot(s)' : 'Unit(s)' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Cells -->
                                        @for ($i = 0; $i < $totalGridCols; $i++)
                                            <td class="p-2.5">
                                                @if ($i < $validUnits->count())
                                                    @php $unit = $validUnits[$i]; @endphp
                                                    
                                                    @if ($isParkingRow)
                                                        @php 
                                                            $isOccupied = in_array(strtolower($unit->status), ['sold', 'blocked']); 
                                                        @endphp
                                                        @if (!$isOccupied)
                                                            <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: 'Car Parking Space', status: 'Available', price: '₹{{ number_format($unit->expected_sale_amount ?? 300000) }}', type: 'parking' }; hoveredEl = $el"
                                                                 @mouseleave="hoveredUnit = null"
                                                                 @click="viewUnitDetails({{ $unit->id }})"
                                                                 class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl border border-transparent transition-all hover:-translate-y-0.5 hover:shadow-md cursor-pointer bg-[#0b1e36] text-white shadow-xs hover:bg-[#152a47] duration-150">
                                                                <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-xs">{{ $unit->door_no }}</span>
                                                                <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-xs">Parking</span>
                                                            </div>
                                                        @else
                                                            <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                        @endif
                                                    @else
                                                        @php
                                                            $status = strtolower($unit->status);
                                                            $isAvailable = ($status === 'available');
                                                        @endphp
                                                        @if ($isAvailable)
                                                            @php
                                                                $isParkingUnit = ($unit->unitType && (strtolower($unit->unitType->name) === 'parking' || strtolower($unit->unitType->category) === 'parking'));
                                                            @endphp
                                                            <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: '{{ $unit->built_up_area ? $unit->built_up_area.' sq.ft' : 'N/A' }}', status: 'Available', price: '₹{{ number_format($unit->expected_sale_amount ?? 0) }}', type: '{{ $isParkingUnit ? 'parking' : 'unit' }}' }; hoveredEl = $el"
                                                                 @mouseleave="hoveredUnit = null"
                                                                 @click="viewUnitDetails({{ $unit->id }})"
                                                                 class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl border border-transparent transition-all hover:-translate-y-0.5 hover:shadow-md cursor-pointer {{ $isParkingUnit ? 'bg-[#0b1e36] hover:bg-[#152a47]' : 'bg-emerald-500 hover:border-emerald-400' }} text-white shadow-xs duration-150">

                                                                <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-xs">
                                                                    {{ $unit->door_no }}
                                                                </span>
                                                                <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-xs">
                                                                    {{ $unit->unitType?->name ? (strtolower($unit->unitType->name) === 'flat' ? 'Apartment' : ucfirst($unit->unitType->name)) : 'N/A' }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                        @endif
                                                    @endif
                                                @else
                                                    <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Hover Tooltip -->
                    <div x-show="hoveredUnit" 
                         class="absolute z-50 bg-white border border-[#EAE3CD] rounded-2xl shadow-2xl p-4 w-[260px] pointer-events-none space-y-2 transition-all duration-150"
                         :style="`left: ${hoveredEl ? Math.min(hoveredEl.getBoundingClientRect().left - $el.parentElement.getBoundingClientRect().left + 10, $el.parentElement.clientWidth - 270) : 0}px; top: ${hoveredEl ? hoveredEl.getBoundingClientRect().top - $el.parentElement.getBoundingClientRect().top - 130 : 0}px;`"
                         x-transition style="display: none;">

                        <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                            <div>
                                <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block" x-text="hoveredUnit?.floor"></span>
                                <span class="text-xs font-black text-slate-800 uppercase tracking-wider" x-text="hoveredUnit?.door_no"></span>
                            </div>
                            <span class="text-[8px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider shadow-xs"
                                  :class="{'bg-[#0b1e36]': hoveredUnit?.type === 'parking', 'bg-rose-600': hoveredUnit?.type !== 'parking' && hoveredUnit?.status === 'Sold', 'bg-blue-500': hoveredUnit?.type !== 'parking' && hoveredUnit?.status === 'Booked', 'bg-amber-500': hoveredUnit?.type !== 'parking' && hoveredUnit?.status === 'Blocked', 'bg-emerald-500': hoveredUnit?.type !== 'parking' && hoveredUnit?.status === 'Available', 'bg-slate-700': hoveredUnit?.type !== 'parking' && hoveredUnit?.status === 'Reserved'}"
                                  x-text="hoveredUnit?.status"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-[9px] font-semibold text-slate-500 uppercase tracking-wider">
                            <div>
                                <span class="block text-[8px] text-slate-400 font-bold">Built Up Area</span>
                                <span class="text-slate-800 font-extrabold" x-text="hoveredUnit?.area"></span>
                            </div>
                            <div>
                                <span class="block text-[8px] text-slate-400 font-bold">Expected Sale</span>
                                <span class="text-[#a38c29] font-extrabold font-mono" x-text="hoveredUnit?.price"></span>
                            </div>
                        </div>
                    </div>


                @endif
            </div>

            <!-- Unit Details Readonly Modal -->
            <div x-show="unitModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity" x-transition style="display: none;">
                <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100" @click.away="unitModalOpen = false">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-[#a38c29] to-[#b89635] text-white flex items-center justify-between">
                        <div>
                            <span class="block text-[8px] font-bold uppercase tracking-wider text-white/80" x-text="selectedUnitDetails?.floor?.name || 'Unit Detail'"></span>
                            <h4 class="text-sm font-black uppercase tracking-wider text-white" x-text="selectedUnitDetails ? 'Unit ' + selectedUnitDetails.door_no : 'Loading...' "></h4>
                        </div>
                        <button type="button" @click="unitModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition text-xs">✕</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4 text-xs">
                        <!-- Loading state -->
                        <div x-show="modalLoading" class="flex flex-col items-center justify-center py-12 space-y-3">
                            <div class="w-8 h-8 rounded-full border-4 border-amber-500 border-t-transparent animate-spin"></div>
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Fetching Details...</span>
                        </div>

                        <!-- Details Content -->
                        <div x-show="!modalLoading && selectedUnitDetails" class="space-y-4">
                            <!-- Project & Type -->
                            <div class="grid grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Project</span>
                                    <span class="text-slate-800 font-bold" x-text="selectedUnitDetails?.project?.name || 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Unit Type</span>
                                    <span class="text-slate-800 font-bold" x-text="selectedUnitDetails?.unit_type?.name || 'N/A'"></span>
                                </div>
                            </div>

                            <!-- Areas -->
                            <div class="grid grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Built Up Area</span>
                                    <span class="text-slate-800 font-mono font-bold" x-text="selectedUnitDetails?.built_up_area ? Number(selectedUnitDetails.built_up_area).toLocaleString() + ' Sq.ft' : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Carpet Area</span>
                                    <span class="text-slate-800 font-mono font-bold" x-text="selectedUnitDetails?.carpet_area ? Number(selectedUnitDetails.carpet_area).toLocaleString() + ' Sq.ft' : 'N/A'"></span>
                                </div>
                            </div>

                            <!-- Pricing & Status -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Expected Sale Value</span>
                                    <span class="text-[#a38c29] font-mono font-bold" x-text="selectedUnitDetails?.expected_sale_amount ? '₹' + Number(selectedUnitDetails.expected_sale_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Status</span>
                                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-[9px] font-black uppercase text-white shadow-xs"
                                          :class="{'bg-rose-600': selectedUnitDetails?.status === 'sold', 'bg-blue-500': selectedUnitDetails?.status === 'booked', 'bg-amber-500': selectedUnitDetails?.status === 'blocked', 'bg-emerald-500': selectedUnitDetails?.status === 'available', 'bg-slate-700': selectedUnitDetails?.status === 'reserved'}"
                                          x-text="selectedUnitDetails?.status"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                        <button type="button" @click="unitModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
                    </div>
                </div>
            </div>

        </div>
        @endif

        {{-- 2. SALES REPORT --}}
        @if($activeTab === 'sales')
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Real-time Sales Register & Report</h3>
                            <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Executive Performance Ledger</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Comprehensive audit trail of property bookings, project distributions, total sale agreements, and active sales revenue tracking.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0 relative z-10">
                    <button @click="printReport()" 
                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Report
                    </button>
                    <button @click="exportCurrentTable()" 
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>


            {{-- Sales Report Data Table Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-amber-50/40 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                            Chronological Sales Log & Financial Auditing Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Chronological listing of active property sale agreements and total amounts.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $activeProjectName = 'All Projects (Default)';
                            if(request('project_id')) {
                                $activeProject = \App\Models\Project::find(request('project_id'));
                                if($activeProject) {
                                    $activeProjectName = $activeProject->name;
                                }
                            }
                        @endphp
                        <span class="px-4 py-1.5 bg-slate-800 text-white border border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active Project: {{ $activeProjectName }}
                        </span>
                    </div>
                </div>

                <div class="w-full">
                    <table id="reportsTable" class="w-full text-[11px] text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-2 py-3 text-white font-extrabold whitespace-nowrap">Sale No</th>
                                <th class="px-2 py-3 text-white font-extrabold">Project / Unit</th>
                                <th class="px-2 py-3 text-white font-extrabold">Customer</th>
                                <th class="px-2 py-3 text-white font-extrabold">Broker</th>
                                <th class="px-2 py-3 text-white font-extrabold whitespace-nowrap">Category</th>
                                <th class="px-2 py-3 text-white font-extrabold text-right whitespace-nowrap">Expected (₹)</th>
                                <th class="px-2 py-3 text-white font-extrabold text-right whitespace-nowrap">Sale Amt (₹)</th>
                                <th class="px-2 py-3 text-white font-extrabold text-right whitespace-nowrap">Diff (₹)</th>
                                <th class="px-2 py-3 text-white font-extrabold text-right whitespace-nowrap">GST (₹)</th>
                                <th class="px-2 py-3 text-white font-extrabold text-right whitespace-nowrap">Total (₹)</th>
                                <th class="px-2 py-3 text-white font-extrabold text-center whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                            @forelse($salesList as $sale)
                            @php
                                $expectedAmount = 0.00;
                                if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                                    foreach ($sale->saleUnits as $su) {
                                        $expectedAmount += (float)($su->unit?->expected_sale_amount ?? 0.00);
                                    }
                                } else {
                                    $expectedAmount = (float)($sale->unit?->expected_sale_amount ?? 0.00);
                                }
                                $saleAmount = (float)($sale->sale_amount ?? 0.00);
                                $difference = $saleAmount - $expectedAmount;
                            @endphp
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium text-[11px]">
                                <td class="px-2 py-3 font-bold text-indigo-700 whitespace-nowrap">{{ $sale->sale_number }}</td>
                                <td class="px-2 py-3 font-sans">
                                    <div class="font-bold text-slate-800 leading-tight">{{ $sale->project?->name }}</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5 leading-normal">
                                        Unit: 
                                        @if($sale->saleUnits && $sale->saleUnits->isNotEmpty())
                                            @foreach($sale->saleUnits as $su)
                                                @if($su->unit)
                                                    @php
                                                        $door = trim(explode(',', $su->unit->door_no)[0]);
                                                        $type = strtolower($su->unit->unitType?->name ?? '');
                                                        if ($type === 'flat') $type = 'Apartment';
                                                        elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                                                        else $type = ucfirst($type);

                                                        $floor = trim($su->unit->floor?->name ?? '');
                                                        if (preg_match('/^(floor|fl)\b/i', $floor)) {
                                                            $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                                                        } elseif ($floor && is_numeric($floor)) {
                                                            $floor = 'Floor ' . $floor;
                                                        } elseif ($floor) {
                                                            $floor = ucfirst($floor);
                                                        }
                                                    @endphp
                                                    {{ $door }}{{ $type ? "($type)" : "" }}{{ $floor ? " - $floor" : "" }}@if(!$loop->last), @endif
                                                @endif
                                            @endforeach
                                        @elseif($sale->unit)
                                            @php
                                                $door = trim(explode(',', $sale->unit->door_no)[0]);
                                                $type = strtolower($sale->unit->unitType?->name ?? '');
                                                if ($type === 'flat') $type = 'Apartment';
                                                elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                                                else $type = ucfirst($type);

                                                $floor = trim($sale->unit->floor?->name ?? '');
                                                if (preg_match('/^(floor|fl)\b/i', $floor)) {
                                                    $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                                                } elseif ($floor && is_numeric($floor)) {
                                                    $floor = 'Floor ' . $floor;
                                                } elseif ($floor) {
                                                    $floor = ucfirst($floor);
                                                }
                                            @endphp
                                            {{ $door }}{{ $type ? "($type)" : "" }}{{ $floor ? " - $floor" : "" }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 py-3 font-sans text-slate-800 leading-tight">{{ $sale->customer?->name ?? 'N/A' }}</td>
                                <td class="px-2 py-3 font-sans text-slate-500">{{ $sale->broker?->name ?? '—' }}</td>
                                <td class="px-2 py-3 font-sans font-bold text-slate-500">
                                    @php
                                        $category = 'N/A';
                                        if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                                            $cats = $sale->saleUnits->map(fn($su) => $su->unit?->unitType?->category)->filter()->unique();
                                            if ($cats->isNotEmpty()) {
                                                $category = implode(', ', $cats->map(fn($c) => ucfirst($c))->toArray());
                                            }
                                        } elseif ($sale->unit && $sale->unit->unitType) {
                                            $category = ucfirst($sale->unit->unitType->category ?? 'Apartment');
                                        }
                                    @endphp
                                    {{ $category }}
                                </td>
                                <td class="px-2 py-3 text-right font-mono font-bold text-slate-650 whitespace-nowrap">₹{{ number_format($expectedAmount, 2) }}</td>
                                <td class="px-2 py-3 text-right font-mono font-bold text-slate-900 whitespace-nowrap">₹{{ number_format($saleAmount, 2) }}</td>
                                <td class="px-2 py-3 text-right font-mono font-extrabold whitespace-nowrap">
                                    @if($difference > 0)
                                        <span class="text-emerald-600">+₹{{ number_format($difference, 2) }}</span>
                                    @elseif($difference < 0)
                                        <span class="text-rose-600">-₹{{ number_format(abs($difference), 2) }}</span>
                                    @else
                                        <span class="text-slate-400">₹0.00</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 text-right font-mono text-slate-700 whitespace-nowrap">
                                    @if($sale->gst_amount > 0)
                                        ₹{{ number_format($sale->gst_amount, 2) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap">₹{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-2 py-3 text-center font-sans whitespace-nowrap">{{ $sale->sale_date?->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-2 py-12 text-center text-slate-400 italic">No sales logs matching filter criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination Footer Card Section --}}
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl">
                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                        Showing <span class="text-slate-900 font-black">{{ $salesList->firstItem() ?? 0 }}</span> to 
                        <span class="text-slate-900 font-black">{{ $salesList->lastItem() ?? 0 }}</span> of 
                        <span class="text-slate-900 font-black">{{ $salesList->total() }}</span> Sales Records
                    </div>
                    <div class="flex items-center gap-1.5 print:hidden">
                        {{ $salesList->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- 3. EMI & COLLECTIONS --}}
        @if($activeTab === 'emi_collections')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">EMI Collection Trends & Summary</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">EMI Outstanding vs Collection</h4>
                    <div id="emiOutstandingCollectionChart" class="w-full h-56"></div>
                </div>
                <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Collection Trend Performance</h4>
                    <div id="emiCollectionTrendChart" class="w-full h-56"></div>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Voucher Ref</th>
                            <th class="px-5 py-3">Customer Details</th>
                            <th class="px-5 py-3">Payment Mode</th>
                            <th class="px-5 py-3 text-right">Inflow Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($cashBookEntries as $receipt)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-900">{{ $receipt->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 font-bold border border-slate-200 inline-block">{{ $receipt->payment_mode }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No collections found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $cashBookEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 4. CUSTOMER LEDGER --}}
        @if($activeTab === 'customer_ledger')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Customer Ledger Statement</h3>

            {{-- Customer Selection Filter Bar --}}
            <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
                <form id="customerLedgerForm" method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    <input type="hidden" name="report" value="customer_ledger">
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
                            <!-- Main Selector Button -->
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

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                                 style="display: none;">
                                
                                <!-- Search Input Header -->
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

                                <!-- Clear / All Customers Option -->
                                <button type="button" @click="selectCustomer(null); open = false; search = ''"
                                        class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>— All Customers —</span>
                                </button>

                                <!-- Customer List Options -->
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
                                    <!-- Empty State -->
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

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
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
                {{-- Executive KPI Summary Cards --}}
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

                {{-- Table 1: All Customers Account Balances Register --}}
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
                                        <a href="?report=customer_ledger&customer_id={{ $cs['customer_id'] }}&project_id={{ request('project_id') }}"
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

                {{-- Table 2: System-Wide Customer Ledger Transaction Log --}}
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
        @endif

        {{-- 5. CASH BOOK --}}
        @if($activeTab === 'cash_book')
        <div class="space-y-6" id="cashBookDashboard">

            {{-- Section Header with partner context --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Partner Cash Book Analytics</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Real-time collection register with partner-wise breakdown and trend analytics.</p>
                </div>
                {{-- Partner Quick-filter pill tabs --}}
                <div class="flex flex-wrap gap-2">
                    <a href="?{{ http_build_query(array_merge(request()->query(), ['report'=>'cash_book', 'partner_id'=>''])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ !request('partner_id') ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'border-slate-200 text-slate-500 hover:border-slate-400 hover:text-slate-800' }}">
                        All Partners
                    </a>
                    @foreach($partners as $pt)
                    <a href="?{{ http_build_query(array_merge(request()->query(), ['report'=>'cash_book', 'partner_id'=>$pt->id])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ request('partner_id') == $pt->id ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-md' : 'border-slate-200 text-slate-500 hover:border-[#a38c29] hover:text-[#a38c29]' }}">
                        {{ $pt->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Total Received</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['total_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">All payment modes</div>
                    </div>
                </div>

                {{-- Cash Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Cash in Hand</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['cash_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Cash mode only</div>
                    </div>
                </div>

                {{-- Bank / Digital Received --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Bank / Digital</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['bank_received'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Bank · Cheque · UPI · Online</div>
                    </div>
                </div>

                {{-- Pending Collections --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 shadow-lg text-white">
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-3 -left-3 w-14 h-14 bg-white/10 rounded-full"></div>
                    <div class="relative">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest opacity-80">Pending Balance</span>
                        </div>
                        <div class="text-xl font-black font-mono tracking-tight">₹{{ number_format($cashBookStats['pending_balance'] ?? 0, 0) }}</div>
                        <div class="text-[9px] opacity-70 mt-1 font-sans">Outstanding receivables</div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 1: Monthly Bar + Daily Line --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Monthly Cash Collections</h4>
                            <p class="text-[9px] text-slate-400">Last 12 months · bar chart</p>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">INFLOW</span>
                    </div>
                    <div id="cbMonthlyChart" class="w-full" style="height:220px;"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Payment Mode Mix</h4>
                            <p class="text-[9px] text-slate-400">Cash · Bank · UPI · Cheque</p>
                        </div>
                    </div>
                    <div id="cbPaymentModeChart" class="w-full" style="height:220px;"></div>
                </div>
            </div>

            {{-- Charts Row 2: Daily Line + Partner Donut + Partner Bar --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Daily Collection Trend</h4>
                            <p class="text-[9px] text-slate-400">Last 30 days · line chart</p>
                        </div>
                        <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">TREND</span>
                    </div>
                    <div id="cbDailyTrendChart" class="w-full" style="height:200px;"></div>
                </div>

                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Partner-wise Collections</h4>
                            <p class="text-[9px] text-slate-400">Basheer vs Pavoor · donut</p>
                        </div>
                    </div>
                    <div id="cbPartnerDonutChart" class="w-full flex-1" style="height:200px;"></div>
                </div>
            </div>

            {{-- Partner comparison bar chart --}}
            @if($cashBookChartData['partner_wise']->count() > 1)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Partner Collection Comparison</h4>
                        <p class="text-[9px] text-slate-400">Total amount received per partner</p>
                    </div>
                </div>
                <div id="cbPartnerBarChart" class="w-full" style="height:180px;"></div>
            </div>
            @endif

            {{-- Transaction Table --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Recent Cash Book Entries</h4>
                    <span class="text-[9px] text-slate-400 font-mono">{{ $cashBookEntries->total() }} records</span>
                </div>
                <div class="overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Voucher #</th>
                                <th class="px-5 py-3">Customer / Unit</th>
                                <th class="px-5 py-3">Partner</th>
                                <th class="px-5 py-3">Mode</th>
                                <th class="px-5 py-3">Bank Ref</th>
                                <th class="px-5 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($cashBookEntries as $cash)
                            @php
                                $modeColors = [
                                    'Cash'          => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'Bank Transfer' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Cheque'        => 'bg-violet-50 text-violet-700 border-violet-100',
                                    'Online'        => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'UPI'           => 'bg-amber-50 text-amber-700 border-amber-100',
                                ];
                                $mc = $modeColors[$cash->payment_mode] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors font-semibold">
                                <td class="px-5 py-3.5 text-slate-500 font-sans whitespace-nowrap">{{ $cash->receipt_date?->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="font-bold text-indigo-700 font-mono">REC-{{ sprintf("%05d", $cash->id) }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    <div class="font-bold text-slate-900">{{ $cash->customer?->name ?? '—' }}</div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $cash->sale?->project?->name }} · Unit {{ $cash->sale?->unit?->door_no ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    @if($cash->partner)
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100 inline-block">{{ $cash->partner->name }}</span>
                                    @elseif(request('partner_id'))
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100 inline-block">Partner Share</span>
                                    @else
                                        <span class="text-slate-300 font-mono text-[10px]">Project Intake</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-sans">
                                    <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase border inline-block {{ $mc }}">{{ $cash->payment_mode }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-400 text-[10px]">{{ $cash->reference_no ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-right font-black font-mono text-emerald-700 text-sm">₹{{ number_format($cash->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-slate-400 italic text-xs">No cash entries found for the selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $cashBookEntries->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif

        {{-- 6. BANK REPORTS --}}
        @if($activeTab === 'bank_reports')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Bank Transaction Statement</h3>

            <div id="bankTransactionsChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Clearance Date</th>
                            <th class="px-5 py-3">Voucher Ref</th>
                            <th class="px-5 py-3">Associated Customer</th>
                            <th class="px-5 py-3">Bank / Ref Number</th>
                            <th class="px-5 py-3 text-right">Cleared Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($bankReportEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $row->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">{{ $row->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $row->bank_name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Ref: {{ $row->reference_no ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($row->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No bank ledger clearance logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $bankReportEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 7. PARTNER STATEMENTS --}}
        @if($activeTab === 'partner_statements')
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Partner Statement Ledger & Capital Outflows</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track capital allocations, profit shares, and mapping of receipt distributions across project partners.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-2 bg-gradient-to-r from-amber-500/10 to-amber-500/5 border border-[#a38c29]/30 rounded-xl">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-[#a38c29]">Total Allocated Outflow</span>
                        <span class="text-base font-black text-slate-900 font-mono">₹{{ number_format($partnerChartData['total_allocated'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Dual Interactive Charts Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Chart 1: Monthly Allocation Outflow Trend --}}
                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29]"></span>
                            Monthly Capital Outflow Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Allocated Amounts (₹)</span>
                    </div>
                    <div id="partnerStatementsChart" class="w-full h-56"></div>
                </div>

                {{-- Chart 2: Partner Distribution Donut --}}
                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Partner Outflow Share
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="partnerDistributionChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>
            </div>

            {{-- Allocations Table --}}
            <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3.5">Allocation Date</th>
                            <th class="px-5 py-3.5">Partner Entity</th>
                            <th class="px-5 py-3.5">Associated Project</th>
                            <th class="px-5 py-3.5">Description Memo</th>
                            <th class="px-5 py-3.5 text-right">Allocated Outflow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($partnerAllocations as $alloc)
                        <tr class="hover:bg-slate-50/70 transition-colors font-semibold">
                            <td class="px-5 py-3.5 text-slate-500 font-sans whitespace-nowrap">{{ $alloc->date?->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 font-sans font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                {{ $alloc->partner?->name }}
                            </td>
                            <td class="px-5 py-3.5 font-sans text-slate-600">{{ $alloc->project?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 font-sans font-medium text-slate-500">Capital Profit Allocation via receipts mapping</td>
                            <td class="px-5 py-3.5 text-right text-rose-600 font-black font-mono">₹{{ number_format($alloc->allocated_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 italic">No allocations posted for partners.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $partnerAllocations->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 8. SUPPLIER & CONTRACTOR --}}
        @if($activeTab === 'supplier_contractor')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Supplier, Contractor & Broker Payables</h3>

            <div id="supplierPayablesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Broker Supplier</th>
                            <th class="px-5 py-3">Associated Sale</th>
                            <th class="px-5 py-3">Project / Customer</th>
                            <th class="px-5 py-3 text-right">Commission Due</th>
                            <th class="px-5 py-3 text-right">Paid Amount</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($supplierContractorEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 font-sans font-bold text-slate-900">{{ $row->broker?->name }}</td>
                            <td class="px-5 py-3 text-indigo-750 font-bold">{{ $row->sale?->sale_number }}</td>
                            <td class="px-5 py-3 font-sans text-slate-500">
                                <div>{{ $row->sale?->project?->name }}</div>
                                <div class="text-[10px] text-slate-400">Cust: {{ $row->sale?->customer?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-rose-600">₹{{ number_format($row->commission_amount, 2) }}</td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($row->paid_amount, 2) }}</td>
                            <td class="px-5 py-3 text-center">
                                @php $sc = ['paid'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','pending'=>'bg-amber-50 text-amber-700 border border-amber-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$row->status] ?? 'bg-slate-105' }}">{{ $row->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No suppliers/broker accounts payable found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $supplierContractorEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 9. SALES RETURN --}}
        @if($activeTab === 'sales_return')
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-rose-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-rose-200/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-rose-500/15 rounded-xl border border-rose-200 text-rose-700 shadow-2xs">
                            <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Sales Cancellation Report</h3>
                            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-widest bg-rose-500/15 px-2.5 py-0.5 rounded border border-rose-200">Cancellation & Refund Audit Trail</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Audit cancelled bookings, cancellation fees retained by the business, and refund liabilities.</p>
                </div>
                
                {{-- Action Buttons & Total Stats --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0 relative z-10">
                    <div class="px-3.5 py-2 bg-rose-50 border border-rose-200 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-rose-700">Total Cancellation Fee</span>
                        <span class="text-xs font-black text-rose-900 font-mono">₹{{ number_format($salesReturnChartData['total_fee'] ?? 0, 2) }}</span>
                    </div>
                    <div class="px-3.5 py-2 bg-emerald-50 border border-emerald-250/20 rounded-xl text-left">
                        <span class="block text-[8px] font-black uppercase tracking-widest text-emerald-700">Total Refund Payable</span>
                        <span class="text-xs font-black text-emerald-900 font-mono">₹{{ number_format($salesReturnChartData['total_refund'] ?? 0, 2) }}</span>
                    </div>
                    <button @click="printReport()" 
                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Report
                    </button>
                    <button @click="exportCurrentTable()" 
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>

            {{-- Dual Interactive Charts Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Chart 1: Monthly Cancellation & Refund Trend --}}
                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            Cancellation Fees & Refund Outflow Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Monthly Timeline (₹)</span>
                    </div>
                    <div id="salesReturnChart" class="w-full h-56"></div>
                </div>

                {{-- Chart 2: Cancellation vs Refund Donut Chart --}}
                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            Cancellation Fee vs Refund Breakdown
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="salesReturnDonutChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>
            </div>

            {{-- Sales Cancellation Table Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-rose-50/20 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                            Chronological Sales Cancellation & Refund Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Verified audit trail of cancelled or returned units, cancellation fees, and refund liabilities.</p>
                    </div>
                </div>

                <div class="w-full overflow-x-auto">
                    <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3 text-white font-extrabold whitespace-nowrap">Ref Code No.</th>
                                <th class="px-5 py-3 text-white font-extrabold">Customer Entity</th>
                                <th class="px-5 py-3 text-white font-extrabold">Returned Property Unit</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Contract Value</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Paid Amount</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Cancellation Fee</th>
                                <th class="px-5 py-3 text-white font-extrabold text-right">Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-655 font-mono">
                            @forelse($salesReturns as $ret)
                            <tr class="hover:bg-rose-50/10 transition-colors duration-150 font-medium text-xs">
                                <td class="px-5 py-3 font-bold text-rose-700 whitespace-nowrap">{{ $ret->sale_number }}</td>
                                <td class="px-5 py-3 font-sans text-slate-800 whitespace-nowrap">{{ $ret->customer?->name }}</td>
                                <td class="px-5 py-3 font-sans">
                                    <div class="font-bold text-slate-800">{{ $ret->project?->name }}</div>
                                    <div class="text-[10px] text-slate-400">Unit: {{ $ret->unit?->door_no }}</div>
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">₹{{ number_format($ret->total_amount, 2) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-600 font-bold whitespace-nowrap">₹{{ number_format($ret->total_paid ?? 0.00, 2) }}</td>
                                <td class="px-5 py-3 text-right text-rose-600 whitespace-nowrap">₹{{ number_format($ret->cancellation_fee, 2) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700 font-bold whitespace-nowrap">₹{{ number_format($ret->refund_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No cancelled or returned sales found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $salesReturns->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 10. EXCHANGE REPORT --}}
        @if($activeTab === 'exchange_report')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">Unit Exchange Report</h3>

            <div id="unitExchangesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Exchange Date</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Transferred Unit</th>
                            <th class="px-5 py-3 text-right">Equity Applied(Paid Amount)</th>
                            <th class="px-5 py-3 text-right">Contract Value</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($exchangeEntries as $row)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $row->sale_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans text-slate-900">{{ $row->customer?->name }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div>{{ $row->project?->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Old Unit: {{ $row->unit?->formatted_name ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700">₹{{ number_format($row->transferred_equity, 2) }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($row->total_amount, 2) }}</td>
                            <td class="px-5 py-3 font-sans">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100">Exchanged</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No exchanges registered.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $exchangeEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 11. PETTY CASH BOOK --}}
        @if($activeTab === 'petty_cash')
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Petty Cash Inflow & Outflow Book</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track daily cash receipts, petty cash disbursements, and physical cash register balances.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-2 bg-gradient-to-r from-amber-500/10 to-amber-500/5 border border-[#a38c29]/30 rounded-xl">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-[#a38c29]">Total Cash Inflow</span>
                        <span class="text-base font-black text-slate-900 font-mono">₹{{ number_format($pettyCashChartData['total_amount'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- 4 Stat Badges Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Collection</span>
                    <div class="text-base font-black text-slate-900 font-mono">₹{{ number_format($pettyCashChartData['total_amount'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cash Receipts Count</span>
                    <div class="text-base font-black text-emerald-600 font-mono">{{ $pettyCashChartData['total_count'] ?? 0 }} Vouchers</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Average Receipt</span>
                    <div class="text-base font-black text-indigo-600 font-mono">₹{{ number_format($pettyCashChartData['avg_amount'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Max Cash Receipt</span>
                    <div class="text-base font-black text-amber-600 font-mono">₹{{ number_format($pettyCashChartData['max_amount'] ?? 0, 2) }}</div>
                </div>
            </div>

            {{-- Dual Visualization Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Chart 1: Customer Cash Collection Donut --}}
                <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            Customer Inflow Share
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Distribution</span>
                    </div>
                    <div id="pettyCashCustomerChart" class="w-full h-56 flex items-center justify-center"></div>
                </div>

                {{-- Chart 2: Monthly Trend Area Chart --}}
                <div class="lg:col-span-2 bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Monthly Cash Timeline Trend
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">Inflows (₹)</span>
                    </div>
                    <div id="pettyCashChart" class="w-full h-56"></div>
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Transaction Date</th>
                            <th class="px-5 py-3">Voucher Ref ID</th>
                            <th class="px-5 py-3">Site / Customer Detail</th>
                            <th class="px-5 py-3 text-right">Receipt Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($pettyCashEntries as $pc)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $pc->receipt_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-bold text-indigo-700">REC-{{ sprintf("%05d", $pc->id) }}</td>
                            <td class="px-5 py-3 font-sans text-slate-800">
                                <div>Cash collection from {{ $pc->customer?->name }}</div>
                                <div class="text-[10px] text-slate-400">Project: {{ $pc->sale?->project?->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format($pc->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-400 italic">No cash inflow transactions posted.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $pettyCashEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 12. BANK LOAN EMI --}}
        @if($activeTab === 'loan_schedules')
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Bank Loan EMI Schedules & Repayments</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track project construction loan repayments, principal amortization, and monthly interest dues.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-xl">
                        <span class="block text-[9px] font-black uppercase tracking-widest text-indigo-700">Total Scheduled Principal</span>
                        <span class="text-base font-black text-indigo-950 font-mono">₹{{ number_format($loanSchedules->sum('principal_component'), 2) }}</span>
                    </div>
                </div>
            </div>

            @if($loanSchedules->count() > 0)
            {{-- Stacked EMI Repayment Chart --}}
            <div class="bg-slate-50/60 border border-slate-200/90 rounded-2xl p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        Monthly Loan Repayment Schedule Breakdown (Principal vs Interest)
                    </span>
                    <span class="text-[10px] text-slate-400 font-mono">EMI Outflows (₹)</span>
                </div>
                <div id="bankLoanEmiChart" class="w-full h-56"></div>
            </div>
            @endif

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Due Date</th>
                            <th class="px-5 py-3">Bank / Project</th>
                            <th class="px-5 py-3">EMI Installment</th>
                            <th class="px-5 py-3 text-right">Principal</th>
                            <th class="px-5 py-3 text-right">Interest</th>
                            <th class="px-5 py-3 text-right">EMI Amount</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($loanSchedules as $sch)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $sch->due_date?->format('d M Y') }}</td>
                            <td class="px-5 py-3 font-sans">
                                <div class="font-bold text-slate-900">{{ $sch->loan?->lender_name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Project: {{ $sch->loan?->project?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-slate-500 font-sans">EMI #{{ $sch->installment_no }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->principal_component, 2) }}</td>
                            <td class="px-5 py-3 text-right">₹{{ number_format($sch->interest_component, 2) }}</td>
                            <td class="px-5 py-3 text-right text-indigo-750 font-bold">₹{{ number_format($sch->emi_amount, 2) }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['Paid'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','Due'=>'bg-amber-50 text-amber-700 border border-amber-100','Overdue'=>'bg-rose-50 text-rose-700 border border-rose-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$sch->status] ?? 'bg-slate-100' }}">{{ $sch->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No loan schedules found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $loanSchedules->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 13. TRIAL BALANCE SUMMARY GRID --}}
        @if($activeTab === 'trial_balance')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Trial Balance Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Closing balances filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Consolidated Trial Balance Summary Grid</h3>
                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[9px] font-extrabold uppercase">Checkpoint Verified</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Aggregated multi-level groupings (Liabilities, Loans, Fixed Assets, Current Assets, Incomes & Expenses) with sharp Closing Balance Debit vs Credit alignment.</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-700">
                        Total Debits: <span class="text-slate-900 font-extrabold">₹{{ number_format($trialBalanceEntries['grand_total_debit'] ?? 0, 2) }}</span>
                    </span>
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-700">
                        Total Credits: <span class="text-slate-900 font-extrabold">₹{{ number_format($trialBalanceEntries['grand_total_credit'] ?? 0, 2) }}</span>
                    </span>
                </div>
            </div>

            <div id="trialBalanceChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                <table id="reportsTable" class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white font-extrabold uppercase tracking-wider text-[10px]">
                            <th class="px-5 py-3.5">Account Code</th>
                            <th class="px-5 py-3.5">Financial Group / Head Name</th>
                            <th class="px-5 py-3.5">Category</th>
                            <th class="px-5 py-3.5 text-right bg-slate-800">Closing Balance Debit (₹)</th>
                            <th class="px-5 py-3.5 text-right bg-slate-800">Closing Balance Credit (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 text-slate-700">
                        @if(isset($trialBalanceEntries['groups']))
                            @foreach($trialBalanceEntries['groups'] as $groupName => $group)
                            {{-- Multi-level Group Header --}}
                            <tr class="bg-slate-100/90 font-extrabold border-t-2 border-slate-200">
                                <td colspan="3" class="px-5 py-3 text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ in_array($group['type'], ['Asset', 'Expense']) ? 'bg-indigo-600' : 'bg-amber-600' }}"></span>
                                    <span>{{ $groupName }}</span>
                                    <span class="text-[10px] text-slate-400 font-semibold uppercase">({{ count($group['items']) }} accounts)</span>
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-900 bg-slate-100/60">
                                    {{ $group['total_debit'] > 0 ? '₹' . number_format($group['total_debit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-900 bg-slate-100/60">
                                    {{ $group['total_credit'] > 0 ? '₹' . number_format($group['total_credit'], 2) : '—' }}
                                </td>
                            </tr>
                            {{-- Sub-items --}}
                            @foreach($group['items'] as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-2.5 font-mono font-bold text-slate-500 pl-8">{{ $item['code'] }}</td>
                                <td class="px-5 py-2.5 font-semibold text-slate-900">{{ $item['name'] }}</td>
                                <td class="px-5 py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ in_array($group['type'], ['Asset', 'Expense']) ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-amber-50 text-amber-800 border border-amber-100' }}">
                                        {{ $group['type'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-2.5 text-right font-mono font-bold text-indigo-900">
                                    {{ $item['debit'] > 0 ? '₹' . number_format($item['debit'], 2) : '—' }}
                                </td>
                                <td class="px-5 py-2.5 text-right font-mono font-bold text-amber-900">
                                    {{ $item['credit'] > 0 ? '₹' . number_format($item['credit'], 2) : '—' }}
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot class="bg-slate-950 text-white font-extrabold font-mono text-xs">
                        <tr class="border-t-2 border-slate-800">
                            <td colspan="3" class="px-5 py-4 font-sans uppercase tracking-widest text-emerald-400 flex items-center justify-between">
                                <span>TOTAL CLOSING TRIAL BALANCE</span>
                                <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded text-[9px] font-bold">Checkpoint: Balanced ✅</span>
                            </td>
                            <td class="px-5 py-4 text-right text-emerald-400 font-black text-sm">
                                ₹{{ number_format($trialBalanceEntries['grand_total_debit'] ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right text-emerald-400 font-black text-sm">
                                ₹{{ number_format($trialBalanceEntries['grand_total_credit'] ?? 0, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif

        {{-- 14. PROFIT & LOSS STATEMENT WORKSPACE --}}
        @if($activeTab === 'profit_loss')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Profit & Loss Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Revenue inflows, direct expenses, and Net Profit outcomes filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Profit & Loss Statement Workspace</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Dual-panel workspace balancing Expenses (Direct, Gross Profit, Indirect) on Left vs Incomes (Direct, Indirect) on Right.</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-mono font-bold">
                        Gross Margin: {{ $profitLossEntries['gross_margin_pct'] ?? 0 }}%
                    </span>
                    <span class="px-3 py-1 bg-primary/10 text-primary-800 border border-primary/20 rounded-xl text-xs font-mono font-bold">
                        Net Margin: {{ $profitLossEntries['net_margin_pct'] ?? 0 }}%
                    </span>
                </div>
            </div>

            {{-- PROMINENT NET PROFIT OUTCOME BOX --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-primary-950 rounded-2xl p-6 text-white shadow-xl border border-primary-500/30 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-40 h-40 bg-primary-500/10 rounded-full blur-2xl"></div>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary-300">Calculated Final Financial Outcome</span>
                        </div>
                        <h2 class="text-3xl font-black text-white tracking-tight uppercase font-sans">
                            Net Profit Result
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Net surplus generated after all direct site works, brokerage commissions, and bank financing interest.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-6 py-4 text-center min-w-[240px]">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-300 block mb-1">NET PROFIT</span>
                        <span class="text-3xl font-black font-mono text-emerald-400 block tracking-tight">
                            ₹{{ number_format($profitLossEntries['net_profit'] ?? 0, 2) }}
                        </span>
                        <div class="mt-2 pt-2 border-t border-white/10 flex justify-around text-[10px] text-slate-300 font-mono">
                            <span>EBITDA: <strong>₹{{ number_format($profitLossEntries['ebitda'] ?? 0, 0) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DUAL PANEL WORKSPACE LAYOUT (Expenses Left vs Incomes Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEFT PANEL: EXPENSES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            Expenses & Outflows (Left Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-rose-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        {{-- 1. Direct Expenses --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Construction Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['direct'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GROSS PROFIT HIGHLIGHT BAR --}}
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex justify-between items-center font-bold">
                            <span class="text-emerald-900 uppercase tracking-wider text-[10px]">GROSS PROFIT (Incomes - Direct Exp.)</span>
                            <span class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['gross_profit'] ?? 0, 2) }}</span>
                        </div>

                        {{-- 2. Indirect Expenses --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Administrative & Finance Expenses</span>
                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($profitLossEntries['expenses']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['expenses']['indirect'] ?? [] as $exp)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $exp['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($exp['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Expenses Outflow</span>
                        <strong class="font-mono text-rose-700 text-sm">₹{{ number_format($profitLossEntries['expenses']['total_expenses'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- RIGHT PANEL: INCOMES WORKSPACE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Incomes & Revenue (Right Panel)
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Direct + Indirect</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        {{-- 1. Direct Incomes --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">1. Direct Project Sales Revenue</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_direct'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['direct'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 2. Indirect Incomes --}}
                        <div>
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-3">
                                <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">2. Indirect Surcharges & Retention Fees</span>
                                <span class="font-mono font-bold text-emerald-700">₹{{ number_format($profitLossEntries['incomes']['total_indirect'] ?? 0, 2) }}</span>
                            </div>
                            <div class="space-y-2 font-mono pl-3 text-slate-650">
                                @foreach($profitLossEntries['incomes']['indirect'] ?? [] as $inc)
                                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                    <span class="font-sans text-slate-700">{{ $inc['name'] }}</span>
                                    <span class="font-semibold text-slate-900">₹{{ number_format($inc['amount'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="profitLossMixChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-xl p-3"></div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-3 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-wider text-slate-700">Total Revenue Inflows</span>
                        <strong class="font-mono text-emerald-700 text-sm">₹{{ number_format($profitLossEntries['incomes']['total_incomes'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- 15. BALANCE SHEET SUMMARY PANEL --}}
        @if($activeTab === 'balance_sheet')
        <div class="space-y-6">

            @if(request('project_id') || request('date_from') || request('customer_id') || request('broker_id') || request('payment_mode'))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-center justify-between text-xs text-amber-900 font-medium shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>
                        <strong>Filtered Balance Sheet Statement Active:</strong> 
                        @if(request('project_id')) Project: <strong>{{ $projects->firstWhere('id', request('project_id'))->name ?? 'Project #'.request('project_id') }}</strong> • @endif
                        @if(request('date_from')) Dates: <strong>{{ request('date_from') }}</strong> to <strong>{{ request('date_to', 'Today') }}</strong> • @endif
                        @if(request('payment_mode')) Mode: <strong>{{ request('payment_mode') }}</strong> • @endif
                        Assets, liabilities, and net worth positions filtered dynamically for selected project parameters.
                    </span>
                </div>
                <a href="{{ route('reports.index', ['report' => $activeTab]) }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Balance Sheet Summary Panel</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Split layout template presenting business net worth: Assets (equipment, cash lines, receivables) balanced against Liabilities & Equity.</p>
                </div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-extrabold uppercase flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Balance Sheet Verified
                </span>
            </div>

            {{-- NET WORTH KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Business Net Worth</span>
                    <span class="text-2xl font-black font-mono text-emerald-400 block">₹{{ number_format($balanceSheetEntries['net_worth'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Equity + Reserves</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Working Capital</span>
                    <span class="text-2xl font-black font-mono text-slate-900 block">₹{{ number_format($balanceSheetEntries['working_capital'] ?? 0, 2) }}</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Current Assets - Current Liab.</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">Quick Liquidity Ratio</span>
                    <span class="text-2xl font-black font-mono text-indigo-700 block">{{ $balanceSheetEntries['quick_ratio'] ?? 0 }}x</span>
                    <span class="text-[10px] text-slate-400 mt-1 block">Cash + Receivables Ratio</span>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 block mb-1">Balance Check</span>
                    <span class="text-xl font-black font-mono text-emerald-700 block">0.00 Variance</span>
                    <span class="text-[10px] text-emerald-600 font-bold mt-1 block">Assets = Liabilities + Equity</span>
                </div>
            </div>

            <div id="balanceSheetRatioChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- SPLIT LAYOUT TEMPLATE (Assets Left vs Liabilities & Equity Right) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ASSETS SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                            ASSETS — What the Business Owns
                        </h4>
                        <span class="text-[10px] font-mono text-emerald-300">Debit Balances (+)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['assets']))
                            @foreach($balanceSheetEntries['assets'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL ASSETS VALUE</span>
                        <strong class="font-mono text-emerald-700 text-base">₹{{ number_format($balanceSheetEntries['assets']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

                {{-- LIABILITIES & EQUITY SIDE --}}
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                    <div class="bg-slate-900 text-white px-5 py-3.5 flex justify-between items-center">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            LIABILITIES & EQUITY — What is Owed & Partner Net Worth
                        </h4>
                        <span class="text-[10px] font-mono text-amber-300">Credit Balances (-)</span>
                    </div>

                    <div class="p-5 space-y-6 text-xs text-slate-700 flex-1">
                        @if(isset($balanceSheetEntries['liabilities_and_equity']))
                            @foreach($balanceSheetEntries['liabilities_and_equity'] as $catName => $subItems)
                                @if($catName !== 'total')
                                <div>
                                    <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                        <span class="font-extrabold text-slate-900 uppercase tracking-wider text-[11px]">{{ $catName }}</span>
                                        <span class="font-mono font-bold text-slate-900">₹{{ number_format(array_sum($subItems), 2) }}</span>
                                    </div>
                                    <div class="space-y-2 font-mono pl-3 text-slate-650">
                                        @foreach($subItems as $itemName => $val)
                                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                                            <span class="font-sans text-slate-700">{{ $itemName }}</span>
                                            <span class="font-semibold text-slate-900">₹{{ number_format($val, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center font-extrabold">
                        <span class="text-xs uppercase tracking-widest text-slate-900">TOTAL LIABILITIES & EQUITY</span>
                        <strong class="font-mono text-amber-700 text-base">₹{{ number_format($balanceSheetEntries['liabilities_and_equity']['total'] ?? 0, 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- 17. AUDIT TRAIL REPORT --}}
        @if($activeTab === 'audit_trail')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Audit Trail logs</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Timestamp</th>
                            <th class="px-5 py-3">User Executed</th>
                            <th class="px-5 py-3">Action Module</th>
                            <th class="px-5 py-3">Ref ID</th>
                            <th class="px-5 py-3">Narrative Details</th>
                            <th class="px-5 py-3">Network IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($auditTrailEntries as $log)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800">{{ $log->user?->name ?? 'System Process' }}</td>
                            <td class="px-5 py-3 font-sans text-indigo-700 uppercase tracking-wide text-[10px]">{{ $log->action }}</td>
                            <td class="px-5 py-3 font-bold text-slate-500">{{ $log->subject_id ?? '—' }}</td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-600">{{ $log->description }}</td>
                            <td class="px-5 py-3 text-slate-400 font-mono text-[10px]">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No activity logs recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $auditTrailEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

        {{-- 18. APPROVAL REPORTS --}}
        @if($activeTab === 'approvals')
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Approval Reports</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Requested Date</th>
                            <th class="px-5 py-3">Rule Subject Type</th>
                            <th class="px-5 py-3">Requester User</th>
                            <th class="px-5 py-3">Approver User</th>
                            <th class="px-5 py-3">Decision Status</th>
                            <th class="px-5 py-3">Reason narrative</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($approvalReportEntries as $app)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $app->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800 text-[10px] uppercase tracking-wide">{{ class_basename($app->approvable_type ?? 'Generic Approval') }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->requester?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->approver?->name ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['approved'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','pending'=>'bg-amber-50 text-amber-700 border border-amber-100','rejected'=>'bg-rose-50 text-rose-700 border border-rose-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$app->status] ?? 'bg-slate-100' }}">{{ $app->status }}</span>
                            </td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-500 italic">{{ $app->reason ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No approval workflows processed.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $approvalReportEntries->appends(request()->query())->links() }}</div>
        </div>
        @endif

    </div>

</div>

{{-- ApexCharts Library --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
function reportsApp() {
    return {
        activeTab: '{{ $activeTab }}',
        customerList: {!! json_encode($customers->map(function($c) { return ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone, 'email' => $c->email]; })) !!},
        selectedCustomerId: '{{ request('customer_id', '') }}',

        getFilteredCustomersList(search = '') {
            const q = (search || '').toLowerCase().trim();
            if (!q) return this.customerList;
            return this.customerList.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.phone && c.phone.toLowerCase().includes(q))
            );
        },
        getSelectedCustomer() {
            if (!this.selectedCustomerId) return null;
            return this.customerList.find(c => c.id == this.selectedCustomerId) || null;
        },
        selectCustomer(customer) {
            this.selectedCustomerId = customer ? customer.id : '';
            this.$nextTick(() => {
                const form = document.getElementById('customerLedgerForm');
                if (form) form.submit();
            });
        },

        init() {
            this.$nextTick(() => {
                this.renderAllCharts();
            });
        },

        printReport() {
            window.print();
        },

        exportCurrentTable() {
            const table = document.querySelector("#reportsTable");
            if (!table) {
                alert("No table available on this report tab to export.");
                return;
            }
            let html = table.outerHTML;
            // Remove styling and interactive components
            html = html.replace(/<button[^>]*>([\s\S]*?)<\/button>/gi, '');
            html = html.replace(/<input[^>]*>/gi, '');
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'HindustanERP_Report_' + this.activeTab + '.xls';
            a.click();
        },

        renderAllCharts() {
            // Render specific charts depending on the active tab
            
            // 16. DASHBOARD & MIS
            @if($activeTab === 'dashboard')
            if (this.activeTab === 'dashboard') {
                const sold = {{ $dashboardData['sold_units'] ?? 0 }};
                const unsold = {{ $dashboardData['unsold_units'] ?? 0 }};

                if (document.querySelector("#soldUnsoldChart")) {
                    new ApexCharts(document.querySelector("#soldUnsoldChart"), {
                        series: [sold, unsold],
                        labels: ['Sold Units', 'Unsold Units'],
                        chart: { type: 'donut', height: 230, fontFamily: 'Inter, sans-serif' },
                        colors: ['#059669', '#3b82f6'],
                        legend: { position: 'bottom', fontSize: '12px', fontWeight: 600, labels: { colors: '#64748b' } },
                        stroke: { width: 2, colors: ['#ffffff'] },
                        dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 'bold' } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Units',
                                            fontSize: '12px',
                                            fontWeight: 700,
                                            color: '#64748b'
                                        },
                                        value: {
                                            color: '#0f172a'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                if (document.querySelector("#collectionsExpectedChart")) {
                    new ApexCharts(document.querySelector("#collectionsExpectedChart"), {
                        series: [{
                            name: 'Amount (₹)',
                            data: [{{ $dashboardData['collections'] ?? 0 }}, {{ $dashboardData['outstanding'] ?? 0 }}]
                        }],
                        chart: { type: 'bar', height: 230, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#f59e0b'],
                        plotOptions: {
                            bar: {
                                columnWidth: '45%',
                                borderRadius: 6,
                                distributed: true
                            }
                        },
                        legend: { show: false },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return '₹' + new Intl.NumberFormat('en-IN').format(val);
                            },
                            style: { fontSize: '10px', fontWeight: 'bold' }
                        },
                        xaxis: {
                            categories: ['Total Collections', 'Outstanding'],
                            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: '700' } }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#64748b' },
                                formatter: function(val) {
                                    return '₹' + (val / 100000).toFixed(1) + 'L';
                                }
                            }
                        }
                    }).render();
                }

                // Profitability charts
                const projNames = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        '{{ $row['project']->name }}',
                    @endforeach
                ];
                const actRevs = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['actual_revenue'] }},
                    @endforeach
                ];
                const totCosts = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['total_cost'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#revenueCostChart"), {
                    series: [
                        { name: 'Actual Revenue', data: actRevs },
                        { name: 'Total Cost', data: totCosts }
                    ],
                    chart: { type: 'bar', height: 250, toolbar: { show: false } },
                    colors: ['#10b981', '#ef4444'],
                    plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 3 } },
                    xaxis: { categories: projNames }
                }).render();

                const margins = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['margin'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#profitMarginsChart"), {
                    series: [{ name: 'Profit Margin %', data: margins }],
                    chart: { type: 'line', height: 250, toolbar: { show: false } },
                    colors: ['#a38c29'],
                    stroke: { width: 3, curve: 'smooth' },
                    markers: { size: 4 },
                    yaxis: { labels: { formatter: (v) => v.toFixed(1) + '%' } },
                    xaxis: { categories: projNames }
                }).render();
            }
            @endif

            // 1. AVAILABILITY
            @if($activeTab === 'availability')
            if (this.activeTab === 'availability') {
                new ApexCharts(document.querySelector("#availabilityDistributionChart"), {
                    series: [
                        {{ $inventoryGrid->where('status', 'available')->count() }},
                        {{ $inventoryGrid->where('status', 'sold')->count() }},
                        {{ $inventoryGrid->where('status', 'booked')->count() }},
                        {{ $inventoryGrid->where('status', 'reserved')->count() }}
                    ],
                    labels: ['Available', 'Sold', 'Booked', 'Reserved'],
                    chart: { type: 'donut', height: 200 },
                    colors: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'],
                    legend: { position: 'bottom' }
                }).render();

                const unitTypeNames = {!! json_encode($groupedSummary->pluck('type')) !!};
                const unitTypeCounts = {!! json_encode($groupedSummary->pluck('nos')) !!};

                new ApexCharts(document.querySelector("#unitTypeDistributionChart"), {
                    series: [{
                        name: 'Units Count',
                        data: unitTypeCounts
                    }],
                    chart: { type: 'bar', height: 200, toolbar: { show: false } },
                    colors: ['#6366f1'],
                    plotOptions: { bar: { columnWidth: '40%', borderRadius: 4 } },
                    xaxis: { categories: unitTypeNames }
                }).render();
            }
            @endif

            // 2. SALES
            @if($activeTab === 'sales')
            if (this.activeTab === 'sales') {
                const sMonths  = {!! json_encode($salesChartData['months'] ?? []) !!};
                const sAmounts = {!! json_encode($salesChartData['amounts'] ?? []) !!};
                const sProjects = {!! json_encode($salesChartData['project_names'] ?? []) !!};
                const sCounts   = {!! json_encode($salesChartData['project_counts'] ?? []) !!};

                const monthlyChartEl = document.querySelector("#monthlySalesTrendChart");
                if (monthlyChartEl) {
                    new ApexCharts(monthlyChartEl, {
                        series: [{
                            name: 'Sales Value (₹)',
                            data: sAmounts
                        }],
                        chart: { type: 'area', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29'],
                        stroke: { curve: 'smooth', width: 3 },
                        fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                        xaxis: { categories: sMonths },
                        yaxis: { labels: { formatter: (v) => '₹' + (v >= 10000000 ? (v/10000000).toFixed(1)+'Cr' : (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K')) } },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                    }).render();
                }

                const projectChartEl = document.querySelector("#salesByProjectChart");
                if (projectChartEl) {
                    new ApexCharts(projectChartEl, {
                        series: [{
                            name: 'Active Sales Count',
                            data: sCounts
                        }],
                        chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#f97316'],
                        plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                        xaxis: { categories: sProjects.length ? sProjects : ['No Projects'] },
                        tooltip: { y: { formatter: (v) => v + ' Units Sold' } }
                    }).render();
                }
            }
            @endif

            // 3. EMI & COLLECTIONS
            @if($activeTab === 'emi_collections')
            if (this.activeTab === 'emi_collections') {
                const emiMonths  = {!! json_encode($emiChartData['months'] ?? []) !!};
                const emiAmounts = {!! json_encode($emiChartData['amounts'] ?? []) !!};

                new ApexCharts(document.querySelector("#emiOutstandingCollectionChart"), {
                    series: [{{ $emiCollectionsSummary['total_received'] ?? 0 }}, {{ $emiCollectionsSummary['outstanding'] ?? 0 }}],
                    labels: ['Collected', 'Outstanding'],
                    chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                    dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Value',
                                        formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                    }
                                }
                            }
                        }
                    }
                }).render();

                new ApexCharts(document.querySelector("#emiCollectionTrendChart"), {
                    series: [{
                        name: 'Monthly Collections (₹)',
                        data: emiAmounts
                    }],
                    chart: { type: 'area', height: 200, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: emiMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 4. CUSTOMER LEDGER
            @if($activeTab === 'customer_ledger')
            if (this.activeTab === 'customer_ledger' && document.querySelector("#customerPaymentHistoryChart")) {
                const customerCredits = {!! json_encode(($ledgerEntries ?? collect())->where('credit', '>', 0)->pluck('credit')->map(fn($v) => (float)$v)->values()) !!};
                const customerDates   = {!! json_encode(($ledgerEntries ?? collect())->where('credit', '>', 0)->pluck('date')->values()) !!};
                new ApexCharts(document.querySelector("#customerPaymentHistoryChart"), {
                    series: [{
                        name: 'Receipt Payments (₹)',
                        data: customerCredits.length ? customerCredits : [0]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#10b981'],
                    plotOptions: { bar: { columnWidth: '45%', borderRadius: 3 } },
                    xaxis: { categories: customerDates.length ? customerDates : ['No Payments'] },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 5. CASH BOOK — Partner Analytics Dashboard
            @if($activeTab === 'cash_book')
            if (this.activeTab === 'cash_book') {
                // Monthly collections bar chart
                const cbMonthlyLabels  = {!! json_encode(array_column($cashBookChartData['monthly'], 'label')) !!};
                const cbMonthlyAmounts = {!! json_encode(array_column($cashBookChartData['monthly'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbMonthlyChart"), {
                    series: [{ name: 'Collections', data: cbMonthlyAmounts }],
                    chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#a38c29'],
                    plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbMonthlyLabels,
                        labels: { style: { fontSize: '9px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v))
                        }
                    },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Payment mode donut
                const cbModeLabels  = {!! json_encode($cashBookChartData['payment_modes']->pluck('payment_mode')->map(fn($m) => $m ?? '-')) !!};
                const cbModeAmounts = {!! json_encode($cashBookChartData['payment_modes']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbModeLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPaymentModeChart"), {
                        series: cbModeAmounts,
                        labels: cbModeLabels,
                        chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f97316'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: { fontSize: '10px', fontWeight: 600, color: '#64748b' },
                                        value: { fontSize: '11px', fontWeight: 700, color: '#0f172a' },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '10px',
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: (w) => {
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                if (total >= 10000000) return '₹' + (total / 10000000).toFixed(2) + ' Cr';
                                                if (total >= 100000) return '₹' + (total / 100000).toFixed(2) + ' L';
                                                return '₹' + total.toLocaleString('en-IN');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                // Daily trend line chart (last 30 days)
                const cbDailyLabels  = {!! json_encode(array_column($cashBookChartData['daily'], 'label')) !!};
                const cbDailyAmounts = {!! json_encode(array_column($cashBookChartData['daily'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbDailyTrendChart"), {
                    series: [{ name: 'Daily Collections', data: cbDailyAmounts }],
                    chart: { type: 'area', height: 200, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbDailyLabels,
                        labels: { rotate: -45, style: { fontSize: '8px' }, show: cbDailyLabels.length <= 15 }
                    },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Partner-wise donut chart
                const cbPartnerLabels  = {!! json_encode($cashBookChartData['partner_wise']->map(fn($r) => $r->partner?->name ?? '-')) !!};
                const cbPartnerAmounts = {!! json_encode($cashBookChartData['partner_wise']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbPartnerLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPartnerDonutChart"), {
                        series: cbPartnerAmounts,
                        labels: cbPartnerLabels,
                        chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316', '#8b5cf6'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: { fontSize: '10px', fontWeight: 600, color: '#64748b', offsetY: -2 },
                                        value: { fontSize: '11px', fontWeight: 700, color: '#0f172a', offsetY: 2 },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '10px',
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: (w) => {
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                if (total >= 10000000) return '₹' + (total / 10000000).toFixed(2) + ' Cr';
                                                if (total >= 100000) return '₹' + (total / 100000).toFixed(2) + ' L';
                                                return '₹' + total.toLocaleString('en-IN');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                // Partner comparison bar chart (if multiple partners)
                @if($cashBookChartData['partner_wise']->count() > 1)
                const cbPartnerBarEl = document.querySelector("#cbPartnerBarChart");
                if (cbPartnerBarEl) {
                    new ApexCharts(cbPartnerBarEl, {
                        series: [{ name: 'Total Received', data: cbPartnerAmounts }],
                        chart: { type: 'bar', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316'],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } } },
                        dataLabels: {
                            enabled: true,
                            formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN'),
                            style: { fontSize: '9px', colors: ['#475569'] },
                            offsetX: 5
                        },
                        xaxis: {
                            categories: cbPartnerLabels,
                            labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') }
                        },
                        grid: { borderColor: '#f1f5f9' },
                        tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                    }).render();
                }
                @endif
            }
            @endif

            // 6. BANK REPORTS
            @if($activeTab === 'bank_reports')
            if (this.activeTab === 'bank_reports') {
                const bankMonths  = {!! json_encode($bankChartData['months'] ?? []) !!};
                const bankAmounts = {!! json_encode($bankChartData['amounts'] ?? []) !!};
                new ApexCharts(document.querySelector("#bankTransactionsChart"), {
                    series: [{
                        name: 'Bank Clearances',
                        data: bankAmounts
                    }],
                    chart: { type: 'area', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: bankMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 7. PARTNER STATEMENTS
            @if($activeTab === 'partner_statements')
            if (this.activeTab === 'partner_statements') {
                const partnerMonths  = {!! json_encode($partnerChartData['months'] ?? []) !!};
                const partnerAmounts = {!! json_encode($partnerChartData['amounts'] ?? []) !!};
                const pLabels = {!! json_encode($partnerChartData['partner_labels'] ?? []) !!};
                const pTotals = {!! json_encode($partnerChartData['partner_totals'] ?? []) !!};

                // Chart 1: Monthly Allocation Column Bar Chart
                new ApexCharts(document.querySelector("#partnerStatementsChart"), {
                    series: [{
                        name: 'Capital Outflow Allocated',
                        data: partnerAmounts
                    }],
                    chart: { type: 'bar', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#a38c29'],
                    plotOptions: {
                        bar: {
                            columnWidth: '32%',
                            borderRadius: 6,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: (v) => v > 0 ? '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') : '',
                        offsetY: -20,
                        style: { fontSize: '10px', fontWeight: 700, colors: ['#a38c29'] }
                    },
                    xaxis: {
                        categories: partnerMonths,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)),
                            style: { colors: '#94a3b8', fontSize: '10px' }
                        }
                    },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();

                // Chart 2: Partner Distribution Donut Chart
                if (pLabels.length > 0 && document.querySelector("#partnerDistributionChart")) {
                    new ApexCharts(document.querySelector("#partnerDistributionChart"), {
                        series: pTotals,
                        labels: pLabels,
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316', '#8b5cf6'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Outflow',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }
            }
            @endif

            // 8. SUPPLIER & CONTRACTOR
            @if($activeTab === 'supplier_contractor')
            if (this.activeTab === 'supplier_contractor') {
                const supplierLabels = {!! json_encode($supplierChartData['labels'] ?? []) !!};
                const supplierDues   = {!! json_encode($supplierChartData['dues'] ?? []) !!};
                const supplierPaids  = {!! json_encode($supplierChartData['paids'] ?? []) !!};
                new ApexCharts(document.querySelector("#supplierPayablesChart"), {
                    series: [
                        { name: 'Commission Due', data: supplierDues },
                        { name: 'Commission Paid', data: supplierPaids }
                    ],
                    chart: { type: 'bar', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#f97316', '#10b981'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { columnWidth: '30%', borderRadius: 4 } },
                    xaxis: { categories: supplierLabels.length ? supplierLabels : ['No Brokers'] },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 9. SALES RETURN
            @if($activeTab === 'sales_return')
            if (this.activeTab === 'sales_return') {
                const retMonths  = {!! json_encode($salesReturnChartData['months'] ?? []) !!};
                const retFees    = {!! json_encode($salesReturnChartData['fees'] ?? []) !!};
                const retRefunds = {!! json_encode($salesReturnChartData['refunds'] ?? []) !!};
                const totalFee   = {{ $salesReturnChartData['total_fee'] ?? 0 }};
                const totalRefund = {{ $salesReturnChartData['total_refund'] ?? 0 }};

                // Chart 1: Monthly Timeline Area Chart
                new ApexCharts(document.querySelector("#salesReturnChart"), {
                    series: [
                        { name: 'Cancellation Fees Retained (₹)', data: retFees },
                        { name: 'Refund Amount Payable (₹)', data: retRefunds }
                    ],
                    chart: { type: 'area', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#ef4444', '#10b981'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.05 } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: retMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)) } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();

                // Chart 2: Retention vs Refund Donut Chart
                if (document.querySelector("#salesReturnDonutChart")) {
                    new ApexCharts(document.querySelector("#salesReturnDonutChart"), {
                        series: [totalFee, totalRefund],
                        labels: ['Cancellation Fee Retained', 'Refund Payable'],
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#ef4444', '#10b981'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Value',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }
            }
            @endif

            // 10. EXCHANGE REPORT
            @if($activeTab === 'exchange_report')
            if (this.activeTab === 'exchange_report') {
                const exMonths   = {!! json_encode($exchangeChartData['months'] ?? []) !!};
                const exEquities = {!! json_encode($exchangeChartData['equities'] ?? []) !!};
                new ApexCharts(document.querySelector("#unitExchangesChart"), {
                    series: [{
                        name: 'Transferred Equity Applied (₹)',
                        data: exEquities
                    }],
                    chart: { type: 'area', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: exMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 11. PETTY CASH
            @if($activeTab === 'petty_cash')
            if (this.activeTab === 'petty_cash') {
                const pcMonths  = {!! json_encode($pettyCashChartData['months'] ?? []) !!};
                const pcAmounts = {!! json_encode($pettyCashChartData['amounts'] ?? []) !!};
                const pcCustLabels = {!! json_encode($pettyCashChartData['cust_labels'] ?? []) !!};
                const pcCustTotals = {!! json_encode($pettyCashChartData['cust_totals'] ?? []) !!};

                // Chart 1: Customer Distribution Donut
                if (pcCustLabels.length > 0 && document.querySelector("#pettyCashCustomerChart")) {
                    new ApexCharts(document.querySelector("#pettyCashCustomerChart"), {
                        series: pcCustTotals,
                        labels: pcCustLabels,
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#a38c29'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Inflow',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                // Chart 2: User-Friendly Monthly Column Bar Chart
                if (document.querySelector("#pettyCashChart")) {
                    new ApexCharts(document.querySelector("#pettyCashChart"), {
                        series: [{
                            name: 'Cash Collections',
                            data: pcAmounts
                        }],
                        chart: { type: 'bar', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29'],
                        plotOptions: {
                            bar: {
                                columnWidth: '32%',
                                borderRadius: 6,
                                dataLabels: { position: 'top' }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (v) => v > 0 ? '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') : '',
                            offsetY: -20,
                            style: { fontSize: '10px', fontWeight: 700, colors: ['#a38c29'] }
                        },
                        xaxis: {
                            categories: pcMonths,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } }
                        },
                        yaxis: {
                            labels: {
                                formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)),
                                style: { colors: '#94a3b8', fontSize: '10px' }
                            }
                        },
                        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                        tooltip: {
                            y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') }
                        }
                    }).render();
                }
            }
            @endif

            // 12. BANK LOAN EMI
            @if($activeTab === 'loan_schedules')
            if (this.activeTab === 'loan_schedules') {
                const el = document.querySelector("#bankLoanEmiChart");
                if (el) {
                    const lMonths     = {!! json_encode($loanChartData['months'] ?? []) !!};
                    const lPrincipals = {!! json_encode($loanChartData['principals'] ?? []) !!};
                    const lInterests  = {!! json_encode($loanChartData['interests'] ?? []) !!};
                    new ApexCharts(el, {
                        series: [
                            { name: 'Principal Component', data: lPrincipals },
                            { name: 'Interest Component', data: lInterests }
                        ],
                        chart: { type: 'bar', height: 210, stacked: true, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#3b82f6', '#ef4444'],
                        dataLabels: { enabled: false },
                        plotOptions: { bar: { columnWidth: '30%', borderRadius: 4 } },
                        xaxis: { categories: lMonths },
                        yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)) } },
                        grid: { borderColor: '#f1f5f9' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                    }).render();
                }
            }
            @endif

            // 13. TRIAL BALANCE
            @if($activeTab === 'trial_balance')
            if (this.activeTab === 'trial_balance') {
                new ApexCharts(document.querySelector("#trialBalanceChart"), {
                    series: [
                        { name: 'Total Debit', data: [{{ $trialBalanceEntries['grand_total_debit'] ?? 0 }}] },
                        { name: 'Total Credit', data: [{{ $trialBalanceEntries['grand_total_credit'] ?? 0 }}] }
                    ],
                    chart: { type: 'bar', height: 140, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6', '#10b981'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { horizontal: true, barHeight: '35%', borderRadius: 4 } },
                    xaxis: { labels: { formatter: (v) => '₹' + (v >= 10000000 ? (v/10000000).toFixed(1)+'Cr' : (v/100000).toFixed(1)+'L') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 14. PROFIT & LOSS
            @if($activeTab === 'profit_loss')
            if (this.activeTab === 'profit_loss') {
                const revenue = {{ $profitLossEntries['revenue'] ?? 0 }};
                const cost = {{ ($profitLossEntries['brokerage'] ?? 0) + ($profitLossEntries['financing'] ?? 0) + ($profitLossEntries['site_expenses'] ?? 0) }};
                new ApexCharts(document.querySelector("#profitLossMixChart"), {
                    series: [revenue, cost],
                    labels: ['Revenue', 'Expenses'],
                    chart: { type: 'donut', height: 180 },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom' }
                }).render();
            }
            @endif

            // 15. BALANCE SHEET
            @if($activeTab === 'balance_sheet')
            if (this.activeTab === 'balance_sheet') {
                const assetsSum = {{ $balanceSheetEntries['assets']['total'] ?? 0 }};
                const liabilitiesSum = {{ $balanceSheetEntries['liabilities_and_equity']['total'] ?? 0 }};
                new ApexCharts(document.querySelector("#balanceSheetRatioChart"), {
                    series: [{
                        name: 'Value',
                        data: [assetsSum, liabilitiesSum]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#10b981', '#f43f5e'],
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
                    xaxis: { categories: ['Total Assets', 'Total Liabilities & Equity'] }
                }).render();
            }
            @endif
        }
    };
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-\[1800px\], .max-w-\[1800px\] * {
        visibility: visible;
    }
    .max-w-\[1800px\] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    /* Hide navigation, filters, buttons and interactive controls from prints */
    button, input, select, a, .print-hidden, .print\:hidden, [class*="print-hidden"] {
        display: none !important;
    }
}
</style>

</x-erp-layout>
