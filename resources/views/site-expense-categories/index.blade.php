<x-erp-layout title="Site Expense Category Master">
<script>
function siteExpenseCategoryComponent() {
    return {
        openAddModal: false,
        openEditModal: false,
        openViewModal: false,
        search: '',
        filterStatus: '',
        addCategory: { category_code: '', category_name: '', description: '', status: 'active' },
        viewCategory: { id: null, category_code: '', category_name: '', description: '', status: 'active', created_at: '' },
        editCategory: { id: null, category_code: '', category_name: '', description: '', status: 'active' },
        categories: @json($categoriesArray),
        get filteredCategories() {
            return this.categories.filter(c => {
                const searchLower = this.search.toLowerCase().trim();
                const matchesSearch = !searchLower || 
                    (c.category_name && c.category_name.toLowerCase().includes(searchLower)) || 
                    (c.category_code && c.category_code.toLowerCase().includes(searchLower));
                const matchesStatus = !this.filterStatus || String(c.status).toLowerCase() === String(this.filterStatus).toLowerCase();
                return matchesSearch && matchesStatus;
            });
        },
        initAdd() {
            this.addCategory = { category_code: '', category_name: '', description: '', status: 'active' };
            this.openAddModal = true;
        },
        resetFilters() {
            this.search = '';
            this.filterStatus = '';
        },
        initView(cat) {
            this.viewCategory = { ...cat };
            this.openViewModal = true;
        },
        initEdit(cat) {
            this.editCategory = { ...cat };
            this.openEditModal = true;
        }
    };
}
</script>

<div class="space-y-6 p-6" x-data="siteExpenseCategoryComponent()">
    <!-- Header Section Card -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Site Expense Category Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage Site Expense Categories, Codes, and Master Statuses</p>
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
            <p class="text-[10px] text-gray-500 mt-1">Site Expense Categories</p>
        </div>

        <!-- Active Categories (Green) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
            <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Active Categories</p>
            <h4 class="text-[22px] font-bold text-[#10b981] m-0">{{ $activeCategories }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">On Active Status</p>
        </div>

        <!-- Inactive Categories (Slate) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-slate-400 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-slate-300">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Inactive Categories</p>
            <h4 class="text-[22px] font-bold text-slate-700 m-0">{{ $inactiveCategories }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Disabled Categories</p>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Instant Filter Panel (No Page Reload) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Search Code or Name..." 
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
                        <th class="px-4 py-3.5 w-28">CODE</th>
                        <th class="px-4 py-3.5">CATEGORY NAME</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="(cat, index) in filteredCategories" :key="cat.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold font-mono text-slate-700" x-text="index + 1"></td>
                            <td class="px-4 py-3.5 font-mono font-bold text-[#a38c29]">
                                <span class="px-2 py-0.5 bg-[#a38c29]/10 rounded border border-[#a38c29]/20" x-text="cat.category_code || '—'"></span>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900" x-text="cat.category_name"></td>
                            <td class="px-4 py-3.5 text-center">
                                <form :action="'{{ url('/site-expense-categories') }}/' + cat.id + '/toggle-status'" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Click to toggle status" class="px-2.5 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition capitalize" :class="String(cat.status).toLowerCase() === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200'" x-text="cat.status">
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="initView(cat)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="initEdit(cat)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="Edit Category">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredCategories.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-medium">
                            No Site Expense Categories found matching your filter criteria.
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
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">SITE EXPENSE MASTER</p>
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
                    <span class="font-bold font-mono text-[#a38c29]" x-text="'SEC-' + String(viewCategory.id).padStart(3, '0')"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CATEGORY CODE</span>
                    <span class="font-bold font-mono text-slate-800" x-text="viewCategory.category_code || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2.5">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CATEGORY NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewCategory.category_name"></span>
                </div>
                <template x-if="viewCategory.description">
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DESCRIPTION</span>
                        <span class="font-medium text-slate-700 text-right" x-text="viewCategory.description"></span>
                    </div>
                </template>
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
                        <h2 class="text-lg font-extrabold text-white">Add Site Expense Category</h2>
                    </div>
                    <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('site-expense-categories.store') }}" method="POST" class="p-6 space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Code (Optional)</label>
                        <input type="text" name="category_code" x-model="addCategory.category_code" placeholder="e.g. 4050" 
                               class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="category_name" x-model="addCategory.category_name" required placeholder="e.g. Site Office & Administrative..." 
                               class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description / Notes</label>
                    <textarea name="description" x-model="addCategory.description" rows="2" placeholder="Optional notes or details about this expense category..."
                              class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 outline-none transition shadow-2xs"></textarea>
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
                        <h2 class="text-lg font-extrabold text-white">Edit Site Expense Category</h2>
                    </div>
                    <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="'{{ url('/site-expense-categories') }}/' + editCategory.id" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Code (Optional)</label>
                        <input type="text" name="category_code" x-model="editCategory.category_code" placeholder="e.g. 4050" 
                               class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="category_name" x-model="editCategory.category_name" required
                               class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 outline-none transition shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description / Notes</label>
                    <textarea name="description" x-model="editCategory.description" rows="2" placeholder="Optional notes..."
                              class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 outline-none transition shadow-2xs"></textarea>
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
</div>
</x-erp-layout>
