@extends('layouts.erp')

@section('title', 'Project Margin Analysis')

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
                <span class="text-[#a38c29] font-bold">PROJECT MARGIN ANALYSIS</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <span>Project Gross & Net Margin Analysis</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Profitability & Risk Metrics</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Analysis</span>
            </button>
        </div>
    </div>

    <!-- ── MARGIN MATRIX REGISTER TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Project Profitability & Cost Per Sq.Ft. Matrix</span>
            <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">{{ $marginAnalysis->count() }} Active Projects</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest sticky top-0 z-10 shadow-xs">
                    <tr>
                        <th class="px-3 py-3.5 text-left text-white font-extrabold">PROJECT</th>
                        <th class="px-3 py-3.5 text-center text-white font-extrabold">UNITS</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">TOTAL AREA (SQ.FT)</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">REVENUE / SQ.FT</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">COST / SQ.FT</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">ACTUAL REVENUE</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">TOTAL COSTS</th>
                        <th class="px-3 py-3.5 text-right text-white font-extrabold">GROSS PROFIT</th>
                        <th class="px-3 py-3.5 text-center text-white font-extrabold">NET MARGIN %</th>
                        <th class="px-3 py-3.5 text-center text-white font-extrabold">HEALTH STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    @forelse($marginAnalysis as $row)
                        <tr class="hover:bg-amber-50/20 transition-colors">
                            <td class="px-3 py-3 font-bold text-slate-900">{{ $row->project_name }}</td>
                            <td class="px-3 py-3 text-center font-bold text-slate-700">{{ number_format($row->total_units) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-slate-700">{{ number_format($row->total_area, 0) }} sq.ft</td>
                            <td class="px-3 py-3 text-right font-mono text-blue-800">₹{{ number_format($row->rev_per_sqft, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-rose-800">₹{{ number_format($row->cost_per_sqft, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono font-bold text-blue-900">₹{{ number_format($row->actual_revenue, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono text-slate-700">₹{{ number_format($row->total_costs, 2) }}</td>
                            <td class="px-3 py-3 text-right font-mono font-black text-emerald-800">₹{{ number_format($row->gross_profit, 2) }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $row->net_margin >= 20 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($row->net_margin >= 10 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-rose-100 text-rose-800 border border-rose-300') }}">
                                    {{ number_format($row->net_margin, 1) }}%
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($row->health_status === 'High Margin')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200">HIGH MARGIN</span>
                                @elseif($row->health_status === 'Healthy')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-50 text-blue-800 border border-blue-200">HEALTHY</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-800 border border-rose-200">AT RISK</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">No active projects found for margin analysis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
