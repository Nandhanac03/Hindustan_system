<x-erp-layout>
    <div class="space-y-6" x-data="moduleManagementApp()">

        {{-- Toast Notification --}}
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl text-white text-xs font-bold"
             :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'"
             style="display: none;">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                <path x-show="toast.type !== 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-text="toast.message"></span>
        </div>

        {{-- BREADCRUMBS & TOP HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>ADMINISTRATION</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span>USERS &amp; ROLES</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-[#a38c29] font-bold">MODULES &amp; SUB-MODULES</span>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center font-bold text-lg shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">ERP Modules &amp; Sub-modules</h1>
                        <p class="text-xs text-slate-500 font-medium">Catalog of all ERP module groups and sub-modules for security gates &amp; role permissions</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.roles.index') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Role Permissions</span>
                </a>

                <button type="button" @click="openAddModuleModalFn()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/25 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Module</span>
                </button>
            </div>
        </div>

        {{-- KPI STATS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Modules --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">ERP Modules</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-[#a38c29] font-bold bg-[#a38c29]/10 px-2 py-0.5 rounded-md border border-[#a38c29]/20 uppercase tracking-wider shadow-sm">
                        <span x-text="modulesList.length"></span> MODULES
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-[#a38c29] font-mono tracking-tight block" x-text="modulesList.length">
                        {{ $totalModules }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Top-level system functional groups</p>
                </div>
            </div>

            {{-- Card 2: Total Sub-modules --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Sub-modules</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm">
                        <span x-text="computedTotalSubmodules"></span> SUB-MODULES
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block" x-text="computedTotalSubmodules">
                        {{ $totalSubModules }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Screen routes &amp; operational items</p>
                </div>
            </div>

            {{-- Card 3: Core ERP Sections --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-purple-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-purple-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(147,51,234,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100/60 transition-all duration-300 group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">ERP Sections</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 uppercase tracking-wider shadow-sm">
                        <span x-text="computedSections.length"></span> SECTIONS
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-purple-600 font-mono tracking-tight block" x-text="computedSections.length">
                        {{ count($sections) }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Distinct sidebar operational domains</p>
                </div>
            </div>

            {{-- Card 4: Active Modules --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Modules</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm">
                        100% ENABLED
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block" x-text="computedActiveModules">
                        {{ $activeModulesCount }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Configured &amp; operational modules</p>
                </div>
            </div>
        </div>

        {{-- SEARCH & FILTER BAR --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Search Input --}}
                <div class="relative flex-1 min-w-[260px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery"
                           placeholder="Search modules, sub-modules or route codes..."
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium">
                </div>

                {{-- Section Filter --}}
                <div class="min-w-[190px]">
                    <select x-model="sectionFilter"
                            class="w-full px-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition cursor-pointer">
                        <option value="all">All Sections (Overview to Admin)</option>
                        <template x-for="sec in computedSections" :key="sec">
                            <option :value="sec" x-text="sec"></option>
                        </template>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="min-w-[140px]">
                    <select x-model="statusFilter"
                            class="w-full px-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition cursor-pointer">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                {{-- Signature Gold RESET Button --}}
                <div>
                    <button type="button" @click="resetFilters()"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer active:scale-95 group">
                        <svg class="w-3.5 h-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>RESET</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- MODULES & SUB-MODULES ACCORDION LIST --}}
        <div class="space-y-4">
            <template x-for="mod in filteredModules" :key="mod.id">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden transition-all duration-200 hover:border-slate-300">
                    {{-- Module Header --}}
                    <div class="p-4 sm:p-5 bg-slate-50/80 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 cursor-pointer select-none"
                         @click="toggleExpandModule(mod.id)">
                        <div class="flex items-center gap-3.5 min-w-0">
                            {{-- Expand Chevron --}}
                            <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-2xs transition-transform duration-200"
                                 :class="expandedModules.includes(mod.id) ? 'rotate-90 text-[#a38c29] bg-[#a38c29]/10 border-[#a38c29]/20' : ''">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-900 truncate" x-text="mod.name"></h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200" x-text="mod.section"></span>
                                    <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-bold bg-slate-200/70 text-slate-700" x-text="mod.code"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-0.5 font-medium" x-text="mod.description"></p>
                            </div>
                        </div>

                        {{-- Right Side Actions --}}
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0" @click.stop>
                            {{-- Module Status Toggle Pill --}}
                            <button type="button" @click="toggleModuleStatusFn(mod)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase transition cursor-pointer select-none"
                                    :class="mod.status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100'"
                                    :title="'Click to toggle status (Currently ' + mod.status + ')'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="mod.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                <span x-text="mod.status"></span>
                            </button>

                            {{-- Sub-modules Counter Pill --}}
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30"
                                  x-text="(mod.sub_modules ? mod.sub_modules.length : 0) + ' Sub-modules'"></span>

                            {{-- Add Sub-module Button --}}
                            <button type="button" @click="openAddSubModuleModalFn(mod)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Add Sub-module</span>
                            </button>

                            {{-- Edit Module Button --}}
                            <button type="button" @click="openEditModuleModalFn(mod)"
                                    class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Edit Module">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            {{-- Delete Module Button --}}
                            <button type="button" @click="deleteModuleFn(mod)"
                                    class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Delete Module">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Expanded Sub-modules Section --}}
                    <div x-show="expandedModules.includes(mod.id)" class="p-4 sm:p-5 border-t border-slate-100 bg-white">
                        <template x-if="mod.sub_modules && mod.sub_modules.length > 0">
                            <div class="overflow-x-auto rounded-xl border border-slate-200/80">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-700">
                                            <th class="px-4 py-3 w-12 text-center border-r border-slate-600 text-white">#</th>
                                            <th class="px-4 py-3 border-r border-slate-600 text-white">Sub-module Name</th>
                                            <th class="px-4 py-3 border-r border-slate-600 text-white">Permission Code</th>
                                            <th class="px-4 py-3 border-r border-slate-600 text-white">Target Route</th>
                                            <th class="px-4 py-3 border-r border-slate-600 text-white">Allowed Actions</th>
                                            <th class="px-4 py-3 border-r border-slate-600 text-white text-center">Status</th>
                                            <th class="px-4 py-3 text-right pr-5 text-white">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="(sub, sIdx) in mod.sub_modules" :key="sub.id">
                                            <tr class="hover:bg-slate-50/70 transition">
                                                <td class="px-4 py-3 text-center text-slate-400 font-medium" x-text="sIdx + 1"></td>
                                                <td class="px-4 py-3">
                                                    <span class="block font-bold text-slate-900" x-text="sub.name"></span>
                                                    <span class="block text-[10px] text-slate-400" x-text="sub.description"></span>
                                                </td>
                                                <td class="px-4 py-3 font-mono text-[11px] font-bold text-[#a38c29]" x-text="sub.code"></td>
                                                <td class="px-4 py-3 font-mono text-[11px] text-slate-600" x-text="sub.route_name || '—'"></td>
                                                <td class="px-4 py-3">
                                                    <div class="flex flex-wrap gap-1">
                                                        <template x-for="act in (sub.actions || [])" :key="act">
                                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase transition inline-flex items-center gap-0.5"
                                                                  :class="{
                                                                      'bg-emerald-50 text-emerald-700 border border-emerald-200': act === 'excel',
                                                                      'bg-rose-50 text-rose-700 border border-rose-200': act === 'pdf',
                                                                      'bg-slate-100 text-slate-700 border border-slate-200': !['excel', 'pdf', 'delete', 'approve', 'disburse'].includes(act),
                                                                      'bg-red-50 text-red-600 border border-red-100': act === 'delete',
                                                                      'bg-amber-50 text-amber-700 border border-amber-200': ['approve', 'disburse'].includes(act)
                                                                  }">
                                                                <template x-if="act === 'excel'">
                                                                    <svg class="w-2.5 h-2.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm1 4a1 1 0 00-1 1v7a1 1 0 001 1h12a1 1 0 001-1V9a1 1 0 00-1-1H4zm3 2a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                                                                </template>
                                                                <template x-if="act === 'pdf'">
                                                                    <svg class="w-2.5 h-2.5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                                                </template>
                                                                <span x-text="act === 'excel' ? 'EXCEL' : (act === 'pdf' ? 'PDF' : act)"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button type="button" @click="toggleSubModuleStatusFn(mod, sub)"
                                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase transition cursor-pointer select-none"
                                                            :class="sub.status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100'"
                                                            :title="'Click to toggle status (Currently ' + sub.status + ')'">
                                                        <span class="w-1.5 h-1.5 rounded-full" :class="sub.status === 'active' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                                        <span x-text="sub.status"></span>
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3 text-right pr-5 whitespace-nowrap">
                                                    <div class="inline-flex items-center gap-1">
                                                        <button type="button" @click="openEditSubModuleModalFn(mod, sub)"
                                                                class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition" title="Edit Sub-module">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                        </button>
                                                        <button type="button" @click="deleteSubModuleFn(mod, sub)"
                                                                class="p-1.5 rounded-md hover:bg-rose-50 text-rose-500 transition" title="Delete Sub-module">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>

                        <template x-if="!mod.sub_modules || mod.sub_modules.length === 0">
                            <div class="py-8 text-center bg-slate-50 rounded-xl border border-slate-200">
                                <p class="text-xs text-slate-400 font-medium italic">No sub-modules added to this module yet.</p>
                                <button type="button" @click="openAddSubModuleModalFn(mod)"
                                        class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-[#a38c29] text-white text-xs font-bold rounded-xl uppercase tracking-wider shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>Add First Sub-module</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Empty Search Results --}}
            <div x-show="filteredModules.length === 0" class="p-12 text-center bg-white rounded-2xl border border-slate-200">
                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-bold text-slate-700">No modules match your search criteria</p>
                <p class="text-xs text-slate-400 mt-1">Try resetting filters or adding a new module.</p>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL 1: ADD / EDIT MODULE MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="moduleModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="moduleModal.open = false">
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1">MODULE DIRECTORY</p>
                            <h2 class="text-base font-black text-white" x-text="moduleModal.isEdit ? 'Edit System Module' : 'Create New System Module'"></h2>
                        </div>
                        <button type="button" @click="moduleModal.open = false" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="submitModuleModal()">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Module Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" x-model="moduleModal.form.name" placeholder="e.g., Procurement &amp; Inventory" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Module Code <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="moduleModal.form.code" placeholder="e.g., procurement" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Section / Domain <span class="text-rose-500">*</span>
                                </label>
                                <select x-model="moduleModal.form.section" required
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                                    <template x-for="sec in computedSections" :key="sec">
                                        <option :value="sec" x-text="sec"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Description
                            </label>
                            <textarea x-model="moduleModal.form.description" rows="2" placeholder="Brief description of this module's functionality"
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                            <select x-model="moduleModal.form.status"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                                <option value="active">Active &amp; Visible</option>
                                <option value="inactive">Inactive / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="moduleModal.open = false" class="px-4 py-2 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase tracking-wider transition">CANCEL</button>
                        <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20"
                                x-text="moduleModal.isEdit ? 'UPDATE MODULE' : 'CREATE MODULE'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL 2: ADD / EDIT SUB-MODULE MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="subModuleModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="subModuleModal.open = false">
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1" x-text="subModuleModal.parentModule ? subModuleModal.parentModule.name.toUpperCase() : 'PARENT MODULE'"></p>
                            <h2 class="text-base font-black text-white" x-text="subModuleModal.isEdit ? 'Edit Sub-module' : 'Add New Sub-module'"></h2>
                        </div>
                        <button type="button" @click="subModuleModal.open = false" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="submitSubModuleModal()">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Sub-module Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" x-model="subModuleModal.form.name" placeholder="e.g., Purchase Orders" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Permission Code <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="subModuleModal.form.code" placeholder="e.g., purchase.orders" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Target Route / URL
                                </label>
                                <input type="text" x-model="subModuleModal.form.route_name" placeholder="e.g., purchase-orders.index"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Allowed Security Actions
                                </label>
                                <span class="text-[10px] text-slate-400 font-medium">Fine-grained action rights</span>
                            </div>
                            <div class="flex flex-wrap gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <template x-for="act in availableSecurityActions" :key="act.key">
                                    <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase cursor-pointer select-none border transition active:scale-95"
                                           :class="subModuleModal.form.actions.includes(act.key)
                                               ? (act.key === 'excel' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : (act.key === 'pdf' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-[#a38c29] text-white border-[#a38c29] shadow-xs'))
                                               : (act.key === 'excel' ? 'bg-white text-emerald-700 border-emerald-200 hover:bg-emerald-50' : (act.key === 'pdf' ? 'bg-white text-rose-700 border-rose-200 hover:bg-rose-50' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'))">
                                        <input type="checkbox" :value="act.key" x-model="subModuleModal.form.actions" class="hidden">
                                        <span class="flex items-center gap-1">
                                            <template x-if="act.key === 'excel'">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </template>
                                            <template x-if="act.key === 'pdf'">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            </template>
                                            <span x-text="act.label"></span>
                                        </span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Description
                            </label>
                            <textarea x-model="subModuleModal.form.description" rows="2" placeholder="Brief explanation of this screen's purpose"
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                            <select x-model="subModuleModal.form.status"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                                <option value="active">Active &amp; Visible</option>
                                <option value="inactive">Inactive / Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="subModuleModal.open = false" class="px-4 py-2 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase tracking-wider transition">CANCEL</button>
                        <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20"
                                x-text="subModuleModal.isEdit ? 'UPDATE SUB-MODULE' : 'ADD SUB-MODULE'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
    function moduleManagementApp() {
        const rawModules = {!! json_encode($modules ?? []) !!};

        // Normalize any legacy 'export' action to 'excel' and 'pdf'
        (rawModules || []).forEach(m => {
            (m.sub_modules || []).forEach(sm => {
                if (Array.isArray(sm.actions)) {
                    const clean = [];
                    sm.actions.forEach(a => {
                        if (a === 'export') {
                            if (!clean.includes('excel')) clean.push('excel');
                            if (!clean.includes('pdf')) clean.push('pdf');
                        } else if (!clean.includes(a)) {
                            clean.push(a);
                        }
                    });
                    sm.actions = clean;
                }
            });
        });

        return {
            modulesList: rawModules,
            searchQuery: '',
            sectionFilter: 'all',
            statusFilter: 'all',
            expandedModules: [], // Collapsed by default; expand on click

            availableSecurityActions: [
                { key: 'view', label: 'View' },
                { key: 'create', label: 'Create' },
                { key: 'edit', label: 'Edit' },
                { key: 'delete', label: 'Delete' },
                { key: 'excel', label: 'Excel' },
                { key: 'pdf', label: 'PDF' },
                { key: 'approve', label: 'Approve' },
                { key: 'disburse', label: 'Disburse' }
            ],

            toast: {
                show: false,
                message: '',
                type: 'success'
            },

            moduleModal: {
                open: false,
                isEdit: false,
                editId: null,
                form: {
                    name: '',
                    code: '',
                    section: 'Overview',
                    description: '',
                    status: 'active'
                }
            },

            subModuleModal: {
                open: false,
                isEdit: false,
                parentModule: null,
                editSubId: null,
                form: {
                    name: '',
                    code: '',
                    route_name: '',
                    actions: ['view', 'create', 'edit', 'delete', 'excel', 'pdf'],
                    description: '',
                    status: 'active'
                }
            },

            get computedSections() {
                const list = [];
                (this.modulesList || []).forEach(m => {
                    if (m.section && !list.includes(m.section)) {
                        list.push(m.section);
                    }
                });
                return list;
            },

            get computedTotalSubmodules() {
                let count = 0;
                (this.modulesList || []).forEach(m => {
                    count += (m.sub_modules ? m.sub_modules.length : 0);
                });
                return count;
            },

            get computedActiveModules() {
                return (this.modulesList || []).filter(m => m.status === 'active').length;
            },

            get filteredModules() {
                let list = this.modulesList || [];

                if (this.searchQuery && this.searchQuery.trim()) {
                    const q = this.searchQuery.toLowerCase().trim();
                    list = list.filter(m => {
                        const matchModule = (m.name && m.name.toLowerCase().includes(q)) ||
                                            (m.code && m.code.toLowerCase().includes(q)) ||
                                            (m.section && m.section.toLowerCase().includes(q));
                        if (matchModule) return true;
                        // check sub_modules
                        const matchSub = (m.sub_modules || []).some(sm =>
                            (sm.name && sm.name.toLowerCase().includes(q)) ||
                            (sm.code && sm.code.toLowerCase().includes(q)) ||
                            (sm.route_name && sm.route_name.toLowerCase().includes(q))
                        );
                        return matchSub;
                    });
                }

                if (this.sectionFilter && this.sectionFilter !== 'all') {
                    list = list.filter(m => m.section === this.sectionFilter);
                }

                if (this.statusFilter && this.statusFilter !== 'all') {
                    list = list.filter(m => m.status === this.statusFilter);
                }

                return list;
            },

            toggleExpandModule(id) {
                if (this.expandedModules.includes(id)) {
                    this.expandedModules = this.expandedModules.filter(x => x !== id);
                } else {
                    this.expandedModules.push(id);
                }
            },

            resetFilters() {
                this.searchQuery = '';
                this.sectionFilter = 'all';
                this.statusFilter = 'all';
            },

            showToast(message, type = 'success') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            },

            openAddModuleModalFn() {
                this.moduleModal.isEdit = false;
                this.moduleModal.editId = null;
                this.moduleModal.form = {
                    name: '',
                    code: '',
                    section: this.computedSections.length > 0 ? this.computedSections[0] : 'Administration',
                    description: '',
                    status: 'active'
                };
                this.moduleModal.open = true;
            },

            openEditModuleModalFn(mod) {
                this.moduleModal.isEdit = true;
                this.moduleModal.editId = mod.id;
                this.moduleModal.form = {
                    name: mod.name || '',
                    code: mod.code || '',
                    section: mod.section || 'Overview',
                    description: mod.description || '',
                    status: mod.status || 'active'
                };
                this.moduleModal.open = true;
            },

            submitModuleModal() {
                const url = this.moduleModal.isEdit 
                    ? `/admin/modules/${this.moduleModal.editId}`
                    : `{{ route('admin.modules.store') }}`;

                const payload = {
                    ...this.moduleModal.form,
                    _method: this.moduleModal.isEdit ? 'PUT' : 'POST'
                };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (this.moduleModal.isEdit) {
                            const mod = this.modulesList.find(m => m.id === this.moduleModal.editId);
                            if (mod) {
                                mod.name = this.moduleModal.form.name;
                                mod.code = this.moduleModal.form.code;
                                mod.section = this.moduleModal.form.section;
                                mod.description = this.moduleModal.form.description;
                                mod.status = this.moduleModal.form.status;
                            }
                        } else if (data.module) {
                            this.modulesList.push(data.module);
                            this.expandedModules.push(data.module.id);
                        }
                        this.moduleModal.open = false;
                        this.showToast(data.message || 'Module saved successfully.', 'success');
                    } else {
                        this.showToast(data.message || 'Error saving module.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Failed to save module.', 'error');
                });
            },

            deleteModuleFn(mod) {
                if (!confirm(`Are you sure you want to delete module "${mod.name}" and all its sub-modules?`)) return;

                fetch(`/admin/modules/${mod.id}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.modulesList = this.modulesList.filter(m => m.id !== mod.id);
                        this.showToast(data.message || 'Module deleted.', 'success');
                    } else {
                        this.showToast(data.message || 'Could not delete module.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Error deleting module.', 'error');
                });
            },

            toggleModuleStatusFn(mod) {
                fetch(`/admin/modules/${mod.id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mod.status = data.status;
                        this.showToast(data.message || `Module status set to ${data.status}`, 'success');
                    } else {
                        this.showToast(data.message || 'Failed to update status.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Network error updating status.', 'error');
                });
            },

            toggleSubModuleStatusFn(mod, sub) {
                fetch(`/admin/modules/${mod.id}/submodules/${sub.id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        sub.status = data.status;
                        this.showToast(data.message || `Sub-module status set to ${data.status}`, 'success');
                    } else {
                        this.showToast(data.message || 'Failed to update status.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Network error updating status.', 'error');
                });
            },

            openAddSubModuleModalFn(mod) {
                this.subModuleModal.isEdit = false;
                this.subModuleModal.parentModule = mod;
                this.subModuleModal.editSubId = null;
                this.subModuleModal.form = {
                    name: '',
                    code: (mod.code ? mod.code.toLowerCase() + '.' : ''),
                    route_name: '',
                    actions: ['view', 'create', 'edit', 'delete', 'excel', 'pdf'],
                    description: '',
                    status: 'active'
                };
                this.subModuleModal.open = true;
            },

            openEditSubModuleModalFn(mod, sub) {
                this.subModuleModal.isEdit = true;
                this.subModuleModal.parentModule = mod;
                this.subModuleModal.editSubId = sub.id;

                const rawActions = Array.isArray(sub.actions) ? [...sub.actions] : ['view'];
                const cleanActions = [];
                rawActions.forEach(a => {
                    if (a === 'export') {
                        if (!cleanActions.includes('excel')) cleanActions.push('excel');
                        if (!cleanActions.includes('pdf')) cleanActions.push('pdf');
                    } else if (!cleanActions.includes(a)) {
                        cleanActions.push(a);
                    }
                });

                this.subModuleModal.form = {
                    name: sub.name || '',
                    code: sub.code || '',
                    route_name: sub.route_name || '',
                    actions: cleanActions,
                    description: sub.description || '',
                    status: sub.status || 'active'
                };
                this.subModuleModal.open = true;
            },

            submitSubModuleModal() {
                if (!this.subModuleModal.parentModule) return;
                const parentId = this.subModuleModal.parentModule.id;

                const url = this.subModuleModal.isEdit
                    ? `/admin/modules/${parentId}/submodules/${this.subModuleModal.editSubId}`
                    : `/admin/modules/${parentId}/submodules`;

                const payload = {
                    ...this.subModuleModal.form,
                    _method: this.subModuleModal.isEdit ? 'PUT' : 'POST'
                };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const mod = this.modulesList.find(m => m.id === parentId);
                        if (mod) {
                            if (!mod.sub_modules) mod.sub_modules = [];
                            if (this.subModuleModal.isEdit) {
                                const sub = mod.sub_modules.find(s => s.id === this.subModuleModal.editSubId);
                                if (sub) {
                                    sub.name = this.subModuleModal.form.name;
                                    sub.code = this.subModuleModal.form.code;
                                    sub.route_name = this.subModuleModal.form.route_name;
                                    sub.actions = [...this.subModuleModal.form.actions];
                                    sub.description = this.subModuleModal.form.description;
                                    sub.status = this.subModuleModal.form.status;
                                }
                            } else if (data.sub_module) {
                                mod.sub_modules.push(data.sub_module);
                            }
                        }
                        this.subModuleModal.open = false;
                        this.showToast(data.message || 'Sub-module saved.', 'success');
                    } else {
                        this.showToast(data.message || 'Error saving sub-module.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Failed to save sub-module.', 'error');
                });
            },

            deleteSubModuleFn(mod, sub) {
                if (!confirm(`Are you sure you want to delete sub-module "${sub.name}"?`)) return;

                fetch(`/admin/modules/${mod.id}/submodules/${sub.id}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mod.sub_modules = mod.sub_modules.filter(s => s.id !== sub.id);
                        this.showToast(data.message || 'Sub-module deleted.', 'success');
                    } else {
                        this.showToast(data.message || 'Cannot delete sub-module.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Error deleting sub-module.', 'error');
                });
            }
        };
    }
    </script>
</x-erp-layout>

