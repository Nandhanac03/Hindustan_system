@extends('layouts.erp')

@section('title', 'Revenue vs. Cost Breakdown')

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
                <span class="text-[#a38c29] font-bold">REVENUE VS. COST BREAKDOWN</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Revenue Streams vs. Cost Breakdown</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Comparative Inflows & Outflows</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Breakdown</span>
            </button>
        </div>
    </div>

    <!-- ── FILTER BAR ── -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('reports.revenue_cost_breakdown') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap">Select Project:</label>
                <select name="project_id" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none min-w-[240px]">
                    <option value="">All Projects Combined</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            @if(request()->filled('project_id'))
                <a href="{{ route('reports.revenue_cost_breakdown') }}" class="text-xs font-bold text-rose-600 hover:text-rose-800">Clear Filter ✕</a>
            @endif
        </form>
    </div>

    <!-- ── EXECUTIVE COMPARATIVE CARDS ── -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 text-white p-6 rounded-2xl shadow-md border border-emerald-400/30">
            <span class="text-xs font-extrabold uppercase tracking-wider opacity-90 block">TOTAL REVENUE (INFLOWS)</span>
            <div class="text-2xl font-mono font-black mt-2">₹{{ number_format($totalRevenue, 2) }}</div>
            <p class="text-[11px] opacity-80 mt-1">Booked Unit Sales + Extra Upgrades</p>
        </div>

        <div class="bg-gradient-to-br from-rose-500 to-rose-700 text-white p-6 rounded-2xl shadow-md border border-rose-400/30">
            <span class="text-xs font-extrabold uppercase tracking-wider opacity-90 block">TOTAL EXPENDITURE (OUTFLOWS)</span>
            <div class="text-2xl font-mono font-black mt-2">₹{{ number_format($totalCosts, 2) }}</div>
            <p class="text-[11px] opacity-80 mt-1">Materials + Contractors + Brokerage + Admin</p>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-slate-900 text-white p-6 rounded-2xl shadow-md border border-blue-400/30">
            <span class="text-xs font-extrabold uppercase tracking-wider opacity-90 block">NET CASH SURPLUS / MARGIN</span>
            <div class="text-2xl font-mono font-black mt-2">₹{{ number_format($netSurplus, 2) }}</div>
            <p class="text-[11px] opacity-80 mt-1">{{ $totalRevenue > 0 ? number_format(($netSurplus / $totalRevenue) * 100, 1) . '% Net Operational Margin' : '0% Margin' }}</p>
        </div>
    </div>

    <!-- ── TWO COLUMN COMPARATIVE SIDE-BY-SIDE TABLES ── -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- REVENUE HEADS -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-emerald-50/50 flex items-center justify-between">
                <span class="text-xs font-black text-emerald-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>REVENUE SOURCES & INFLOWS</span>
                </span>
                <span class="text-xs font-mono font-bold text-emerald-700">₹{{ number_format($totalRevenue, 2) }}</span>
            </div>

            <div class="p-4 space-y-4">
                @foreach($revenueBreakdown as $head => $amount)
                    @php
                        $pct = $totalRevenue > 0 ? ($amount / $totalRevenue) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                            <span>{{ $head }}</span>
                            <span class="font-mono text-emerald-800">₹{{ number_format($amount, 2) }} ({{ number_format($pct, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- COST HEADS -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-rose-50/50 flex items-center justify-between">
                <span class="text-xs font-black text-rose-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                    <span>EXPENDITURE & COST HEADS</span>
                </span>
                <span class="text-xs font-mono font-bold text-rose-700">₹{{ number_format($totalCosts, 2) }}</span>
            </div>

            <div class="p-4 space-y-4">
                @foreach($costBreakdown as $head => $amount)
                    @php
                        $pct = $totalCosts > 0 ? ($amount / $totalCosts) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                            <span>{{ $head }}</span>
                            <span class="font-mono text-rose-800">₹{{ number_format($amount, 2) }} ({{ number_format($pct, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
