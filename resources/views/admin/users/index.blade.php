<x-erp-layout>
    <x-slot:title>User Management</x-slot:title>
    <x-slot:headerTitle>User Directory</x-slot:headerTitle>

    <div class="space-y-6">
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Enterprise Users</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage credentials, permissions, system separation, and statuses.</p>
            </div>
            
            <a href="{{ route('admin.users.create') }}" class="btn-ripple flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/95 transition-colors shadow-md shadow-primary/10 tracking-wide uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create User
            </a>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in-up">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Employee &amp; Name</th>
                            <th class="px-6 py-4">Associated System</th>
                            <th class="px-6 py-4">Role Assignment</th>
                            <th class="px-6 py-4">Account Status</th>
                            <th class="px-6 py-4">Registered Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                        @forelse($users as $user)
                            <tr class="table-row-hover hover:bg-slate-50/50 transition" x-data="{ openView: false }">
                                <!-- Employee & Name -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-150 text-slate-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ $user->employee_code ?? 'EMP' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium mt-0.5">{{ $user->email }}</div>
                                            @if($user->phone)
                                                <div class="text-[10px] text-slate-500 font-medium">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Associated System Badge -->
                                <td class="px-6 py-4">
                                    @if($user->system)
                                        @php
                                            $systemColor = $user->system->code === 'IN' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-blue-50 text-blue-700 border-blue-100';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $systemColor }}">
                                            {{ $user->system->code }} - {{ $user->system->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-450 italic">Global Admin</span>
                                    @endif
                                </td>

                                <!-- Role -->
                                <td class="px-6 py-4">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 mr-1">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = $user->status === 'active' ? 'badge-success' : ($user->status === 'inactive' ? 'badge-warning' : 'badge-danger');
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>

                                <!-- Registered Date -->
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ $user->created_at->format('d M, Y') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- View -->
                                        <button @click="openView = true" title="View User Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline" onsubmit="return confirm('Change status for {{ addslashes($user->name) }}?');">
                                            @csrf
                                            @if($user->status === 'active')
                                                <button type="submit" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Suspend Account">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                </button>
                                            @else
                                                <button type="submit" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Activate Account">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                </button>
                                            @endif
                                        </form>
                                    </div>

                                    {{-- View Modal --}}
                                    <div x-show="openView" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity text-left" style="display: none;">
                                        <div @click.away="openView = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5 whitespace-normal">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    </div>
                                                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">User Profile Details</h3>
                                                </div>
                                                <button @click="openView = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                                            </div>

                                            <div class="space-y-4">
                                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                                                    <div>
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Employee / Name</span>
                                                        <span class="text-base font-extrabold text-slate-900">{{ $user->name }}</span>
                                                        <span class="text-xs text-slate-500 block mt-0.5">{{ $user->email }}</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Employee Code</span>
                                                        <span class="px-2 py-1 rounded bg-[#a38c29]/10 text-[#a38c29] font-mono font-bold text-xs inline-block mt-0.5">{{ $user->employee_code ?? 'N/A' }}</span>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">System Node</span>
                                                        <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $user->system ? $user->system->name : 'Global Admin' }}</span>
                                                    </div>
                                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Account Status</span>
                                                        <span class="text-xs font-bold uppercase mt-0.5 block {{ $user->status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">{{ ucfirst($user->status) }}</span>
                                                    </div>
                                                </div>

                                                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Assigned Roles</span>
                                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                                        @forelse($user->roles as $role)
                                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $role->name }}</span>
                                                        @empty
                                                            <span class="text-xs text-slate-400 italic">No roles assigned</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-3 flex justify-between items-center border-t border-slate-100">
                                                <button type="button" @click="openView = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md inline-flex items-center gap-1.5">
                                                    <span>Edit User Profile</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-450">
                                    No users found in this operating system node.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-150">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-erp-layout>
