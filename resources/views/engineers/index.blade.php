<x-erp-layout title="Engineer Master">
<script>
function engineerMasterComponent() {
    return {
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        openViewModal: false,
        search: '',
        selectedProjectId: '',
        selectedStatus: '',
        projectOpen: false,
        statusOpen: false,
        projects: @json($projectsArray),
        engineers: @json($engineersArray),

        currentPage: 1,
        perPage: 15,

        viewEngineer: { id: null, engineer_code: '', name: '', email: '', phone: '', designation: 'Site Engineer', specialization: '', project_name: '', is_active: true },
        editEngineer: { id: null, engineer_code: '', name: '', email: '', phone: '', designation: 'Site Engineer', specialization: '', project_id: '', is_active: true },
        deleteEngineer: { id: null, name: '' },

        get filteredEngineers() {
            const s = (this.search || '').toLowerCase().trim();
            const projId = (this.selectedProjectId || '').trim();
            const status = (this.selectedStatus || '').trim();

            return this.engineers.filter(eng => {
                const matchSearch = !s || 
                    (eng.engineer_code && eng.engineer_code.toLowerCase().includes(s)) ||
                    (eng.name && eng.name.toLowerCase().includes(s)) ||
                    (eng.email && eng.email.toLowerCase().includes(s)) ||
                    (eng.phone && eng.phone.toLowerCase().includes(s)) ||
                    (eng.designation && eng.designation.toLowerCase().includes(s)) ||
                    (eng.specialization && eng.specialization.toLowerCase().includes(s)) ||
                    (eng.project_name && eng.project_name.toLowerCase().includes(s));

                const matchProject = !projId || String(eng.project_id) === String(projId);
                const matchStatus = !status || (status === 'active' ? eng.is_active : !eng.is_active);

                return matchSearch && matchProject && matchStatus;
            });
        },

        get totalFiltered() {
            return this.filteredEngineers.length;
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.totalFiltered / this.perPage));
        },

        get paginatedEngineers() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredEngineers.slice(start, start + this.perPage);
        },

        get selectedProjectName() {
            if (!this.selectedProjectId) return 'All Projects';
            const p = this.projects.find(x => String(x.id) === String(this.selectedProjectId));
            return p ? p.name : 'All Projects';
        },

        get selectedStatusName() {
            if (this.selectedStatus === 'active') return 'Active';
            if (this.selectedStatus === 'inactive') return 'Inactive';
            return 'All Statuses';
        },

        resetFilters() {
            this.search = '';
            this.selectedProjectId = '';
            this.selectedStatus = '';
            this.projectOpen = false;
            this.statusOpen = false;
            this.currentPage = 1;
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) {
                this.currentPage = p;
            }
        },

        initView(eng) {
            this.viewEngineer = { ...eng };
            this.openViewModal = true;
        },
        initEdit(eng) {
            this.editEngineer = { 
                ...eng,
                project_id: eng.project_id ? String(eng.project_id) : ''
            };
            this.openEditModal = true;
        },
        initDelete(eng) {
            this.deleteEngineer = { ...eng };
            this.openDeleteModal = true;
        }
    };
}
</script>

