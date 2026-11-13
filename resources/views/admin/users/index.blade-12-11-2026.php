<x-erp-layout>
    <x-slot:title>User Management</x-slot:title>
    <x-slot:headerTitle>User Directory</x-slot:headerTitle>

    <div class="space-y-6" x-data="userManagementApp()">



        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 cursor-pointer">✕</button>
            </div>
        @endif


        {{-- Under Construction Notice --}}
        <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-5 md:p-6 shadow-sm relative overflow-hidden backdrop-blur-sm">
            <div class="flex items-start md:items-center gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30 text-2xl md:text-3xl">
                    🚧
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                        <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                            Work In Progress
                        </span>
                    </div>
                    <h2 class="text-lg md:text-2xl font-black text-red-950 tracking-tight leading-snug">
                        We're working on this module. It is not yet ready for use and will be released shortly.
                    </h2>
                    <p class="text-xs md:text-sm font-medium text-red-800">
                        This module is currently being finalized. Please check back soon for full availability.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Enterprise Users</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage credentials, permissions, system separation, and statuses.</p>
            </div>
            
            <button type="button" @click="openAddModalFn()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add New User</span>
            </button>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Configured Users Directory</h3>
                <span class="px-2.5 py-1 rounded-full bg-slate-200/70 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest">
                    Total Users: {{ $users->total() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                        <tr class="erp-table-header border-b border-slate-700 text-left">
                            <th class="px-6 py-3.5 erp-table-header border-r border-slate-600 text-white">Employee &amp; Name</th>
                            <th class="px-6 py-3.5 erp-table-header border-r border-slate-600 text-white">Associated System</th>
                            <th class="px-6 py-3.5 erp-table-header border-r border-slate-600 text-white">Role Assignment</th>
                            <th class="px-6 py-3.5 erp-table-header border-r border-slate-600 text-white text-center">Account Status</th>
                            <th class="px-6 py-3.5 erp-table-header border-r border-slate-600 text-white">Registered Date</th>
                            <th class="px-6 py-3.5 erp-table-header text-white text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <!-- Employee & Name -->
                                <td class="px-6 py-4 border-b border-slate-100">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900">{{ $user->name }}</span>
                                            @if($user->employee_code)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold font-mono uppercase bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20">{{ $user->employee_code }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-[10px] text-slate-500 font-medium">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Associated System Badge -->
                                <td class="px-6 py-4 border-b border-slate-100">
                                    @if($user->system)
                                        @php
                                            $systemColor = $user->system->code === 'IN' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-blue-50 text-blue-700 border-blue-100';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-[10px] font-bold uppercase border {{ $systemColor }}">
                                            {{ $user->system->code }} - {{ $user->system->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic font-semibold text-[11px]">Global Admin</span>
                                    @endif
                                </td>

                                <!-- Role -->
                                <td class="px-6 py-4 border-b border-slate-100">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic text-[11px]">No Role</span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 border-b border-slate-100 text-center">
                                    @if($user->status === 'active')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-[10px] uppercase">Active</span>
                                    @elseif($user->status === 'inactive')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-extrabold text-[10px] uppercase">Inactive</span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-extrabold text-[10px] uppercase">Suspended</span>
                                    @endif
                                </td>

                                <!-- Registered Date -->
                                <td class="px-6 py-4 border-b border-slate-100 text-slate-400 font-medium">
                                    {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 border-b border-slate-100 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- View -->
                                        <button type="button" @click="showUser(@js($user), '{{ route('admin.users.update', $user->id) }}', '{{ route('admin.users.toggle-status', $user->id) }}')" title="View User Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit -->
                                        <button type="button" @click="editUserFn(@js($user), '{{ route('admin.users.update', $user->id) }}')" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="Edit User">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>

                                        <!-- Toggle Status Trigger -->
                                        <button type="button" @click="toggleStatusFn(@js($user), '{{ route('admin.users.toggle-status', $user->id) }}')"
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer"
                                                title="{{ $user->status === 'active' ? 'Suspend / Deactivate Account' : 'Activate Account' }}">
                                            @if($user->status === 'active')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-semibold italic">
                                    No users found in this operating system node.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- ========================================================================= --}}
        {{-- ADD USER MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="closeAddModalFn()">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">USER DIRECTORY</p>
                            <h2 class="text-lg font-extrabold text-white">Register New Enterprise User</h2>
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
                                       :class="addErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                       placeholder="e.g. Rajesh Kumar"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.name">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.name"></p>
                                </template>
                                @error('name')
                                    <p x-show="!addErrors.name" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" x-model="addForm.email"
                                       @input="if (addErrors.email) delete addErrors.email"
                                       :class="addErrors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                       placeholder="user@company.com"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.email">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.email"></p>
                                </template>
                                @error('email')
                                    <p x-show="!addErrors.email" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone & Employee Code -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="addForm.phone"
                                       @input="if (addErrors.phone) delete addErrors.phone"
                                       :class="addErrors.phone ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                       placeholder="+91 99999 99999"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.phone">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.phone"></p>
                                </template>
                                @error('phone')
                                    <p x-show="!addErrors.phone" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Employee Code</label>
                                <input type="text" name="employee_code" x-model="addForm.employee_code"
                                       @input="if (addErrors.employee_code) delete addErrors.employee_code"
                                       :class="addErrors.employee_code ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('employee_code') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                       placeholder="e.g. EMP-101"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold font-mono">
                                <template x-if="addErrors.employee_code">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.employee_code"></p>
                                </template>
                                @error('employee_code')
                                    <p x-show="!addErrors.employee_code" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Operating System & Role -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Operating System <span class="text-rose-500">*</span></label>
                                <select name="system_id" x-model="addForm.system_id"
                                        @change="if (addErrors.system_id) delete addErrors.system_id"
                                        :class="addErrors.system_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('system_id') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
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
                                @error('system_id')
                                    <p x-show="!addErrors.system_id" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role Assignment <span class="text-rose-500">*</span></label>
                                <select name="role" x-model="addForm.role"
                                        @change="if (addErrors.role) delete addErrors.role"
                                        :class="addErrors.role ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('role') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
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
                                @error('role')
                                    <p x-show="!addErrors.role" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status & Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="addForm.status"
                                        @change="if (addErrors.status) delete addErrors.status"
                                        :class="addErrors.status ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('status') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <template x-if="addErrors.status">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.status"></p>
                                </template>
                                @error('status')
                                    <p x-show="!addErrors.status" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password <span class="text-rose-500">*</span> <span class="text-[9px] text-slate-400 font-normal lowercase">(min 6 chars)</span></label>
                                <input type="password" name="password" x-model="addForm.password"
                                       @input="if (addErrors.password) delete addErrors.password"
                                       :class="addErrors.password ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : '{{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50' }}'"
                                       placeholder="Minimum 6 characters"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="addErrors.password">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="addErrors.password"></p>
                                </template>
                                @error('password')
                                    <p x-show="!addErrors.password" class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="closeAddModalFn()" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE USER ACCOUNT</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- EDIT USER MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="openEditModal = false">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">USER DIRECTORY</p>
                            <h2 class="text-lg font-extrabold text-white">Edit User Account</h2>
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
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Employee Code</label>
                                <input type="text" name="employee_code" x-model="editForm.employee_code" placeholder="e.g. EMP-001"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold font-mono">
                            </div>
                        </div>

                        <!-- Operating System & Role -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Operating System <span class="text-rose-500">*</span></label>
                                <select name="system_id" x-model="editForm.system_id"
                                        @change="if (editErrors.system_id) delete editErrors.system_id"
                                        :class="editErrors.system_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select System...</option>
                                    @foreach($systems as $sys)
                                        <option value="{{ $sys->id }}">
                                            {{ $sys->name }} ({{ $sys->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <template x-if="editErrors.system_id">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.system_id"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Role Assignment <span class="text-rose-500">*</span></label>
                                <select name="role" x-model="editForm.role"
                                        @change="if (editErrors.role) delete editErrors.role"
                                        :class="editErrors.role ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="">Select Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <template x-if="editErrors.role">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.role"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Status & Password (optional on edit) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                                <select name="status" x-model="editForm.status"
                                        @change="if (editErrors.status) delete editErrors.status"
                                        :class="editErrors.status ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                        class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 cursor-pointer focus:outline-none transition-all font-bold">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <template x-if="editErrors.status">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.status"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">New Password <span class="text-slate-400 font-normal normal-case">(Leave blank to keep current)</span></label>
                                <input type="password" name="password" x-model="editForm.password"
                                       @input="if (editErrors.password) delete editErrors.password"
                                       :class="editErrors.password ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                       placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-bold">
                                <template x-if="editErrors.password">
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1" x-text="editErrors.password"></p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE USER ACCOUNT</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- VIEW USER PROFILE MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0 ring-0 outline-none" @click.away="openViewModal = false">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">USER PROFILE</p>
                            <h2 class="text-lg font-extrabold text-white">User Profile Details</h2>
                        </div>
                        <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Employee / Name</span>
                            <span class="text-sm font-extrabold text-slate-900 block mt-0.5" x-text="viewUser.name"></span>
                            <span class="text-xs text-slate-500 block mt-0.5 font-semibold" x-text="viewUser.email"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Employee Code</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/25" x-text="viewUser.employee_code"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">System Node</span>
                            <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="viewUser.system_name"></span>
                        </div>
                        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Account Status</span>
                            <span class="text-xs font-bold uppercase mt-0.5 block" :class="viewUser.status === 'active' ? 'text-emerald-600' : (viewUser.status === 'inactive' ? 'text-amber-600' : 'text-rose-600')" x-text="viewUser.status"></span>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                        <span class="text-xs font-bold text-slate-800 mt-1 block" x-text="viewUser.phone"></span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Assigned Roles</span>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <template x-for="role in viewUser.roles" :key="role">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" x-text="role"></span>
                            </template>
                            <template x-if="!viewUser.roles || viewUser.roles.length === 0">
                                <span class="text-xs text-slate-400 italic font-semibold">No roles assigned</span>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="openViewModal = false" class="px-4 py-2 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">CLOSE</button>
                        <button type="button" @click="triggerStatusFromView()"
                                class="px-4 py-2 border text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer"
                                :class="viewUser.status === 'active' ? 'border-rose-200 hover:bg-rose-50 text-rose-600' : 'border-emerald-200 hover:bg-emerald-50 text-emerald-600'"
                                x-text="viewUser.status === 'active' ? 'SUSPEND' : 'ACTIVATE'">
                        </button>
                    </div>
                    <button type="button" @click="triggerEditFromView()" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5 cursor-pointer">
                        <span>EDIT PROFILE</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- CONFIRM STATUS MODAL --}}
        {{-- ========================================================================= --}}
        <div x-show="openStatusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs text-left" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up border-0 ring-0 outline-none" @click.away="openStatusModal = false">
                <div class="p-6 text-center space-y-4">
                    <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-200 text-[#a38c29] flex items-center justify-center mx-auto text-lg">
                        ⚠️
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Confirm Status Change</h3>
                        <p class="text-xs text-slate-500">
                            Are you sure you want to change account status for <strong class="text-slate-800" x-text="statusForm.name"></strong> to <strong class="uppercase text-[#a38c29]" x-text="statusForm.new_status"></strong>?
                        </p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="openStatusModal = false" class="px-4 py-2 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">CANCEL</button>
                    <form method="POST" :action="statusForm.action" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer">
                            CONFIRM
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
    function userManagementApp() {
        return {
            openAddModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
            openEditModal: {{ $errors->any() && old('_method') ? 'true' : 'false' }},
            openViewModal: false,
            openStatusModal: false,

            rawSelectedUser: null,

            addForm: {
                name: @js(old('name', '')),
                email: @js(old('email', '')),
                phone: @js(old('phone', '')),
                employee_code: @js(old('employee_code', '')),
                system_id: @js(old('system_id', $systems->first()?->id ?? '')),
                role: @js(old('role', '')),
                status: @js(old('status', 'active')),
                password: ''
            },
            addErrors: {},
            editErrors: {},

            openAddModalFn() {
                this.addErrors = {};
                this.openAddModal = true;
            },

            closeAddModalFn() {
                this.addErrors = {};
                this.openAddModal = false;
            },

            validateAddUser(e) {
                this.addErrors = {};
                let hasError = false;
                let firstErrorKey = null;

                if (!this.addForm.name || !this.addForm.name.trim()) {
                    this.addErrors.name = 'Please enter full name.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'name';
                } else if (this.addForm.name.trim().length < 2) {
                    this.addErrors.name = 'Full name must be at least 2 characters.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'name';
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.addForm.email || !this.addForm.email.trim()) {
                    this.addErrors.email = 'Please enter email address.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'email';
                } else if (!emailRegex.test(this.addForm.email.trim())) {
                    this.addErrors.email = 'Please enter a valid email address.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'email';
                }

                if (!this.addForm.system_id) {
                    this.addErrors.system_id = 'Please select an operating system.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'system_id';
                }

                if (!this.addForm.role) {
                    this.addErrors.role = 'Please select a role assignment.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'role';
                }

                if (!this.addForm.status) {
                    this.addErrors.status = 'Please select an account status.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'status';
                }

                if (!this.addForm.password) {
                    this.addErrors.password = 'Please enter a password.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'password';
                } else if (this.addForm.password.length < 6) {
                    this.addErrors.password = 'Password must be at least 6 characters.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'password';
                }

                if (hasError) {
                    e.preventDefault();
                    if (firstErrorKey) {
                        this.$nextTick(() => {
                            const el = document.querySelector(`[name="${firstErrorKey}"]`);
                            if (el) el.focus();
                        });
                    }
                    return false;
                }
            },

            validateEditUser(e) {
                this.editErrors = {};
                let hasError = false;
                let firstErrorKey = null;

                if (!this.editForm.name || !this.editForm.name.trim()) {
                    this.editErrors.name = 'Please enter full name.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'name';
                } else if (this.editForm.name.trim().length < 2) {
                    this.editErrors.name = 'Full name must be at least 2 characters.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'name';
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.editForm.email || !this.editForm.email.trim()) {
                    this.editErrors.email = 'Please enter email address.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'email';
                } else if (!emailRegex.test(this.editForm.email.trim())) {
                    this.editErrors.email = 'Please enter a valid email address.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'email';
                }

                if (!this.editForm.system_id) {
                    this.editErrors.system_id = 'Please select an operating system.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'system_id';
                }

                if (!this.editForm.role) {
                    this.editErrors.role = 'Please select a role assignment.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'role';
                }

                if (!this.editForm.status) {
                    this.editErrors.status = 'Please select an account status.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'status';
                }

                if (this.editForm.password && this.editForm.password.length < 6) {
                    this.editErrors.password = 'Password must be at least 6 characters.';
                    hasError = true;
                    if (!firstErrorKey) firstErrorKey = 'password';
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }
            },

            viewUser: {
                id: null,
                name: '',
                email: '',
                phone: '',
                employee_code: '',
                system_name: '',
                roles: [],
                status: '',
                update_url: '',
                status_url: ''
            },

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

            statusForm: {
                id: null,
                name: '',
                current_status: '',
                new_status: '',
                action: ''
            },

            showUser(user, updateUrl, statusUrl) {
                this.rawSelectedUser = user;
                this.viewUser = {
                    id: user.id,
                    name: user.name || '',
                    email: user.email || '',
                    phone: user.phone || 'N/A',
                    employee_code: user.employee_code || 'N/A',
                    system_name: user.system ? (user.system.name + ' (' + user.system.code + ')') : 'Global Admin',
                    roles: (user.roles || []).map(r => r.name),
                    status: user.status || 'active',
                    update_url: updateUrl,
                    status_url: statusUrl
                };
                this.openViewModal = true;
            },

            editUserFn(user, updateUrl) {
                this.openViewModal = false;
                this.editErrors = {};
                const userRole = (user.roles && user.roles.length > 0) ? user.roles[0].name : '';
                this.editForm = {
                    id: user.id,
                    action: updateUrl,
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

            toggleStatusFn(user, actionUrl) {
                this.openViewModal = false;
                this.statusForm = {
                    id: user.id,
                    name: user.name || '',
                    current_status: user.status || 'active',
                    new_status: user.status === 'active' ? 'inactive' : 'active',
                    action: actionUrl
                };
                this.openStatusModal = true;
            },

            triggerEditFromView() {
                if (this.rawSelectedUser) {
                    this.editUserFn(this.rawSelectedUser, this.viewUser.update_url);
                }
            },

            triggerStatusFromView() {
                if (this.rawSelectedUser) {
                    this.toggleStatusFn(this.rawSelectedUser, this.viewUser.status_url);
                }
            }
        };
    }
    </script>
</x-erp-layout>
