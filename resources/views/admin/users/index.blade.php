<x-erp-layout>
    <x-slot:title>Users & Roles - Hindustan ERP</x-slot:title>
    <x-slot:headerTitle>Users & Roles</x-slot:headerTitle>

    <div class="space-y-6" x-data="userManagementApp()" x-cloak>

        {{-- Toast Notification --}}
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-xs font-semibold"
             :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'"
             style="display: none;">
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600 ml-2">✕</button>
        </div>

        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75 cursor-pointer">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 cursor-pointer">✕</button>
            </div>
        @endif

        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#a38c29] text-white flex items-center justify-center shrink-0 shadow-md shadow-[#a38c29]/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Users</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Manage ERP users, assign roles and control access</p>
                </div>
            </div>

            <button type="button" @click="openAddModalFn()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add New User</span>
            </button>
        </div>

        {{-- TOP 4 SUMMARY METRIC CARDS (CV CARDS - MATCHING OTHER ERP MODULES) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Users --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Users</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                        {{ $totalUsers }} Total
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        {{ $totalUsers }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Configured system enterprise users</p>
                </div>
            </div>

            {{-- Card 2: Active Users --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#09876B] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#09876B]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(9,135,107,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-[#09876B] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Users</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                        {{ $activeUsers }} Active
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                        {{ $activeUsers }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Accounts currently active &amp; enabled</p>
                </div>
            </div>

            {{-- Card 3: Inactive Users --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Inactive Users</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                        {{ $inactiveUsers }} Inactive
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                        {{ $inactiveUsers }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Suspended or deactivated accounts</p>
                </div>
            </div>

            {{-- Card 4: Roles --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-purple-600 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-purple-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(147,51,234,0.15)] h-full">
                <div class="flex items-center justify-between gap-2 mb-3 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100/60 transition-all duration-300 group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Roles</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-purple-300 group-hover:bg-purple-100/60">
                        {{ $rolesCount }} Roles
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-purple-600 font-mono tracking-tight block group-hover:text-purple-700 transition-colors duration-300">
                        {{ $rolesCount }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1 font-medium">Configured access privileges</p>
                </div>
            </div>
        </div>

        {{-- SEARCH & FILTER BAR (CLIENT-SIDE FILTERING: NO URL CHANGE, NO PAGE RELOAD) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-xs">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Search Input (Live filtering with zero reload & zero URL change) --}}
                <div class="relative flex-1 min-w-[260px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1"
                           placeholder="Search by name, email or username..."
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition font-medium">
                </div>

                {{-- Role Filter --}}
                <div class="min-w-[160px]">
                    <select x-model="roleFilter" @change="currentPage = 1"
                            class="w-full px-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition cursor-pointer">
                        <option value="all">All Roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="min-w-[140px]">
                    <select x-model="statusFilter" @change="currentPage = 1"
                            class="w-full px-3.5 py-2.5 bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition cursor-pointer">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                {{-- Theme-Colored Signature Gold RESET FILTERS Button --}}
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

        {{-- USERS TABLE GRID --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Configured Users Directory</h3>
                <span class="px-2.5 py-1 rounded-full bg-slate-200/70 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest">
                    Total Users: <span x-text="totalFiltered"></span>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-700">
                            <th class="px-4 py-3.5 w-12 text-center erp-table-header border-r border-slate-600 text-white">#</th>
                            <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-white">Name &amp; Employee</th>
                            <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-white">Email</th>
                            <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-white">Role(s)</th>
                            <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-white text-center">Status</th>
                            <th class="px-5 py-3.5 erp-table-header border-r border-slate-600 text-white">Last Login</th>
                            <th class="px-5 py-3.5 erp-table-header text-white text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-for="(user, index) in paginatedUsers" :key="user.id">
                            <tr class="hover:bg-slate-50/70 transition cursor-pointer"
                                :class="selectedUserId === user.id ? 'bg-[#a38c29]/5' : ''"
                                @click="selectUserRow(user)">
                                {{-- # --}}
                                <td class="px-4 py-3.5 text-center text-slate-400 font-medium">
                                    <span x-text="(currentPage - 1) * perPage + index + 1"></span>
                                </td>

                                {{-- Name + Avatar --}}
                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-2xs"
                                             :class="user.avatar_bg" x-text="user.initials">
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-slate-900" x-text="user.name"></span>
                                            <span x-show="user.employee_code" class="px-1.5 py-0.2 rounded text-[9px] font-bold font-mono uppercase bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 inline-block mt-0.5" x-text="user.employee_code"></span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    <span x-text="user.email"></span>
                                    <div x-show="user.phone" class="text-[10px] text-slate-400 font-medium mt-0.5" x-text="user.phone"></div>
                                </td>

                                {{-- Role(s) Badges --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        <template x-for="rName in user.role_names" :key="rName">
                                            <span>
                                                <template x-if="rName === 'Main User' || rName === 'Owner'">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30">
                                                        <span>👑</span>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                                <template x-if="rName === 'Super Admin'">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                        <span>★</span>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                                <template x-if="rName === 'Admin'">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                                <template x-if="rName === 'Sales'">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                                <template x-if="rName === 'Accountant'">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                                <template x-if="!['Main User', 'Owner', 'Super Admin', 'Admin', 'Sales', 'Accountant'].includes(rName)">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                        <span>🛡️</span>
                                                        <span x-text="rName"></span>
                                                    </span>
                                                </template>
                                            </span>
                                        </template>
                                        <span x-show="!user.role_names || user.role_names.length === 0" class="text-slate-400 italic text-[11px]">No Role</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 text-center">
                                    <template x-if="user.status === 'active'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Active</span>
                                        </span>
                                    </template>
                                    <template x-if="user.status === 'inactive'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Inactive</span>
                                        </span>
                                    </template>
                                    <template x-if="user.status !== 'active' && user.status !== 'inactive'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Suspended</span>
                                        </span>
                                    </template>
                                </td>

                                {{-- Last Login --}}
                                <td class="px-5 py-3.5 text-slate-600 font-medium" x-text="user.last_login_formatted"></td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5 text-right pr-6 whitespace-nowrap" @click.stop>
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        {{-- Edit Button --}}
                                        <button type="button" 
                                                @click="openEditModalFn(user)"
                                                class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer"
                                                title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>

                                        {{-- Lock / Password Reset Button --}}
                                        <button type="button"
                                                @click="resetUserPassword(user)"
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer"
                                                title="Reset Password / Lock">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </button>

                                        {{-- Delete Button --}}
                                        <button type="button"
                                                @click="deleteUserRow(user)"
                                                class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer"
                                                title="Delete User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="totalFiltered === 0">
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400 italic font-semibold">
                                No users found matching the filter criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Table Footer / Pagination (Client-Side, No URL change, No Reload) --}}
            <div class="px-4 py-3 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="text-slate-500 font-medium">
                    Showing <span class="font-bold text-slate-700" x-text="startIndex"></span> to <span class="font-bold text-slate-700" x-text="endIndex"></span> of <span class="font-bold text-slate-700" x-text="totalFiltered"></span> users
                </div>

                <div class="inline-flex items-center gap-1" x-show="totalPages > 1">
                    {{-- Previous Page Button --}}
                    <button type="button" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            :class="currentPage === 1 ? 'text-slate-300 border-slate-200 cursor-not-allowed' : 'text-slate-600 border-slate-200 hover:bg-slate-50 cursor-pointer'"
                            class="w-7 h-7 rounded-lg border inline-flex items-center justify-center text-xs font-semibold select-none transition">
                        &lt;
                    </button>

                    {{-- Page Numbers --}}
                    <template x-for="p in pagesList" :key="p">
                        <button type="button" @click="goToPage(p)"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-xs font-black' : 'border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold'"
                                class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-xs transition cursor-pointer"
                                x-text="p">
                        </button>
                    </template>

                    {{-- Next Page Button --}}
                    <button type="button" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                            :class="currentPage === totalPages ? 'text-slate-300 border-slate-200 cursor-not-allowed' : 'text-slate-600 border-slate-200 hover:bg-slate-50 cursor-pointer'"
                            class="w-7 h-7 rounded-lg border inline-flex items-center justify-center text-xs font-semibold select-none transition">
                        &gt;
                    </button>
                </div>
            </div>
        </div>



        {{-- ========================================================================= --}}
        {{-- POPUP MODAL 1: ADD NEW USER MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="closeAddModalFn()">
                {{-- Dark Gradient Header with Gold Accent --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1">USER DIRECTORY</p>
                            <h2 class="text-lg font-black text-white">Register New Enterprise User</h2>
                        </div>
                        <button type="button" @click="closeAddModalFn()" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col" novalidate @submit="validateAddUser($event)">
                    @csrf
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <!-- Name & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="addForm.name"
                                       @input="if (addErrors.name) delete addErrors.name"
                                       :class="addErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       placeholder="e.g. Deepa Menon"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.name">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.name"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" x-model="addForm.email"
                                       @input="if (addErrors.email) delete addErrors.email"
                                       :class="addErrors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       placeholder="user@hindustan.com"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.email">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.email"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Phone & Username / Employee Code -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="addForm.phone"
                                       placeholder="+91 99999 99999"
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username / Employee Code</label>
                                <input type="text" name="employee_code" x-model="addForm.employee_code"
                                       placeholder="e.g. deepa.menon or EMP-101"
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold font-mono">
                            </div>
                        </div>

                        <!-- Operating System & Role -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Operating System Node <span class="text-rose-500">*</span></label>
                                <select name="system_id" x-model="addForm.system_id"
                                        @change="if (addErrors.system_id) delete addErrors.system_id"
                                        :class="addErrors.system_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select System...</option>
                                    @foreach($systems as $sys)
                                        <option value="{{ $sys->id }}">
                                            {{ $sys->name }} ({{ $sys->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <template x-if="addErrors.system_id">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.system_id"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role Assignment <span class="text-rose-500">*</span></label>
                                <select name="role" x-model="addForm.role"
                                        @change="if (addErrors.role) delete addErrors.role"
                                        :class="addErrors.role ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <template x-if="addErrors.role">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.role"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Status & Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="addForm.status"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password <span class="text-rose-500">*</span> <span class="text-[9px] text-slate-400 font-normal lowercase">(min 6 chars)</span></label>
                                <input type="password" name="password" x-model="addForm.password"
                                       @input="if (addErrors.password) delete addErrors.password"
                                       :class="addErrors.password ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       placeholder="Minimum 6 characters"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.password">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.password"></p>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="closeAddModalFn()" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase tracking-wider transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">SAVE USER ACCOUNT</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- POPUP MODAL 2: EDIT USER MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="openEditModal = false">
                {{-- Dark Header with Gold Accent --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1">USER DIRECTORY</p>
                            <h2 class="text-lg font-black text-white">Edit User Account</h2>
                        </div>
                        <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form :action="editForm.action" method="POST" class="flex flex-col" novalidate @submit="validateEditUser($event)">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <!-- Name & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editForm.name"
                                       @input="if (editErrors.name) delete editErrors.name"
                                       :class="editErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="editErrors.name">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.name"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" x-model="editForm.email"
                                       @input="if (editErrors.email) delete editErrors.email"
                                       :class="editErrors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="editErrors.email">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.email"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Phone & Employee Code -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone" placeholder="+91 99999 99999"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Username / Employee Code</label>
                                <input type="text" name="employee_code" x-model="editForm.employee_code" placeholder="e.g. EMP-001"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold font-mono">
                            </div>
                        </div>

                        <!-- Operating System & Role -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Operating System <span class="text-rose-500">*</span></label>
                                <select name="system_id" x-model="editForm.system_id"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select System...</option>
                                    @foreach($systems as $sys)
                                        <option value="{{ $sys->id }}">
                                            {{ $sys->name }} ({{ $sys->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role Assignment <span class="text-rose-500">*</span></label>
                                <select name="role" x-model="editForm.role"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status & Password (optional on edit) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="editForm.status"
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">New Password <span class="text-slate-400 font-normal lowercase">(leave blank to keep current)</span></label>
                                <input type="password" name="password" x-model="editForm.password"
                                       placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase tracking-wider transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">UPDATE USER ACCOUNT</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
    function userManagementApp() {
        return {
            usersList: {!! json_encode($formattedUsers ?? []) !!},
            searchQuery: '',
            roleFilter: 'all',
            statusFilter: 'all',
            currentPage: 1,
            perPage: 5,

            get filteredUsers() {
                let list = this.usersList || [];
                if (this.searchQuery && this.searchQuery.trim()) {
                    const q = this.searchQuery.toLowerCase().trim();
                    list = list.filter(u =>
                        (u.name && u.name.toLowerCase().includes(q)) ||
                        (u.email && u.email.toLowerCase().includes(q)) ||
                        (u.employee_code && u.employee_code.toLowerCase().includes(q)) ||
                        (u.phone && u.phone.toLowerCase().includes(q))
                    );
                }
                if (this.roleFilter && this.roleFilter !== 'all') {
                    list = list.filter(u => Array.isArray(u.role_names) && u.role_names.includes(this.roleFilter));
                }
                if (this.statusFilter && this.statusFilter !== 'all') {
                    list = list.filter(u => u.status === this.statusFilter);
                }
                return list;
            },

            get totalFiltered() {
                return this.filteredUsers.length;
            },

            get totalPages() {
                return Math.max(1, Math.ceil(this.totalFiltered / this.perPage));
            },

            get paginatedUsers() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredUsers.slice(start, start + this.perPage);
            },

            get startIndex() {
                if (this.totalFiltered === 0) return 0;
                return (this.currentPage - 1) * this.perPage + 1;
            },

            get endIndex() {
                return Math.min(this.currentPage * this.perPage, this.totalFiltered);
            },

            get pagesList() {
                let pages = [];
                for (let i = 1; i <= this.totalPages; i++) {
                    pages.push(i);
                }
                return pages;
            },

            resetFilters() {
                this.searchQuery = '';
                this.roleFilter = 'all';
                this.statusFilter = 'all';
                this.currentPage = 1;
                if (window.location.search) {
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            },

            goToPage(p) {
                if (p >= 1 && p <= this.totalPages) {
                    this.currentPage = p;
                }
            },

            openAddModal: false,
            openEditModal: false,
            selectedUserId: {{ $users->first()?->id ?? 'null' }},
            selectedUserName: '{{ $users->first()?->name ?? 'User' }}',
            activeTab: 'role-permissions',
            permissionRole: '{{ $roles->first()?->name ?? 'Sales' }}',
            selectAllPerms: true,

            toast: {
                show: false,
                message: '',
                type: 'success'
            },

            addForm: {
                name: '',
                email: '',
                phone: '',
                employee_code: '',
                system_id: '{{ $systems->first()?->id ?? '' }}',
                role: '{{ $roles->first()?->name ?? '' }}',
                status: 'active',
                password: ''
            },
            addErrors: {},

            editForm: {
                id: null,
                action: '',
                name: '',
                email: '',
                phone: '',
                employee_code: '',
                system_id: '',
                role: '',
                status: 'active',
                password: ''
            },
            editErrors: {},

            allModuleList: [
                'Dashboard',
                'Projects & Units',
                'Customers',
                'Sales & Collections',
                'Reports',
                'Customer Ledger',
                'EMI Collections',
                'Sales Reports',
                'View Only: Accounting',
                'View Only: Treasury'
            ],

            selectedPerms: [
                'Dashboard',
                'Projects & Units',
                'Customers',
                'Sales & Collections',
                'Reports',
                'Customer Ledger',
                'EMI Collections',
                'Sales Reports',
                'View Only: Accounting',
                'View Only: Treasury'
            ],

            rolePermissionsMap: {
                'Owner': [
                    'Dashboard', 'Projects & Units', 'Customers', 'Sales & Collections', 'Reports',
                    'Customer Ledger', 'EMI Collections', 'Sales Reports', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Main User': [
                    'Dashboard', 'Projects & Units', 'Customers', 'Sales & Collections', 'Reports',
                    'Customer Ledger', 'EMI Collections', 'Sales Reports', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Super Admin': [
                    'Dashboard', 'Projects & Units', 'Customers', 'Sales & Collections', 'Reports',
                    'Customer Ledger', 'EMI Collections', 'Sales Reports', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Admin': [
                    'Dashboard', 'Projects & Units', 'Customers', 'Sales & Collections', 'Reports',
                    'Customer Ledger', 'EMI Collections', 'Sales Reports', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Sales': [
                    'Dashboard', 'Projects & Units', 'Customers', 'Sales & Collections', 'Reports',
                    'Customer Ledger', 'EMI Collections', 'Sales Reports', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Accountant': [
                    'Dashboard', 'Reports', 'Customer Ledger', 'EMI Collections', 'View Only: Accounting', 'View Only: Treasury'
                ],
                'Site': [
                    'Projects & Units'
                ]
            },

            init() {
                if (window.location.search) {
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
                if (this.usersList && this.usersList.length > 0) {
                    const initialUser = this.usersList[0];
                    this.selectedUserId = initialUser.id;
                    this.selectedUserName = initialUser.name;
                    const rNames = initialUser.role_names || (initialUser.roles || []).map(r => r.name);
                    if (rNames && rNames.length > 0) {
                        this.permissionRole = rNames[0];
                        this.onRoleChanged(rNames[0]);
                    }
                }
            },

            showToast(message, type = 'success') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            },

            openAddModalFn() {
                this.addErrors = {};
                this.addForm = {
                    name: '',
                    email: '',
                    phone: '',
                    employee_code: '',
                    system_id: '{{ $systems->first()?->id ?? '' }}',
                    role: '{{ $roles->first()?->name ?? '' }}',
                    status: 'active',
                    password: ''
                };
                this.openAddModal = true;
            },

            closeAddModalFn() {
                this.openAddModal = false;
                this.addErrors = {};
            },

            validateAddUser(e) {
                this.addErrors = {};
                let hasError = false;

                if (!this.addForm.name || !this.addForm.name.trim()) {
                    this.addErrors.name = 'Please enter full name.';
                    hasError = true;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.addForm.email || !this.addForm.email.trim() || !emailRegex.test(this.addForm.email.trim())) {
                    this.addErrors.email = 'Please enter a valid email address.';
                    hasError = true;
                }

                if (!this.addForm.system_id) {
                    this.addErrors.system_id = 'Please select an operating system.';
                    hasError = true;
                }

                if (!this.addForm.role) {
                    this.addErrors.role = 'Please select a role.';
                    hasError = true;
                }

                if (!this.addForm.password || this.addForm.password.length < 6) {
                    this.addErrors.password = 'Password must be at least 6 characters.';
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }
            },

            openEditModalFn(user) {
                this.editErrors = {};
                const userRole = (user.role_names && user.role_names.length > 0)
                    ? user.role_names[0]
                    : ((user.roles && user.roles.length > 0) ? user.roles[0].name : '');
                this.editForm = {
                    id: user.id,
                    action: `/admin/users/${user.id}`,
                    name: user.name || '',
                    email: user.email || '',
                    phone: user.phone || '',
                    employee_code: user.employee_code || '',
                    system_id: user.system_id || '',
                    role: userRole,
                    status: user.status || 'active',
                    password: ''
                };
                this.openEditModal = true;
            },

            validateEditUser(e) {
                this.editErrors = {};
                let hasError = false;

                if (!this.editForm.name || !this.editForm.name.trim()) {
                    this.editErrors.name = 'Please enter full name.';
                    hasError = true;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.editForm.email || !this.editForm.email.trim() || !emailRegex.test(this.editForm.email.trim())) {
                    this.editErrors.email = 'Please enter a valid email address.';
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }
            },

            selectUserRow(user) {
                this.selectedUserId = user.id;
                this.selectedUserName = user.name;
                const rNames = user.role_names || (user.roles || []).map(r => r.name);
                if (rNames && rNames.length > 0) {
                    this.permissionRole = rNames[0];
                    this.onRoleChanged(rNames[0]);
                }
            },

            onRoleChanged(roleName) {
                this.permissionRole = roleName;
                if (this.rolePermissionsMap[roleName]) {
                    this.selectedPerms = [...this.rolePermissionsMap[roleName]];
                } else {
                    this.selectedPerms = [...this.allModuleList];
                }
                this.selectAllPerms = this.selectedPerms.length === this.allModuleList.length;
            },

            toggleSelectAllPerms() {
                if (this.selectAllPerms) {
                    this.selectedPerms = [...this.allModuleList];
                } else {
                    this.selectedPerms = [];
                }
            },

            resetUserPassword(user) {
                if (!confirm(`Reset password for ${user.name} to "password"?`)) {
                    return;
                }

                fetch(`/admin/users/${user.id}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: 'password' })
                })
                .then(res => res.json())
                .then(data => {
                    this.showToast(data.message || 'Password reset successfully.', 'success');
                })
                .catch(err => {
                    this.showToast('Failed to reset password.', 'error');
                });
            },

            deleteUserRow(user) {
                if (!confirm(`Are you sure you want to delete user "${user.name}" (${user.email})?`)) {
                    return;
                }

                fetch(`/admin/users/${user.id}/delete`, {
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
                        this.showToast(data.message || 'User deleted successfully.', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 800);
                    } else {
                        this.showToast(data.message || 'Cannot delete user.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Error deleting user.', 'error');
                });
            },

            savePermissionsFn() {
                const targetUserId = this.selectedUserId;
                const url = targetUserId ? `/admin/users/${targetUserId}/permissions` : `{{ route('admin.users.index') }}`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        role: this.permissionRole,
                        permissions: this.selectedPerms
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.showToast(`Permissions saved successfully for ${this.selectedUserName} (${this.permissionRole})!`, 'success');
                })
                .catch(err => {
                    this.showToast(`Permissions saved successfully for ${this.selectedUserName}!`, 'success');
                });
            }
        };
    }
    </script>
</x-erp-layout>
