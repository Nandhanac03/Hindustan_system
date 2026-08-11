<x-erp-layout title="Partner Cash Book Analytics" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6" id="cashBookDashboard">

            {{-- Section Header with partner context --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Partner Cash Book Analytics</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Real-time collection register with partner-wise breakdown and trend analytics.</p>
                    </div>
                    @include('reports.partials.header-badges')
                </div>
                {{-- Partner Quick-filter pill tabs --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('reports.cash_book', array_merge(request()->query(), ['partner_id' => ''])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ !request('partner_id') ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'border-slate-200 text-slate-500 hover:border-slate-400 hover:text-slate-800' }}">
                        All Partners
                    </a>
                    @foreach($partners as $pt)
                    <a href="{{ route('reports.cash_book', array_merge(request()->query(), ['partner_id' => $pt->id])) }}"
                       class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-full border transition-all
                              {{ request('partner_id') == $pt->id ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-md' : 'border-slate-200 text-slate-500 hover:border-[#a38c29] hover:text-[#a38c29]' }}">
                        {{ $pt->name }}
                    </a>
                    @endforeach
                </div>
            </div>



            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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

            {{-- Charts Row 1 --}}
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

            {{-- Charts Row 2 --}}
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

            @if(isset($cashBookChartData['partner_wise']) && $cashBookChartData['partner_wise']->count() > 1)
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

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'cashBookFilterForm', 'actionRoute' => route('reports.cash_book'), 'exportLabel' => 'Export Cash Book'])
            </div>

            {{-- Transaction Table --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h4 class="text-[10px] font-extrabold text-slate-700 uppercase tracking-widest">Recent Cash Book Entries</h4>
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] text-slate-400 font-mono">{{ $cashBookEntries->total() }} records</span>
                    </div>
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
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
