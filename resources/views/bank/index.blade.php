<x-erp-layout title="Bank Master" headerTitle="Bank Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="bankApp()">

    {{-- Alert Messages --}}

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75">✕</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Pending EMI Alert --}}
    @if(isset($pendingEmisCount) && $pendingEmisCount > 0)
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-250 text-amber-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-amber-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>
                    Attention: You have <strong class="text-amber-900">{{ $pendingEmisCount }}</strong> pending/overdue Loan EMI repayments due this month totaling <strong class="text-amber-900">₹{{ number_format($pendingEmisAmount, 2) }}</strong>.
                </span>
            </div>
            <a href="{{ route('loans.index') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-wide transition shadow-sm">
                View Repayments &rarr;
            </a>
        </div>
    @endif

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Bank Management</h1>
            <p class="text-xs text-slate-500 mt-1">Configure company bank accounts, branches, and routing details.</p>
        </div>

        <div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Bank
            </button>
        </div>
    </div>

    {{-- Banks Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #banks-table thead th {
                border-color: #8a7522 !important;
            }
            #banks-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #banks-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="banks-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">SL NO</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">BANK NAME</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">IFSC CODE</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">STATUS</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="banks-tbody" class="divide-y divide-[#EAE3CD] text-center">
                    @forelse($banks as $index => $bank)
                        <tr class="hover:bg-[#ebe5d0] transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 shadow-sm border border-primary-800">
                                        {{ substr(trim($bank->bank_name), 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight">{{ $bank->bank_name }}</span>
                                        <span class="text-[9px] text-slate-500 font-medium">Corporate Account</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border font-mono text-slate-650 uppercase font-bold text-left">{{ $bank->ifsc_code }}</td>
                            <td class="px-4 py-3.5 border text-center">
                                <span class="badge-pill inline-flex items-center justify-center px-2.5 py-1 rounded-md border font-bold text-[10px] uppercase tracking-wider {{ $bank->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                      {{ $bank->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-right pr-6">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal('{{ addslashes($bank->bank_name) }}', '{{ addslashes($bank->ifsc_code) }}', '{{ $bank->status }}')" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal({{ $bank->id }}, '{{ addslashes($bank->bank_name) }}', '{{ addslashes($bank->ifsc_code) }}', '{{ $bank->status }}')" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Bank">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteModal({{ $bank->id }}, '{{ addslashes($bank->bank_name) }}', '{{ addslashes($bank->ifsc_code) }}')" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Bank">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">No bank records found. Please configure a bank.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- Bank Add Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div x-show="addModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="addModalOpen = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="addModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Bank Loan Master</p>
                        <h2 class="text-lg font-extrabold text-white">Add Bank Account</h2>
                    </div>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('bank.store') }}" method="POST" @submit="submitAddBank($event)" novalidate class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                @csrf
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" x-model="addForm.bank_name" required placeholder="e.g. HDFC Bank, ICICI Bank"
                               :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        <template x-if="errors.bank_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.bank_name) ? errors.bank_name[0] : errors.bank_name"></p></template>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">IFSC Code <span class="text-rose-500">*</span></label>
                        <input type="text" name="ifsc_code" x-model="addForm.ifsc_code" required placeholder="e.g. HDFC0001234"
                               :class="errors.ifsc_code ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] font-mono uppercase outline-none transition">
                        <template x-if="errors.ifsc_code"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.ifsc_code) ? errors.ifsc_code[0] : errors.ifsc_code"></p></template>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="addForm.status" required
                                :class="errors.status ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Bank
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bank Edit Modal --}}
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div x-show="editModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="editModalOpen = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="editModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Bank Loan Master</p>
                        <h2 class="text-lg font-extrabold text-white">Edit Bank Account</h2>
                    </div>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form :action="editForm.action" method="POST" @submit="submitEditBank($event)" novalidate class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 overflow-y-auto">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Bank Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" x-model="editForm.bank_name" required
                               :class="errors.edit_bank_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        <template x-if="errors.edit_bank_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_bank_name) ? errors.edit_bank_name[0] : errors.edit_bank_name"></p></template>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">IFSC Code <span class="text-rose-500">*</span></label>
                        <input type="text" name="ifsc_code" x-model="editForm.ifsc_code" required
                               :class="errors.edit_ifsc_code ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] font-mono uppercase outline-none transition">
                        <template x-if="errors.edit_ifsc_code"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_ifsc_code) ? errors.edit_ifsc_code[0] : errors.edit_ifsc_code"></p></template>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="editForm.status" required
                                :class="errors.edit_status ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="viewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div x-show="viewModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="viewModalOpen = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="viewModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Bank Loan Master</p>
                        <h2 class="text-lg font-extrabold text-white">Bank Account Details</h2>
                    </div>
                    <button @click="viewModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-left space-y-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bank Name</span>
                            <span class="text-sm font-extrabold text-slate-900" x-text="viewForm.bank_name"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase inline-block mt-0.5 border"
                                  :class="viewForm.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200'"
                                  x-text="viewForm.status"></span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200/80 pt-2.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IFSC Code</span>
                        <span class="text-sm font-bold font-mono text-[#a38c29] uppercase" x-text="viewForm.ifsc_code"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end bg-white">
                <button type="button" @click="viewModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

    {{-- Bank Delete Modal --}}
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div x-show="deleteModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="deleteModalOpen = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="deleteModalOpen"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-rose-400 text-[10px] font-semibold uppercase tracking-widest mb-1">Confirm Deletion</p>
                        <h2 class="text-lg font-extrabold text-white">Delete Bank Account</h2>
                    </div>
                    <button @click="deleteModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-rose-100 text-rose-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wide">Warning: Permanent Action</h4>
                        <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                            You are about to delete <span class="font-bold text-slate-900 font-mono" x-text="deleteForm.bank_name"></span> (<span class="font-mono font-bold text-rose-800" x-text="deleteForm.ifsc_code"></span>). This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>

            <form :action="deleteForm.action" method="POST">
                @csrf
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-rose-600/30 uppercase tracking-wide inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Now
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    </div>

