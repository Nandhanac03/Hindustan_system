<x-erp-layout title="Bank Loan EMI Schedules & Repayments" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Header & Summary Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Bank Loan EMI Schedules & Repayments</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track project construction loan repayments, principal amortization, and monthly interest dues.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @include('reports.partials.header-badges')
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

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'loanSchedulesFilterForm', 'actionRoute' => route('reports.loan_schedules'), 'exportLabel' => 'Export Loan Schedule'])
            </div>

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
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="loanScheduleExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="140" style="width: 105pt;" />  {{-- Due Date --}}
            <col width="260" style="width: 195pt;" />  {{-- Bank / Project --}}
            <col width="160" style="width: 120pt;" />  {{-- EMI Installment --}}
            <col width="160" style="width: 120pt;" />  {{-- Principal --}}
            <col width="160" style="width: 120pt;" />  {{-- Interest --}}
            <col width="160" style="width: 120pt;" />  {{-- EMI Amount --}}
            <col width="120" style="width: 90pt;" />   {{-- Status --}}
        </colgroup>
        <thead>
            {{-- Empty Spacer Row Top --}}
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    HINDUSTAN ERP : BANK LOAN EMI SCHEDULES
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#007398" style="background-color: #007398; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    Loan Repayment Tracking & Audits
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#006039" style="background-color: #006039; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    INSTALLMENT DETAILS
                </th>
            </tr>
            {{-- Empty Spacer Row Middle --}}
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Due Date</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px;">Bank / Project</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">EMI Installment</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Principal (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Interest (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">EMI Amount (₹)</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalPrincipal = 0;
                $totalInterest = 0;
                $totalEmi = 0;
            @endphp
            @foreach($loanSchedules as $index => $sch)
                @php 
                    $totalPrincipal += (float)$sch->principal_component;
                    $totalInterest += (float)$sch->interest_component;
                    $totalEmi += (float)$sch->emi_amount;
                    $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F0F8FF'; // Light blue for zebra striping
                    $bankDetails = ($sch->loan?->lender_name ?? '—') . ' (Project: ' . ($sch->loan?->project?->name ?? '—') . ')';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'yyyy-mm-dd'; color: #000000;">{{ $sch->due_date?->format('Y-m-d') }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: left; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-left: 8px; font-weight: bold; color: #000000;">{{ $bankDetails }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">EMI #{{ $sch->installment_no }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $sch->principal_component }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #000000;">{{ $sch->interest_component }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #00008B;">{{ $sch->emi_amount }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: {{ $sch->status == 'Paid' ? '#008000' : ($sch->status == 'Overdue' ? '#FF0000' : '#DAA520') }};">{{ $sch->status }}</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($loanSchedules) > 0)
        <tfoot>
            <tr height="30" style="height: 30pt;">
                <td colspan="3" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px; font-size: 11pt;">TOTAL SCHEDULE SUMMARY</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalPrincipal }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalInterest }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalEmi }}</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; border: 1px solid #475569;"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
