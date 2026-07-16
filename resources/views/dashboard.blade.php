<x-erp-layout title="Dashboard" headerTitle="Executive Dashboard">
@php
    $currencySymbol = (auth()->user()->system->currency_code ?? 'INR') === 'AED' ? 'AED ' : '₹';
@endphp

<div class="max-w-[1800px] mx-auto space-y-6">

  {{-- ACTIVE PROJECT OVERVIEW BANNER (Replaces Welcome Box) --}}
@if($activeProject)
    @php
        $projectImage = $activeProject->image_url
            ? asset('storage/' . $activeProject->image_url)
            : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80';
        $statusColors = [
            'planning' => 'bg-slate-50 text-slate-700 border-slate-200',
            'ongoing' => 'bg-primary-50 text-primary-800 border-primary-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
            'on_hold' => 'bg-amber-50 text-amber-700 border-amber-150'
        ];
        $colorClass = $statusColors[$activeProject->status] ?? $statusColors['planning'];
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row gap-6 p-6 relative">
        <!-- Accent Top Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#a38c29] via-amber-400 to-[#a38c29]"></div>

        {{-- Project Image --}}
        <div class="w-full md:w-[260px] h-[170px] rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100 border border-slate-150 shadow-inner">
            <img src="{{ $projectImage }}" alt="{{ $activeProject->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent"></div>
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-extrabold text-[10px] uppercase tracking-wider shadow-md">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    Active Project
                </span>
            </div>
        </div>

        {{-- Project Information --}}
        <div class="flex-1 flex flex-col justify-between py-1">
            <div class="space-y-2.5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                          
                          
                        </div>
                        <h2 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight leading-snug">{{ $activeProject->name }}</h2>
                        <p class="text-xs text-slate-500 font-semibold flex items-center gap-1.5 mt-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $activeProject->location }}, {{ $activeProject->city }}, {{ $activeProject->state_or_emirate }}, {{ $activeProject->country }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="badge border font-extrabold uppercase {{ $colorClass }} text-[11px] px-3 py-1.5 rounded-xl shadow-2xs">
                            {{ str_replace('_', ' ', $activeProject->status) }}
                        </span>
                        <a href="{{ route('units.index') }}" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold rounded-xl transition text-xs uppercase tracking-wide flex items-center gap-2 shadow-md shadow-[#a38c29]/20">
                            <span>Manage Project Units</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                @if($activeProject->description)
                    <div class="mt-3 text-xs leading-relaxed text-slate-600 bg-slate-50/80 p-3 rounded-xl border border-slate-100 font-medium">
                        {!! $activeProject->description !!}
                    </div>
                @endif
            </div>

            {{-- Summary of Statistics --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-slate-100 pt-3.5 mt-3.5 text-xs font-semibold text-slate-500">
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Total Floors</span>
                    <strong class="text-slate-800 font-bold text-xs sm:text-sm">{{ $activeProject->total_floors }} Floors</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Start Date</span>
                    <strong class="text-slate-800 font-bold text-xs sm:text-sm">{{ $activeProject->start_date ? \Carbon\Carbon::parse($activeProject->start_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Target Completion</span>
                    <strong class="text-slate-800 font-bold text-xs sm:text-sm">{{ $activeProject->expected_completion_date ? \Carbon\Carbon::parse($activeProject->expected_completion_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
              
            </div>
        </div>
    </div>
@else
    {{-- FALLBACK WHEN NO ACTIVE PROJECT --}}
    <div class="relative overflow-hidden rounded-2xl bg-white p-8 shadow-sm border border-slate-200">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px] uppercase tracking-wider mb-2">
                    No Active Project
                </span>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Select an Active Project</h2>
                <p class="text-sm text-slate-600 font-medium mt-1">
                    To start managing units, bookings, and operations, please activate a project from your portfolio.
                </p>
            </div>
            <a href="{{ route('projects.index') }}" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white font-bold rounded-xl transition text-xs uppercase tracking-wide flex items-center gap-2 shadow-md shadow-[#a38c29]/20 flex-shrink-0">
                <span>Go to Projects List</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
@endif

    {{-- KPI Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

       

        {{-- Units (Ash / Slate Theme) --}}
        <div class="kpi-card anim-2 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-slate-100/50 flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <svg viewBox="0 0 60 30" class="w-16 h-8 text-slate-400">
                    <polyline class="sparkline-path" style="animation-delay:0.1s" points="0,28 10,20 20,24 30,14 40,18 50,6 60,10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalUnits) }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Total Units</div>
            <div class="mt-2 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">{{ $availableUnits }} Available</div>
        </div>

        {{-- Total Sales (Soft Emerald Profit Theme) --}}
        <div class="kpi-card anim-3 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <svg viewBox="0 0 60 30" class="w-16 h-8 text-emerald-400">
                    <polyline class="sparkline-path" style="animation-delay:0.2s" points="0,26 10,22 20,25 30,12 40,18 50,8 60,12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            @php
                $salesFormatted = $totalSales >= 10000000 ? $currencySymbol.number_format($totalSales/10000000,2).'Cr'
                    : ($totalSales >= 100000 ? $currencySymbol.number_format($totalSales/100000,2).'L' : $currencySymbol.number_format($totalSales));
            @endphp
            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">{!! $salesFormatted !!}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Total Sales</div>
            <div class="mt-2 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">{{ $bookedUnits }} Bookings</div>
        </div>

        {{-- Collections (Gold Theme) --}}
        <div class="kpi-card anim-4 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-primary-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <svg viewBox="0 0 60 30" class="w-16 h-8 text-primary-400">
                    <polyline class="sparkline-path" style="animation-delay:0.3s" points="0,20 10,22 20,16 30,24 40,14 50,18 60,10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            @php
                $colFormatted = $totalCollections >= 10000000 ? $currencySymbol.number_format($totalCollections/10000000,2).'Cr'
                    : ($totalCollections >= 100000 ? $currencySymbol.number_format($totalCollections/100000,2).'L' : $currencySymbol.number_format($totalCollections));
            @endphp
            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">{!! $colFormatted !!}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Collections</div>
            <div class="mt-2 text-[10px] font-bold text-primary uppercase tracking-wider">EMI Receipts</div>
        </div>

        {{-- Pending Approvals --}}
        <div class="kpi-card anim-5 bg-white rounded-2xl p-5 border {{ $pendingApprovals > 0 ? 'border-rose-200' : 'border-slate-200/80' }} shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl {{ $pendingApprovals > 0 ? 'bg-rose-50' : 'bg-slate-50' }} flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="{{ $pendingApprovals > 0 ? 'text-rose-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                @if($pendingApprovals > 0)
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-rose-100 text-rose-700 rounded-full uppercase tracking-wide animate-pulse">Urgent</span>
                @endif
            </div>
            <div class="text-2xl font-extrabold {{ $pendingApprovals > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight">{{ $pendingApprovals }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Pending Approvals</div>
            <div class="mt-2 text-[10px] font-bold {{ $pendingApprovals > 0 ? 'text-rose-500' : 'text-slate-400' }} uppercase tracking-wider">
                {{ $pendingApprovals > 0 ? 'Action Required' : 'All Clear' }}
            </div>
        </div>
    </div>

    {{-- REVENUE CHART + UNIT STATUS DONUT --}}
    <div class="anim-3 grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Revenue Area Chart (2/3) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Revenue Analytics</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Monthly booking revenue vs collections - {{ now()->year }}</p>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest">
                    <span class="flex items-center gap-1.5 text-primary"><span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>Revenue</span>
                    <span class="flex items-center gap-1.5 text-emerald-500"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Collected</span>
                </div>
            </div>
            <div id="revenueChart" class="w-full" style="height:260px;"></div>
        </div>

        {{-- Unit Status Donut (1/3) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="mb-4">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Unit Inventory</h2>
                <p class="text-xs text-slate-400 mt-0.5">Live availability breakdown</p>
            </div>
            <div id="donutChart" class="w-full" style="height:200px;"></div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Available</span>
                    <span class="font-bold text-slate-700">{{ $donutAvailable }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>Sold / Booked</span>
                    <span class="font-bold text-slate-700">{{ $donutSold }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-slate-400 inline-block"></span>Reserved</span>
                    <span class="font-bold text-slate-700">{{ $donutReserved }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MINI STAT PANELS (Outstanding + Customers + Health) --}}
    <div class="anim-4 grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Outstanding (Ash / Slate Theme) --}}
        <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl p-5 text-white shadow-xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest opacity-80">Outstanding</span>
            </div>
            @php $outFmt = $outstanding >= 10000000 ? $currencySymbol.number_format($outstanding/10000000,2).'Cr' : ($outstanding >= 100000 ? $currencySymbol.number_format($outstanding/100000,2).'L' : $currencySymbol.number_format($outstanding)); @endphp
            <div class="text-3xl font-extrabold tracking-tight">{!! $outFmt !!}</div>
            <div class="text-xs text-white/70 mt-1">Sales minus collections</div>
        </div>

        {{-- Total Customers (Gold Theme) --}}
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-5 text-white shadow-xl shadow-primary-600/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest opacity-80">Customers</span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">{{ number_format($totalCustomers) }}</div>
            <div class="text-xs text-white/70 mt-1">Registered property buyers</div>
        </div>

        {{-- Sold Units Details (Replaces System Health) --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-xl shadow-emerald-500/10 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest opacity-80">Sold Units</span>
                </div>
                <a href="{{ route('units.index', ['status' => 'sold']) }}" class="text-[10px] font-bold bg-white/20 hover:bg-white/30 px-2.5 py-1 rounded-lg uppercase transition-colors flex items-center gap-1">
                    <span>View Sold</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @php 
                $soldCount = $soldUnitsCount ?? ($donutSold ?? 0);
                $soldVal = $soldUnitsValue ?? 0;
                $soldValFmt = $soldVal >= 10000000 ? $currencySymbol.number_format($soldVal/10000000,2).'Cr' : ($soldVal >= 100000 ? $currencySymbol.number_format($soldVal/100000,2).'L' : $currencySymbol.number_format($soldVal)); 
            @endphp
            <div>
                <div class="text-3xl font-extrabold tracking-tight">{{ number_format($soldCount) }} <span class="text-base font-semibold text-white/80">Units</span></div>
                <div class="text-xs text-white/80 font-medium mt-1.5 flex items-center gap-1.5">
                    <span>Est. Sale Value: <strong class="text-white font-extrabold">{!! $soldValFmt !!}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT UNITS + TOP CUSTOMERS --}}
    <div class="anim-6 grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Recent Units Table (3/5) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recent Units</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest units added to inventory</p>
                </div>
                <a href="{{ route('units.index') }}" class="text-xs font-bold text-[#a38c29] hover:text-[#8a7522] uppercase tracking-wider transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Unit</th>
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Type / Area</th>
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Status</th>
                            <th class="px-4 py-3 text-right font-bold text-slate-500 uppercase tracking-widest text-[10px]">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentUnits as $unit)
                            <tr class="table-row transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-[#a38c29]/10 flex items-center justify-center flex-shrink-0">
                                            <svg style="width:13px;height:13px" class="text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $unit->door_no }}</div>
                                            <div class="text-[10px] text-slate-400">Floor: {{ $unit->floor->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="font-semibold text-slate-700">{{ $unit->unitType->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $unit->built_up_area }} Sq Ft</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $badgeClass = match($unit->status) {
                                            'available' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                            'blocked' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                            'booked' => 'bg-indigo-50 text-indigo-700 border border-indigo-100',
                                            'sold' => 'bg-rose-50 text-rose-700 border border-rose-100',
                                            default => 'bg-slate-50 text-slate-700 border border-slate-200',
                                        };
                                    @endphp
                                    <span class="badge-pill {{ $badgeClass }}">{{ ucfirst($unit->status) }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-bold text-slate-800">
                                    {!! $currencySymbol !!}{{ number_format($unit->expected_sale_amount ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No units found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Customers (2/5) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col ">
            <div class="mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Top Customers</h2>
                <p class="text-xs text-slate-400 mt-0.5">Ranked by total payment volume</p>
            </div>
            <div class="space-y-3">
                @forelse($topCustomers as $idx => $customer)
                    @php
                        $sv = $customer->payments_sum_amount ?? 0;
                        $sdFmt = $sv >= 10000000 ? $currencySymbol.number_format($sv/10000000,2).'Cr' : ($sv >= 100000 ? $currencySymbol.number_format($sv/100000,2).'L' : $currencySymbol.number_format($sv));
                        
                        // Dynamic rank badge styling
                        $rankBadge = match($idx) {
                            0 => 'bg-amber-100 text-amber-800 border-amber-200 ring-4 ring-amber-50',
                            1 => 'bg-slate-100 text-slate-800 border-slate-200 ring-4 ring-slate-50',
                            2 => 'bg-orange-100 text-orange-800 border-orange-200 ring-4 ring-orange-50',
                            default => 'bg-slate-50 text-slate-600 border-slate-100'
                        };
                        
                        $initials = collect(explode(' ', $customer->name))
                            ->map(fn($n) => mb_substr($n, 0, 1))
                            ->take(2)
                            ->join('');
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-[#a38c29]/30 hover:bg-[#a38c29]/5 hover:shadow-sm transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            {{-- Rank Badge --}}
                            <span class="w-6 h-6 flex items-center justify-center rounded-lg text-[10px] font-bold border {{ $rankBadge }} flex-shrink-0 font-mono">
                                {{ $idx + 1 }}
                            </span>
                            
                            {{-- Avatar Initials --}}
                            <div class="w-9 h-9 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-xs font-bold text-slate-700 tracking-wider flex-shrink-0 uppercase group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29] group-hover:border-[#a38c29]/20 transition-colors">
                                {{ $initials ?: 'C' }}
                            </div>

                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-900 group-hover:text-[#8a7522] transition-colors truncate">{{ $customer->name }}</div>
                                <div class="text-[10px] text-slate-400 font-semibold">{{ $customer->sales_count }} {{ Str::plural('Sale', $customer->sales_count) }}</div>
                            </div>
                        </div>

                        <div class="text-right pl-3 flex-shrink-0">
                            <div class="text-xs font-extrabold text-slate-900">{!! $sdFmt !!}</div>
                            <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider flex items-center justify-end gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-8 italic">No customer data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="anim-7 grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Recent Bookings --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recent Bookings</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest bookings across your units</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-bold text-[#a38c29] hover:text-[#8a7522] uppercase tracking-wider transition-colors">View All</a>
            </div>
            <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto">
                @forelse($recentBookings as $booking)
                    @php
                        $bColor = match($booking->status) {
                            'pending'  => ['bg'=>'bg-amber-50','text'=>'text-amber-600','border'=>'border-amber-200'],
                            'approved' => ['bg'=>'bg-emerald-50','text'=>'text-emerald-600','border'=>'border-emerald-200'],
                            'rejected','cancelled' => ['bg'=>'bg-rose-50','text'=>'text-rose-600','border'=>'border-rose-200'],
                            default    => ['bg'=>'bg-slate-50','text'=>'text-slate-600','border'=>'border-slate-200'],
                        };
                    @endphp
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="w-2 h-10 rounded-full {{ $bColor['bg'] }} border {{ $bColor['border'] }} flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-[9px] font-bold uppercase tracking-widest {{ $bColor['text'] }}">{{ ucfirst($booking->status) }}</span>
                                <span class="text-[10px] text-slate-400">Unit: {{ $booking->unit->door_no ?? 'N/A' }}</span>
                            </div>
                            <div class="text-xs font-semibold text-slate-900 truncate">Customer: {{ $booking->customer->name ?? 'Unknown' }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $booking->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs font-bold text-slate-900">{!! $currencySymbol !!}{{ number_format($booking->total_amount ?? 0) }}</div>
                            <div class="text-[10px] text-slate-400">Sale Value</div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg style="width:24px;height:24px" class="text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-700">No Bookings Yet</p>
                        <p class="text-[11px] text-slate-400 mt-1">Bookings will appear here once created.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Bank Loan EMI Repayment Notifications Card --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-sm font-bold text-[#0B1E36] uppercase tracking-wider">EMI Pending Alerts</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Active bank loan EMI repayment obligations</p>
                    </div>
                    <a href="{{ route('loans.index') }}" class="text-[10px] font-extrabold text-[#a38c29] hover:underline uppercase tracking-wider">
                        Show All
                    </a>
                </div>
                
                <div class="space-y-3.5">
                    @forelse($pendingEmiAlerts as $alert)
                        <div class="p-3.5 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <!-- Bank Logo abbreviation box -->
                                <div class="w-8 h-8 rounded-xl bg-[#0B1E36] text-white flex items-center justify-center font-bold text-[9px] shrink-0 uppercase">
                                    {{ substr($alert->provider, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">{{ $alert->provider }}</div>
                                    <div class="text-[9px] mt-0.5 font-bold uppercase {{ $alert->is_overdue ? 'text-rose-600' : 'text-amber-700' }}">
                                        {{ $alert->due_text }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end gap-1">
                                <span class="font-mono font-bold text-slate-900 text-xs">₹{{ number_format($alert->emi_amount, 2) }}</span>
                                <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $alert->is_overdue ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    {{ $alert->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 rounded-2xl bg-emerald-50/30 border border-emerald-200/50 text-xs font-semibold text-slate-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-wider">No pending EMIs due at this time</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

{{-- APEX CHARTS SCRIPTS --}}
<script>
// Revenue Area Chart
const currencySymbol = @json($currencySymbol);
new ApexCharts(document.querySelector('#revenueChart'), {
    series: [
        { name: `Revenue (${currencySymbol} L)`, data: @json($revenueData) },
        { name: `Collected (${currencySymbol} L)`, data: @json($collectionsData) }
    ],
    chart: {
        type: 'area', height: 260, fontFamily: 'Inter, sans-serif',
        toolbar: { show: false },
        animations: { enabled: true, easing: 'easeinout', speed: 900 }
    },
    colors: ['#a38c29', '#10B981'],
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] }
    },
    stroke: { curve: 'smooth', width: [2.5, 2.5] },
    dataLabels: { enabled: false },
    xaxis: {
        categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        axisBorder: { show: false }, axisTicks: { show: false },
        labels: { style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter, sans-serif' } }
    },
    yaxis: {
        labels: {
            style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter, sans-serif' },
            formatter: v => currencySymbol + v.toFixed(1) + 'L'
        }
    },
    grid: { borderColor: '#f1f5f9', strokeDashArray: 4, xaxis: { lines: { show: false } } },
    legend: { show: false },
    tooltip: {
        theme: 'light',
        y: { formatter: v => currencySymbol + v.toFixed(2) + ' Lakhs' }
    }
}).render();

// Donut Chart
new ApexCharts(document.querySelector('#donutChart'), {
    series: [{{ $donutAvailable }}, {{ $donutSold }}, {{ $donutReserved }}],
    labels: ['Available', 'Sold/Booked', 'Reserved'],
    chart: {
        type: 'donut', height: 200, fontFamily: 'Inter, sans-serif',
        animations: { enabled: true, speed: 800 }
    },
    colors: ['#10B981', '#a38c29', '#a59d92'],
    plotOptions: {
        pie: {
            donut: {
                size: '72%',
                labels: {
                    show: true,
                    total: {
                        show: true, label: 'Total',
                        formatter: w => w.globals.seriesTotals.reduce((a,b) => a+b, 0),
                        style: { fontSize: '16px', fontWeight: '800', color: '#1e293b', fontFamily: 'Inter, sans-serif' }
                    }
                }
            }
        }
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    stroke: { width: 0 },
    tooltip: { theme: 'light' }
}).render();
</script>

</x-erp-layout>
