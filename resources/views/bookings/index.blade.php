<x-erp-layout title="Sales Register" headerTitle="Sales Register & Bookings">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Sales Register</h1>
            <p class="text-xs text-slate-500 mt-1">Manage property bookings, track unit sales contracts, and handle cancellations or resales.</p>
        </div>

        <div>
          <a href="{{ route('bookings.create') }}"
   class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all duration-200 hover:bg-slate-900">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v16m8-8H4"/>
    </svg>
    Register Sale
</a>
            <!-- <a href="{{ route('bookings.create') }}" class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-indigo-650 hover:bg-indigo-600 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-600/10 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Register Sale
            </a> -->
        </div>
    </div>

    {{-- Status Alerts / Feedback --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-150 rounded-xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between">
            <span>{{ session('status') }}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5" x-data="{ expanded: {{ request()->hasAny(['floor_id', 'unit_type_id', 'unit_number', 'customer_id', 'broker_id', 'min_sqft', 'max_sqft', 'min_price', 'max_price', 'start_date', 'end_date']) ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('bookings.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                {{-- Search --}}
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search BK# or Customer..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs placeholder-slate-450 focus:outline-none transition-all">
                </div>

                {{-- Project Filter --}}
                <select name="project_id"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                    <option value="">All Statuses</option>
                    <option value="pending_approval" {{ request('status') === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                {{-- Trigger to Show More Filters --}}
                <div class="flex gap-2">
                    <button type="button" @click="expanded = !expanded" 
                            class="w-full px-3 py-2.5 border border-slate-250 text-slate-700 hover:bg-slate-50 text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 uppercase tracking-wide transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Advanced Filters
                    </button>
                </div>
            </div>

            {{-- Collapsible Advanced Filters Section --}}
            <div x-show="expanded" class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100" style="display: none;">
                {{-- Floor Filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Floor</label>
                    <select name="floor_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">All Floors</option>
                        @foreach($floors as $f)
                            <option value="{{ $f->id }}" {{ request('floor_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Unit Type Filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Unit Type</label>
                    <select name="unit_type_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">All Types</option>
                        @foreach($unitTypes as $ut)
                            <option value="{{ $ut->id }}" {{ request('unit_type_id') == $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Unit Number Filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Unit Number</label>
                    <input type="text" name="unit_number" value="{{ request('unit_number') }}" placeholder="e.g. A-102"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                </div>

                {{-- Customer Filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Customer</label>
                    <select name="customer_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">All Customers</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Broker Filter --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Broker</label>
                    <select name="broker_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">All Brokers</option>
                        @foreach($brokers as $b)
                            <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- BUA Range --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Sqft Range (BUA)</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_sqft" value="{{ request('min_sqft') }}" placeholder="Min"
                               class="w-1/2 px-2.5 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                        <input type="number" name="max_sqft" value="{{ request('max_sqft') }}" placeholder="Max"
                               class="w-1/2 px-2.5 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Price Range</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Price"
                               class="w-1/2 px-2.5 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Price"
                               class="w-1/2 px-2.5 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Date Range --}}
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wide block">Agreement Date Range</label>
                    <div class="flex gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-1/2 px-2 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-1/2 px-2 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Submit/Reset Row --}}
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('bookings.index') }}" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl text-xs font-bold transition uppercase tracking-wide text-center">Reset</a>
                <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white rounded-xl text-xs font-bold transition uppercase tracking-wide">Apply Filters</button>
            </div>
        </form>
    </div>

    {{-- Main Register Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-left">
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Sale / Dates</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Property Details</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Area & Rates</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Pricing Calculations</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Stakeholders</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Status</th>
                        <th class="px-4 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50 transition-colors" x-data="{ openView: false }">
                            {{-- Sale / Dates --}}
                            <td class="px-4 py-4 space-y-1">
                                <div class="font-bold text-[#a38c29] font-mono text-[11px]">{{ $booking->booking_number }}</div>
                                <div class="text-[9px] text-slate-450 uppercase font-semibold">
                                    Booked: {{ $booking->created_at->format('d M Y') }}
                                </div>
                                @if($booking->agreement_date)
                                    <div class="text-[9px] text-indigo-700 uppercase font-bold">
                                        Agr: {{ $booking->agreement_date->format('d M Y') }}
                                    </div>
                                @endif
                                @if($booking->registration_date)
                                    <div class="text-[9px] text-emerald-700 uppercase font-bold">
                                        Reg: {{ $booking->registration_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Property Details --}}
                            <td class="px-4 py-4 space-y-1">
                                <div class="font-bold text-slate-900">{{ $booking->project->name }}</div>
                                <div class="flex flex-wrap gap-1 items-center">
                                    <span class="text-[9px] bg-slate-100 border text-slate-500 font-bold px-1.5 py-0.5 rounded uppercase">{{ $booking->unit->unitType->name }}</span>
                                    <span class="text-[9px] bg-amber-50 border border-amber-100 text-amber-700 font-bold px-1.5 py-0.5 rounded font-mono">Unit {{ $booking->unit->door_no }}</span>
                                    <span class="text-[9px] bg-slate-100 border text-slate-500 font-semibold px-1.5 py-0.5 rounded uppercase">{{ $booking->unit->floor->name }}</span>
                                </div>
                            </td>

                            {{-- Area & Rates --}}
                            <td class="px-4 py-4 space-y-1">
                                <div>
                                    <span class="text-slate-400 font-medium">BUA:</span> <span class="font-bold text-slate-700">{{ $booking->unit->built_up_area }} Sq Ft</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-medium">Carpet:</span> <span class="font-semibold text-slate-600">{{ $booking->unit->carpet_area ?? 'N/A' }} Sq Ft</span>
                                </div>
                                <div class="text-[10px] pt-1">
                                    <span class="text-slate-400">Exp. Rate:</span> <span class="font-semibold text-slate-600">₹{{ number_format($booking->unit->expected_rate_per_sqft, 2) }}</span>
                                </div>
                                <div class="text-[10px]">
                                    <span class="text-slate-400">Sale Rate:</span> <span class="font-bold text-slate-800">₹{{ number_format($booking->sale_rate_per_sqft ?? $booking->unit->expected_rate_per_sqft, 2) }}</span>
                                </div>
                            </td>

                            {{-- Pricing Calculations --}}
                            <td class="px-4 py-4 space-y-1">
                                <div class="text-slate-500">Expected: <span class="font-semibold text-slate-700">₹{{ number_format($booking->expected_sale_value, 2) }}</span></div>
                                <div class="font-bold text-slate-900">Actual: ₹{{ number_format($booking->amount, 2) }}</div>
                                
                                {{-- GST behavior description --}}
                                <div class="text-[9px] uppercase font-bold tracking-wider">
                                    @if($booking->gst_behavior === 'inclusive')
                                        <span class="text-amber-600">GST Incl. (₹{{ number_format($booking->gst_amount, 2) }})</span>
                                    @elseif($booking->gst_behavior === 'exclusive')
                                        <span class="text-purple-600">GST Excl. (+₹{{ number_format($booking->gst_amount, 2) }})</span>
                                    @else
                                        <span class="text-slate-400">No GST</span>
                                    @endif
                                </div>

                                {{-- Profit/Shortfall badge --}}
                                @php
                                    $diff = $booking->profit_shortfall;
                                @endphp
                                <div class="pt-1">
                                    @if($diff > 0)
                                        <span class="text-[9px] bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded">+₹{{ number_format($diff, 2) }} Profit</span>
                                    @elseif($diff < 0)
                                        <span class="text-[9px] bg-rose-50 border border-rose-150 text-rose-700 font-bold px-1.5 py-0.5 rounded">-₹{{ number_format(abs($diff), 2) }} Shortfall</span>
                                    @else
                                        <span class="text-[9px] text-slate-400 italic">No rate diff</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Stakeholders --}}
                            <td class="px-4 py-4 space-y-1">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-medium block">Customer</span>
                                    <span class="font-semibold text-slate-900">{{ $booking->customer->name }}</span>
                                </div>
                                <div class="pt-1">
                                    <span class="text-[10px] text-slate-400 font-medium block">Broker / Agent</span>
                                    @if($booking->broker)
                                        <span class="font-bold text-indigo-700">{{ $booking->broker->name }}</span>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">Direct Sale</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4">
                                @php
                                    $badgeClass = match($booking->status) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'pending_approval' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                    $statusName = match($booking->status) {
                                        'pending_approval' => 'Pending Approval',
                                        default => ucfirst($booking->status)
                                    };
                                @endphp
                                <span class="badge-pill border px-2.5 py-1 rounded-lg font-bold text-[9px] uppercase {{ $badgeClass }}">
                                    {{ $statusName }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openView = true" title="View Booking Summary" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    @if($booking->status !== 'cancelled')
                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this booking and release the unit?')">
                                            @csrf
                                            <button type="submit" title="Cancel Sale & Release Unit" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if($booking->unit->status === 'sold' && $booking->status !== 'cancelled')
                                        <form action="{{ route('bookings.resale', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to release this sold unit for resale?')">
                                            @csrf
                                            <button type="submit" title="Release Unit for Resale" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- View Modal --}}
                                <div x-show="openView" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity text-left" style="display: none;">
                                    <div @click.away="openView = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-lg space-y-5 whitespace-normal">
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                </div>
                                                <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Booking & Sale Summary (#{{ $booking->booking_number }})</h3>
                                            </div>
                                            <button @click="openView = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                                                <div>
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Customer Name</span>
                                                    <span class="text-base font-extrabold text-slate-900">{{ $booking->customer->name }}</span>
                                                    <span class="text-xs text-slate-500 block mt-0.5">Project: {{ $booking->project->name }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Number</span>
                                                    <span class="px-2.5 py-1 rounded bg-[#a38c29]/10 text-[#a38c29] font-mono font-bold text-xs inline-block mt-0.5">Door {{ $booking->unit->door_no }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Actual Sale Amount</span>
                                                    <span class="text-sm font-extrabold text-emerald-700 mt-0.5 block font-mono">₹{{ number_format($booking->amount, 2) }}</span>
                                                </div>
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Sale Value</span>
                                                    <span class="text-xs font-bold text-slate-700 mt-0.5 block font-mono">₹{{ number_format($booking->expected_sale_value, 2) }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Behavior (18% Slab)</span>
                                                    @if($booking->gst_behavior === 'inclusive')
                                                        <span class="text-xs font-bold text-amber-600 mt-0.5 block">Included (18% GST)</span>
                                                    @elseif($booking->gst_behavior === 'exclusive')
                                                        <span class="text-xs font-bold text-purple-600 mt-0.5 block">Additional (+18% Extra)</span>
                                                    @else
                                                        <span class="text-xs font-bold text-slate-500 mt-0.5 block">No GST (None)</span>
                                                    @endif
                                                </div>
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Amount Payable</span>
                                                    <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono">₹{{ number_format($booking->gst_amount, 2) }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker / Agent</span>
                                                    <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $booking->broker ? $booking->broker->name : 'Direct Sale (No Broker)' }}</span>
                                                </div>
                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Booking Status</span>
                                                    <span class="text-xs font-bold uppercase mt-0.5 block {{ $booking->status === 'approved' ? 'text-emerald-600' : 'text-amber-600' }}">{{ ucfirst($booking->status) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 flex justify-between items-center border-t border-slate-100">
                                            <button type="button" @click="openView = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
                                            <a href="{{ route('units.index', ['search' => $booking->unit->door_no, 'project_id' => $booking->project_id]) }}" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md inline-flex items-center gap-1.5">
                                                <span>Unit Details & Payouts</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-450 italic">No bookings found in the register.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>

</x-erp-layout>
