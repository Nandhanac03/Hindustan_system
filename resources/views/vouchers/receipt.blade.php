<x-erp-layout>
    <x-slot:title>Receipt Management</x-slot:title>
    <x-slot:headerTitle>Receipt Management</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptAllocationWorkspace()" x-init="init()">
        <!-- Breadcrumb Navigation -->
        <div class="text-[11px] font-semibold text-slate-400 tracking-wide uppercase flex items-center gap-1.5 -mt-2">
            <span>Home</span>
            <span class="text-slate-300">›</span>
            <span>Vouchers</span>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-extrabold">Receipt</span>
        </div>

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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/50 p-6 mb-6 relative">
                <div class="max-w-4xl mx-auto flex items-center justify-between relative">
                    <!-- Step 1 Dot -->
                    <div class="flex items-center gap-4 transition cursor-pointer relative py-2" @click="step = 1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 transition duration-300"
                             :class="step >= 1 ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/20' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                            1
                        </div>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider block" :class="step >= 1 ? 'text-slate-900' : 'text-slate-500'">Select Receipt</span>
                            <span class="text-[8px] text-slate-400 font-medium block mt-0.5">Inbound receipt list</span>
                        </div>
                    </div>

                    <!-- Step 2 Dot -->
                    <div class="flex items-center gap-4 transition relative py-2" :class="selectedReceiptId ? 'cursor-pointer' : 'opacity-60'" @click="selectedReceiptId ? step = 2 : null">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 border-2 transition duration-300"
                             :class="step >= 2 ? 'bg-[#a38c29] text-white border-transparent shadow-md shadow-[#a38c29]/20' : 'bg-white text-slate-400 border-slate-200'">
                            2
                        </div>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider block" :class="step >= 2 ? 'text-slate-900' : 'text-slate-500'">Allocate Funds</span>
                            <span class="text-[8px] text-slate-400 font-medium block mt-0.5">Dynamic split table</span>
                        </div>
                    </div>

                    <!-- Step 3 Dot -->
                    <div class="flex items-center gap-4 transition relative py-2" :class="isBalanced() ? 'cursor-pointer' : 'opacity-60'" @click="isBalanced() ? step = 3 : null">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 border-2 transition duration-300"
                             :class="step >= 3 ? 'bg-[#a38c29] text-white border-transparent shadow-md shadow-[#a38c29]/20' : 'bg-white text-slate-400 border-slate-250'">
                            3
                        </div>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider block" :class="step >= 3 ? 'text-slate-900' : 'text-slate-500'">Review & Process</span>
                            <span class="text-[8px] text-slate-400 font-medium block mt-0.5">Ledger entry preview</span>
                        </div>
                    </div>
                </div>

                <!-- Golden bottom active indicator line, sliding or shifting based on step -->
                <div class="absolute bottom-0 h-1 bg-[#a38c29] transition-all duration-300"
                     :style="step === 1 ? 'left: 6%; width: 28%;' : (step === 2 ? 'left: 36%; width: 28%;' : 'left: 66%; width: 28%;')"></div>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" x-show="step === 1" x-transition>
                
                <!-- Left Panel: Unallocated Receipt List (2/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/40 overflow-hidden lg:col-span-2 flex flex-col justify-between">
                    <div>
                        <!-- Sleek Dark Navy Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-[#2D2B24] to-[#1F1D1A] text-white border-b border-[#a38c29]/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white/10 text-primary flex items-center justify-center shrink-0 border border-white/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-100">Unallocated Receipt List</h3>
                            </div>
                            
                            <!-- Search & Filter Controls inside header -->
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </span>
                                    <input type="text" x-model="searchQuery" placeholder="Search customer/receipt..."
                                           class="pl-9 pr-3 py-1.5 bg-white/10 border border-slate-700 hover:border-slate-500 rounded-lg text-xs font-semibold text-white placeholder-slate-400 focus:bg-white focus:text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-white transition-all w-52">
                                </div>
                                
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                    </span>
                                    <select x-model="filterProject" class="pl-9 pr-8 py-1.5 bg-white/10 border border-slate-700 hover:border-slate-500 rounded-lg text-xs font-semibold text-slate-350 focus:bg-white focus:text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-white transition-all appearance-none cursor-pointer">
                                        <option value="" class="text-slate-800">Filter Project</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}" class="text-slate-800">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <th class="px-6 py-4">Receipt No</th>
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Customer</th>
                                        <th class="px-6 py-4">Project</th>
                                        <th class="px-6 py-4">Unit</th>
                                        <th class="px-6 py-4 text-right">Amount Received</th>
                                        <th class="px-6 py-4">Mode</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                                    <template x-for="r in filteredReceipts()" :key="r.id">
                                        <tr @click="selectReceipt(r)"
                                            :class="selectedReceiptId == r.id ? 'bg-[#a38c29]/10 border-l-4 border-[#a38c29] font-bold' : 'hover:bg-slate-50/50 cursor-pointer'"
                                            class="transition duration-150">
                                            <td class="px-6 py-5 font-mono text-slate-900 flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/></svg>
                                                </div>
                                                <span class="font-bold text-slate-900" x-text="r.ref"></span>
                                            </td>
                                            <td class="px-6 py-5 text-slate-500 font-semibold" x-text="r.date"></td>
                                            <td class="px-6 py-5 font-bold text-slate-955" x-text="r.customer_name"></td>
                                            <td class="px-6 py-5 text-slate-500 font-semibold" x-text="r.project_name"></td>
                                            <td class="px-6 py-5 font-extrabold text-slate-800" x-text="r.unit_name"></td>
                                            <td class="px-6 py-5 font-mono font-extrabold text-slate-950 text-right" x-text="'₹' + formatCurrency(r.amount)"></td>
                                            <td class="px-6 py-5">
                                                <span :class="
                                                    r.payment_mode.toLowerCase() === 'cash' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                                    (r.payment_mode.toLowerCase() === 'cheque' ? 'bg-amber-50 text-amber-700 border border-amber-100' :
                                                    'bg-blue-50 text-blue-700 border border-blue-100')
                                                " class="px-2.5 py-1 rounded text-[8px] font-extrabold uppercase tracking-wide">
                                                    <span x-text="r.payment_mode"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <span :class="r.is_allocated ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-250'"
                                                      class="px-2.5 py-1 rounded text-[8px] font-extrabold uppercase tracking-wide"
                                                      x-text="r.is_allocated ? 'Allocated' : 'Unallocated'">
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="filteredReceipts().length === 0">
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">No unallocated receipts found matching filters.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Table Footer Pagination -->
                    <div class="px-6 py-5 bg-white border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-450 uppercase tracking-wide">
                        <span x-text="'Showing 1 to ' + filteredReceipts().length + ' of ' + filteredReceipts().length + ' entries'"></span>
                        
                        <div class="flex items-center gap-1.5">
                            <button type="button" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition text-slate-650 font-bold">‹</button>
                            <button type="button" class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center font-extrabold shadow-glow">1</button>
                            <button type="button" class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition text-slate-650 font-bold">›</button>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Receipt Details (1/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/40 overflow-hidden flex flex-col justify-between h-full min-h-[580px]">
                    <!-- Dark Blue Header with Gold accents -->
                    <div class="px-6 py-5 bg-gradient-to-r from-[#2D2B24] to-[#1F1D1A] border-b border-[#a38c29]/20 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0 border border-[#EFECE1]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-extrabold text-white uppercase tracking-wider">Receipt Details</h3>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between bg-white">
                        <template x-if="selectedReceipt">
                            <div class="space-y-4 text-xs flex-grow">
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Receipt Number</div>
                                    <div class="mt-0.5 text-sm font-mono font-extrabold text-[#9C6D3B] uppercase" x-text="selectedReceipt.ref"></div>
                                </div>
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Customer</div>
                                    <div class="mt-0.5 font-bold text-slate-900" x-text="selectedReceipt.customer_name"></div>
                                </div>
                                <div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Project / Unit</div>
                                    <div class="mt-0.5 font-bold text-slate-700" x-text="selectedReceipt.project_name + ' • ' + selectedReceipt.unit_name"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Amount Received</div>
                                        <div class="mt-0.5 font-mono font-extrabold text-slate-900 text-sm" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Remaining Balance</div>
                                        <div class="mt-0.5 font-mono font-extrabold text-emerald-600 text-sm" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Already Allocated</div>
                                        <div class="mt-0.5 font-mono text-slate-450 font-bold">₹0.00</div>
                                    </div>
                                    <div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Payment Mode</div>
                                        <div class="mt-1">
                                            <span :class="
                                                selectedReceipt.payment_mode.toLowerCase() === 'cash' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                                (selectedReceipt.payment_mode.toLowerCase() === 'cheque' ? 'bg-amber-50 text-amber-700 border border-amber-100' :
                                                'bg-blue-50 text-blue-700 border border-blue-100')
                                            " class="px-2.5 py-1 rounded text-[8px] font-extrabold uppercase tracking-wide">
                                                <span x-text="selectedReceipt.payment_mode"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-slate-100">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Receipt Narration</label>
                                    <textarea x-model="form.narration" placeholder="Enter narration..." rows="2"
                                              class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs text-slate-700 font-semibold focus:outline-none transition resize-none"></textarea>
                                </div>

                                <!-- Select Destination Bank Account for split processing -->
                                <div class="pt-3 border-t border-slate-100">
                                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Process Into Bank / Cash Account</label>
                                    <select name="destination_account_id" x-model="form.destination_account_id" required
                                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                        <option value="">-- Select Destination Ledger --</option>
                                        @foreach($assetAccounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedReceipt">
                            <div class="py-16 text-center text-slate-400 italic text-xs flex-grow flex flex-col items-center justify-center gap-3">
                                <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Select an unallocated receipt from the list to display details.</span>
                            </div>
                        </template>
                        
                        <div class="pt-4 border-t border-slate-100/50">
                            <button type="button" @click="step = 2" :disabled="!selectedReceiptId || !form.destination_account_id"
                                    :class="!selectedReceiptId || !form.destination_account_id ? 'bg-slate-50 text-slate-400 border border-slate-200 cursor-not-allowed' : 'bg-gradient-to-r from-[#a38c29] to-[#806c1d] hover:brightness-110 text-white shadow-md shadow-[#a38c29]/20'"
                                    class="w-full py-3.5 text-center text-[10px] font-extrabold rounded-xl transition duration-300 uppercase tracking-wider flex items-center justify-center gap-2 border border-[#a38c29]/10">
                                <span>Use This Receipt for Allocation</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 2: ALLOCATION BUILDER ── -->
            <div class="space-y-6" x-show="step === 2" x-transition>
                <!-- Header Cards displaying balance status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Card 1: Receipt Amount -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden flex items-center justify-between group hover:shadow-md transition duration-300">
                        <div class="space-y-2">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Receipt Amount</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-mono font-black text-slate-900" x-text="'₹' + formatCurrency(form.amount)"></span>
                            </div>
                            <span class="text-[8px] text-slate-400 font-medium block">Total intake amount collected</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29] transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>

                    <!-- Card 2: Total Allocated -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition duration-300 min-h-[110px]">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Allocated</span>
                                <span class="text-2xl font-mono font-black text-primary" x-text="'₹' + formatCurrency(totalAllocated())"></span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            </div>
                        </div>
                        
                        <!-- Allocation progress bar -->
                        <div class="mt-4 space-y-1.5">
                            <div class="flex justify-between items-center text-[9px] font-bold text-slate-400 uppercase tracking-wide">
                                <span>Allocation Progress</span>
                                <span x-text="form.amount > 0 ? Math.min(100, Math.round((totalAllocated() / form.amount) * 100)) + '%' : '0%'"></span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden relative">
                                <div class="h-full bg-gradient-to-r from-[#a38c29] to-[#c7b252] rounded-full transition-all duration-300"
                                     :style="'width: ' + (form.amount > 0 ? Math.min(100, (totalAllocated() / form.amount) * 100)) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Remaining Balance -->
                    <div class="rounded-2xl border transition-all duration-300 p-6 flex items-center justify-between group shadow-sm min-h-[110px]"
                         :class="isBalanced() 
                            ? 'bg-gradient-to-r from-emerald-500 to-teal-650 border-transparent text-white shadow-md shadow-emerald-500/20' 
                            : 'bg-white border-slate-200 hover:shadow-md'">
                        
                        <div class="space-y-1.5">
                            <span class="text-[9px] font-bold uppercase tracking-widest block"
                                  :class="isBalanced() ? 'text-emerald-100' : 'text-rose-500'">Remaining Balance</span>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-mono font-black"
                                      :class="isBalanced() ? 'text-white' : 'text-slate-900'"
                                      x-text="'₹' + formatCurrency(remainingBalance())"></span>
                            </div>
                            <span class="text-[8px] font-medium block"
                                  :class="isBalanced() ? 'text-emerald-50' : 'text-slate-400'">
                                <span x-text="isBalanced() ? 'Funds perfectly allocated' : 'Must be allocated to zero'"></span>
                            </span>
                        </div>
                        
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300"
                             :class="isBalanced() 
                                ? 'bg-white/20 text-white' 
                                : 'bg-rose-50 text-rose-500 border border-rose-100 animate-pulse'">
                            <template x-if="isBalanced()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="!isBalanced()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Allocation Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/40 overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-[#2D2B24] to-[#1F1D1A] text-white border-b border-[#a38c29]/20 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-100">Allocation Builder Table</h3>
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
                                                    class="w-full px-2.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-xs font-bold text-slate-850 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer">
                                                <option value="partner">Partner Payout</option>
                                                <option value="supplier">Supplier Bill</option>
                                                <option value="refund">Customer Refund</option>
                                                <option value="general">General Fund</option>
                                            </select>
                                        </td>
                                        <!-- Dynamic Target Dropdown -->
                                        <td class="px-6 py-3">
                                            <select x-model="row.target_id" x-html="getTargetOptionsHtml(row.type, row.target_id)"
                                                    class="w-full px-2.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-xs font-bold text-slate-850 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer">
                                            </select>
                                        </td>
                                        <!-- Amount Field -->
                                        <td class="px-6 py-3 text-right">
                                            <div class="relative flex items-center justify-end">
                                                <span class="absolute left-2 font-bold text-slate-400">₹</span>
                                                <input type="number" x-model.number="row.amount" step="0.01" min="0" placeholder="0.00"
                                                       class="w-full px-2.5 py-2.5 pl-6 text-right bg-slate-50 border border-slate-200 rounded-lg font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition">
                                            </div>
                                        </td>
                                        <!-- Remarks -->
                                        <td class="px-6 py-3">
                                            <input type="text" x-model="row.remarks" placeholder="Enter remarks..."
                                                   class="w-full px-2.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] text-xs font-semibold text-slate-700 transition">
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
                                class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 hover:border-slate-350 text-slate-700 transition text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Allocation Row</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <button type="button" @click="step = 1"
                                    class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-100 text-slate-655 text-xs font-extrabold uppercase rounded-xl transition tracking-wider shadow-sm">
                                Back
                            </button>
                            <button type="button" @click="goToStep3()" :disabled="!isBalanced()"
                                    :class="!isBalanced() ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200' : 'bg-gradient-to-r from-[#a38c29] to-[#806c1d] hover:brightness-110 text-white shadow-md shadow-[#a38c29]/20'"
                                    class="px-6 py-2.5 text-xs font-extrabold uppercase rounded-xl transition duration-300 tracking-wider flex items-center gap-2">
                                <span>Continue to Review</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 3: REVIEW & PROCESS ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" x-show="step === 3" x-transition>
                
                <!-- Left Column: Summary & Ledger Preview (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Summary Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/40 p-6 space-y-4">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider pb-2 border-b border-slate-100">Review Summary</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-xs leading-relaxed">
                            <div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Receipt Amount</div>
                                <div class="mt-0.5 font-mono font-extrabold text-slate-900 text-sm" x-text="'₹' + formatCurrency(form.amount)"></div>
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Partner Allocation</div>
                                <div class="mt-0.5 font-mono font-bold text-[#a38c29]" x-text="'₹' + formatCurrency(getSummaryAmount('partner'))"></div>
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
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/40 overflow-hidden">
                        <div class="px-6 py-5 bg-gradient-to-r from-[#2D2B24] to-[#1F1D1A] text-white border-b border-[#a38c29]/20">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-100">Ledger Particulars (Double-Entry Matrix Preview)</h3>
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
                                    <!-- Dr Line -->
                                    <tr class="bg-primary/5 font-semibold">
                                        <td class="px-6 py-3.5 text-slate-900" x-text="destAccountName || 'Destination Bank Account'"></td>
                                        <td class="px-6 py-3.5 text-slate-500">Intake collection receipt allocation</td>
                                        <td class="px-6 py-3.5 text-right font-mono font-extrabold text-emerald-700" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-6 py-3.5 text-right font-mono text-slate-300">—</td>
                                    </tr>

                                    <!-- Cr Lines -->
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

                <!-- Right Column: Visualizer Chart Panel (1/3 width) - Dark Combo Card! -->
                <div class="bg-[#1F1D1A] border border-[#a38c29]/20 rounded-2xl shadow-xl shadow-slate-900/10 p-6 flex flex-col justify-between h-full min-h-[450px]">
                    <div class="space-y-4">
                        <h3 class="text-xs font-extrabold text-white uppercase tracking-wider pb-2 border-b border-slate-800 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] inline-block shadow-glow"></span>
                            <span>Transaction Visualizer</span>
                        </h3>
                        
                        <!-- ApexCharts donut chart container -->
                        <div id="splitChart" class="flex justify-center items-center py-4"></div>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-slate-800">
                        <div class="flex gap-2">
                            <button type="button" @click="step = 2"
                                    class="flex-1 py-3 text-center border border-slate-700 bg-slate-900 hover:bg-slate-850 text-slate-300 text-xs font-extrabold uppercase rounded-xl transition tracking-wider">
                                Back
                            </button>
                            <button type="submit"
                                    class="flex-[2] py-3 text-center bg-gradient-to-r from-emerald-600 to-emerald-700 hover:brightness-110 text-white shadow-soft text-xs font-extrabold uppercase rounded-xl transition tracking-wider flex items-center justify-center gap-1.5 border border-emerald-500/20">
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
                filterProject: '{{ $projects->first()?->id ?? "" }}',
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
                                    colors: '#f1f5f9'
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
