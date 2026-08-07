<x-erp-layout title="GST & Tax Statutory Executive Ledger" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
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
                        <a href="{{ route('reports.gst_report', ['section' => $sKey, 'project_id' => request('project_id'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}"
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
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