</div>

<script>
function bankApp() {
    return {
        errors: {},
        addModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,
        deleteModalOpen: false,
        addForm: {
            bank_name: '',
            ifsc_code: '',
            status: 'active'
        },
        editForm: {
            action: '',
            bank_name: '',
            ifsc_code: '',
            status: 'active'
        },
        viewForm: {
            bank_name: '',
            ifsc_code: '',
            status: 'active'
        },
        deleteForm: {
            action: '',
            bank_name: '',
            ifsc_code: ''
        },

        openDeleteModal(id, bankName, ifscCode) {
            this.deleteForm = {
                action: `{{ url('/bank') }}/${id}/delete`,
                bank_name: bankName,
                ifsc_code: ifscCode
            };
            this.deleteModalOpen = true;
        },

        openAddModal() {
            this.addForm = {
                bank_name: '',
                ifsc_code: '',
                status: 'active'
            };
            this.addModalOpen = true;
        },

        openViewModal(bankName, ifscCode, status) {
            this.viewForm = {
                bank_name: bankName,
                ifsc_code: ifscCode,
                status: status
            };
            this.viewModalOpen = true;
        },

        openEditModal(id, bankName, ifscCode, status) {
            this.editForm = {
                action: `{{ url('/bank') }}/${id}/update`,
                bank_name: bankName,
                ifsc_code: ifscCode,
                status: status
            };
            this.editModalOpen = true;
        },
        submitAddBank(e) {
            let clientErrors = {};
            if (!this.addForm.bank_name || !String(this.addForm.bank_name).trim()) {
                clientErrors.bank_name = ['The bank name field is required.'];
            }
            if (!this.addForm.ifsc_code || !String(this.addForm.ifsc_code).trim()) {
                clientErrors.ifsc_code = ['The IFSC code field is required.'];
            }
            if (!this.addForm.status) {
                clientErrors.status = ['The status field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        },
        submitEditBank(e) {
            let clientErrors = {};
            if (!this.editForm.bank_name || !String(this.editForm.bank_name).trim()) {
                clientErrors.edit_bank_name = ['The bank name field is required.'];
            }
            if (!this.editForm.ifsc_code || !String(this.editForm.ifsc_code).trim()) {
                clientErrors.edit_ifsc_code = ['The IFSC code field is required.'];
            }
            if (!this.editForm.status) {
                clientErrors.edit_status = ['The status field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        }
    }
}
</script>

</x-erp-layout>
