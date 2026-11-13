<x-erp-layout>
    <style>
        /* Bulletproof Layout Styles (Zero dependence on missing Tailwind utility classes) */
        .roles-layout-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }
        @media (min-width: 1024px) {
            .roles-layout-container {
                flex-direction: row !important;
                align-items: flex-start !important;
            }
        }

        .roles-sidebar-col {
            width: 100%;
        }
        @media (min-width: 1024px) {
            .roles-sidebar-col {
                width: 270px !important;
                flex-shrink: 0 !important;
            }
        }

        .roles-main-panel-col {
            width: 100%;
            min-width: 0;
            flex: 1 1 0% !important;
        }

        .role-cards-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.875rem;
            width: 100%;
        }
        @media (min-width: 640px) {
            .role-cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 900px) {
            .role-cards-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (min-width: 1200px) {
            .role-cards-grid {
                grid-template-columns: repeat({{ max(1, min(count($roleCards), 6)) }}, minmax(0, 1fr));
            }
        }

        /* Checkbox custom style matching Tabasco ERP theme */
        .matrix-checkbox {
            width: 1.15rem;
            height: 1.15rem;
            accent-color: #a38c29;
            cursor: pointer;
            border-radius: 0.25rem;
        }
    </style>

    <div class="space-y-6" x-data="roleManagementApp()">

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
             style="display: none; position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                <path x-show="toast.type !== 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-text="toast.message"></span>
            <button type="button" @click="toast.show = false" class="ml-2 opacity-80 hover:opacity-100 cursor-pointer">✕</button>
        </div>

        {{-- PAGE HEADER (Matching Users & Modules ERP Style) --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#a38c29] text-white flex items-center justify-center shrink-0 shadow-md shadow-[#a38c29]/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">User Roles</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Manage roles and permissions for Hindustan ERP. Assign roles to users to control access to different modules and features.</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>View Users</span>
                </a>

                <button type="button" @click="openCreateRoleModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/25 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add New Role</span>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TOP ROW: ROLE SUMMARY CV CARDS (Exact Cheque Realization / ERP CV Style)  --}}
        {{-- ========================================================================= --}}
        <div class="role-cards-grid">
            @foreach($roleCards as $r)
                @php
                    $roleNameLower = strtolower($r['name']);
                    [$iconBgHex, $softBgHex, $softBorderHex] = match(true) {
                        str_contains($roleNameLower, 'super admin') => ['#9333ea', '#faf5ff', '#e9d5ff'],
                        $roleNameLower === 'admin' => ['#3b82f6', '#eff6ff', '#bfdbfe'],
                        str_contains($roleNameLower, 'sale') => ['#10b981', '#ecfdf5', '#a7f3d0'],
                        str_contains($roleNameLower, 'account') => ['#f59e0b', '#fffbeb', '#fde68a'],
                        str_contains($roleNameLower, 'main user') => ['#0d9488', '#f0fdfa', '#99f6e4'],
                        str_contains($roleNameLower, 'owner') => ['#a38c29', '#faf8ef', '#e5ddb8'],
                        default => ['#475569', '#f8fafc', '#cbd5e1'],
                    };
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 flex flex-col justify-between relative overflow-hidden group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-pointer h-full"
                     style="border-left: 6px solid {{ $iconBgHex }};"
                     :class="selectedRoleId === {{ $r['id'] }} ? 'ring-2 ring-[#a38c29]/35 border-[#a38c29] shadow-md' : 'hover:border-slate-300'"
                     @click="selectRoleById({{ $r['id'] }}); scrollToMatrix()">
                    {{-- Top Header: Soft Circle Icon + Uppercase Role Name + Count Pill --}}
                    <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center border transition-all duration-300 group-hover:shadow-md group-hover:scale-110"
                                 style="background-color: {{ $softBgHex }}; color: {{ $iconBgHex }}; border-color: {{ $softBorderHex }};">
                                @if(($r['theme']['icon'] ?? '') === 'user')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @elseif(($r['theme']['icon'] ?? '') === 'trending-up')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                @elseif(($r['theme']['icon'] ?? '') === 'calculator')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @elseif(($r['theme']['icon'] ?? '') === 'shield-check')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                @elseif(($r['theme']['icon'] ?? '') === 'crown')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                @elseif(($r['theme']['icon'] ?? '') === 'key')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                @endif
                            </div>
                            <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">{{ $r['name'] }}</span>
                        </div>
                        <span class="shrink-0 whitespace-nowrap text-[9px] font-bold px-2 py-0.5 rounded-md border uppercase tracking-wider shadow-sm"
                              style="background-color: {{ $softBgHex }}; color: {{ $iconBgHex }}; border-color: {{ $softBorderHex }};">
                            {{ $r['user_count'] }} {{ Str::plural('User', $r['user_count']) }}
                        </span>
                    </div>

                    {{-- Middle Value + Bottom Description (Exact CV Card Layout) --}}
                    <div class="relative z-10 mt-1">
                        <span class="text-2xl font-black font-mono tracking-tight block"
                              style="color: {{ $iconBgHex }};">
                            {{ $r['user_count'] }}
                        </span>
                        <p class="text-[9px] text-slate-400 mt-1.5 font-medium line-clamp-2">{{ $r['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ========================================================================= --}}
        {{-- MAIN SECTION: ROLE BASED ACCESS (LEFT) + PERMISSIONS MATRIX TABLE (RIGHT) --}}
        {{-- ========================================================================= --}}
        <div id="permissions-matrix-section" class="roles-layout-container">

            {{-- LEFT COLUMN: ROLE BASED ACCESS (EXACT IMAGE 1 STYLE) --}}
            <div class="roles-sidebar-col space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Role Based Access</h3>
                        <span class="px-2 py-0.5 rounded-full bg-slate-200/70 text-slate-600 text-[10px] font-extrabold uppercase tracking-wider">
                            {{ count($roleCards) }} Roles
                        </span>
                    </div>

                    {{-- Search Filter --}}
                    <div class="p-3 border-b border-slate-100 bg-slate-50/40">
                        <input type="text" x-model="roleSearchQuery" placeholder="Filter roles..."
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] transition">
                    </div>

                    {{-- Role Items Vertical List --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($roleCards as $r)
                            @php
                                $roleNameLower = strtolower($r['name']);
                                $iconBgHex = match(true) {
                                    str_contains($roleNameLower, 'super admin') => '#9333ea',
                                    $roleNameLower === 'admin' => '#3b82f6',
                                    str_contains($roleNameLower, 'sale') => '#10b981',
                                    str_contains($roleNameLower, 'account') => '#f59e0b',
                                    str_contains($roleNameLower, 'main user') => '#0d9488',
                                    str_contains($roleNameLower, 'owner') => '#a38c29',
                                    default => '#475569',
                                };
                            @endphp
                            <div x-show="roleMatchesQuery('{{ strtolower($r['name']) }}')"
                                 @click="selectRoleById({{ $r['id'] }})"
                                 class="p-3.5 transition cursor-pointer flex items-center justify-between gap-3 group select-none"
                                 :class="selectedRoleId === {{ $r['id'] }} ? 'bg-[#a38c29]/10 border-l-4 border-l-[#a38c29] font-bold' : 'hover:bg-slate-50/80 border-l-4 border-l-transparent'">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white shrink-0 shadow-2xs {{ $r['theme']['icon_bg'] ?? 'bg-slate-600' }}"
                                         style="background-color: {{ $iconBgHex }};">
                                        @if(($r['theme']['icon'] ?? '') === 'user')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        @elseif(($r['theme']['icon'] ?? '') === 'trending-up')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        @elseif(($r['theme']['icon'] ?? '') === 'calculator')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        @elseif(($r['theme']['icon'] ?? '') === 'shield-check')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        @elseif(($r['theme']['icon'] ?? '') === 'crown')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        @elseif(($r['theme']['icon'] ?? '') === 'key')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs text-slate-800 block truncate"
                                              :class="selectedRoleId === {{ $r['id'] }} ? 'font-black text-[#8a7522]' : 'font-semibold'">
                                            {{ $r['name'] }}{{ $r['name'] === 'Main User' ? ' (ERP Full Handling)' : '' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-1.5">
                                    @if(!$r['is_system'])
                                        <button type="button" @click.stop="deleteCustomRoleById({{ $r['id'] }}, '{{ addslashes($r['name']) }}')" title="Delete Custom Role"
                                                class="w-5 h-5 rounded hover:bg-rose-100 text-rose-500 flex items-center justify-center transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Add New Role Button --}}
                    <div class="p-3 bg-white border-t border-slate-100">
                        <button type="button" @click="openCreateRoleModal = true"
                                class="w-full py-2 px-3 border border-dashed border-slate-300 hover:border-[#a38c29] hover:bg-[#a38c29]/5 text-slate-600 hover:text-[#8a7522] rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Add New Role</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: PERMISSIONS MATRIX (FILLS ENTIRE REMAINING WIDTH) --}}
            <div class="roles-main-panel-col space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col w-full">

                    {{-- Role Header (Matching Ledger / Tabasco ERP Card Header) --}}
                    <div class="px-6 py-5 bg-white border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm"
                                 :class="activeThemeBg"
                                 :style="'background-color: ' + activeThemeColor">
                                <span class="text-lg" x-text="activeThemeIconEmoji"></span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-800" x-text="activeRoleName + ' Role'"></h2>
                                    <span class="px-2 py-0.5 rounded-md bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-[9px] font-bold uppercase tracking-wider">
                                        <span x-text="activePermsCount"></span> Active
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1" x-text="activeRoleDesc"></p>
                            </div>
                        </div>

                        {{-- Action Buttons: Save Permissions & Select All --}}
                        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                            <button type="button" @click="saveRolePermissions()" :disabled="saving"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] disabled:opacity-50 text-white rounded-xl text-[11px] font-bold uppercase tracking-wider transition shadow-sm cursor-pointer active:scale-95">
                                <template x-if="saving">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </template>
                                <template x-if="!saving">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <span x-text="saving ? 'Saving...' : 'Save Permissions'"></span>
                            </button>

                            <button type="button" @click="toggleSelectAllMatrix()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#a38c29]/40 bg-white hover:bg-[#a38c29]/5 text-[#8a7522] text-[11px] font-bold uppercase tracking-wider transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="allMatrixSelected ? 'Deselect All' : 'Select All'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- PERMISSIONS MATRIX TABLE (Tabasco ERP Table Format) --}}
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <th class="px-5 py-3.5 erp-table-header text-white min-w-[260px]">Module / Feature</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">View</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">Create</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">Edit</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">Delete</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">Excel</th>
                                    <th class="px-4 py-3.5 erp-table-header text-white text-center w-24">PDF</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($modulesCatalog as $mod)
                                    {{-- Module Group Header Row --}}
                                    <tr class="bg-[#faf8ef] border-t border-b border-slate-200/80 font-bold select-none">
                                        <td colspan="7" class="px-5 py-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 text-slate-900">
                                                    <span class="text-sm">
                                                        @if(str_contains(strtolower($mod['name']), 'dash')) 🏠
                                                        @elseif(str_contains(strtolower($mod['name']), 'sale')) 📈
                                                        @elseif(str_contains(strtolower($mod['name']), 'project')) 🏢
                                                        @elseif(str_contains(strtolower($mod['name']), 'cust')) 👥
                                                        @elseif(str_contains(strtolower($mod['name']), 'account')) 💳
                                                        @elseif(str_contains(strtolower($mod['name']), 'bank')) 🏦
                                                        @elseif(str_contains(strtolower($mod['name']), 'petty')) 💰
                                                        @else 📁
                                                        @endif
                                                    </span>
                                                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800">{{ $mod['name'] }}</span>
                                                </div>
                                                <button type="button" @click="toggleModuleGroup('{{ $mod['code'] }}')"
                                                        class="px-2.5 py-1 rounded-lg bg-white border border-[#a38c29]/30 text-[#8a7522] hover:bg-[#a38c29] hover:text-white text-[10px] font-bold uppercase tracking-wider transition cursor-pointer">
                                                    Toggle All
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Sub-module / Feature Rows --}}
                                    @foreach($mod['sub_modules'] as $sub)
                                        @php
                                            $supportedActions = $sub['actions'] ?? ['view', 'create', 'edit', 'delete', 'excel', 'pdf', 'export'];
                                            $hasExport = in_array('excel', $supportedActions) || in_array('pdf', $supportedActions) || in_array('export', $supportedActions);
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            {{-- Feature Name & Description --}}
                                            <td class="px-5 py-3.5">
                                                <div class="min-w-0 pl-1">
                                                    <span class="block text-xs font-bold text-slate-800">{{ $sub['name'] }}</span>
                                                    @if(!empty($sub['description']))
                                                        <span class="block text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $sub['description'] }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Actions: View, Create, Edit, Delete, Excel, PDF --}}
                                            @foreach(['view', 'create', 'edit', 'delete', 'excel', 'pdf'] as $action)
                                                <td class="px-4 py-3.5 text-center w-24">
                                                    <label class="inline-flex items-center justify-center cursor-pointer select-none">
                                                        <input type="checkbox"
                                                               :checked="isPermChecked('{{ $sub['code'] }}', '{{ $action }}')"
                                                               @change="togglePerm('{{ $sub['code'] }}', '{{ $action }}')"
                                                               class="matrix-checkbox">
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer Summary & Save Button --}}
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 font-bold text-[11px]">
                                <span x-text="activePermsCount"></span> Active Permissions
                            </span>
                            <span>for role <strong class="text-slate-800" x-text="activeRoleName"></strong></span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <button type="button" @click="saveRolePermissions()" :disabled="saving"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] disabled:opacity-50 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md shadow-[#a38c29]/25 cursor-pointer active:scale-95">
                                <template x-if="saving">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </template>
                                <template x-if="!saving">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <span x-text="saving ? 'Saving...' : 'Save Permissions'"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- POPUP MODAL: ADD NEW ROLE                                                 --}}
        {{-- ========================================================================= --}}
        <div x-show="openCreateRoleModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="openCreateRoleModal = false">
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-blue-400 text-[10px] font-bold uppercase tracking-widest mb-1">ROLE MANAGEMENT</p>
                            <h2 class="text-base font-black text-white">Create New User Role</h2>
                        </div>
                        <button type="button" @click="openCreateRoleModal = false" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="submitNewRole()">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Role Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" x-model="newRoleName" placeholder="e.g., Audit Manager, Floor Executive" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Description
                            </label>
                            <textarea x-model="newRoleDescription" rows="2" placeholder="Brief summary of responsibilities"
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition font-medium"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openCreateRoleModal = false" class="px-4 py-2 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase tracking-wider transition">CANCEL</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-md shadow-blue-600/20">CREATE ROLE</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ========================================================================= --}}
    {{-- ALPINE.JS LOGIC & PERMISSION STATE MANAGEMENT                            --}}
    {{-- ========================================================================= --}}
    <script>
    function roleManagementApp() {
        const rawRoles = {!! json_encode($roleCards ?? []) !!};
        const rawModules = {!! json_encode($modulesCatalog ?? []) !!};

        const defaultRoleId = rawRoles.length > 0 ? rawRoles[0].id : 1;

        return {
            rolesList: rawRoles,
            modulesCatalog: rawModules,
            selectedRoleId: defaultRoleId,
            rolePermsMap: {}, // Maps role.id -> Set of permission strings
            roleSearchQuery: '',
            openCreateRoleModal: false,
            newRoleName: '',
            newRoleDescription: '',
            saving: false,

            toast: {
                show: false,
                message: '',
                type: 'success'
            },

            init() {
                const validMatrixKeys = new Set();
                const matrixActions = ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];
                this.modulesCatalog.forEach(m => {
                    (m.sub_modules || []).forEach(sub => {
                        matrixActions.forEach(act => validMatrixKeys.add(this.permKey(sub.code, act)));
                    });
                });

                // Initialize permissions for all roles
                this.rolesList.forEach(r => {
                    let set = new Set();
                    const matched = (r.permissions || []).filter(p => validMatrixKeys.has(p));
                    if (matched.length > 0) {
                        matched.forEach(p => set.add(p));
                    } else {
                        set = this.buildDefaultPermsForRole(r.name);
                    }
                    this.rolePermsMap[r.id] = set;
                });
            },

            get selectedRole() {
                return this.rolesList.find(r => r.id === this.selectedRoleId) || (this.rolesList[0] || null);
            },

            get activeRoleName() {
                return this.selectedRole ? this.selectedRole.name : 'Role';
            },

            get activeRoleDesc() {
                return this.selectedRole ? this.selectedRole.description : '';
            },

            get activeUserCount() {
                return this.selectedRole ? this.selectedRole.user_count : 0;
            },

            get activeThemeBg() {
                if (!this.selectedRole || !this.selectedRole.theme) return 'bg-blue-600';
                return this.selectedRole.theme.icon_bg || 'bg-blue-600';
            },

            get activeThemeColor() {
                const name = this.activeRoleName.toLowerCase();
                if (name.includes('super admin')) return '#9333ea';
                if (name === 'admin') return '#3b82f6';
                if (name.includes('sale')) return '#10b981';
                if (name.includes('account')) return '#f59e0b';
                if (name.includes('main user')) return '#0d9488';
                if (name.includes('owner')) return '#a38c29';
                return '#2563eb';
            },

            get activeThemeIconEmoji() {
                const name = this.activeRoleName.toLowerCase();
                if (name === 'admin') return '👤';
                if (name.includes('sale')) return '📈';
                if (name.includes('account')) return '🧮';
                if (name.includes('super admin')) return '🛡️';
                if (name.includes('main user')) return '👑';
                if (name.includes('owner')) return '🔑';
                return '🛡️';
            },

            get activePermsCount() {
                const set = this.rolePermsMap[this.selectedRoleId];
                return set ? set.size : 0;
            },

            roleMatchesQuery(name) {
                if (!this.roleSearchQuery || !this.roleSearchQuery.trim()) return true;
                return name.includes(this.roleSearchQuery.toLowerCase().trim());
            },

            selectRoleById(id) {
                this.selectedRoleId = id;
            },

            scrollToMatrix() {
                const el = document.getElementById('permissions-matrix-section');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            },

            permKey(subCode, action) {
                return `${subCode}.${action}`;
            },

            isPermChecked(subCode, action) {
                const set = this.rolePermsMap[this.selectedRoleId];
                if (!set) return false;
                const key = this.permKey(subCode, action);
                return set.has(key) || set.has(subCode);
            },

            togglePerm(subCode, action) {
                let set = this.rolePermsMap[this.selectedRoleId];
                if (!set) {
                    set = new Set();
                    this.rolePermsMap[this.selectedRoleId] = set;
                }

                const key = this.permKey(subCode, action);
                if (set.has(key)) {
                    set.delete(key);
                } else {
                    set.add(key);
                }
                this.rolePermsMap[this.selectedRoleId] = new Set(set);
            },

            togglePermExport(subCode) {
                let set = this.rolePermsMap[this.selectedRoleId];
                if (!set) {
                    set = new Set();
                    this.rolePermsMap[this.selectedRoleId] = set;
                }

                const excelKey = this.permKey(subCode, 'excel');
                const pdfKey = this.permKey(subCode, 'pdf');
                const exportKey = this.permKey(subCode, 'export');

                const isChecked = set.has(excelKey) || set.has(pdfKey) || set.has(exportKey);

                if (isChecked) {
                    set.delete(excelKey);
                    set.delete(pdfKey);
                    set.delete(exportKey);
                } else {
                    set.add(excelKey);
                    set.add(pdfKey);
                    set.add(exportKey);
                }
                this.rolePermsMap[this.selectedRoleId] = new Set(set);
            },

            toggleModuleGroup(modCode) {
                const mod = this.modulesCatalog.find(m => m.code === modCode);
                if (!mod) return;

                let set = this.rolePermsMap[this.selectedRoleId];
                if (!set) {
                    set = new Set();
                    this.rolePermsMap[this.selectedRoleId] = set;
                }

                const matrixActions = ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];
                let allChecked = true;
                (mod.sub_modules || []).forEach(sub => {
                    matrixActions.forEach(act => {
                        if (!set.has(this.permKey(sub.code, act))) {
                            allChecked = false;
                        }
                    });
                });

                (mod.sub_modules || []).forEach(sub => {
                    matrixActions.forEach(act => {
                        const key = this.permKey(sub.code, act);
                        if (allChecked) {
                            set.delete(key);
                        } else {
                            set.add(key);
                        }
                    });
                });

                this.rolePermsMap[this.selectedRoleId] = new Set(set);
            },

            get allMatrixSelected() {
                const set = this.rolePermsMap[this.selectedRoleId] || new Set();
                const matrixActions = ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];
                let allCount = 0;
                let checkedCount = 0;

                this.modulesCatalog.forEach(m => {
                    (m.sub_modules || []).forEach(sub => {
                        matrixActions.forEach(act => {
                            allCount++;
                            if (set.has(this.permKey(sub.code, act))) {
                                checkedCount++;
                            }
                        });
                    });
                });

                return allCount > 0 && checkedCount === allCount;
            },

            toggleSelectAllMatrix() {
                const shouldSelect = !this.allMatrixSelected;
                const matrixActions = ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];
                const set = new Set();

                if (shouldSelect) {
                    this.modulesCatalog.forEach(m => {
                        (m.sub_modules || []).forEach(sub => {
                            matrixActions.forEach(act => {
                                set.add(this.permKey(sub.code, act));
                            });
                        });
                    });
                }

                this.rolePermsMap[this.selectedRoleId] = set;
            },

            buildDefaultPermsForRole(roleName) {
                const set = new Set();
                const name = (roleName || '').toLowerCase();
                const matrixActions = ['view', 'create', 'edit', 'delete', 'excel', 'pdf'];

                this.modulesCatalog.forEach(m => {
                    const modCode = (m.code || '').toLowerCase();
                    (m.sub_modules || []).forEach(sub => {
                        const subCode = (sub.code || '').toLowerCase();
                        const actions = matrixActions;

                        if (name.includes('owner') || name.includes('main user')) {
                            actions.forEach(a => set.add(this.permKey(sub.code, a)));
                        } else if (name.includes('super admin')) {
                            actions.forEach(a => {
                                if (a !== 'delete' || !subCode.includes('user')) {
                                    set.add(this.permKey(sub.code, a));
                                }
                            });
                        } else if (name === 'admin') {
                            actions.forEach(a => {
                                if (['view', 'create', 'edit', 'excel', 'pdf', 'export'].includes(a)) {
                                    set.add(this.permKey(sub.code, a));
                                }
                                if (a === 'delete' && (subCode.includes('dashboard') || subCode.includes('booking'))) {
                                    set.add(this.permKey(sub.code, a));
                                }
                            });
                        } else if (name.includes('sales')) {
                            if (modCode.includes('sale') || modCode.includes('cust') || modCode.includes('project') || modCode.includes('broker') || modCode.includes('dash')) {
                                actions.forEach(a => {
                                    if (['view', 'create', 'edit', 'excel', 'pdf', 'export'].includes(a)) {
                                        set.add(this.permKey(sub.code, a));
                                    }
                                });
                            }
                        } else if (name.includes('account')) {
                            if (modCode.includes('account') || modCode.includes('bank') || modCode.includes('treasury') || modCode.includes('petty') || modCode.includes('profit') || modCode.includes('ledger') || modCode.includes('dash')) {
                                actions.forEach(a => {
                                    if (['view', 'create', 'edit', 'excel', 'pdf', 'export'].includes(a)) {
                                        set.add(this.permKey(sub.code, a));
                                    }
                                });
                            }
                        }
                    });
                });

                return set;
            },

            showToast(message, type = 'success') {
                if (type === 'success' && typeof window.showSuccessModal === 'function') {
                    window.showSuccessModal(
                        'Permissions Saved!',
                        'Role Updated',
                        `${this.activeRoleName} permissions updated successfully.`,
                        message
                    );
                    return;
                }

                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            },

            saveRolePermissions() {
                if (!this.selectedRole) return;
                this.saving = true;

                const set = this.rolePermsMap[this.selectedRoleId] || new Set();
                const permsArray = Array.from(set);

                fetch(`{{ url('admin/roles') }}/${this.selectedRoleId}/permissions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        permissions: permsArray
                    })
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || data.success === false) {
                        throw new Error(data.message || 'Failed to save permissions.');
                    }
                    return data;
                })
                .then(data => {
                    this.saving = false;
                    const savedPerms = Array.isArray(data.permissions) ? data.permissions : permsArray;
                    this.selectedRole.permissions = savedPerms;
                    this.rolePermsMap[this.selectedRoleId] = new Set(savedPerms);
                    this.showToast(data.message || `Permissions saved successfully for ${this.activeRoleName}!`, 'success');
                })
                .catch(err => {
                    this.saving = false;
                    this.showToast(err.message || `Failed to save permissions for ${this.activeRoleName}.`, 'error');
                });
            },

            submitNewRole() {
                if (!this.newRoleName || !this.newRoleName.trim()) return;

                fetch(`{{ route('admin.roles.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newRoleName.trim(),
                        permissions: []
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.role) {
                        window.location.reload();
                    } else {
                        this.showToast(data.message || 'Could not create role.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Failed to create role.', 'error');
                });
            },

            deleteCustomRoleById(id, name) {
                if (!confirm(`Are you sure you want to delete role "${name}"?`)) return;

                fetch(`/admin/roles/${id}`, {
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
                        window.location.reload();
                    } else {
                        this.showToast(data.message || 'Cannot delete role.', 'error');
                    }
                })
                .catch(err => {
                    this.showToast('Failed to delete role.', 'error');
                });
            }
        };
    }
    </script>
</x-erp-layout>
