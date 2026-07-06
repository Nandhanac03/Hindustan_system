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
                            <tr class="table-row-hover hover:bg-slate-50/50 transition">
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
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[10px]" title="Edit Details">
                                            Edit
                                        </a>

                                        <!-- Toggle Status -->
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                            @csrf
                                            @if($user->status === 'active')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg transition text-[10px]" title="Suspend Account">
                                                    Suspend
                                                </button>
                                            @else
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-lg transition text-[10px]" title="Activate Account">
                                                    Activate
                                                </button>
                                            @endif
                                        </form>
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
