<x-erp-layout title="Customers Directory" headerTitle="Customers Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="customersApp()">

    {{-- Notification Toast --}}
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

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">

            {{-- Search: Name / Email / Phone --}}
            <div class="relative sm:col-span-2">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search by name, email or phone..."
                       x-model="filters.search" @input.debounce.300ms="fetchCustomers()"
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs placeholder-slate-450 focus:outline-none transition-all">
            </div>

            {{-- Status Filter --}}
            <select x-model="filters.status" @change="fetchCustomers()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button
                @click="resetFilters()"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-primary hover:bg-primary-50 hover:text-primary-700 hover:shadow-md">
                Reset Filters
            </button>
           <button
                @click="openAddModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-primary-700 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Customer
            </button>
        </div>
    </div>

    {{-- Customers Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border">Customer</th>
                        <th class="px-3 py-3 border">Email</th>
                        <th class="px-3 py-3 border">Phone</th>
                        <th class="px-3 py-3 border">Address</th>
                        <!-- <th class="px-3 py-3 border">ID Proof</th> -->
                        <!-- <th class="px-3 py-3 border">System</th> -->
                        <th class="px-3 py-3 border">Status</th>
                        <th class="px-3 py-3 border text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="customer in customers" :key="customer.id">
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3 border text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-[9px] font-bold text-white flex-shrink-0"
                                         x-text="(customer.avatar_url || customer.name.substring(0,2)).toUpperCase()"></div>
                                    <span class="font-bold text-slate-900" x-text="customer.name"></span>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-slate-600" x-text="customer.email"></td>
                            <td class="px-3 py-3 border text-slate-600" x-text="customer.phone || 'N/A'"></td>
                            <td class="px-3 py-3 border text-slate-500 text-left" x-text="customer.address || 'N/A'"></td>
                            <!-- <td class="px-3 py-3 border text-slate-600">
                                <span x-text="customer.id_proof_type ? customer.id_proof_type + ': ' + customer.id_proof_number : 'N/A'"></span>
                            </td> -->
                            <!-- <td class="px-3 py-3 border">
                                <span class="badge-pill" :class="customer.system === 'uae' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-primary-50 text-primary-700 border border-primary-100'" x-text="customer.system.toUpperCase()"></span>
                            </td> -->
                            <td class="px-3 py-3 border">
                                <span class="badge-pill" :class="customer.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200'" x-text="customer.is_active ? 'Active' : 'Inactive'"></span>
                            </td>
                            <td class="px-3 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(customer)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Customer Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(customer.id)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Customer">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteModal(customer)" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Customer">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="customers.length === 0">
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No customers match the query filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ADD CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add New Customer</h3>
                <button @click="closeAddModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitAddCustomer()">
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name</label>
                        <input type="text" x-model="forms.add.name" placeholder="Enter name"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.name[0]"></p></template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                            <input type="email" x-model="forms.add.email" placeholder="Enter email"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.email"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.email[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                            <input type="text" x-model="forms.add.phone" placeholder="Enter phone number"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.phone"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.phone[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                        <textarea x-model="forms.add.address" rows="2" placeholder="Enter address..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                        <template x-if="errors.address"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.address[0]"></p></template>
                    </div>

                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Add Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         EDIT CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit Customer</h3>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitEditCustomer()">
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name</label>
                        <input type="text" x-model="forms.edit.name" placeholder="Enter name"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        <template x-if="editErrors.name"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.name[0]"></p></template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                            <input type="email" x-model="forms.edit.email" placeholder="Enter email"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="editErrors.email"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.email[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                            <input type="text" x-model="forms.edit.phone" placeholder="Enter phone number"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="editErrors.phone"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.phone[0]"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                        <textarea x-model="forms.edit.address" rows="2" placeholder="Enter address..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                        <template x-if="editErrors.address"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.address[0]"></p></template>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</label>
                        <select x-model="forms.edit.is_active"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <template x-if="editErrors.is_active"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.is_active[0]"></p></template>
                    </div>

                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Update Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         DELETE CUSTOMER CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Delete Customer</h3>
                <button @click="closeDeleteModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <div class="p-6 space-y-2">
                <p class="text-sm text-slate-700">
                    Are you sure you want to delete customer <span class="font-bold" x-text="deleteTarget?.name"></span>?
                </p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                <button type="button" @click="confirmDeleteCustomer()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Confirm Delete</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="modals.view.open = false">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Customer Profile</h3>
                </div>
                <button @click="modals.view.open = false" class="text-slate-400 hover:text-slate-600 text-base">✕</button>
            </div>

            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Customer Name</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewTarget?.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono uppercase inline-block mt-0.5"
                              :class="viewTarget?.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500'"
                              x-text="viewTarget?.is_active ? 'Active' : 'Inactive'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.email || 'N/A'"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.phone || 'N/A'"></span>
                    </div>
                </div>

                <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address Details</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.address || 'No address provided'"></span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end bg-slate-50">
                <button type="button" @click="modals.view.open = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC CODE
═══════════════════════════════════════════ --}}
<script>
function customersApp() {
    return {
        customers: [],
        filters: {
            search: '',
            status: ''
        },
        modals: {
            add: { open: false },
            edit: { open: false },
            delete: { open: false },
            view: { open: false }
        },
        deleteTarget: null,
        viewTarget: null,
        forms: {
            add: {
                name: '',
                email: '',
                phone: '',
                address: '',
            },
            edit: {
                id: null,
                name: '',
                email: '',
                phone: '',
                address: '',
                is_active: '1'
            }
        },
        errors: {},
        editErrors: {},
        toast: {
            open: false,
            message: '',
            type: 'success'
        },

        init() {
            this.fetchCustomers();
        },

        fetchCustomers() {
            let params = new URLSearchParams();
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.status !== '') params.append('status', this.filters.status);

            fetch('{{ route('customers.index') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.customers = data.customers;
            })
            .catch(err => {
                console.error('Error fetching customers:', err);
                this.showToast('Failed to fetch customers list.', 'error');
            });
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.status = '';
            this.fetchCustomers();
        },

        openAddModal() {
            this.errors = {};
            this.forms.add = {
                name: '',
                email: '',
                phone: '',
                address: '',
            };
            this.modals.add.open = true;
        },
        closeAddModal() {
            this.modals.add.open = false;
        },

        submitAddCustomer() {
            fetch('{{ route('customers.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.add)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Customer added successfully.');
                    this.closeAddModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        openViewModal(customer) {
            this.viewTarget = customer;
            this.modals.view.open = true;
        },

        openEditModal(customerId) {
            this.editErrors = {};

            // Try to use already-loaded row data first for instant display
            let existing = this.customers.find(c => c.id === customerId);
            if (existing) {
                this.forms.edit = {
                    id: existing.id,
                    name: existing.name || '',
                    email: existing.email || '',
                    phone: existing.phone || '',
                    address: existing.address || '',
                    is_active: existing.is_active ? '1' : '0'
                };
                this.modals.edit.open = true;
            }

            // Fetch the latest data from the server to make sure it's fresh
            fetch(`{{ url('customers') }}/${customerId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                let c = data.customer || data;
                this.forms.edit = {
                    id: c.id,
                    name: c.name || '',
                    email: c.email || '',
                    phone: c.phone || '',
                    address: c.address || '',
                    is_active: c.is_active ? '1' : '0'
                };
                this.modals.edit.open = true;
            })
            .catch(err => {
                console.error('Error fetching customer:', err);
                if (!existing) {
                    this.showToast('Failed to load customer details.', 'error');
                }
            });
        },
        closeEditModal() {
            this.modals.edit.open = false;
        },

        submitEditCustomer() {
            let customerId = this.forms.edit.id;
            fetch(`{{ url('customers') }}/${customerId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.edit)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.editErrors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Customer updated successfully.');
                    this.closeEditModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        openDeleteModal(customer) {
            this.deleteTarget = customer;
            this.modals.delete.open = true;
        },
        closeDeleteModal() {
            this.modals.delete.open = false;
            this.deleteTarget = null;
        },

        confirmDeleteCustomer() {
            if (!this.deleteTarget) return;
            let customerId = this.deleteTarget.id;

            fetch(`{{ url('customers') }}/${customerId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                let data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to delete customer.', 'error');
                } else {
                    this.showToast('Customer deleted successfully.');
                    this.closeDeleteModal();
                    this.fetchCustomers();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        }
    };
}
</script>

</x-erp-layout>