<x-erp-layout title="GST Master" headerTitle="GST Tax Slabs Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="gstApp()">
    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">GST Tax Slabs Management</h1>
            <p class="text-xs text-slate-500 mt-1">Configure Goods and Services Tax (GST) rates, HSN/SAC codes, and applicability across real estate units.</p>
        </div>

        <div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add GST Slab
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

    {{-- GST Slabs Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">TAX SLAB NAME</th>
                        <th class="px-4 py-3 border">HSN / SAC CODE</th>
                        <th class="px-4 py-3 border">RATE (%)</th>
                        <th class="px-4 py-3 border">CGST (%)</th>
                        <th class="px-4 py-3 border">SGST (%)</th>
                        <th class="px-4 py-3 border">APPLICABILITY</th>
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    <template x-for="(slab, index) in slabs" :key="slab.id">
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-4 py-3.5 border font-bold text-slate-400" x-text="index + 1"></td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold" x-text="slab.name"></td>
                            <td class="px-4 py-3.5 border font-mono text-slate-800" x-text="slab.hsn_code"></td>
                            <td class="px-4 py-3.5 border font-extrabold text-[#a38c29]" x-text="Number(slab.rate).toFixed(2) + '%'"></td>
                            <td class="px-4 py-3.5 border text-slate-600" x-text="(Number(slab.rate) / 2).toFixed(2) + '%'"></td>
                            <td class="px-4 py-3.5 border text-slate-600" x-text="(Number(slab.rate) / 2).toFixed(2) + '%'"></td>
                            <td class="px-4 py-3.5 border text-slate-600" x-text="slab.applicability"></td>
                            <td class="px-4 py-3.5 border">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border"
                                      :class="slab.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-200'"
                                      x-text="slab.status"></span>
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(slab)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(slab)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit GST Slab">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="deleteSlab(slab.id)" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete GST Slab">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="slabs.length === 0">
                        <td colspan="9" class="px-6 py-10 text-center text-slate-400 italic">No GST tax slabs found. Please configure a tax slab.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- GST Add Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add GST Tax Slab</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-655">✕</button>
            </div>
            <form @submit.prevent="submitAddForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tax Slab Name</label>
                        <input type="text" x-model="addForm.name" required placeholder="e.g. Construction - Affordable Housing" class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">HSN / SAC Code</label>
                            <input type="text" x-model="addForm.hsn_code" required placeholder="9954" class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Total GST Rate (%)</label>
                            <input type="number" step="0.01" x-model="addForm.rate" required placeholder="5.00" class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Applicability</label>
                            <input type="text" x-model="addForm.applicability" required placeholder="Residential / Commercial Units" class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                            <select x-model="addForm.status" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold cursor-pointer focus:outline-none transition-all">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 border-0 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4.5 py-2.5 border-0 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md shadow-[#a38c29]/20">Add GST Slab</button>
                </div>
            </form>
        </div>
    </div>

    {{-- GST Edit Modal --}}
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit GST Tax Slab</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-655">✕</button>
            </div>
            <form @submit.prevent="submitEditForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tax Slab Name</label>
                        <input type="text" x-model="editForm.name" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">HSN / SAC Code</label>
                            <input type="text" x-model="editForm.hsn_code" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Total GST Rate (%)</label>
                            <input type="number" step="0.01" x-model="editForm.rate" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Applicability</label>
                            <input type="text" x-model="editForm.applicability" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                            <select x-model="editForm.status" required class="w-full px-3.5 py-2.5 bg-slate-100 border-0 hover:bg-slate-200/80 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-bold cursor-pointer focus:outline-none transition-all">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 border-0 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4.5 py-2.5 border-0 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md shadow-[#a38c29]/20">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <div x-show="viewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity text-left" style="display: none;">
        <div @click.away="viewModalOpen = false" class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Tax Slab Details</h3>
                </div>
                <button @click="viewModalOpen = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
            </div>

            <div class="space-y-4 text-xs font-semibold text-slate-700">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tax Slab Name</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewForm.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase inline-block mt-0.5"
                              :class="viewForm.status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500'"
                              x-text="viewForm.status"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-150">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">HSN / SAC Code</span>
                        <span class="font-mono font-bold text-slate-900 text-sm" x-text="viewForm.hsn_code"></span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-150">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Rate</span>
                        <span class="font-extrabold text-[#a38c29] text-sm" x-text="Number(viewForm.rate).toFixed(2) + '%'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-150">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">CGST Component</span>
                        <span class="font-bold text-slate-800" x-text="(Number(viewForm.rate) / 2).toFixed(2) + '%'"></span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-150">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SGST Component</span>
                        <span class="font-bold text-slate-800" x-text="(Number(viewForm.rate) / 2).toFixed(2) + '%'"></span>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-150">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Applicability</span>
                    <span class="text-slate-800 font-bold" x-text="viewForm.applicability"></span>
                </div>
            </div>

            <div class="pt-3 flex justify-end items-center border-t border-slate-100">
                <button type="button" @click="viewModalOpen = false" class="px-4 py-2 border-0 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>
    
    </div>

</div>

<script>
function gstApp() {
    return {
        slabs: [],
        addModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,
        addForm: {
            name: '',
            hsn_code: '',
            rate: '',
            applicability: 'Residential & Commercial Units',
            status: 'active'
        },
        editForm: {
            id: null,
            name: '',
            hsn_code: '',
            rate: '',
            applicability: '',
            status: 'active'
        },
        viewForm: {
            id: null,
            name: '',
            hsn_code: '',
            rate: '',
            applicability: '',
            status: 'active'
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },

        init() {
            let saved = localStorage.getItem('erp_gst_slabs');
            if (saved) {
                this.slabs = JSON.parse(saved);
            } else {
                this.slabs = [
                    { id: 1, name: 'Construction - Affordable Housing', hsn_code: '9954', rate: 1.00, applicability: 'Affordable Residential Projects', status: 'active' },
                    { id: 2, name: 'Construction - Standard Residential', hsn_code: '9954', rate: 5.00, applicability: 'Non-Affordable Residential Projects (No ITC)', status: 'active' },
                    { id: 3, name: 'Commercial & Office Units', hsn_code: '9954', rate: 12.00, applicability: 'Commercial Shops, Offices & IT Parks', status: 'active' },
                    { id: 4, name: 'Property Management & Services', hsn_code: '9983', rate: 18.00, applicability: 'Maintenance, Brokerage & Consulting Services', status: 'active' }
                ];
                this.saveStorage();
            }
        },

        saveStorage() {
            localStorage.setItem('erp_gst_slabs', JSON.stringify(this.slabs));
        },

        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 3000);
        },

        openAddModal() {
            this.addForm = { name: '', hsn_code: '9954', rate: '', applicability: 'Residential & Commercial Units', status: 'active' };
            this.addModalOpen = true;
        },

        submitAddForm() {
            let newId = this.slabs.length ? Math.max(...this.slabs.map(s => s.id)) + 1 : 1;
            this.slabs.push({
                id: newId,
                name: this.addForm.name,
                hsn_code: this.addForm.hsn_code,
                rate: parseFloat(this.addForm.rate) || 0,
                applicability: this.addForm.applicability,
                status: this.addForm.status
            });
            this.saveStorage();
            this.addModalOpen = false;
            this.showToast('GST tax slab added successfully!');
        },

        openEditModal(slab) {
            this.editForm = { ...slab };
            this.editModalOpen = true;
        },

        submitEditForm() {
            let index = this.slabs.findIndex(s => s.id === this.editForm.id);
            if (index !== -1) {
                this.slabs[index] = {
                    ...this.editForm,
                    rate: parseFloat(this.editForm.rate) || 0
                };
                this.saveStorage();
                this.editModalOpen = false;
                this.showToast('GST tax slab updated successfully!');
            }
        },

        openViewModal(slab) {
            this.viewForm = { ...slab };
            this.viewModalOpen = true;
        },

        deleteSlab(id) {
            if (confirm('Are you sure you want to delete this GST tax slab?')) {
                this.slabs = this.slabs.filter(s => s.id !== id);
                this.saveStorage();
                this.showToast('GST tax slab deleted successfully!');
            }
        }
    }
}
</script>
</x-erp-layout>
