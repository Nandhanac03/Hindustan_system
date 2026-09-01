<x-erp-layout title="Unit Type Master" headerTitle="Unit Type Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="unitTypeMasterApp()">

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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Unit Type Management Master</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Configure property classifications linked to projects such as Apartments, Commercial Shops, Villas, and Parking bays.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Project Selector --}}
            <form method="GET" action="{{ route('unit-types.index') }}" class="flex items-center gap-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Filter Project:</label>
                <select name="project_id" onchange="this.form.submit()" class="px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-300 bg-white shadow-sm text-slate-800 focus:ring-2 focus:ring-[#a38c29]/50">
                    <option value="">All Projects (Global & Scoped)</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->is_active ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>

            <button @click="openAddModal({{ $selectedProjectId ?? ($projects->first()?->id ?? 'null') }})" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Unit Type</span>
            </button>
        </div>
    </div>

    {{-- Unit Types Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Configured Unit Types Directory</h3>
            <span class="px-2.5 py-1 rounded-full bg-slate-200/70 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest">
                Total Types: {{ $unitTypes->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5 border border-[#8a7522]">SL NO</th>
                        <th class="px-4 py-3.5 border border-[#8a7522]">PROJECT / SCOPE</th>
                        <th class="px-4 py-3.5 border border-[#8a7522]">UNIT TYPE NAME</th>
                        <th class="px-4 py-3.5 border border-[#8a7522]">CATEGORY</th>
                        <th class="px-4 py-3.5 border border-[#8a7522]">LINKED UNITS</th>
                        <th class="px-4 py-3.5 border border-[#8a7522]">STATUS</th>
                        <th class="px-4 py-3.5 border border-[#8a7522] text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    @forelse($unitTypes as $index => $type)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 border font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border font-semibold text-slate-800 text-left">
                                @if($type->project_id && $type->project)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-900 border border-emerald-200/60 font-bold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $type->project->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                        {{ $type->project->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-extrabold text-[10px] uppercase tracking-wider">
                                        Global (All Projects)
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border font-extrabold text-slate-900 text-left">{{ $type->name }}</td>
                            <td class="px-4 py-3 border">
                                @if(strtolower($type->category) === 'residential')
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-900 font-extrabold uppercase text-[10px]">Residential</span>
                                @elseif(strtolower($type->category) === 'commercial')
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 font-extrabold uppercase text-[10px]">Commercial</span>
                                @elseif(strtolower($type->category) === 'parking')
                                    <span class="px-2.5 py-1 rounded-lg bg-purple-100 text-purple-900 font-extrabold uppercase text-[10px]">Parking</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-extrabold uppercase text-[10px]">{{ $type->category }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold {{ $type->units_count > 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $type->units_count }} {{ $type->units_count === 1 ? 'Unit' : 'Units' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border">
                                @if($type->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px] uppercase">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-extrabold text-[10px] uppercase">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->category) }}', {{ $type->units_count }}, '{{ addslashes($type->project->name ?? 'Global/All Projects') }}', {{ $type->is_active ? 'true' : 'false' }}, '{{ route('units.index', ['unit_type_id' => $type->id, 'project_id' => $type->project_id]) }}')" title="View Unit Type Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    <button @click="openEditModal({{ $type->id }}, {{ $type->project_id ?? 'null' }}, '{{ addslashes($type->name) }}', '{{ addslashes($type->category) }}', {{ $type->is_active ? 'true' : 'false' }})" title="Edit Unit Type" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    @if($type->units_count > 0)
                                        <span class="text-[10px] text-slate-400 font-bold uppercase italic px-2" title="Cannot delete because units exist of this type">In Use</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400 font-semibold">
                                No unit types configured yet for this scope. Click "Add Unit Type" above to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
    {{-- Add Modal --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" @click.away="showAddModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">UNIT TYPE MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Add New Unit Type</h2>
                    </div>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('unit-types.store') }}" method="POST" @submit="submitAddUnitType($event)" novalidate class="flex flex-col">
                @csrf
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 font-sans">Project Scope <span class="text-[9px] font-normal text-slate-400 normal-case">(Optional - leave blank for Global)</span></label>
                        <select name="project_id" x-model="addForm.project_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                            <option value="">Global (Available to All Projects)</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Unit Type Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="addForm.name" required placeholder="e.g. Apartment, Parking, Shop"
                               :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                        <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Category <span class="text-rose-500">*</span></label>
                        <select name="category" x-model="addForm.category" required
                                :class="errors.category ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                            <option value="parking">Parking</option>
                        </select>
                        <template x-if="errors.category"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.category) ? errors.category[0] : errors.category"></p></template>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" value="1" x-model="addForm.is_active" id="add_active" class="rounded border-slate-350 text-[#a38c29] focus:ring-[#a38c29] w-4 h-4 cursor-pointer">
                        <label for="add_active" class="text-[11px] font-bold text-slate-700 cursor-pointer select-none">Active Status (Enabled for new units)</label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE UNIT TYPE</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" @click.away="showEditModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">UNIT TYPE MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Edit Unit Type</h2>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="editForm.action" method="POST" @submit="submitEditUnitType($event)" novalidate class="flex flex-col">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Project Scope</label>
                        <select name="project_id" x-model="editForm.project_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                            <option value="">Global (Available to All Projects)</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Unit Type Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editForm.name" required
                               :class="errors.edit_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                        <template x-if="errors.edit_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_name) ? errors.edit_name[0] : errors.edit_name"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Category <span class="text-rose-500">*</span></label>
                        <select name="category" x-model="editForm.category" required
                                :class="errors.edit_category ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                            <option value="parking">Parking</option>
                        </select>
                        <template x-if="errors.edit_category"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_category) ? errors.edit_category[0] : errors.edit_category"></p></template>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" value="1" x-model="editForm.is_active" id="edit_active" class="rounded border-slate-350 text-[#a38c29] focus:ring-[#a38c29] w-4 h-4 cursor-pointer">
                        <label for="edit_active" class="text-[11px] font-bold text-slate-700 cursor-pointer select-none">Active Status</label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE UNIT TYPE</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" @click.away="showViewModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">UNIT TYPE MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Unit Type Details</h2>
                    </div>
                    <button type="button" @click="showViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-3.5 bg-white text-xs font-sans">
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">UNIT TYPE NAME</span>
                    <span class="font-bold text-slate-900 text-sm" x-text="viewData.name"></span>
                </div>

                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                    <span class="font-bold uppercase" :class="viewData.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewData.is_active ? 'Active' : 'Inactive'"></span>
                </div>

                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CATEGORY</span>
                    <span class="font-bold text-slate-800 uppercase" x-text="viewData.category"></span>
                </div>

                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ASSOCIATED PROJECT</span>
                    <span class="font-bold text-slate-800 truncate" x-text="viewData.project_name"></span>
                </div>

                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">TOTAL CONFIGURED UNITS</span>
                    <span class="font-mono font-bold text-[#a38c29]" x-text="viewData.units_count + ' Unit(s)'"></span>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <button type="button" @click="showViewModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CLOSE</button>
                    <a :href="viewData.units_url" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-sm inline-flex items-center gap-1.5 cursor-pointer">
                        <span>VIEW TYPE UNITS</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>    </div>

    </div>

</div>

<script>
function unitTypeMasterApp() {
    return {
        errors: {},
        showAddModal: false,
        showEditModal: false,
        showViewModal: false,
        addForm: {
            project_id: '',
            name: '',
            category: 'residential',
            is_active: true
        },
        editForm: {
            action: '',
            project_id: '',
            name: '',
            category: '',
            is_active: true
        },
        viewData: {
            id: '',
            name: '',
            category: '',
            units_count: 0,
            project_name: '',
            is_active: true,
            units_url: ''
        },
        openAddModal(defaultProjectId) {
            this.addForm.project_id = defaultProjectId || '';
            this.addForm.name = '';
            this.addForm.category = 'residential';
            this.addForm.is_active = true;
            this.showAddModal = true;
        },
        setPreset(name, cat) {
            this.addForm.name = name;
            this.addForm.category = cat;
        },
        openEditModal(id, projectId, name, category, isActive) {
            this.editForm.action = '{{ url("/unit-types") }}/' + id;
            this.editForm.project_id = projectId || '';
            this.editForm.name = name;
            this.editForm.category = category;
            this.editForm.is_active = isActive;
            this.showEditModal = true;
        },
        openViewModal(id, name, category, projectScope, unitsCount, unitsUrl) {
            this.viewData.id = id;
            this.viewData.name = name;
            this.viewData.category = category;
            this.viewData.project_scope = projectScope;
            this.viewData.units_count = unitsCount;
            this.viewData.units_url = unitsUrl;
            this.showViewModal = true;
        },
        submitAddUnitType(e) {
            let clientErrors = {};
            if (!this.addForm.name || !String(this.addForm.name).trim()) {
                clientErrors.name = ['The unit type name field is required.'];
            }
            if (!this.addForm.category) {
                clientErrors.category = ['The category field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        },
        submitEditUnitType(e) {
            let clientErrors = {};
            if (!this.editForm.name || !String(this.editForm.name).trim()) {
                clientErrors.edit_name = ['The unit type name field is required.'];
            }
            if (!this.editForm.category) {
                clientErrors.edit_category = ['The category field is required.'];
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
