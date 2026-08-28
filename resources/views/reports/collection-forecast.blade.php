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
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm mb-6">
        <form id="collectionForecastFilterForm" method="GET" action="{{ route('reports.collection_forecast') }}" class="flex flex-wrap items-end gap-5">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">As On Date</label>
                <div class="relative">
                    <input type="date" name="as_of_date" value="{{ request('as_of_date') }}" onchange="this.form.submit()" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors">
                </div>
            </div>
            
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Project</label>
                <select name="project_id" onchange="this.form.submit()" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors">
                    <option value="">All</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[220px]" x-data="{ open: false, search: '', selectedId: '{{ request('customer_id') }}', selectedName: '{{ request('customer_id') ? addslashes($customers->firstWhere('id', request('customer_id'))->name ?? '') : '' }}' }" @click.outside="open = false">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Customer</label>
                <div class="relative">
                    <input type="hidden" name="customer_id" :value="selectedId">
                    <button type="button" 
                            @click="open = !open; if(open) $nextTick(() => $refs.customerSearch.focus())"
                            class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors flex items-center justify-between shadow-sm">
                        <span x-text="selectedId ? selectedName : 'All Customers'" :class="!selectedId ? 'text-slate-500' : ''" class="truncate pr-2"></span>
                        <div class="flex items-center gap-1 shrink-0 ml-1">
                            <span x-show="selectedId" @click.stop="selectedId = ''; selectedName = ''; document.getElementById('collectionForecastFilterForm').submit()" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-200 transition" title="Clear customer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute left-0 top-full mt-1.5 w-72 bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                         style="display: none;">
                         
                         <div class="p-2 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                             <div class="relative">
                                 <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                 <input type="text"
                                        x-model="search"
                                        x-ref="customerSearch"
                                        placeholder="Search customer..."
                                        @keydown.escape="open = false"
                                        class="w-full pl-8 pr-7 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                 <button x-show="search" type="button" @click="search = ''; $refs.customerSearch.focus()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                             </div>
                         </div>
                         
                         <div class="overflow-y-auto p-1.5 space-y-0.5 custom-scrollbar">
                             <button type="button"
                                     @click="selectedId = ''; selectedName = ''; open = false; document.getElementById('collectionForecastFilterForm').submit()"
                                     x-show="search === ''"
                                     class="w-full flex flex-col px-3 py-2 rounded-lg text-left transition-colors hover:bg-slate-50 text-xs text-slate-500 font-bold">
                                 All Customers
                             </button>
                             @foreach($customers as $customer)
                                 <button type="button"
                                         x-show="'{{ strtolower(addslashes($customer->name)) }}'.includes(search.toLowerCase())"
                                         @click="selectedId = '{{ $customer->id }}'; selectedName = '{{ addslashes($customer->name) }}'; open = false; document.getElementById('collectionForecastFilterForm').submit()"
                                         class="w-full flex flex-col px-3 py-2 rounded-lg text-left transition-colors hover:bg-slate-50 text-xs text-slate-800 font-bold"
                                         :class="selectedId == '{{ $customer->id }}' ? 'bg-[#a38c29]/5 text-[#a38c29]' : ''">
                                     {{ $customer->name }}
                                 </button>
                             @endforeach
                         </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ageing Bucket</label>
                <select name="ageing_bucket" onchange="this.form.submit()" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors">
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
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Risk Level</label>
                <select name="risk_level" onchange="this.form.submit()" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors">
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
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Reminder Status</label>
                <select name="reminder_status" onchange="this.form.submit()" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#a38c29] focus:ring-[#a38c29] h-11 px-4 transition-colors">
                    <option value="All">All</option>
                    <option value="Sent" {{ request('reminder_status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                    <option value="Pending" {{ request('reminder_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ request('reminder_status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="flex-none flex gap-3 h-11">
                <a href="{{ route('reports.collection_forecast') }}" class="px-6 py-0 bg-white text-slate-500 border border-slate-200 text-[11px] font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-700 transition-colors flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Outstanding -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Outstanding</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-[#a38c29]">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['total_outstanding'], 0)) }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-[#a38c29]">{{ $kpis['total_customers'] }} Customers</span></p>
            </div>
        </div>

        <!-- Total Overdue -->
        <div class="bg-white border-y border-r border-l-4 border-l-rose-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.2)] hover:border-r-rose-500/20 hover:border-y-rose-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Overdue</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-rose-600">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['total_overdue'], 0)) }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-rose-500">{{ $kpis['overdue_customers'] }} Customers</span></p>
            </div>
        </div>

        <!-- Current / Not Due -->
        <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Current / Not Due</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-emerald-600">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['current_not_due'], 0)) }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-1"><span class="text-emerald-500">{{ $kpis['total_customers'] - $kpis['overdue_customers'] }} Customers</span></p>
            </div>
        </div>

        <!-- Expected Collection -->
        <div class="bg-white border-y border-r border-l-4 border-l-[#3b82f6] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)] hover:border-r-[#3b82f6]/20 hover:border-y-[#3b82f6]/20">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Expected Collection (This Month)</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-blue-600">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($kpis['expected_collection'], 0)) }}</h3>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-[#3b82f6] mt-1 hover:underline inline-block transition-colors duration-300 group-hover:text-blue-700">View Forecast</a>
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
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                    <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                    Overdue Installments Directory
                </h3>
                <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all overdue installments and forecast status.</p>
            </div>
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
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-white font-extrabold">Customer</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Sale No.</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Project</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Unit</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Inst. No.</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Due Date</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Outstanding</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Days Overdue</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Ageing</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Risk</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Reminder Level</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Last Reminder</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Action</th>
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
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $inst->sale->customer->name }}</td>
                            <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $inst->sale->sale_number }}</td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $inst->sale->project->name }}</td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500 leading-tight max-w-[200px] truncate" title="{{ $inst->sale->saleUnits->count() > 0 ? $inst->sale->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ') : ($inst->sale->unit ? $inst->sale->unit->formatted_name : '-') }}">
                                @if($inst->sale->saleUnits && $inst->sale->saleUnits->count() > 0)
                                    {{ $inst->sale->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ') }}
                                @else
                                    {{ $inst->sale->unit ? $inst->sale->unit->formatted_name : '-' }}
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-400">{{ $inst->installment_no }}</td>
                            <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $inst->due_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-right text-xs font-black text-slate-800 tracking-tight">₹ {{ preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($inst->calculated_outstanding, 0)) }}</td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-500">{{ $inst->days_overdue > 0 ? $inst->days_overdue : '-' }}</td>
                            <td class="px-5 py-3 text-center text-xs font-black {{ $ageingColorClass }}">
                                {{ str_replace('+', ' > 120', $inst->ageing_bucket) }}
                            </td>
                            <td class="px-5 py-3 text-center text-xs font-black {{ $riskColorClass }}">
                                {{ $inst->risk_level }}
                            </td>
                            <td class="px-5 py-3 text-center text-xs font-black text-slate-600">
                                {{ $inst->last_reminder ? $inst->last_reminder->reminder_level : ($inst->days_overdue > 0 ? $inst->suggested_reminder_level : '-') }}
                            </td>
                            <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">
                                {{ $inst->last_reminder ? $inst->last_reminder->created_at->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $modalPayload = [
                                        'customer' => [
                                            'name' => $inst->sale->customer->name ?? '-',
                                            'mobile' => $inst->sale->customer->phone ?? '-',
                                            'email' => $inst->sale->customer->email ?? '-',
                                            'address' => $inst->sale->customer->address ?? '-'
                                        ],
                                        'booking' => [
                                            'sale_no' => $inst->sale->sale_number,
                                            'project' => $inst->sale->project->name ?? '-',
                                            'unit' => $inst->sale->saleUnits->count() > 0 ? $inst->sale->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ') : ($inst->sale->unit ? $inst->sale->unit->formatted_name : '-'),
                                            'booking_date' => $inst->sale->agreement_date ? \Carbon\Carbon::parse($inst->sale->agreement_date)->format('d-M-Y') : '-',
                                            'possession_date' => '-'
                                        ],
                                        'summary' => [
                                            'total_outstanding' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($inst->sale->customerInstallments->sum(fn($i) => max(0, $i->amount - $i->paid_amount)), 0)),
                                            'total_overdue' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($inst->calculated_outstanding, 0)),
                                            'days_overdue' => $inst->days_overdue > 0 ? $inst->days_overdue : 0,
                                            'ageing_bucket' => $inst->ageing_bucket,
                                            'risk_level' => $inst->risk_level
                                        ],
                                        'installments' => $inst->sale->customerInstallments->map(function($i) use ($inst) {
                                            $out = max(0, $i->amount - $i->paid_amount);
                                            return [
                                                'no' => $i->installment_no,
                                                'inst_date' => $i->installment_date ? \Carbon\Carbon::parse($i->installment_date)->format('d-M-Y') : '-',
                                                'due_date' => $i->due_date ? \Carbon\Carbon::parse($i->due_date)->format('d-M-Y') : '-',
                                                'amount' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($i->amount, 0)),
                                                'paid' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($i->paid_amount, 0)),
                                                'outstanding' => preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", number_format($out, 0)),
                                                'status' => $i->status,
                                                'is_current' => $i->id === $inst->id
                                            ];
                                        })->sortBy('no')->values()->toArray(),
                                        'reminders' => $inst->collectionReminders->map(function($r) {
                                            return [
                                                'no' => 'RMND-' . str_pad($r->id, 5, '0', STR_PAD_LEFT),
                                                'date' => $r->created_at->format('d-M-Y'),
                                                'type' => $r->reminder_level,
                                                'channel' => 'SMS / Email',
                                                'status' => 'Sent'
                                            ];
                                        })->toArray()
                                    ];
                                @endphp
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button type="button" @click.prevent="openModal(JSON.parse(atob($el.dataset.payload)))" data-payload="{{ base64_encode(json_encode($modalPayload)) }}" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <a href="#" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Message"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></a>
                                    <a href="#" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Call"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></a>
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

    <!-- OVERDUE INSTALLMENT DETAILS MODAL -->
    <div x-show="isModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl my-8 overflow-hidden" @click.outside="closeModal()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Overdue Installment Details</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Detailed view of overdue installment and customer information</p>
                </div>
                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(100vh-200px)] custom-scrollbar space-y-6" x-show="modalData">
                <template x-if="modalData">
                    <div>
                        <!-- Section 1: Customer & Booking Details -->
                        <div class="border border-slate-200 rounded-xl p-5 bg-white relative">
                            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-4 absolute -top-2.5 left-4 bg-white px-2">Customer & Booking Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
                                <div class="grid grid-cols-[120px_auto] gap-2 items-start text-xs">
                                    <span class="font-bold text-slate-500">Customer Name</span>
                                    <span class="font-black text-slate-800 flex gap-2">: <span x-text="modalData.customer.name"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Mobile Number</span>
                                    <span class="font-black text-slate-800 flex gap-2 text-blue-600">: <span x-text="modalData.customer.mobile"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Email ID</span>
                                    <span class="font-black text-slate-800 flex gap-2 text-blue-600">: <span x-text="modalData.customer.email"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Address</span>
                                    <span class="font-medium text-slate-600 flex gap-2 leading-tight">: <span x-text="modalData.customer.address"></span></span>
                                </div>
                                <div class="grid grid-cols-[120px_auto] gap-2 items-start text-xs">
                                    <span class="font-bold text-slate-500">Sale No.</span>
                                    <span class="font-black text-slate-800 flex gap-2">: <span x-text="modalData.booking.sale_no"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Project</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.project"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Unit</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.unit"></span></span>
                                    
                                    <span class="font-bold text-slate-500">Booking Date</span>
                                    <span class="font-bold text-slate-600 flex gap-2">: <span x-text="modalData.booking.booking_date"></span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Outstanding Summary -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Outstanding Summary</h4>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-indigo-900">₹ <span x-text="modalData.summary.total_outstanding"></span></div>
                                <div class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider mt-1">Total Outstanding</div>
                            </div>
                            <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-amber-900">₹ <span x-text="modalData.summary.total_overdue"></span></div>
                                <div class="text-[9px] font-bold text-amber-700 uppercase tracking-wider mt-1">Total Overdue</div>
                            </div>
                            <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-rose-600"><span x-text="modalData.summary.days_overdue"></span> Days</div>
                                <div class="text-[9px] font-bold text-rose-500 uppercase tracking-wider mt-1">Days Overdue</div>
                            </div>
                            <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-emerald-700"><span x-text="modalData.summary.ageing_bucket"></span></div>
                                <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider mt-1">Ageing Bucket</div>
                            </div>
                            <div class="bg-purple-50/50 border border-purple-100 rounded-xl p-3 text-center">
                                <div class="text-sm font-black text-purple-700" x-text="modalData.summary.risk_level"></div>
                                <div class="text-[9px] font-bold text-purple-600 uppercase tracking-wider mt-1">Risk Level</div>
                            </div>
                        </div>

                        <!-- Section 3: Installment Summary -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Installment Summary</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-center">Inst. No.</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Inst. Date</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Due Date</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-right">Inst. Amount</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-right">Paid Amount</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-right">Outst. Amount</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="inst in modalData.installments" :key="inst.no">
                                        <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors" :class="inst.is_current ? 'bg-amber-50/40' : ''">
                                            <td class="px-4 py-2 text-xs font-black text-slate-500 text-center" x-text="inst.no"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="inst.inst_date"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="inst.due_date"></td>
                                            <td class="px-4 py-2 text-xs font-black text-slate-800 text-right">₹ <span x-text="inst.amount"></span></td>
                                            <td class="px-4 py-2 text-xs font-black text-slate-600 text-right">₹ <span x-text="inst.paid"></span></td>
                                            <td class="px-4 py-2 text-xs font-black text-slate-800 text-right" :class="inst.outstanding !== '0' ? 'text-rose-600' : ''">₹ <span x-text="inst.outstanding"></span></td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest inline-flex"
                                                      :class="inst.status === 'paid' ? 'bg-emerald-100 text-emerald-700' : (inst.status === 'unpaid' && inst.outstanding !== '0' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600')"
                                                      x-text="inst.status === 'unpaid' && inst.outstanding !== '0' ? 'Overdue' : inst.status">
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Section 4: Reminder History -->
                        <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3 mt-6">Reminder History</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white mb-4">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Reminder No.</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Reminder Date</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Reminder Type</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider">Channel</th>
                                        <th class="px-4 py-2.5 text-[10px] font-black text-white uppercase tracking-wider text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="modalData.reminders.length === 0">
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-xs font-bold text-slate-400 italic">No reminders sent yet.</td>
                                        </tr>
                                    </template>
                                    <template x-for="rem in modalData.reminders" :key="rem.no">
                                        <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-2 text-xs font-black text-slate-600" x-text="rem.no"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="rem.date"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-600" x-text="rem.type"></td>
                                            <td class="px-4 py-2 text-xs font-bold text-slate-500" x-text="rem.channel"></td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[9px] font-bold uppercase tracking-widest">Sent</span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" @click="closeModal()" class="px-5 py-2 border border-slate-300 bg-white text-slate-600 text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                    Close
                </button>
                <a :href="'tel:' + (modalData ? modalData.customer.mobile : '')" class="px-5 py-2 border border-[#3b82f6] bg-white text-[#3b82f6] text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-blue-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    Call Customer
                </a>
                <button type="button" class="px-5 py-2 bg-[#3b82f6] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm hover:bg-blue-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Send Reminder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function collectionForecastApp() {
    return {
        isModalOpen: false,
        modalData: null,
        openModal(data) {
            this.modalData = data;
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = '';
            setTimeout(() => { this.modalData = null; }, 300);
        },
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
