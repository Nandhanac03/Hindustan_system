<x-erp-layout title="Real-time Sales Register & Report" headerTitle="Business Reports Center">

<style>
    .table-responsive-container::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .table-responsive-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e1; /* slate-300 */
        border-radius: 9999px;
    }
    .table-responsive-container::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8; /* slate-400 */
    }
</style>

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
                </div>
            </div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'salesReportFilterForm', 'actionRoute' => route('reports.sales'), 'exportLabel' => 'Sale Report'])
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
                    @include('reports.partials.header-badges')
                </div>

                <div class="w-full overflow-x-auto table-responsive-container">
                    <table id="reportsTable" class="w-full text-[11px] text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-3 py-3 text-white font-extrabold whitespace-nowrap min-w-[100px]">Sale No</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[200px]">Project / Unit</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[90px]">Floor</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[130px]">Customer</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Expected (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Sale Amt (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[115px]">Diff (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[130px]">Additional Work (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[100px]">GST (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Total (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-center whitespace-nowrap min-w-[115px]">Agreement Date</th>
                                <th class="px-3 py-3 text-white font-extrabold text-center whitespace-nowrap min-w-[125px]">Registration Date</th>
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
                                $extraWorkAmount = (float)($sale->extraWorks->sum('amount') ?? 0.00);

                                $floorsList = '';
                                if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                                    $floorNames = $sale->saleUnits->map(fn($su) => $su->unit?->floor?->name)->filter()->unique();
                                    if ($floorNames->isNotEmpty()) {
                                        $floorsList = implode(', ', $floorNames->toArray());
                                    }
                                } elseif ($sale->unit && $sale->unit->floor) {
                                    $floorsList = $sale->unit->floor->name;
                                }
                            @endphp
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium text-[11px]">
                                <td class="px-3 py-3.5 font-bold text-emerald-700 whitespace-nowrap min-w-[100px]">{{ $sale->sale_number }}</td>
                                <td class="px-3 py-3.5 font-sans min-w-[200px]">
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
                                <td class="px-3 py-3.5 font-sans text-slate-650 min-w-[90px]">{{ $floorsList ?: '—' }}</td>
                                <td class="px-3 py-3.5 font-sans text-slate-800 leading-tight min-w-[130px]">{{ $sale->customer?->name ?? 'N/A' }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-slate-650 whitespace-nowrap min-w-[110px]">₹{{ number_format($expectedAmount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap min-w-[110px]">₹{{ number_format($saleAmount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold whitespace-nowrap min-w-[115px]">
                                    @if($difference > 0)
                                        <span class="text-emerald-600">+₹{{ number_format($difference, 2) }}</span>
                                    @elseif($difference < 0)
                                        <span class="text-rose-600">-₹{{ number_format(abs($difference), 2) }}</span>
                                    @else
                                        <span class="text-slate-400">₹0.00</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-[#a38c29] whitespace-nowrap min-w-[130px] font-bold">
                                    @if($extraWorkAmount > 0)
                                        ₹{{ number_format($extraWorkAmount, 2) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono text-slate-700 whitespace-nowrap min-w-[100px]">
                                    @if($sale->gst_amount > 0)
                                        ₹{{ number_format($sale->gst_amount, 2) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap min-w-[110px]">₹{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-3 py-3.5 text-center font-sans whitespace-nowrap min-w-[115px]">{{ $sale->agreement_date?->format('d M Y') ?? $sale->sale_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-3 py-3.5 text-center font-sans whitespace-nowrap min-w-[125px]">{{ $sale->registration_date?->format('d M Y') ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="px-3 py-12 text-center text-slate-400 italic">No sales logs matching filter criteria.</td>
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

<div class="hidden" style="display: none;">
    <table id="salesExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            {{-- Section 1 --}}
            <col width="60" style="width: 45pt;" />    {{-- SL NO --}}
            <col width="220" style="width: 165pt;" />  {{-- CUSTOMER NAME --}}
            <col width="130" style="width: 98pt;" />   {{-- BOOKING DATE --}}
            <col width="90" style="width: 68pt;" />    {{-- FLOOR --}}
            <col width="110" style="width: 83pt;" />   {{-- UNIT TYPE --}}
            <col width="140" style="width: 105pt;" />  {{-- AGREEMENT DATE --}}
            <col width="110" style="width: 83pt;" />   {{-- AREA (SQFT) --}}
            {{-- Section 2 --}}
            <col width="150" style="width: 113pt;" />  {{-- EXPECTED RATE / SQFT --}}
            <col width="140" style="width: 105pt;" />  {{-- ACTUAL RATE / SQFT --}}
            <col width="150" style="width: 113pt;" />  {{-- BASE TOTAL AMOUNT --}}
            <col width="190" style="width: 143pt;" />  {{-- RATE VARIANCE --}}
            {{-- Section 3 --}}
            <col width="80" style="width: 60pt;" />    {{-- GST % --}}
            <col width="130" style="width: 98pt;" />   {{-- GST AMOUNT --}}
            <col width="140" style="width: 105pt;" />  {{-- PARKING CHARGES --}}
            <col width="140" style="width: 105pt;" />  {{-- ADDITIONAL WORK --}}
            <col width="170" style="width: 128pt;" />  {{-- GRAND TOTAL DEAL PRICE --}}
            {{-- Section 4 --}}
            <col width="160" style="width: 120pt;" />  {{-- TOTAL CHEQUE VALUE --}}
            <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIVED --}}
            <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIPT DATE --}}
            {{-- Section 5 --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE BALANCE DUE --}}
            <col width="110" style="width: 83pt;" />   {{-- INSTALMENT --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE COLLECTION % --}}
        </colgroup>
        <thead>
            {{-- Title Header Row (Exact A3 print layout friendly combined 1 row title) --}}
            <tr height="45" style="height: 45pt;">
                <th colspan="22" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: REAL ESTATE SALES BOOKING MASTER (WITH AUDIT DATES & DUAL-TRACK SPLIT)
                </th>
            </tr>
            {{-- Super Section Headers Row with correct requested colors --}}
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. UNIT & CUSTOMER INFORMATION</th>
                <th colspan="4" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. PRICING & RATE VARIANCE</th>
                <th colspan="5" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. TAXES & ADD-ONS</th>
                <th colspan="3" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">4. CHEQUE VALUE</th>
                <!-- <th colspan="3" bgcolor="#15803d" style="background-color: #15803d; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">5. BALANCE & COLLECTION</th> -->
            </tr>
            {{-- Main Column Headers --}}
            <tr height="40" style="height: 40pt;">
                {{-- Section 1 --}}
                <th width="60" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 45pt;">SL NO</th>
                <th width="220" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 165pt;">CUSTOMER NAME</th>
                <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">BOOKING DATE</th>
                <th width="90" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 68pt;">FLOOR</th>
                <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">UNIT TYPE</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">AGREEMENT DATE</th>
                <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">AREA (SQFT)</th>
                {{-- Section 2 --}}
                <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">EXPECTED RATE / SQFT</th>
                <th width="140" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ACTUAL RATE / SQFT</th>
                <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">BASE TOTAL AMOUNT</th>
                <th width="190" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 143pt;">RATE VARIANCE (DISCOUNT / LOSS)</th>
                {{-- Section 3 --}}
                <th width="80" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 60pt;">GST %</th>
                <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">GST AMOUNT</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">PARKING CHARGES</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ADDITIONAL WORK</th>
                <th width="170" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 128pt;">TOTAL AMOUNT INCLUDING GST /PARKING/ADDITIONAL</th>
                {{-- Section 4 --}}
                <!-- <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL CHEQUE VALUE</th> -->
                <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIVED</th>
                <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIPT DATE</th>
                <th width="110" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">INSTALMENT</th>
                <!-- <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE COLLECTION %</th> -->
                {{-- Section 5 --}}
                <!-- <th width="160" bgcolor="#17365D" x:autofilter="all" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE BALANCE DUE</th> -->

            </tr>
        </thead>
        <tbody>
            @php
                $totals = [
                    'area' => 0,
                    'base_total' => 0,
                    'variance' => 0,
                    'gst_amount' => 0,
                    'parking' => 0,
                    'additional' => 0,
                    'grand_total' => 0,
                    'cheque_value' => 0,
                    'cheque_received' => 0,
                    'cheque_due' => 0
                ];
            @endphp
            @foreach($salesList as $sale)
                @php
                    $mainUnit = $sale->saleUnits->filter(fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->first() ?? $sale->saleUnits->first();

                    // Collect non-parking units for floor / unit display
                    $nonParkingUnits = $sale->saleUnits->filter(
                        fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking')
                    );

                    // Helper: format a floor name string to ordinal (e.g. "Floor 11" → "11TH")
                    $formatFloor = function(string $floorName): string {
                        $clean = preg_replace('/[^0-9]/', '', $floorName);
                        if ($clean !== '') {
                            $n = (int)$clean;
                            $suffix = in_array($n % 100, [11, 12, 13]) ? 'TH'
                                : (['TH', 'ST', 'ND', 'RD'][$n % 10] ?? 'TH');
                            return $clean . $suffix;
                        }
                        return strtoupper(trim($floorName));
                    };

                    // Comma-separated floor display for all units (including parking)
                    $floorParts = $sale->saleUnits
                        ->map(fn($su) => $su->unit?->floor?->name ?? '')
                        ->filter()
                        ->map($formatFloor)
                        ->unique()
                        ->values()
                        ->toArray();
                    if (empty($floorParts)) {
                        $fb = $sale->unit?->floor?->name ?? '';
                        $floorParts = $fb ? [$formatFloor($fb)] : [];
                    }
                    $floorDisplay = implode(', ', $floorParts);

                    // Comma-separated door numbers for all units — parking units get "(Parking)" label
                    $doorParts = $sale->saleUnits
                        ->map(function($su) {
                            $door = trim(explode(',', $su->unit?->door_no ?? '')[0]);
                            if (!$door) return null;
                            $isParking = str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking');
                            return $isParking ? $door . '(Parking)' : $door;
                        })
                        ->filter()
                        ->unique()
                        ->values()
                        ->toArray();
                    if (empty($doorParts)) {
                        $fallbackDoor = $sale->unit?->door_no ?? '';
                        $doorParts = $fallbackDoor ? [trim(explode(',', $fallbackDoor)[0])] : [];
                    }
                    $unitTypeDisplay = implode(', ', $doorParts);

                    // Area (sum of non-parking units)
                    $areaSqft = (float)($nonParkingUnits->sum('area_sqft') ?: $sale->saleUnits->sum('area_sqft'));

                    // Pricing values
                    $expectedRate = (float)($mainUnit?->unit?->expected_rate_per_sqft ?? 0.00);
                    $actualRate = (float)($mainUnit?->rate_per_sqft ?? 0.00);
                    $baseTotal = (float)$sale->saleUnits->filter(fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->sum('base_amount');
                    $variance = ($expectedRate - $actualRate) * $areaSqft;
                    
                    // Taxes & Charges
                    $gstPercentage = (float)($mainUnit?->gst_percentage ?? 0.00);
                    $gstAmount = (float)$sale->saleUnits->sum('gst_amount');
                    $parkingCharges = (float)$sale->saleUnits->filter(fn($su) => str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->sum('base_amount');
                    $additionalWork = (float)$sale->extraWorks->sum('amount');
                    $grandTotalDeal = $baseTotal + $parkingCharges + $gstAmount + $additionalWork;
                    
                    // Track A
                    $totalChequeValue = (float)$sale->total_amount;
                    $chequeReceived = (float)$sale->receipts->sum('amount');
                    $chequeBalanceDue = (float)$sale->remaining_balance;
                    
                    // Receipt details
                    $latestReceipt = $sale->receipts->sortByDesc('receipt_date')->first();
                    $receiptDate = $latestReceipt?->receipt_date?->format('Y-m-d') ?? '';
                    $bookingDate = $sale->sale_date?->format('Y-m-d') ?? '';
                    $agreementDate = $sale->agreement_date?->format('Y-m-d') ?? $sale->sale_date?->format('Y-m-d') ?? '';
                    $installmentsCount = $sale->emi_installment_count ?? '';
                    $collectionPct = $totalChequeValue > 0 ? ($chequeReceived / $totalChequeValue) * 100 : 0.00;
 
                    // Increment totals
                    $totals['area'] += $areaSqft;
                    $totals['base_total'] += $baseTotal;
                    $totals['variance'] += $variance;
                    $totals['gst_amount'] += $gstAmount;
                    $totals['parking'] += $parkingCharges;
                    $totals['additional'] += $additionalWork;
                    $totals['grand_total'] += $grandTotalDeal;
                    $totals['cheque_value'] += $totalChequeValue;
                    $totals['cheque_received'] += $chequeReceived;
                    $totals['cheque_due'] += $chequeBalanceDue;
 
                    // Row Zebra striping
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
 
                    // Conditional highlights for Rate Variance (Discount/Loss = Red, Premium = Green)
                    $varianceStyle = '';
                    if ($variance > 0) {
                        $varianceStyle = 'background-color: #fee2e2; color: #991b1b;';
                    } elseif ($variance < 0) {
                        $varianceStyle = 'background-color: #dcfce7; color: #166534;';
                    }
                    
                    // Conditional highlights for Outstanding Balance (Due > 0 = Red)
                    $balanceStyle = $chequeBalanceDue > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    
                    // Conditional highlights for Collection % (100% = Green, >= 50% = Yellow, < 50% = Red)
                    $pctStyle = '';
                    if ($collectionPct >= 100) {
                        $pctStyle = 'background-color: #dcfce7; color: #166534; font-weight: bold;';
                    } elseif ($collectionPct >= 50) {
                        $pctStyle = 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                    } else {
                        $pctStyle = 'background-color: #fee2e2; color: #991b1b; font-weight: bold;';
                    }
                @endphp
                <tr height="25" style="height: 25pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    {{-- Section 1 --}}
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($sale->customer?->name ?? '') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $bookingDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $floorDisplay }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $unitTypeDisplay }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $agreementDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $areaSqft }}</td>
                    
                    {{-- Section 2 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $expectedRate > 0 ? $expectedRate : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $actualRate > 0 ? $actualRate : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $baseTotal > 0 ? $baseTotal : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; {{ $varianceStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $variance != 0 ? abs($variance) : '0' }}</td>
                    
                    {{-- Section 3 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $gstPercentage > 0 ? ($gstPercentage / 100) : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $gstAmount > 0 ? $gstAmount : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $parkingCharges > 0 ? $parkingCharges : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $additionalWork > 0 ? $additionalWork : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #f1f5f9; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $grandTotalDeal }}</td>
                    
                    {{-- Section 4 --}}
                    <!-- <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #dcfce7; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totalChequeValue }}</td> -->
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $chequeReceived }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $receiptDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $installmentsCount }}</td>
                    <!-- <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; {{ $pctStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $collectionPct / 100 }}</td> -->

                    
                    {{-- Section 5 --}}
                    <!-- <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $balanceStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $chequeBalanceDue }}</td> -->
                </tr>
            @endforeach
 
              <tr height="30" style="height: 30pt; font-weight: bold; color: #ffffff;">
                {{-- Section 1 Totals --}}
                <td colspan="6" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['area'] }}</td>
                {{-- Section 2 Totals --}}
                <td colspan="2" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['base_total'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['variance'] }}</td>
                {{-- Section 3 Totals --}}
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['gst_amount'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['parking'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['additional'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['grand_total'] }}</td>
                {{-- Section 4 Totals --}}
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_received'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                {{-- Section 5 Totals --}}
                <!-- <td bgcolor="#17365D" style="background-color: #17365D; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td> -->
                <!-- <td bgcolor="#17365D" style="background-color: #17365D; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td> -->
                <!-- @php
                    $overallCollectionPct = $totals['cheque_value'] > 0 ? ($totals['cheque_received'] / $totals['cheque_value'] * 100) : 0.00;
                @endphp
                <td bgcolor="#17365D" style="background-color: #17365D; text-align: center; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $overallCollectionPct / 100 }}</td> -->
            </tr>
        </tbody>
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
