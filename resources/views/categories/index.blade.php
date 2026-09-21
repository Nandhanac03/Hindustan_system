<x-erp-layout title="Category Master">
<script>
function categoryMasterComponent() {
    return {
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        openViewModal: false,
        search: '',
        filterProjectId: '',
        filterStatus: '',
        addCategory: { category: '', project_id: '', status: 'active' },
        viewCategory: { id: null, category: '', project_name: '', status: 'active', created_at: '' },
        editCategory: { id: null, category: '', project_id: '', status: 'active' },
        deleteCategory: { id: null, category: '' },
        categories: @json($categoriesArray),
        get filteredCategories() {
            return this.categories.filter(c => {
                const searchLower = this.search.toLowerCase().trim();
                const matchesSearch = !searchLower || 
                    (c.category && c.category.toLowerCase().includes(searchLower)) || 
                    (c.project_name && c.project_name.toLowerCase().includes(searchLower));
                const matchesProject = !this.filterProjectId || String(c.project_id) === String(this.filterProjectId);
                const matchesStatus = !this.filterStatus || String(c.status).toLowerCase() === String(this.filterStatus).toLowerCase();
                return matchesSearch && matchesProject && matchesStatus;
            });
        },
        initAdd() {
            this.addCategory = { category: '', project_id: this.filterProjectId, status: 'active' };
            this.openAddModal = true;
        },
        resetFilters() {
            this.search = '';
            this.filterProjectId = '';
            this.filterStatus = '';
        },
        initView(cat) {
            this.viewCategory = { ...cat };
            this.openViewModal = true;
        },
        initEdit(cat) {
            this.editCategory = { ...cat };
            this.openEditModal = true;
        },
        initDelete(cat) {
            this.deleteCategory = { ...cat };
            this.openDeleteModal = true;
        }
    };
}
</script>

<div class="space-y-6 p-6" x-data="categoryMasterComponent()">
    <!-- Header Section Card -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-blue-500/10 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Category Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage Expense & Site Categories, Project Scopes, and Master Statuses</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="initAdd()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Category</span>
            </button>
        </div>
    </div>

    <!-- Flash Success Message -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-xs font-bold">✕</button>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-xs font-bold">✕</button>
    </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Categories (Gold) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#a38c29]/50">
            <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider mb-1">Total Categories</p>
            <h4 class="text-[22px] font-bold text-[#8a7522] m-0">{{ $totalCategories }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Master Categories</p>
        </div>

        <!-- Active Categories (Green) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
            <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Active Categories</p>
            <h4 class="text-[22px] font-bold text-[#10b981] m-0">{{ $activeCategories }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">On Active Status</p>
        </div>

        <!-- Project Scopes (Blue) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-blue-600 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-300">
            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-1">Project Scopes</p>
            <h4 class="text-[22px] font-bold text-blue-700 m-0">{{ $assignedCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Site Allocations</p>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Instant Filter Panel (No Page Reload / No URL Change) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Search Category Name..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    <template x-if="search">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                            <button type="button" @click="search = ''"
                                   class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Project Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                        </svg>
                    </div>
                    <select x-model="filterProjectId"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
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
                    <select x-model="filterStatus"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <button type="button" @click="resetFilters()"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0 cursor-pointer">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5 w-16">SL.NO</th>
                        <th class="px-4 py-3.5">CATEGORY NAME</th>
                        <th class="px-4 py-3.5">PROJECT SCOPE</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(cat, index) in filteredCategories" :key="cat.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold font-mono text-slate-700" x-text="index + 1"></td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900" x-text="cat.category"></td>
                            <td class="px-4 py-3.5 font-medium text-slate-800">
                                <template x-if="cat.project_name && cat.project_name !== 'Unassigned (Global)'">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-800 border border-blue-200 rounded-md font-bold text-[11px]" x-text="cat.project_name"></span>
                                </template>
                                <template x-if="!cat.project_name || cat.project_name === 'Unassigned (Global)'">
                                    <span class="text-slate-400 italic">Unassigned (Global)</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <form :action="'{{ url('/categories') }}/' + cat.id + '/toggle-status'" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Click to toggle status" class="px-2.5 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition capitalize" :class="String(cat.status).toLowerCase() === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'" x-text="cat.status">
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="initView(cat)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="initEdit(cat)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Category">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredCategories.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium">
                            No Categories found matching your filter criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Category Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openViewModal = false">
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">CATEGORY MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Category Details</h2>
                    </div>
                    <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-3.5 text-xs bg-white">
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CATEGORY ID</span>
                    <span class="font-bold font-mono text-[#a38c29]" x-text="'CAT-' + String(viewCategory.id).padStart(3, '0')"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CATEGORY NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewCategory.category"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PROJECT SCOPE</span>
                    <span class="font-semibold text-blue-700" x-text="viewCategory.project_name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                    <span class="font-bold capitalize" :class="String(viewCategory.status).toLowerCase() === 'active' ? 'text-emerald-600' : 'text-slate-500'" x-text="viewCategory.status"></span>
                </div>
                <div class="flex justify-between pb-1">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CREATED AT</span>
                    <span class="font-mono text-slate-700" x-text="viewCategory.created_at"></span>
                </div>
                <div class="pt-4 border-t border-slate-100 text-right">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition uppercase cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openAddModal = false">
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">MASTER CONFIGURATION</p>
                        <h2 class="text-lg font-extrabold text-white">Add New Category</h2>
                    </div>
                    <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" x-model="addCategory.category" required placeholder="e.g. Refreshments, Transport, Minor Tools..." 
                           class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Project Scope (Optional)</label>
                    <select name="project_id" x-model="addCategory.project_id" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none transition cursor-pointer shadow-2xs">
                        <option value="">All Projects (Global Scope)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Leave blank if this category applies globally to all projects.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" x-model="addCategory.status" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none transition cursor-pointer shadow-2xs">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2 text-xs font-extrabold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition uppercase tracking-wider cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-xs font-black uppercase text-white bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openEditModal = false">
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">EDIT MASTER</p>
                        <h2 class="text-lg font-extrabold text-white">Edit Category Details</h2>
                    </div>
                    <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="'{{ url('/categories') }}/' + editCategory.id" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" x-model="editCategory.category" required
                           class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Project Scope (Optional)</label>
                    <select name="project_id" x-model="editCategory.project_id" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none transition cursor-pointer shadow-2xs">
                        <option value="">All Projects (Global Scope)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" x-model="editCategory.status" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 outline-none transition cursor-pointer shadow-2xs">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2 text-xs font-extrabold text-slate-600 hover:text-slate-900 bg-white border border-slate-300 hover:bg-slate-100 rounded-xl transition uppercase tracking-wider cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-xs font-black uppercase text-white bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden flex flex-col" @click.outside="openDeleteModal = false">
            <div class="p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center text-xl font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Delete Category</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Are you sure you want to delete <strong class="text-slate-800" x-text="deleteCategory.category"></strong>?</p>
                </div>

                <form :action="'{{ url('/categories') }}/' + deleteCategory.id" method="POST" class="pt-2 flex items-center justify-center gap-3">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="button" @click="openDeleteModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition uppercase tracking-wider cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-black uppercase text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-md shadow-rose-600/20 cursor-pointer">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-erp-layout>
