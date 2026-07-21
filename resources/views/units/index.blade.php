<x-erp-layout title="Units Directory" headerTitle="Units Directory">



<div class="max-w-[1800px] mx-auto space-y-6" x-data="unitsApp()">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                    Project:
                </h1>
                @if(isset($projects) && $projects->count() > 1)
                    <form method="GET" action="{{ route('units.index') }}" class="inline">
                        <select name="project_id" onchange="this.form.submit()" class="px-3 py-1 text-xs font-extrabold uppercase tracking-wide rounded-xl border border-slate-300 bg-white text-primary-700 focus:ring-2 focus:ring-[#a38c29]/50 shadow-sm cursor-pointer">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $project->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} {{ $p->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <span class="text-primary-700 text-lg font-extrabold uppercase">{{ $project->name }}</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage single unit listings, pricing matrices, and floor allocations for <span class="font-bold text-slate-700">{{ $project->name }}</span>.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <select x-model="filters.status" @change="fetchUnits()"
                    class="px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                <option value="">All Statuses</option>
                <option value="recently_added">Recently Added</option>
                <option value="available">Available</option>
                <option value="blocked">Blocked</option>
                <option value="booked">Booked</option>
                <option value="sold">Sold</option>
                <option value="on_hold">On Hold</option>
            </select>

            <template x-if="permissions.manage">
                <div class="flex items-center gap-2">
                     <button @click="openBulkModal()" class="btn-ripple inline-flex items-center gap-2 px-3 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-sm uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Bulk Add
                    </button> 
                    <button @click="openAddModal()" class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Unit
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- Project Overview Banner Card --}}
   @php
    $projectImage = $project->image_url
        ? asset('storage/' . $project->image_url)
        : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80';
@endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row gap-6 p-5">
        {{-- Project Image --}}
        <div class="w-full md:w-[240px] h-[160px] rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100 border border-slate-150">
            <img src="{{ $projectImage }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
            <!-- <div class="absolute bottom-3 left-3">
                <span class="text-[9px] font-bold px-2 py-0.5 bg-primary text-white rounded-md uppercase font-mono tracking-wider">{{ $project->code }}</span>
            </div> -->
        </div>

        {{-- Project Information --}}
        <div class="flex-1 flex flex-col justify-between py-1">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight leading-snug">{{ $project->name }}</h2>
                        <p class="text-xs text-slate-450 font-semibold flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $project->location }}, {{ $project->city }}, {{ $project->state_or_emirate }}, {{ $project->country }}
                        </p>
                    </div>

                    @php
                        $statusColors = [
                            'planning' => 'bg-slate-50 text-slate-700 border-slate-200',
                            'ongoing' => 'bg-primary-50 text-primary-800 border-primary-200',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
                            'on_hold' => 'bg-amber-50 text-amber-700 border-amber-150'
                        ];
                        $colorClass = $statusColors[$project->status] ?? $statusColors['planning'];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="badge border font-extrabold uppercase {{ $colorClass }} text-[10px] px-2.5 py-1 rounded-lg">
                            {{ str_replace('_', ' ', $project->status) }}
                        </span>
                        @can('projects.manage')
    <button
        @click="editProjectModal = true"
        class="px-2.5 py-1 bg-white border border-[#09876B] hover:bg-[#09876B]/10 text-[#09876B] hover:text-[#076852] font-bold rounded-lg transition text-[10px] uppercase tracking-wide flex items-center gap-1"
    >
        <svg class="w-3.5 h-3.5 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Project
    </button>
