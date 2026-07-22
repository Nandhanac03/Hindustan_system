@php
    $isReturnExchange = request('tab') === 'returns' || request('tab') === 'cancellations' || request('tab') === 'exchange' || request('tab') === 'sale-return';
    $pageTitle = request('tab') === 'exchange' ? 'Unit Exchange Operations' : (request('tab') === 'cancellations' ? 'Sales Cancellations' : (request('tab') === 'sale-return' ? 'Sales Return / Cancellation' : ($isReturnExchange ? 'Sales Returns' : 'Sales Register')));
@endphp
<x-erp-layout :title="$pageTitle" :headerTitle="$pageTitle">
<div class="max-w-[1800px] mx-auto space-y-6" x-data="salesApp()">
    {{-- Toast --}}
    <div x-show="toast.open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-[100] p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>
    @if($isReturnExchange)
        @include('sales.return_exchange_operations')
    @else
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
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-center font-bold text-slate-650 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Sale No</th>
                        <th class="px-4 py-3.5 text-left text-slate-500 font-bold border-b">Project / Unit</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Customer</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Broker</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Sale Amount</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">GST</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Total</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Sale Date</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b">Status</th>
                        <th class="px-4 py-3.5 text-slate-500 font-bold border-b text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <template x-for="sale in sales" :key="sale.id">
                        <tr class="hover:bg-slate-50/50 transition-colors text-center text-xs font-semibold text-slate-700">
                            <td class="px-4 py-4 font-bold text-slate-900 border-b border-slate-100" x-text="sale.sale_number"></td>
                            <td class="px-4 py-4 text-left border-b border-slate-100">
                                <div class="font-bold text-slate-800" x-text="sale.project ? sale.project.name : 'N/A'"></div>
                                <div class="text-[10px] text-slate-400 mt-0.5" x-text="sale.sale_units && sale.sale_units.length ? sale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (sale.unit ? sale.unit.door_no : '')"></div>
                            </td>
                            <td class="px-4 py-4 text-slate-600 border-b border-slate-100" x-text="sale.customer ? sale.customer.name : 'N/A'"></td>
                            <td class="px-4 py-4 text-slate-500 border-b border-slate-100" x-text="sale.broker ? sale.broker.name : '—'"></td>
                            <td class="px-4 py-4 font-bold text-slate-900 border-b border-slate-100" x-text="'₹' + Number(sale.sale_amount).toLocaleString()"></td>
                            <td class="px-4 py-4 border-b border-slate-100">
                                <span x-show="sale.gst_amount > 0" x-text="'₹' + Number(sale.gst_amount).toLocaleString()"></span>
                                <span x-show="!sale.gst_amount || sale.gst_amount == 0" class="text-slate-400">N/A</span>
                            </td>
                            <td class="px-4 py-4 font-bold text-emerald-700 border-b border-slate-100" x-text="'₹' + Number(sale.total_amount).toLocaleString()"></td>
                            <td class="px-4 py-4 text-slate-500 border-b border-slate-100" x-text="formatDate(sale.sale_date)"></td>
                            <td class="px-4 py-4 border-b border-slate-100">
                                <span class="badge-pill" :class="getStatusBadgeClass(sale.status)" x-text="sale.status"></span>
                            </td>
                            <td class="px-4 py-4 text-right border-b border-slate-100">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal(sale.id)" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Sale">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal(sale.id)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Sale">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="sales.length === 0">
                        <td colspan="10" class="px-6 py-10 text-center text-slate-400 italic bg-white">No sales match the query filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- ═══════════════════════════════════════════
         ADD SALE MODAL — 6 Sections with Repeatable Alpine Rows
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="if (!modals.quickCustomer.open) closeAddModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add New Sale (Multi-Unit Contract)</h3>
                <button @click="closeAddModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitAddSale()">
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    {{-- ── Section 1 — Basics ── --}}
                    <div class="grid grid-cols-3 gap-4">
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
                        <div class="space-y-1.5 col-span-2">
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
                    {{-- ── Section 2 — Repeatable Units / Line Items ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-primary uppercase tracking-widest">🏢 Booked Inventory / Units</p>
                            <button type="button" @click="addUnitRow()"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Unit Row
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(row, index) in forms.add.units" :key="index">
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 relative">
                                    <button type="button" @click="removeUnitRow(index)" x-show="forms.add.units.length > 1"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-xs">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit *</label>
                                            <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                                <!-- Trigger Button -->
                                                <button type="button" @click="open = !open" :disabled="!forms.add.project_id"
                                                        class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 text-left flex justify-between items-center h-8">
                                                    <span x-text="row.unit_id ? (availableUnits.add.find(u => u.id == row.unit_id) ? (availableUnits.add.find(u => u.id == row.unit_id).floor_name + ' — ' + availableUnits.add.find(u => u.id == row.unit_id).door_no) : '— Select Unit —') : '— Select Unit —'"></span>
                                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                                <!-- Dropdown Content -->
                                                <div x-show="open" x-transition
                                                     class="absolute z-50 w-64 mt-1 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-72 flex flex-col">
                                                    <!-- Search Input -->
                                                    <div class="p-2 border-b border-slate-100 bg-slate-50 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                                                        <input type="text" x-model="search" placeholder="Search floor or unit..."
                                                               class="w-full py-1 text-xs border-0 bg-transparent focus:outline-none focus:ring-0" x-ref="searchInput">
                                                    </div>
                                                    <!-- Clear option -->
                                                    <button type="button" @click="row.unit_id = ''; onRowUnitSelect(index); open = false; search = ''"
                                                            class="w-full px-3 py-2 text-left text-xs text-slate-400 hover:bg-slate-50 border-b border-slate-100 italic">
                                                        — Clear Selection —
                                                    </button>
                                                    <!-- Grouped Options List -->
                                                    <div class="overflow-y-auto flex-1">
                                                        <template x-for="floorGroup in getFloorGroups('add', search)" :key="floorGroup.floor">
                                                            <div>
                                                                <!-- Floor Header -->
                                                                <div class="px-3 py-1 bg-slate-100 text-[9px] font-bold uppercase tracking-widest text-slate-500" x-text="floorGroup.floor"></div>
                                                                <!-- Units in this floor -->
                                                                <template x-for="unit in floorGroup.units" :key="unit.id">
                                                                    <button type="button"
                                                                            @click="row.unit_id = unit.id; onRowUnitSelect(index); open = false; search = ''"
                                                                            :disabled="forms.add.units.some((r, i) => i !== index && r.unit_id == unit.id)"
                                                                            class="w-full px-4 py-2 text-left text-xs hover:bg-primary/5 transition-colors disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-between gap-2"
                                                                            :class="row.unit_id == unit.id ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700'">
                                                                        <span class="font-semibold" x-text="unit.door_no"></span>
                                                                        <span class="text-[9px] text-slate-400 font-mono" x-text="unit.unit_type_name"></span>
                                                                    </button>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <!-- No results -->
                                                        <div x-show="getFloorGroups('add', search).length === 0"
                                                             class="px-4 py-6 text-center text-xs text-slate-400">
                                                            No units match your search.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built Up Area (Sq Ft)</label>
                                            <div class="w-full px-2.5 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 font-bold h-9 flex items-center">
                                                <span x-text="onGetRowArea(index) + ' Sq Ft'"></span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate/Sqft *</label>
                                            <input type="number" step="0.01" x-model="row.rate_per_sqft" @input="onRowRateChange(index)" placeholder="Expected rate"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreed Sale Amount *</label>
                                            <input type="number" step="0.01" x-model="row.sale_amount" @input="recalculateRowGst(index)" placeholder="Base Amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Percentage (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateRowGst(index)" placeholder="e.g. 18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">GST Amount</p>
                                            <p class="font-bold text-slate-800 mt-1 font-mono" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Payable</p>
                                            <p class="font-extrabold text-indigo-700 mt-1 font-mono" x-text="'₹' + Number(row.total_amount || 0).toLocaleString()"></p>
                                        </div>
                                        {{-- Row-level commission details --}}
                                        <!-- <div class="flex items-center gap-2 h-9" x-show="forms.add.broker_involved">
                                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-650 cursor-pointer">
                                                <input type="checkbox" x-model="row.broker_involved" @change="recalculateRowBrokerage(index)" class="rounded text-primary focus:ring-primary/20">
                                                <span>Commission Row?</span>
                                            </label>
                                        </div> -->
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50" x-show="forms.add.broker_involved && row.broker_involved">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                            <select x-model="row.brokerage_type" @change="recalculateRowBrokerage(index)"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="fixed">Fixed (₹)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                            <input type="number" step="0.01" x-model="row.brokerage_value" @input="recalculateRowBrokerage(index)" placeholder="Value"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Brokerage Amount</p>
                                            <p class="font-bold text-slate-800 mt-1 font-mono" x-text="'₹' + Number(row.brokerage_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Section 3 — Broker / Commission ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                            <input type="checkbox" x-model="forms.add.broker_involved" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">Broker / Commission — A broker is involved in this sale</span>
                        </label>
                        <div x-show="forms.add.broker_involved" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker</label>
                                    <select x-model="forms.add.broker_id" @change="onBrokerSelect('add')"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <option value="">— Select Broker —</option>
                                        @foreach($brokers as $broker)
                                            <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                        @endforeach
                                    </select>
                                    <template x-if="errors.broker_id"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.broker_id[0]"></p></template>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                    <div class="flex items-center gap-4 h-9">
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="percentage" x-model="forms.add.brokerage_type" @change="onBrokerageTypeChange('add')" class="text-primary focus:ring-primary/20">
                                            <span>Percentage (%)</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 cursor-pointer">
                                            <input type="radio" value="fixed" x-model="forms.add.brokerage_type" @change="onBrokerageTypeChange('add')" class="text-primary focus:ring-primary/20">
                                            <span>Fixed (₹)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                    <input type="number" step="0.01" x-model="forms.add.brokerage_value" @input="recalculateAllTotals('add')" placeholder="e.g. 2 for 2%"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                <div class="space-y-1.5">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brokerage Amount</p>
                                    <p class="font-bold text-slate-900 leading-9 font-mono" x-text="'₹' + Number(forms.add.brokerage_amount || 0).toLocaleString()"></p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Status</label>
                                    <select x-model="forms.add.brokerage_status"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Custom Alterations / Extra Work (add) ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-primary uppercase tracking-widest">🛠️ Custom Alterations / Extra Work</p>
                            <button type="button" @click="addExtraWorkRow('add')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Extra Work
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(row, index) in forms.add.extra_works" :key="index">
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 relative">
                                    <button type="button" @click="removeExtraWorkRow(index, 'add')"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-xs">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                                        <div class="space-y-1.5 sm:col-span-2">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Description / Work Details *</label>
                                            <input type="text" x-model="row.description" placeholder="e.g. Flooring Upgrade, Custom Fittings"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Amount (₹) *</label>
                                            <input type="number" step="0.01" x-model="row.amount" @input="recalculateExtraWorkRowGst(index, 'add')" placeholder="Enter amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Type</label>
                                            <select x-model="row.gst_type" @change="recalculateExtraWorkRowGst(index, 'add')"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                                <option value="none">None</option>
                                                <option value="exclusive">Exclusive</option>
                                                <option value="inclusive">Inclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5" x-show="row.gst_type !== 'none'">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateExtraWorkRowGst(index, 'add')" placeholder="18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div></div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-455 uppercase tracking-wider block font-bold text-emerald-800">GST Amount</p>
                                            <p class="font-bold text-slate-900 leading-9 font-mono" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-455 uppercase tracking-wider block font-bold text-emerald-800">Total Payable</p>
                                            <p class="font-bold text-emerald-800 leading-9 font-mono" x-text="'₹' + Number(row.line_total || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- Aggregated Contract Summary --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 grid grid-cols-3 gap-4 text-center">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total SALE Amount</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.add.base_amount || 0).toLocaleString()"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total GST Amount</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.add.gst_amount || 0).toLocaleString()"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Contract Value</span>
                            <span class="font-extrabold text-[#a38c29] text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.add.total_amount || 0).toLocaleString()"></span>
                        </div>
                    </div>
                    {{-- ── Section 4 — Initial Payment ── --}}
                    <div class="border-t border-slate-100 pt-5 mt-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm shadow-sm border border-primary/20">
                                💼
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Initial Payment</h3>
                                <p class="text-[9px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Record the first payment details for this contract</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-5 items-start bg-slate-50 border border-slate-100 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5 flex-1 w-full">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Initial Payment Amount & %</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" x-model="forms.add.initial_payment_amount" @input="updateInitialPaymentFromAmount('add')" placeholder="Amount (₹)"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                    <div>
                                        <input type="number" step="0.01" min="0" max="100" x-model="forms.add.initial_payment_percentage" @input="updateInitialPaymentFromPercentage('add')" placeholder="Percentage (%)"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                </div>
                                <p class="text-[9px] text-slate-400 font-medium">Enter amount or percentage (0 if none)</p>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Mode</label>
                                <select x-model="forms.add.payment_mode"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Date</label>
                                <input type="date" x-model="forms.add.initial_payment_date"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div x-show="['Bank Transfer', 'Cheque'].includes(forms.add.payment_mode)" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 bg-slate-50 border border-slate-100 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Reference / Cheque No</label>
                                <input type="text" x-model="forms.add.reference_no" placeholder="e.g. UTR / Cheque number"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Bank Name</label>
                                <select x-model="forms.add.bank_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="">Select Bank Account</option>
                                    <template x-for="bank in bankAccountsList" :key="bank.id">
                                        <option :value="bank.id" x-text="bank.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 5 — Balance & Payment Plan ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">📊 Balance & Payment Plan</p>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</p>
                                <p class="text-lg font-extrabold text-primary" x-text="'₹' + Number(forms.add.remaining_balance || 0).toLocaleString()"></p>
                            </div>
                            <div class="space-y-1.5 col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Plan</label>
                                <div class="flex items-center gap-4 h-9">
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="lump_sum" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        Lump Sum (Full payment)
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="emi" x-model="forms.add.payment_plan" class="text-primary focus:ring-primary/20">
                                        EMI / Installment Plan
                                    </label>
                                </div>
                                <div x-show="forms.add.payment_plan === 'emi'" class="mt-4 space-y-4" x-transition>
                                    {{-- Equal Installments Fields --}}
                                    <div class="grid grid-cols-3 gap-3 border border-slate-100 p-3 rounded-xl bg-slate-50/50">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">No. of Installments</label>
                                            <input type="number" x-model="forms.add.emi_installment_count" min="1" placeholder="e.g. 12"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">Frequency</label>
                                            <select x-model="forms.add.emi_frequency"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                                <option value="monthly">Monthly</option>
                                                <!-- <option value="quarterly">Quarterly</option> -->
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">First Installment Date</label>
                                            <input type="date" x-model="forms.add.first_installment_date"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        </div>
                                    </div>
                                    {{-- Live Preview Block --}}
                                    <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-xl p-3 space-y-2">
                                        <p class="text-[9px] font-bold text-indigo-800 uppercase tracking-widest">📝 Live Schedule Preview</p>
                                        <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-[11px] font-semibold text-slate-700">
                                            <template x-for="(preview, pIdx) in getEmiPreview()" :key="pIdx">
                                                <div class="flex justify-between items-center py-1 border-b border-indigo-100/30">
                                                    <span x-text="preview.label"></span>
                                                    <div class="flex gap-4">
                                                        <span class="text-slate-400 font-mono text-[10px]" x-text="preview.due_date"></span>
                                                        <span class="font-bold text-indigo-700 font-mono" x-text="'₹' + Number(preview.amount).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="getEmiPreview().length === 0">
                                                <p class="text-[10px] text-slate-455 italic py-1 text-center">No schedule preview available. Fill EMI parameters.</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 6 — Remarks ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">💬 Remarks</p>
                        <textarea x-model="forms.add.notes" rows="3" placeholder="Optional remarks or notes..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Create Sale</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         QUICK ADD CUSTOMER MODAL (nested)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.quickCustomer.open" @click.self.stop="modals.quickCustomer.open = false" class="fixed inset-0 z-[60] flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.stop>
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
                    <button type="button" @click="modals.quickCustomer.open = false" class="px-4 py-2 border border-slate-200 text-slate-650 text-xs font-bold rounded-xl uppercase">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl uppercase">Add & Select</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         EDIT SALE MODAL (legacy single-unit fields kept for backward-compatible edits)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeEditModal()">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Edit Sale — <span x-text="activeSale.sale_number"></span></h3>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <form @submit.prevent="submitEditSale()">
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto font-sans">
                    {{-- ── Section 1 — Basics (Read-Only) ── --}}
                    <div class="grid grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Project</label>
                            <span class="font-bold text-slate-800 block mt-0.5" x-text="activeSale.project ? activeSale.project.name : '—'"></span>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Unit</label>
                            <span class="font-bold text-slate-800 block mt-0.5" x-text="activeSale.sale_units && activeSale.sale_units.length ? activeSale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (activeSale.unit ? activeSale.unit.door_no : '—')"></span>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Customer</label>
                            <span class="font-bold text-slate-800 block mt-0.5" x-text="activeSale.customer ? activeSale.customer.name : '—'"></span>
                        </div>
                    </div>
                    {{-- ── Section 2 — Repeatable Units / Line Items ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-primary uppercase tracking-widest">🏢 Booked Inventory / Units</p>
                            <button type="button" @click="addUnitRow('edit')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Unit Row
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(row, index) in forms.edit.units" :key="index">
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 relative">
                                    <button type="button" @click="removeUnitRow(index, 'edit')" x-show="forms.edit.units.length > 1"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-xs">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit *</label>
                                            <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                                                <!-- Trigger Button -->
                                                <button type="button" @click="open = !open" :disabled="!forms.edit.project_id"
                                                        class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all disabled:opacity-50 text-left flex justify-between items-center h-8">
                                                    <span x-text="row.unit_id ? (availableUnits.edit.find(u => u.id == row.unit_id) ? (availableUnits.edit.find(u => u.id == row.unit_id).floor_name + ' — ' + availableUnits.edit.find(u => u.id == row.unit_id).door_no) : '— Select Unit —') : '— Select Unit —'"></span>
                                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                                <!-- Dropdown Content -->
                                                <div x-show="open" x-transition
                                                     class="absolute z-50 w-64 mt-1 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden max-h-72 flex flex-col">
                                                    <!-- Search Input -->
                                                    <div class="p-2 border-b border-slate-100 bg-slate-50 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                                                        <input type="text" x-model="search" placeholder="Search floor or unit..."
                                                               class="w-full py-1 text-xs border-0 bg-transparent focus:outline-none focus:ring-0">
                                                    </div>
                                                    <!-- Clear option -->
                                                    <button type="button" @click="row.unit_id = ''; onRowUnitSelect(index, 'edit'); open = false; search = ''"
                                                            class="w-full px-3 py-2 text-left text-xs text-slate-400 hover:bg-slate-50 border-b border-slate-100 italic">
                                                        — Clear Selection —
                                                    </button>
                                                    <!-- Grouped Options List -->
                                                    <div class="overflow-y-auto flex-1">
                                                        <template x-for="floorGroup in getFloorGroups('edit', search)" :key="floorGroup.floor">
                                                            <div>
                                                                <!-- Floor Header -->
                                                                <div class="px-3 py-1 bg-slate-100 text-[9px] font-bold uppercase tracking-widest text-slate-500" x-text="floorGroup.floor"></div>
                                                                <!-- Units in this floor -->
                                                                <template x-for="unit in floorGroup.units" :key="unit.id">
                                                                    <button type="button"
                                                                            @click="row.unit_id = unit.id; onRowUnitSelect(index, 'edit'); open = false; search = ''"
                                                                            :disabled="forms.edit.units.some((r, i) => i !== index && r.unit_id == unit.id)"
                                                                            class="w-full px-4 py-2 text-left text-xs hover:bg-primary/5 transition-colors disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-between gap-2"
                                                                            :class="row.unit_id == unit.id ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700'">
                                                                        <span class="font-semibold" x-text="unit.door_no"></span>
                                                                        <span class="text-[9px] text-slate-400 font-mono" x-text="unit.unit_type_name"></span>
                                                                    </button>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <!-- No results -->
                                                        <div x-show="getFloorGroups('edit', search).length === 0"
                                                             class="px-4 py-6 text-center text-xs text-slate-400">
                                                            No units match your search.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built Up Area (Sq Ft)</label>
                                            <div class="w-full px-2.5 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 font-bold h-9 flex items-center">
                                                <span x-text="onGetRowArea(index, 'edit') + ' Sq Ft'"></span>
                                            </div>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate/Sqft *</label>
                                            <input type="number" step="0.01" x-model="row.rate_per_sqft" @input="onRowRateChange(index, 'edit')" placeholder="Expected rate"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreed Sale Amount *</label>
                                            <input type="number" step="0.01" x-model="row.sale_amount" @input="recalculateRowGst(index, 'edit')" placeholder="Base Amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Percentage (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateRowGst(index, 'edit')" placeholder="e.g. 18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">GST Amount</p>
                                            <p class="font-bold text-slate-900 leading-9 font-mono" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-455 uppercase tracking-wider block font-bold text-emerald-800">Total Payable</p>
                                            <p class="font-bold text-emerald-800 leading-9 font-mono" x-text="'₹' + Number(row.total_amount || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Custom Alterations / Extra Work (edit) ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-primary uppercase tracking-widest">🛠️ Custom Alterations / Extra Work</p>
                            <button type="button" @click="addExtraWorkRow('edit')"
                                    class="px-2.5 py-1 bg-primary hover:bg-primary-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow-sm">
                                + Add Extra Work
                            </button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(row, index) in forms.edit.extra_works" :key="index">
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3 relative">
                                    <button type="button" @click="removeExtraWorkRow(index, 'edit')"
                                            class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 font-bold text-xs">✕ Remove</button>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                                        <div class="space-y-1.5 sm:col-span-2">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Description / Work Details *</label>
                                            <input type="text" x-model="row.description" placeholder="e.g. Flooring Upgrade, Custom Fittings"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Amount (₹) *</label>
                                            <input type="number" step="0.01" x-model="row.amount" @input="recalculateExtraWorkRowGst(index, 'edit')" placeholder="Enter amount"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all font-mono">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST Type</label>
                                            <select x-model="row.gst_type" @change="recalculateExtraWorkRowGst(index, 'edit')"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                                <option value="none">None</option>
                                                <option value="exclusive">Exclusive</option>
                                                <option value="inclusive">Inclusive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end pt-2 border-t border-slate-200/50">
                                        <div class="space-y-1.5" x-show="row.gst_type !== 'none'">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">GST (%)</label>
                                            <input type="number" step="0.01" x-model="row.gst_percentage" @input="recalculateExtraWorkRowGst(index, 'edit')" placeholder="18"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                        </div>
                                        <div></div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-455 uppercase tracking-wider block font-bold text-emerald-800">GST Amount</p>
                                            <p class="font-bold text-slate-900 leading-9 font-mono" x-text="'₹' + Number(row.gst_amount || 0).toLocaleString()"></p>
                                        </div>
                                        <div class="space-y-1.5">
                                            <p class="text-[10px] font-bold text-slate-455 uppercase tracking-wider block font-bold text-emerald-800">Total Payable</p>
                                            <p class="font-bold text-emerald-800 leading-9 font-mono" x-text="'₹' + Number(row.line_total || 0).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- ── Section 3 — Pricing & Contract Totals Summary ── --}}
                    <div class="grid grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Sale Amount</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.edit.base_amount || 0).toLocaleString()"></span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total GST Amount</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.edit.gst_amount || 0).toLocaleString()"></span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Contract Value</span>
                            <span class="font-extrabold text-[#a38c29] text-sm mt-1 block font-mono" x-text="'₹' + Number(forms.edit.total_amount || 0).toLocaleString()"></span>
                        </div>
                    </div>
                    {{-- ── Section 3 — Broker / Commission ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                            <input type="checkbox" x-model="forms.edit.broker_involved" class="rounded text-primary focus:ring-primary/20">
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">Broker / Commission — A broker is involved in this sale</span>
                        </label>
                        <div x-show="forms.edit.broker_involved" class="grid grid-cols-3 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker</label>
                                <select x-model="forms.edit.broker_id" @change="onBrokerSelect('edit')"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="">— Select Broker —</option>
                                    @foreach($brokers as $broker)
                                        <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Type</label>
                                <div class="flex items-center gap-4 h-9">
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="percentage" x-model="forms.edit.brokerage_type" @change="onBrokerageTypeChange('edit')" class="text-primary focus:ring-primary/20">
                                        Percentage (%)
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="fixed" x-model="forms.edit.brokerage_type" @change="onBrokerageTypeChange('edit')" class="text-primary focus:ring-primary/20">
                                        Fixed (₹)
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Value</label>
                                <input type="number" step="0.01" x-model="forms.edit.brokerage_value"  @input="recalculateBrokerage('edit')"
                                       :placeholder="forms.edit.brokerage_type === 'fixed' ? '0' : 'e.g. 2 for 2%'"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Brokerage Amount</p>
                                <p class="font-bold text-slate-900 leading-9 font-mono" x-text="'₹' + Number(forms.edit.brokerage_amount || 0).toLocaleString()"></p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Brokerage Status</label>
                                <select x-model="forms.edit.brokerage_status"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 4 — Dates ── --}}
                    <div class="border-t border-slate-100 pt-4 grid grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Agreement / Sale Date *</label>
                            <input type="date" x-model="forms.edit.sale_date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Registration Date</label>
                            <input type="date" x-model="forms.edit.registration_date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                        </div>
                    </div>
                    {{-- ── Section 5 — Initial Payment ── --}}
                    <div class="border-t border-slate-100 pt-5 mt-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm shadow-sm border border-primary/20">
                                💼
                            </div>
                            <div>
                                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Initial Payment</h3>
                                <p class="text-[9px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Record the first payment details for this contract</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-5 items-start bg-slate-50 border border-slate-100 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5 flex-1 w-full">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Initial Payment Amount & %</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" step="0.01" x-model="forms.edit.initial_payment_amount" @input="updateInitialPaymentFromAmount('edit')" placeholder="Amount (₹)"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                    <div>
                                        <input type="number" step="0.01" min="0" max="100" x-model="forms.edit.initial_payment_percentage" @input="updateInitialPaymentFromPercentage('edit')" placeholder="Percentage (%)"
                                               class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm font-mono">
                                    </div>
                                </div>
                                <p class="text-[9px] text-slate-400 font-medium">Enter amount or percentage (0 if none)</p>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Mode</label>
                                <select x-model="forms.edit.payment_mode"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>
                            <div class="space-y-1.5 w-full md:w-40">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Payment Date</label>
                                <input type="date" x-model="forms.edit.initial_payment_date"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div x-show="['Bank Transfer', 'Cheque'].includes(forms.edit.payment_mode)" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 bg-slate-50 border border-slate-100 rounded-xl p-4 shadow-sm">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Reference / Cheque No</label>
                                <input type="text" x-model="forms.edit.reference_no" placeholder="e.g. UTR / Cheque number"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Bank Name</label>
                                <select x-model="forms.edit.bank_id"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all shadow-sm">
                                    <option value="">Select Bank Account</option>
                                    <template x-for="bank in bankAccountsList" :key="bank.id">
                                        <option :value="bank.id" x-text="bank.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 6 — Balance & Payment Plan ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">📊 Balance & Payment Plan</p>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</p>
                                <p class="text-lg font-extrabold text-primary font-mono" x-text="'₹' + Number(forms.edit.remaining_balance || 0).toLocaleString()"></p>
                            </div>
                            <div class="space-y-1.5 col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Plan</label>
                                <div class="flex items-center gap-4 h-9">
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="lump_sum" x-model="forms.edit.payment_plan" class="text-primary focus:ring-primary/20">
                                        Lump Sum (Full payment)
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                        <input type="radio" value="emi" x-model="forms.edit.payment_plan" class="text-primary focus:ring-primary/20">
                                        EMI / Installment Plan
                                    </label>
                                </div>
                                <div x-show="forms.edit.payment_plan === 'emi'" class="mt-4 space-y-4" x-transition>
                                    {{-- Equal Installments Fields --}}
                                    <div class="grid grid-cols-3 gap-3 border border-slate-100 p-3 rounded-xl bg-slate-50/50">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">No. of Installments</label>
                                            <input type="number" x-model="forms.edit.emi_installment_count" min="1" placeholder="e.g. 12"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">Frequency</label>
                                            <select x-model="forms.edit.emi_frequency"
                                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                                <option value="monthly">Monthly</option>
                                                <!-- <option value="quarterly">Quarterly</option> -->
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-slate-400 uppercase block">First Installment Date</label>
                                            <input type="date" x-model="forms.edit.first_installment_date"
                                                   class="w-full px-2.5 py-1.5 bg-white border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                        </div>
                                    </div>
                                    {{-- Live Preview Block --}}
                                    <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-xl p-3 space-y-2">
                                        <p class="text-[9px] font-bold text-indigo-800 uppercase tracking-widest">📝 Live Schedule Preview</p>
                                        <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 text-[11px] font-semibold text-slate-700">
                                            <template x-for="(preview, pIdx) in getEmiPreview('edit')" :key="pIdx">
                                                <div class="flex justify-between items-center py-1 border-b border-indigo-100/30">
                                                    <span x-text="preview.label"></span>
                                                    <div class="flex gap-4">
                                                        <span class="text-slate-400 font-mono text-[10px]" x-text="preview.due_date"></span>
                                                        <span class="font-bold text-indigo-700 font-mono" x-text="'₹' + Number(preview.amount).toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="getEmiPreview('edit').length === 0">
                                                <p class="text-[10px] text-slate-455 italic py-1 text-center">No schedule preview available. Fill EMI parameters.</p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Section 7 — Remarks ── --}}
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">💬 Remarks</p>
                        <textarea x-model="forms.edit.notes" rows="3" placeholder="Optional remarks or notes..."
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 bg-slate-50">
                    <button type="button" @click="closeEditModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════
         VIEW SALE MODAL (read-only)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeViewModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Agreement Details</span>
                            <span class="badge-pill text-[9px] whitespace-nowrap" :class="getStatusBadgeClass(activeSale.status)" x-text="activeSale.status"></span>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight truncate break-all" x-text="activeSale.sale_number"></h2>
                    </div>
                    <button @click="closeViewModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            {{-- Scrollable Container --}}
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                {{-- Row 1: Sale Profile --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Project Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Project Name</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.project ? activeSale.project.name : '—'"></strong>
                        </div>
                    </div>
                    {{-- Unit Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Unit & Floor</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.sale_units && activeSale.sale_units.length ? activeSale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (activeSale.unit ? activeSale.unit.door_no + ' — ' + (activeSale.unit.floor ? activeSale.unit.floor.name : '') : '—')"></strong>
                        </div>
                    </div>
                    {{-- Customer Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Customer Details</span>
                            <strong class="text-slate-800 text-xs block mt-1 truncate" x-text="activeSale.customer ? activeSale.customer.name : '—'"></strong>
                            <span class="text-slate-450 text-[10px] block mt-0.5 truncate" x-text="activeSale.customer ? activeSale.customer.phone : ''"></span>
                        </div>
                    </div>
                </div>
                {{-- Multi Unit Details Table --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3" x-show="activeSale.sale_units && activeSale.sale_units.length > 0">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">🏢 Booked Inventory / Units</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[11px] border-collapse min-w-[500px]">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="py-2 px-2">Unit</th>
                                    <th class="py-2 px-2">Floor</th>
                                    <th class="py-2 px-2">Area (Sq.Ft)</th>
                                    <th class="py-2 px-2">Rate/Sq.Ft</th>
                                    <th class="py-2 px-2">GST</th>
                                    <th class="py-2 px-2 text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                <template x-for="su in activeSale.sale_units" :key="su.id">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-2 px-2 font-bold text-indigo-700" x-text="su.unit ? su.unit.door_no : '—'"></td>
                                        <td class="py-2 px-2" x-text="su.unit && su.unit.floor ? su.unit.floor.name : '—'"></td>
                                        <td class="py-2 px-2 font-mono" x-text="Number(su.area_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 font-mono" x-text="'₹' + Number(su.rate_per_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 whitespace-nowrap" x-text="su.gst_type !== 'none' ? '₹' + Number(su.gst_amount).toLocaleString() + ' (' + su.gst_type + ')' : 'None'"></td>
                                        <td class="py-2 px-2 text-right font-mono text-slate-900" x-text="'₹' + Number(su.line_total).toLocaleString()"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 1.5: Extra Works Details --}}
                <template x-if="activeSale.extra_works && activeSale.extra_works.length > 0">
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                        <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">🛠️ Custom Alterations / Extra Work Details</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-455 uppercase tracking-wider border-b border-slate-100">
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                        <th class="px-4 py-3">GST Type</th>
                                        <th class="px-4 py-3">GST (%)</th>
                                        <th class="px-4 py-3 text-right">GST Amount</th>
                                        <th class="px-4 py-3 text-right font-bold text-emerald-800">Total Payable</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                    <template x-for="ew in activeSale.extra_works" :key="ew.id">
                                        <tr class="hover:bg-slate-55/40 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-900" x-text="ew.description"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 uppercase" x-text="ew.gst_type"></td>
                                            <td class="px-4 py-3" x-text="ew.gst_percentage + '%'"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.gst_amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 text-right font-mono text-emerald-800 font-bold" x-text="'₹' + Number(ew.line_total).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
                {{-- Row 2: Financial Summary Card --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💰 Pricing & GST Breakdown</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreed Rate / Sqft</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="activeSale.rate_per_sqft > 0 ? '₹' + Number(activeSale.rate_per_sqft).toLocaleString() : '₹0 (Flat Price)'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Base Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block font-mono" x-text="activeSale.base_amount ? '₹' + Number(activeSale.base_amount).toLocaleString() : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total GST Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block" x-text="activeSale.gst_amount > 0 ? '₹' + Number(activeSale.gst_amount || 0).toLocaleString() : 'None / Excluded'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Contract Value</span>
                            <span class="font-extrabold text-[#a38c29] text-base mt-1 block font-mono" x-text="'₹' + Number(activeSale.total_amount || 0).toLocaleString()"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100 bg-slate-50/50 -mx-5 -mb-5 p-5 rounded-b-xl">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreement Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatDate(activeSale.sale_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Registration Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatDate(activeSale.registration_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Selected Plan</span>
                            <span class="font-extrabold text-indigo-600 mt-1 block uppercase" x-text="activeSale.payment_plan === 'emi' ? 'EMI (' + (activeSale.emi_installment_count || 12) + '-Mo ' + (activeSale.emi_frequency || 'Monthly') + ')' : 'Lump Sum'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Remaining Balance</span>
                            <span class="font-extrabold text-sm mt-1 block font-mono" :class="activeSale.remaining_balance > 0 ? 'text-rose-600' : 'text-emerald-700'" x-text="'₹' + Number(activeSale.remaining_balance || 0).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
                {{-- Row 3: Receipts Ledger --}}
                <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">💳 Collection Receipts History</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-450 uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-3">Receipt No</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Payment Mode</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="r in activeSale.receipts" :key="r.id">
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-4 py-3 font-bold text-slate-900" x-text="'REC-' + String(r.id).padStart(5, '0') + (r.reference_no ? ' (' + r.reference_no + ')' : '')"></td>
                                        <td class="px-4 py-3 text-slate-500" x-text="formatDate(r.receipt_date)"></td>
                                        <td class="px-4 py-3 text-slate-500 uppercase" x-text="r.payment_mode"></td>
                                        <td class="px-4 py-3 text-right font-extrabold text-emerald-700 font-mono" x-text="'₹' + Number(r.amount).toLocaleString()"></td>
                                    </tr>
                                </template>
                                <template x-if="!activeSale.receipts || activeSale.receipts.length === 0">
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-400 italic bg-white">No receipts recorded for this sale.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 4: Broker details --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">💼 Broker & Commission Details</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="activeSale.brokerage">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Broker Name</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="activeSale.broker ? activeSale.broker.name : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Commission Structure</span>
                            <span class="font-bold text-slate-855 mt-1 block" x-text="activeSale.brokerage ? (activeSale.brokerage.commission_type === 'percentage' ? activeSale.brokerage.commission_percent + '% of Sale Price' : 'Fixed Commission') : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Payout Amount / Status</span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="font-extrabold text-slate-900 font-mono" x-text="activeSale.brokerage ? '₹' + Number(activeSale.brokerage.commission_amount).toLocaleString() : '—'"></span>
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider inline-block"
                                      :class="activeSale.brokerage && activeSale.brokerage.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                      x-text="activeSale.brokerage ? activeSale.brokerage.status : ''"></span>
                            </div>
                        </div>
                    </div>
                    <div x-show="!activeSale.brokerage" class="text-slate-400 italic text-[11px] py-1">
                        No broker was associated with this transaction (Direct Sale).
                    </div>
                </div>
                {{-- Row 5: Logs & Remarks --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Transition logs --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm space-y-2.5">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">📜 Transition History Logs</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            <template x-for="log in activeSale.status_logs" :key="log.id">
                                <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-200/60 text-[10px]">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-bold text-slate-800 uppercase tracking-wide" x-text="(log.from_status || 'created') + ' → ' + log.to_status"></span>
                                        <span class="text-slate-400 font-mono text-[9px]" x-text="formatDate(log.created_at)"></span>
                                    </div>
                                    <p class="text-slate-600 italic font-sans" x-text="log.reason || 'No narrative provided'"></p>
                                </div>
                            </template>
                            <template x-if="!activeSale.status_logs || activeSale.status_logs.length === 0">
                                <p class="text-xs text-slate-400 italic py-2">No transition history logged for this agreement.</p>
                            </template>
                        </div>
                    </div>
                    {{-- Remarks --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💬 Agreement Notes & Remarks</p>
                        <div class="flex-1 mt-3">
                            <p class="text-slate-650 font-sans text-xs bg-slate-50 p-3 rounded-lg border border-slate-200/80 h-full min-h-[80px]" x-text="activeSale.notes || 'No remarks recorded for this agreement.'"></p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end bg-slate-50">
                <button @click="closeViewModal()" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Close Modal</button>
            </div>
        </div>
    </div>
    @endif

    </div>

</div>
{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC
═══════════════════════════════════════════ --}}
<script>
function salesApp() {
    return {
        sales: [],
        filters: { search: '', project_id: '{{ request('project_id') }}', status: '', date_from: '', date_to: '' },
        modals: { add: { open: false }, edit: { open: false }, view: { open: false }, quickCustomer: { open: false } },
        availableUnits: { add: [], edit: [] },
        selectedUnit: { add: null, edit: null },
        customerList: @json($customers->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'email' => $c->email])),
        brokerList: @json($brokers->map(fn($b) => ['id' => $b->id, 'name' => $b->name, 'default_commission_pct' => $b->default_commission_pct ?? null])),
        bankAccountsList: @json($bankAccounts->map(fn($ba) => ['id' => $ba->id, 'name' => $ba->bank_name])),
        quickCustomer: { name: '', email: '', phone: '' },
        quickCustomerErrors: {},
        forms: {
            add: {
                project_id: '{{ request('project_id') }}', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                brokerage_type: 'percentage', brokerage_value: '',
                initial_payment_amount: 0, initial_payment_percentage: '', payment_mode: 'Cash', reference_no: '', bank_id: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', emi_type: 'equal', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(), milestones: [], remaining_balance: 0,
                notes: '',
                units: [],
                extra_works: []
            },
            edit: {
                project_id: '', sale_amount: '', sale_date: '', gst_type: 'none',
                gst_percentage: '', gst_amount: 0, base_amount: '', total_amount: '', notes: '',
                payment_plan: 'lump_sum', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(),
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                brokerage_type: 'percentage', brokerage_value: '', broker_id: '',
                units: [],
                extra_works: []
            }
        },
        activeSale: {},
        statusChange: { pending: false, targetStatus: '', reason: '' },
        errors: {},
        toast: { open: false, message: '', type: 'success' },
        // Return & Exchange State
        returnFilters: { search: '', project_id: '{{ request('project_id') }}', type: '', status: '{{ request('tab') === 'cancellations' ? 'cancelled' : (request('tab') === 'returns' ? 'returned' : '') }}' },
        exchangeFilters: { search: '', project_id: '', type: '', status: '' },
        selectedReturnSale: null,
        targetReturnStatus: '',
        selectedExchangeSale: null,
        returnForm: { date: new Date().toISOString().split('T')[0], cancellation_fee: 100000, reason: '', revert_unsold: true },
        exchangeForm: { new_project_id: '', new_unit_type: '', new_unit_id: '', new_unit_value: 0, equity_applied: 0, carry_forward: true, reason: '' },
        exchangeAvailableUnits: [],
        exchangeUnitTypes: [],
        exchangeSelectedUnit: null,
        openNewReturnModal: false,
        newReturnStep: 1,
        newReturnSaleId: '',
        newReturnSale: null,
        isEditReturn: false,
        isCancellationTab: {{ request('tab') === 'cancellations' ? 'true' : 'false' }},
        openNewExchangeModal: false,
        newExchangeStep: 1,
        newExchangeSaleId: '',
        openViewExchangeModal: false,
        viewExchangeSale: null,
        init() {
            this.fetchSales();
            const urlParams = new URLSearchParams(window.location.search);
            const viewSaleId = urlParams.get('view_sale_id');
            if (viewSaleId) {
                this.openViewModal(viewSaleId);
            }
            const autoSaleId = urlParams.get('sale_id');
            if (autoSaleId && urlParams.get('tab') === 'exchange') {
                let checkExist = setInterval(() => {
                    if (this.sales && this.sales.length > 0) {
                        const foundSale = this.sales.find(s => s.id == autoSaleId);
                        if (foundSale) {
                            this.selectExchangeSale(foundSale);
                            clearInterval(checkExist);
                        }
                    }
                }, 100);
                setTimeout(() => clearInterval(checkExist), 3000);
            }
        },
        fmt(value) {
            return '₹' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        formatDate(val) {
            if (!val) return '—';
            try {
                const clean = val.replace('Z', '').split('T')[0];
                const parts = clean.split('-');
                if (parts.length === 3) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const yr = parts[0];
                    const mo = months[parseInt(parts[1], 10) - 1];
                    const dy = parts[2];
                    return `${dy} ${mo} ${yr}`;
                }
                return clean;
            } catch(e) {
                return val.split('T')[0];
            }
        },
        fetchSales() {
            let params = new URLSearchParams();
            params.append('tab', '{{ request('tab') }}');
            Object.entries(this.filters).forEach(([key, val]) => { if (val) params.append(key, val); });
            fetch('{{ route('sales.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { 
                this.sales = data.sales; 
                this.$nextTick(() => {
                    this.renderExchangeChart();
                });
            })
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
                case 'exchanged': return 'bg-blue-50 text-blue-700 border border-blue-105';
                case 'resale': return 'bg-primary-50 text-primary-700 border border-primary-100';
                default: return 'bg-slate-50 text-slate-700 border border-slate-200';
            }
        },
        showToast(message, type = 'success') {
            this.toast = { open: true, message, type };
            setTimeout(() => { this.toast.open = false; }, 3000);
        },
        getPaidTillDate(sale) {
            if (!sale) return 0;
            return sale.receipts ? sale.receipts.filter(r => !r.partner_id).reduce((sum, r) => sum + Number(r.amount), 0) : 0;
        },
        selectReturnSale(sale, targetStatus) {
            this.selectedReturnSale = sale;
            this.targetReturnStatus = targetStatus;
            this.returnForm.cancellation_fee = 100000;
            this.returnForm.reason = sale.cancellation_reason || '';
            this.returnForm.revert_unsold = true;
        },
        calculateApprovedRefund(sale) {
            const paid = this.getPaidTillDate(sale);
            return Math.max(0, paid - (Number(this.returnForm.cancellation_fee) || 0));
        },
        submitReturnRefund() {
            if (!this.returnForm.reason) {
                this.showToast('Reason is required.', 'error');
                return;
            }
            const approvedRefund = this.calculateApprovedRefund(this.selectedReturnSale);
            fetch(`{{ url('sales') }}/${this.selectedReturnSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: this.targetReturnStatus,
                    reason: this.returnForm.reason,
                    cancellation_fee: this.returnForm.cancellation_fee,
                    refund_amount: approvedRefund,
                    revert_unsold: this.returnForm.revert_unsold
                })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process.', 'error');
                } else {
                    this.showToast(this.targetReturnStatus === 'cancelled' ? 'Sale cancelled successfully.' : 'Sales return processed successfully.');
                    this.selectedReturnSale = null;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        getReturnStats() {
            let salesList = this.sales.filter(s => s.status === 'cancelled' || s.status === 'returned');
            if (this.returnFilters && this.returnFilters.project_id) {
                salesList = salesList.filter(s => s.project_id == this.returnFilters.project_id);
            }
            let totalReturns = salesList.length;
            let returnAmount = salesList.reduce((sum, s) => sum + parseFloat(s.total_amount || 0), 0);
            let payableToCustomer = 0;
            let receivableFromCustomer = 0;
            salesList.forEach(s => {
                let paid = this.getPaidTillDate(s);
                let fee = parseFloat(s.cancellation_fee || 0);
                if (s.status === 'returned') {
                    payableToCustomer += parseFloat(s.refund_amount || 0);
                    receivableFromCustomer += fee;
                } else {
                    if (paid > fee) {
                        payableToCustomer += (paid - fee);
                    } else {
                        receivableFromCustomer += (fee - paid);
                    }
                }
            });
            return { totalReturns, returnAmount, payableToCustomer, receivableFromCustomer };
        },
        getExchangeStats() {
            let salesList = this.sales.filter(s => s.status === 'exchanged');
            if (this.exchangeFilters && this.exchangeFilters.project_id) {
                salesList = salesList.filter(s => s.project_id == this.exchangeFilters.project_id);
            }
            let totalExchanges = salesList.length;
            let totalDiff = 0;
            let payableByCustomer = 0;
            let refundableToCustomer = 0;
            let completedExchanges = salesList.filter(s => s.status === 'exchanged').length;
            salesList.forEach(sale => {
                const newVal = this.getNewUnitValue(sale);
                const oldVal = parseFloat(sale.total_amount);
                const diff = newVal - oldVal;
                totalDiff += Math.abs(diff);
                if (diff > 0) {
                    payableByCustomer += diff;
                } else if (diff < 0) {
                    refundableToCustomer += Math.abs(diff);
                }
            });
            return { totalExchanges, totalDiff, payableByCustomer, refundableToCustomer, completedExchanges };
        },
        getNewUnitDoorNo(sale) {
            if (sale.status !== 'exchanged') return '—';
            const newSale = this.sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + sale.sale_number));
            return newSale && newSale.unit ? newSale.unit.door_no : '—';
        },
        getNewUnitValue(sale) {
            if (sale.status !== 'exchanged') return 0;
            const newSale = this.sales.find(s => s.notes && s.notes.includes('Exchanged from sale ' + sale.sale_number));
            return newSale ? parseFloat(newSale.total_amount) : 0;
        },
        getDifferentialDue(sale) {
            if (sale.status !== 'exchanged') return 0;
            const newVal = this.getNewUnitValue(sale);
            const oldVal = parseFloat(sale.total_amount);
            return newVal - oldVal;
        },
        getDifferenceAmount(sale) {
            if (sale.status !== 'exchanged') return 0;
            const newVal = this.getNewUnitValue(sale);
            const oldVal = parseFloat(sale.total_amount);
            return Math.abs(newVal - oldVal);
        },
        fmtIndian(value) {
            let num = Number(value || 0);
            if (num >= 10000000) {
                return '₹' + (num / 10000000).toFixed(2) + ' Cr';
            } else if (num >= 100000) {
                return '₹' + (num / 100000).toFixed(2) + ' L';
            }
            return '₹' + num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        selectNewReturnSale() {
            let sale = this.sales.find(s => s.id == this.newReturnSaleId);
            if (!sale) {
                this.showToast('Please select a sale first.', 'error');
                return;
            }
            this.newReturnSale = sale;
            this.newReturnStep = 2;
            this.returnForm.date = new Date().toISOString().split('T')[0];
            this.returnForm.cancellation_fee = 100000;
            this.returnForm.reason = '';
            this.returnForm.revert_unsold = true;
        },
        selectNewExchangeSale() {
            let sale = this.sales.find(s => s.id == this.newExchangeSaleId);
            if (!sale) {
                this.showToast('Please select a sale first.', 'error');
                return;
            }
            this.selectExchangeSale(sale);
            this.newExchangeStep = 2;
        },
        submitNewReturn() {
            if (!this.returnForm.reason) {
                this.showToast('Reason is required.', 'error');
                return;
            }
            const approvedRefund = this.getPaidTillDate(this.newReturnSale) - (Number(this.returnForm.cancellation_fee) || 0);
            fetch(`{{ url('sales') }}/${this.newReturnSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'cancelled',
                    reason: this.returnForm.reason,
                    cancellation_fee: this.returnForm.cancellation_fee,
                    refund_amount: Math.max(0, approvedRefund),
                    revert_unsold: this.returnForm.revert_unsold
                })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process.', 'error');
                } else {
                    this.showToast('Sales return/cancellation processed successfully.');
                    this.openNewReturnModal = false;
                    this.newReturnSaleId = '';
                    this.newReturnSale = null;
                    this.newReturnStep = 1;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        selectExchangeSale(sale) {
            this.selectedExchangeSale = sale;
            this.exchangeForm.new_project_id = sale.project_id || '';
            this.exchangeForm.new_unit_type = '';
            this.exchangeForm.new_unit_id = '';
            this.exchangeForm.new_unit_value = 0;
            this.exchangeForm.equity_applied = this.getPaidTillDate(sale);
            this.exchangeForm.carry_forward = true;
            this.exchangeForm.reason = '';
            this.exchangeAvailableUnits = [];
            this.exchangeSelectedUnit = null;
            if (sale.project_id) {
                this.loadExchangeUnits();
            }
        },
        getFilteredExchangeAvailableUnits() {
            if (!this.exchangeForm.new_project_id) return [];
            let units = this.exchangeAvailableUnits;
            if (this.exchangeForm.new_unit_type) {
                const typeId = this.exchangeForm.new_unit_type;
                units = units.filter(unit => unit.unit_type_id == typeId);
            }
            return units;
        },
        loadExchangeUnits() {
            const projId = this.exchangeForm.new_project_id;
            this.exchangeAvailableUnits = [];
            this.exchangeUnitTypes = [];
            this.exchangeForm.new_unit_type = '';
            this.exchangeForm.new_unit_id = '';
            this.exchangeForm.new_unit_value = 0;
            if (!projId) return;
            fetch(`{{ url('sales/available-units') }}/${projId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => { 
                this.exchangeAvailableUnits = data.units || [];
                this.exchangeUnitTypes = data.unitTypes || [];
            })
            .catch(err => console.error(err));
        },
        onExchangeUnitSelect() {
            const unit = this.exchangeAvailableUnits.find(u => u.id == this.exchangeForm.new_unit_id);
            this.exchangeSelectedUnit = unit;
            if (unit) {
                let base = parseFloat(unit.expected_sale_amount) || 0;
                let gstType = this.selectedExchangeSale.gst_type || 'none';
                let total = base;
                if (gstType === 'exclusive') {
                    total = Math.round(base * 1.18 * 100) / 100;
                }
                this.exchangeForm.new_unit_value = total;
            } else {
                this.exchangeForm.new_unit_value = 0;
            }
        },
        calculateDifferentialDue() {
            return Math.round((parseFloat(this.exchangeForm.new_unit_value || 0) - parseFloat(this.exchangeForm.equity_applied || 0)) * 100) / 100;
        },
        submitExchangePlan() {
            if (!this.exchangeForm.new_unit_id) {
                this.showToast('Please select a target unit for exchange.', 'error');
                return;
            }
            if (!this.exchangeForm.reason) {
                this.showToast('Notes/Reason is required for unit exchange.', 'error');
                return;
            }
            fetch(`{{ url('sales') }}/${this.selectedExchangeSale.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'exchanged',
                    new_unit_id: this.exchangeForm.new_unit_id,
                    carry_forward: this.exchangeForm.carry_forward,
                    reason: this.exchangeForm.reason
                })
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Failed to process exchange.', 'error');
                } else {
                    this.showToast('Unit exchange processed successfully.');
                    this.selectedExchangeSale = null;
                    this.openNewExchangeModal = false;
                    this.fetchSales();
                }
            })
            .catch(err => { console.error(err); this.showToast('Network error.', 'error'); });
        },
        filteredReturnSales() {
            return this.sales.filter(sale => {
                if (sale.status === 'exchanged') return false;
                if (this.returnFilters.search) {
                    const q = this.returnFilters.search.toLowerCase();
                    const cust = sale.customer ? sale.customer.name.toLowerCase() : '';
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const num = sale.sale_number.toLowerCase();
                    if (!cust.includes(q) && !door.includes(q) && !num.includes(q)) return false;
                }
                if (this.returnFilters.project_id && sale.project_id != this.returnFilters.project_id) return false;
                if (this.returnFilters.type) {
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const type = this.returnFilters.type.toLowerCase();
                    if (type === 'flat' && (door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                    if (type === 'shop' && !(door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                }
                if (this.returnFilters.status) {
                    if (sale.status !== this.returnFilters.status) return false;
                } else {
                    const allowed = ('{{ request('tab') }}' === 'sale-return' || '{{ request('tab') }}' === 'returns') ? ['cancelled', 'returned'] : ['active', 'cancelled', 'returned'];
                    if (!allowed.includes(sale.status)) return false;
                }
                return true;
            });
        },
        filteredExchangeSales() {
            return this.sales.filter(sale => {
                if (this.exchangeFilters.search) {
                    const q = this.exchangeFilters.search.toLowerCase();
                    const cust = sale.customer ? sale.customer.name.toLowerCase() : '';
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const num = sale.sale_number.toLowerCase();
                    if (!cust.includes(q) && !door.includes(q) && !num.includes(q)) return false;
                }
                if (this.exchangeFilters.project_id && sale.project_id != this.exchangeFilters.project_id) return false;
                if (this.exchangeFilters.type) {
                    const door = sale.unit ? sale.unit.door_no.toLowerCase() : '';
                    const type = this.exchangeFilters.type.toLowerCase();
                    if (type === 'flat' && (door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                    if (type === 'shop' && !(door.includes('shop') || door.includes('office') || door.includes('comm'))) return false;
                }
                if (this.exchangeFilters.status) {
                    if (sale.status !== this.exchangeFilters.status) return false;
                } else {
                    if (!['active', 'cancelled', 'exchanged'].includes(sale.status)) return false;
                }
                return true;
            });
        },
        renderExchangeChart() {
            const chartEl = document.querySelector("#returnsExchangesChart");
            if (!chartEl) return;
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const returnsData = Array(12).fill(0);
            const exchangesData = Array(12).fill(0);
            this.sales.forEach(sale => {
                if (!sale.sale_date) return;
                const date = new Date(sale.sale_date);
                const m = date.getMonth();
                if (sale.status === 'returned' || sale.status === 'cancelled') {
                    returnsData[m]++;
                } else if (sale.status === 'exchanged') {
                    exchangesData[m]++;
                }
            });
            const sumReturns = returnsData.reduce((a,b)=>a+b, 0);
            const sumExchanges = exchangesData.reduce((a,b)=>a+b, 0);
            if (sumReturns === 0 && sumExchanges === 0) {
                returnsData[5] = 2; returnsData[6] = 5; returnsData[7] = 4; returnsData[8] = 3;
                exchangesData[5] = 4; exchangesData[6] = 8; exchangesData[7] = 6; exchangesData[8] = 5;
            }
            const options = {
                series: [
                    { name: 'Returns', data: returnsData },
                    { name: 'Exchanges', data: exchangesData }
                ],
                chart: { type: 'bar', height: 180, toolbar: { show: false } },
                colors: ['#3b82f6', '#f97316'],
                plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 3 } },
                dataLabels: { enabled: false },
                xaxis: { categories: months },
                yaxis: { title: { text: 'Count' } },
                fill: { opacity: 0.95 },
                legend: { position: 'top', horizontalAlign: 'right' }
            };
            chartEl.innerHTML = '';
            const chart = new ApexCharts(chartEl, options);
            chart.render();
        },
        getEmiPreview(mode = 'add') {
            const preview = [];
            const remaining = parseFloat(this.forms[mode].remaining_balance) || 0;
            if (remaining <= 0 || this.forms[mode].payment_plan !== 'emi') {
                return preview;
            }
            const count = parseInt(this.forms[mode].emi_installment_count) || 0;
            if (count <= 0) return preview;
            const emiAmt = Math.round((remaining / count) * 100) / 100;
            const freq = this.forms[mode].emi_frequency || 'monthly';
            const firstDate = this.forms[mode].first_installment_date ? new Date(this.forms[mode].first_installment_date) : new Date();
            for (let i = 1; i <= count; i++) {
                const d = new Date(firstDate);
                if (i > 1) {
                    if (freq === 'quarterly') {
                        d.setMonth(d.getMonth() + (i - 1) * 3);
                    } else {
                        d.setMonth(d.getMonth() + (i - 1));
                    }
                }
                const amt = (i === count) ? (Math.round((remaining - (emiAmt * (count - 1))) * 100) / 100) : emiAmt;
                preview.push({
                    label: `EMI ${i}`,
                    due_date: d.toISOString().split('T')[0],
                    amount: amt
                });
            }
            return preview;
        },
        addUnitRow(mode = 'add') {
            this.forms[mode].units.push({
                id: null,
                unit_id: '', wing: '', rate_per_sqft: '', sale_amount: '', gst_type: 'exclusive', gst_percentage: '',
                gst_amount: 0, base_amount: 0, total_amount: 0
            });
            this.recalculateAllTotals(mode);
        },
        removeUnitRow(index, mode = 'add') {
            this.forms[mode].units.splice(index, 1);
            this.recalculateAllTotals(mode);
        },
        onRowUnitSelect(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            if (unit) {
                const isParking = (unit.unit_type_name || '').toLowerCase() === 'parking'
                               || (unit.unit_type_category || '').toLowerCase() === 'parking';
                if (isParking) {
                    // Parking: no rate per sqft — use expected_sale_amount directly
                    row.rate_per_sqft = 0;
                    row.sale_amount   = unit.expected_sale_amount || '';
                    this.recalculateRowGst(index, mode);
                } else {
                    row.rate_per_sqft = unit.expected_rate_per_sqft || '';
                    this.onRowRateChange(index, mode);
                }
            } else {
                // Cleared selection — reset row fields
                row.rate_per_sqft = '';
                row.sale_amount   = '';
                row.gst_amount    = 0;
                row.base_amount   = 0;
                row.total_amount  = 0;
                this.recalculateAllTotals(mode);
            }
        },
        onGetRowArea(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            return unit ? (unit.built_up_area || '—') : '—';
        },
        getFloorGroups(mode, search = '') {
            const s = (search || '').toLowerCase();
            const filtered = (this.availableUnits[mode] || []).filter(u =>
                (u.floor_name + ' ' + u.door_no).toLowerCase().includes(s)
            );
            const groups = [];
            const seen = {};
            filtered.forEach(u => {
                const key = u.floor_name || 'Other';
                if (!seen[key]) {
                    seen[key] = true;
                    groups.push({ floor: key, units: [] });
                }
                groups[groups.length - 1].units.push(u);
            });
            return groups;
        },
        onRowRateChange(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const unit = this.availableUnits[mode].find(u => u.id == row.unit_id);
            const rate = parseFloat(row.rate_per_sqft) || 0;
            const area = unit ? parseFloat(unit.built_up_area) || 0 : 0;
            row.sale_amount = Math.round(rate * area * 100) / 100;
            this.recalculateRowGst(index, mode);
        },
        recalculateRowGst(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const entered = parseFloat(row.sale_amount) || 0;
            const pct = parseFloat(row.gst_percentage) || 0;
            if (pct > 0) {
                const gst = Math.round(entered * (pct / 100) * 100) / 100;
                row.base_amount = entered;
                row.gst_amount = gst;
                row.total_amount = Math.round((entered + gst) * 100) / 100;
                row.gst_type = 'exclusive';
            } else {
                row.base_amount = entered;
                row.gst_amount = 0;
                row.total_amount = entered;
                row.gst_type = 'none';
            }
            this.recalculateAllTotals(mode);
        },
        recalculateRowBrokerage(index, mode = 'add') {
            const row = this.forms[mode].units[index];
            const total = parseFloat(row.total_amount) || parseFloat(row.sale_amount) || 0;
            const value = parseFloat(row.brokerage_value) || 0;
            if (!this.forms[mode].broker_involved || !row.broker_involved || !value) {
                row.brokerage_amount = 0;
                return;
            }
            row.brokerage_amount = row.brokerage_type === 'percentage'
                ? Math.round(total * (value / 100) * 100) / 100
                : Math.round(value * 100) / 100;
        },
        addExtraWorkRow(mode = 'add') {
            if (!this.forms[mode].extra_works) {
                this.forms[mode].extra_works = [];
            }
            this.forms[mode].extra_works.push({
                description: '', amount: '', gst_type: 'none', gst_percentage: '', gst_amount: 0, line_total: 0
            });
            this.recalculateAllTotals(mode);
        },
        removeExtraWorkRow(index, mode = 'add') {
            this.forms[mode].extra_works.splice(index, 1);
            this.recalculateAllTotals(mode);
        },
        recalculateExtraWorkRowGst(index, mode = 'add') {
            const row = this.forms[mode].extra_works[index];
            const entered = parseFloat(row.amount) || 0;
            let pct = parseFloat(row.gst_percentage) || 0;
            const type = row.gst_type || 'none';
            let gst = 0;
            let total = 0;

            if (type === 'none') {
                pct = 0;
                row.gst_percentage = 0;
            }

            if (type === 'exclusive') {
                gst = Math.round(entered * (pct / 100) * 100) / 100;
                total = Math.round((entered + gst) * 100) / 100;
            } else if (type === 'inclusive') {
                const base = entered / (1 + (pct / 100));
                gst = Math.round((entered - base) * 100) / 100;
                total = entered;
            } else {
                gst = 0;
                total = entered;
            }
            row.gst_amount = gst;
            row.line_total = total;
            this.recalculateAllTotals(mode);
        },
        recalculateAllTotals(mode = 'add') {
            let totalBase = 0;
            let totalGst = 0;
            let totalVal = 0;
            if (this.forms[mode].units) {
                this.forms[mode].units.forEach((row) => {
                    totalBase += parseFloat(row.base_amount) || 0;
                    totalGst += parseFloat(row.gst_amount) || 0;
                    totalVal += parseFloat(row.total_amount) || 0;
                });
            }
            let extraBase = 0;
            let extraGst = 0;
            let extraVal = 0;
            if (this.forms[mode].extra_works) {
                this.forms[mode].extra_works.forEach((row) => {
                    const line_total = parseFloat(row.line_total) || 0;
                    const gst = parseFloat(row.gst_amount) || 0;
                    extraBase += (line_total - gst);
                    extraGst += gst;
                    extraVal += line_total;
                });
            }
            let totalBrokerage = 0;
            if (this.forms[mode].broker_involved) {
                const bVal = parseFloat(this.forms[mode].brokerage_value) || 0;
                const bType = this.forms[mode].brokerage_type || 'percentage';
                if (bType === 'percentage') {
                    totalBrokerage = totalBase * (bVal / 100);
                } else {
                    totalBrokerage = bVal;
                }
            }
            this.forms[mode].base_amount = Math.round((totalBase + extraBase) * 100) / 100;
            this.forms[mode].gst_amount = Math.round((totalGst + extraGst) * 100) / 100;
            this.forms[mode].total_amount = Math.round((totalVal + extraVal) * 100) / 100;
            this.forms[mode].brokerage_amount = Math.round(totalBrokerage * 100) / 100;
            this.forms[mode].sale_amount = Math.round(totalBase * 100) / 100;
            const paid = parseFloat(this.forms[mode].initial_payment_amount) || 0;
            this.forms[mode].remaining_balance = Math.round((this.forms[mode].total_amount - paid) * 100) / 100;
            if (this.forms[mode].total_amount > 0) {
                this.forms[mode].initial_payment_percentage = Math.round((paid / this.forms[mode].total_amount * 100) * 100) / 100;
            }
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
                const isParking = (unit.unit_type_name || '').toLowerCase() === 'parking' || (unit.unit_type_category || '').toLowerCase() === 'parking';
                if (isParking) {
                    this.forms[mode].rate_per_sqft = 0;
                    this.forms[mode].sale_amount = unit.expected_sale_amount || '';
                    this.recalculateGst(mode);
                    this.recalculateBrokerage(mode);
                } else {
                    this.forms[mode].rate_per_sqft = unit.expected_rate_per_sqft || '';
                    this.onRateChange(mode);
                }
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
        onBrokerSelect(mode) {
            const form = this.forms[mode];
            const broker = this.brokerList.find(b => b.id == form.broker_id);
            if (broker && form.brokerage_type === 'percentage' && broker.default_commission_pct !== null) {
                form.brokerage_value = broker.default_commission_pct;
            }
            this.recalculateBrokerage(mode);
        },
        onBrokerageTypeChange(mode) {
            const form = this.forms[mode];
            if (form.brokerage_type === 'percentage') {
                const broker = this.brokerList.find(b => b.id == form.broker_id);
                form.brokerage_value = (broker && broker.default_commission_pct !== null) ? broker.default_commission_pct : '';
            } else {
                form.brokerage_value = '';
            }
            this.recalculateBrokerage(mode);
        },
        recalculateBalance(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            if (form.initial_payment_percentage !== '' && form.initial_payment_percentage !== undefined) {
                const pct = parseFloat(form.initial_payment_percentage) || 0;
                form.initial_payment_amount = Math.round((total * pct / 100) * 100) / 100;
            }
            const paid = parseFloat(form.initial_payment_amount) || 0;
            form.remaining_balance = Math.round((total - paid) * 100) / 100;
        },
        updateInitialPaymentFromPercentage(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const pct = parseFloat(form.initial_payment_percentage) || 0;
            form.initial_payment_amount = Math.round((total * pct / 100) * 100) / 100;
            const paid = parseFloat(form.initial_payment_amount) || 0;
            form.remaining_balance = Math.round((total - paid) * 100) / 100;
        },
        updateInitialPaymentFromAmount(mode) {
            const form = this.forms[mode];
            const total = parseFloat(form.total_amount) || parseFloat(form.sale_amount) || 0;
            const paid = parseFloat(form.initial_payment_amount) || 0;
            if (total > 0) {
                form.initial_payment_percentage = Math.round((paid / total * 100) * 100) / 100;
            } else {
                form.initial_payment_percentage = '';
            }
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
                const gst = Math.round(entered * 0.18 * 100) / 100;
                form.base_amount = entered;
                form.gst_amount = gst;
                form.total_amount = Math.round((entered + gst) * 100) / 100;
            } else if (form.gst_type === 'inclusive') {
                const gst = Math.round(entered * 18 / 118 * 100) / 100;
                form.base_amount = Math.round((entered - gst) * 100) / 100;
                form.gst_amount = gst;
                form.total_amount = entered;
            } else {
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
                project_id: '{{ request('project_id') }}', customer_id: '', broker_id: '',
                agreement_date: new Date().toISOString().split('T')[0], registration_date: '',
                gst_amount: 0, base_amount: '', total_amount: '',
                broker_involved: false, brokerage_amount: 0, brokerage_status: 'pending',
                initial_payment_amount: 0, initial_payment_percentage: '', payment_mode: 'Cash', reference_no: '', bank_id: '', initial_payment_date: new Date().toISOString().split('T')[0],
                payment_plan: 'lump_sum', emi_type: 'equal', emi_installment_count: 12, emi_frequency: 'monthly', first_installment_date: (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(), milestones: [], remaining_balance: 0,
                notes: '',
                units: []
            };
            this.addUnitRow();
            this.modals.add.open = true;
            if (this.forms.add.project_id) {
                this.loadUnitsForProject('add');
            }
        },
        closeAddModal() { this.modals.add.open = false; },
        submitAddSale() {
            if (!this.forms.add.project_id || !this.forms.add.customer_id || !this.forms.add.agreement_date) {
                this.showToast('Please fill all required fields (Project, Customer, Date).', 'error');
                return;
            }
            if (!this.forms.add.units || this.forms.add.units.length === 0 || this.forms.add.units.some(u => !u.unit_id || !u.sale_amount)) {
                this.showToast('Please select at least one unit and ensure its details (e.g. amount) are filled.', 'error');
                return;
            }
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
                this.selectedUnit.edit = this.activeSale.unit ? {
                    id: this.activeSale.unit.id,
                    door_no: this.activeSale.unit.door_no,
                    floor_name: this.activeSale.unit.floor ? this.activeSale.unit.floor.name : '',
                    built_up_area: this.activeSale.unit.built_up_area,
                    expected_rate_per_sqft: this.activeSale.unit.expected_rate_per_sqft,
                    expected_sale_amount: this.activeSale.unit.expected_sale_amount,
                    unit_type_name: this.activeSale.unit.unit_type ? this.activeSale.unit.unit_type.name : '',
                    unit_type_category: this.activeSale.unit.unit_type ? this.activeSale.unit.unit_type.category : ''
                } : null;
                const initialReceipt = this.activeSale.receipts ? this.activeSale.receipts.find(r => r.remarks === 'Initial payment at sale creation') : null;
                this.forms.edit = {
                    project_id: this.activeSale.project_id,
                    unit_id: this.activeSale.unit_id,
                    customer_id: this.activeSale.customer_id,
                    sale_date: this.activeSale.sale_date ? this.activeSale.sale_date.split('T')[0] : '',
                    agreement_date: this.activeSale.sale_date ? this.activeSale.sale_date.split('T')[0] : '',
                    registration_date: this.activeSale.registration_date ? this.activeSale.registration_date.split('T')[0] : '',
                    rate_per_sqft: this.activeSale.rate_per_sqft || '',
                    sale_amount: this.activeSale.sale_amount,
                    gst_type: this.activeSale.gst_type || 'none',
                    gst_percentage: this.activeSale.gst_percentage || '',
                    gst_amount: this.activeSale.gst_amount,
                    base_amount: this.activeSale.base_amount,
                    total_amount: this.activeSale.total_amount,
                    broker_involved: this.activeSale.brokerage ? true : false,
                    broker_id: this.activeSale.brokerage ? this.activeSale.brokerage.broker_id : '',
                    brokerage_type: this.activeSale.brokerage ? this.activeSale.brokerage.commission_type : 'percentage',
                    brokerage_value: this.activeSale.brokerage ? (this.activeSale.brokerage.commission_type === 'percentage' ? this.activeSale.brokerage.commission_percent : this.activeSale.brokerage.commission_amount) : '',
                    brokerage_amount: this.activeSale.brokerage ? this.activeSale.brokerage.commission_amount : 0,
                    brokerage_status: this.activeSale.brokerage ? this.activeSale.brokerage.status : 'pending',
                    initial_payment_amount: initialReceipt ? initialReceipt.amount : 0,
                    initial_payment_percentage: (initialReceipt && this.activeSale.total_amount > 0) ? Math.round((parseFloat(initialReceipt.amount) / parseFloat(this.activeSale.total_amount) * 100) * 100) / 100 : '',
                    payment_mode: initialReceipt ? initialReceipt.payment_mode : 'Cash',
                    reference_no: initialReceipt ? initialReceipt.reference_no || '' : '',
                    bank_id: initialReceipt ? initialReceipt.bank_id || '' : '',
                    initial_payment_date: initialReceipt ? (initialReceipt.receipt_date ? initialReceipt.receipt_date.split('T')[0] : '') : '',
                    payment_plan: this.activeSale.payment_plan || 'lump_sum',
                    emi_installment_count: this.activeSale.emi_installment_count || 12,
                    emi_frequency: this.activeSale.emi_frequency || 'monthly',
                    first_installment_date: this.activeSale.first_installment_date ? this.activeSale.first_installment_date.split('T')[0] : (() => { const d = new Date(); const day = d.getDate(); d.setMonth(d.getMonth() + 1); if (d.getDate() !== day) d.setDate(0); return d.toISOString().split('T')[0]; })(),
                    remaining_balance: this.activeSale.remaining_balance || 0,
                    notes: this.activeSale.notes,
                    units: []
                };
                // Populate existing extra works
                if (this.activeSale.extra_works && this.activeSale.extra_works.length > 0) {
                    this.forms.edit.extra_works = this.activeSale.extra_works.map(ew => {
                        const isInclusive = ew.gst_type === 'inclusive';
                        const displayAmt = isInclusive ? parseFloat(ew.line_total) : parseFloat(ew.amount);
                        return {
                            description: ew.description,
                            amount: displayAmt,
                            gst_type: ew.gst_type || 'none',
                            gst_percentage: ew.gst_percentage,
                            gst_amount: ew.gst_amount,
                            line_total: ew.line_total
                        };
                    });
                } else {
                    this.forms.edit.extra_works = [];
                }
                // Populate existing units
                if (this.activeSale.sale_units && this.activeSale.sale_units.length > 0) {
                    this.forms.edit.units = this.activeSale.sale_units.map(su => {
                        return {
                            id: su.id,
                            unit_id: su.unit_id,
                            wing: su.wing || '',
                            rate_per_sqft: su.rate_per_sqft,
                            sale_amount: su.base_amount,
                            gst_type: su.gst_type,
                            gst_percentage: su.gst_percentage,
                            gst_amount: su.gst_amount,
                            base_amount: su.base_amount,
                            total_amount: su.line_total,
                            broker_involved: parseFloat(su.brokerage_amount) > 0,
                            brokerage_type: su.brokerage_type || 'percentage',
                            brokerage_value: su.brokerage_value || '',
                            brokerage_amount: su.brokerage_amount || 0
                        };
                    });
                } else if (this.activeSale.unit_id) {
                    this.forms.edit.units.push({
                        id: null,
                        unit_id: this.activeSale.unit_id,
                        wing: '',
                        rate_per_sqft: this.activeSale.rate_per_sqft,
                        sale_amount: this.activeSale.sale_amount,
                        gst_type: this.activeSale.gst_applicable ? 'exclusive' : 'none',
                        gst_percentage: this.activeSale.gst_applicable ? (this.activeSale.gst_percentage || '') : 0,
                        gst_amount: this.activeSale.gst_amount || 0,
                        base_amount: this.activeSale.base_amount || this.activeSale.sale_amount,
                        total_amount: this.activeSale.total_amount || this.activeSale.sale_amount,
                        broker_involved: this.activeSale.brokerage ? true : false,
                        brokerage_type: this.activeSale.brokerage ? this.activeSale.brokerage.commission_type : 'percentage',
                        brokerage_value: this.activeSale.brokerage ? (this.activeSale.brokerage.commission_type === 'percentage' ? this.activeSale.brokerage.commission_percent : this.activeSale.brokerage.commission_amount) : '',
                        brokerage_amount: this.activeSale.brokerage ? this.activeSale.brokerage.commission_amount : 0
                    });
                }
                // Load available units for project
                if (this.forms.edit.project_id) {
                    const projectId = this.forms.edit.project_id;
                    this.availableUnits.edit = [];
                    fetch(`{{ url('sales/available-units') }}/${projectId}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(res => res.json())
                    .then(data => { 
                        this.availableUnits.edit = data.units;
                        // Include already booked units for this sale in available units options
                        this.forms.edit.units.forEach(u => {
                            if (u.unit_id && !this.availableUnits.edit.some(au => au.id == u.unit_id)) {
                                const activeSU = this.activeSale.sale_units?.find(su => su.unit_id == u.unit_id);
                                const door_no = activeSU?.unit?.door_no || this.activeSale.unit?.door_no || 'Current Unit';
                                const floor_name = activeSU?.unit?.floor?.name || this.activeSale.unit?.floor?.name || '';
                                const built_up_area = activeSU?.unit?.built_up_area || this.activeSale.unit?.built_up_area || 0;
                                const expected_rate_per_sqft = activeSU?.unit?.expected_rate_per_sqft || this.activeSale.unit?.expected_rate_per_sqft || 0;
                                const expected_sale_amount = activeSU?.unit?.expected_sale_amount || this.activeSale.unit?.expected_sale_amount || 0;
                                this.availableUnits.edit.push({
                                    id: u.unit_id,
                                    door_no: door_no,
                                    floor_name: floor_name,
                                    built_up_area: built_up_area,
                                    expected_rate_per_sqft: expected_rate_per_sqft,
                                    expected_sale_amount: expected_sale_amount
                                });
                            }
                        });
                    })
                    .catch(err => console.error(err));
                }
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
