<x-erp-layout>
    <x-slot:title>Real-Time Cash Allocation Workspace - Tabasco Human Capital</x-slot:title>
    <x-slot:headerTitle>Payment Receipt Intake & Real-Time Allocation</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptAllocationWorkspace()" x-init="init()">
        <form action="{{ route('vouchers.receipt.store') }}" method="POST" @submit="onSubmit($event)">
            @csrf

            <!-- Hidden input fields to submit values in a single HTTP request -->
            <input type="hidden" name="voucher_number" value="{{ $voucherNumber }}">
            <input type="hidden" name="split_active" value="1">
            <input type="hidden" name="date" :value="form.date">
            <input type="hidden" name="project_id" :value="form.project_id">
            <input type="hidden" name="unit_id" :value="form.unit_id">
            <input type="hidden" name="credit_account_id" :value="form.credit_account_id">
            <input type="hidden" name="destination_account_id" :value="form.destination_account_id">
            <input type="hidden" name="amount" :value="form.amount">
            <input type="hidden" name="gst_behavior" value="inclusive">
            <input type="hidden" name="gst_rate" value="0">
            <input type="hidden" name="source_receipt_id" :value="selectedReceiptId">
            <input type="hidden" name="narration" :value="form.narration">
            <input type="hidden" name="allocations" :value="JSON.stringify(allocations)">

            <!-- ── STEP PROGRESS INDICATOR ── -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                <div class="max-w-4xl mx-auto relative flex items-center justify-between">
                    <!-- Progress Tracker Bar -->
                    <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200 -z-0"></div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-primary transition-all duration-500 -z-0"
                         :style="'width: ' + ((step - 1) * 50) + '%'"></div>

                    <!-- Step 1 Dot -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="step = 1"
                                :class="step >= 1 ? 'bg-primary text-white border-primary shadow-glow' : 'bg-slate-100 text-slate-400 border-slate-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition duration-300">
                            1
                        </button>
                        <span class="text-[10px] font-extrabold mt-2 uppercase tracking-wider" :class="step >= 1 ? 'text-primary' : 'text-slate-500'">Select Receipt</span>
                        <span class="text-[8px] text-slate-400 font-medium">Inbound receipt list</span>
                    </div>

                    <!-- Step 2 Dot -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="selectedReceiptId ? step = 2 : null" :disabled="!selectedReceiptId"
                                :class="step >= 2 ? 'bg-primary text-white border-primary shadow-glow' : 'bg-slate-100 text-slate-400 border-slate-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            2
                        </button>
                        <span class="text-[10px] font-extrabold mt-2 uppercase tracking-wider" :class="step >= 2 ? 'text-primary' : 'text-slate-500'">Allocate Funds</span>
                        <span class="text-[8px] text-slate-400 font-medium">Dynamic split table</span>
                    </div>

                    <!-- Step 3 Dot -->
                    <div class="relative z-10 flex flex-col items-center">
                        <button type="button" @click="isBalanced() ? step = 3 : null" :disabled="!isBalanced()"
                                :class="step >= 3 ? 'bg-primary text-white border-primary shadow-glow' : 'bg-slate-100 text-slate-400 border-slate-200'"
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            3
                        </button>
                        <span class="text-[10px] font-extrabold mt-2 uppercase tracking-wider" :class="step >= 3 ? 'text-primary' : 'text-slate-500'">Review & Process</span>
                        <span class="text-[8px] text-slate-400 font-medium">Ledger entry preview</span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ── STEP 1: SELECT RECEIPT (Left -> Right Layout) ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-show="step === 1" x-transition>
                
                <!-- Left Panel: Unallocated Receipt List (2/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden lg:col-span-2">
                    <div class="px-6 py-4.5 bg-gradient-to-r from-slate-50 to-slate-100/50 border-b border-slate-150 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Unallocated Receipt List</h3>
                        
                        <!-- Search & Filter Controls -->
                        <div class="flex flex-wrap items-center gap-2">
                            <input type="text" x-model="searchQuery" placeholder="Search customer/receipt..."
                                   class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                            
                            <select x-model="filterProject" class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none">
                                <option value="">Filter Project</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-5 py-3">Receipt No</th>
                                    <th class="px-5 py-3">Date</th>
                                    <th class="px-5 py-3">Customer</th>
                                    <th class="px-5 py-3">Project</th>
                                    <th class="px-5 py-3">Unit</th>
                                    <th class="px-5 py-3 text-right">Amount Received</th>
                                    <th class="px-5 py-3">Mode</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                                <template x-for="r in filteredReceipts()" :key="r.id">
                                    <tr @click="selectReceipt(r)"
                                        :class="selectedReceiptId == r.id ? 'bg-primary/10 border-l-4 border-primary font-bold' : 'hover:bg-slate-50/50 cursor-pointer'"
                                        class="transition duration-150">
                                        <td class="px-5 py-3.5 font-mono text-slate-900" x-text="r.ref"></td>
                                        <td class="px-5 py-3.5 text-slate-500" x-text="r.date"></td>
                                        <td class="px-5 py-3.5 font-bold text-slate-800" x-text="r.customer_name"></td>
                                        <td class="px-5 py-3.5 text-slate-500" x-text="r.project_name"></td>
                                        <td class="px-5 py-3.5 font-bold text-slate-700" x-text="r.unit_name"></td>
                                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(r.amount)"></td>
                                        <td class="px-5 py-3.5 text-slate-450 uppercase font-semibold text-[10px]" x-text="r.payment_mode"></td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span :class="r.is_allocated ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-250'"
                                                  class="px-2 py-0.5 border rounded-lg text-[9px] font-extrabold uppercase"
                                                  x-text="r.is_allocated ? 'Allocated' : 'Unallocated'">
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="filteredReceipts().length === 0">
                                    <tr>
                                        <td colspan="8" class="px-5 py-12 text-center text-slate-400 italic">No unallocated receipts found matching filters.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Panel: Receipt Details (1/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-6 p-6">
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Receipt Details</h3>
                        
                        <template x-if="selectedReceipt">
                            <div class="space-y-4 text-xs">
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Receipt Number</div>
                                    <div class="mt-0.5 text-sm font-mono font-bold text-primary" x-text="selectedReceipt.ref"></div>
                                </div>
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Customer</div>
                                    <div class="mt-0.5 font-bold text-slate-900" x-text="selectedReceipt.customer_name"></div>
                                </div>
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Project / Unit</div>
                                    <div class="mt-0.5 font-semibold text-slate-800" x-text="selectedReceipt.project_name + ' — ' + selectedReceipt.unit_name"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Amount Received</div>
                                        <div class="mt-0.5 font-mono font-extrabold text-slate-900" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</div>
                                        <div class="mt-0.5 font-mono font-extrabold text-emerald-600" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Already Allocated</div>
                                        <div class="mt-0.5 font-mono text-slate-400 font-bold">₹0.00</div>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Payment Mode</div>
                                        <div class="mt-0.5 font-bold text-slate-900 uppercase text-[10px]" x-text="selectedReceipt.payment_mode"></div>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Receipt Narration</div>
                                    <div class="mt-1 p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 italic leading-relaxed" x-text="selectedReceipt.remarks"></div>
                                </div>

                                <!-- Select Destination Bank Account for split processing -->
                                <div class="pt-2 border-t border-slate-100">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-wider block mb-1">Process Into Bank / Cash Account</label>
                                    <select name="destination_account_id" x-model="form.destination_account_id" required
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                        <option value="">-- Select Destination Ledger --</option>
                                        @foreach($assetAccounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedReceipt">
                            <div class="py-12 text-center text-slate-400 italic text-xs">
                                Select an unallocated receipt from the list to display details.
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="step = 2" :disabled="!selectedReceiptId || !form.destination_account_id"
                            :class="!selectedReceiptId || !form.destination_account_id ? 'bg-slate-150 text-slate-400 cursor-not-allowed border border-slate-200' : 'bg-gradient-to-r from-primary to-primary-700 hover:brightness-110 text-white shadow-soft shadow-primary-900/30'"
                            class="w-full py-4 text-center text-xs font-extrabold rounded-xl transition duration-300 uppercase tracking-wider border border-primary-500/20 flex items-center justify-center gap-2">
                        <span>Use This Receipt for Allocation</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <!-- ── STEP 2: ALLOCATION BUILDER ── -->
            <div class="space-y-6" x-show="step === 2" x-transition>
                <!-- Header Card displaying balance status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider block">Receipt Amount</span>
                        <span class="text-2xl font-mono font-extrabold text-slate-900 mt-2" x-text="'₹' + formatCurrency(form.amount)"></span>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-slate-455 uppercase tracking-wider block">Total Allocated</span>
                        <span class="text-2xl font-mono font-extrabold text-primary mt-2" x-text="'₹' + formatCurrency(totalAllocated())"></span>
                    </div>
                    <div class="border rounded-2xl p-6 flex flex-col justify-between transition duration-300"
                         :class="isBalanced() ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-white border-slate-200'">
                        <span class="text-[9px] font-bold uppercase tracking-wider block" :class="isBalanced() ? 'text-emerald-600' : 'text-slate-400'">Remaining Balance</span>
                        <div class="flex items-baseline justify-between mt-2">
                            <span class="text-2xl font-mono font-extrabold" :class="isBalanced() ? 'text-emerald-700' : 'text-slate-900'" x-text="'₹' + formatCurrency(remainingBalance())"></span>
                            <template x-if="isBalanced()">
                                <span class="px-2.5 py-0.5 rounded-lg bg-emerald-500 text-white font-extrabold text-[9px] uppercase tracking-wider flex items-center gap-1">
                                    Balanced ✓
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Allocation Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4.5 bg-gradient-to-r from-slate-50 to-slate-100/50 border-b border-slate-150 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Allocation Builder Table</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-6 py-3.5 w-1/4">Allocation Type</th>
                                    <th class="px-6 py-3.5 w-1/3">Target Destination</th>
                                    <th class="px-6 py-3.5 w-1/4 text-right">Amount (₹)</th>
                                    <th class="px-6 py-3.5">Remarks</th>
                                    <th class="px-6 py-3.5 text-center w-12">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <template x-for="(row, idx) in allocations" :key="idx">
                                    <tr class="hover:bg-slate-50/30 transition">
                                        <!-- Allocation Type Dropdown -->
                                        <td class="px-6 py-3">
                                            <select x-model="row.type" @change="row.target_id = ''"
                                                    class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-primary">
                                                <option value="partner">Partner Payout</option>
                                                <option value="supplier">Supplier Bill</option>
                                                <option value="refund">Customer Refund</option>
                                                <option value="general">General Fund</option>
                                            </select>
                                        </td>
                                        <!-- Dynamic Target Dropdown using custom HTML generation -->
                                        <td class="px-6 py-3">
                                            <select x-model="row.target_id" x-html="getTargetOptionsHtml(row.type, row.target_id)"
                                                    class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-primary">
                                            </select>
                                        </td>
                                        <!-- Amount Field -->
                                        <td class="px-6 py-3 text-right">
                                            <div class="relative flex items-center justify-end">
                                                <span class="absolute left-2 font-bold text-slate-400">₹</span>
                                                <input type="number" x-model.number="row.amount" step="0.01" min="0" placeholder="0.00"
                                                       class="w-full px-2.5 py-2 pl-6 text-right bg-slate-50 border border-slate-200 rounded-lg font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary">
                                            </div>
                                        </td>
                                        <!-- Remarks -->
                                        <td class="px-6 py-3">
                                            <input type="text" x-model="row.remarks" placeholder="Enter remarks..."
                                                   class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-1 focus:ring-primary text-xs font-semibold text-slate-700">
                                        </td>
                                        <!-- Remove Row -->
                                        <td class="px-6 py-3 text-center">
                                            <button type="button" @click="removeAllocationRow(idx)"
                                                    class="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg transition duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Row Action Box -->
                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                        <button type="button" @click="addAllocationRow()"
                                class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 hover:border-slate-300 transition text-xs font-extrabold uppercase tracking-wider rounded-xl">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Allocation Row</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <button type="button" @click="step = 1"
                                    class="px-5 py-2.5 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-extrabold uppercase rounded-xl transition tracking-wider">
                                Back
                            </button>
                            <button type="button" @click="goToStep3()" :disabled="!isBalanced()"
                                    :class="!isBalanced() ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200' : 'bg-gradient-to-r from-primary to-primary-700 hover:brightness-110 text-white shadow-soft'"
                                    class="px-6 py-2.5 text-xs font-extrabold uppercase rounded-xl transition duration-300 tracking-wider flex items-center gap-2 border border-primary-500/20">
                                <span>Continue to Review</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 3: REVIEW & PROCESS ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-show="step === 3" x-transition>
                
                <!-- Left Column: Summary & Ledger Preview (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Summary Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider pb-2 border-b border-slate-100">Review Summary</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-xs leading-relaxed">
                            <div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Receipt Amount</div>
                                <div class="mt-0.5 font-mono font-extrabold text-slate-900 text-sm" x-text="'₹' + formatCurrency(form.amount)"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Partner Allocation</div>
                                <div class="mt-0.5 font-mono font-bold text-primary" x-text="'₹' + formatCurrency(getSummaryAmount('partner'))"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Supplier Allocation</div>
                                <div class="mt-0.5 font-mono font-bold text-slate-800" x-text="'₹' + formatCurrency(getSummaryAmount('supplier'))"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Customer Refund</div>
                                <div class="mt-0.5 font-mono font-bold text-rose-600" x-text="'₹' + formatCurrency(getSummaryAmount('refund'))"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-455 uppercase tracking-wider">General Fund</div>
                                <div class="mt-0.5 font-mono font-bold text-emerald-600" x-text="'₹' + formatCurrency(getSummaryAmount('general'))"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</div>
                                <div class="mt-0.5 font-mono font-bold text-slate-400">₹0.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Ledger Entry Preview Table -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4.5 bg-gradient-to-r from-slate-50 to-slate-100/50 border-b border-slate-150">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Ledger Particulars (Double-Entry Matrix Preview)</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        <th class="px-6 py-3.5">Ledger Head / Account</th>
                                        <th class="px-6 py-3.5">Narration</th>
                                        <th class="px-6 py-3.5 text-right">Debit (DR)</th>
                                        <th class="px-6 py-3.5 text-right">Credit (CR)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                                    <!-- Dr Line (destination bank account receiving the customer cash) -->
                                    <tr class="bg-primary/5 font-semibold">
                                        <td class="px-6 py-3.5 text-slate-900" x-text="destAccountName || 'Destination Bank Account'"></td>
                                        <td class="px-6 py-3.5 text-slate-500">Intake collection receipt allocation</td>
                                        <td class="px-6 py-3.5 text-right font-mono font-extrabold text-emerald-700" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-6 py-3.5 text-right font-mono text-slate-300">—</td>
                                    </tr>

                                    <!-- Cr Lines for allocations -->
                                    <template x-for="(alloc, idx) in allocations" :key="'preview-'+idx">
                                        <tr>
                                            <td class="px-6 py-3.5 font-bold text-slate-850" x-text="getPreviewAccountName(alloc)"></td>
                                            <td class="px-6 py-3.5 text-slate-500" x-text="getPreviewNarration(alloc)"></td>
                                            <td class="px-6 py-3.5 text-right font-mono text-slate-300">—</td>
                                            <td class="px-6 py-3.5 text-right font-mono font-bold text-rose-600" x-text="'₹' + formatCurrency(alloc.amount)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-50 border-t-2 border-slate-200 font-extrabold text-xs">
                                        <td colspan="2" class="px-6 py-3.5 text-slate-600 uppercase">Total</td>
                                        <td class="px-6 py-3.5 text-right font-mono text-emerald-700" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-6 py-3.5 text-right font-mono text-rose-600" x-text="'₹' + formatCurrency(form.amount)"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visualizer Chart Panel (1/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between h-full min-h-[450px]">
                    <div class="space-y-4">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider pb-2 border-b border-slate-100">Transaction Visualizer</h3>
                        
                        <!-- ApexCharts donut chart container -->
                        <div id="splitChart" class="flex justify-center items-center py-4"></div>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-slate-100">
                        <div class="flex gap-2">
                            <button type="button" @click="step = 2"
                                    class="flex-1 py-3 text-center border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-extrabold uppercase rounded-xl transition tracking-wider">
                                Back
                            </button>
                            <button type="submit"
                                    class="flex-[2] py-3 text-center bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white shadow-soft text-xs font-extrabold uppercase rounded-xl transition tracking-wider flex items-center justify-center gap-1.5 border border-emerald-500/20">
                                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Process Receipt & Split</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ── SCRIPTS ── -->
    <script>
        function receiptAllocationWorkspace() {
            return {
                step: 1, // Step 1: Select Receipt, Step 2: Allocate Funds, Step 3: Review & Process
                allReceipts: @json($recentReceipts->values()),
                generalFunds: @json($assetAccounts->values()),
                
                // Selection state
                selectedReceiptId: '',
                selectedReceipt: null,
                selectedReceiptLabel: '',

                // Form validation metadata
                form: {
                    project_id: '',
                    unit_id: '',
                    date: '{{ date('Y-m-d') }}',
                    destination_account_id: '',
                    credit_account_id: '',
                    amount: 0.00,
                    narration: '',
                },
                
                // Targets mapping fetched dynamically
                targets: { partners: [], pending_bills: [], cancelled_sales: [] },
                
                // Search & filters for step 1
                searchQuery: '',
                filterProject: '',
                filterCustomer: '',

                // Step 2 allocations builder rows array
                allocations: [],

                // Target account helpers for preview names
                customerName: '',
                destAccountName: '',

                init() {
                    this.$watch('step', value => {
                        if (value === 3) {
                            this.initChart();
                        }
                    });
                },
                filteredReceipts() {
                    return this.allReceipts.filter(r => {
                        const matchesSearch = !this.searchQuery || 
                            r.ref.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            r.customer_name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesProject = !this.filterProject || r.project_id == this.filterProject;
                        const matchesCustomer = !this.filterCustomer || r.customer_id == this.filterCustomer;
                        return matchesSearch && matchesProject && matchesCustomer;
                    });
                },
                selectReceipt(r) {
                    if (this.selectedReceiptId == r.id) {
                        // Unselect
                        this.selectedReceiptId = '';
                        this.selectedReceipt = null;
                        this.selectedReceiptLabel = '';
                        this.form.project_id = '';
                        this.form.unit_id = '';
                        this.form.amount = 0.00;
                        this.form.credit_account_id = '';
                        this.form.destination_account_id = '';
                        this.form.narration = '';
                    } else {
                        // Select
                        this.selectedReceiptId = r.id;
                        this.selectedReceipt = r;
                        this.selectedReceiptLabel = r.ref + ' — ' + r.customer_name;
                        this.form.project_id = r.project_id || '';
                        this.form.date = r.date || '{{ date('Y-m-d') }}';
                        this.form.amount = parseFloat(r.amount) || 0.00;
                        this.form.credit_account_id = r.customer_ledger_account_id || '';
                        this.form.destination_account_id = ''; // Let them pick destination bank
                        this.form.narration = r.remarks || '';

                        // Prefill units
                        if (r.project_id) {
                            fetch(`/vouchers/project/${r.project_id}/units`)
                                .then(res => res.json())
                                .then(data => {
                                    this.units = data;
                                    this.form.unit_id = r.unit_id || '';
                                });
                        }

                        // Load split targets
                        this.fetchTargets();
                    }
                    this.updateNames();
                },
                fetchTargets() {
                    const projectId = this.form.project_id || '';
                    if (!projectId) {
                        this.targets = { partners: [], pending_bills: [], cancelled_sales: [] };
                        return;
                    }
                    fetch('/api/receipt/targets?project_id=' + projectId)
                        .then(res => res.json())
                        .then(data => {
                            this.targets = data;
                            // Initialize with default allocation rows matching your diagram
                            this.allocations = [
                                { type: 'partner', target_id: '', amount: 0, remarks: 'Partner Share allocation' },
                                { type: 'supplier', target_id: '', amount: 0, remarks: 'Supplier liability clearing' },
                                { type: 'refund', target_id: '', amount: 0, remarks: 'Customer cancellation refund' }
                            ];
                            // Preselect targets if they exist
                            if (data.partners && data.partners.length > 0) {
                                this.allocations[0].target_id = data.partners[0].id;
                            }
                            if (data.pending_bills && data.pending_bills.length > 0) {
                                this.allocations[1].target_id = data.pending_bills[0].id;
                            }
                            if (data.cancelled_sales && data.cancelled_sales.length > 0) {
                                this.allocations[2].target_id = data.cancelled_sales[0].id;
                            }
                        })
                        .catch(() => {
                            this.targets = { partners: [], pending_bills: [], cancelled_sales: [] };
                        });
                },
                addAllocationRow() {
                    this.allocations.push({
                        type: 'partner',
                        target_id: '',
                        amount: 0.00,
                        remarks: ''
                    });
                },
                removeAllocationRow(idx) {
                    this.allocations.splice(idx, 1);
                },
                getTargetOptionsHtml(type, selectedId) {
                    let html = '<option value="">-- Select Target --</option>';
                    if (type === 'partner') {
                        this.targets.partners.forEach(p => {
                            html += `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.name}</option>`;
                        });
                    } else if (type === 'supplier') {
                        this.targets.pending_bills.forEach(b => {
                            const bal = Number(b.balance).toLocaleString('en-IN', {minimumFractionDigits: 2});
                            html += `<option value="${b.id}" ${b.id == selectedId ? 'selected' : ''}>${b.bill_number} — ${b.supplier_name} (Bal: ₹${bal})</option>`;
                        });
                    } else if (type === 'refund') {
                        this.targets.cancelled_sales.forEach(r => {
                            html += `<option value="${r.id}" ${r.id == selectedId ? 'selected' : ''}>${r.label}</option>`;
                        });
                    } else if (type === 'general') {
                        this.generalFunds.forEach(gf => {
                            html += `<option value="${gf.id}" ${gf.id == selectedId ? 'selected' : ''}>${gf.name}</option>`;
                        });
                    }
                    return html;
                },
                totalAllocated() {
                    return this.allocations.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                },
                remainingBalance() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    return parseFloat((amt - this.totalAllocated()).toFixed(2));
                },
                isBalanced() {
                    return this.remainingBalance() === 0.00 && this.form.amount > 0;
                },
                getSummaryAmount(type) {
                    return this.allocations
                        .filter(a => a.type === type)
                        .reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                },
                goToStep3() {
                    if (this.isBalanced()) {
                        this.step = 3;
                    }
                },
                getPreviewAccountName(alloc) {
                    if (alloc.type === 'partner') {
                        const p = this.targets.partners.find(x => x.id == alloc.target_id);
                        return p ? `${p.name} (Partner Drawing)` : 'Partner Account';
                    } else if (alloc.type === 'supplier') {
                        const b = this.targets.pending_bills.find(x => x.id == alloc.target_id);
                        return b ? `${b.supplier_name} (Supplier Account Payable)` : 'Supplier Account';
                    } else if (alloc.type === 'refund') {
                        const r = this.targets.cancelled_sales.find(x => x.id == alloc.target_id);
                        return r ? `Customer Refund Ledger [${r.label.split(' — ')[0] || 'N/A'}]` : 'Customer Refund Ledger';
                    } else if (alloc.type === 'general') {
                        const gf = this.generalFunds.find(x => x.id == alloc.target_id);
                        return gf ? gf.name : 'General Fund';
                    }
                    return 'Particular Ledger';
                },
                getPreviewNarration(alloc) {
                    let text = '';
                    if (alloc.type === 'partner') text = 'Partner share drawings drawings';
                    else if (alloc.type === 'supplier') text = 'Clear pending supplier invoice';
                    else if (alloc.type === 'refund') text = 'Customer booking cancellation refund';
                    else if (alloc.type === 'general') text = 'Fund transfer to ledger';

                    if (alloc.remarks) {
                        text += ` (${alloc.remarks})`;
                    }
                    return text;
                },
                updateNames() {
                    this.$nextTick(() => {
                        const custEl = document.getElementById('credit_account_id');
                        this.customerName = custEl ? custEl.options[custEl.selectedIndex]?.text : '';
                        
                        const destEl = document.getElementById('destination_account_id');
                        this.destAccountName = destEl ? destEl.options[destEl.selectedIndex]?.text : '';
                    });
                },
                initChart() {
                    this.$nextTick(() => {
                        const partnerAmt = this.getSummaryAmount('partner');
                        const supplierAmt = this.getSummaryAmount('supplier');
                        const refundAmt = this.getSummaryAmount('refund');
                        const generalAmt = this.getSummaryAmount('general');

                        const options = {
                            chart: {
                                type: 'donut',
                                height: 320
                            },
                            series: [partnerAmt, supplierAmt, refundAmt, generalAmt],
                            labels: ['Partner Allocation', 'Supplier Allocation', 'Customer Refund', 'General Fund'],
                            colors: ['#a38c29', '#6c665d', '#e11d48', '#059669'],
                            legend: {
                                position: 'bottom',
                                fontSize: '11px',
                                fontFamily: 'Inter, sans-serif',
                                labels: {
                                    colors: '#475569'
                                }
                            },
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '10px',
                                    fontFamily: 'Inter, sans-serif'
                                },
                                formatter: function (val, opts) {
                                    return opts.w.globals.series[opts.seriesIndex].toLocaleString('en-IN', {
                                        style: 'currency',
                                        currency: 'INR',
                                        maximumFractionDigits: 0
                                    });
                                }
                            }
                        };
                        
                        const chartEl = document.querySelector("#splitChart");
                        if (chartEl) {
                            chartEl.innerHTML = '';
                            const chart = new ApexCharts(chartEl, options);
                            chart.render();
                        }
                    });
                },
                onSubmit(e) {
                    if (!this.isBalanced()) {
                        e.preventDefault();
                        alert('Remaining balance must be balanced to zero to post splits.');
                        return false;
                    }
                    return true;
                },
                formatCurrency(val) {
                    return Number(val.toFixed(2)).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
</x-erp-layout>