@endcan
                    </div>
                </div>

                @if($project->description)
                    <div class="mt-3 text-[11px] leading-relaxed text-slate-500 bg-slate-50/50 p-2.5 rounded-lg border border-slate-100">
                        {!! $project->description !!}
                    </div>
                @endif
            </div>
            {{-- Summary of Statistics / RERA --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-slate-100 pt-4 mt-4 text-xs font-semibold text-slate-500">
               
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Total Floors</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->total_floors }} Floors</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Start Date</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Target Completion</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->expected_completion_date ? \Carbon\Carbon::parse($project->expected_completion_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class=" rounded-2xl border-0 shadow-sm p-4.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3.5 flex-1">
            {{-- Search Door No --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" placeholder="Search Door No..." 
                       x-model="filters.search" @input.debounce.300ms="fetchUnits()"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
            </div>

            {{-- Floor Filter --}}
            <select x-model="filters.floor_id" @change="fetchUnits()"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 hover:border-slate-300 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                <option value="">All Floors</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                @endforeach
            </select>

            {{-- Unit Type Filter --}}
            <select x-model="filters.unit_type_id" @change="fetchUnits()"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 hover:border-slate-300 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                <option value="">All Types</option>
                @foreach($unitTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }} ({{ ucfirst($type->category) }})</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select x-model="filters.status" @change="fetchUnits()"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 hover:border-slate-300 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                <option value="">All Statuses</option>
                <option value="recently_added">Recently Added</option>
                <option value="available">Available</option>
                <option value="blocked">Blocked</option>
                <option value="booked">Booked</option>
                <option value="sold">Sold</option>
                <option value="on_hold">On Hold</option>
            </select>
        </div>

        <button @click="resetFilters()"
                class="inline-flex items-center justify-center gap-2 rounded-xl border-0 bg-[#a38c29] hover:bg-[#8a7522] px-7 py-2.5 text-xs font-bold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wide">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reset Filters
        </button>
    </div>

    {{-- Units Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #units-table thead th {
                border-color: #8a7522 !important;
            }
            #units-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #units-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="units-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border">FLOOR</th>
                        <th class="px-3 py-3 border">TYPE</th>
                        <th class="px-3 py-3 border">DOOR NO</th>
                        <th class="px-3 py-3 border">BUILT UP AREA (In Sq Ft)</th>
                        <th class="px-3 py-3 border">CARPET AREA (In Sq Ft)</th>
                        <th class="px-3 py-3 border">₹ EXPECTED / SQ.FT</th>
                        <th class="px-3 py-3 border">₹ EXPECTED SALE</th>
                        <th class="px-3 py-3 border">₹ SALE PER SQ.FT</th>
                        <th class="px-3 py-3 border">₹ SALE AMOUNT</th>
                        <th class="px-3 py-3 border">DIFFERENCE</th>
                        <th class="px-3 py-3 border">STATUS</th>
                        <th class="px-3 py-3 border text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="units-tbody" x-effect="renderUnitsTable()">
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between" x-show="pagination.total > 0">
            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                Showing <span class="text-slate-900" x-text="(pagination.current_page - 1) * (pagination.per_page || 50) + 1"></span> to 
                <span class="text-slate-900" x-text="Math.min(pagination.current_page * (pagination.per_page || 50), pagination.total)"></span> of 
                <span class="text-slate-900" x-text="Number(pagination.total).toLocaleString()"></span> Units
            </div>
            <div class="flex items-center gap-1.5">
                <button @click="if(pagination.current_page > 1) fetchUnits(pagination.current_page - 1)" 
                        :disabled="pagination.current_page <= 1"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Prev
                </button>
                
                {{-- Page Numbers --}}
                <template x-for="p in getPageNumbers()">
                    <span class="inline-flex items-center gap-1">
                        <span x-show="p === '...'" class="px-2 py-1 text-[10px] text-slate-400 font-bold" x-text="p"></span>
                        <button x-show="p !== '...'"
                                @click="fetchUnits(p)"
                                x-text="p"
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors"
                                :class="pagination.current_page === p ? 'bg-primary text-white border border-primary' : 'bg-white border border-slate-200 text-slate-650 hover:bg-slate-50'"></button>
                    </span>
                </template>
                
                <button @click="if(pagination.current_page < pagination.last_page) fetchUnits(pagination.current_page + 1)" 
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- ═══════════════════════════════════════════
         MODAL 1: ADD UNIT MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        {{-- Backdrop --}}
        <div x-show="modals.add.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeAddModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.add.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-[#4a4224] px-6 py-5 flex-shrink-0">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Single Unit Setup</p>
                        <h2 class="text-lg font-extrabold text-white">Add New Unit</h2>
                    </div>
                    <button @click="closeAddModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitAddUnit()" class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                <div class="p-6 space-y-4 overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Floor <span class="text-rose-500">*</span></label>
                            <select x-model="forms.add.floor_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type <span class="text-rose-500">*</span></label>
                            <select x-model="forms.add.unit_type_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                <option value="">Select Type...</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_type_id[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Door No (e.g. A-404) <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="forms.add.door_no" placeholder="Enter door number..." class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        <template x-if="errors.door_no"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.door_no[0]"></p></template>
                    </div>

                    {{-- Area fields: hidden for Parking type --}}
                    <template x-if="!isParking(forms.add.unit_type_id)">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.add.built_up_area" placeholder="e.g. 1200" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.built_up_area[0]"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.add.carpet_area" placeholder="e.g. 1000" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                            </div>
                        </div>
                    </template>

                    {{-- Rate field: hidden for Parking; show direct Expected Sale instead --}}
                    <template x-if="!isParking(forms.add.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Rate per Sq Ft (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.add.expected_rate_per_sqft" placeholder="e.g. 4500" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_rate_per_sqft"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_rate_per_sqft[0]"></p></template>
                        </div>
                    </template>

                    {{-- Parking: direct Expected Sale field (no area, no rate) --}}
                    <template x-if="isParking(forms.add.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.add.expected_sale_amount" placeholder="e.g. 300000" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <p class="text-[10px] text-slate-400 italic">Auto-calculation not applicable for Parking.</p>
                            <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_sale_amount[0]"></p></template>
                        </div>
                    </template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">Add Unit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 2: EDIT UNIT & ACTIONS MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        {{-- Backdrop --}}
        <div x-show="modals.edit.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeEditModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.edit.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh]"
             @click.stop>

            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Unit Management</p>
                        <h2 class="text-lg font-extrabold text-white" x-text="'Unit: ' + (activeUnit.door_no || '—')"></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="badge-pill text-[9px]" :class="getStatusBadgeClass(activeUnit.status)" x-text="activeUnit.status"></span>
                            <span class="text-slate-400 text-[10px]" x-text="activeUnit.floor ? activeUnit.floor.name : ''"></span>
                            <span class="text-slate-600 text-[10px]">•</span>
                            <span class="text-slate-400 text-[10px]" x-text="activeUnit.unit_type ? activeUnit.unit_type.name : ''"></span>
                        </div>
                    </div>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body: Two Panes --}}
            <div class="grid lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100 overflow-y-auto max-h-[calc(90vh-130px)]">

                {{-- LEFT PANE: Edit Form --}}
                <div class="p-6 space-y-4 overflow-y-auto">
                    <template x-if="permissions.manage">
                        <form @submit.prevent="submitEditUnit()">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Floor</label>
                                        <select x-model="forms.edit.floor_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type</label>
                                        <select x-model="forms.edit.unit_type_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                            @foreach($unitTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_type_id[0]"></p></template>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Door No</label>
                                    <input type="text" x-model="forms.edit.door_no"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    <template x-if="errors.door_no"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.door_no[0]"></p></template>
                                </div>

                                {{-- Area fields: hidden for Parking --}}
                                <template x-if="!isParking(forms.edit.unit_type_id)">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                            <input type="number" step="0.01" x-model="forms.edit.built_up_area"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.built_up_area[0]"></p></template>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                            <input type="number" step="0.01" x-model="forms.edit.carpet_area"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Parking: editable Expected Sale Amount --}}
                                <template x-if="isParking(forms.edit.unit_type_id)">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" x-model="forms.edit.expected_sale_amount"
                                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                        <p class="text-[10px] text-slate-400 italic">Auto-calculation not applicable for Parking.</p>
                                        <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_sale_amount[0]"></p></template>
                                    </div>
                                </template>

                                {{-- Pricing Summary Card --}}
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Pricing Summary</p>
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <template x-if="!isParking(forms.edit.unit_type_id)">
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Expected Rate</span>
                                                <strong class="text-slate-800 text-xs" x-text="activeUnit.expected_rate_per_sqft ? '₹' + Number(activeUnit.expected_rate_per_sqft).toLocaleString() : 'N/A'"></strong>
                                            </div>
                                        </template>
                                        <div>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Expected Sale</span>
                                            <strong class="text-emerald-700 text-xs" x-text="activeUnit.expected_sale_amount ? '₹' + Number(activeUnit.expected_sale_amount).toLocaleString() : 'N/A'"></strong>
                                        </div>
                                    </div>
                                    <template x-if="activeUnit.sale_rate_per_sqft">
                                        <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-200 pt-3">
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Sale Rate</span>
                                                <strong class="text-slate-900 text-xs" x-text="'₹' + Number(activeUnit.sale_rate_per_sqft).toLocaleString()"></strong>
                                            </div>
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Sale Amount</span>
                                                <strong class="text-emerald-700 text-xs" x-text="'₹' + Number(activeUnit.sale_amount).toLocaleString()"></strong>
                                            </div>
                                            <div class="col-span-2 border-t border-slate-200 pt-2">
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Difference</span>
                                                <strong class="text-rose-600 text-xs" x-text="'₹' + Number(activeUnit.difference).toLocaleString()"></strong>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="submit"
                                    class="w-full py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-xs font-bold transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">
                                    Save Details
                                </button>
                            </div>
                        </form>
                    </template>
                    <template x-if="!permissions.manage">
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block border-b pb-1">Unit Info (Read-Only)</span>
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium">
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Floor</p><p class="text-slate-900" x-text="activeUnit.floor ? activeUnit.floor.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Type</p><p class="text-slate-900" x-text="activeUnit.unit_type ? activeUnit.unit_type.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Built Up Area</p><p class="text-slate-900" x-text="activeUnit.built_up_area + ' Sq Ft'"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Carpet Area</p><p class="text-slate-900" x-text="activeUnit.carpet_area ? activeUnit.carpet_area + ' Sq Ft' : 'N/A'"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Expected Rate</p><p class="text-slate-900" x-text="'₹' + Number(activeUnit.expected_rate_per_sqft).toLocaleString()"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Expected Sale</p><p class="text-slate-900" x-text="'₹' + Number(activeUnit.expected_sale_amount).toLocaleString()"></p></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- RIGHT PANE: Status Transitions & Rate Updates --}}
                <div class="p-6 space-y-5 overflow-y-auto">
                    {{-- Status Transitions --}}
                    <div class="space-y-3">
                        <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Status Transitions</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-if="['available', 'blocked'].includes(activeUnit.status)">
                                <a :href="'{{ route('bookings.create') }}?unit_id=' + activeUnit.id"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition uppercase tracking-wider shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                    Book Unit
                                </a>
                            </template>
                            <template x-for="state in allowedTransitions" :key="state">
                                <button type="button" @click="transitionStatus(state)"
                                        class="px-3 py-1.5 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-lg text-xs transition">
                                    Move to <span class="capitalize font-bold" x-text="state"></span>
                                </button>
                            </template>
                            <template x-if="activeUnit.status === 'sold' && allowedTransitions.includes('available')">
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-500 ml-1 cursor-pointer">
                                    <input type="checkbox" x-model="forms.status.is_resale" class="rounded text-[#a38c29] focus:ring-[#a38c29]/20">
                                    <span class="font-bold">Resale Flag</span>
                                </label>
                            </template>
                            <template x-if="allowedTransitions.length === 0">
                                <p class="text-xs text-slate-400 italic">No transitions available.</p>
                            </template>
                        </div>
                        <div x-show="allowedTransitions.length > 0">
                            <input type="text" x-model="forms.status.reason" placeholder="Reason for transition (optional)..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    {{-- Rate Update Form --}}
                    <template x-if="permissions.rateManage">
                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Update Base Rate</p>
                            <form @submit.prevent="submitUpdateRate()">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">New Rate (₹)</label>
                                            <input type="number" step="0.01" x-model="forms.rate.rate" placeholder="e.g. 5000"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Effective From</label>
                                            <input type="date" x-model="forms.rate.effective_from"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                        </div>
                                    </div>
                                    <input type="text" x-model="forms.rate.reason" placeholder="Reason for rate change..."
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    <button type="submit"
                                        class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition uppercase tracking-wide">
                                        Update Rate
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>

                    {{-- Pricing History Logs --}}
                    <div class="space-y-3 border-t border-slate-100 pt-4">
                        <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Pricing Log History</p>
                        <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                            <template x-for="log in activeUnit.rate_logs" :key="log.id">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[10px] space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-900 text-xs" x-text="'₹' + Number(log.rate).toLocaleString()"></span>
                                        <span class="text-slate-400 font-medium" x-text="log.effective_from"></span>
                                    </div>
                                    <p class="text-slate-500" x-text="log.reason || 'No reason provided'"></p>
                                    <p class="text-[9px] text-slate-400 font-semibold" x-text="'By: ' + (log.user ? log.user.name : 'System')"></p>
                                </div>
                            </template>
                            <template x-if="!activeUnit.rate_logs || activeUnit.rate_logs.length === 0">
                                <p class="text-xs text-slate-400 italic">No rate changes logged.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                <button type="button" @click="closeEditModal()"
                    class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 3: BULK ADD UNITS
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.bulk.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        {{-- Backdrop --}}
        <div x-show="modals.bulk.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeBulkModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.bulk.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Bulk Generation</p>
                        <h2 class="text-lg font-extrabold text-white">Bulk Add Units</h2>
                    </div>
                    <button @click="closeBulkModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitBulkAdd()" class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                <div class="p-6 space-y-4 overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Target Floor</label>
                            <select x-model="forms.bulk.floor_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type</label>
                            <select x-model="forms.bulk.unit_type_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                <option value="">Select Type...</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_type_id[0]"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Prefix (e.g. A-)</label>
                            <input type="text" x-model="forms.bulk.unit_prefix" placeholder="A-" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.unit_prefix"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_prefix[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Starting Num</label>
                            <input type="number" x-model="forms.bulk.start_number" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.start_number"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.start_number[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Count</label>
                            <input type="number" x-model="forms.bulk.count" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.count"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.count[0]"></p></template>
                        </div>
                    </div>

                    {{-- Area fields: hidden for Parking --}}
                    <template x-if="!isParking(forms.bulk.unit_type_id)">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.bulk.built_up_area" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.built_up_area[0]"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.bulk.carpet_area" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                            </div>
                        </div>
                    </template>

                    {{-- Expected Rate: hidden for Parking --}}
                    <template x-if="!isParking(forms.bulk.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Rate per Sq Ft (₹)</label>
                            <input type="number" step="0.01" x-model="forms.bulk.expected_rate_per_sqft" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_rate_per_sqft"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_rate_per_sqft[0]"></p></template>
                        </div>
                    </template>

                    {{-- Parking: Expected Sale Amount --}}
                    <template x-if="isParking(forms.bulk.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.bulk.expected_sale_amount" placeholder="e.g. 300000" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_sale_amount[0]"></p></template>
                        </div>
                    </template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="closeBulkModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">Generate Units</button>
                </div>
            </form>
        </div>
    </div>



