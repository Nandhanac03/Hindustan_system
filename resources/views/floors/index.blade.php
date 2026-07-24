<x-erp-layout title="Floor Master" headerTitle="Floor Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="floorMasterApp()">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75">✕</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Floor Management Master</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Define building levels including Ground Floors, Basements, and Upper Towers per project.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Project Selector --}}
            <form method="GET" action="{{ route('floors.index') }}" class="flex items-center gap-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Filter Project:</label>
                <select name="project_id" onchange="this.form.submit()" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-300 bg-white shadow-sm text-slate-800 focus:ring-2 focus:ring-[#a38c29]/50">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->is_active ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>

            <button @click="openAddModal({{ $selectedProjectId ?? ($projects->first()?->id ?? 'null') }})" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Floor Level
            </button>
        </div>
    </div>

    {{-- Floors Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Configured Floors Directory</h3>
            <span class="px-2.5 py-1 rounded-full bg-slate-200/70 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest">
                Total Floors: {{ $floors->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">PROJECT NAME</th>
                        <th class="px-4 py-3 border">FLOOR NUMBER</th>
                        <th class="px-4 py-3 border">FLOOR NAME / LABEL</th>
                        <th class="px-4 py-3 border">ASSIGNED UNITS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    @forelse($floors as $index => $floor)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 border. font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border font-semibold text-slate-800 text-left">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full {{ $floor->project?->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                    {{ $floor->project?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border font-bold">
                                @if($floor->floor_number < 0)
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 font-extrabold">Basement ({{ $floor->floor_number }})</span>
                                @elseif($floor->floor_number === 0)
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-900 font-extrabold">Ground (0)</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-900 font-extrabold">Level +{{ $floor->floor_number }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border font-bold text-slate-900 text-left">{{ $floor->name }}</td>
                            <td class="px-4 py-3 border">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold {{ $floor->units_count > 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $floor->units_count }} {{ $floor->units_count === 1 ? 'Unit' : 'Units' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal({{ $floor->id }}, {{ $floor->floor_number }}, '{{ addslashes($floor->name) }}', {{ $floor->units_count }}, '{{ addslashes($floor->project->name ?? 'N/A') }}', '{{ route('units.index', ['floor_id' => $floor->id, 'project_id' => $floor->project_id]) }}')" title="View Floor Profile" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    <button @click="openEditModal({{ $floor->id }}, {{ $floor->floor_number }}, '{{ addslashes($floor->name) }}')" title="Edit Floor" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    @if($floor->units_count === 0)
                                        <form action="{{ route('floors.destroy', $floor->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this floor level?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Floor" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-bold uppercase italic px-2" title="Cannot delete because units exist on this floor">In Use</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 font-semibold">
                                No floor levels defined yet for the selected project filter. Click "Add Floor Level" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
    {{-- Add Floor Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="showAddModal = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Floor Master</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Add New Floor Level</h2>
                    </div>
                    <button type="button" @click="showAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <form action="{{ route('floors.store') }}" method="POST" @submit="submitAddFloor($event)" novalidate>
                @csrf
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Project <span class="text-rose-500">*</span></label>
                            <select name="project_id" x-model="addForm.project_id" required
                                    :class="errors.project_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                    class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all shadow-sm font-semibold">
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.project_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.project_id) ? errors.project_id[0] : errors.project_id"></p></template>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Quick Presets</label>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="setPreset(-2, 'Basement 2')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-bold transition border border-slate-200">Basement 2 (-2)</button>
                                <button type="button" @click="setPreset(-1, 'Basement 1')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-bold transition border border-slate-200">Basement 1 (-1)</button>
                                <button type="button" @click="setPreset(0, 'Ground Floor')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-bold transition border border-slate-200">Ground (0)</button>
                                <button type="button" @click="setPreset(1, 'Floor 1')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-bold transition border border-slate-200">Floor 1 (1)</button>
                                <button type="button" @click="setPreset(2, 'Floor 2')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-bold transition border border-slate-200">Floor 2 (2)</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Floor Number <span class="text-rose-500">*</span></label>
                            <input type="number" name="floor_number" x-model="addForm.floor_number" @input="autoName()" required placeholder="e.g. 0 for Ground, -1 for Basement"
                                   :class="errors.floor_number ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.floor_number"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.floor_number) ? errors.floor_number[0] : errors.floor_number"></p></template>
                            <p class="text-[9px] text-slate-400 mt-1.5">Use negative numbers for basements (-1, -2) and 0 for Ground Floor.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Floor Name / Label <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="addForm.name" required placeholder="e.g. Ground Floor, Basement 1, Floor 1"
                                   :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">Save Floor Level</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Floor Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="showEditModal = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Floor</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Edit Floor Level</h2>
                    </div>
                    <button type="button" @click="showEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <form :action="editForm.action" method="POST" @submit="submitEditFloor($event)" novalidate>
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Floor Number <span class="text-rose-500">*</span></label>
                            <input type="number" name="floor_number" x-model="editForm.floor_number" required
                                   :class="errors.edit_floor_number ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.edit_floor_number"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_floor_number) ? errors.edit_floor_number[0] : errors.edit_floor_number"></p></template>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Floor Name / Label <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="editForm.name" required
                                   :class="errors.edit_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.edit_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_name) ? errors.edit_name[0] : errors.edit_name"></p></template>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">Update Floor Level</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop text-left" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="showViewModal = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Floor Profile</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Floor Level Details</h2>
                    </div>
                    <button type="button" @click="showViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-4 bg-slate-50/50 text-xs font-sans">
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Floor Level Name</span>
                        <span class="text-sm font-extrabold text-slate-900" x-text="viewData.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Floor Index Number</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20" x-text="'Floor ' + viewData.number"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Associated Project</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="viewData.project_name"></span>
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Units</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewData.units_count + ' Configured Unit(s)'"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                <button type="button" @click="showViewModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
                <a :href="viewData.units_url" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5">
                    <span>View Floor Units</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>    </div>

    </div>

</div>

<script>
function floorMasterApp() {
    return {
        errors: {},
        showAddModal: false,
        showEditModal: false,
        showViewModal: false,
        addForm: {
            project_id: '',
            floor_number: '',
            name: ''
        },
        editForm: {
            action: '',
            floor_number: '',
            name: ''
        },
        viewData: {
            id: '',
            number: '',
            name: '',
            units_count: 0,
            project_name: '',
            units_url: ''
        },
        openAddModal(defaultProjectId) {
            this.addForm.project_id = defaultProjectId || '{{ $projects->first()?->id }}';
            this.addForm.floor_number = '';
            this.addForm.name = '';
            this.showAddModal = true;
        },
        setPreset(num, label) {
            this.addForm.floor_number = num;
            this.addForm.name = label;
        },
        autoName() {
            let num = parseInt(this.addForm.floor_number);
            if (isNaN(num)) return;
            if (num === 0) this.addForm.name = 'Ground Floor';
            else if (num < 0) this.addForm.name = 'Basement ' + Math.abs(num);
            else this.addForm.name = 'Floor ' + num;
        },
        openEditModal(id, number, name) {
            this.editForm.action = '{{ url("/floors") }}/' + id;
            this.editForm.floor_number = number;
            this.editForm.name = name;
            this.showEditModal = true;
        },
        openViewModal(id, number, name, unitsCount, projectName, unitsUrl) {
            this.viewData.id = id;
            this.viewData.number = number;
            this.viewData.name = name;
            this.viewData.units_count = unitsCount;
            this.viewData.project_name = projectName;
            this.viewData.units_url = unitsUrl;
            this.showViewModal = true;
        },
        submitAddFloor(e) {
            let clientErrors = {};
            if (!this.addForm.project_id) {
                clientErrors.project_id = ['The project field is required.'];
            }
            if (this.addForm.floor_number === '' || this.addForm.floor_number === null || this.addForm.floor_number === undefined) {
                clientErrors.floor_number = ['The floor number field is required.'];
            }
            if (!this.addForm.name || !String(this.addForm.name).trim()) {
                clientErrors.name = ['The floor name field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        },
        submitEditFloor(e) {
            let clientErrors = {};
            if (this.editForm.floor_number === '' || this.editForm.floor_number === null || this.editForm.floor_number === undefined) {
                clientErrors.edit_floor_number = ['The floor number field is required.'];
            }
            if (!this.editForm.name || !String(this.editForm.name).trim()) {
                clientErrors.edit_name = ['The floor name field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        }
    }
}
</script>

</x-erp-layout>
