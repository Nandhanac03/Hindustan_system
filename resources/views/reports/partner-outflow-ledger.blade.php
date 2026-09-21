<x-erp-layout title="Partner Statement Ledger & Capital Outflows">

<div class="max-w-[1800px] mx-auto p-4 sm:p-6 space-y-6 font-sans">

    {{-- Top Header Section Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col xl:flex-row xl:items-center justify-between gap-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight">
                PARTNER STATEMENT LEDGER & CAPITAL OUTFLOWS
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Track capital allocations, profit shares, and mapping of receipt distributions across project partners.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Active Project Selector Badge --}}
            <form action="{{ route('reports.partner_outflow_ledger') }}" method="GET" class="flex items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    <select name="project_id" onchange="this.form.submit()" 
                            class="pl-7 pr-8 py-2 bg-[#1c241b] text-white text-xs font-extrabold rounded-full border border-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 cursor-pointer shadow-sm tracking-wide uppercase">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ (string)$p->id === (string)$selectedProjectId ? 'selected' : '' }}>
                                ACTIVE PROJECT: {{ strtoupper($p->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Total Allocated Outflow Stat Box --}}
            <div class="bg-[#faf9f5] border border-[#a38c29]/40 rounded-2xl px-5 py-2.5 text-right shadow-2xs">
                <div class="text-[9.5px] font-black uppercase tracking-wider text-[#a38c29]">TOTAL ALLOCATED OUTFLOW</div>
                <div class="text-lg sm:text-xl font-black text-slate-900 font-mono mt-0.5">
                    ₹{{ number_format($totalAllocatedOutflow, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row (2 Columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Left Card: Monthly Capital Outflow Trend --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29]"></span>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">MONTHLY CAPITAL OUTFLOW TREND</h3>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Allocated Amounts (₹)</span>
            </div>

            <div class="w-full h-64 relative flex items-center justify-center">
                <div id="monthlyOutflowChart" class="w-full h-full"></div>
            </div>
        </div>

        {{-- Right Card: Partner Outflow Share --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">PARTNER OUTFLOW SHARE</h3>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Distribution</span>
            </div>

            <div class="w-full h-64 relative flex items-center justify-center">
                <div id="partnerShareChart" class="w-full h-full"></div>
            </div>

            {{-- Custom Partner Legend at Bottom --}}
            <div class="flex items-center justify-center gap-6 pt-3 border-t border-slate-100 mt-2">
                @php
                    $colors = ['#a38c29', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'];
                    $colorIdx = 0;
                @endphp
                @foreach($partnerShareData as $partnerName => $amount)
                    @php $c = $colors[$colorIdx % count($colors)]; $colorIdx++; @endphp
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $c }};"></span>
                        <span>{{ $partnerName }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Data Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white text-[10.5px] font-black uppercase tracking-wider text-left border-b border-[#8a7522]">
                        <th class="px-5 py-4 w-44">ALLOCATION DATE</th>
                        <th class="px-5 py-4 w-48">PARTNER ENTITY</th>
                        <th class="px-5 py-4">ASSOCIATED PROJECT</th>
                        <th class="px-5 py-4">DESCRIPTION MEMO</th>
                        <th class="px-5 py-4 text-right w-44">ALLOCATED OUTFLOW</th>
                        <th class="px-5 py-4 text-center w-40">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($outflowList as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors font-semibold">
                            <td class="px-5 py-4 text-slate-700 font-bold whitespace-nowrap">
                                {{ $row->date }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-slate-900 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                    <span>{{ $row->partner_name }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $row->project_name }}
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-600">
                                {{ $row->description }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-black text-rose-600 text-sm whitespace-nowrap">
                                ₹{{ number_format((float)$row->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if(!empty($row->partner_id))
                                    <a href="{{ url('/reports/partner-statements?partner_id=' . $row->partner_id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#8a7522] hover:text-white rounded-xl font-extrabold text-[11px] transition-all duration-150 shadow-2xs group border border-[#a38c29]/30">
                                        <svg class="w-3.5 h-3.5 text-[#a38c29] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Partner Statement</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium italic">
                                No capital outflow allocations found for the selected project.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ApexCharts Script Integration --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Monthly Capital Outflow Trend (Bar Chart)
        const trendCategories = @json(array_keys($monthlyTrendData->toArray()));
        const trendValues = @json(array_values($monthlyTrendData->toArray()));

        const monthlyOptions = {
            series: [{
                name: 'Allocated Outflow',
                data: trendValues.length > 0 ? trendValues : [200000]
            }],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'sans-serif'
            },
            plotOptions: {
                bar: {
                    columnWidth: '35%',
                    borderRadius: 6,
                    dataLabels: { position: 'top' }
                }
            },
            colors: ['#a38c29'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                    if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                    return '₹' + val;
                },
                offsetY: -20,
                style: { fontSize: '11px', colors: ['#8a7522'], fontWeight: 700 }
            },
            xaxis: {
                categories: trendCategories.length > 0 ? trendCategories : ['Aug 2026'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                        if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                        return '₹' + val;
                    },
                    style: { colors: '#94a3b8', fontSize: '10px' }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                    }
                }
            }
        };

        const monthlyChart = new ApexCharts(document.querySelector("#monthlyOutflowChart"), monthlyOptions);
        monthlyChart.render();

        // 2. Partner Outflow Share (Donut Chart)
        const shareLabels = @json(array_keys($partnerShareData->toArray()));
        const shareValues = @json(array_values($partnerShareData->toArray()));
        const totalOutflow = {{ (float)$totalAllocatedOutflow }};
        const totalOutflowFormatted = '₹' + (totalOutflow >= 100000 ? (totalOutflow / 100000).toFixed(1) + 'L' : totalOutflow.toLocaleString());

        const shareOptions = {
            series: shareValues.length > 0 ? shareValues : [115000, 85000],
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'sans-serif'
            },
            labels: shareLabels.length > 0 ? shareLabels : ['Basheer', 'Pavoor'],
            colors: ['#a38c29', '#10b981', '#3b82f6', '#f59e0b'],
            legend: { show: false },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return val.toFixed(1) + '%';
                },
                dropShadow: { enabled: false }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '11px', color: '#64748b', fontWeight: 600 },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 800,
                                color: '#1e293b',
                                formatter: function () {
                                    return totalOutflowFormatted;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Outflow',
                                fontSize: '11px',
                                color: '#64748b',
                                fontWeight: 600,
                                formatter: function () {
                                    return totalOutflowFormatted;
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                    }
                }
            }
        };

        const shareChart = new ApexCharts(document.querySelector("#partnerShareChart"), shareOptions);
        shareChart.render();
    });
</script>

</x-erp-layout>