<div class="space-y-6 p-6" x-data="engineerMasterComponent()">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
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

    {{-- Global Class Filter Panel (Zero Page Refresh) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Search Code, Name, Phone, Email, Designation..." 
                           class="erp-search-input w-full"
                           @keydown.escape="search = ''; currentPage = 1">
                    <template x-if="search">
                        <button type="button" @click="search = ''; currentPage = 1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-rose-500 cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </template>
                </div>

                {{-- Project Filter (Custom Popover) --}}
                <div class="relative" @click.outside="projectOpen = false">
                    <div @click="projectOpen = !projectOpen; statusOpen = false"
                         class="erp-dropdown-trigger cursor-pointer"
                         :class="projectOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                            </svg>
                            <span class="truncate font-bold text-slate-800" x-text="selectedProjectName"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="projectOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="projectOpen" x-cloak class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectedProjectId = ''; projectOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="!selectedProjectId ? 'selected-all' : ''">
                                <span>All Projects</span>
                            </div>
                            <template x-for="p in projects" :key="p.id">
                                <div @click="selectedProjectId = p.id; projectOpen = false; currentPage = 1"
                                     class="erp-dropdown-option cursor-pointer"
                                     :class="String(selectedProjectId) === String(p.id) ? 'selected' : ''">
                                    <span x-text="p.name"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Status Filter (Custom Popover) --}}
                <div class="relative" @click.outside="statusOpen = false">
                    <div @click="statusOpen = !statusOpen; projectOpen = false"
                         class="erp-dropdown-trigger cursor-pointer"
                         :class="statusOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                            </svg>
                            <span class="truncate font-bold text-slate-800" x-text="selectedStatusName"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="statusOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="statusOpen" x-cloak class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectedStatus = ''; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="!selectedStatus ? 'selected-all' : ''">
                                <span>All Statuses</span>
                            </div>
                            <div @click="selectedStatus = 'active'; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="selectedStatus === 'active' ? 'selected' : ''">
                                <span>Active</span>
                            </div>
                            <div @click="selectedStatus = 'inactive'; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="selectedStatus === 'inactive' ? 'selected' : ''">
                                <span>Inactive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button (Zero Page Refresh) --}}
        <button type="button" @click="resetFilters()"
           class="inline-flex items-center justify-center gap-2 rounded-xl theme-btn px-5 h-[38px] text-xs font-extrabold flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Reset Filters</span>
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr class="erp-table-header border-b border-slate-700 text-left">
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600 w-28">CODE</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">ENGINEER NAME</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">DESIGNATION & SPECIALIZATION</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">CONTACT DETAILS</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">ASSIGNED PROJECT</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600 text-center">STATUS</th>
                        <th class="px-4 py-3.5 erp-table-header text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="eng in paginatedEngineers" :key="eng.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]" x-text="eng.engineer_code"></td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900" x-text="eng.name"></td>
                            <td class="px-4 py-3.5">
                                <p class="font-bold text-slate-800" x-text="eng.designation"></p>
                                <template x-if="eng.specialization">
                                    <p class="text-[10px] text-slate-500" x-text="eng.specialization"></p>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">
                                <template x-if="eng.phone">
                                    <p class="font-mono text-slate-800 font-semibold" x-text="'📞 ' + eng.phone"></p>
                                </template>
                                <template x-if="eng.email">
                                    <p class="text-[11px] text-slate-500" x-text="'✉️ ' + eng.email"></p>
                                </template>
                                <template x-if="!eng.phone && !eng.email">
                                    <span class="text-slate-400">—</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 font-medium text-slate-800">
                                <template x-if="eng.project_id">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-800 border border-blue-200 rounded-md font-bold text-[11px]" x-text="eng.project_name"></span>
                                </template>
                                <template x-if="!eng.project_id">
                                    <span class="text-slate-400 italic">Unassigned (Global)</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <form :action="'/engineers/' + eng.id + '/toggle-status'" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Click to toggle status" 
                                            class="px-2.5 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition"
                                            :class="eng.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'"
                                            x-text="eng.is_active ? 'Active' : 'Inactive'">
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    {{-- View Trigger --}}
                                    <button type="button" @click="initView(eng)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    
                                    {{-- Edit Trigger --}}
                                    <button type="button" @click="initEdit(eng)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Engineer">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    {{-- Delete Trigger --}}
                                    <button type="button" @click="initDelete(eng)" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Engineer">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedEngineers.length === 0">
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No engineer records match the selected filters.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Dynamic Client Pagination Bar --}}
        <div x-show="totalFiltered > 0" class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-800" x-text="((currentPage - 1) * perPage) + 1"></span> to 
                <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, totalFiltered)"></span> of 
                <span class="font-bold text-slate-800" x-text="totalFiltered"></span> engineers
            </div>

            <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                <button type="button" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-bold transition bg-white">
                    Previous
                </button>

                <template x-for="page in totalPages" :key="page">
                    <button type="button" @click="goToPage(page)"
                            :class="currentPage === page ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-300'"
                            class="w-8 h-8 rounded-lg border text-xs font-bold transition flex items-center justify-center cursor-pointer"
                            x-text="page">
                    </button>
                </template>

                <button type="button" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-bold transition bg-white">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- View Modal (No outer border) -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all flex flex-col" @click.outside="openViewModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">ENGINEER MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ENGINEER DETAILS</h3>
                </div>
                <button type="button" @click="openViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
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
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                    <span class="font-bold" :class="viewEngineer.is_active ? 'text-emerald-600' : 'text-slate-500'" x-text="viewEngineer.is_active ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal (No outer border) -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all flex flex-col" @click.outside="openAddModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">ENGINEER MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD SITE ENGINEER</h3>
                </div>
                <button type="button" @click="openAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('engineers.store') }}" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ENGINEER CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="engineer_code" required placeholder="e.g. ENG-004" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Vikram Sharma" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                            <input type="text" name="phone" placeholder="9876543210" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                            <input type="email" name="email" placeholder="engineer@hindustan.com" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="designation" required value="Site Engineer" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SPECIALIZATION</label>
                            <input type="text" name="specialization" placeholder="e.g. Civil / RA Bills" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ASSIGNED PROJECT</label>
                        <select name="project_id" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                            <option value="">-- Global / Unassigned --</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="eng_add_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29] cursor-pointer">
                        <label for="eng_add_active" class="text-xs font-bold text-slate-700 cursor-pointer">Active Engineer Status</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE ENGINEER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (No outer border) -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all flex flex-col" @click.outside="openEditModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">ENGINEER MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT SITE ENGINEER</h3>
                </div>
                <button type="button" @click="openEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'/engineers/' + editEngineer.id" method="POST" class="flex flex-col">
                <div class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ENGINEER CODE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="engineer_code" x-model="editEngineer.engineer_code" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" x-model="editEngineer.name" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                            <input type="text" name="phone" x-model="editEngineer.phone" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                            <input type="email" name="email" x-model="editEngineer.email" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="designation" x-model="editEngineer.designation" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SPECIALIZATION</label>
                            <input type="text" name="specialization" x-model="editEngineer.specialization" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">ASSIGNED PROJECT</label>
                        <select name="project_id" x-model="editEngineer.project_id" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                            <option value="">-- Global / Unassigned --</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="eng_edit_active" value="1" :checked="editEngineer.is_active" class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29] cursor-pointer">
                        <label for="eng_edit_active" class="text-xs font-bold text-slate-700 cursor-pointer">Active Engineer Status</label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">UPDATE ENGINEER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal (No outer border) -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all flex flex-col" @click.outside="openDeleteModal = false">
            <div class="bg-rose-950 p-5 text-white flex items-center justify-between border-b border-rose-900">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-rose-900/40 text-rose-200 text-[9px] font-black uppercase tracking-wider rounded border border-rose-800 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE SITE ENGINEER</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center bg-white text-xs font-sans">
                <p class="font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteEngineer.name"></span>?</p>
                <form :action="'/engineers/' + deleteEngineer.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
