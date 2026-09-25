<x-erp-layout title="Partner Cash Book Analytics" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    {{-- Under Construction / Work In Progress Notice Banner --}}
    <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-4 md:p-5 shadow-sm relative overflow-hidden backdrop-blur-sm">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-md text-xl">
                🚧
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Work In Progress
                    </span>
                </div>
                <h2 class="text-sm md:text-base font-extrabold text-red-950 mt-0.5">
                    We're working on this module. It is not yet ready for use and will be released shortly.
                </h2>
            </div>
        </div>
    </div>

    {{-- 1. Executive Header Card --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/90 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            {{-- Left: Architectural Emblem, Breadcrumbs, Title & Active Badges --}}
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29]/20 via-[#a38c29]/10 to-[#8a7522]/5 text-[#8a7522] flex items-center justify-center shrink-0 border border-[#a38c29]/30 shadow-xs">
                    <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <div>
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 mb-1 flex-wrap">
                        <a href="{{ route('dashboard') }}" class="hover:text-slate-700 transition flex items-center gap-1">
                            <span>Finance & Analytics</span>
                        </a>
                        <span class="text-slate-300">/</span>
                        <a href="{{ route('reports.cash_book') }}" class="hover:text-slate-700 transition">Reports & Analytics</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-[#a38c29] font-bold">Partner Cash Book</span>
                    </div>

                    {{-- Title + Active Project Badge --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Partner Cash Book Analytics</h1>
                        @include('reports.partials.header-badges')
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time collection register with partner-wise breakdown and trend analytics.</p>
                </div>
            </div>

            {{-- Right: Partner Filter Pill Tabs --}}
            <div class="flex items-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl border border-slate-200/80 flex-wrap">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-2.5">Partner:</span>
                <a href="{{ route('reports.cash_book', array_merge(request()->query(), ['partner_id' => ''])) }}"
                   class="px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-xl transition-all
                          {{ !request('partner_id') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                    All Partners
                </a>
                @foreach($partners as $pt)
                <a href="{{ route('reports.cash_book', array_merge(request()->query(), ['partner_id' => $pt->id])) }}"
                   class="px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wider rounded-xl transition-all
                          {{ request('partner_id') == $pt->id ? 'bg-[#a38c29] text-white shadow-sm' : 'text-slate-600 hover:text-[#a38c29] hover:bg-white' }}">
                    {{ $pt->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 2. KPI Summary Cards (Clean, Simple & Professional Box Design matching Treasury Report) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Total Received --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Received</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-2xs shrink-0">
                    All Modes
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300 whitespace-nowrap">
                    ₹{{ number_format($cashBookStats['total_received'] ?? 0, 0) }}
                </span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Total collection across all payment modes</p>
            </div>
        </div>

        {{-- Card 2: Cash in Hand --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Cash in Hand</span>
                </div>
                <span class="text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider shadow-2xs shrink-0">
                    Physical Cash
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-blue-600 font-mono tracking-tight block group-hover:text-blue-700 transition-colors duration-300 whitespace-nowrap">
                    ₹{{ number_format($cashBookStats['cash_received'] ?? 0, 0) }}
                </span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Physical site & office cash collection</p>
            </div>
        </div>

        {{-- Card 3: Bank / Digital --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-indigo-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/60 transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Bank / Digital</span>
                </div>
                <span class="text-[9px] text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 uppercase tracking-wider shadow-2xs shrink-0">
                    Cheque / Online
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-indigo-600 font-mono tracking-tight block group-hover:text-indigo-700 transition-colors duration-300 whitespace-nowrap">
                    ₹{{ number_format($cashBookStats['bank_received'] ?? 0, 0) }}
                </span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Bank · Cheque · RTGS · Online</p>
            </div>
        </div>

        {{-- Card 4: Pending Balance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Pending Balance</span>
                </div>
                <span class="text-[9px] text-[#8a7522] font-bold bg-[#a38c29]/10 px-2 py-0.5 rounded-md border border-[#a38c29]/30 uppercase tracking-wider shadow-2xs shrink-0">
                    Receivables
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 group-hover:text-[#a38c29] font-mono tracking-tight block transition-colors duration-300 whitespace-nowrap">
                    ₹{{ number_format($cashBookStats['pending_balance'] ?? 0, 0) }}
                </span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium truncate">Outstanding balance from booked units</p>
            </div>
        </div>

    </div>

    {{-- 3. Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Monthly Cash Collections</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Last 12 months collection overview</p>
                </div>
                <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 tracking-wider">INFLOW</span>
            </div>
            <div id="cbMonthlyChart" class="w-full" style="height:250px;"></div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Payment Mode Mix</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Cash · Bank · UPI · Cheque</p>
                </div>
            </div>
            <div id="cbPaymentModeChart" class="w-full flex-1" style="height:250px;"></div>
        </div>
    </div>

    {{-- 4. Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Daily Collection Trend</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Last 30 days collection velocity</p>
                </div>
                <span class="text-[10px] font-extrabold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200 tracking-wider">TREND</span>
            </div>
            <div id="cbDailyTrendChart" class="w-full" style="height:250px;"></div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Partner-wise Collections</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Distribution across partners</p>
                </div>
            </div>
            <div id="cbPartnerDonutChart" class="w-full flex-1" style="height:250px;"></div>
        </div>
    </div>

    @if(isset($cashBookChartData['partner_wise']) && $cashBookChartData['partner_wise']->count() > 1)
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Partner Collection Comparison</h4>
                <p class="text-[11px] text-slate-400 mt-0.5">Total amount received per partner</p>
            </div>
        </div>
        <div id="cbPartnerBarChart" class="w-full" style="height:200px;"></div>
    </div>
    @endif

    {{-- 5. Filter, Print & Export Bar --}}
    <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm relative z-40">
        @include('reports.partials.filter-bar', ['formId' => 'cashBookFilterForm', 'actionRoute' => route('reports.cash_book'), 'exportLabel' => 'Export Cash Book'])
    </div>

    {{-- 6. Transaction Table Card --}}
    <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4.5 border-b border-slate-100 bg-slate-50/60">
            <div>
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Recent Cash Book Entries</h4>
                <p class="text-[11px] text-slate-400 mt-0.5">Itemized transaction receipt audit trail</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-slate-600 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-2xs font-mono">{{ $cashBookEntries->total() }} records</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="reportsTable" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-white font-extrabold">Date</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Voucher #</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Customer / Unit</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Partner</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Mode</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Bank Ref</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Amount</th>
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
        <div class="px-5 py-4 border-t border-slate-100 bg-white">
            {{ $cashBookEntries->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="cashBookExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            <col width="120" style="width: 90pt;" />   {{-- Date --}}
            <col width="140" style="width: 105pt;" />  {{-- Voucher No --}}
            <col width="260" style="width: 195pt;" />  {{-- Customer / Unit --}}
            <col width="160" style="width: 120pt;" />  {{-- Partner --}}
            <col width="120" style="width: 90pt;" />   {{-- Mode --}}
            <col width="140" style="width: 105pt;" />  {{-- Bank Ref --}}
            <col width="180" style="width: 135pt;" />  {{-- Amount --}}
        </colgroup>
        <thead>
            {{-- Empty Spacer Row Top --}}
            <tr height="20" style="height: 20pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    HINDUSTAN ERP : PARTNER CASH BOOK ANALYTICS
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#007398" style="background-color: #007398; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    Cash & Bank Collection Register
                </th>
            </tr>
            <tr height="25" style="height: 25pt;">
                <th colspan="7" bgcolor="#006039" style="background-color: #006039; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">
                    TRANSACTION DETAILS
                </th>
            </tr>
            {{-- Empty Spacer Row Middle --}}
            <tr height="15" style="height: 15pt;" data-no-border="true">
                <th colspan="7" style="background-color: #ffffff; border: none;"></th>
            </tr>
            <tr height="30" style="height: 30pt;">
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Date</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Voucher No</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px;">Customer / Unit</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Partner</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Mode</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">Bank Ref</th>
                <th bgcolor="#34495E" style="background-color: #34495E; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach($cashBookEntries as $index => $cash)
                @php 
                    $totalAmount += (float)$cash->amount;
                    $bgColor = $loop->iteration % 2 == 0 ? '#FFFFFF' : '#F0F8FF';
                    
                    $partnerText = 'Project Intake';
                    if ($cash->partner) {
                        $partnerText = $cash->partner->name;
                    } elseif (request('partner_id')) {
                        $partnerText = 'Partner Share';
                    }

                    $customerText = ($cash->customer?->name ?? '—') . ' (' . ($cash->sale?->project?->name ?? '') . ' - ' . ($cash->sale?->unit?->door_no ?? '') . ')';
                @endphp
                <tr height="25" style="height: 25pt;">
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; mso-number-format:'yyyy-mm-dd'; color: #000000;">{{ $cash->receipt_date?->format('Y-m-d') }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">REC-{{ sprintf("%05d", $cash->id) }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: left; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-left: 8px; font-weight: bold; color: #000000;">{{ $customerText }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $partnerText }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; font-weight: bold; color: #000000;">{{ $cash->payment_mode }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: center; vertical-align: middle; border: 0.5pt solid #cbd5e1; color: #000000;">{{ $cash->reference_no ?? '—' }}</td>
                    <td bgcolor="{{ $bgColor }}" style="background-color: {{ $bgColor }}; text-align: right; vertical-align: middle; border: 0.5pt solid #cbd5e1; padding-right: 8px; font-weight: bold; mso-number-format:'\#\,\#\#0\.00'; color: #008000;">{{ $cash->amount }}</td>
                </tr>
            @endforeach
        </tbody>
        @if(count($cashBookEntries) > 0)
        <tfoot>
            <tr height="30" style="height: 30pt;">
                <td colspan="6" bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #475569; padding-left: 8px; font-size: 11pt;">TOTAL CASH BOOK TRANSACTIONS</td>
                <td bgcolor="#2C3E50" style="background-color: #2C3E50; color: #ffffff; font-weight: bold; text-align: right; vertical-align: middle; border: 1px solid #475569; padding-right: 8px; font-size: 11pt; mso-number-format:'\#\,\#\#0\.00';">{{ $totalAmount }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
