@php
    $modeColors = [
        'Cash'          => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'Bank Transfer' => 'bg-blue-50 text-blue-700 border-blue-100',
        'Cheque'        => 'bg-violet-50 text-violet-700 border-violet-100',
        'Online'        => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'UPI'           => 'bg-amber-50 text-amber-700 border-amber-100',
    ];
@endphp

<x-erp-layout title="EMI Collection Trends & Summary" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            
            {{-- Top Header & Action Banner (Matching Sales Report style) --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">EMI Collection Trends & Summary</h3>
                            <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Executive Inflow Ledger</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Real-time collection analytics, payment trends, and outstanding receivables tracker.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0 relative z-10 print:hidden">
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

            {{-- Charts section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 print:break-inside-avoid">
                <div class="border border-slate-200 rounded-2xl p-5 bg-white shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">EMI Outstanding vs Collection</h4>
                            <p class="text-[9px] text-slate-400 mt-0.5">Ratio breakdown of received vs outstanding</p>
                        </div>
                        <span class="px-2 py-0.5 text-[8px] font-bold bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-full uppercase tracking-wider">Ratio Donut</span>
                    </div>
                    <div id="emiOutstandingCollectionChart" class="w-full h-56"></div>
                </div>
                
                <div class="border border-slate-200 rounded-2xl p-5 bg-white shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Collection Trend Performance</h4>
                            <p class="text-[9px] text-slate-400 mt-0.5">Monthly cash inflow history & trend line</p>
                        </div>
                        <span class="px-2 py-0.5 text-[8px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full uppercase tracking-wider">Trend Area</span>
                    </div>
                    <div id="emiCollectionTrendChart" class="w-full h-56"></div>
                </div>
            </div>

            {{-- Table section (Matching Sales Report table wrapper style) --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-amber-50/40 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                            Chronological Cash Inflow Log & Financial Auditing Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Chronological listing of cash/bank collections and receipt vouchers.</p>
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
                                <th class="px-4 py-3 text-white font-extrabold text-center whitespace-nowrap">Sl.No</th>
                                <th class="px-4 py-3 text-white font-extrabold whitespace-nowrap">Date</th>
                                <th class="px-4 py-3 text-white font-extrabold whitespace-nowrap text-center">Voucher Ref</th>
                                <th class="px-4 py-3 text-white font-extrabold">Customer & Booking Details</th>
                                <th class="px-4 py-3 text-white font-extrabold whitespace-nowrap">Payment Mode</th>
                                <th class="px-4 py-3 text-white font-extrabold text-right whitespace-nowrap">Inflow Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                            @forelse($cashBookEntries as $receipt)
                            @php
                                $mc = $modeColors[$receipt->payment_mode] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                $slNo = $loop->iteration + ($cashBookEntries->currentPage() - 1) * $cashBookEntries->perPage();
                            @endphp
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium text-[11px]">
                                <td class="px-4 py-3 text-center text-slate-500 font-mono font-bold whitespace-nowrap">{{ $slNo }}</td>
                                <td class="px-4 py-3 text-slate-500 font-sans whitespace-nowrap">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-bold text-indigo-700 font-mono text-center whitespace-nowrap">REC-{{ sprintf("%05d", $receipt->id) }}</td>
                                <td class="px-4 py-3 font-sans">
                                    <div class="font-bold text-slate-900 leading-tight">{{ $receipt->customer?->name ?? '—' }}</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5 leading-normal">
                                        {{ $receipt->sale?->project?->name ?? '—' }} · Unit {{ $receipt->sale?->unit?->door_no ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-sans whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded text-[9px] font-bold border inline-block {{ $mc }}">{{ $receipt->payment_mode }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-emerald-700 font-bold font-mono text-sm whitespace-nowrap">₹{{ number_format($receipt->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center text-slate-400 italic">
                                    No collections found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination Panel (Matching Sales Report style) --}}
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl print:hidden">
                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                        Showing <span class="text-slate-900 font-black">{{ $cashBookEntries->firstItem() ?? 0 }}</span> to 
                        <span class="text-slate-900 font-black">{{ $cashBookEntries->lastItem() ?? 0 }}</span> of 
                        <span class="text-slate-900 font-black">{{ $cashBookEntries->total() }}</span> Collection Records
                    </div>
                    <div class="flex items-center gap-1.5">
                        {{ $cashBookEntries->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
