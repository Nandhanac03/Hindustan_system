<x-erp-layout title="Bank Master" headerTitle="Bank Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="bankApp()">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif
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
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">BANK NAME</th>
                        <th class="px-4 py-3 border">IFSC CODE</th>
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    @forelse($banks as $index => $bank)
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold text-left pl-6">{{ $bank->bank_name }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-650 uppercase">{{ $bank->ifsc_code }}</td>
                            <td class="px-4 py-3.5 border">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $bank->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-200' }}">
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
                                    <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Bank">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
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
    </div>

    {{-- Bank Add Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="addModalOpen = false">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest text-left w-full">Add Bank Account</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form action="{{ route('bank.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4 text-left">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" name="bank_name" x-model="addForm.bank_name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                        <input type="text" name="ifsc_code" x-model="addForm.ifsc_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" x-model="addForm.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-[#a38c29]/5">Add Bank</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bank Edit Modal --}}
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="editModalOpen = false">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest text-left w-full">Edit Bank Account</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form :action="editForm.action" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4 text-left">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" name="bank_name" x-model="editForm.bank_name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                        <input type="text" name="ifsc_code" x-model="editForm.ifsc_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" x-model="editForm.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-[#a38c29]/5">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="viewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div @click.away="viewModalOpen = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5 text-left animate-fade-in-up">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Bank Account Details</h3>
                </div>
                <button @click="viewModalOpen = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
            </div>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 space-y-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bank Name</span>
                            <span class="text-sm font-extrabold text-slate-900" x-text="viewForm.bank_name"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono uppercase inline-block mt-0.5 border"
                                  :class="viewForm.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200'"
                                  x-text="viewForm.status"></span>
                        </div>
                    </div>
                    <div class="border-t border-slate-150 pt-2.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IFSC Code</span>
                        <span class="text-sm font-bold font-mono text-slate-800 uppercase" x-text="viewForm.ifsc_code"></span>
                    </div>
                </div>
            </div>

            <div class="pt-3 flex justify-end items-center border-t border-slate-100">
                <button type="button" @click="viewModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function bankApp() {
    return {
        addModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,
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
                action: `/bank/${id}`,
                bank_name: bankName,
                ifsc_code: ifscCode,
                status: status
            };
            this.editModalOpen = true;
        }
    }
}
</script>

</x-erp-layout>
