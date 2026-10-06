<x-erp-layout title="Document Types Master" headerTitle="Document Types Directory">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="dmsDocumentTypeApp()">

        <!-- Breadcrumb & Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Document Management</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Document Types Master</span>
            </div>

            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Add Document Type</span>
            </button>
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Card 1: Total Document Types --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Document Types</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $documentTypes->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total registered document sub-types</p>
                </div>
            </div>

            {{-- Card 2: Active Document Types --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Active Document Types</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $documentTypes->where('is_active', true)->count() }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Currently in active use</p>
                </div>
            </div>
        </div>

        <!-- Ultra-Clean Modern Instant Filter Panel -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                    {{-- Search Input with Gold Icon --}}
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Search Document Type Name..." 
                               class="w-full pl-10 pr-10 erp-search-input">
                        <template x-if="search">
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                                <button type="button" @click="search = ''"
                                       class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Category Group Filter (Custom Gold Popover) --}}
                    <div class="relative w-full" @click.outside="categoryFilterOpen = false">
                        <button type="button"
                                @click="categoryFilterOpen = !categoryFilterOpen; if(categoryFilterOpen) { statusFilterOpen = false; }"
                                class="erp-dropdown-trigger"
                                :class="categoryFilterOpen ? 'active' : ''">
                            <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                                <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span class="truncate text-xs font-bold"
                                      :class="filterCategoryId ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                      x-text="getSelectedCategoryName()">All Category Groups</span>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                <template x-if="filterCategoryId">
                                    <span @click.stop="selectCategoryFilter('')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </span>
                                </template>
                                <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="categoryFilterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>

                        {{-- Category Popover Menu --}}
                        <div x-show="categoryFilterOpen" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="erp-dropdown-popover"
                             style="display: none;">
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="selectCategoryFilter('')"
                                     class="erp-dropdown-option"
                                     :class="!filterCategoryId ? 'selected-all' : ''">
                                    <span>All Category Groups</span>
                                </div>
                                @foreach($categories as $cat)
                                    <div @click="selectCategoryFilter('{{ $cat->id }}')"
                                         class="erp-dropdown-option"
                                         :class="String(filterCategoryId) === '{{ $cat->id }}' ? 'selected' : ''">
                                        <span>{{ $cat->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Status Filter (Custom Gold Popover) --}}
                    <div class="relative w-full" @click.outside="statusFilterOpen = false">
                        <button type="button"
                                @click="statusFilterOpen = !statusFilterOpen; if(statusFilterOpen) { categoryFilterOpen = false; }"
                                class="erp-dropdown-trigger"
                                :class="statusFilterOpen ? 'active' : ''">
                            <div class="flex items-center gap-2 overflow-hidden min-w-0 flex-1">
                                <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate text-xs font-bold"
                                      :class="filterStatus ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'"
                                      x-text="getStatusLabel()">All Statuses</span>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                <template x-if="filterStatus">
                                    <span @click.stop="selectStatusFilter('')" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selection">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </span>
                                </template>
                                <svg class="w-3.5 h-3.5 text-[#a38c29] transition-transform duration-200" :class="statusFilterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>

                        {{-- Status Popover Menu --}}
                        <div x-show="statusFilterOpen" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="erp-dropdown-popover"
                             style="display: none;">
                            <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                                <div @click="selectStatusFilter('')"
                                     class="erp-dropdown-option"
                                     :class="!filterStatus ? 'selected-all' : ''">
                                    <span>All Statuses</span>
                                </div>
                                <div @click="selectStatusFilter('active')"
                                     class="erp-dropdown-option"
                                     :class="filterStatus === 'active' ? 'selected' : ''">
                                    <span>Active</span>
                                </div>
                                <div @click="selectStatusFilter('inactive')"
                                     class="erp-dropdown-option"
                                     :class="filterStatus === 'inactive' ? 'selected' : ''">
                                    <span>Inactive</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <button type="button" @click="resetFilters()"
                   class="inline-flex items-center justify-center gap-2 rounded-xl theme-btn px-5 h-[38px] text-xs font-extrabold flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>

        <!-- Directory Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6 flex flex-col">
            
            <!-- Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Document Types List
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Manage sub-types grouped under document categories.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse">
                    <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                        <tr class="erp-table-header border-b border-slate-700 text-left">
                            <th class="px-5 py-3.5 border-r border-slate-600 w-16 text-center">ID</th>
                            <th class="px-5 py-3.5 border-r border-slate-600">Document Type</th>
                            <th class="px-5 py-3.5 border-r border-slate-600">Category Group</th>
                            <th class="px-5 py-3.5 border-r border-slate-600 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documentTypes as $type)
                            <tr x-show="isDocTypeMatch('{{ strtolower(addslashes($type->name)) }}', '{{ $type->dms_category_id }}', {{ $type->is_active ? 'true' : 'false' }})"
                                class="hover:bg-slate-50 transition group">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">
                                    {{ str_pad((string)$type->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">
                                    {{ $type->name }}
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-650">
                                    {{ $type->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($type->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider border border-emerald-200/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal({{ $type->toJson() }})" class="p-2 rounded-xl transition-colors inline-flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button type="button" @click="openDeleteModal({{ $type->toJson() }})" class="p-2 rounded-xl transition-colors inline-flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-100 cursor-pointer" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm font-bold text-slate-500">No Document Types Found</p>
                                    <p class="text-xs mt-1">Add a new type to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Document Type Modal -->
        <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/20">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">New Document Type</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white">Add Document Type</h3>
                        </div>
                        <button type="button" @click="closeAddModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                    </div>
                </div>
                <form action="{{ route('dms.document-types.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Document Category Group *</label>
                        <select name="dms_category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800 cursor-pointer">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Document Type Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Environmental NOC" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="closeAddModal()" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Save Type</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Document Type Modal -->
        <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/20">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">Edit Document Type</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white truncate max-w-[280px]" x-text="forms.edit.name || 'Edit Document Type'"></h3>
                        </div>
                        <button type="button" @click="closeEditModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                    </div>
                </div>
                <form :action="editUrl" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Document Category Group *</label>
                        <select name="dms_category_id" x-model="forms.edit.dms_category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800 cursor-pointer">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Document Type Name *</label>
                        <input type="text" name="name" x-model="forms.edit.name" required placeholder="e.g. Environmental NOC" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all text-xs font-semibold text-slate-800">
                    </div>
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="closeEditModal()" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Document Type Confirmation Modal -->
        <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-500/10">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Safety Check</span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Delete Document Type</h2>
                        </div>
                        <button type="button" @click="closeDeleteModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer">✕</button>
                    </div>
                </div>
                <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-2">
                        <p class="text-sm text-slate-700">
                            Are you sure you want to delete document type <span class="font-bold text-slate-900" x-text="deleteTarget?.name"></span>?
                        </p>
                        <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">This action cannot be undone and will remove the record.</p>
                    </div>
                </div>
                <form :action="deleteUrl" method="POST" class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50 m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer">Confirm Delete</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function dmsDocumentTypeApp() {
            const categoriesMap = @json($categories->pluck('name', 'id'));

            return {
                search: '',
                filterCategoryId: '',
                filterStatus: '',
                categoryFilterOpen: false,
                statusFilterOpen: false,

                selectCategoryFilter(catId) {
                    this.filterCategoryId = catId;
                    this.categoryFilterOpen = false;
                },
                selectStatusFilter(status) {
                    this.filterStatus = status;
                    this.statusFilterOpen = false;
                },
                resetFilters() {
                    this.search = '';
                    this.filterCategoryId = '';
                    this.filterStatus = '';
                    this.categoryFilterOpen = false;
                    this.statusFilterOpen = false;
                },
                getSelectedCategoryName() {
                    if (!this.filterCategoryId) return 'All Category Groups';
                    return categoriesMap[this.filterCategoryId] || 'Category Group';
                },
                getStatusLabel() {
                    if (this.filterStatus === 'active') return 'Active';
                    if (this.filterStatus === 'inactive') return 'Inactive';
                    return 'All Statuses';
                },
                isDocTypeMatch(name, catId, isActive) {
                    if (this.filterCategoryId && String(catId) !== String(this.filterCategoryId)) return false;
                    if (this.filterStatus === 'active' && !isActive) return false;
                    if (this.filterStatus === 'inactive' && isActive) return false;
                    if (this.search) {
                        const q = this.search.toLowerCase().trim();
                        if (!name.includes(q)) return false;
                    }
                    return true;
                },
                modals: {
                    add: { open: false },
                    edit: { open: false },
                    delete: { open: false }
                },
                forms: {
                    edit: { id: '', name: '', dms_category_id: '' }
                },
                deleteTarget: null,
                deleteUrl: '',
                editUrl: '',
                openAddModal() {
                    this.modals.add.open = true;
                },
                closeAddModal() {
                    this.modals.add.open = false;
                },
                openEditModal(type) {
                    this.forms.edit = {
                        id: type.id,
                        name: type.name,
                        dms_category_id: type.dms_category_id
                    };
                    this.editUrl = `{{ url('dms/document-types') }}/${type.id}`;
                    this.modals.edit.open = true;
                },
                closeEditModal() {
                    this.modals.edit.open = false;
                },
                openDeleteModal(type) {
                    this.deleteTarget = type;
                    this.deleteUrl = `{{ url('dms/document-types') }}/${type.id}`;
                    this.modals.delete.open = true;
                },
                closeDeleteModal() {
                    this.modals.delete.open = false;
                    this.deleteTarget = null;
                }
            }
        }
    </script>

</x-erp-layout>
