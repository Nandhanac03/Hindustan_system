<x-erp-layout title="Dashboard" headerTitle="Executive Dashboard">
@php
    $currencySymbol = (auth()->user()->system->currency_code ?? 'INR') === 'AED' ? 'AED ' : '&#8377;';
@endphp

<div class="max-w-[1400px] mx-auto space-y-6">

  {{-- WELCOME HERO BANNER --}}
<div class="anim-1 relative overflow-hidden rounded-2xl bg-white p-7 shadow-sm border border-slate-200">

    <!-- Decorative Background -->
    <div class="absolute -top-16 -right-16 w-72 h-72 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-8 w-56 h-56 bg-slate-200/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#a38c29]/50 to-transparent"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

        <!-- Left -->
        <div>

            @php
                $hour = now()->hour;
                $greeting = $hour < 12
                    ? 'Good Morning'
                    : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
            @endphp

            <p class="text-[#a38c29] text-xs font-bold uppercase tracking-[0.25em] mb-2">
                {{ $greeting }}
            </p>

            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">
                Welcome, {{ auth()->user()->name }} 👋
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">

                Here's your real estate portfolio overview for

                <span class="font-semibold text-slate-900">
                    {{ now()->format('l, d M Y') }}
                </span>.

                @if($pendingApprovals > 0)

                    You have

                    <span class="font-bold text-[#a38c29]">
                        {{ $pendingApprovals }}
                        pending approval{{ $pendingApprovals > 1 ? 's' : '' }}
                    </span>

                    awaiting review.

                @else

                    <span class="font-semibold text-emerald-600">
                        All approvals are up to date.
                    </span>

                    Great work!

                @endif

            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <a href="{{ route('approvals.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#a38c29] px-5 py-2.5 text-xs font-bold uppercase tracking-wide text-white transition-all duration-300 hover:bg-[#8d7923] shadow-md">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>

                    </svg>

                    Approvals Inbox

                </a>

            </div>

        </div>

        <!-- Right -->
        <div class="flex flex-col items-start lg:items-end gap-3">

            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2">

                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>

                <span class="text-sm font-medium text-slate-700">
                    {{ auth()->user()->system->name ?? 'All Regions' }}
                </span>

                <span class="text-slate-400">•</span>

                <span class="font-bold text-[#a38c29]">
                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                </span>

            </div>

            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-500">

                Last Login :
                {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Just now' }}

            </div>

        </div>

    </div>

</div>

    {{-- KPI Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

        {{-- Projects --}}
        <div class="kpi-card anim-1 bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-primary-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-primary-600" style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <svg viewBox="0 0 60 30" class="w-16 h-8 text-primary-400">
                    <polyline class="sparkline-path" points="0,25 10,20 20,22 30,12 40,16 50,8 60,10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $totalProjects }}</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Total Projects</div>
            <div class="mt-2 text-[10px] font-bold text-primary-600 uppercase tracking-wider">Live Portfolio</div>
        </div>

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

        {{-- ERP System Health --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-xl shadow-emerald-500/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest opacity-80">System</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></span>
                <div class="text-xl font-extrabold tracking-tight">All Systems Online</div>
            </div>
            <div class="text-xs text-white/70 mt-1">Database &bull; Server &bull; Queue</div>
        </div>
    </div>

    {{-- PROJECT UNIT AVAILABILITY GRID --}}
    <div class="anim-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Project Unit Availability</h2>
                <p class="text-xs text-slate-400 mt-0.5">Live inventory across all active projects</p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-xs font-bold text-primary hover:text-primary-700 uppercase tracking-wider transition-colors">
                View All Projects &rarr;
            </a>
        </div>
        @php
            $dashProjects = \App\Models\Project::withCount(['units' => fn($q) => $q->where('is_active', true)])->get();
            foreach ($dashProjects as $dp) {
                $dp->avail   = \App\Models\Unit::where('project_id', $dp->id)->where('status', 'available')->where('is_active', true)->count();
                $dp->sold    = \App\Models\Unit::where('project_id', $dp->id)->whereIn('status', ['sold','booked'])->where('is_active', true)->count();
                $dp->reserv  = \App\Models\Unit::where('project_id', $dp->id)->where('status', 'reserved')->where('is_active', true)->count();
            }
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(4, max(1, $dashProjects->count())) }} gap-4">
            @forelse($dashProjects as $dp)
                @php $pct = $dp->units_count > 0 ? ($dp->avail / $dp->units_count) * 100 : 0; @endphp
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary-300 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ $dp->name }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $dp->city }}, {{ $dp->country }}</p>
                        </div>
                        <span class="text-[9px] font-bold px-1.5 py-0.5 bg-primary-100 text-primary-800 rounded flex-shrink-0 ml-2">{{ $dp->code }}</span>
                    </div>
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-2xl font-extrabold text-slate-900">{{ $dp->avail }}</span>
                        <span class="text-[10px] text-slate-400">of {{ $dp->units_count }} units</span>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" style="width:{{ $pct }}%"></div></div>
                    <div class="mt-2 flex gap-3 text-[10px] font-semibold">
                        <span class="text-emerald-600">{{ $dp->avail }} avail</span>
                        <span class="text-primary">{{ $dp->sold }} sold</span>
                        <span class="text-slate-500">{{ $dp->reserv }} reserved</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-400 text-xs italic">No projects found. Add your first project.</div>
            @endforelse
        </div>
    </div>

    {{-- RECENT PROJECTS + TOP CUSTOMERS --}}
    <div class="anim-6 grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Recent Projects Table (3/5) --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recent Projects</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest project activity & completion status</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-xs font-bold text-primary hover:text-primary-700 uppercase tracking-wider transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Project</th>
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Status</th>
                            <th class="px-6 py-3 text-left font-bold text-slate-500 uppercase tracking-widest text-[10px]">Progress</th>
                            <th class="px-4 py-3 text-right font-bold text-slate-500 uppercase tracking-widest text-[10px]">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentProjects as $project)
                            <tr class="table-row transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                                            <svg style="width:13px;height:13px" class="text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $project->name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $project->city }}, {{ $project->country }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="badge-pill badge-{{ $project->status }}">{{ ucfirst($project->status) }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="progress-bar w-28">
                                        <div class="progress-fill" style="width:{{ $project->completion_percentage }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-right font-bold text-slate-800">{{ number_format($project->completion_percentage, 0) }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No projects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Customers (2/5) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Top Customers</h2>
                    <p class="text-xs text-slate-400 mt-0.5">By payment volume</p>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($topCustomers as $idx => $customer)
                    @php
                        $sv = $customer->payments_sum_amount ?? 0;
                        $sdFmt = $sv >= 10000000 ? $currencySymbol.number_format($sv/10000000,2).'Cr' : ($sv >= 100000 ? $currencySymbol.number_format($sv/100000,2).'L' : $currencySymbol.number_format($sv));
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="relative flex-shrink-0">
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($customer->name) }}&backgroundColor=a38c29"
                                 class="w-9 h-9 rounded-full" alt="{{ $customer->name }}">
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-primary text-white text-[8px] font-bold rounded-full flex items-center justify-center">{{ $idx+1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-slate-900 truncate">{{ $customer->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $customer->bookings_count }} {{ Str::plural('Booking', $customer->bookings_count) }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs font-bold text-slate-900">{!! $sdFmt !!}</div>
                            <div class="text-[10px] text-emerald-600 font-semibold">Verified</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4 italic">No customer data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="anim-7 grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Pending Approvals Inbox --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Live Approvals Inbox</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Direct actions for pending items</p>
                </div>
                <span class="badge-pill bg-amber-50 text-amber-700 border border-amber-200">{{ $pendingApprovals }} Requests</span>
            </div>
            <div class="divide-y divide-slate-100 max-h-[350px] overflow-y-auto">
                @forelse($approvalRequests as $req)
                    @php
                        $pColor = match($req->priority ?? 'low') {
                            'critical','high' => ['bg'=>'bg-rose-50','text'=>'text-rose-600','border'=>'border-rose-200'],
                            'medium'          => ['bg'=>'bg-amber-50','text'=>'text-amber-600','border'=>'border-amber-200'],
                            default           => ['bg'=>'bg-blue-50','text'=>'text-blue-600','border'=>'border-blue-200'],
                        };
                    @endphp
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <div class="w-2 h-10 rounded-full {{ $pColor['bg'] }} border {{ $pColor['border'] }} flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-[9px] font-bold uppercase tracking-widest {{ $pColor['text'] }}">{{ ucfirst($req->priority ?? 'Normal') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $req->type ?? 'Request' }}</span>
                            </div>
                            <div class="text-xs font-semibold text-slate-900 truncate">{{ $req->title ?? 'Approval Required' }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $req->created_at->diffForHumans() }}</div>
                        </div>
                        <a href="{{ route('approvals.index') }}" class="flex-shrink-0 w-7 h-7 bg-primary-50 hover:bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg style="width:13px;height:13px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg style="width:24px;height:24px" class="text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-700">All Caught Up!</p>
                        <p class="text-[11px] text-slate-400 mt-1">No pending approvals at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Activity Timeline --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recent Activity</h2>
                <p class="text-xs text-slate-400 mt-0.5">Latest system events & actions</p>
            </div>
            <div class="relative">
                <div class="absolute left-[11px] top-2 bottom-2 w-px bg-slate-200"></div>
                <div class="space-y-5">
                    @forelse($activityLogs as $log)
                        @php
                            $dotColors = ['indigo'=>'border-primary-500 bg-primary-100','green'=>'border-emerald-500 bg-emerald-100','blue'=>'border-blue-500 bg-blue-100','amber'=>'border-amber-500 bg-amber-100','purple'=>'border-primary-500 bg-primary-100','rose'=>'border-rose-500 bg-rose-100'];
                            $dot = $dotColors[$log->color_code ?? 'indigo'] ?? 'border-primary-500 bg-primary-100';
                        @endphp
                        <div class="flex gap-4 pl-1">
                            <div class="relative z-10 mt-1 flex-shrink-0">
                                <div class="w-[22px] h-[22px] rounded-full border-2 {{ $dot }} flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></div>
                                </div>
                            </div>
                            <div class="flex-1 pb-2 min-w-0">
                                <div class="text-xs font-semibold text-slate-900 leading-snug">{{ $log->description }}</div>
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ $log->created_at->diffForHumans() }}
                                    @if($log->user_name ?? false) &bull; <span class="text-slate-500">{{ $log->user_name }}</span> @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6 italic">No recent activity recorded.</p>
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
