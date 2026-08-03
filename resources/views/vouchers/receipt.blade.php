<x-erp-layout>
    <x-slot:title>Receipt Allocation Management</x-slot:title>
    <x-slot:headerTitle>Receipt Allocation Workspace</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptAllocationWorkspace()" x-init="init()">
        
        <!-- Top Navigation Bar & Action Shortcut -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Vouchers</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Receipt Allocation Workspace</span>
            </div>

            <!-- Collect New Payment Shortcut -->
            <a href="{{ route('emi-collections.receipts') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-slate-800 self-start sm:self-auto">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Collect New Customer Payment</span>
            </a>
        </div>

        <form action="{{ route('vouchers.receipt.store') }}" method="POST" @submit="onSubmit($event)">
            @csrf

            <!-- Hidden input fields -->
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6 relative">
                <div class="max-w-4xl mx-auto flex items-center justify-between relative z-10">
                    
                    <!-- Step 1 -->
                    <div class="flex items-center gap-3.5 transition cursor-pointer relative group" @click="step = 1">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 transition-all duration-300 shadow-sm"
                             :class="step >= 1 ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/25 ring-4 ring-[#a38c29]/15' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                            <template x-if="selectedReceiptId && step > 1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="!selectedReceiptId || step === 1">
                                <span>1</span>
                            </template>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider block" :class="step >= 1 ? 'text-slate-900' : 'text-slate-400'">1. Select Receipt</span>
                            <span class="text-[11px] text-slate-500 font-semibold block mt-0.5" x-text="selectedReceiptId ? selectedReceipt.ref : 'Choose receipt to split'"></span>
                        </div>
                    </div>

                    <!-- Line 1-2 -->
                    <div class="flex-1 mx-4 h-1 rounded-full transition-all duration-500" :class="step >= 2 ? 'bg-[#a38c29]' : 'bg-slate-100'"></div>

                    <!-- Step 2 -->
                    <div class="flex items-center gap-3.5 transition relative group" :class="selectedReceiptId ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed'" @click="selectedReceiptId ? step = 2 : null">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 transition-all duration-300 shadow-sm"
                             :class="step >= 2 ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/25 ring-4 ring-[#a38c29]/15' : 'bg-white text-slate-400 border-2 border-slate-200'">
                            <template x-if="isBalanced() && step > 2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="!isBalanced() || step <= 2">
                                <span>2</span>
                            </template>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider block" :class="step >= 2 ? 'text-slate-900' : 'text-slate-400'">2. Allocate Funds</span>
                            <span class="text-[11px] text-slate-500 font-semibold block mt-0.5" x-text="step >= 2 ? (isBalanced() ? 'Balanced (₹0.00 Left)' : 'Configure targets') : 'Split across targets'"></span>
                        </div>
                    </div>

                    <!-- Line 2-3 -->
                    <div class="flex-1 mx-4 h-1 rounded-full transition-all duration-500" :class="step >= 3 ? 'bg-[#a38c29]' : 'bg-slate-100'"></div>

                    <!-- Step 3 -->
                    <div class="flex items-center gap-3.5 transition relative group" :class="isBalanced() ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed'" @click="isBalanced() ? step = 3 : null">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 transition-all duration-300 shadow-sm"
                             :class="step >= 3 ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/25 ring-4 ring-[#a38c29]/15' : 'bg-white text-slate-400 border-2 border-slate-200'">
                            <span>3</span>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider block" :class="step >= 3 ? 'text-slate-900' : 'text-slate-400'">3. Review & Journal</span>
                            <span class="text-[11px] text-slate-500 font-semibold block mt-0.5">Post double-entry vouchers</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                    <div class="flex items-center gap-2 mb-1 text-rose-900 font-black">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Please correct the errors below:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 font-semibold text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ── STEP 1: SELECT RECEIPT ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" x-show="step === 1" x-transition>
                
                <!-- Left Panel: Receipts Directory (2/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden lg:col-span-2 flex flex-col justify-between">
                    <div>
                        <!-- Header with Filters (Light Theme) -->
                        <div class="px-6 py-5 bg-slate-50 text-slate-900 border-b border-slate-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">Inbound Payment Receipts</h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Select any receipt to preview details and configure splits</p>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-100 border border-amber-300 text-amber-800">
                                        <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                        <span class="text-xs font-black" x-text="filteredReceipts().filter(r => !r.is_allocated).length"></span>
                                        <span class="text-xs font-bold text-amber-900">Unallocated</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <span class="text-xs font-black" x-text="filteredReceipts().filter(r => r.is_allocated).length"></span>
                                        <span class="text-xs font-bold text-emerald-900">Allocated</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Inputs -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                <div class="relative flex items-center">
                                    <span class="absolute left-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </span>
                                    <input type="text" x-model="searchQuery" placeholder="Search receipt #, customer name..."
                                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all shadow-xs">
                                </div>
                                
                                <div class="relative flex items-center">
                                    <span class="absolute left-3.5 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </span>
                                    <select x-model="filterProject" class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all cursor-pointer shadow-xs">
                                        <option value="" class="text-slate-800">All Projects</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}" class="text-slate-800">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Pills -->
                        <div class="p-2 bg-slate-100 rounded-xl m-4 border border-slate-200/80">
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <button type="button" @click="activeTab = 'unallocated'"
                                        class="py-2.5 px-4 rounded-lg font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none"
                                        :class="activeTab === 'unallocated' ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'">
                                    <span class="w-2 h-2 rounded-full bg-white" x-show="activeTab === 'unallocated'"></span>
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse" x-show="activeTab !== 'unallocated'"></span>
                                    Unallocated Receipts
                                    <span class="ml-1 px-2 py-0.5 rounded-md text-[10px] font-black"
                                          :class="activeTab === 'unallocated' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                          x-text="filteredReceipts().filter(r => !r.is_allocated).length"></span>
                                </button>

                                <button type="button" @click="activeTab = 'allocated'"
                                        class="py-2.5 px-4 rounded-lg font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none"
                                        :class="activeTab === 'allocated' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'">
                                    <span class="w-2 h-2 rounded-full bg-white" x-show="activeTab === 'allocated'"></span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500" x-show="activeTab !== 'allocated'"></span>
                                    Allocated Receipts
                                    <span class="ml-1 px-2 py-0.5 rounded-md text-[10px] font-black"
                                          :class="activeTab === 'allocated' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                                          x-text="filteredReceipts().filter(r => r.is_allocated).length"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Table View -->
                        <div class="overflow-x-auto" style="max-height: 440px; overflow-y: auto;">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 z-10 shadow-xs">
                                    <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-widest">
                                        <th class="px-5 py-3.5">Action</th>
                                        <th class="px-5 py-3.5">Receipt #</th>
                                        <th class="px-5 py-3.5">Date</th>
                                        <th class="px-5 py-3.5">Customer Name</th>
                                        <th class="px-5 py-3.5">Project / Unit</th>
                                        <th class="px-5 py-3.5 text-right">Intake Amount</th>
                                        <th class="px-5 py-3.5 text-center min-w-[130px] whitespace-nowrap">Mode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <!-- Unallocated Rows -->
                                    <template x-if="activeTab === 'unallocated'">
                                        <template x-for="r in filteredReceipts().filter(r => !r.is_allocated).slice((unallocatedPage - 1) * perPage, unallocatedPage * perPage)" :key="r.id">
                                            <tr @click="selectReceipt(r)"
                                                :class="selectedReceiptId == r.id ? 'bg-[#a38c29]/10 border-l-4 border-l-[#a38c29]' : 'hover:bg-slate-50 cursor-pointer border-l-4 border-l-transparent'"
                                                class="transition-all duration-150 group">
                                                
                                                <td class="px-4 py-4 text-center">
                                                    <button type="button" @click.stop="selectReceipt(r)"
                                                            :class="selectedReceiptId == r.id ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                                            class="px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider transition">
                                                        <span x-text="selectedReceiptId == r.id ? '✓ Selected' : 'Select'"></span>
                                                    </button>
                                                </td>
                                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-2 h-2 rounded-full bg-amber-500 shrink-0 animate-pulse"></div>
                                                        <span x-text="r.ref"></span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 font-medium text-slate-500" x-text="r.date"></td>
                                                <td class="px-5 py-4 font-bold text-slate-900" x-text="r.customer_name"></td>
                                                <td class="px-5 py-4">
                                                    <span class="font-bold text-slate-800" x-text="r.project_name"></span>
                                                    <span class="text-slate-400 mx-1">•</span>
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold" x-text="r.unit_name"></span>
                                                </td>
                                                <td class="px-5 py-4 font-mono font-black text-slate-950 text-right text-sm" x-text="'₹' + formatCurrency(r.amount)"></td>
                                                <td class="px-5 py-4 text-center whitespace-nowrap min-w-[130px]">
                                                    <span :class="
                                                        r.payment_mode && r.payment_mode.toLowerCase() === 'cash' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' :
                                                        (r.payment_mode && r.payment_mode.toLowerCase() === 'cheque' ? 'bg-amber-100 text-amber-800 border border-amber-200' :
                                                        'bg-blue-100 text-blue-800 border border-blue-200')
                                                    " class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider whitespace-nowrap inline-block">
                                                        <span x-text="r.payment_mode || 'N/A'"></span>
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>

                                    <!-- Allocated Rows -->
                                    <template x-if="activeTab === 'allocated'">
                                        <template x-for="r in filteredReceipts().filter(r => r.is_allocated).slice((allocatedPage - 1) * perPage, allocatedPage * perPage)" :key="r.id">
                                            <tr @click="selectReceipt(r)"
                                                :class="selectedReceiptId == r.id ? 'bg-emerald-50 border-l-4 border-l-emerald-600' : 'hover:bg-slate-50 cursor-pointer border-l-4 border-l-transparent'"
                                                class="transition-all duration-150 group opacity-85">
                                                
                                                <td class="px-4 py-4 text-center">
                                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                                        View
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4 font-mono font-bold text-slate-700">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
                                                        <span x-text="r.ref"></span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 font-medium text-slate-400" x-text="r.date"></td>
                                                <td class="px-5 py-4 font-bold text-slate-700" x-text="r.customer_name"></td>
                                                <td class="px-5 py-4">
                                                    <span class="font-bold text-slate-700" x-text="r.project_name"></span>
                                                    <span class="text-slate-300 mx-1">•</span>
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold" x-text="r.unit_name"></span>
                                                </td>
                                                <td class="px-5 py-4 font-mono font-black text-slate-700 text-right text-sm" x-text="'₹' + formatCurrency(r.amount)"></td>
                                                <td class="px-5 py-4 text-center whitespace-nowrap min-w-[130px]">
                                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap inline-block">
                                                        <span x-text="r.payment_mode || 'N/A'"></span>
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>

                                    <!-- Empty State -->
                                    <template x-if="(activeTab === 'unallocated' && filteredReceipts().filter(r => !r.is_allocated).length === 0) || (activeTab === 'allocated' && filteredReceipts().filter(r => r.is_allocated).length === 0)">
                                        <tr>
                                            <td colspan="7" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center gap-3">
                                                    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                    <span class="text-slate-500 font-bold text-xs" x-text="activeTab === 'unallocated' ? 'No unallocated receipts found matching filters.' : 'No allocated receipts found matching filters.'"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Pagination -->
                    <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between" x-show="filteredReceipts().filter(r => activeTab === 'unallocated' ? !r.is_allocated : r.is_allocated).length > 0">
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                            <span>
                                Showing 
                                <span class="text-slate-900 font-extrabold" x-text="((activeTab === 'unallocated' ? unallocatedPage - 1 : allocatedPage - 1) * perPage) + 1"></span> 
                                to 
                                <span class="text-slate-900 font-extrabold" x-text="Math.min((activeTab === 'unallocated' ? unallocatedPage : allocatedPage) * perPage, filteredReceipts().filter(r => activeTab === 'unallocated' ? !r.is_allocated : r.is_allocated).length)"></span> 
                                of 
                                <span class="text-slate-900 font-extrabold" x-text="filteredReceipts().filter(r => activeTab === 'unallocated' ? !r.is_allocated : r.is_allocated).length"></span> 
                                receipts
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                <span class="font-bold text-slate-400">Show:</span>
                                <select x-model.number="perPage" @change="unallocatedPage = 1; allocatedPage = 1;"
                                        class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#a38c29] cursor-pointer">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-1">
                                <button type="button" 
                                        @click="activeTab === 'unallocated' ? (unallocatedPage > 1 && unallocatedPage--) : (allocatedPage > 1 && allocatedPage--)" 
                                        :disabled="activeTab === 'unallocated' ? unallocatedPage <= 1 : allocatedPage <= 1"
                                        class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                    Prev
                                </button>
                                
                                <template x-for="p in getPageNumbers()" :key="p">
                                    <span class="inline-flex items-center">
                                        <span x-show="p === '...'" class="px-2 text-slate-400 font-bold text-xs" x-text="p"></span>
                                        <button type="button" x-show="p !== '...'"
                                                @click="setPage(p)"
                                                x-text="p"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition"
                                                :class="(activeTab === 'unallocated' ? unallocatedPage : allocatedPage) === p ? 'bg-[#a38c29] text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100'"></button>
                                    </span>
                                </template>
                                
                                <button type="button" 
                                        @click="activeTab === 'unallocated' ? (unallocatedPage < getTotalPages() && unallocatedPage++) : (allocatedPage < getTotalPages() && allocatedPage++)" 
                                        :disabled="activeTab === 'unallocated' ? unallocatedPage >= getTotalPages() : allocatedPage >= getTotalPages()"
                                        class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: PROFESSIONAL ENTERPRISE PAYMENT RECEIPT CARD (1/3 width) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between h-full min-h-[580px]">
                    
                    <!-- Card Header (Luxury Gold Theme) -->
                    <div class="px-6 py-5 bg-gradient-to-r from-[#FAF0D7] via-[#F6F3E9] to-white border-b border-[#EAE3CD] text-slate-900 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest">OFFICIAL PAYMENT RECEIPT</div>
                            <div class="text-xs font-extrabold text-slate-900 mt-0.5" x-text="selectedReceipt ? selectedReceipt.ref : 'Receipt Voucher'"></div>
                        </div>
                        <template x-if="selectedReceipt">
                            <span :class="selectedReceipt.is_allocated ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-amber-100 text-amber-900 border-amber-300'"
                                  class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border">
                                <span x-text="selectedReceipt.is_allocated ? 'Allocated' : 'Ready to Allocate'"></span>
                            </span>
                        </template>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between bg-white space-y-4">
                        <template x-if="selectedReceipt">
                            <div class="space-y-4 text-xs">
                                
                                <!-- Enterprise Receipt Box -->
                                <div class="p-4 bg-slate-50 border border-slate-200/90 rounded-2xl space-y-3 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-24 h-24 bg-[#a38c29]/5 rounded-bl-full pointer-events-none"></div>
                                    
                                    <div class="border-b border-slate-200 pb-2 flex justify-between items-center">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RECEIPT INTAKE VOUCHER</span>
                                        <span class="text-xs font-mono font-bold text-slate-500" x-text="selectedReceipt.date"></span>
                                    </div>

                                    <div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RECEIVED FROM (CUSTOMER)</div>
                                        <div class="mt-0.5 font-black text-slate-900 text-base" x-text="selectedReceipt.customer_name"></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200/60">
                                        <div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">PROJECT</div>
                                            <div class="mt-0.5 font-extrabold text-slate-800" x-text="selectedReceipt.project_name"></div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">UNIT NUMBER</div>
                                            <div class="mt-0.5 font-extrabold text-slate-800" x-text="'Unit ' + selectedReceipt.unit_name"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount & Payment Mode (Luxury Gold Theme) -->
                                <div class="grid grid-cols-2 gap-3 p-4 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] text-slate-900 rounded-2xl shadow-xs">
                                    <div>
                                        <div class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider">COLLECTED AMOUNT</div>
                                        <div class="mt-1 font-mono font-black text-[#a38c29] text-lg" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider">PAYMENT MODE</div>
                                        <div class="mt-1.5">
                                            <span :class="
                                                selectedReceipt.payment_mode && selectedReceipt.payment_mode.toLowerCase() === 'cash' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' :
                                                (selectedReceipt.payment_mode && selectedReceipt.payment_mode.toLowerCase() === 'cheque' ? 'bg-amber-100 text-amber-900 border border-amber-300' :
                                                'bg-blue-100 text-blue-800 border border-blue-300')
                                            " class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider whitespace-nowrap inline-block shadow-2xs">
                                                <span x-text="selectedReceipt.payment_mode || 'N/A'"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Allocation Narration / Remarks</label>
                                    <textarea x-model="form.narration" placeholder="Enter narration or allocation reference notes..." rows="2"
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 focus:bg-white focus:ring-2 focus:ring-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition resize-none"></textarea>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedReceipt">
                            <div class="py-20 text-center text-slate-400 italic text-xs flex-grow flex flex-col items-center justify-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                </div>
                                <span class="font-bold text-slate-500">Select any receipt from the left table to preview voucher details.</span>
                            </div>
                        </template>
                        
                        <!-- CTA Buttons including 1-Click Quick Partner Split Shortcut -->
                        <div class="pt-4 border-t border-slate-100 space-y-2">
                            <button type="button" @click="quickSplitPartnerShares()" :disabled="!selectedReceiptId || selectedReceipt?.is_allocated"
                                    :class="(!selectedReceiptId || selectedReceipt?.is_allocated) ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-slate-900 hover:bg-slate-800 text-white shadow-md'"
                                    class="w-full py-3 text-center text-xs font-black rounded-xl transition duration-200 uppercase tracking-wider flex items-center justify-center gap-2 border border-slate-800">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>⚡ 1-Click Quick Partner Equity Split</span>
                            </button>

                            <button type="button" @click="step = 2" :disabled="!selectedReceiptId || selectedReceipt?.is_allocated"
                                    :class="(!selectedReceiptId || selectedReceipt?.is_allocated) ? 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed' : 'bg-[#a38c29] hover:bg-[#8f7a23] text-white shadow-md shadow-[#a38c29]/20'"
                                    class="w-full py-3.5 text-center text-xs font-black rounded-xl transition duration-200 uppercase tracking-wider flex items-center justify-center gap-2">
                                <span x-text="selectedReceipt?.is_allocated ? 'Receipt Already Allocated' : 'Configure Custom Splits ➔'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 2: ALLOCATION BUILDER ── -->
            <div class="space-y-6" x-show="step === 2" x-transition>
                
                <!-- Status Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center justify-between">
                        <div class="space-y-1">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Total Receipt Amount</span>
                            <div class="text-2xl font-mono font-black text-slate-900" x-text="'₹' + formatCurrency(form.amount)"></div>
                            <span class="text-[10px] text-slate-500 font-semibold block" x-text="selectedReceipt ? selectedReceipt.customer_name : ''"></span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Total Allocated</span>
                                <div class="text-2xl font-mono font-black text-[#a38c29]" x-text="'₹' + formatCurrency(totalAllocated())"></div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center font-bold">
                                <span x-text="allocations.length"></span>
                            </div>
                        </div>
                        
                        <div class="mt-3 space-y-1">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase">
                                <span>Allocation Progress</span>
                                <span x-text="form.amount > 0 ? Math.min(100, Math.round((totalAllocated() / form.amount) * 100)) + '%' : '0%'"></span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#a38c29] rounded-full transition-all duration-300"
                                     :style="'width: ' + (form.amount > 0 ? Math.min(100, (totalAllocated() / form.amount) * 100)) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border p-5 flex items-center justify-between transition-all duration-300 shadow-sm"
                         :class="isBalanced() ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-600/20' : 'bg-rose-50 border-rose-200 text-rose-900'">
                        
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest block"
                                  :class="isBalanced() ? 'text-emerald-100' : 'text-rose-600'">Remaining Balance</span>
                            <div class="text-2xl font-mono font-black"
                                  :class="isBalanced() ? 'text-white' : 'text-rose-900'"
                                  x-text="'₹' + formatCurrency(remainingBalance())"></div>
                            <span class="text-[11px] font-bold block"
                                  :class="isBalanced() ? 'text-emerald-100' : 'text-rose-700'">
                                <span x-text="isBalanced() ? '✓ Funds 100% Balanced & Ready' : '⚠️ Balance must be allocated to ₹0.00'"></span>
                            </span>
                        </div>
                        
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                             :class="isBalanced() ? 'bg-white/20 text-white' : 'bg-rose-200/60 text-rose-700'">
                            <template x-if="isBalanced()">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="!isBalanced()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- CATEGORY QUICK-ADD PILLS & TOOLBAR (Luxury Gold Theme) -->
                <div class="bg-gradient-to-r from-[#FAF0D7] via-[#F6F3E9] to-white rounded-2xl p-4 text-slate-900 flex flex-wrap items-center justify-between gap-3 border border-[#EAE3CD] shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-[#8a7522]">Quick Category Add:</span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button type="button" @click="addCategoryRow('partner')" class="px-2.5 py-1 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>🤝 + Partner</span>
                            </button>
                            <button type="button" @click="addCategoryRow('broker')" class="px-2.5 py-1 bg-[#FAF0D7] hover:bg-[#F6F3E9] text-[#8a7522] border border-[#a38c29]/40 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>🏷️ + Broker Commission</span>
                            </button>
                            <button type="button" @click="addCategoryRow('supplier')" class="px-2.5 py-1 bg-blue-100 hover:bg-blue-200 text-blue-900 border border-blue-300 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>🏗️ + Supplier</span>
                            </button>
                            <button type="button" @click="addCategoryRow('refund')" class="px-2.5 py-1 bg-rose-100 hover:bg-rose-200 text-rose-900 border border-rose-300 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>↩️ + Refund</span>
                            </button>
                            <button type="button" @click="addCategoryRow('general')" class="px-2.5 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-900 border border-emerald-300 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>🏦 + General</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <button type="button" @click="autoAllocatePartnerShares()"
                                class="px-3.5 py-2 bg-[#a38c29] hover:bg-[#8f7a23] text-white text-xs font-extrabold uppercase rounded-xl transition flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Auto-Split Partner Shares</span>
                        </button>

                        <button type="button" @click="allocateAllToGeneral()"
                                class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-extrabold uppercase rounded-xl transition flex items-center gap-2 border border-slate-300 shadow-2xs">
                            <span>100% General Reserve</span>
                        </button>

                        <button type="button" @click="clearAllAllocations()"
                                class="px-3 py-2 bg-rose-100 hover:bg-rose-200 text-rose-900 text-xs font-extrabold uppercase rounded-xl transition flex items-center gap-1.5 border border-rose-300 shadow-2xs">
                            <span>Clear All</span>
                        </button>
                    </div>
                </div>

                <!-- ALLOCATION TABLE -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-[#a38c29] text-white border-b border-[#8a7522] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-white">Dynamic Allocation Rows</h3>
                        </div>
                        <span class="text-xs text-amber-100 font-semibold" x-text="allocations.length + ' allocation rows configured'"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3.5" style="width:20%">Allocation Category</th>
                                    <th class="px-4 py-3.5" style="width:32%">Target Destination Ledger</th>
                                    <th class="px-4 py-3.5 text-right" style="width:22%">Amount (₹)</th>
                                    <th class="px-4 py-3.5" style="width:20%">Remarks / Reference</th>
                                    <th class="px-4 py-3.5 text-center" style="width:6%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <template x-for="(row, idx) in allocations" :key="idx">
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-4 py-3.5 align-top">
                                            <select x-model="row.type" @change="row.target_id = ''; recalculatePartnerSplits();"
                                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer transition">
                                                <option value="partner">🤝 Partner Share Payout</option>
                                                <option value="broker">🏷️ Broker Commission</option>
                                                <option value="supplier">🏗️ Supplier Vendor Bill</option>
                                                <option value="refund">↩️ Customer Refund</option>
                                                <option value="general">🏦 General Reserve</option>
                                            </select>
                                        </td>

                                        <td class="px-4 py-3.5 align-top">
                                            <select x-model="row.target_id" @change="recalculatePartnerSplits()"
                                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29] cursor-pointer transition">
                                                <option value="">-- Select Target Ledger --</option>
                                                <template x-for="opt in getFilteredTargets(row.type)" :key="opt.id">
                                                    <option :value="opt.id" x-text="opt.name" :selected="opt.id == row.target_id"></option>
                                                </template>
                                            </select>
                                        </td>

                                        <!-- Amount Field with Quick Fill Remaining Button -->
                                        <td class="px-4 py-3.5 align-top text-right">
                                            <div class="flex flex-col items-end gap-1.5 w-full">
                                                <div class="flex items-center justify-end gap-1.5 w-full">
                                                    <div class="relative flex items-center justify-end flex-1">
                                                        <span class="absolute left-3 font-mono font-bold text-slate-400">₹</span>
                                                        <input type="number" x-model.number="row.amount" step="0.01" min="0" placeholder="0.00"
                                                               @input="row.type === 'partner' ? handlePartnerInput(row) : validateNonPartnerAmount(row)"
                                                               class="w-full pl-7 pr-3 py-2.5 text-right font-mono font-black text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5 align-top">
                                            <input type="text" x-model="row.remarks" placeholder="Enter line description..."
                                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition">
                                        </td>

                                        <td class="px-4 py-3.5 align-top text-center">
                                            <button type="button" @click="removeAllocationRow(idx); recalculatePartnerSplits();"
                                                    class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition inline-flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="allocations.length === 0">
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                            No allocation rows added yet. Click "+ Add Custom Allocation Row" below to start.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <button type="button" @click="addAllocationRow(); recalculatePartnerSplits();"
                                class="w-full sm:w-auto px-5 py-3 bg-white border border-slate-300 hover:bg-slate-100 text-slate-800 transition font-black text-xs uppercase tracking-wider rounded-xl shadow-xs flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Custom Allocation Row</span>
                        </button>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <button type="button" @click="step = 1"
                                    class="px-6 py-3 border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-black uppercase rounded-xl transition tracking-wider">
                                Back
                            </button>
                            <button type="button" @click="goToStep3()" :disabled="!isBalanced()"
                                    :class="!isBalanced() ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-[#a38c29] hover:bg-[#8f7a23] text-white shadow-md shadow-[#a38c29]/20'"
                                    class="px-7 py-3 text-xs font-black uppercase rounded-xl transition duration-200 tracking-wider flex items-center justify-center gap-2">
                                <span>Continue to Review & Post ➔</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── STEP 3: REVIEW & PROCESS ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" x-show="step === 3" x-transition>
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Allocation Summary Breakdown</h3>
                            <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase">
                                ✓ Fully Balanced
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 text-xs">
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Receipt Intake</div>
                                <div class="mt-1 font-mono font-black text-slate-900 text-base" x-text="'₹' + formatCurrency(form.amount)"></div>
                            </div>
                            <div class="p-3 bg-amber-500/10 rounded-xl">
                                <div class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Partner Share</div>
                                <div class="mt-1 font-mono font-black text-[#a38c29] text-base" x-text="'₹' + formatCurrency(getSummaryAmount('partner'))"></div>
                            </div>
                            <div class="p-3 rounded-xl" style="background:rgba(163,140,41,0.08); border:1px solid rgba(163,140,41,0.2)">
                                <div class="text-[10px] font-bold uppercase tracking-wider" style="color:#8e7a23">Broker Commission</div>
                                <div class="mt-1 font-mono font-black text-base" style="color:#a38c29" x-text="'₹' + formatCurrency(getSummaryAmount('broker'))"></div>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-xl">
                                <div class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">Supplier Bills</div>
                                <div class="mt-1 font-mono font-black text-blue-900 text-base" x-text="'₹' + formatCurrency(getSummaryAmount('supplier'))"></div>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-xl">
                                <div class="text-[10px] font-bold text-rose-700 uppercase tracking-wider">Customer Refunds</div>
                                <div class="mt-1 font-mono font-black text-rose-700 text-base" x-text="'₹' + formatCurrency(getSummaryAmount('refund'))"></div>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-xl">
                                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">General Reserve</div>
                                <div class="mt-1 font-mono font-black text-emerald-800 text-base" x-text="'₹' + formatCurrency(getSummaryAmount('general'))"></div>
                            </div>
                            <div class="p-3 bg-slate-100 rounded-xl">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Remaining Unallocated</div>
                                <div class="mt-1 font-mono font-black text-slate-500 text-base">₹0.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#a38c29] text-white border-b border-[#8a7522] flex items-center justify-between">
                            <h3 class="text-xs font-black uppercase tracking-wider text-white">Double-Entry Journal Matrix Preview</h3>
                            <span class="text-xs text-amber-100 font-semibold">Automatic Ledger Posting</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                                        <th class="px-6 py-3.5">Ledger Account / Particulars</th>
                                        <th class="px-6 py-3.5">Transaction Narration</th>
                                        <th class="px-6 py-3.5 text-right">Debit (DR)</th>
                                        <th class="px-6 py-3.5 text-right">Credit (CR)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <tr class="bg-slate-50 font-extrabold">
                                        <td class="px-6 py-4 text-slate-900 flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded text-[10px]">DR</span>
                                            <span x-text="destAccountName || 'Destination Bank/Cash Account'"></span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 font-medium">Customer intake collection receipt split</td>
                                        <td class="px-6 py-4 text-right font-mono font-black text-rose-700 text-sm" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-300">—</td>
                                    </tr>

                                    <template x-for="(alloc, idx) in allocations" :key="'preview-'+idx">
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-bold text-slate-800 pl-10 flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-[10px]">CR</span>
                                                <span x-text="getPreviewAccountName(alloc)"></span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 font-medium" x-text="getPreviewNarration(alloc)"></td>
                                            <td class="px-6 py-4 text-right font-mono text-slate-300">—</td>
                                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-700 text-sm" x-text="'₹' + formatCurrency(alloc.amount)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100 border-t-2 border-slate-200 font-black text-xs">
                                        <td colspan="2" class="px-6 py-4 text-slate-700 uppercase tracking-wider">Total Balanced Voucher</td>
                                        <td class="px-6 py-4 text-right font-mono text-rose-700 text-sm" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-6 py-4 text-right font-mono text-emerald-700 text-sm" x-text="'₹' + formatCurrency(form.amount)"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between h-full min-h-[480px]">
                    <div class="space-y-4">
                        <div class="pb-3 border-b border-slate-100 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29]"></span>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Split Visualizer Chart</h3>
                        </div>
                        
                        <div id="splitChart" class="flex justify-center items-center py-4"></div>
                    </div>

                    <div class="space-y-3 pt-6 border-t border-slate-100">
                        <div class="flex gap-2">
                            <button type="button" @click="step = 2"
                                    class="flex-1 py-3 text-center border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-black uppercase rounded-xl transition">
                                Back
                            </button>
                            <button type="submit"
                                    class="flex-[2] py-3 text-center bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/20 text-xs font-black uppercase rounded-xl transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Post Voucher & Split</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Validation Error Modal -->
        <div x-show="validationError" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeErrorModal()"></div>
            
            <div x-show="validationError" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-sm w-full transform transition-all border border-slate-200">
                
                <div class="p-6 text-center space-y-4">
                    <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight mb-1">Limit Exceeded</h3>
                        <p class="text-sm text-slate-500 font-medium" x-text="validationError"></p>
                    </div>
                </div>
                
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    <button type="button" @click="closeErrorModal()" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-slate-900 px-4 py-2.5 text-sm font-black uppercase text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition">
                        Okay, got it
                    </button>
                </div>
            </div>
        </div>

    </div>
    <!-- ── ALPINE JS CONTROLLER ── -->
    <script>
        function receiptAllocationWorkspace() {
            return {
                step: 1,
                allReceipts: @json($recentReceipts->values()),
                generalFunds: @json($assetAccounts->values()),
                
                selectedReceiptId: '',
                selectedReceipt: null,
                selectedReceiptLabel: '',

                validationError: '',
                closeErrorModal() {
                    this.validationError = '';
                },

                form: {
                    project_id: '',
                    unit_id: '',
                    date: '{{ date('Y-m-d') }}',
                    destination_account_id: '',
                    credit_account_id: '',
                    amount: 0.00,
                    narration: '',
                },
                
                targets: { partners: [], pending_bills: [], cancelled_sales: [], default_shares: [] },
                
                searchQuery: '',
                filterProject: '{{ request('project_id') ?: ($projects->first()?->id ?? "") }}',
                filterCustomer: '',

                allocations: [],

                customerName: '',
                destAccountName: '',

                activeTab: 'unallocated',
                unallocatedPage: 1,
                allocatedPage: 1,
                perPage: 10,

                init() {
                    this.$watch('step', value => {
                        if (value === 3) {
                            this.initChart();
                        }
                    });
                    this.$watch('searchQuery', () => { this.unallocatedPage = 1; this.allocatedPage = 1; });
                    this.$watch('filterProject', () => { this.unallocatedPage = 1; this.allocatedPage = 1; });
                    this.$watch('filterCustomer', () => { this.unallocatedPage = 1; this.allocatedPage = 1; });
                    this.$watch('activeTab', () => { this.unallocatedPage = 1; this.allocatedPage = 1; });

                    // AUTO-SELECT FIRST UNALLOCATED RECEIPT ON LOAD
                    this.$nextTick(() => {
                        const firstUnallocated = this.allReceipts.find(r => !r.is_allocated);
                        if (firstUnallocated) {
                            this.selectReceipt(firstUnallocated);
                        } else if (this.allReceipts.length > 0) {
                            this.selectReceipt(this.allReceipts[0]);
                        }
                    });
                },
                filteredReceipts() {
                    let filtered = this.allReceipts.filter(r => {
                        const matchesSearch = !this.searchQuery || 
                            (r.ref || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            (r.customer_name || '').toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesProject = !this.filterProject || r.project_id == this.filterProject;
                        const matchesCustomer = !this.filterCustomer || r.customer_id == this.filterCustomer;
                        return matchesSearch && matchesProject && matchesCustomer;
                    });
                    filtered.sort((a, b) => {
                        if (a.is_allocated !== b.is_allocated) return a.is_allocated ? 1 : -1;
                        return (b.date || '').localeCompare(a.date || '');
                    });
                    return filtered;
                },
                selectReceipt(r) {
                    if (this.selectedReceiptId == r.id && this.selectedReceiptId !== '') {
                        // Keep selected
                    } else {
                        this.selectedReceiptId = r.id;
                        this.selectedReceipt = r;
                        this.selectedReceiptLabel = r.ref + ' — ' + r.customer_name;
                        this.form.project_id = r.project_id || '';
                        this.form.date = r.date || '{{ date('Y-m-d') }}';
                        this.form.amount = parseFloat(r.amount) || 0.00;
                        this.form.credit_account_id = r.customer_ledger_account_id || '';
                        this.form.destination_account_id = r.resolved_destination_account_id || '';
                        this.form.narration = r.remarks || '';

                        this.fetchTargets();
                    }
                    this.updateNames();
                },
                quickSplitPartnerShares() {
                    if (!this.selectedReceiptId || this.selectedReceipt?.is_allocated) return;
                    this.autoAllocatePartnerShares();
                    if (this.isBalanced()) {
                        this.step = 3;
                    } else {
                        this.step = 2;
                    }
                },
                fetchTargets() {
                    const projectId = this.form.project_id || '';
                    if (!projectId) {
                        this.targets = { partners: [], pending_bills: [], cancelled_sales: [], default_shares: [], pending_brokers: [] };
                        return;
                    }
                    fetch("{{ url('/api/receipt/targets') }}?project_id=" + projectId)
                        .then(res => res.json())
                        .then(data => {
                            this.targets = data;
                            this.allocations = [];
                            
                            if (data.default_shares && data.default_shares.length > 0) {
                                data.default_shares.forEach(share => {
                                    this.allocations.push({
                                        type: 'partner',
                                        target_id: share.partner_id,
                                        amount: 0.00,
                                        remarks: `Partner Share (${share.share_pct}%)`
                                    });
                                });
                            } else if (data.partners && data.partners.length > 0) {
                                this.allocations.push({
                                    type: 'partner',
                                    target_id: data.partners[0].id,
                                    amount: 0.00,
                                    remarks: 'Partner Share allocation'
                                });
                            }

                            if (data.pending_brokers && data.pending_brokers.length > 0) {
                                this.allocations.push({
                                    type: 'broker',
                                    target_id: data.pending_brokers[0].id,
                                    amount: 0.00,
                                    remarks: 'Broker commission payout'
                                });
                            }

                            if (data.pending_bills && data.pending_bills.length > 0) {
                                this.allocations.push({
                                    type: 'supplier',
                                    target_id: data.pending_bills[0].id,
                                    amount: 0.00,
                                    remarks: 'Supplier liability clearing'
                                });
                            }

                            if (data.cancelled_sales && data.cancelled_sales.length > 0) {
                                this.allocations.push({
                                    type: 'refund',
                                    target_id: data.cancelled_sales[0].id,
                                    amount: 0.00,
                                    remarks: 'Customer cancellation refund'
                                });
                            }

                            this.recalculatePartnerSplits();
                        })
                        .catch(() => {
                            this.targets = { partners: [], pending_bills: [], cancelled_sales: [], default_shares: [], pending_brokers: [] };
                        });
                },
                autoAllocatePartnerShares() {
                    const nonPartnerRows = this.allocations.filter(a => a.type !== 'partner');
                    this.allocations = [...nonPartnerRows];
                    
                    if (this.targets.default_shares && this.targets.default_shares.length > 0) {
                        this.targets.default_shares.forEach(share => {
                            this.allocations.push({
                                type: 'partner',
                                target_id: share.partner_id,
                                amount: 0.00,
                                remarks: `Partner Share (${share.share_pct}%)`,
                                is_locked: false
                            });
                        });
                    } else if (this.targets.partners && this.targets.partners.length > 0) {
                        this.targets.partners.forEach(p => {
                            this.allocations.push({
                                type: 'partner',
                                target_id: p.id,
                                amount: 0.00,
                                remarks: 'Partner Share allocation',
                                is_locked: false
                            });
                        });
                    }
                    this.recalculatePartnerSplits();
                },
                allocateAllToGeneral() {
                    this.allocations = [];
                    const genAccount = this.generalFunds[0];
                    this.allocations.push({
                        type: 'general',
                        target_id: genAccount ? genAccount.id : '',
                        amount: parseFloat(this.form.amount) || 0.00,
                        remarks: '100% General Fund Reserve allocation'
                    });
                },
                clearAllAllocations() {
                    this.allocations = [];
                },
                addCategoryRow(type) {
                    let targetId = '';
                    let remarks = '';
                    if (type === 'broker' && this.targets.pending_brokers && this.targets.pending_brokers.length > 0) {
                        targetId = this.targets.pending_brokers[0].id;
                        remarks = 'Broker commission payout';
                    } else if (type === 'supplier' && this.targets.pending_bills && this.targets.pending_bills.length > 0) {
                        targetId = this.targets.pending_bills[0].id;
                        remarks = 'Supplier liability clearing';
                    } else if (type === 'refund' && this.targets.cancelled_sales && this.targets.cancelled_sales.length > 0) {
                        targetId = this.targets.cancelled_sales[0].id;
                        remarks = 'Customer cancellation refund';
                    } else if (type === 'partner' && this.targets.partners && this.targets.partners.length > 0) {
                        targetId = this.targets.partners[0].id;
                        remarks = 'Partner Share allocation';
                    }
                    this.allocations.push({
                        type: type,
                        target_id: targetId,
                        amount: 0.00,
                        remarks: remarks
                    });
                },
                fillRemainingBalance(idx) {
                    const current = parseFloat(this.allocations[idx].amount) || 0.0;
                    const remaining = this.remainingBalance() + current;
                    this.allocations[idx].amount = Math.max(0, parseFloat(remaining.toFixed(2)));
                    this.recalculatePartnerSplits();
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
                recalculatePartnerSplits() {
                    if (!this.targets.default_shares || this.targets.default_shares.length === 0) {
                        return;
                    }
                    const nonPartnerRows = this.allocations.filter(a => a.type !== 'partner');
                    const nonPartnerSum = nonPartnerRows.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                    
                    const partnerRows = this.allocations.filter(a => a.type === 'partner');
                    if (partnerRows.length === 0) return;

                    const lockedPartners = partnerRows.filter(a => a.is_locked);
                    const lockedSum = lockedPartners.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                    
                    const unlockedPartners = partnerRows.filter(a => !a.is_locked);
                    if (unlockedPartners.length === 0) return;

                    const balanceToSplit = parseFloat((this.form.amount - nonPartnerSum - lockedSum).toFixed(2));

                    // Calculate total relative percentage weight of currently UNLOCKED partners
                    let totalRelativePct = 0.0;
                    unlockedPartners.forEach(row => {
                        const share = this.targets.default_shares.find(s => s.partner_id == row.target_id);
                        if (share) totalRelativePct += parseFloat(share.share_pct);
                    });

                    if (totalRelativePct === 0) return;

                    let distributedAmount = 0.0;
                    unlockedPartners.forEach((row, index) => {
                        const share = this.targets.default_shares.find(s => s.partner_id == row.target_id);
                        const originalPct = share ? parseFloat(share.share_pct) : 0.0;
                        
                        let amt = 0.0;
                        if (index === unlockedPartners.length - 1) {
                            amt = parseFloat(Math.max(0, balanceToSplit - distributedAmount).toFixed(2));
                        } else {
                            // Split based on their relative share weight against the total weight of remaining partners
                            amt = parseFloat(Math.max(0, balanceToSplit * (originalPct / totalRelativePct)).toFixed(2));
                            distributedAmount += amt;
                        }
                        row.amount = amt;
                        row.remarks = `Partner Share (${originalPct}%) allocation`;
                    });
                },
                validateNonPartnerAmount(row) {
                    if (row.type === 'partner') return;
                    if (row.type === 'general') {
                        this.recalculatePartnerSplits();
                        return;
                    }
                    
                    let maxAllowed = null;
                    if (row.type === 'broker' && this.targets.pending_brokers) {
                        const broker = this.targets.pending_brokers.find(b => b.id == row.target_id);
                        if (broker) maxAllowed = parseFloat(broker.pending_amount);
                    } else if (row.type === 'supplier' && this.targets.pending_bills) {
                        const bill = this.targets.pending_bills.find(b => b.id == row.target_id);
                        if (bill) maxAllowed = parseFloat(bill.balance);
                    } else if (row.type === 'refund' && this.targets.cancelled_sales) {
                        const sale = this.targets.cancelled_sales.find(s => s.id == row.target_id);
                        if (sale) maxAllowed = parseFloat(sale.remaining);
                    }

                    if (maxAllowed !== null && parseFloat(row.amount) > maxAllowed) {
                        row.amount = maxAllowed;
                        this.validationError = `Amount cannot exceed the pending balance (₹${maxAllowed}) for this target.`;
                    }

                    this.recalculatePartnerSplits();
                },
                handlePartnerInput(row) {
                    if (row.type !== 'partner') return;
                    row.is_locked = true;
                    
                    const nonPartnerRows = this.allocations.filter(a => a.type !== 'partner');
                    const nonPartnerSum = nonPartnerRows.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                    
                    const otherLockedPartners = this.allocations.filter(a => a.type === 'partner' && a.is_locked && a !== row);
                    const otherLockedSum = otherLockedPartners.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                    
                    const maxAllowed = parseFloat((this.form.amount - nonPartnerSum - otherLockedSum).toFixed(2));
                    
                    if (parseFloat(row.amount) > maxAllowed) {
                        row.amount = maxAllowed;
                        this.validationError = `Amount cannot exceed the total remaining balance (₹${maxAllowed}).`;
                    }
                    this.recalculatePartnerSplits();
                },
                getFilteredTargets(type) {
                    if (type === 'partner') {
                        return this.targets.partners.map(p => ({ id: p.id, name: p.name }));
                    }
                    if (type === 'broker') {
                        return (this.targets.pending_brokers || []).map(b => ({
                            id: b.id,
                            name: b.name
                        }));
                    }
                    if (type === 'supplier') {
                        return this.targets.pending_bills.map(b => ({
                            id: b.id,
                            name: `${b.bill_number} — ${b.supplier_name} (Bal: ₹${this.formatCurrency(b.balance)})`
                        }));
                    }
                    if (type === 'refund') {
                        return this.targets.cancelled_sales.map(s => ({ id: s.id, name: s.label }));
                    }
                    if (type === 'general') {
                        return this.generalFunds.map(gf => ({ id: gf.id, name: gf.name }));
                    }
                    return [];
                },
                totalAllocated() {
                    return this.allocations.reduce((sum, a) => sum + (parseFloat(a.amount) || 0.0), 0);
                },
                remainingBalance() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    return parseFloat((amt - this.totalAllocated()).toFixed(2));
                },
                isBalanced() {
                    return Math.abs(this.remainingBalance()) < 0.01 && this.form.amount > 0;
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
                        return p ? `${p.name} (Partner Capital Share)` : 'Partner Account';
                    } else if (alloc.type === 'broker') {
                        const b = (this.targets.pending_brokers || []).find(x => x.id == alloc.target_id);
                        return b ? `Broker Commission Payable — ${b.name.split(' (A/C:')[0]}` : 'Broker Commission Account';
                    } else if (alloc.type === 'supplier') {
                        const b = this.targets.pending_bills.find(x => x.id == alloc.target_id);
                        return b ? `${b.supplier_name} (Supplier Account Payable)` : 'Supplier Account';
                    } else if (alloc.type === 'refund') {
                        const r = this.targets.cancelled_sales.find(x => x.id == alloc.target_id);
                        return r ? `Customer Cancellation Refund [${r.label.split(' — ')[0] || 'N/A'}]` : 'Customer Refund Ledger';
                    } else if (alloc.type === 'general') {
                        const gf = this.generalFunds.find(x => x.id == alloc.target_id);
                        return gf ? gf.name : 'General Reserve';
                    }
                    return 'Particular Ledger';
                },
                getPreviewNarration(alloc) {
                    let text = '';
                    if (alloc.type === 'partner') text = 'Partner share drawings';
                    else if (alloc.type === 'broker') text = 'Broker commission cash payout';
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
                        const destAcc = this.generalFunds.find(x => x.id == this.form.destination_account_id);
                        this.destAccountName = destAcc ? destAcc.name : 'Destination Account';
                    });
                },
                initChart() {
                    this.$nextTick(() => {
                        const partnerAmt = this.getSummaryAmount('partner');
                        const brokerAmt = this.getSummaryAmount('broker');
                        const supplierAmt = this.getSummaryAmount('supplier');
                        const refundAmt = this.getSummaryAmount('refund');
                        const generalAmt = this.getSummaryAmount('general');

                        const options = {
                            chart: {
                                type: 'donut',
                                height: 300
                            },
                            series: [partnerAmt, brokerAmt, supplierAmt, refundAmt, generalAmt],
                            labels: ['Partner Share', 'Broker Commission', 'Supplier Bills', 'Customer Refund', 'General Fund'],
                            colors: ['#a38c29', '#d97706', '#3b82f6', '#f43f5e', '#10b981'],
                            legend: {
                                position: 'bottom',
                                fontSize: '12px',
                                fontFamily: 'Inter, sans-serif',
                                labels: { colors: '#334155' }
                            },
                            dataLabels: {
                                enabled: true,
                                style: { fontSize: '11px', fontFamily: 'Inter, sans-serif' },
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
                    if (!this.form.destination_account_id) {
                        e.preventDefault();
                        alert('Please select a destination Bank / Cash Account.');
                        return false;
                    }
                    for (let i = 0; i < this.allocations.length; i++) {
                        const row = this.allocations[i];
                        const amt = parseFloat(row.amount) || 0.0;
                        if (amt > 0 && !row.target_id) {
                            e.preventDefault();
                            alert(`Please select a target destination for row #${i + 1} (${row.type}).`);
                            return false;
                        }
                    }
                    if (!this.isBalanced()) {
                        e.preventDefault();
                        alert('Remaining balance must be balanced to zero to post splits.');
                        return false;
                    }
                    return true;
                },
                formatCurrency(val) {
                    const num = typeof val === 'number' ? val : parseFloat(val);
                    return isNaN(num) ? '0.00' : num.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },
                amountInWords(amount) {
                    if (!amount || isNaN(amount) || parseFloat(amount) <= 0) return '';
                    const num = Math.floor(parseFloat(amount));
                    const paise = Math.round((parseFloat(amount) - num) * 100);

                    const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                                   'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                    const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

                    function convert(n) {
                        if (n < 20) return units[n];
                        if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + units[n % 10] : '');
                        if (n < 1000) return units[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + convert(n % 100) : '');
                        if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + convert(n % 1000) : '');
                        if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + convert(n % 100000) : '');
                        return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + convert(n % 10000000) : '');
                    }

                    let words = convert(num);
                    if (!words) return '';
                    let result = words + ' Rupees';
                    if (paise > 0) {
                        result += ' and ' + convert(paise) + ' Paise';
                    }
                    result += ' Only';
                    return result;
                },
                getPageNumbers() {
                    let list = this.filteredReceipts().filter(r => this.activeTab === 'unallocated' ? !r.is_allocated : r.is_allocated);
                    let totalItems = list.length;
                    let last = Math.max(1, Math.ceil(totalItems / (this.perPage || 10)));
                    let current = this.activeTab === 'unallocated' ? this.unallocatedPage : this.allocatedPage;
                    let range = [];
                    let rangeWithDots = [];
                    let l;

                    for (let i = 1; i <= last; i++) {
                        if (i === 1 || i === last || (i >= current - 2 && i <= current + 2)) {
                            range.push(i);
                        }
                    }

                    for (let i of range) {
                        if (l) {
                            if (i - l === 2) {
                                rangeWithDots.push(l + 1);
                            } else if (i - l !== 1) {
                                rangeWithDots.push('...');
                            }
                        }
                        rangeWithDots.push(i);
                        l = i;
                    }

                    return rangeWithDots;
                },
                getTotalPages() {
                    let list = this.filteredReceipts().filter(r => this.activeTab === 'unallocated' ? !r.is_allocated : r.is_allocated);
                    return Math.max(1, Math.ceil(list.length / (this.perPage || 10)));
                },
                setPage(p) {
                    if (this.activeTab === 'unallocated') {
                        this.unallocatedPage = p;
                    } else {
                        this.allocatedPage = p;
                    }
                }
            }
        }
    </script>
</x-erp-layout>
