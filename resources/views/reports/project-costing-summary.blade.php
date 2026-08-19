@extends('layouts.erp')

@section('title', 'Project Costing Summary')

@section('content')
<div class="p-6 space-y-6 bg-slate-50 min-h-screen">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>FINANCE & ANALYTICS</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">PROJECT COSTING SUMMARY</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <span>Project Costing & Expenditure Summary</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Financial Analytics</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Report</span>
            </button>
        </div>
    </div>

    <!-- ── KPI METRICS BAR ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-slate-800 shadow-xs">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">EXPECTED REVENUE</span>
            <div class="text-xl font-mono font-black text-slate-900 mt-1">₹{{ number_format((float) $totals->expected_revenue, 2) }}</div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Total Projected Valuation</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-600 shadow-xs">
            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">ACTUAL SALES AGREED</span>
            <div class="text-xl font-mono font-black text-blue-900 mt-1">₹{{ number_format((float) $totals->actual_revenue, 2) }}</div>
            <div class="text-[10px] text-blue-600 font-semibold mt-1">Booked Sales Contracted</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-rose-500 shadow-xs">
            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider block">TOTAL INCURRED COSTS</span>
            <div class="text-xl font-mono font-black text-rose-900 mt-1">₹{{ number_format((float) $totals->total_cost, 2) }}</div>
            <div class="text-[10px] text-rose-600 font-semibold mt-1">Materials + Contractors + Expenses</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">PROJECT NET SURPLUS</span>
            <div class="text-xl font-mono font-black text-emerald-900 mt-1">₹{{ number_format((float) $totals->net_profit, 2) }}</div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-1">Net Realized Profitability</div>
        </div>
    </div>

    <!-- ── FILTER BAR ── -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('reports.project_costing_summary') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Filter Project:</label>
                <select name="project_id" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none min-w-[240px]">
                    <option value="">All Projects Summary</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(request()->filled('project_id'))
                <a href="{{ route('reports.project_costing_summary') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800">Clear Filter ✕</a>
            @endif
        </form>
    </div>

    <!-- ── PROJECT COSTING REGISTER TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Project-Wise Cost & Expenditure Breakdown</span>
            <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ $costingSummary->count() }} Projects</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest sticky top-0 z-10 shadow-xs">
                    <tr>
                        <th class="px-3 py-3.5 text-left text-white font-extrabold">PROJECT</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">ACTUAL REVENUE</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">MATERIAL COSTS</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">CONTRACTOR RA BILLS</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">BROKERAGE</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">PARTNER PAYOUTS</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">SITE EXPENSES</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold bg-[#8a7522]/40">TOTAL COST</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">NET SURPLUS</th>
                        <th class="px-3 py-3.5 text-center text-white font-extrabold">MARGIN %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    @forelse($costingSummary as $row)
                        <tr class="hover:bg-amber-50/20 transition-colors">
                            <td class="px-3 py-3 font-bold text-slate-900">
                                <div>{{ $row->project_name }}</div>
                                <div class="text-[9.5px] text-slate-400 font-mono">{{ $row->code }}</div>
                            </td>
                            <td class="px-3 py-3 text-right font-mono font-bold text-blue-900">₹{{ number_format($row->actual_revenue, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-slate-700">₹{{ number_format($row->material_costs, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-amber-700">₹{{ number_format($row->contractor_bills, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-purple-700">₹{{ number_format($row->brokerage_costs, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-indigo-700">₹{{ number_format($row->partner_payouts, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-slate-600">₹{{ number_format($row->site_expenses, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono font-black text-rose-900 bg-rose-50/30">₹{{ number_format($row->total_cost, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono font-black text-emerald-800">₹{{ number_format($row->net_profit, 2) }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $row->profit_margin >= 20 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ number_format($row->profit_margin, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">No active projects found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-100 font-bold border-t-2 border-slate-300 text-xs">
                    <tr>
                        <td class="px-3 py-3 uppercase">TOTALS</td>
                        <td class="px-3 py-3 text-right font-mono font-black text-blue-900">₹{{ number_format($totals->actual_revenue, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono">₹{{ number_format($totals->material_costs, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono text-amber-700">₹{{ number_format($totals->contractor_bills, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono text-purple-700">₹{{ number_format($totals->brokerage_costs, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono text-indigo-700">₹{{ number_format($totals->partner_payouts, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono text-slate-600">₹{{ number_format($totals->site_expenses, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono font-black text-rose-900">₹{{ number_format($totals->total_cost, 2) }}</td>
                        <td class="px-3 py-3 text-right font-mono font-black text-emerald-800">₹{{ number_format($totals->net_profit, 2) }}</td>
                        <td class="px-3 py-3 text-center">—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
