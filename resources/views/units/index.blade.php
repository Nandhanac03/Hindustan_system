<x-erp-layout title="Units Directory" headerTitle="Units Directory">



<div class="max-w-[1400px] mx-auto space-y-6" x-data="unitsApp()">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                    Project: <span class="text-primary-700">{{ $project->name }}</span>
                </h1>
                <span class="text-[10px] font-bold px-2 py-0.5 bg-primary-50 text-primary-800 rounded-lg border border-primary-200">{{ $project->code }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage single unit listings, pricing matrices, and floor allocations.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <template x-if="permissions.manage">
                <div class="flex items-center gap-2">
                    <button @click="openBulkModal()" class="btn-ripple inline-flex items-center gap-2 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition shadow-sm uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Bulk Add
                    </button>
                    <button @click="openAddModal()" class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-primary-600/10 uppercase tracking-wide">
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
        $projectImage = $project->image_url ?: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80';
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row gap-6 p-5">
        {{-- Project Image --}}
        <div class="w-full md:w-[240px] h-[160px] rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100 border border-slate-150">
            <img src="{{ $projectImage }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
            <div class="absolute bottom-3 left-3">
                <span class="text-[9px] font-bold px-2 py-0.5 bg-primary text-white rounded-md uppercase font-mono tracking-wider">{{ $project->code }}</span>
            </div>
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
        class="px-2.5 py-1 bg-white border border-[#a38c29] hover:bg-[#a38c29]/10 text-[#a38c29] hover:text-[#8a7522] font-bold rounded-lg transition text-[10px] uppercase tracking-wide flex items-center gap-1"
    >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Project
    </button>
@endcan
                    </div>
                </div>

                @if($project->description)
                    <p class="text-xs text-slate-550 leading-relaxed max-w-2xl font-medium pt-1">{{ $project->description }}</p>
                @else
                    <p class="text-xs text-slate-400 italic pt-1">No description provided for this project.</p>
                @endif
            </div>

            {{-- Summary of Statistics / RERA --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-slate-100 pt-4 mt-4 text-xs font-semibold text-slate-500">
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">RERA Certificate</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->rera_number ?? 'Exempt/Pending' }}</strong>
                </div>
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
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 flex-1">
            {{-- Search --}}
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search Unit Number..." 
                       x-model="filters.search" @input.debounce.300ms="fetchUnits()"
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs placeholder-slate-450 focus:outline-none transition-all">
            </div>

            {{-- Floor Filter --}}
            <select x-model="filters.floor_id" @change="fetchUnits()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Floors</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                @endforeach
            </select>

            {{-- Unit Type Filter --}}
            <select x-model="filters.unit_type_id" @change="fetchUnits()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Types</option>
                @foreach($unitTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }} ({{ ucfirst($type->category) }})</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select x-model="filters.status" @change="fetchUnits()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="blocked">Blocked</option>
                <option value="booked">Booked</option>
                <option value="sold">Sold</option>
                <option value="on_hold">On Hold</option>
            </select>
        </div>


        <button
    @click="resetFilters()"
    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-primary hover:bg-primary-50 hover:text-primary-705 hover:shadow-md"
>
    <x-heroicon-o-arrow-path class="h-4 w-4" />
    Reset Filters
</button>
        <!-- <button @click="resetFilters()" class="text-xs font-bold text-slate-450 hover:text-indigo-650 uppercase tracking-widest transition-colors flex-shrink-0">
            Reset Filters
        </button> -->
    </div>

    {{-- Units Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Unit Number</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Floor</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Type / Category</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Area (BUA / Carpet)</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Facing</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Base Rate</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px]">Status</th>
                        <th class="px-6 py-3 font-bold text-slate-550 uppercase tracking-widest text-[10px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="unit in units" :key="unit.id">
                        <tr class="table-row transition-colors cursor-pointer" @click="openEditModal(unit.id)">
                            <td class="px-6 py-3.5 font-bold text-slate-900" x-text="unit.unit_number"></td>
                            <td class="px-6 py-3.5 text-slate-600 font-semibold" x-text="unit.floor.name"></td>
                            <td class="px-6 py-3.5 text-slate-500 font-semibold">
                                <span x-text="unit.unit_type.name"></span>
                                <span class="text-[9px] text-slate-400 capitalize" x-text="'(' + unit.unit_type.category + ')'"></span>
                            </td>
                            <td class="px-6 py-3.5 text-slate-550">
                                <span x-text="unit.bua_area + ' ' + unit.area_unit + ' BUA'"></span>
                                <template x-if="unit.carpet_area">
                                    <span class="text-slate-400 text-[10px]" x-text="'/ ' + unit.carpet_area + ' ' + unit.area_unit + ' Carpet'"></span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5 text-slate-500 font-medium" x-text="unit.facing || 'N/A'"></td>
                            <td class="px-6 py-3.5 font-bold text-slate-900" x-text="'₹' + Number(unit.base_rate).toLocaleString()"></td>
                            <td class="px-6 py-3.5">
                                <span class="badge-pill" :class="getStatusBadgeClass(unit.status)" x-text="unit.status"></span>
                            </td>
                            <td class="px-6 py-3.5 text-right flex items-center justify-end gap-2" @click.stop>
                                <template x-if="permissions.manage">
                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal(unit.id)" class="p-1.5 hover:bg-primary-50 text-slate-400 hover:text-primary-600 rounded transition-colors" title="Edit Unit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button @click="confirmDelete(unit)" class="p-1.5 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded transition-colors" :disabled="unit.status !== 'available'" :class="unit.status !== 'available' ? 'opacity-30 cursor-not-allowed' : ''" title="Delete Unit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="units.length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No units match the query filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 1: ADD UNIT MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add New Unit</h3>
                <button @click="closeAddModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitAddUnit()">
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor</label>
                            <select x-model="forms.add.floor_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Type</label>
                            <select x-model="forms.add.unit_type_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <option value="">Select Type...</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_type_id[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Number (e.g. A-404)</label>
                        <input type="text" x-model="forms.add.unit_number" placeholder="Enter number..." class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="errors.unit_number"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_number[0]"></p></template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">BUA Area</label>
                            <input type="number" step="0.01" x-model="forms.add.bua_area" placeholder="e.g. 1200" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.bua_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.bua_area[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Carpet Area</label>
                            <input type="number" step="0.01" x-model="forms.add.carpet_area" placeholder="e.g. 1000" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Area Unit</label>
                            <select x-model="forms.add.area_unit" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <option value="sqft">Sqft</option>
                                <option value="sqm">Sqm</option>
                            </select>
                            <template x-if="errors.area_unit"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.area_unit[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Facing</label>
                            <input type="text" x-model="forms.add.facing" placeholder="e.g. East" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.facing"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.facing[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Base Rate (Rate per Area Unit)</label>
                        <input type="number" step="0.01" x-model="forms.add.base_rate" placeholder="e.g. 4500" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="errors.base_rate"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.base_rate[0]"></p></template>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-slate-950 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-primary/5">Add Unit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 2: EDIT UNIT & ACTIONS MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit Unit: <span class="text-primary-700" x-text="activeUnit.unit_number"></span></h3>
                    <span class="badge-pill" :class="getStatusBadgeClass(activeUnit.status)" x-text="activeUnit.status"></span>
                </div>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            
            <div class="grid lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-150">
                {{-- Left Pane: Edit Form --}}
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <template x-if="permissions.manage">
                        <form @submit.prevent="submitEditUnit()">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor</label>
                                        <select x-model="forms.edit.floor_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Type</label>
                                        <select x-model="forms.edit.unit_type_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                            @foreach($unitTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_type_id[0]"></p></template>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Number</label>
                                    <input type="text" x-model="forms.edit.unit_number" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <template x-if="errors.unit_number"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_number[0]"></p></template>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">BUA Area</label>
                                        <input type="number" step="0.01" x-model="forms.edit.bua_area" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <template x-if="errors.bua_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.bua_area[0]"></p></template>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Carpet Area</label>
                                        <input type="number" step="0.01" x-model="forms.edit.carpet_area" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Area Unit</label>
                                        <select x-model="forms.edit.area_unit" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                            <option value="sqft">Sqft</option>
                                            <option value="sqm">Sqm</option>
                                        </select>
                                        <template x-if="errors.area_unit"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.area_unit[0]"></p></template>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Facing</label>
                                        <input type="text" x-model="forms.edit.facing" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <template x-if="errors.facing"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.facing[0]"></p></template>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition uppercase tracking-wide">
                                        Save Details
                                    </button>
                                </div>
                            </div>
                        </form>
                    </template>
                    <template x-if="!permissions.manage">
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block border-b pb-1">Unit Info (Read-Only)</span>
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium">
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Floor</p><p class="text-slate-900" x-text="activeUnit.floor ? activeUnit.floor.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Type</p><p class="text-slate-900" x-text="activeUnit.unit_type ? activeUnit.unit_type.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">BUA</p><p class="text-slate-900" x-text="activeUnit.bua_area + ' ' + activeUnit.area_unit"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Carpet</p><p class="text-slate-900" x-text="(activeUnit.carpet_area || 'N/A') + ' ' + activeUnit.area_unit"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Facing</p><p class="text-slate-900" x-text="activeUnit.facing || 'N/A'"></p></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Right Pane: Status Transitions & Rate Updates --}}
                <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    {{-- Status Transitions --}}
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Transitions</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-if="['available', 'blocked'].includes(activeUnit.status)">
                                <a :href="'{{ route('bookings.create') }}?unit_id=' + activeUnit.id" 
                                   class="btn-ripple inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-550 border border-emerald-500 text-white font-bold rounded-lg text-xs transition uppercase tracking-wider shadow-sm shadow-emerald-650/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                    Book Unit
                                </a>
                            </template>
                            <template x-for="state in allowedTransitions" :key="state">
                                <button type="button" @click="transitionStatus(state)"
                                        class="btn-ripple px-3 py-1.5 bg-primary-50 border border-primary-200 text-primary-700 font-semibold rounded-lg text-xs hover:bg-primary-100 transition">
                                    Move to <span class="capitalize" x-text="state"></span>
                                </button>
                            </template>
                            <template x-if="activeUnit.status === 'sold' && allowedTransitions.includes('available')">
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-500 ml-1 cursor-pointer">
                                    <input type="checkbox" x-model="forms.status.is_resale" class="rounded text-primary focus:ring-primary/10">
                                    <span class="font-bold">Resale Flag</span>
                                </label>
                            </template>
                            <template x-if="allowedTransitions.length === 0">
                                <p class="text-xs text-slate-400 italic">No transitions available for this status.</p>
                            </template>
                        </div>
                        <div class="space-y-1.5" x-show="allowedTransitions.length > 0">
                            <input type="text" x-model="forms.status.reason" placeholder="Reason for transition (optional)..." class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-[10px] focus:outline-none transition">
                        </div>
                    </div>

                    {{-- Rate Update Form (Only visible to rateManage) --}}
                    <template x-if="permissions.rateManage">
                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Update Base Rate</h4>
                            <form @submit.prevent="submitUpdateRate()">
                                <div class="space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wider block">New Rate (₹)</label>
                                            <input type="number" step="0.01" x-model="forms.rate.rate" placeholder="e.g. 5000" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white rounded-lg text-xs focus:outline-none transition">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-450 uppercase tracking-wider block">Effective From</label>
                                            <input type="date" x-model="forms.rate.effective_from" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white rounded-lg text-xs focus:outline-none transition">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <input type="text" x-model="forms.rate.reason" placeholder="Reason for rate change..." class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-250 focus:bg-white rounded-lg text-xs focus:outline-none transition">
                                    </div>
                                    <button type="submit" class="w-full py-1.5 bg-slate-950 hover:bg-slate-900 text-white rounded-lg text-xs font-bold transition uppercase tracking-wide">
                                        Update Rate
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>

                    {{-- Pricing History Logs --}}
                    <div class="space-y-3 border-t border-slate-100 pt-4">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pricing Log History</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <template x-for="log in activeUnit.rate_logs" :key="log.id">
                                <div class="p-2.5 bg-slate-50 rounded-xl space-y-1 border border-slate-100 text-[10px]">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-900" x-text="'₹' + Number(log.rate).toLocaleString()"></span>
                                        <span class="text-slate-450 font-medium" x-text="log.effective_from"></span>
                                    </div>
                                    <p class="text-slate-500 leading-tight" x-text="log.reason || 'No reason provided'"></p>
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
            
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end bg-slate-50">
                <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 3: BULK ADD UNITS
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.bulk.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeBulkModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Bulk Setup Floor Units</h3>
                <button @click="closeBulkModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitBulkAdd()">
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Floor</label>
                            <select x-model="forms.bulk.floor_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.floor_id[0]"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Type</label>
                            <select x-model="forms.bulk.unit_type_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
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
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Prefix (e.g. A-)</label>
                            <input type="text" x-model="forms.bulk.unit_prefix" placeholder="A-" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.unit_prefix"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_prefix[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Starting Num</label>
                            <input type="number" x-model="forms.bulk.start_number" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.start_number"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.start_number[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Count</label>
                            <input type="number" x-model="forms.bulk.count" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.count"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.count[0]"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">BUA Area</label>
                            <input type="number" step="0.01" x-model="forms.bulk.bua_area" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.bua_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.bua_area[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Carpet Area</label>
                            <input type="number" step="0.01" x-model="forms.bulk.carpet_area" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Area Unit</label>
                            <select x-model="forms.bulk.area_unit" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                                <option value="sqft">Sqft</option>
                                <option value="sqm">Sqm</option>
                            </select>
                            <template x-if="errors.area_unit"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.area_unit[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Facing</label>
                            <input type="text" x-model="forms.bulk.facing" placeholder="e.g. East" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.facing"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.facing[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Base Rate</label>
                        <input type="number" step="0.01" x-model="forms.bulk.base_rate" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="errors.base_rate"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.base_rate[0]"></p></template>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeBulkModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-550 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Generate Units</button>
                </div>
            </form>
        </div>
    </div>



{{-- ═══════════════════════ EDIT PROJECT MODAL ═══════════════════════ --}}
<div
    x-show="editProjectModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="editProjectModal"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="editProjectModal = false"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
    ></div>

    {{-- Modal panel --}}
    <div
        x-show="editProjectModal"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
        @click.stop
    >
        {{-- Header --}}
        <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-900 px-6 py-5">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Edit Project</p>
                    <h2 class="text-lg font-extrabold text-white">{{ $project->name }}</h2>
                    <p class="text-slate-400 text-xs mt-0.5">{{ $project->code }}</p>
                </div>
                <button @click="editProjectModal = false" class="text-slate-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Project Image Upload --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Project Image</label>
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-[#a38c29]/30 bg-slate-100 flex-shrink-0">
                        <img
                            x-show="!imagePreview"
                            src="{{ $project->image_url ?? asset('images/no-image.png') }}"
                            class="w-full h-full object-cover"
                            alt="Project image"
                        >
                        <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover" x-cloak>
                    </div>
                    <div class="flex-1">
                        <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 bg-[#a38c29] hover:bg-[#a38c29]/80 text-white text-xs font-bold rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Choose Image
                            <input type="file" name="image" accept="image/*" class="hidden" @change="
                                const file = $event.target.files[0];
                                if (file) imagePreview = URL.createObjectURL(file);
                            ">
                        </label>
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG up to 2MB</p>
                    </div>
                </div>
            </div>

            {{-- Project Name & Code --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Project Name</label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Project Code</label>
                    <input type="text" name="code" value="{{ old('code', $project->code) }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                </div>
            </div>

            {{-- Location --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Location</label>
                <input type="text" name="location" value="{{ old('location', $project->location) }}"
                    placeholder="e.g. Sector 62, Noida, Uttar Pradesh, India"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
            </div>

            {{-- Status & RERA --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        @foreach(['ongoing' => 'Ongoing', 'completed' => 'Completed', 'upcoming' => 'Upcoming', 'on_hold' => 'On Hold'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $project->status) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">RERA Status</label>
                    <select name="rera_status" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        @foreach(['exempt' => 'Exempt', 'pending' => 'Pending', 'approved' => 'Approved'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('rera_status', $project->rera_status) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Floors & Dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Total Floors</label>
                    <input type="number" name="total_floors" value="{{ old('total_floors', $project->total_floors) }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Target Completion</label>
                    <input type="date" name="target_completion" value="{{ old('target_completion', $project->target_completion?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="No description provided for this project."
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="editProjectModal = false"
                    class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-[#a38c29] hover:bg-[#a38c29]/80 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30">
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
                    Are you sure you want to delete unit <strong class="text-slate-900" x-text="activeUnit.unit_number"></strong>?
                </p>
                <p class="text-[10px] text-rose-500 font-bold uppercase tracking-wider">This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                <button type="button" @click="submitDelete()" class="px-4 py-2 bg-rose-600 hover:bg-rose-550 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-rose-600/5">Confirm Delete</button>
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
        units: [],
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
            delete: { open: false }
        },
        forms: {
            add: {
                floor_id: '',
                unit_type_id: '',
                unit_number: '',
                bua_area: '',
                carpet_area: '',
                area_unit: 'sqft',
                facing: '',
                base_rate: ''
            },
            edit: {
                floor_id: '',
                unit_type_id: '',
                unit_number: '',
                bua_area: '',
                carpet_area: '',
                area_unit: 'sqft',
                facing: ''
            },
            bulk: {
                floor_id: '',
                unit_type_id: '',
                unit_prefix: '',
                start_number: 1,
                count: 10,
                bua_area: '',
                carpet_area: '',
                area_unit: 'sqft',
                facing: '',
                base_rate: ''
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
        fetchUnits() {
            let params = new URLSearchParams();
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
            })
            .catch(err => {
                console.error('Error fetching units:', err);
                this.showToast('Failed to fetch units list.', 'error');
            });
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.floor_id = '';
            this.filters.unit_type_id = '';
            this.filters.status = '';
            this.fetchUnits();
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
                unit_number: '',
                bua_area: '',
                carpet_area: '',
                area_unit: 'sqft',
                facing: '',
                base_rate: ''
            };
            this.modals.add.open = true;
        },
        closeAddModal() {
            this.modals.add.open = false;
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
                    unit_number: this.activeUnit.unit_number,
                    bua_area: this.activeUnit.bua_area,
                    carpet_area: this.activeUnit.carpet_area,
                    area_unit: this.activeUnit.area_unit,
                    facing: this.activeUnit.facing
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
                bua_area: '',
                carpet_area: '',
                area_unit: 'sqft',
                facing: '',
                base_rate: ''
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
                    this.fetchUnits();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitEditUnit() {
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
                    // Update table & active item state
                    this.fetchUnits();
                    this.openEditModal(this.activeUnit.id);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitBulkAdd() {
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
                    this.fetchUnits();
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
                    this.openEditModal(this.activeUnit.id);
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
