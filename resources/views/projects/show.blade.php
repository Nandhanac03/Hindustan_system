<x-erp-layout>
    <x-slot:title>Project Details - {{ $project->name }}</x-slot:title>
    <x-slot:headerTitle>Projects / Details</x-slot:headerTitle>

    <div x-data="{
        panelOpen: false,
        unit: null,
        allowedTransitions: [],
        loading: false,
        activeTab: 'details',
        searchQuery: '',
        statusFilter: '',
        typeFilter: '',
        fetchUnit(unitId) {
            this.loading = true;
            this.panelOpen = true;
            this.activeTab = 'details';
            fetch(`{{ url('units') }}/${unitId}/json`)
                .then(res => {
                    if (!res.ok) throw new Error('Unauthorized');
                    return res.json();
                })
                .then(data => {
                    this.unit = data.unit;
                    this.allowedTransitions = data.allowed_transitions;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                    this.panelOpen = false;
                    alert('Error loading unit details or permission denied.');
                });
        }
    }" class="space-y-6">

        <!-- Project Overview Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
            <div>
                <div class="flex items-center gap-2">
                    <!-- <span class="text-[9px] font-bold text-indigo-650 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded uppercase">
                        {{ $project->code }}
                    </span> -->
                    <h2 class="text-lg font-bold text-slate-900">{{ $project->name }}</h2>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    {{ $project->location }}, {{ $project->city }}, {{ $project->state_or_emirate }}, {{ $project->country }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- @can('projects.manage')
                    <a href="{{ route('project.bulk-generate', $project->id) }}" class="flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold border border-indigo-150 rounded-xl text-xs uppercase tracking-wide transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Bulk Generate Units
                    </a>
                @endcan -->
                <a href="{{ route('projects.index') }}" class="px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-650 hover:text-slate-900 font-bold rounded-xl text-xs uppercase tracking-wide transition">
                    Back
                </a>
            </div>
        </div>

        <!-- Grid Plan -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Floor Layout Grid</h3>
                <p class="text-xs text-slate-500 mt-0.5">Click a unit block to view its history logs, modify base rates, or transition its status.</p>
            </div>

            <!-- Color Palette legend -->
            <div class="flex flex-wrap items-center justify-between gap-4 py-2 border-y border-slate-100">
                <div class="flex flex-wrap items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-500 rounded border border-emerald-600"></span> Available</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-amber-500 rounded border border-amber-600"></span> Blocked</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-blue-500 rounded border border-blue-600"></span> Booked</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-rose-500 rounded border border-rose-600"></span> Sold</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-slate-400 rounded border border-slate-500"></span> On Hold</span>
                </div>
            </div>

            <!-- Real-time Filter Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50 border border-slate-150 rounded-xl">
                {{-- Search --}}
                <div class="relative">
                    <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Filter Unit Number..." x-model="searchQuery"
                           class="w-full pl-9 pr-3 py-1.5 bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 rounded-lg text-xs placeholder-slate-400 transition-all">
                </div>

                {{-- Status Filter --}}
                <select x-model="statusFilter"
                        class="w-full px-3 py-1.5 bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 rounded-lg text-xs text-slate-650 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="blocked">Blocked</option>
                    <option value="booked">Booked</option>
                    <option value="sold">Sold</option>
                    <option value="on_hold">On Hold</option>
                </select>

                {{-- Type Filter --}}
                <select x-model="typeFilter"
                        class="w-full px-3 py-1.5 bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 rounded-lg text-xs text-slate-650 cursor-pointer">
                    <option value="">All Types</option>
                    @foreach($unitTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Floors & Units Grid Wrapper -->
            <div class="space-y-4 pt-4 max-h-[600px] overflow-y-auto pr-1">
                @forelse($project->floors->reverse() as $floor)
                    <div class="grid grid-cols-12 gap-3 items-center border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                        <!-- Floor Header -->
                        <div class="col-span-12 md:col-span-2 text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-2">
                            <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] min-w-[36px] text-center">F: {{ $floor->floor_number }}</span>
                            <span class="truncate">{{ $floor->name }}</span>
                        </div>

                        <!-- Unit Grid List -->
                        <div class="col-span-12 md:col-span-10 flex flex-nowrap overflow-x-auto gap-2 pb-2 scrollbar-thin scrollbar-thumb-slate-300">
                            @forelse($floor->units as $ut)
                                @php
                                    $unitClasses = [
                                        'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100/70',
                                        'blocked' => 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100/70',
                                        'booked' => 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100/70',
                                        'sold' => 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100/70',
                                        'on_hold' => 'bg-slate-100 text-slate-650 border-slate-200 hover:bg-slate-200/70',
                                    ];
                                    $class = $unitClasses[$ut->status] ?? $unitClasses['available'];
                                @endphp
                                <button type="button" 
                                        @click="fetchUnit({{ $ut->id }})"
                                        x-show="(searchQuery === '' || '{{ strtolower($ut->door_no) }}'.includes(searchQuery.toLowerCase())) && (statusFilter === '' || '{{ $ut->status }}' === statusFilter) && (typeFilter === '' || '{{ $ut->unit_type_id }}' === typeFilter)"
                                        class="flex flex-col items-start p-2.5 min-w-[84px] text-left border rounded-xl font-bold cursor-pointer transition select-none flex-shrink-0 {{ $class }}">
                                    <span class="text-xs">{{ $ut->door_no }}</span>
                                    <span class="text-[9px] uppercase font-bold tracking-wide mt-1 block opacity-70">{{ $ut->unitType->name }}</span>
                                    <span class="text-[8px] mt-0.5 opacity-60">{{ $project->system->currency_code }} {{ number_format($ut->expected_rate_per_sqft) }}</span>
                                </button>
                            @empty
                                <span class="text-[10px] text-slate-400 italic">No units registered on this floor.</span>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs italic">
                        No structural floors built for this project yet. Select "Bulk Generate Units" to set up.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Partner Shares & Allocations Section -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Project Partner Shares</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Partners registered to this project with their collection share percentages.</p>
                </div>
                @can('projects.manage')
                    <a href="{{ route('partners.shares', $project->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary hover:text-primary-700 font-bold rounded-xl text-xs uppercase tracking-wide transition shadow-sm border border-primary-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Configure Shares
                    </a>
                @endcan
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($project->partnerShares as $share)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between shadow-sm">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Partner</span>
                            <a href="{{ route('partners.statement', $share->partner->id) }}?project_id={{ $project->id }}" class="text-xs font-bold text-slate-900 hover:text-primary transition">
                                {{ $share->partner->name }}
                            </a>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Share %</span>
                            <span class="px-2.5 py-1 bg-primary/10 text-primary font-bold text-xs rounded-lg inline-block mt-0.5">
                                {{ $share->share_pct }}%
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-4 text-center text-slate-450 italic text-xs">
                        No partners assigned to this project yet. Assign partners to automatically allocate their shares of booking receipts.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Dynamic Drawer Slide-Over Panel -->
        <div x-show="panelOpen" 
             class="fixed inset-0 z-50 overflow-hidden" 
             style="display: none;"
             @keydown.window.escape="panelOpen = false">
            <div class="absolute inset-0 overflow-hidden">
                <!-- Overlay Backdrop -->
                <div x-show="panelOpen" 
                     x-transition:enter="ease-in-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in-out duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="panelOpen = false" 
                     class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="panelOpen" 
                         x-transition:enter="transform transition ease-in-out duration-350"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-350"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="pointer-events-auto w-screen max-w-md">
                        
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-200">
                            
                            <!-- Header Info -->
                            <div class="bg-slate-950 p-6 text-white border-b border-slate-900">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-indigo-300 bg-indigo-950 px-2 py-0.5 rounded uppercase" 
                                              x-text="unit ? unit.unit_type.name : ''"></span>
                                        <h2 class="text-sm font-bold tracking-tight uppercase" x-text="unit ? 'Unit ' + unit.door_no : ''"></h2>
                                    </div>
                                    <button @click="panelOpen = false" class="text-slate-400 hover:text-white rounded-lg transition-colors p-1.5">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-4 flex items-center justify-between text-xs text-slate-400 font-medium">
                                    <span>Floor: <strong class="text-white" x-text="unit ? unit.floor.name : ''"></strong></span>
                                    <span>Status: 
                                        <span class="ml-1 uppercase text-[9px] font-bold px-2 py-0.5 rounded"
                                              :class="{
                                                  'bg-emerald-950 text-emerald-400 border border-emerald-800': unit && unit.status === 'available',
                                                  'bg-amber-950 text-amber-400 border border-amber-800': unit && unit.status === 'blocked',
                                                  'bg-blue-950 text-blue-400 border border-blue-800': unit && unit.status === 'booked',
                                                  'bg-rose-950 text-rose-400 border border-rose-800': unit && unit.status === 'sold',
                                                  'bg-slate-900 text-slate-400 border border-slate-800': unit && unit.status === 'on_hold'
                                              }"
                                              x-text="unit ? unit.status : ''"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Drawer Navigation Tabs -->
                            <div class="flex border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-center">
                                <button @click="activeTab = 'details'" 
                                        :class="activeTab === 'details' ? 'border-primary text-primary border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                        class="flex-1 py-3 transition">Details</button>
                                <button @click="activeTab = 'rates'" 
                                        :class="activeTab === 'rates' ? 'border-primary text-primary border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                        class="flex-1 py-3 transition">Rate Logs</button>
                                <button @click="activeTab = 'status'" 
                                        :class="activeTab === 'status' ? 'border-primary text-primary border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                        class="flex-1 py-3 transition">Status Logs</button>
                            </div>

                            <!-- Content Body -->
                            <div class="flex-1 p-6 overflow-y-auto space-y-6">
                                
                                <!-- Loading Spinner -->
                                <div x-show="loading" class="flex flex-col items-center justify-center py-12 space-y-2">
                                    <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
                                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Syncing details...</span>
                                </div>

                                <div x-show="!loading && unit">
                                    
                                    <!-- TAB 1: DETAILS & ACTIONS -->
                                    <div x-show="activeTab === 'details'" class="space-y-6">
                                        <!-- Specs Matrix Card -->
                                        <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 space-y-3 text-xs">
                                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Specifications</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <span class="text-slate-450 font-medium block">BUA Area</span>
                                                    <strong class="text-slate-850" x-text="unit ? unit.built_up_area + ' Sq Ft' : ''"></strong>
                                                </div>
                                                <div>
                                                    <span class="text-slate-455 font-medium block">Carpet Area</span>
                                                    <strong class="text-slate-850" x-text="unit && unit.carpet_area ? unit.carpet_area + ' Sq Ft' : 'N/A'"></strong>
                                                </div>
                                                <div class="col-span-2 border-t border-slate-200/60 pt-2 grid grid-cols-2 gap-4">
                                                    <div>
                                                        <span class="text-slate-455 font-medium block">Expected Rate</span>
                                                        <strong class="text-slate-850" x-text="unit ? '{{ $project->system->currency_code }} ' + Number(unit.expected_rate_per_sqft).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-455 font-medium block">Expected Sale</span>
                                                        <strong class="text-emerald-700" x-text="unit ? '{{ $project->system->currency_code }} ' + Number(unit.expected_sale_amount).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                </div>
                                                <template x-if="unit && unit.sale_rate_per_sqft">
                                                    <div class="col-span-2 border-t border-slate-200/60 pt-2 grid grid-cols-2 gap-4">
                                                        <div>
                                                            <span class="text-slate-455 font-medium block">Sale Rate</span>
                                                            <strong class="text-slate-850" x-text="'{{ $project->system->currency_code }} ' + Number(unit.sale_rate_per_sqft).toLocaleString('en-US')"></strong>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-455 font-medium block">Sale Amount</span>
                                                            <strong class="text-emerald-800" x-text="'{{ $project->system->currency_code }} ' + Number(unit.sale_amount).toLocaleString('en-US')"></strong>
                                                        </div>
                                                        <div class="col-span-2 border-t border-slate-200/60 pt-2 text-left">
                                                            <span class="text-slate-455 font-medium block">Difference</span>
                                                            <strong class="text-rose-750" x-text="'{{ $project->system->currency_code }} ' + Number(unit.difference).toLocaleString('en-US')"></strong>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- STATUS CHANGE ACTIONS -->
                                        @can('units.manage')
                                            <div class="border-t border-slate-100 pt-4 space-y-4">
                                                <h4 class="text-[10px] font-bold text-indigo-650 uppercase tracking-widest">Execute State Transition</h4>
                                                
                                                <div x-show="allowedTransitions.length === 0" class="p-3 bg-slate-50 text-slate-400 italic text-xs rounded-xl">
                                                    No valid status transitions can be performed from the current status.
                                                </div>

                                                <template x-if="allowedTransitions.length > 0">
                                                    <form method="POST" :action="'/units/' + unit.id + '/status'" class="space-y-4">
                                                        @csrf
                                                        
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Select Target Status</label>
                                                            <select name="status" required class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 focus:outline-none cursor-pointer">
                                                                <option value="">Choose status...</option>
                                                                <template x-for="target in allowedTransitions" :key="target">
                                                                    <option :value="target" x-text="target.toUpperCase()"></option>
                                                                </template>
                                                            </select>
                                                        </div>

                                                        <!-- Checkbox if moving sold -> available (resale trigger) -->
                                                        <div x-show="unit.status === 'sold'" class="flex items-center gap-2">
                                                            <input id="is_resale" type="checkbox" name="is_resale" value="1" class="rounded border-slate-200 text-primary w-4 h-4" />
                                                            <label for="is_resale" class="text-xs text-slate-650 font-bold select-none cursor-pointer">Trigger explicitly as Resale / Booking cancellation</label>
                                                        </div>

                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Authorization / Change Reason</label>
                                                            <input type="text" name="reason" placeholder="Explain this status update..." class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 focus:outline-none" />
                                                        </div>

                                                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-650/10 tracking-wide uppercase">
                                                            Apply Status Change
                                                        </button>
                                                    </form>
                                                </template>
                                            </div>
                                        @endcan

                                        <!-- RATE CHANGE ACTION -->
                                        @can('units.rate.manage')
                                            <div class="border-t border-slate-100 pt-4 space-y-4">
                                                <h4 class="text-[10px] font-bold text-indigo-650 uppercase tracking-widest">Modify Pricing / Base Rate</h4>
                                                
                                                <form method="POST" :action="'/units/' + unit.id + '/rate'" class="space-y-4">
                                                    @csrf
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">New Rate (per Sq Ft)</label>
                                                            <input type="number" step="0.01" name="rate" :value="unit.expected_rate_per_sqft" required class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 focus:outline-none" />
                                                        </div>

                                                        <div class="space-y-1.5">
                                                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Effective Date</label>
                                                            <input type="date" name="effective_from" :value="new Date().toISOString().split('T')[0]" required class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 focus:outline-none cursor-pointer" />
                                                        </div>
                                                    </div>

                                                    <div class="space-y-1.5">
                                                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Rate Escalation Reason</label>
                                                        <input type="text" name="reason" placeholder="e.g. Quarterly price revision" class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 focus:outline-none" />
                                                    </div>

                                                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-850 text-white rounded-xl text-xs font-bold transition uppercase tracking-wider">
                                                        Record New Rate
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>

                                    <!-- TAB 2: RATE HISTORY -->
                                    <div x-show="activeTab === 'rates'" class="space-y-4">
                                        <h4 class="text-[10px] font-bold text-slate-450 uppercase tracking-widest mb-2">Pricing History Logs (Append-Only)</h4>
                                        <div class="relative pl-6 border-l border-slate-200 space-y-4">
                                            <template x-for="log in unit.rate_logs" :key="log.id">
                                                <div class="relative">
                                                    <!-- Icon indicator -->
                                                    <span class="absolute -left-[30px] top-0.5 bg-indigo-100 text-indigo-700 border-2 border-white rounded-full w-4 h-4 flex items-center justify-center">
                                                        <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                                    </span>
                                                    <div class="text-xs">
                                                        <div class="flex items-center justify-between font-semibold text-slate-900">
                                                            <span x-text="'{{ $project->system->currency_code }} ' + Number(log.rate).toLocaleString('en-US')"></span>
                                                            <span class="text-[10px] text-slate-400 font-medium" x-text="log.effective_from"></span>
                                                        </div>
                                                        <p class="text-slate-500 font-medium mt-1" x-text="log.reason ?? 'No reason provided'"></p>
                                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                                            Updated by: <span class="font-bold text-slate-650" x-text="log.user ? log.user.name : 'System'"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- TAB 3: STATUS HISTORY -->
                                    <div x-show="activeTab === 'status'" class="space-y-4">
                                        <h4 class="text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Status Change Logs (Append-Only)</h4>
                                        <div class="relative pl-6 border-l border-slate-200 space-y-4">
                                            <template x-for="log in unit.status_logs" :key="log.id">
                                                <div class="relative">
                                                    <!-- Icon indicator -->
                                                    <span class="absolute -left-[30px] top-0.5 bg-slate-100 border-2 border-white rounded-full w-4 h-4 flex items-center justify-center">
                                                        <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                                    </span>
                                                    <div class="text-xs">
                                                        <div class="flex items-center gap-2 font-bold uppercase text-[9px]">
                                                            <span class="text-slate-450" x-text="log.from_status ? log.from_status : 'NEW'"></span>
                                                            <span class="text-slate-400">&rarr;</span>
                                                            <span class="text-slate-800" x-text="log.to_status"></span>
                                                        </div>
                                                        <p class="text-slate-500 font-medium mt-1" x-text="log.reason ?? 'No reason provided'"></p>
                                                        <div class="flex justify-between items-center text-[10px] text-slate-400 mt-1">
                                                            <span x-text="log.user ? 'Changed by: ' + log.user.name : 'Changed by: System'"></span>
                                                            <span x-text="new Date(log.created_at).toLocaleDateString()"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-erp-layout>