{{-- ═══════════════════════ EDIT PROJECT MODAL ═══════════════════════ --}}
<div x-show="editProjectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    {{-- Backdrop --}}
    <div x-show="editProjectModal"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="editProjectModal = false"
         class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- Modal panel --}}
    <div x-show="editProjectModal"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col"
         @click.stop>
        
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 flex-shrink-0">
            <div class="absolute -top-10 -right-10 w-36 h-36 bg-[#a38c29]/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[#a38c29] text-[9px] font-bold uppercase tracking-widest mb-0.5">Edit Project</p>
                    <h2 class="text-xs font-extrabold text-white">{{ $project->name }}</h2>
                    <!-- <p class="text-slate-400 text-[10px] mt-0.5 font-mono">{{ $project->code }}</p> -->
                </div>
                <button @click="editProjectModal = false" class="text-slate-400 hover:text-white transition-colors duration-150 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
            @csrf
            @method('PUT')

            {{-- Single-pane body --}}
            <div class="p-5 space-y-4 overflow-y-auto flex-1 min-h-0">
                {{-- Media & Image --}}
                <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Media & Image</p>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0 relative">
                            <img x-show="!imagePreview" src="{{ $projectImage }}" class="w-full h-full object-cover" alt="Project image">
                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover" x-cloak>
                        </div>
                        <div class="flex-1">
                            <label class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-lg transition shadow-sm uppercase tracking-wide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Upload
                                <input type="file" name="image" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file);">
                            </label>
                            <p class="text-[9px] text-slate-400 mt-1">JPG, PNG up to 2MB</p>
                        </div>
                    </div>
                </div>

                {{-- Project Details Section --}}
                <div class="space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Project Details</p>
                    
                   
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Name</label>
                            <input type="text" name="name" value="{{ old('name', $project->name) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                       
                    

                    <div class="space-y-1">
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Location / Address</label>
                        <input type="text" name="location" value="{{ old('location', $project->location) }}"
                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">City</label>
                            <input type="text" name="city" value="{{ old('city', $project->city) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">State</label>
                            <input type="text" name="state_or_emirate" value="{{ old('state_or_emirate', $project->state_or_emirate) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Country</label>
                            <input type="text" name="country" value="{{ old('country', $project->country) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                </div>

                {{-- Status & Scope Section --}}
                <div class="space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Status & Scope</p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Status</label>
                            <select name="status" class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                @foreach(['planning' => 'Planning', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'on_hold' => 'On Hold'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $project->status) == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total Floors</label>
                            <input type="number" name="total_floors" value="{{ old('total_floors', $project->total_floors) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Target Completion</label>
                            <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', $project->expected_completion_date ? \Carbon\Carbon::parse($project->expected_completion_date)->format('Y-m-d') : '') }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    <div class="space-y-1 mt-3">
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Description</label>
                        <textarea name="description" id="ck_units_project_description" rows="4"
                            placeholder="Write a detailed project description..."
                            class="ck-editor-field w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none">{{ old('description', $project->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3.5 border-t border-slate-150 flex items-center justify-end gap-2.5 bg-slate-50 flex-shrink-0">
                <button type="button" @click="editProjectModal = false"
                    class="px-3.5 py-1.5 border border-slate-250 hover:bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg transition uppercase tracking-wide">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-lg transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>















    {{-- ═══════════════════════════════════════════
         MODAL 4: DELETE CONFIRMATION
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-rose-600 uppercase tracking-widest">Delete Unit</h3>
                <button @click="closeDeleteModal()" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-slate-600 font-medium">
                    Are you sure you want to delete unit <strong class="text-slate-900" x-text="activeUnit.door_no"></strong>?
                </p>
                <p class="text-[10px] text-rose-500 font-bold uppercase tracking-wider">This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                <button type="button" @click="submitDelete()" class="px-4 py-2 bg-rose-600 hover:bg-rose-550 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-rose-600/5">Confirm Delete</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW UNIT MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-cloak>
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="modals.view.open = false">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Unit Details & Specifications</h3>
                </div>
                <button @click="modals.view.open = false" class="text-slate-400 hover:text-slate-600 text-base">✕</button>
            </div>

            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Door / Unit No</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewTarget?.door_no"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono uppercase inline-block mt-0.5"
                              :class="getStatusBadgeClass(viewTarget?.status)"
                              x-text="viewTarget?.status"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.floor?.name"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Type</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.unit_type?.name"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built-up Area (BUA)</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="viewTarget?.built_up_area != null ? Number(viewTarget.built_up_area).toLocaleString() + ' Sq Ft' : 'N/A'"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Carpet Area</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="viewTarget?.carpet_area != null ? Number(viewTarget.carpet_area).toLocaleString() + ' Sq Ft' : 'N/A'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate / Sq Ft</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="(viewTarget?.unit_type?.name?.toLowerCase() === 'parking' || viewTarget?.unit_type?.category?.toLowerCase() === 'parking') ? 'N/A' : (viewTarget?.expected_rate_per_sqft != null ? '₹' + Number(viewTarget.expected_rate_per_sqft).toLocaleString() : 'N/A')"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Sale Amount</span>
                        <span class="text-xs font-bold text-emerald-700 mt-0.5 block font-mono" x-text="viewTarget?.expected_sale_amount != null ? '₹' + Number(viewTarget.expected_sale_amount).toLocaleString() : 'N/A'"></span>
                    </div>
                </div>

                <template x-if="viewTarget?.sale_amount || (viewTarget?.gst_behavior && viewTarget?.gst_behavior !== 'none') || viewTarget?.booking">
                    <div class="p-3.5 rounded-xl border border-amber-200/80 bg-amber-50/50 shadow-2xs space-y-2.5 mt-2">
                        <div class="flex items-center justify-between border-b border-amber-200/60 pb-1.5">
                            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Active Sale & GST Handling</span>
                            <span class="text-[9px] font-mono px-2 py-0.5 rounded-full font-bold uppercase"
                                  :class="viewTarget?.gst_behavior === 'inclusive' ? 'bg-amber-100 text-amber-800' : (viewTarget?.gst_behavior === 'exclusive' ? 'bg-purple-100 text-purple-800' : 'bg-[#a38c29] text-white')"
                                  x-text="viewTarget?.gst_behavior === 'inclusive' ? 'GST Included (18%)' : (viewTarget?.gst_behavior === 'exclusive' ? 'GST Additional (+18%)' : 'No GST')"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Sale Rate / Sq Ft</span>
                                <strong class="text-slate-800 font-mono" x-text="(viewTarget?.unit_type?.name?.toLowerCase() === 'parking' || viewTarget?.unit_type?.category?.toLowerCase() === 'parking') ? 'N/A' : (viewTarget?.sale_rate_per_sqft ? '₹' + Number(viewTarget.sale_rate_per_sqft).toLocaleString() : 'N/A')"></strong>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Total Sale Value</span>
                                <strong class="text-emerald-700 font-mono" x-text="viewTarget?.sale_amount ? '₹' + Number(viewTarget.sale_amount).toLocaleString() : 'N/A'"></strong>
                            </div>
                            <div class="col-span-2 pt-1 border-t border-amber-200/50 flex justify-between items-center" x-show="viewTarget?.gst_behavior !== 'none'">
                                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">GST Amount Payable (18%)</span>
                                <strong class="text-indigo-700 font-mono text-xs" x-text="viewTarget?.gst_amount ? '₹' + Number(viewTarget.gst_amount).toLocaleString() : '₹0.00'"></strong>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end bg-slate-50">
                <button type="button" @click="modals.view.open = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         RATE HISTORY MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.rateHistory.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-cloak>
        <div class="w-full max-w-4xl bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up" @click.away="modals.rateHistory.open = false">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-slate-50/70">
                <div class="space-y-1">
                    <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <span>Home</span>
                        <span>/</span>
                        <span>All Units</span>
                        <span>/</span>
                        <span class="text-[rgb(67,56,212)]" style="color: rgb(67 56 212);">Rate History</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[rgb(67,56,212)]/10 flex items-center justify-center text-[rgb(67,56,212)]" style="color: rgb(67 56 212);">
                            <svg class="w-4 h-4" style="color: rgb(67 56 212);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-950 tracking-tight">Rate Change History Register</h3>
                    </div>
                </div>
                <button @click="modals.rateHistory.open = false" class="text-slate-400 hover:text-slate-600 text-lg p-1 transition">✕</button>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="p-6 overflow-y-auto flex-grow space-y-5">
                {{-- Top Summary Theme Card --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50/70 via-white to-amber-50/40 border border-[#a38c29]/30 p-5 shadow-md text-slate-900">
                    {{-- Decorative Background Glows --}}
                    <div class="absolute -top-14 -right-14 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-14 -left-14 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#a38c29]/15 relative z-10">
                        <div class="flex items-center gap-3.5">
                            <div class="min-w-[3rem] min-h-[3rem] px-3.5 py-2 rounded-xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] flex items-center justify-center text-white font-black text-sm md:text-base tracking-tight shadow-md shadow-[#a38c29]/20 border border-[#a38c29]/20 max-w-[220px] break-words text-center leading-tight flex-shrink-0" x-text="rateHistoryTarget?.door_no || 'U'">
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#a38c29] bg-[#a38c29]/10 px-2.5 py-0.5 rounded-full border border-[#a38c29]/30">Unit Profile</span>
                                    <span class="text-xs font-bold text-slate-600" x-show="rateHistoryTarget?.unit_type?.name" x-text="rateHistoryTarget?.unit_type?.name"></span>
                                </div>
                                <h4 class="text-lg font-black tracking-tight text-slate-900 mt-0.5" x-text="'Unit ' + (rateHistoryTarget?.door_no || 'N/A')"></h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <div class="px-3 py-1.5 rounded-xl bg-white border border-[#a38c29]/20 shadow-2xs">
                                <span class="text-slate-500 font-medium">Floor:</span>
                                <strong class="text-slate-900 ml-1 font-bold" x-text="rateHistoryTarget?.floor?.name || 'N/A'"></strong>
                            </div>
                            <div class="px-3 py-1.5 rounded-xl bg-white border border-[#a38c29]/20 shadow-2xs" x-show="!isParking(rateHistoryTarget?.unit_type_id)">
                                <span class="text-slate-500 font-medium">Built-up Area:</span>
                                <strong class="text-slate-900 ml-1 font-mono font-bold" x-text="rateHistoryTarget?.built_up_area != null ? Number(rateHistoryTarget.built_up_area).toLocaleString('en-IN', {minimumFractionDigits: 1, maximumFractionDigits: 2}) + ' Sq.Ft' : 'N/A'"></strong>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Row: Financial / Pricing Metrics --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 pt-4 relative z-10">
                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Unit Type</span>
                            <span class="text-xs font-bold text-slate-800 mt-1 block" x-text="rateHistoryTarget?.unit_type?.name || 'Flat'"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Floor Level</span>
                            <span class="text-xs font-bold text-slate-800 mt-1 block" x-text="rateHistoryTarget?.floor?.name || 'N/A'"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-[#a38c29]/15 via-amber-500/10 to-white border border-[#a38c29]/40 shadow-sm">
                            <span class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-widest block flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                                Current Rate
                            </span>
                            <span class="text-sm font-black text-slate-950 mt-1 block font-mono" x-text="rateHistoryTarget?.expected_rate_per_sqft != null ? '₹' + Number(rateHistoryTarget.expected_rate_per_sqft).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + (isParking(rateHistoryTarget?.unit_type_id) ? '' : '/Sq.Ft') : (isParking(rateHistoryTarget?.unit_type_id) ? '₹0.00' : '₹0.00/Sq.Ft')"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500/15 via-emerald-500/10 to-white border border-emerald-500/40 shadow-sm">
                            <span class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-widest block flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Expected Sale Value
                            </span>
                            <span class="text-sm font-black text-slate-950 mt-1 block font-mono tracking-tight" x-text="rateHistoryTarget?.expected_sale_amount != null ? '₹' + Number(rateHistoryTarget.expected_sale_amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₹0.00'"></span>
                        </div>
                    </div>
                </div>

                {{-- Loading State --}}
                <div x-show="loadingRateHistory" class="py-12 text-center space-y-3">
                    <div class="inline-block w-8 h-8 border-3 border-[#a38c29] border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-500">Fetching latest rate logs and audit trails...</p>
                </div>

                {{-- Empty State --}}
                <div x-show="!loadingRateHistory && (!rateHistoryLogs || rateHistoryLogs.length === 0)" class="py-12 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-full bg-[rgb(67,56,212)]/10 text-[rgb(67,56,212)] flex items-center justify-center mx-auto mb-3" style="color: rgb(67 56 212);">
                        <svg class="w-6 h-6" style="color: rgb(67 56 212);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">No Rate Modifications Logged</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">The rate for this unit has not been modified since its initial setup.</p>
                </div>

                {{-- Table of Rate Changes --}}
                <div x-show="!loadingRateHistory && rateHistoryLogs && rateHistoryLogs.length > 0" class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Rate Change History</span>
                            <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-[10px] text-slate-600" x-text="rateHistoryLogs.length + ' records'"></span>
                        </h4>
                        <div class="flex items-center gap-3 text-[10px] font-medium text-slate-500">
                            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> IST (UTC+5:30)</span>
                            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> UAE (UTC+4:00)</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-3.5 py-3 w-12 text-center">#</th>
                                    <th class="px-4 py-3">Changed At</th>
                                    <th class="px-4 py-3 text-right">Old Rate <span x-show="!isParking(rateHistoryTarget?.unit_type_id)">(₹/Sq.Ft)</span></th>
                                    <th class="px-4 py-3 text-right">New Rate <span x-show="!isParking(rateHistoryTarget?.unit_type_id)">(₹/Sq.Ft)</span></th>
                                    <th class="px-4 py-3 text-right">Change</th>
                                    <th class="px-4 py-3">Changed By</th>
                                    <th class="px-4 py-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <template x-for="(log, index) in rateHistoryLogs" :key="log.id || index">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-3.5 py-3 text-center font-bold text-slate-400 font-mono" x-text="index + 1"></td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                                <span x-text="formatDate(log)"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-medium text-slate-500 whitespace-nowrap"
                                            x-text="log.previous_rate !== undefined && log.previous_rate !== null ? '₹' + Number(log.previous_rate).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₹0.00'">
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5">
                                                <span x-text="'₹' + Number(log.rate).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                <template x-if="index === 0">
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase bg-emerald-100 text-emerald-800">Current</span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap font-mono font-extrabold">
                                            <span class="px-2 py-0.5 rounded-md text-[11px]"
                                                  :class="(Number(log.rate) - Number(log.previous_rate || 0)) >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'"
                                                  x-text="((Number(log.rate) - Number(log.previous_rate || 0)) >= 0 ? '+₹' : '-₹') + Math.abs(Number(log.rate) - Number(log.previous_rate || 0)).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center font-bold text-[10px] flex-shrink-0"
                                                     x-text="log.user ? log.user.name.charAt(0).toUpperCase() : 'S'"></div>
                                                <div>
                                                    <div class="font-bold text-slate-800 text-xs" x-text="log.user ? log.user.name : (log.changed_by ? 'User #' + log.changed_by : 'User')"></div>
                                                    <div class="text-[10px] text-slate-400" x-text="log.user ? (log.user.email || 'Authorized Role') : 'System / Admin'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700">
                                            <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 text-xs font-medium whitespace-nowrap" x-text="log.reason || 'Initial rate set'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end bg-slate-50 flex-shrink-0">
                <button type="button" @click="modals.rateHistory.open = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

    </div>

</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC CODE
═══════════════════════════════════════════ --}}
<script>
function unitsApp() {
    return {
        // App states
        projectId: {{ $project->id }},
        unitTypeMap: {!! json_encode($unitTypes->keyBy('id')->map(fn($t) => ['name' => $t->name, 'category' => $t->category])) !!},
        editProjectModal: false,
        imagePreview: null,
        units: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 50
        },
        filters: {
            search: '',
            floor_id: '',
            unit_type_id: '',
            status: ''
        },
        permissions: {
            manage: {{ auth()->user()->hasPermissionTo('units.manage') ? 'true' : 'false' }},
            rateManage: {{ auth()->user()->hasPermissionTo('units.rate.manage') ? 'true' : 'false' }}
        },
        modals: {
            add: { open: false },
            edit: { open: false },
            bulk: { open: false },
            delete: { open: false },
            view: { open: false },
            rateHistory: { open: false }
        },
        viewTarget: null,
        rateHistoryTarget: null,
        rateHistoryLogs: [],
        loadingRateHistory: false,
        forms: {
            add: {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            },
            edit: {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_sale_amount: ''
            },
            bulk: {
                floor_id: '',
                unit_type_id: '',
                unit_prefix: '',
                start_number: 1,
                count: 10,
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            },
            status: {
                status: '',
                reason: '',
                is_resale: false
            },
            rate: {
                rate: '',
                effective_from: new Date().toISOString().split('T')[0],
                reason: ''
            }
        },
        activeUnit: {},
        allowedTransitions: [],
        errors: {},
        toast: {
            open: false,
            message: '',
            type: 'success'
        },

        init() {
            this.fetchUnits();
        },

        // Fetch Listings
        fetchUnits(page = 1) {
            let params = new URLSearchParams();
            params.append('page', page);
            if (this.projectId) params.append('project_id', this.projectId);
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.floor_id) params.append('floor_id', this.filters.floor_id);
            if (this.filters.unit_type_id) params.append('unit_type_id', this.filters.unit_type_id);
            if (this.filters.status) params.append('status', this.filters.status);

            fetch('{{ route('units.index') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.units = data.units;
                if(data.pagination) {
                    this.pagination = data.pagination;
                }
            })
            .catch(err => {
                console.error('Error fetching units:', err);
                this.showToast('Failed to fetch units list.', 'error');
            });
        },

        getPageNumbers() {
            let current = this.pagination.current_page;
            let last = this.pagination.last_page;
            let delta = 2;
            let left = current - delta;
            let right = current + delta + 1;
            let range = [];
            let rangeWithDots = [];
            let l;

            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || (i >= left && i < right)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l > 2) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            return rangeWithDots;
        },

        isParking(typeId) {
            if (!typeId || !this.unitTypeMap[typeId]) return false;
            const info = this.unitTypeMap[typeId];
            return (info.name || '').toLowerCase() === 'parking' || (info.category || '').toLowerCase() === 'parking';
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.floor_id = '';
            this.filters.unit_type_id = '';
            this.filters.status = '';
            this.fetchUnits();
        },

        // Group units by floor for rowspan rendering
        groupedUnits() {
            let groups = [];
            let currentFloorId = null;
            let currentGroup = null;
            for (let unit of this.units) {
                let floorId = unit.floor ? unit.floor.id : null;
                if (floorId !== currentFloorId) {
                    currentFloorId = floorId;
                    currentGroup = {
                        floor_id: floorId,
                        floor_name: unit.floor ? unit.floor.name : 'Unknown Floor',
                        units: []
                    };
                    groups.push(currentGroup);
                }
                currentGroup.units.push(unit);
            }
            return groups;
        },

        // Render units table with rowspan floor grouping via direct DOM injection
        renderUnitsTable() {
            const tbody = document.getElementById('units-tbody');
            if (!tbody) return;

            if (this.units.length === 0) {
                tbody.innerHTML = `<tr><td colspan="12" class="px-6 py-10 text-center text-slate-400 italic">No units match the query filters.</td></tr>`;
                return;
            }

            const fmtNum = (v) => v != null && v !== '' ? Number(v).toLocaleString() : 'N/A';
            const fmtMoney = (v) => v != null && v !== '' ? '₹' + Number(v).toLocaleString() : 'N/A';
            const fmtArea = (v) => v != null && v !== '' ? Number(v).toLocaleString() + ' Sq Ft' : 'N/A';
            const statusBadge = (s) => {
                const cls = {
                    'available': 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                    'blocked': 'bg-amber-50 text-amber-700 border border-amber-100',
                    'booked': 'bg-indigo-50 text-indigo-700 border border-indigo-100',
                    'sold': 'bg-rose-50 text-rose-700 border border-rose-100',
                }[s] || 'bg-slate-50 text-slate-700 border border-slate-200';
                return `<span class="badge-pill ${cls}">${s}</span>`;
            };
            const canManage = this.permissions.manage;
            const actionsBtns = (unit) => {
                if (!canManage) return '';
                const disabledDel = unit.status !== 'available' ? 'opacity-30 cursor-not-allowed' : '';
                return `<div class="inline-flex items-center justify-end gap-1.5">
                    <button data-action="view" data-id="${unit.id}" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] transition inline-flex items-center justify-center shadow-sm" title="View Unit Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button data-action="rate-history" data-id="${unit.id}" class="p-2 rounded-lg bg-[rgb(67,56,212)]/10 hover:bg-[rgb(67,56,212)]/20 text-[rgb(67,56,212)] transition inline-flex items-center justify-center shadow-sm" title="View Rate History">
                        <svg class="w-4 h-4" style="color:rgb(67 56 212)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                    <button data-action="edit" data-id="${unit.id}" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] transition inline-flex items-center justify-center shadow-sm" title="Edit Unit">
                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button data-action="delete" data-id="${unit.id}" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 transition inline-flex items-center justify-center shadow-sm ${disabledDel}" title="Delete Unit" ${unit.status !== 'available' ? 'disabled' : ''}>
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>`;
            };

            let html = '';
            const groups = this.groupedUnits();
            for (const group of groups) {
                group.units.forEach((unit, ui) => {
                    html += `<tr class="unit-table-row transition-colors cursor-pointer text-center text-xs font-semibold text-slate-700" data-unit-id="${unit.id}">`;
                    if (ui === 0) {
                        html += `<td rowspan="${group.units.length}" class="border text-slate-900 font-extrabold text-[11px] uppercase bg-[#a38c29]/10 select-none" style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg);min-width:38px;padding:14px 8px;text-align:center;vertical-align:middle;letter-spacing:0.13em;">${group.floor_name}</td>`;
                    }
                    const isParkingUnit = unit.unit_type && (unit.unit_type.name.toLowerCase() === 'parking' || (unit.unit_type.category || '').toLowerCase() === 'parking');
                    const expRateDisp = isParkingUnit ? 'N/A' : fmtMoney(unit.expected_rate_per_sqft);
                    const saleRateDisp = isParkingUnit ? 'N/A' : fmtMoney(unit.sale_rate_per_sqft);

                    html += `
                        <td class="px-3 py-3 border text-slate-600">${unit.unit_type ? unit.unit_type.name : ''}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${unit.door_no}</td>
                        <td class="px-3 py-3 border">${fmtArea(unit.built_up_area)}</td>
                        <td class="px-3 py-3 border">${fmtArea(unit.carpet_area)}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${expRateDisp}</td>
                        <td class="px-3 py-3 border font-bold text-emerald-700">${fmtMoney(unit.expected_sale_amount)}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${saleRateDisp}</td>
                        <td class="px-3 py-3 border font-bold">${fmtMoney(unit.sale_amount)}</td>
                        <td class="px-3 py-3 border font-bold">${fmtMoney(unit.difference)}</td>
                        <td class="px-3 py-3 border">${statusBadge(unit.status)}</td>
                        <td class="px-3 py-3 border text-right">${actionsBtns(unit)}</td>
                    </tr>`;
                });
            }
            tbody.innerHTML = html;

            // Attach click handlers via delegation
            const self = this;
            tbody.querySelectorAll('[data-action]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = parseInt(this.dataset.id);
                    const unit = self.units.find(u => u.id === id);
                    if (this.dataset.action === 'view') self.openViewModal(unit);
                    else if (this.dataset.action === 'rate-history') self.openRateHistoryModal(unit);
                    else if (this.dataset.action === 'edit') self.openEditModal(id);
                    else if (this.dataset.action === 'delete') self.confirmDelete(unit);
                });
            });
            tbody.querySelectorAll('.unit-table-row').forEach(row => {
                const id = parseInt(row.dataset.unitId);
                row.addEventListener('click', function() {
                    self.openEditModal(id);
                });
            });
        },

        // Helper Status Colors
        getStatusBadgeClass(status) {
            switch(status) {
                case 'available': return 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                case 'blocked': return 'bg-amber-50 text-amber-700 border border-amber-100';
                case 'booked': return 'bg-indigo-50 text-indigo-700 border border-indigo-100';
                case 'sold': return 'bg-rose-50 text-rose-700 border border-rose-100';
                default: return 'bg-slate-50 text-slate-700 border border-slate-200';
            }
        },

        // Toast Messages
        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        },

        // Modal triggers
        openAddModal() {
            this.errors = {};
            this.forms.add = {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: ''
            };
            this.modals.add.open = true;
        },
        closeAddModal() {
            this.modals.add.open = false;
        },

        openViewModal(unit) {
            this.viewTarget = unit;
            this.modals.view.open = true;
        },

        openRateHistoryModal(unit) {
            this.rateHistoryTarget = unit;
            this.rateHistoryLogs = unit.rate_logs || [];
            this.modals.rateHistory.open = true;
            this.loadingRateHistory = true;

            fetch(`{{ url('units') }}/${unit.id}/json`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.unit && data.unit.rate_logs) {
                    this.rateHistoryLogs = data.unit.rate_logs;
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                this.loadingRateHistory = false;
            });
        },

        parseLogDate(log) {
            let raw = log.created_at || log.effective_from;
            if (!raw) return new Date();
            
            if (typeof raw === 'string' && !raw.includes('Z') && !raw.match(/[+-]\d{2}:?\d{2}$/)) {
                // Replace space with T and append Z to force UTC parsing for Laravel timestamps
                raw = raw.replace(' ', 'T') + 'Z';
            }
            
            let dt = new Date(raw);
            return isNaN(dt.getTime()) ? new Date() : dt;
        },

        formatDate(log) {
            try {
                const dt = this.parseLogDate(log);
                return new Intl.DateTimeFormat('en-IN', {
                    timeZone: 'Asia/Kolkata',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).format(dt);
            } catch (e) {
                return (log.created_at || log.effective_from || '');
            }
        },

        openEditModal(unitId) {
            this.errors = {};
            this.forms.status = { status: '', reason: '', is_resale: false };
            this.forms.rate = { rate: '', effective_from: new Date().toISOString().split('T')[0], reason: '' };

            fetch(`{{ url('units') }}/${unitId}/json`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    this.showToast(data.error, 'error');
                    return;
                }
                this.activeUnit = data.unit;
                this.allowedTransitions = data.allowed_transitions;

                // Prefill Form
                this.forms.edit = {
                    floor_id: this.activeUnit.floor_id,
                    unit_type_id: this.activeUnit.unit_type_id,
                    door_no: this.activeUnit.door_no,
                    built_up_area: this.activeUnit.built_up_area,
                    carpet_area: this.activeUnit.carpet_area,
                    expected_sale_amount: this.activeUnit.expected_sale_amount
                };

                this.modals.edit.open = true;
            })
            .catch(err => {
                console.error(err);
                this.showToast('Failed to load unit details.', 'error');
            });
        },
        closeEditModal() {
            this.modals.edit.open = false;
        },

        openBulkModal() {
            this.errors = {};
            this.forms.bulk = {
                floor_id: '',
                unit_type_id: '',
                unit_prefix: '',
                start_number: 1,
                count: 10,
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            };
            this.modals.bulk.open = true;
        },
        closeBulkModal() {
            this.modals.bulk.open = false;
        },

        // Deletions
        confirmDelete(unit) {
            if (unit.status !== 'available') {
                this.showToast('Only available units can be deleted.', 'error');
                return;
            }
            this.activeUnit = unit;
            this.modals.delete.open = true;
        },
        closeDeleteModal() {
            this.modals.delete.open = false;
        },

        // POST/AJAX Actions
        submitAddUnit() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.add.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.add.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.add.door_no || !this.forms.add.door_no.trim()) {
                clientErrors.door_no = ['The door number field is required.'];
            }

            const isParking = this.isParking(this.forms.add.unit_type_id);
            if (isParking) {
                if (!this.forms.add.expected_sale_amount && this.forms.add.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            } else {
                if (!this.forms.add.expected_rate_per_sqft && this.forms.add.expected_rate_per_sqft !== 0) {
                    clientErrors.expected_rate_per_sqft = ['The expected rate per sq ft field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            let payload = {
                project_id: this.projectId,
                ...this.forms.add
            };

            fetch('{{ route('units.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Unit added successfully.');
                    this.closeAddModal();
                    this.resetFilters();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitEditUnit() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.edit.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.edit.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.edit.door_no || !this.forms.edit.door_no.trim()) {
                clientErrors.door_no = ['The door number field is required.'];
            }

            const isParking = this.isParking(this.forms.edit.unit_type_id);
            if (isParking) {
                if (!this.forms.edit.expected_sale_amount && this.forms.edit.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            fetch(`{{ url('units') }}/${this.activeUnit.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.edit)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Unit details updated successfully.');
                    // Update table & close modal
                    this.fetchUnits();
                    this.closeEditModal();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitBulkAdd() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.bulk.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.bulk.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.bulk.start_number && this.forms.bulk.start_number !== 0) {
                clientErrors.start_number = ['The starting number is required.'];
            }
            if (!this.forms.bulk.count) {
                clientErrors.count = ['The count is required.'];
            }

            const isParking = this.isParking(this.forms.bulk.unit_type_id);
            if (isParking) {
                if (!this.forms.bulk.expected_sale_amount && this.forms.bulk.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            } else {
                if (!this.forms.bulk.expected_rate_per_sqft && this.forms.bulk.expected_rate_per_sqft !== 0) {
                    clientErrors.expected_rate_per_sqft = ['The expected rate per sq ft field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            let payload = {
                project_id: this.projectId,
                ...this.forms.bulk
            };

            fetch('{{ route('units.bulk-store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast(`Bulk created ${data.count} units successfully.`);
                    this.closeBulkModal();
                    this.resetFilters();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitDelete() {
            fetch(`{{ url('units') }}/${this.activeUnit.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to delete unit.', 'error');
                } else {
                    this.showToast('Unit deleted successfully.');
                    this.closeDeleteModal();
                    this.fetchUnits();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        // Status Transition Handler
        transitionStatus(targetState) {
            let payload = {
                status: targetState,
                reason: this.forms.status.reason,
                is_resale: this.forms.status.is_resale ? 1 : 0
            };

            fetch(`{{ url('units') }}/${this.activeUnit.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to update status.', 'error');
                } else {
                    this.showToast(`Transitioned status to ${targetState} successfully.`);
                    this.fetchUnits();
                    this.openEditModal(this.activeUnit.id);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        // Rate History Handler
        submitUpdateRate() {
            fetch(`{{ url('units') }}/${this.activeUnit.id}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.rate)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to update base rate.', 'error');
                } else {
                    this.showToast('Base rate updated successfully.');
                    this.fetchUnits();
                    this.closeEditModal();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        }
    };
}
</script>

</x-erp-layout>
