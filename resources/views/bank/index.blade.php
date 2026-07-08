<x-erp-layout title="Bank Master" headerTitle="Bank Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="bankApp()">
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

    {{-- Alert Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- Banks Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">BANK NAME</th>
                        <!-- <th class="px-4 py-3 border">ACCOUNT NUMBER</th>
                        <th class="px-4 py-3 border">IFSC CODE</th>
                        <th class="px-4 py-3 border">BRANCH NAME</th>
                        <th class="px-4 py-3 border">ACCOUNT TYPE</th> -->
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    <template x-for="(bank, index) in banks" :key="bank.id">
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-4 py-3.5 border font-bold text-slate-400" x-text="index + 1"></td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold" x-text="bank.bank_name"></td>
                            <!-- <td class="px-4 py-3.5 border font-mono text-slate-800" x-text="bank.account_no"></td>
                            <td class="px-4 py-3.5 border font-mono text-slate-650" x-text="bank.ifsc_code"></td>
                            <td class="px-4 py-3.5 border text-slate-600" x-text="bank.branch"></td> -->
                            <!-- <td class="px-4 py-3.5 border text-slate-600 uppercase text-[10px] tracking-wide" x-text="bank.account_type"></td> -->
                            <td class="px-4 py-3.5 border">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border"
                                      :class="bank.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-200'"
                                      x-text="bank.status"></span>
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(bank)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(bank)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Edit Bank">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteBank(bank.id)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="Delete Bank">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="banks.length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No bank records found. Please configure a bank.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bank Add Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add Bank Account</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-655">✕</button>
            </div>
            <form @submit.prevent="submitAddForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" x-model="addForm.bank_name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <!-- <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Number</label>
                            <input type="text" x-model="addForm.account_no" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                            <input type="text" x-model="addForm.ifsc_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono uppercase">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
                            <input type="text" x-model="addForm.branch" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Type</label>
                            <select x-model="addForm.account_type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                                <option value="current">Current Account</option>
                                <option value="savings">Savings Account</option>
                                <option value="escrow">Escrow Account</option>
                            </select>
                        </div>
                    </div> -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select x-model="addForm.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-[#a38c29]/5">Add Bank</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bank Edit Modal --}}
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit Bank Account</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-655">✕</button>
            </div>
            <form @submit.prevent="submitEditForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" x-model="editForm.bank_name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <!-- <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Number</label>
                            <input type="text" x-model="editForm.account_no" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                            <input type="text" x-model="editForm.ifsc_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all font-mono uppercase">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
                            <input type="text" x-model="editForm.branch" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Type</label>
                            <select x-model="editForm.account_type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                                <option value="current">Current Account</option>
                                <option value="savings">Savings Account</option>
                                <option value="escrow">Escrow Account</option>
                            </select>
                        </div>
                    </div> -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select x-model="editForm.status" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm shadow-[#a38c29]/5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="viewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity text-left" style="display: none;">
        <div @click.away="viewModalOpen = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5">
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
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bank Name</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewForm.bank_name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono uppercase inline-block mt-0.5"
                              :class="viewForm.status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500'"
                              x-text="viewForm.status"></span>
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
        banks: [
            { id: 1, bank_name: 'HDFC Bank', status: 'active' },
            { id: 2, bank_name: 'State Bank of India', status: 'active' },
            { id: 3, bank_name: 'Federal Bank', status: 'inactive' }
        ],
        addModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,
        addForm: {
            bank_name: '',
            status: 'active'
        },
        editForm: {
            id: null,
            bank_name: '',
            status: 'active'
        },
        viewForm: {
            id: null,
            bank_name: '',
            status: 'active'
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 3000);
        },

        openAddModal() {
            this.addForm = {
                bank_name: '',
                status: 'active'
            };
            this.addModalOpen = true;
        },

        submitAddForm() {
            const newId = this.banks.length > 0 ? Math.max(...this.banks.map(b => b.id)) + 1 : 1;
            this.banks.push({
                id: newId,
                bank_name: this.addForm.bank_name,
                status: this.addForm.status
            });
            this.addModalOpen = false;
            this.showToast('Bank account added successfully.');
        },

        openViewModal(bank) {
            this.viewForm = { ...bank };
            this.viewModalOpen = true;
        },

        openEditModal(bank) {
            this.editForm = { ...bank };
            this.editModalOpen = true;
        },

        submitEditForm() {
            const idx = this.banks.findIndex(b => b.id === this.editForm.id);
            if (idx !== -1) {
                this.banks[idx] = { ...this.editForm };
            }
            this.editModalOpen = false;
            this.showToast('Bank account updated successfully.');
        },

        deleteBank(id) {
            if (confirm('Are you sure you want to delete this bank account?')) {
                this.banks = this.banks.filter(b => b.id !== id);
                this.showToast('Bank account deleted successfully.');
            }
        }
    }
}
</script>

</x-erp-layout>
