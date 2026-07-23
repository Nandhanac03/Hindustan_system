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
                            <tr class="table-row-hover hover:bg-slate-50/50 transition" x-data="{ openView: false, showConfirmStatus: false }">
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
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit User">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        <!-- Toggle Status Trigger -->
                                        <button type="button" @click="showConfirmStatus = true"
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm"
                                                title="{{ $user->status === 'active' ? 'Suspend Account' : 'Activate Account' }}">
                                            @if($user->status === 'active')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </div>

                                     {{-- View Modal --}}
                                     <div x-show="openView" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left whitespace-normal" style="display: none;" x-transition.opacity>
                                         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openView = false">
                                              {{-- Header --}}
                                              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                                                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                                                  <div class="relative z-10 flex items-center justify-between gap-4">
                                                      <div>
                                                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">User Profile</span>
                                                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">User Profile Details</h2>
                                                      </div>
                                                      <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                                                  </div>
                                              </div>

                                              <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                                                  <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                                                      <div>
                                                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Employee / Name</span>
                                                          <span class="text-sm font-extrabold text-slate-900 block mt-0.5">{{ $user->name }}</span>
                                                          <span class="text-xs text-slate-500 block mt-0.5 font-semibold">{{ $user->email }}</span>
                                                      </div>
                                                      <div class="text-right">
                                                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Employee Code</span>
                                                          <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/25">{{ $user->employee_code ?? 'N/A' }}</span>
                                                      </div>
                                                  </div>

                                                  <div class="grid grid-cols-2 gap-3">
                                                      <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">System Node</span>
                                                          <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate">{{ $user->system ? $user->system->name : 'Global Admin' }}</span>
                                                      </div>
                                                      <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Account Status</span>
                                                          <span class="text-xs font-bold uppercase mt-0.5 block {{ $user->status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">{{ ucfirst($user->status) }}</span>
                                                      </div>
                                                  </div>

                                                  <div class="p-4 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Assigned Roles</span>
                                                      <div class="flex flex-wrap gap-1 mt-1.5">
                                                          @forelse($user->roles as $role)
                                                              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $role->name }}</span>
                                                          @empty
                                                              <span class="text-xs text-slate-455 italic font-semibold">No roles assigned</span>
                                                          @endforelse
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                                                  <div class="flex items-center gap-2">
                                                      <button type="button" @click="openView = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
                                                      <button type="button" @click="openView = false; showConfirmStatus = true"
                                                              class="px-4 py-2 border {{ $user->status === 'active' ? 'border-rose-200 hover:bg-rose-50 text-rose-600' : 'border-emerald-250 hover:bg-emerald-50 text-emerald-600' }} text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                                          {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                                      </button>
                                                  </div>
                                                  <a href="{{ route('admin.users.edit', $user->id) }}" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5">
                                                      <span>Edit Profile</span>
                                                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                  </a>
                                              </div>
                                         </div>
                                     </div>

                                     {{-- Confirm Status Modal --}}
                                     <div x-show="showConfirmStatus" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left" style="display: none;" x-transition.opacity>
                                         <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="showConfirmStatus = false">
                                             <div class="p-6 text-center space-y-4">
                                                 <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-200 text-[#a38c29] flex items-center justify-center mx-auto text-lg">
                                                     ⚠️
                                                 </div>
                                                 <div class="space-y-1">
                                                     <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Confirm Status Change</h3>
                                                     <p class="text-xs text-slate-500">Are you sure you want to change the status for <strong class="text-slate-800">{{ $user->name }}</strong>?</p>
                                                 </div>
                                             </div>
                                             <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                                                 <button type="button" @click="showConfirmStatus = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                                                 <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                                     @csrf
                                                     <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                                                         Confirm
                                                     </button>
                                                 </form>
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
