<x-erp-layout title="Payment Mode Master" headerTitle="Payment Mode Master Directory">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="paymentModeApp()">

        <!-- Breadcrumb & Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Masters</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Payment Mode Master</span>
            </div>

            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span> Add Payment Mode</span>
            </button>
        </div>

        <!-- Alert Notifications -->

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
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total Payment Modes</span>
                    <div class="text-2xl font-mono font-black text-slate-900 mt-1">{{ $totalCount }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Configured Master Modes</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest block">Active Modes</span>
                    <div class="text-2xl font-mono font-black text-emerald-700 mt-1">{{ $activeCount }}</div>
                    <span class="text-[10px] text-emerald-600/80 font-semibold block mt-0.5">Available for Customer Intake</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Inactive Modes</span>
                    <div class="text-2xl font-mono font-black text-slate-500 mt-1">{{ $inactiveCount }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Disabled Modes</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
        </div>

        <!-- Directory Filter Bar & Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Filters Header -->
            <div class="p-5 bg-slate-900 text-white border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-white">Payment Mode Master Register</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Manage customer transaction modes, reference requirements & bank validation</p>
                </div>

                <form method="GET" action="{{ route('payment-modes.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search mode name or code..."
                               class="pl-9 pr-4 py-2 bg-slate-800 border border-slate-700 hover:border-slate-600 rounded-xl text-xs font-semibold text-white placeholder-slate-400 focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs font-semibold text-slate-200 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>

                    <button type="submit" class="px-3 py-2 bg-[#a38c29] text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-[#8a7522] transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('payment-modes.index') }}" class="px-3 py-2 bg-slate-800 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-700 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Master Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                            <th class="px-5 py-3.5">SL NO</th>
                            <th class="px-5 py-3.5">MODE NAME</th>
                            <th class="px-5 py-3.5">SYSTEM CODE</th>
                            <th class="px-5 py-3.5">TXN REF # REQUIRED</th>
                            <th class="px-5 py-3.5">ISSUING BANK REQUIRED</th>
                            <th class="px-5 py-3.5 text-center">STATUS</th>
                            <th class="px-5 py-3.5 text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($paymentModes as $index => $pm)
                            <tr class="hover:bg-slate-50 transition-colors font-semibold text-slate-700">
                                <td class="px-5 py-4 font-mono font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-extrabold text-slate-900 text-sm">{{ $pm->name }}</div>
                                    @if($pm->description)
                                        <div class="text-[11px] text-slate-400 font-medium mt-0.5 leading-tight">{{ $pm->description }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-[#a38c29]">
                                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/20 text-[#a38c29] rounded-md text-[10px] font-black uppercase">
                                        {{ $pm->code }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($pm->requires_reference)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-black uppercase bg-blue-100 text-blue-800 border border-blue-200">
                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Yes (Required)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-500">
                                            Optional / No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($pm->requires_bank)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-black uppercase bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Yes (Required)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-500">
                                            Optional / No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <form action="{{ route('payment-modes.toggle-status', $pm->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Click to toggle status"
                                                class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border transition cursor-pointer {{ $pm->status === 'active' ? 'bg-emerald-100 text-emerald-800 border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-300 hover:bg-slate-200' }}">
                                            {{ $pm->status }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- View Modal Button -->
                                        <button type="button" @click="openViewModal(@js($pm))" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit Modal Button -->
                                        <button type="button" @click="openEditModal(@js($pm))" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Mode">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('payment-modes.destroy', $pm->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete payment mode {{ addslashes($pm->name) }}?');">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition" title="Delete Mode">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-slate-400 italic">
                                    No payment modes found matching criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── ADD PAYMENT MODE MODAL ── -->
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition>
            <div @click.away="showAddModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">PAYMENT MODES MASTER</p>
                            <h2 class="text-lg font-extrabold text-white">Add New Payment Mode</h2>
                        </div>
                        <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('payment-modes.store') }}" method="POST" class="flex flex-col">
                    <div class="p-6 space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mode Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="e.g. UPI / GooglePay, Demand Draft..."
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">System Code (Optional)</label>
                            <input type="text" name="code" placeholder="e.g. UPI, CHEQUE, DD (Auto-generated if empty)"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Description / Notes</label>
                            <textarea name="description" rows="2" placeholder="Enter mode details or instructions..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="requires_reference" value="1" class="w-4 h-4 text-[#a38c29] rounded border-slate-300 focus:ring-[#a38c29]">
                                <span class="text-xs font-bold text-slate-700">Requires Txn Reference # (Cheque No / UTR / Txn ID)</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="requires_bank" value="1" class="w-4 h-4 text-[#a38c29] rounded border-slate-300 focus:ring-[#a38c29]">
                                <span class="text-xs font-bold text-slate-700">Requires Issuing Bank Selection</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                            <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE PAYMENT MODE</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── EDIT PAYMENT MODE MODAL ── -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition>
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">PAYMENT MODES MASTER</p>
                            <h2 class="text-lg font-extrabold text-white">Edit Payment Mode</h2>
                        </div>
                        <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <form :action="editFormAction" method="POST" class="flex flex-col">
                    <div class="p-6 space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mode Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="editItem.name" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">System Code</label>
                            <input type="text" name="code" x-model="editItem.code" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Description / Notes</label>
                            <textarea name="description" x-model="editItem.description" rows="2"
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="requires_reference" value="1" :checked="editItem.requires_reference" class="w-4 h-4 text-[#a38c29] rounded border-slate-300 focus:ring-[#a38c29]">
                                <span class="text-xs font-bold text-slate-700">Requires Txn Reference #</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="requires_bank" value="1" :checked="editItem.requires_bank" class="w-4 h-4 text-[#a38c29] rounded border-slate-300 focus:ring-[#a38c29]">
                                <span class="text-xs font-bold text-slate-700">Requires Issuing Bank Selection</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                            <select name="status" x-model="editItem.status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE PAYMENT MODE</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── VIEW DETAILS MODAL ── -->
        <div x-show="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition>
            <div @click.away="showViewModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">PAYMENT MODES MASTER</p>
                            <h2 class="text-lg font-extrabold text-white">Payment Mode Details</h2>
                        </div>
                        <button type="button" @click="showViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-3.5 text-xs bg-white">
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">MODE NAME</span>
                        <span class="font-bold text-slate-900 text-sm" x-text="viewItem.name"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">SYSTEM CODE</span>
                        <span class="font-mono font-bold text-[#a38c29]" x-text="viewItem.code"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">TXN REF # REQUIRED</span>
                        <span class="font-bold text-slate-800" x-text="viewItem.requires_reference ? 'Required' : 'Optional / No'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ISSUING BANK REQUIRED</span>
                        <span class="font-bold text-slate-800" x-text="viewItem.requires_bank ? 'Required' : 'Optional / No'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                        <span class="font-bold uppercase" :class="viewItem.status === 'active' ? 'text-emerald-600' : 'text-slate-500'" x-text="viewItem.status"></span>
                    </div>
                    <div x-show="viewItem.description" class="pt-1">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px] block mb-1">DESCRIPTION / NOTES</span>
                        <p class="text-slate-700 font-medium bg-slate-50 p-3 rounded-xl border border-slate-200" x-text="viewItem.description"></p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="button" @click="showViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase rounded-xl transition shadow-sm cursor-pointer">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── ALPINE JS CONTROLLER ── -->
    <script>
        function paymentModeApp() {
            return {
                showAddModal: false,
                showEditModal: false,
                showViewModal: false,
                editFormAction: '',
                editItem: { id: '', name: '', code: '', description: '', requires_reference: false, requires_bank: false, status: 'active' },
                viewItem: { name: '', code: '', description: '', requires_reference: false, requires_bank: false, status: '' },

                openAddModal() {
                    this.showAddModal = true;
                },
                openEditModal(item) {
                    this.editItem = { ...item };
                    this.editFormAction = "{{ url('/payment-modes') }}/" + item.id + "/update";
                    this.showEditModal = true;
                },
                openViewModal(item) {
                    this.viewItem = { ...item };
                    this.showViewModal = true;
                }
            }
        }
    </script>
</x-erp-layout>
