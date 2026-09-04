<x-erp-layout title="Voucher Type Master">
<div class="space-y-6 p-6" x-data="voucherMasterData({{ json_encode($allVouchers ?? []) }})">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Voucher Types</h1>
                    <p class="text-xs text-slate-500 font-medium">Configure dynamic voucher types, prefixes, and auto-numbering rules</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Voucher Type</span>
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
        <!-- Total Voucher Types (Gold) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#a38c29]/50">
            <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider mb-1">Total Voucher Types</p>
            <h4 class="text-[22px] font-bold text-[#8a7522] m-0">{{ $totalCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Configured Definitions</p>
        </div>
        <!-- Active Types (Green) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
            <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Active Types</p>
            <h4 class="text-[22px] font-bold text-[#10b981] m-0">{{ $activeCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Enabled for Posting</p>
        </div>
        <!-- Inactive Types (Red) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#ef4444] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#ef4444]/30">
            <p class="text-[11px] font-bold text-[#ef4444] uppercase tracking-wider mb-1">Inactive Types</p>
            <h4 class="text-[22px] font-bold text-[#ef4444] m-0">{{ $inactiveCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Disabled / Deprecated</p>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel (No Page Reload, Clean URL) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Search by Code, Display Name, Prefix, or Description..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    <div x-show="search" class="absolute inset-y-0 right-0 pr-2.5 flex items-center" style="display: none;">
                        <button type="button" @click="search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Status Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                        </svg>
                    </div>
                    <select x-model="statusFilter"
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

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">CODE</th>
                        <th class="px-4 py-3.5">DISPLAY NAME</th>
                        <th class="px-4 py-3.5">PREFIX</th>
                        <th class="px-4 py-3.5">DESCRIPTION</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="v in filteredVouchers" :key="v.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-400 font-mono" x-text="'#' + v.id"></td>
                            <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]" x-text="v.code"></td>
                            <td class="px-4 py-3.5 font-semibold text-slate-900" x-text="v.name"></td>
                            <td class="px-4 py-3.5 font-mono">
                                <span class="px-2 py-1 bg-slate-100 rounded text-slate-700 font-bold border border-slate-200 text-[11px]" x-text="v.prefix"></span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 max-w-xs truncate" x-text="v.description || '—'"></td>
                            <td class="px-4 py-3.5 text-center">
                                <template x-if="v.is_active">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                </template>
                                <template x-if="!v.is_active">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button type="button" @click="initView(v)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button type="button" @click="initEdit(v)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Voucher Type">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredVouchers.length === 0" style="display: none;">
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400 italic">No voucher types found matching search filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Modal (Refined to Match Units Setup Style) -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.outside="openViewModal = false">
            <!-- Modal Header -->
            <div class="relative overflow-hidden rounded-t-2xl bg-[#2e291b] px-6 py-5 flex items-center justify-between flex-shrink-0">
                <div class="relative z-10">
                    <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">VOUCHER TYPES MASTER</p>
                    <h2 class="text-lg font-extrabold text-white">Voucher Type Details</h2>
                </div>
                <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6 space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Voucher Code</span>
                        <span class="font-bold font-mono text-sm text-[#a38c29]" x-text="viewVoucher.code"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Prefix</span>
                        <span class="font-bold font-mono text-xs bg-slate-100 px-2.5 py-1 rounded border border-slate-200 text-slate-800" x-text="viewVoucher.prefix"></span>
                    </div>
                </div>
                <div class="border-b border-slate-100 pb-3">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Display Name</span>
                    <span class="font-bold text-sm text-slate-900" x-text="viewVoucher.name"></span>
                </div>
                <div class="border-b border-slate-100 pb-3">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Description</span>
                    <span class="font-semibold text-slate-700" x-text="viewVoucher.description || '—'"></span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Active Status</span>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold" :class="viewVoucher.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'" x-text="viewVoucher.is_active ? 'Active' : 'Inactive'"></span>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                <button type="button" @click="openViewModal = false" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase tracking-wider rounded-lg transition shadow-xs cursor-pointer">CLOSE</button>
            </div>
        </div>
    </div>

    <!-- Add Modal (Refined to Match Units Setup Style) -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.outside="openAddModal = false">
            <!-- Modal Header -->
            <div class="relative overflow-hidden rounded-t-2xl bg-[#2e291b] px-6 py-5 flex items-center justify-between flex-shrink-0">
                <div class="relative z-10">
                    <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">VOUCHER TYPES MASTER</p>
                    <h2 class="text-lg font-extrabold text-white">Add New Voucher Type</h2>
                </div>
                <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Form Body -->
            <form action="{{ route('voucher-types.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">VOUCHER CODE (UNIQUE) <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" required placeholder="E.G., PETTY_CASH_CONTRA" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold uppercase text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">PREFIX <span class="text-rose-500">*</span></label>
                            <input type="text" name="prefix" required placeholder="E.G., JV-PC" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold uppercase text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISPLAY NAME <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g., Petty Cash Contra Transfer" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESCRIPTION</label>
                        <textarea name="description" rows="2" placeholder="Generated on..." class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-semibold text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="vt_add_active" value="1" checked class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29] cursor-pointer">
                        <label for="vt_add_active" class="text-xs font-bold text-slate-700 cursor-pointer">Active Voucher Type Status</label>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-lg transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm transition cursor-pointer">SAVE VOUCHER TYPE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal (Refined to Match Units Setup Style) -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.outside="openEditModal = false">
            <!-- Modal Header -->
            <div class="relative overflow-hidden rounded-t-2xl bg-[#2e291b] px-6 py-5 flex items-center justify-between flex-shrink-0">
                <div class="relative z-10">
                    <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">VOUCHER TYPES MASTER</p>
                    <h2 class="text-lg font-extrabold text-white">Edit Voucher Type</h2>
                </div>
                <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Form Body -->
            <form :action="'/voucher-types/' + editVoucher.id" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">VOUCHER CODE (UNIQUE) <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" x-model="editVoucher.code" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold uppercase text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">PREFIX <span class="text-rose-500">*</span></label>
                            <input type="text" name="prefix" x-model="editVoucher.prefix" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold uppercase text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">DISPLAY NAME <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editVoucher.name" required class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-bold text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESCRIPTION</label>
                        <textarea name="description" x-model="editVoucher.description" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-lg font-semibold text-slate-900 focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] outline-none transition resize-none"></textarea>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="vt_edit_active" value="1" x-model="editVoucher.is_active" class="w-4 h-4 rounded border-slate-300 text-[#a38c29] focus:ring-[#a38c29] cursor-pointer">
                        <label for="vt_edit_active" class="text-xs font-bold text-slate-700 cursor-pointer">Active Voucher Type Status</label>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-lg transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm transition cursor-pointer">UPDATE VOUCHER TYPE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all" @click.outside="openDeleteModal = false">
            <div class="relative overflow-hidden rounded-t-2xl bg-[#2e291b] px-6 py-5 flex items-center justify-between flex-shrink-0">
                <div class="relative z-10">
                    <p class="text-rose-400 text-[10px] font-semibold uppercase tracking-widest mb-1">CONFIRMATION</p>
                    <h2 class="text-lg font-extrabold text-white">Delete Voucher Type</h2>
                </div>
                <button type="button" @click="openDeleteModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4 text-center">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteVoucher.name"></span>?</p>
                <form :action="'/voucher-types/' + deleteVoucher.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-lg transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm transition cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function voucherMasterData(initialVouchers) {
    return {
        allVouchers: initialVouchers || [],
        search: '',
        statusFilter: '',
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        openViewModal: false,
        viewVoucher: { id: null, code: '', name: '', prefix: '', description: '', is_active: true },
        editVoucher: { id: null, code: '', name: '', prefix: '', description: '', is_active: true },
        deleteVoucher: { id: null, name: '' },

        init() {
            if (window.location.search) {
                window.history.replaceState(null, '', window.location.pathname);
            }
        },

        get filteredVouchers() {
            let list = this.allVouchers || [];
            if (this.statusFilter) {
                if (this.statusFilter === 'active') {
                    list = list.filter(function(v) { return v.is_active; });
                } else if (this.statusFilter === 'inactive') {
                    list = list.filter(function(v) { return !v.is_active; });
                }
            }
            if (this.search && this.search.trim() !== '') {
                const q = this.search.trim().toLowerCase();
                list = list.filter(function(v) {
                    const code = (v.code || '').toLowerCase();
                    const name = (v.name || '').toLowerCase();
                    const prefix = (v.prefix || '').toLowerCase();
                    const desc = (v.description || '').toLowerCase();
                    return code.includes(q) || name.includes(q) || prefix.includes(q) || desc.includes(q);
                });
            }
            return list;
        },

        initView(v) {
            this.viewVoucher = Object.assign({}, v);
            this.openViewModal = true;
        },
        initEdit(v) {
            this.editVoucher = Object.assign({}, v);
            this.openEditModal = true;
        },
        initDelete(v) {
            this.deleteVoucher = Object.assign({}, v);
            this.openDeleteModal = true;
        },
        resetFilters() {
            this.search = '';
            this.statusFilter = '';
        }
    };
}
</script>
</x-erp-layout>
