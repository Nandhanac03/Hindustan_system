<x-erp-layout title="Unit Exchange Report" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Unit Exchange Report</h3>
                @include('reports.partials.header-badges')
            </div>



            <div id="unitExchangesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'exchangeFilterForm', 'actionRoute' => route('reports.exchange_report'), 'exportLabel' => 'Export Exchanges'])
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                            <th class="px-5 py-3.5 text-white font-extrabold">Exchange Date</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Customer Name</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Transferred Unit</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Equity Applied(Paid Amount)</th>
                            <th class="px-5 py-3.5 text-right text-white font-extrabold">Contract Value</th>
                            <th class="px-5 py-3.5 text-white font-extrabold">Status</th>
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
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="exchangeExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="140" style="width: 105pt;" />  {{-- Exchange Date --}}
            <col width="260" style="width: 195pt;" />  {{-- Customer Name --}}
            <col width="300" style="width: 225pt;" />  {{-- Transferred Unit --}}
            <col width="180" style="width: 135pt;" />  {{-- Equity Applied --}}
            <col width="180" style="width: 135pt;" />  {{-- Contract Value --}}
            <col width="120" style="width: 90pt;" />   {{-- Status --}}
        </colgroup>
        <thead>
            {{-- Empty Spacer Row Top --}}
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="6" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="6" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    HINDUSTAN ERP : UNIT EXCHANGE REPORT
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="6" bgcolor="#007398" style="background-color: #007398; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    Unit Transfer & Exchange Audit
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="6" bgcolor="#006039" style="background-color: #006039; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    TRANSACTION DETAILS
                </th>
            </tr>
            {{-- Empty Spacer Row Middle --}}
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="6" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Exchange Date</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px;">Customer Name</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px;">Transferred Unit</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Equity Applied (Paid) (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Contract Value (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalEquity = 0;
                $totalContract = 0;
            @endphp
            @foreach($exchangeEntries as $index => $row)
                @php 
                    $totalEquity += (float)$row->transferred_equity;
                    $totalContract += (float)$row->total_amount;
                    $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F0F8FF'; // Light blue for zebra striping
                    $unitDetails = ($row->project?->name ?? '—') . ' (Old Unit: ' . ($row->unit?->formatted_name ?? '—') . ')';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'yyyy-mm-dd'; color: #000000;">{{ $row->sale_date?->format('Y-m-d') }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: left; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-left: 8px; font-weight: bold; color: #000000;">{{ $row->customer?->name ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: left; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-left: 8px; color: #000000;">{{ $unitDetails }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ $row->transferred_equity }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $row->total_amount }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #1d4ed8;">Exchanged</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($exchangeEntries) > 0)
        <tfoot>
            <tr height="30" style="height: 30pt;">
                <td colspan="3" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px; font-size: 11pt;">TOTAL EXCHANGE EQUITY</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalEquity }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalContract }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; border: 1px solid #475569;"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
