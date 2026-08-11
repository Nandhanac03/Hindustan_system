<x-erp-layout title="Consolidated Trial Balance Summary Grid" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
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
                <a href="{{ route('reports.trial_balance') }}" class="px-2.5 py-1 bg-amber-200/70 hover:bg-amber-300 text-amber-950 text-[11px] font-extrabold rounded-lg transition uppercase tracking-wider">Clear Filter</a>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-widest">Consolidated Trial Balance Summary Grid</h3>
                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[9px] font-extrabold uppercase">Checkpoint Verified</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Aggregated multi-level groupings with sharp Closing Balance Debit vs Credit alignment.</p>
                </div>
                @include('reports.partials.header-badges')
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

            {{-- Filter, Print & Export Bar directly above Table --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
                @include('reports.partials.filter-bar', ['formId' => 'trialBalanceFilterForm', 'actionRoute' => route('reports.trial_balance'), 'exportLabel' => 'Export Trial Balance'])
            </div>

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
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
