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
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Engineer</span>
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
        <!-- Total Engineers (Gold) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#a38c29]/50">
            <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider mb-1">Total Engineers</p>
            <h4 class="text-[22px] font-bold text-[#8a7522] m-0">{{ $totalEngineers }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Technical Personnel</p>
        </div>

        <!-- Active Engineers (Green) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
            <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Active Engineers</p>
            <h4 class="text-[22px] font-bold text-[#10b981] m-0">{{ $activeEngineers }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">On Active Duty</p>
        </div>

        <!-- Assigned To Projects (Blue) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-blue-600 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-300">
            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-1">Assigned To Projects</p>
            <h4 class="text-[22px] font-bold text-blue-700 m-0">{{ $assignedCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Site Allocations</p>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <form method="GET" action="{{ route('engineers.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Code, Name, Phone, Email, Designation..." 
                           class="w-full pl-10 @if(request('search')) pr-10 @else pr-4 @endif py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    @if(request('search'))
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <a href="{{ route('engineers.index', request()->except('search')) }}"
                           class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Project Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                        </svg>
                    </div>
                    <select name="project_id" onchange="this.form.submit()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Status Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                        </svg>
                    </div>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <a href="{{ route('engineers.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </a>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">CODE</th>
                        <th class="px-4 py-3.5">ENGINEER NAME</th>
                        <th class="px-4 py-3.5">DESIGNATION & SPECIALIZATION</th>
                        <th class="px-4 py-3.5">CONTACT DETAILS</th>
                        <th class="px-4 py-3.5">ASSIGNED PROJECT</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
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
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openViewModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">ENGINEER MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Engineer Profile Details</h2>
                    </div>
                    <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-3.5 text-xs bg-white">
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ENGINEER CODE</span>
                    <span class="font-bold font-mono text-[#a38c29]" x-text="viewEngineer.engineer_code"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">FULL NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewEngineer.name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DESIGNATION</span>
                    <span class="font-bold text-slate-800" x-text="viewEngineer.designation"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">SPECIALIZATION</span>
                    <span class="font-bold text-slate-700" x-text="viewEngineer.specialization || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PHONE NUMBER</span>
                    <span class="font-mono font-bold text-slate-800" x-text="viewEngineer.phone || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">EMAIL ADDRESS</span>
                    <span class="font-bold text-slate-800" x-text="viewEngineer.email || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ASSIGNED PROJECT</span>
                    <span class="font-bold text-blue-700" x-text="viewEngineer.project_name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ACTIVE STATUS</span>
                    <span class="font-bold" :class="viewEngineer.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewEngineer.is_active ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase rounded-xl transition shadow-sm cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openAddModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">ENGINEER MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Add Site Engineer</h2>
                    </div>
                    <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('engineers.store') }}" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ENGINEER CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="engineer_code" required placeholder="e.g., ENG-004" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" required placeholder="e.g., Vikram Sharma" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                            <input type="text" name="phone" placeholder="9876543210" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                            <input type="email" name="email" placeholder="engineer@hindustan.com" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="designation" required value="Site Engineer" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">SPECIALIZATION</label>
                            <input type="text" name="specialization" placeholder="e.g. Civil / RA Bills" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ASSIGNED PROJECT</label>
                        <select name="project_id" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                            <option value="">-- Global / Unassigned --</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="eng_add_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                        <label for="eng_add_active" class="text-xs font-bold text-slate-700">Active Engineer Status</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE ENGINEER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openEditModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">ENGINEER MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Edit Site Engineer</h2>
                    </div>
                    <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form :action="'/engineers/' + editEngineer.id" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ENGINEER CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="engineer_code" x-model="editEngineer.engineer_code" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" x-model="editEngineer.name" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                            <input type="text" name="phone" x-model="editEngineer.phone" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                            <input type="email" name="email" x-model="editEngineer.email" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="designation" x-model="editEngineer.designation" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">SPECIALIZATION</label>
                            <input type="text" name="specialization" x-model="editEngineer.specialization" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">ASSIGNED PROJECT</label>
                        <select name="project_id" x-model="editEngineer.project_id" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                            <option value="">-- Global / Unassigned --</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="eng_edit_active" value="1" :checked="editEngineer.is_active" class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29]">
                        <label for="eng_edit_active" class="text-xs font-bold text-slate-700">Active Engineer Status</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE ENGINEER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openDeleteModal = false">
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-rose-950 to-rose-900 px-6 py-5 flex-shrink-0 border-b border-rose-800">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-rose-300 text-[10px] font-semibold uppercase tracking-widest mb-1">CONFIRMATION</p>
                        <h2 class="text-lg font-extrabold text-white">Delete Site Engineer</h2>
                    </div>
                    <button type="button" @click="openDeleteModal = false" class="text-rose-200 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4 text-center bg-white">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteEngineer.name"></span>?</p>
                <form :action="'/engineers/' + deleteEngineer.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
