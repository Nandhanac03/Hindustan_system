<x-erp-layout title="Sales Register" headerTitle="Sales Register">

<div class="max-w-[1500px] mx-auto space-y-6" x-data="salesApp()">

    {{-- Toast --}}
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
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 flex-1">

            {{-- Search --}}
            <div class="relative sm:col-span-1">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Sale No / Customer..."
                       x-model="filters.search" @input.debounce.300ms="fetchSales()"
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all">
            </div>

            {{-- Project Filter --}}
            <select x-model="filters.project_id" @change="fetchSales()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select x-model="filters.status" @change="fetchSales()"
                    class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="cancelled">Cancelled</option>
                <option value="returned">Returned</option>
                <option value="exchanged">Exchanged</option>
                <option value="resale">Resale</option>
            </select>

            {{-- Date From --}}
            <input type="date" x-model="filters.date_from" @change="fetchSales()"
                   class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 focus:outline-none transition-all">

            {{-- Date To --}}
            <input type="date" x-model="filters.date_to" @change="fetchSales()"
                   class="w-full px-3 py-2 bg-slate-50 border-0 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 focus:outline-none transition-all">
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="resetFilters()"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:border-primary hover:bg-primary-50 hover:text-primary-700 hover:shadow-md">
                Reset Filters
            </button>
            <button @click="openAddModal()"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-primary-700 hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Sale
            </button>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border">Sale No</th>
                        <th class="px-3 py-3 border">Project / Unit</th>
                        <th class="px-3 py-3 border">Customer</th>
                        <th class="px-3 py-3 border">Broker</th>
                        <th class="px-3 py-3 border">Sale Amount</th>
                        <th class="px-3 py-3 border">GST</th>
                        <th class="px-3 py-3 border">Total</th>
                        <th class="px-3 py-3 border">Sale Date</th>
                        <th class="px-3 py-3 border">Status</th>
                        <th class="px-3 py-3 border text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="sale in sales" :key="sale.id">
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3 border font-bold text-slate-900" x-text="sale.sale_number"></td>
                            <td class="px-3 py-3 border text-left">
                                <div class="font-bold text-slate-800" x-text="sale.project ? sale.project.name : 'N/A'"></div>
                                <div class="text-[10px] text-slate-400" x-text="sale.unit ? sale.unit.door_no : ''"></div>
                            </td>
                            <td class="px-3 py-3 border text-slate-600" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                            <td class="px-3 py-3 border text-slate-500" x-text="sale.broker ? sale.broker.name : '—'"></td>
                            <td class="px-3 py-3 border font-bold text-slate-900" x-text="'₹' + Number(sale.sale_amount).toLocaleString()"></td>
                            <td class="px-3 py-3 border">
                                <span x-show="sale.gst_type && sale.gst_type !== 'none'" x-text="'₹' + Number(sale.gst_amount).toLocaleString() + ' (' + sale.gst_percentage + '%, ' + sale.gst_type + ')'"></span>
                                <span x-show="!sale.gst_type || sale.gst_type === 'none'" class="text-slate-400">N/A</span>
                            </td>
                            <td class="px-3 py-3 border font-bold text-emerald-700" x-text="'₹' + Number(sale.total_amount).toLocaleString()"></td>
                            <td class="px-3 py-3 border text-slate-500" x-text="sale.sale_date"></td>
                            <td class="px-3 py-3 border">
                                <span class="badge-pill" :class="getStatusBadgeClass(sale.status)" x-text="sale.status"></span>
                            </td>
                            <td class="px-3 py-3 border text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openViewModal(sale.id)" class="p-1.5 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(sale.id)" class="p-1.5 hover:bg-primary-50 text-slate-400 hover:text-primary-600 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="sales.length === 0">
                        <td colspan="10" class="px-6 py-10 text-center text-slate-400 italic">No sales match the query filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ADD SALE MODAL — 6 Sections
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeAddModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add New Sale</h3>
                <button @click="closeAddModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitAddSale()">
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                    {{-- ── Section 1 — Basics ── --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project *</label>
                            <select x-model="forms.add.project_id" @change="loadUnitsForProject('add')"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <option value="">Select Project...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.project_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.project_id[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit *</label>
                            <select x-model="forms.add.unit_id" @change="onUnitSelect('add')" :disabled="!forms.add.project_id"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50">
                                <option value="">— Select Project First —</option>
                                <template x-for="unit in availableUnits.add" :key="unit.id">
                                    <option :value="unit.id" x-text="unit.door_no + ' — ' + unit.floor_name"></option>
                                </template>
                            </select>
                            <template x-if="errors.unit_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.unit_id[0]"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-end">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Customer *</label>
                            <div class="flex gap-2">
                                <select x-model="forms.add.customer_id"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="">— Select Customer —</option>
                                    <template x-for="customer in customerList" :key="customer.id">
                                        <option :value="customer.id" x-text="customer.name + ' (' + customer.email + ')'"></option>
                                    </template>
                                </select>
                                <button type="button" @click="openQuickAddCustomer()"
                                        class="flex-shrink-0 w-9 h-9 flex items-center justify-center border border-slate-300 rounded-xl text-slate-500 hover:border-primary hover:text-primary transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            <template x-if="errors.customer_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.customer_id[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreement Date *</label>
                            <input type="date" x-model="forms.add.agreement_date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            <template x-if="errors.agreement_date"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.agreement_date[0]"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Registration Date</label>
                            <input type="date" x-model="forms.add.registration_date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        </div>
                    </div>

                    {{-- ── Section 2 — Sale Amount ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">📐 Section 2 — Sale Amount</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Expected Rate / Sq.Ft</p>
                                    <p class="font-bold text-slate-800" x-text="selectedUnit.add ? '₹' + Number(selectedUnit.add.expected_rate_per_sqft).toLocaleString() : '—'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Expected Sale Value</p>
                                    <p class="font-bold text-slate-800" x-text="selectedUnit.add ? '₹' + Number(selectedUnit.add.expected_sale_amount).toLocaleString() : '—'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Built-up Area</p>
                                    <p class="font-bold text-slate-800" x-text="(selectedUnit.add ? Number(selectedUnit.add.built_up_area).toLocaleString() : '—') + ' Sq.Ft'"></p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreed Rate per Sq.Ft *</label>
                                    <input type="number" step="0.01" x-model="forms.add.rate_per_sqft" @input="onRateChange('add')" placeholder="Enter agreed rate"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <template x-if="errors.rate_per_sqft"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.rate_per_sqft[0]"></p></template>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Agreed Sale Amount</p>
                                    <p class="text-lg font-extrabold text-slate-900" x-text="'₹' + Number(forms.add.sale_amount || 0).toLocaleString()"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Difference</p>
                                    <p class="text-sm font-bold" :class="saleDifference('add') >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="'₹' + Number(saleDifference('add')).toLocaleString()"></p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Type</label>
                                    <select x-model="forms.add.gst_type" @change="recalculateGst('add')"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <option value="none">None — No GST</option>
                                        <option value="inclusive">GST Included (18%)</option>
                                        <option value="exclusive">GST Excluded (18% Extra)</option>
                                    </select>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">GST Amount</p>
                                    <p class="font-bold text-slate-800" x-text="'₹' + Number(forms.add.gst_amount || 0).toLocaleString()"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Payable</p>
                                    <p class="text-sm font-extrabold text-emerald-700" x-text="'₹' + Number(forms.add.total_amount || 0).toLocaleString()"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Section 3 — Broker / Commission ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                            <input type="checkbox" x-model="forms.add.broker_involved" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">🧑 Section 3 — Broker / Commission — A broker is involved in this sale</span>
                        </label>
                        <div x-show="forms.add.broker_involved" class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker</label>
                                <select x-model="forms.add.broker_id"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="">— Select Broker —</option>
                                    @foreach($brokers as $broker)
                                        <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.broker_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.broker_id[0]"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Status</label>
                                <select x-model="forms.add.brokerage_status"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                <div class="flex items-center gap-4 pt-2">
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="percentage" x-model="forms.add.brokerage_type" @change="recalculateBrokerage('add')" class="text-primary focus:ring-primary/20">
                                        Percentage (%)
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="fixed" x-model="forms.add.brokerage_type" @change="recalculateBrokerage('add')" class="text-primary focus:ring-primary/20">
                                        Fixed Amount (₹)
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                <input type="number" step="0.01" x-model="forms.add.brokerage_value" @input="recalculateBrokerage('add')" placeholder="e.g. 2 for 2%"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <template x-if="errors.brokerage_value"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.brokerage_value[0]"></p></template>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brokerage Amount</p>
                                <p class="font-bold text-slate-900" x-text="'₹' + Number(forms.add.brokerage_amount || 0).toLocaleString()"></p>
                            </div>
                        </div>
                    </div>

                    {{-- ── Section 4 — Initial Payment ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">💼 Section 4 — Initial Payment</p>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Initial Payment Amount</label>
                                <input type="number" step="0.01" x-model="forms.add.initial_payment_amount" @input="recalculateBalance('add')" placeholder="Enter 0 if no payment at this time"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                <p class="text-[10px] text-slate-400">Enter 0 if no payment at this time</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</label>
                                <select x-model="forms.add.payment_mode"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Date</label>
                                <input type="date" x-model="forms.add.initial_payment_date"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            </div>
                        </div>
                        <div x-show="['Bank Transfer', 'Cheque'].includes(forms.add.payment_mode)" class="grid grid-cols-2 gap-4 mt-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Reference / Cheque No</label>
                                <input type="text" x-model="forms.add.reference_no" placeholder="e.g. UTR / Cheque number"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bank Name</label>
                                <input type="text" x-model="forms.add.bank_name" placeholder="e.g. HDFC Bank"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- ── Section 5 — Balance & Payment Plan ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">📊 Section 5 — Balance & Payment Plan</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</p>
                                <p class="text-lg font-extrabold text-primary" x-text="'₹' + Number(forms.add.remaining_balance || 0).toLocaleString()"></p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Plan</label>
                                <div class="flex items-center gap-4 pt-2">
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="lump_sum" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        Lump Sum (Full payment)
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="emi" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        EMI / Installment Plan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Section 6 — Remarks ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">💬 Section 6 — Remarks</p>
                        <textarea x-model="forms.add.notes" rows="3" placeholder="Optional remarks or notes..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                    </div>

                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Create Sale</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         QUICK ADD CUSTOMER MODAL (nested)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.quickCustomer.open" class="fixed inset-0 z-[60] flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="modals.quickCustomer.open = false">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Quick Add Customer</h3>
                <button @click="modals.quickCustomer.open = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitQuickCustomer()">
                <div class="p-6 space-y-3">
                    <input type="text" x-model="quickCustomer.name" placeholder="Full Name"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <input type="email" x-model="quickCustomer.email" placeholder="Email"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <input type="text" x-model="quickCustomer.phone" placeholder="Phone"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                    <template x-if="quickCustomerErrors.name"><p class="text-[10px] text-rose-600 font-semibold" x-text="quickCustomerErrors.name[0]"></p></template>
                    <template x-if="quickCustomerErrors.email"><p class="text-[10px] text-rose-600 font-semibold" x-text="quickCustomerErrors.email[0]"></p></template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50">
                    <button type="button" @click="modals.quickCustomer.open = false" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl uppercase">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl uppercase">Add & Select</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════
         EDIT SALE MODAL (includes status transitions)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit Sale — <span x-text="activeSale.sale_number"></span></h3>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                <form @submit.prevent="submitEditSale()" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sale Amount (₹)</label>
                            <input type="number" step="0.01" x-model="forms.edit.sale_amount" @input="recalculateGst('edit')"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sale Date</label>
                            <input type="date" x-model="forms.edit.sale_date"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition">
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <select x-model="forms.edit.gst_type" @change="recalculateGst('edit')"
                                    class="px-3 py-2 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-4 focus:ring-primary/10 focus:border-primary">
                                <option value="none">No GST (None)</option>
                                <option value="inclusive">GST Included (18%)</option>
                                <option value="exclusive">GST Excluded (18% Extra)</option>
                            </select>
                            <input type="text" :value="'GST: ₹' + Number(forms.edit.gst_amount || 0).toLocaleString()" disabled
                                   class="px-3 py-2 bg-slate-100 border border-slate-250 rounded-xl text-xs text-slate-500">
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <input type="text" :value="'Base: ₹' + Number(forms.edit.base_amount || forms.edit.sale_amount || 0).toLocaleString()" disabled
                                   class="px-3 py-2 bg-slate-100 border border-slate-250 rounded-xl text-xs text-slate-500">
                            <input type="text" :value="'Total: ₹' + Number(forms.edit.total_amount || forms.edit.sale_amount || 0).toLocaleString()" disabled
                                   class="px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-bold">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Notes</label>
                        <textarea x-model="forms.edit.notes" rows="2"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition uppercase tracking-wide">
                        Save Changes
                    </button>
                </form>

                {{-- Status Transitions --}}
                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <p class="text-[9px] font-bold text-primary uppercase tracking-widest">Status Actions</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-if="activeSale.status === 'active'">
                            <button type="button" @click="promptStatusChange('cancelled')" class="px-3 py-1.5 border border-rose-300 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg text-xs transition">Cancel Sale</button>
                        </template>
                        <template x-if="activeSale.status === 'active'">
                            <button type="button" @click="promptStatusChange('returned')" class="px-3 py-1.5 border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold rounded-lg text-xs transition">Mark Returned</button>
                        </template>
                        <template x-if="['active','returned'].includes(activeSale.status)">
                            <button type="button" @click="promptStatusChange('exchanged')" class="px-3 py-1.5 border border-blue-300 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg text-xs transition">Mark Exchanged</button>
                        </template>
                        <template x-if="['cancelled','returned'].includes(activeSale.status)">
                            <button type="button" @click="promptStatusChange('resale')" class="px-3 py-1.5 border border-primary-300 bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold rounded-lg text-xs transition">Mark for Resale</button>
                        </template>
                    </div>
                    <div x-show="statusChange.pending">
                        <input type="text" x-model="statusChange.reason" placeholder="Reason (required)..."
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition mb-2">
                        <div class="flex gap-2">
                            <button type="button" @click="confirmStatusChange()" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg text-xs transition uppercase">Confirm</button>
                            <button type="button" @click="statusChange.pending = false" class="px-3 py-1.5 border border-slate-200 text-slate-600 font-bold rounded-lg text-xs transition uppercase">Cancel</button>
                        </div>
                    </div>
                </div>

                {{-- Status History --}}
                <div class="border-t border-slate-100 pt-4 space-y-2">
                    <p class="text-[9px] font-bold text-primary uppercase tracking-widest">Status History</p>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        <template x-for="log in activeSale.status_logs" :key="log.id">
                            <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100 text-[10px]">
                                <div class="flex justify-between">
                                    <span class="font-bold text-slate-800" x-text="(log.from_status || 'created') + ' → ' + log.to_status"></span>
                                    <span class="text-slate-400" x-text="log.created_at"></span>
                                </div>
                                <p class="text-slate-500 mt-0.5" x-text="log.reason || 'No reason provided'"></p>
                            </div>
                        </template>
                        <template x-if="!activeSale.status_logs || activeSale.status_logs.length === 0">
                            <p class="text-xs text-slate-400 italic">No status changes logged.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW SALE MODAL (read-only)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeViewModal()">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Sale Details</p>
                        <h2 class="text-lg font-extrabold text-white" x-text="activeSale.sale_number"></h2>
                        <span class="badge-pill text-[9px] mt-1 inline-block" :class="getStatusBadgeClass(activeSale.status)" x-text="activeSale.status"></span>
                    </div>
                    <button @click="closeViewModal()" class="text-slate-400 hover:text-white transition">✕</button>
                </div>
            </div>
            <div class="p-6 space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Project</p><p class="text-slate-900 font-semibold" x-text="activeSale.project ? activeSale.project.name : 'N/A'"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Unit</p><p class="text-slate-900 font-semibold" x-text="activeSale.unit ? activeSale.unit.door_no : 'N/A'"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Customer</p><p class="text-slate-900 font-semibold" x-text="activeSale.customer ? activeSale.customer.name : 'N/A'"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Broker</p><p class="text-slate-900 font-semibold" x-text="activeSale.broker ? activeSale.broker.name : 'Direct Sale'"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Sale Amount</p><p class="text-slate-900 font-bold" x-text="'₹' + Number(activeSale.sale_amount || 0).toLocaleString()"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">GST</p><p class="text-slate-900 font-semibold" x-text="activeSale.gst_type && activeSale.gst_type !== 'none' ? '₹' + Number(activeSale.gst_amount).toLocaleString() + ' (' + activeSale.gst_percentage + '%, ' + activeSale.gst_type + ')' : 'Not Applicable'"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Total Amount</p><p class="text-emerald-700 font-bold" x-text="'₹' + Number(activeSale.total_amount || 0).toLocaleString()"></p></div>
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Sale Date</p><p class="text-slate-900 font-semibold" x-text="activeSale.sale_date"></p></div>
                </div>
                <template x-if="activeSale.is_resale">
                    <div class="p-2.5 bg-primary-50 border border-primary-200 rounded-lg text-[10px] text-primary-700 font-semibold">
                        This is a resale linked to original sale #<span x-text="activeSale.original_sale_id"></span>
                    </div>
                </template>
                <template x-if="activeSale.notes">
                    <div><p class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Notes</p><p class="text-slate-600" x-text="activeSale.notes"></p></div>
                </template>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end bg-slate-50">
                <button @click="closeViewModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC
═══════════════════════════════════════════ --}}
<script>
function salesApp() {
    return {
        sales: [],
        filters: { search: '', project_id: '', status: '', date_from: '', date_to: '' },
        modals: { add: { open: false }, edit: { open: false }, view: { open: false }, quickCustomer: { open: false } },
        availableUnits: { add: [], edit: [] },
        selectedUnit: { add: null, edit: null },
        customerList: @json($customers->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'email' => $c->email])),
        quickCustomer: { name: '', email: '', phone: '' },
        quickCustomerErrors: {},
        forms: {
            add: {
                project_id: '', unit_id: '', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                rate_per_sqft: '', sale_amount: '', gst_type: 'none',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_type: 'percentage', brokerage_value: '', brokerage_amount: 0, brokerage_status: 'pending',
                initial_payment_amount: 0, payment_mode: 'Cash', reference_no: '', bank_name: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', remaining_balance: 0,
                notes: ''
            },
            edit: {
                sale_amount: '', sale_date: '', gst_type: 'none',
                gst_percentage: 18, gst_amount: 0, base_amount: '', total_amount: '', notes: ''
            }
        },
        activeSale: {},
        statusChange: { pending: false, targetStatus: '', reason: '' },
        errors: {},
        toast: { open: false, message: '', type: 'success' },

        init() {
            this.fetchSales();
        },

        fetchSales() {
            let params = new URLSearchParams();
            Object.entries(this.filters).forEach(([key, val]) => { if (val) params.append(key, val); });

            fetch('{{ route('sales.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { this.sales = data.sales; })
            .catch(err => { console.error(err); this.showToast('Failed to fetch sales.', 'error'); });
        },

        resetFilters() {
            this.filters = { search: '', project_id: '', status: '', date_from: '', date_to: '' };
            this.fetchSales();
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 'active': return 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                case 'cancelled': return 'bg-rose-50 text-rose-700 border border-rose-100';
                case 'returned': return 'bg-amber-50 text-amber-700 border border-amber-100';
                case 'exchanged': return 'bg-blue-50 text-blue-700 border border-blue-100';
                case 'resale': return 'bg-primary-50 text-primary-700 border border-primary-100';
                default: return 'bg-slate-50 text-slate-700 border border-slate-200';
            }
        },

        showToast(message, type = 'success') {
            this.toast = { open: true, message, type };
            setTimeout(() => { this.toast.open = false; }, 3000);
        },

        loadUnitsForProject(mode) {
            const projectId = this.forms[mode].project_id;
            this.availableUnits[mode] = [];
            this.selectedUnit[mode] = null;
            this.forms[mode].unit_id = '';
            if (!projectId) return;

            fetch(`{{ url('sales/available-units') }}/${projectId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { this.availableUnits[mode] = data.units; })
            .catch(err => console.error(err));
        },

        onUnitSelect(mode) {
            const unit = this.availableUnits[mode].find(u => u.id == this.forms[mode].unit_id);
            this.selectedUnit[mode] = unit || null;
            if (unit) {
                this.forms[mode].rate_per_sqft = unit.expected_rate_per_sqft || '';
                this.onRateChange(mode);
            }
        },

        onRateChange(mode) {
            const unit = this.selectedUnit[mode];
            const rate = parseFloat(this.forms[mode].rate_per_sqft) || 0;
            const area = unit ? parseFloat(unit.built_up_area) || 0 : 0;
            this.forms[mode].sale_amount = Math.round(rate * area * 100) / 100;
            this.recalculateGst(mode);
            this.recalculateBrokerage(mode);
        },

        saleDifference(mode) {
            const unit = this.selectedUnit[mode];
            const expected = unit ? parseFloat(unit.expected_sale_amount) || 0 : 0;
            const agreed = parseFloat(this.forms[mode].sale_amount) || 0;
            return Math.round((agreed - expected) * 100) / 100;
        },

        recalculateBrokerage(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const value = parseFloat(form.brokerage_value) || 0;
            if (!form.broker_involved || !value) {
                form.brokerage_amount = 0;
                return;
            }
            form.brokerage_amount = form.brokerage_type === 'percentage'
                ? Math.round(total * (value / 100) * 100) / 100
                : Math.round(value * 100) / 100;
        },

        recalculateBalance(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const paid = parseFloat(form.initial_payment_amount) || 0;
            form.remaining_balance = Math.round((total - paid) * 100) / 100;
        },

        openQuickAddCustomer() {
            this.quickCustomer = { name: '', email: '', phone: '' };
            this.quickCustomerErrors = {};
            this.modals.quickCustomer.open = true;
        },

        submitQuickCustomer() {
            fetch('{{ route('customers.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.quickCustomer)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.quickCustomerErrors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Failed to add customer.', 'error');
                } else {
                    this.customerList.push({ id: data.customer.id, name: data.customer.name, email: data.customer.email });
                    this.forms.add.customer_id = data.customer.id;
                    this.modals.quickCustomer.open = false;
                    this.showToast('Customer added and selected.');
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },

        recalculateGst(mode) {
            const form = this.forms[mode];
            const entered = parseFloat(form.sale_amount) || 0;

            if (form.gst_type === 'exclusive') {
                // Entered amount is the base price; GST added on top (18%)
                const gst = Math.round(entered * 0.18 * 100) / 100;
                form.base_amount = entered;
                form.gst_amount = gst;
                form.total_amount = Math.round((entered + gst) * 100) / 100;
            } else if (form.gst_type === 'inclusive') {
                // Entered amount already includes 18% GST
                const gst = Math.round(entered * 18 / 118 * 100) / 100;
                form.base_amount = Math.round((entered - gst) * 100) / 100;
                form.gst_amount = gst;
                form.total_amount = entered;
            } else {
                // No GST
                form.base_amount = entered;
                form.gst_amount = 0;
                form.total_amount = entered;
            }

            this.recalculateBrokerage(mode);
            this.recalculateBalance(mode);
        },

        openAddModal() {
            this.errors = {};
            this.availableUnits.add = [];
            this.selectedUnit.add = null;
            this.forms.add = {
                project_id: '', unit_id: '', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                rate_per_sqft: '', sale_amount: '', gst_type: 'none',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_type: 'percentage', brokerage_value: '', brokerage_amount: 0, brokerage_status: 'pending',
                initial_payment_amount: 0, payment_mode: 'Cash', reference_no: '', bank_name: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', remaining_balance: 0,
                notes: ''
            };
            this.modals.add.open = true;
        },
        closeAddModal() { this.modals.add.open = false; },

        submitAddSale() {
            fetch('{{ route('sales.store') }}', {
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
                if (res.status === 422) { this.errors = data.errors || {}; }
                else if (!res.ok) { this.showToast(data.error || 'Server error.', 'error'); }
                else { this.showToast('Sale recorded successfully.'); this.closeAddModal(); this.fetchSales(); }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },

        openEditModal(saleId) {
            this.errors = {};
            fetch(`{{ url('sales') }}/${saleId}/json`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.activeSale = data.sale;
                this.forms.edit = {
                    sale_amount: this.activeSale.sale_amount,
                    sale_date: this.activeSale.sale_date,
                    gst_type: this.activeSale.gst_type || 'none',
                    gst_percentage: this.activeSale.gst_percentage || 18,
                    gst_amount: this.activeSale.gst_amount,
                    base_amount: this.activeSale.base_amount,
                    total_amount: this.activeSale.total_amount,
                    notes: this.activeSale.notes
                };
                this.modals.edit.open = true;
            })
            .catch(err => { console.error(err); this.showToast('Failed to load sale.', 'error'); });
        },
        closeEditModal() { this.modals.edit.open = false; this.statusChange.pending = false; },

        submitEditSale() {
            fetch(`{{ url('sales') }}/${this.activeSale.id}`, {
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
                if (res.status === 422) { this.errors = data.errors || {}; }
                else if (!res.ok) { this.showToast(data.error || 'Server error.', 'error'); }
                else { this.showToast('Sale updated successfully.'); this.fetchSales(); this.openEditModal(this.activeSale.id); }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },

        promptStatusChange(targetStatus) {
            this.statusChange = { pending: true, targetStatus, reason: '' };
        },
        confirmStatusChange() {
            if (!this.statusChange.reason) {
                this.showToast('A reason is required.', 'error');
                return;
            }
            fetch(`{{ url('sales') }}/${this.activeSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: this.statusChange.targetStatus, reason: this.statusChange.reason })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) { this.showToast(data.error || 'Failed to update status.', 'error'); }
                else {
                    this.showToast(`Sale marked as ${this.statusChange.targetStatus}.`);
                    this.statusChange.pending = false;
                    this.fetchSales();
                    this.openEditModal(this.activeSale.id);
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },

        openViewModal(saleId) {
            fetch(`{{ url('sales') }}/${saleId}/json`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { this.activeSale = data.sale; this.modals.view.open = true; })
            .catch(err => { console.error(err); this.showToast('Failed to load sale.', 'error'); });
        },
        closeViewModal() { this.modals.view.open = false; }
    };
}
</script>

</x-erp-layout>
