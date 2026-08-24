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

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
            {{-- Search: Name / Email / Phone --}}
            <div class="relative sm:col-span-2 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search by name, email or phone..."
                       x-model="filters.search" @input.debounce.300ms="fetchCustomers()"
                       class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                    <button type="button" x-show="filters.search" @click="filters.search = ''; fetchCustomers()"
                            class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <select x-model="filters.status" @change="fetchCustomers()"
                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                    <option value="">All Statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="resetFilters()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
            <button @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-slate-900/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider">
                <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Customer
            </button>
        </div>
    </div>

    {{-- Customers Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #customers-table thead th {
                border-color: #8a7522 !important;
            }
            #customers-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #customers-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="customers-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Customer</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Contact Info</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Units Purchased</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Total Sale Value</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Total Paid</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Outstanding Balance</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">Status</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="customers-tbody">
                    <template x-for="customer in customers" :key="customer.id">
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3 border text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0"
                                         x-text="(customer.avatar_url || customer.name.substring(0,2)).toUpperCase()"></div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight" x-text="customer.name"></span>
                                        <span class="text-[9px] text-slate-500 font-medium" x-text="customer.address ? (customer.address.length > 25 ? customer.address.substring(0,25)+'...' : customer.address) : 'No address'"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-left">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-slate-700 font-medium flex items-center gap-1.5 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span x-text="customer.email"></span>
                                    </span>
                                    <template x-if="customer.phone">
                                        <span class="text-slate-500 text-[10px] flex items-center gap-1.5 mt-0.5">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span x-text="customer.phone"></span>
                                        </span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]" x-text="(customer.sales_count || 0) + ' Units'"></span>
                            </td>
                            <td class="px-3 py-3 border text-right font-bold text-slate-800" x-text="'₹' + Number(customer.total_purchase || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-right font-bold text-emerald-600" x-text="'₹' + Number(customer.total_paid || 0).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-right font-bold" :class="Number(customer.total_purchase || 0) - Number(customer.total_paid || 0) > 0 ? 'text-rose-600' : 'text-slate-500'" x-text="'₹' + Number(Math.max(0, (customer.total_purchase || 0) - (customer.total_paid || 0))).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></td>
                            <td class="px-3 py-3 border text-center">
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
                                    <template x-if="!customer.sales_count || customer.sales_count == 0">
                                        <button @click="openDeleteModal(customer)" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Customer">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
                                    <template x-if="customer.sales_count > 0">
                                        <button disabled class="p-2 rounded-lg bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed shadow-sm" title="Cannot delete customer with associated properties">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </template>
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

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>    {{-- ═══════════════════════════════════════════
         ADD CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Customer Directory</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Add New Customer</h2>
                    </div>
                    <button type="button" @click="closeAddModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitAddCustomer()">
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name *</label>
                            <input type="text" x-model="forms.add.name"
                                   @input="if (errors.name) delete errors.name"
                                   :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   placeholder="Enter name"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                                <input type="email" x-model="forms.add.email"
                                       @input="if (errors.email) delete errors.email"
                                       :class="errors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       placeholder="Enter email"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="errors.email"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.email) ? errors.email[0] : errors.email"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                                <input type="text" x-model="forms.add.phone"
                                       @input="if (errors.phone) delete errors.phone"
                                       :class="errors.phone ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       placeholder="Enter phone number"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="errors.phone"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.phone) ? errors.phone[0] : errors.phone"></p></template>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                            <textarea x-model="forms.add.address" rows="2"
                                      @input="if (errors.address) delete errors.address"
                                      :class="errors.address ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                      placeholder="Enter address..."
                                      class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm font-semibold"></textarea>
                            <template x-if="errors.address"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.address) ? errors.address[0] : errors.address"></p></template>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Add Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         EDIT CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Profile</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Edit Customer Details</h2>
                    </div>
                    <button type="button" @click="closeEditModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <form @submit.prevent="submitEditCustomer()">
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Full Name *</label>
                            <input type="text" x-model="forms.edit.name" placeholder="Enter name"
                                   @input="if (editErrors.name) delete editErrors.name"
                                   :class="editErrors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                            <template x-if="editErrors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.name) ? editErrors.name[0] : editErrors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email</label>
                                <input type="email" x-model="forms.edit.email" placeholder="Enter email"
                                       @input="if (editErrors.email) delete editErrors.email"
                                       :class="editErrors.email ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="editErrors.email"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.email) ? editErrors.email[0] : editErrors.email"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Phone</label>
                                <input type="text" x-model="forms.edit.phone" placeholder="Enter phone number"
                                       @input="if (editErrors.phone) delete editErrors.phone"
                                       :class="editErrors.phone ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                       class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold">
                                <template x-if="editErrors.phone"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.phone) ? editErrors.phone[0] : editErrors.phone"></p></template>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Address</label>
                            <textarea x-model="forms.edit.address" rows="2" placeholder="Enter address..."
                                      @input="if (editErrors.address) delete editErrors.address"
                                      :class="editErrors.address ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                      class="w-full px-3 py-2 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all resize-none shadow-sm font-semibold"></textarea>
                            <template x-if="editErrors.address"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(editErrors.address) ? editErrors.address[0] : editErrors.address"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</label>
                            <select x-model="forms.edit.is_active"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs focus:outline-none transition-all shadow-sm font-semibold text-slate-700 cursor-pointer">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <template x-if="editErrors.is_active"><p class="text-[10px] text-rose-600 font-semibold" x-text="editErrors.is_active[0]"></p></template>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Update Customer</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         DELETE CUSTOMER CONFIRMATION MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-500/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Safety Check</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Delete Customer</h2>
                    </div>
                    <button type="button" @click="closeDeleteModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-2">
                    <p class="text-sm text-slate-700">
                        Are you sure you want to delete customer <span class="font-bold text-slate-900" x-text="deleteTarget?.name"></span>?
                    </p>
                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">This action cannot be undone and will remove the record.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-255 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                <button type="button" @click="confirmDeleteCustomer()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Confirm Delete</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW CUSTOMER MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="modals.view.open = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Customer Profile</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Profile Overview</h2>
                    </div>
                    <button type="button" @click="modals.view.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-4 bg-slate-50/50 text-xs font-sans">
                <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Customer Name</span>
                        <span class="text-sm font-extrabold text-slate-900" x-text="viewTarget?.name"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5"
                              :class="viewTarget?.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200'"
                              x-text="viewTarget?.is_active ? 'Active' : 'Inactive'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate" x-text="viewTarget?.email || 'N/A'"></span>
                    </div>
                    <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.phone || 'N/A'"></span>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Address Details</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.address || 'No address provided'"></span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                <a :href="'{{ route('dms.index', ['category' => 'customer']) }}&search=' + encodeURIComponent(viewTarget?.name || '')" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Manage Documents
                </a>
                <button type="button" @click="modals.view.open = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
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
            this.errors = {};
            let hasError = false;

            if (!this.forms.add.name || !this.forms.add.name.trim()) {
                this.errors.name = ['Please enter customer name.'];
                hasError = true;
            } else if (this.forms.add.name.trim().length < 2) {
                this.errors.name = ['Full Name must be at least 2 characters.'];
                hasError = true;
            }

            if (this.forms.add.email && this.forms.add.email.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.forms.add.email.trim())) {
                    this.errors.email = ['Please enter a valid email address.'];
                    hasError = true;
                }
            }

            if (this.forms.add.phone && this.forms.add.phone.trim()) {
                const phoneRegex = /^[+]*[0-9\s\-()]{7,20}$/;
                if (!phoneRegex.test(this.forms.add.phone.trim())) {
                    this.errors.phone = ['Please enter a valid phone number format.'];
                    hasError = true;
                }
            }

            if (hasError) return;

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
            this.editErrors = {};
            let hasError = false;

            if (!this.forms.edit.name || !this.forms.edit.name.trim()) {
                this.editErrors.name = ['Please enter full name.'];
                hasError = true;
            } else if (this.forms.edit.name.trim().length < 2) {
                this.editErrors.name = ['Full Name must be at least 2 characters.'];
                hasError = true;
            }

            if (this.forms.edit.email && this.forms.edit.email.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.forms.edit.email.trim())) {
                    this.editErrors.email = ['Please enter a valid email address.'];
                    hasError = true;
                }
            }

            if (this.forms.edit.phone && this.forms.edit.phone.trim()) {
                const phoneRegex = /^[+]*[0-9\s\-()]{7,20}$/;
                if (!phoneRegex.test(this.forms.edit.phone.trim())) {
                    this.editErrors.phone = ['Please enter a valid phone number format.'];
                    hasError = true;
                }
            }

            if (hasError) return;

            let customerId = this.forms.edit.id;
            let payload = { ...this.forms.edit, _method: 'PUT' };
            fetch(`{{ url('customers') }}/${customerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Server returned non-JSON:', text);
                    this.showToast('Server returned an invalid response. Check console.', 'error');
                    return;
                }
                
                if (res.status === 422) {
                    this.editErrors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || data.message || 'Server error occurred.', 'error');
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
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Server returned non-JSON:', text);
                    this.showToast('Server returned an invalid response. Check console.', 'error');
                    return;
                }
                
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to delete customer.', 'error');
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
