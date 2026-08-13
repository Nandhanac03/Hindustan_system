<x-erp-layout title="Engineer Master">
<div class="space-y-6 p-6" x-data="{
    openAddModal: false,
    openEditModal: false,
    openDeleteModal: false,
    openViewModal: false,
    viewEngineer: { id: null, engineer_code: '', name: '', email: '', phone: '', designation: 'Site Engineer', specialization: '', project_name: '', is_active: true },
    editEngineer: { id: null, engineer_code: '', name: '', email: '', phone: '', designation: 'Site Engineer', specialization: '', project_id: '', is_active: true },
    deleteEngineer: { id: null, name: '' },
    initView(eng) {
        this.viewEngineer = { ...eng };
        this.openViewModal = true;
    },
    initEdit(eng) {
        this.editEngineer = { ...eng };
        this.openEditModal = true;
    },
    initDelete(eng) {
        this.deleteEngineer = { ...eng };
        this.openDeleteModal = true;
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-blue-500/10 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Engineer Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage Site Engineers, Project Assignments, and RA Bill Verifiers</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Engineer
            </button>
        </div>
    </div>

    <!-- Flash Success Message -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Engineers</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalEngineers }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-xs">
            <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Active Engineers</p>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $activeEngineers }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-xs">
            <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider">Assigned To Projects</p>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $assignedCount }}</p>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('engineers.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Code, Name, Phone, Email, Designation..." class="w-full px-3.5 py-2 text-xs border border-slate-250 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
            </div>
            <div class="w-48">
                <select name="project_id" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
                Filter
            </button>
            @if(request('search') || request('project_id'))
            <a href="{{ route('engineers.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-700 font-bold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-300 uppercase tracking-wider font-bold border-b border-slate-800">
                        <th class="px-4 py-3.5">Code</th>
                        <th class="px-4 py-3.5">Engineer Name</th>
                        <th class="px-4 py-3.5">Designation & Specialization</th>
                        <th class="px-4 py-3.5">Contact Details</th>
                        <th class="px-4 py-3.5">Assigned Project</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($engineers as $eng)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]">{{ $eng->engineer_code }}</td>
                        <td class="px-4 py-3.5 font-semibold text-slate-900">
                            {{ $eng->name }}
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-slate-800">{{ $eng->designation }}</p>
                            @if($eng->specialization)
                            <p class="text-[10px] text-slate-500">{{ $eng->specialization }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-slate-600">
                            @if($eng->phone)<p class="font-mono text-slate-800">📞 {{ $eng->phone }}</p>@endif
                            @if($eng->email)<p class="text-[11px] text-slate-500">✉️ {{ $eng->email }}</p>@endif
                            @if(!$eng->phone && !$eng->email)<span class="text-slate-400">—</span>@endif
                        </td>
                        <td class="px-4 py-3.5 font-medium text-slate-800">
                            @if($eng->project)
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-800 border border-blue-200 rounded-md font-bold text-[11px]">{{ $eng->project->name }}</span>
                            @else
                            <span class="text-slate-400 italic">Unassigned (Global)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <form action="{{ route('engineers.toggle-status', $eng->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Click to toggle status" class="px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer transition {{ $eng->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $eng->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3.5 text-right pr-4">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button @click="initView({ id: {{ $eng->id }}, engineer_code: '{{ addslashes($eng->engineer_code) }}', name: '{{ addslashes($eng->name) }}', email: '{{ addslashes($eng->email ?? '') }}', phone: '{{ addslashes($eng->phone ?? '') }}', designation: '{{ addslashes($eng->designation) }}', specialization: '{{ addslashes($eng->specialization ?? '') }}', project_name: '{{ addslashes($eng->project->name ?? 'Unassigned (Global)') }}', is_active: {{ $eng->is_active ? 'true' : 'false' }} })" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button @click="initEdit({ id: {{ $eng->id }}, engineer_code: '{{ addslashes($eng->engineer_code) }}', name: '{{ addslashes($eng->name) }}', email: '{{ addslashes($eng->email ?? '') }}', phone: '{{ addslashes($eng->phone ?? '') }}', designation: '{{ addslashes($eng->designation) }}', specialization: '{{ addslashes($eng->specialization ?? '') }}', project_id: '{{ $eng->project_id ?? '' }}', is_active: {{ $eng->is_active ? 'true' : 'false' }} })" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Engineer">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="initDelete({ id: {{ $eng->id }}, name: '{{ addslashes($eng->name) }}' })" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Engineer">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 font-medium">
                            No Engineers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($engineers->hasPages())
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
            {{ $engineers->links() }}
        </div>
        @endif
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5" @click.outside="openViewModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Engineer Profile Details</h3>
                <button @click="openViewModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Engineer Code</span>
                    <span class="font-bold font-mono text-[#a38c29]" x-text="viewEngineer.engineer_code"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Full Name</span>
                    <span class="font-bold text-slate-900" x-text="viewEngineer.name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Designation</span>
                    <span class="font-bold text-slate-800" x-text="viewEngineer.designation"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Specialization</span>
                    <span class="font-medium text-slate-700" x-text="viewEngineer.specialization || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Phone Number</span>
                    <span class="font-mono text-slate-800" x-text="viewEngineer.phone || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Email Address</span>
                    <span class="text-slate-800" x-text="viewEngineer.email || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Assigned Project</span>
                    <span class="font-bold text-blue-700" x-text="viewEngineer.project_name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-medium">Active Status</span>
                    <span class="font-bold" :class="viewEngineer.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewEngineer.is_active ? 'Active' : 'Inactive'"></span>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="button" @click="openViewModal = false" class="px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg shadow-sm">Close</button>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5" @click.outside="openAddModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Add Site Engineer</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('engineers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Engineer Code *</label>
                    <input type="text" name="engineer_code" required placeholder="e.g., ENG-004" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg uppercase focus:ring-2 focus:ring-[#a38c29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g., Vikram Sharma" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" placeholder="9876543210" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" placeholder="engineer@hindustan.com" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Designation *</label>
                        <input type="text" name="designation" required value="Site Engineer" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Specialization</label>
                        <input type="text" name="specialization" placeholder="e.g. Civil / RA Bills" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Project</label>
                    <select name="project_id" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                        <option value="">-- Global / Unassigned --</option>
                        @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="eng_add_active" value="1" checked class="rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                    <label for="eng_add_active" class="text-xs font-semibold text-slate-700">Active Status</label>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg shadow-sm">Save Engineer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5" @click.outside="openEditModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Edit Site Engineer</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form :action="'/engineers/' + editEngineer.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Engineer Code *</label>
                    <input type="text" name="engineer_code" x-model="editEngineer.engineer_code" required class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg uppercase focus:ring-2 focus:ring-[#a38c29]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name *</label>
                    <input type="text" name="name" x-model="editEngineer.name" required class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" x-model="editEngineer.phone" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" x-model="editEngineer.email" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Designation *</label>
                        <input type="text" name="designation" x-model="editEngineer.designation" required class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Specialization</label>
                        <input type="text" name="specialization" x-model="editEngineer.specialization" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Assigned Project</label>
                    <select name="project_id" x-model="editEngineer.project_id" class="w-full px-3 py-2 text-xs border border-slate-250 rounded-lg focus:ring-2 focus:ring-[#a38c29]">
                        <option value="">-- Global / Unassigned --</option>
                        @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="eng_edit_active" value="1" :checked="editEngineer.is_active" class="rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                    <label for="eng_edit_active" class="text-xs font-semibold text-slate-700">Active Status</label>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg shadow-sm">Update Engineer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4" @click.outside="openDeleteModal = false">
            <div class="text-center">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Delete Engineer</h3>
                <p class="text-xs text-slate-500 mt-1">Are you sure you want to delete <span class="font-bold text-slate-800" x-text="deleteEngineer.name"></span>?</p>
            </div>
            <form :action="'/engineers/' + deleteEngineer.id" method="POST" class="flex justify-center gap-2 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm">Confirm Delete</button>
            </form>
        </div>
    </div>
</div>
</x-erp-layout>
