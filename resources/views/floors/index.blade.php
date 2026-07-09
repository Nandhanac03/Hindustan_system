<x-erp-layout title="Floor Master" headerTitle="Floor Master Directory">

<div class="max-w-[1400px] mx-auto space-y-6" x-data="floorMasterApp()">

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

    {{-- Add Floor Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative" @click.away="showAddModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Add New Floor Level</h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
            </div>

            <form action="{{ route('floors.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Project <span class="text-rose-500">*</span></label>
                    <select name="project_id" x-model="addForm.project_id" required class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#a38c29]/50">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Quick Presets</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="setPreset(-2, 'Basement 2')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[11px] font-bold transition">Basement 2 (-2)</button>
                        <button type="button" @click="setPreset(-1, 'Basement 1')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[11px] font-bold transition">Basement 1 (-1)</button>
                        <button type="button" @click="setPreset(0, 'Ground Floor')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[11px] font-bold transition">Ground Floor (0)</button>
                        <button type="button" @click="setPreset(1, 'Floor 1')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[11px] font-bold transition">Floor 1 (1)</button>
                        <button type="button" @click="setPreset(2, 'Floor 2')" class="px-2.5 py-1 bg-slate-100 hover:bg-[#a38c29] hover:text-white rounded-lg text-[11px] font-bold transition">Floor 2 (2)</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Floor Number <span class="text-rose-500">*</span></label>
                    <input type="number" name="floor_number" x-model="addForm.floor_number" @input="autoName()" required placeholder="e.g. 0 for Ground, -1 for Basement" class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#a38c29]/50">
                    <p class="text-[10px] text-slate-400 mt-1">Use negative numbers for basements (-1, -2) and 0 for Ground Floor.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Floor Name / Label <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="addForm.name" required placeholder="e.g. Ground Floor, Basement 1, Floor 1" class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#a38c29]/50">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold shadow-md uppercase transition">Save Floor Level</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Floor Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative" @click.away="showEditModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Edit Floor Level</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
            </div>

            <form :action="editForm.action" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Floor Number <span class="text-rose-500">*</span></label>
                    <input type="number" name="floor_number" x-model="editForm.floor_number" required class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#a38c29]/50">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Floor Name / Label <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editForm.name" required class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#a38c29]/50">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold shadow-md uppercase transition">Update Floor Level</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div @click.away="showViewModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Floor Level Details</h3>
                </div>
                <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
            </div>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor Level Name</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewData.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor Index Number</span>
                        <span class="px-2.5 py-1 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-mono font-bold text-xs inline-block mt-0.5" x-text="'Floor ' + viewData.number"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Associated Project</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewData.project_name"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Units</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewData.units_count + ' Configured Unit(s)'"></span>
                    </div>
                </div>
            </div>

            <div class="pt-3 flex justify-between items-center border-t border-slate-100">
                <button type="button" @click="showViewModal = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
                <a :href="viewData.units_url" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md inline-flex items-center gap-1.5">
                    <span>View Floor Units</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
function floorMasterApp() {
    return {
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
        }
    }
}
</script>

</x-erp-layout>
