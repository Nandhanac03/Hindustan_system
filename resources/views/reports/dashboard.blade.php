<x-erp-layout title="Executive Dashboard Analytics & Profitability" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
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
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
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
                <div class="bg-white border border-slate-200/80 border-l-4 border-l-slate-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Projects</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['total_projects'] }}</span>
                </div>

                <div class="bg-white border border-slate-200/80 border-l-4 border-l-blue-600 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Units (Sold / Total)</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block">{{ $dashboardData['sold_units'] }} <span class="text-slate-400 text-lg">/ {{ $dashboardData['total_units'] }}</span></span>
                </div>

                <div class="bg-white border border-slate-200/80 border-l-4 border-l-emerald-500 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Collections</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-emerald-700 font-mono tracking-tight block">₹{{ number_format($dashboardData['collections'], 0) }}</span>
                </div>

                <div class="bg-white border border-slate-200/80 border-l-4 border-l-amber-500 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Outstanding Receivable</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">₹{{ number_format($dashboardData['outstanding'], 0) }}</span>
                </div>

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

            {{-- Dashboard Charts --}}
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
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-4 py-3.5 text-white font-extrabold rounded-tl-2xl">Project</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Expected Revenue</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Actual Revenue</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Partner Payouts</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Brokerage</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Material Costs</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Contractor Payments</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Total Cost</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold">Net Profit</th>
                                <th class="px-4 py-3.5 text-right text-white font-extrabold rounded-tr-2xl">Margin %</th>
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
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
