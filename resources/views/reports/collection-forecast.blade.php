<x-erp-layout title="Collection Forecast & Overdue Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto p-6 space-y-6" x-data="collectionForecastApp()">

    <!-- Header Section -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Collection Forecast & Overdue Reports</h1>
            <p class="text-sm text-slate-500 mt-1">Ageing analysis of outstanding customer dues with automated reminder generation</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form id="collectionForecastFilterForm" method="GET" action="{{ route('reports.collection_forecast') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">As On Date</label>
                <div class="relative">
                    <input type="date" name="as_of_date" value="{{ request('as_of_date', now()->format('Y-m-d')) }}" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 pl-3 pr-10">
                </div>
            </div>
            
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Project</label>
                <select name="project_id" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10">
                    <option value="">All</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Customer</label>
                <select name="customer_id" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10">
                    <option value="">All</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ageing Bucket</label>
                <select name="ageing_bucket" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10">
                    <option value="All">All</option>
                    <option value="Current" {{ request('ageing_bucket') == 'Current' ? 'selected' : '' }}>Current (Not Due)</option>
                    <option value="0-30" {{ request('ageing_bucket') == '0-30' ? 'selected' : '' }}>0-30 Days</option>
                    <option value="31-60" {{ request('ageing_bucket') == '31-60' ? 'selected' : '' }}>31-60 Days</option>
                    <option value="61-90" {{ request('ageing_bucket') == '61-90' ? 'selected' : '' }}>61-90 Days</option>
                    <option value="91-120" {{ request('ageing_bucket') == '91-120' ? 'selected' : '' }}>91-120 Days</option>
                    <option value="120+" {{ request('ageing_bucket') == '120+' ? 'selected' : '' }}>> 120 Days</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Risk Level</label>
                <select name="risk_level" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10">
                    <option value="All">All</option>
                    <option value="None" {{ request('risk_level') == 'None' ? 'selected' : '' }}>None</option>
                    <option value="Low" {{ request('risk_level') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('risk_level') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('risk_level') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Critical" {{ request('risk_level') == 'Critical' ? 'selected' : '' }}>Critical</option>
                    <option value="Severe" {{ request('risk_level') == 'Severe' ? 'selected' : '' }}>Severe</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Reminder Status</label>
                <select name="reminder_status" class="w-full text-sm rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10">
                    <option value="All">All</option>
                    <option value="Sent" {{ request('reminder_status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                    <option value="Pending" {{ request('reminder_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ request('reminder_status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="flex-none flex gap-2 h-10">
                <button type="submit" class="px-5 py-2 bg-[#3b82f6] text-white text-sm font-medium rounded-lg hover:bg-blue-600 shadow-sm transition-colors">
                    Filter
                </button>
                <a href="{{ route('reports.collection_forecast') }}" class="px-5 py-2 bg-white text-slate-700 border border-slate-200 text-sm font-medium rounded-lg hover:bg-slate-50 shadow-sm transition-colors flex items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Outstanding -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 mb-1">Total Outstanding</div>
                <div class="text-2xl font-bold text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['total_outstanding'], 0)) }}</div>
                <div class="text-xs text-orange-500 font-medium mt-1">{{ $kpis['total_customers'] }} Customers</div>
            </div>
        </div>

        <!-- Total Overdue -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 mb-1">Total Overdue</div>
                <div class="text-2xl font-bold text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['total_overdue'], 0)) }}</div>
                <div class="text-xs text-rose-500 font-medium mt-1">{{ $kpis['overdue_customers'] }} Customers</div>
            </div>
        </div>

        <!-- Current / Not Due -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 mb-1">Current / Not Due</div>
                <div class="text-2xl font-bold text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['current_not_due'], 0)) }}</div>
                <div class="text-xs text-emerald-500 font-medium mt-1">{{ $kpis['total_customers'] - $kpis['overdue_customers'] }} Customers</div>
            </div>
        </div>

        <!-- Expected Collection -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-500 mb-1">Expected Collection (This Month)</div>
                <div class="text-2xl font-bold text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['expected_collection'], 0)) }}</div>
                <a href="#" class="text-xs text-blue-500 font-medium mt-1 hover:underline inline-block">View Forecast</a>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Donut Chart -->
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-6">Ageing Summary (Outstanding)</h3>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8">
                <div id="donutChart" class="w-48 h-48"></div>
                <div class="flex-1 w-full">
                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $colors = ['#4f46e5', '#f59e0b', '#22c55e', '#f97316', '#ec4899'];
                                $idx = 0;
                            @endphp
                            @foreach($chartData['labels'] as $i => $label)
                                @php
                                    $amount = $chartData['amounts'][$i];
                                    $percentage = $kpis['total_overdue'] > 0 ? round(($amount / $kpis['total_overdue']) * 100, 2) : 0;
                                    $color = $colors[$idx % count($colors)];
                                    $idx++;
                                @endphp
                                <tr class="py-2">
                                    <td class="py-2 flex items-center gap-2 text-slate-600">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $color }}"></span>
                                        {{ str_replace('+', ' Days', $label) }}{{ strpos($label, '+') === false ? ' Days' : '' }}
                                    </td>
                                    <td class="py-2 text-right font-medium text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($amount, 0)) }} <span class="text-slate-400 font-normal ml-1">({{ number_format($percentage, 2) }}%)</span></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="py-3 font-bold text-slate-800">Total</td>
                                <td class="py-3 text-right font-bold text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['total_overdue'], 0)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-2">Ageing Distribution</h3>
            <div id="barChart" class="w-full h-64"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="text-base font-bold text-slate-800">Overdue Installments</h3>
            <div class="flex items-center gap-3">
                <form action="{{ route('reports.collection_forecast.reminders') }}" method="POST" onsubmit="return confirm('Generate and send reminders for all overdue items currently shown on this page?');">
                    @csrf
                    @foreach($installmentsPaginated as $inst)
                        @if($inst->days_overdue > 0)
                            <input type="hidden" name="installment_ids[]" value="{{ $inst->id }}">
                        @endif
                    @endforeach
                </form>
                <button class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded shadow-sm hover:bg-slate-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Excel
                </button>
                <button class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded shadow-sm hover:bg-slate-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Export PDF
                </button>
            </div>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Sale No.</th>
                        <th class="px-5 py-3">Project</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3 text-center">Inst. No.</th>
                        <th class="px-5 py-3">Due Date</th>
                        <th class="px-5 py-3 text-right">Outstanding</th>
                        <th class="px-5 py-3 text-center">Days Overdue</th>
                        <th class="px-5 py-3 text-center">Ageing</th>
                        <th class="px-5 py-3 text-center">Risk</th>
                        <th class="px-5 py-3 text-center">Reminder Level</th>
                        <th class="px-5 py-3 text-center">Last Reminder</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($installmentsPaginated as $inst)
                        @php
                            $ageingColorClass = 'text-emerald-500';
                            if($inst->ageing_bucket == '31-60') $ageingColorClass = 'text-amber-500';
                            if($inst->ageing_bucket == '61-90') $ageingColorClass = 'text-orange-500';
                            if($inst->ageing_bucket == '91-120') $ageingColorClass = 'text-rose-500';
                            if($inst->ageing_bucket == '120+') $ageingColorClass = 'text-red-600 font-semibold';
                            if($inst->ageing_bucket == 'Current') $ageingColorClass = 'text-slate-500';

                            $riskColorClass = 'text-emerald-500';
                            if($inst->risk_level == 'Medium') $riskColorClass = 'text-amber-500';
                            if($inst->risk_level == 'High') $riskColorClass = 'text-orange-500';
                            if($inst->risk_level == 'Critical') $riskColorClass = 'text-rose-500 font-semibold';
                            if($inst->risk_level == 'Severe') $riskColorClass = 'text-red-600 font-bold';
                            if($inst->risk_level == 'None') $riskColorClass = 'text-slate-500';
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $inst->sale->customer->name }}</td>
                            <td class="px-5 py-3 text-blue-600 font-medium">{{ $inst->sale->sale_number }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $inst->sale->project->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $inst->sale->unit->door_no }}</td>
                            <td class="px-5 py-3 text-center text-slate-800">{{ $inst->installment_no }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $inst->due_date->format('d-M-Y') }}</td>
                            <td class="px-5 py-3 text-right font-medium text-slate-800">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($inst->calculated_outstanding, 0)) }}</td>
                            <td class="px-5 py-3 text-center font-medium text-slate-800">{{ $inst->days_overdue > 0 ? $inst->days_overdue : '-' }}</td>
                            <td class="px-5 py-3 text-center font-medium {{ $ageingColorClass }}">
                                {{ str_replace('+', ' > 120', $inst->ageing_bucket) }}
                            </td>
                            <td class="px-5 py-3 text-center {{ $riskColorClass }}">
                                {{ $inst->risk_level }}
                            </td>
                            <td class="px-5 py-3 text-center text-slate-800 font-medium">
                                {{ $inst->last_reminder ? $inst->last_reminder->reminder_level : ($inst->days_overdue > 0 ? $inst->suggested_reminder_level : '-') }}
                            </td>
                            <td class="px-5 py-3 text-center text-slate-600">
                                {{ $inst->last_reminder ? $inst->last_reminder->created_at->format('d-M-Y') : '-' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="#" class="text-blue-500 hover:text-blue-700" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                    <a href="#" class="text-blue-500 hover:text-blue-700" title="Message"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></a>
                                    <a href="#" class="text-blue-500 hover:text-blue-700" title="Call"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-5 py-8 text-center text-slate-500 italic">No installments found for the given criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 bg-white flex justify-between items-center text-xs text-slate-500">
            <div>
                Showing {{ $installmentsPaginated->firstItem() ?? 0 }} to {{ $installmentsPaginated->lastItem() ?? 0 }} of {{ $installmentsPaginated->total() }} entries
            </div>
            <div>
                {{ $installmentsPaginated->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

</div>

<script>
function collectionForecastApp() {
    return {
        init() {
            this.renderDonutChart();
            this.renderBarChart();
        },
        renderDonutChart() {
            const chartData = @json($chartData);
            const dataAmounts = chartData.amounts;
            
            // Format labels for chart
            const labels = chartData.labels.map(l => l.replace('+', ' > 120'));
            
            if (dataAmounts.every(item => item === 0)) {
                document.getElementById('donutChart').innerHTML = '<div class="flex items-center justify-center h-full text-xs text-slate-400">No data available</div>';
                return;
            }

            const options = {
                series: dataAmounts,
                labels: labels,
                chart: {
                    type: 'donut',
                    height: 200,
                    fontFamily: 'inherit',
                },
                colors: chartData.colors,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%',
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(0) + "%"
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                stroke: {
                    show: true,
                    colors: '#ffffff',
                    width: 2
                },
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#donutChart"), options);
            chart.render();
        },
        renderBarChart() {
            const chartData = @json($chartData);
            const dataAmounts = chartData.amounts;
            const labels = chartData.labels.map(l => l.replace('+', ' > 120'));

            if (dataAmounts.every(item => item === 0)) {
                document.getElementById('barChart').innerHTML = '<div class="flex items-center justify-center h-full text-xs text-slate-400">No data available</div>';
                return;
            }

            const options = {
                series: [{
                    name: 'Amount',
                    data: dataAmounts
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit',
                },
                colors: ['#3b82f6'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '40%',
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return '₹ ' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(val);
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '10px',
                        colors: ["#475569"]
                    }
                },
                xaxis: {
                    categories: labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            if (val >= 100000) {
                                return (val / 100000).toFixed(0) + "L";
                            }
                            return val;
                        },
                        style: {
                            colors: '#64748b',
                            fontSize: '11px'
                        }
                    },
                    title: {
                        text: 'Amount (₹)',
                        style: {
                            color: '#64748b',
                            fontSize: '11px',
                            fontWeight: 500
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return '₹ ' + new Intl.NumberFormat('en-IN').format(value);
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#barChart"), options);
            chart.render();
        }
    }
}
</script>

</x-erp-layout>
