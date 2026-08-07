<x-erp-layout title="Real-time Sales Register & Report" headerTitle="Business Reports Center">

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
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
