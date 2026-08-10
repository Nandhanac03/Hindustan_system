<x-erp-layout title="Receipt Management" headerTitle="Receipt Management Workspace">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptManagementWorkspace()" x-init="init()">
        
        {{-- Flash Notifications --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black">✕</button>
            </div>
        @endif

        <!-- Top Header & Action Bar matching Receipt Allocation UI -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Finance & Accounting</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Receipt Management Workspace</span>
            </div>

            <!-- Record New Receipt Button -->
            <button type="button" @click="openAddModal()" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-[#a38c29] self-start sm:self-auto cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ RECORD NEW BANK RECEIPT</span>
            </button>
        </div>

        <!-- ── KPI EXECUTIVE CARDS BAR (Matching Brokerage Management 3-Card Colors & Box Effect) ── -->
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-4">
            <!-- Card 1: Total Collections (Gold Accent) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#a38c29]">Total Corporate Collections</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalCollectionAmount ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">{{ $totalReceiptsCount ?? 0 }} Total Receipts Intake</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Card 2: Unstored Receipts (Emerald Green Accent) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Unstored Receipts</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-wider">PENDING</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ max(0, ($totalReceiptsCount ?? 0) - ($storedCount ?? 0)) }}</h3>
                    <span class="text-[10px] text-emerald-700 font-bold block mt-0.5">Pending Bank Storage</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Card 3: Stored Bank Receipts (Blue/Indigo Accent) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-blue-600 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">Stored Bank Receipts</span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[9px] font-black uppercase tracking-wider">STORED</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalTransferredAmount ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-blue-700 font-bold block mt-0.5">{{ $storedCount ?? 0 }} Stored in Bank</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </div>

        <!-- ── TAB CONTROL: SEPARATE VIEWS FOR INBOUND RECEIPTS vs STORED BANK RECEIPTS (RECEIPT_STORES) ── -->
        <div class="p-2 bg-slate-100 rounded-2xl border border-slate-200">
            <div class="grid grid-cols-2 gap-2 text-xs font-black uppercase tracking-wider">
                <button type="button" @click="activeMainTab = 'inbound'"
                        class="py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2.5 focus:outline-none cursor-pointer"
                        :class="activeMainTab === 'inbound' ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'">
                    <span class="w-2.5 h-2.5 rounded-full bg-white" x-show="activeMainTab === 'inbound'"></span>
                    <span>1. Inbound Receipts Directory</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px]"
                          :class="activeMainTab === 'inbound' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                          x-text="filteredReceipts().length"></span>
                </button>

                <button type="button" @click="activeMainTab = 'stored'"
                        class="py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2.5 focus:outline-none cursor-pointer"
                        :class="activeMainTab === 'stored' ? 'bg-[#a38c29] text-white shadow-md shadow-[#a38c29]/20' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60'">
                    <span class="w-2.5 h-2.5 rounded-full bg-white" x-show="activeMainTab === 'stored'"></span>
                    <span>2. Stored Bank Receipts </span>
                    <span class="px-2 py-0.5 rounded-md text-[10px]"
                          :class="activeMainTab === 'stored' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'"
                          x-text="storedReceipts.length"></span>
                </button>
            </div>
        </div>

        <!-- ── TAB 1: INBOUND RECEIPTS WORKSPACE (2/3 Table + 1/3 Right Voucher Box) ── -->
        <div x-show="activeMainTab === 'inbound'" class="flex flex-row gap-6 items-start w-full">
            
            <!-- Left Panel: Inbound Payment Receipts Directory (2/3 width) -->
            <div class="w-2/3 min-w-0 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between shrink-0">
                <div>
                    <!-- Header with Filters -->
                    <div class="px-6 py-5 bg-slate-50 text-slate-900 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                                    <span>INBOUND CHEQUE & PAYMENT RECEIPTS</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-black uppercase" x-text="filterPaymentMode ? filterPaymentMode + ' MODE' : 'ALL MODES'"></span>
                                </h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Select single or multiple receipts to store amounts in company bank account</p>
                            </div>
                            
                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-100 border border-amber-300 text-amber-800">
                                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                <span class="text-xs font-black" x-text="filteredReceipts().length"></span>
                                <span class="text-xs font-bold text-amber-900">Receipts Found</span>
                            </div>
                        </div>

                        <!-- Filters Grid (Default Payment Mode: Cheque Only) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text" x-model="searchQuery" placeholder="Search receipt #, customer..."
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 hover:border-slate-400 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all">
                            </div>
                            
                            {{-- Payment Mode Filter (Default: Cheque) --}}
                            <div class="relative flex items-center">
                                <select x-model="filterPaymentMode" class="w-full px-3 py-2.5 bg-white border-2 border-blue-400 font-extrabold rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                                    <option value="Cheque">Cheque Payment Mode Only (Default)</option>
                                    <option value="">All Payment Modes</option>
                                    <option value="NEFT/RTGS">NEFT / RTGS / IMPS</option>
                                    <option value="Direct Transfer">Direct Bank Transfer</option>
                                    <option value="UPI">UPI / VPA</option>
                                    <option value="Cash">Cash Deposit</option>
                                </select>
                            </div>

                            <div class="relative flex items-center">
                                <select x-model="filterBank" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                                    <option value="">All Company Bank Accounts</option>
                                    @foreach($companyBankAccounts as $bAcc)
                                        <option value="{{ $bAcc->id }}">{{ $bAcc->bank_name }} {{ $bAcc->account_number ? '('.$bAcc->account_number.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="relative flex items-center">
                                <select x-model="filterStorageStatus" class="w-full px-3 py-2.5 bg-white border-2 border-[#a38c29]/40 font-bold rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                                    <option value="unstored">Unstored Receipts Only (Default)</option>
                                    <option value="stored">Stored In Bank Only</option>
                                    <option value="">All Storage Statuses</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table View with MULTIPLE CHECKBOX SELECTION and SOLID GOLD HEADER -->
                    <div class="overflow-x-auto" style="max-height: 480px; overflow-y: auto;">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky top-0 z-10 bg-[#a38c29] text-white border-b border-[#8a7522] text-[11px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-3 py-3.5 text-center text-white w-10">
                                        <input type="checkbox" @change="toggleSelectAll($event)" :checked="isAllSelected()" class="w-4 h-4 text-[#a38c29] bg-white border-slate-300 rounded focus:ring-[#a38c29] cursor-pointer">
                                    </th>
                                    <th class="px-4 py-3.5 text-white">RECEIPT #</th>
                                    <th class="px-4 py-3.5 text-white">DATE</th>
                                    <th class="px-4 py-3.5 text-white">CUSTOMER / PAYER</th>
                                    <th class="px-4 py-3.5 text-white">COMPANY BANK ACCOUNT</th>
                                    <th class="px-4 py-3.5 text-right text-white">INTAKE AMOUNT</th>
                                    <th class="px-4 py-3.5 text-center text-white">MODE</th>
                                    <th class="px-4 py-3.5 text-center text-white">STORAGE STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                                <template x-for="r in paginatedReceipts()" :key="r.id">
                                    <tr @click="toggleSelectRow(r)"
                                        :class="isSelected(r.id) ? 'bg-[#a38c29]/10 border-l-4 border-l-[#a38c29]' : 'hover:bg-slate-50 cursor-pointer border-l-4 border-l-transparent'"
                                        class="transition-all duration-150 group">
                                        
                                        <!-- Multiple Selection Checkbox -->
                                        <td class="px-3 py-3.5 text-center w-10" @click.stop>
                                            <input type="checkbox" :value="r.id" x-model="selectedReceiptIds" class="w-4 h-4 text-[#a38c29] border-slate-300 rounded focus:ring-[#a38c29] cursor-pointer">
                                        </td>

                                        <td class="px-4 py-3.5 font-mono font-bold text-slate-900" x-text="r.ref"></td>
                                        <td class="px-4 py-3.5 text-slate-500 font-medium" x-text="formatDate(r.date)"></td>
                                        <td class="px-4 py-3.5 font-bold text-slate-900" x-text="r.customer_name || 'General Inbound Payer'"></td>
                                        <td class="px-4 py-3.5 font-extrabold text-slate-800" x-text="r.company_bank_account_name || 'General Account'"></td>
                                        <td class="px-4 py-3.5 font-mono font-black text-slate-950 text-right text-sm" x-text="'₹' + formatCurrency(r.amount)"></td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-block"
                                                  :class="r.payment_mode === 'Cheque' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800'">
                                                <span x-text="r.payment_mode || 'Cheque'"></span>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <template x-if="r.company_bank_account_id">
                                                <span class="px-2.5 py-1 rounded-full bg-[#a38c29]/15 text-[#a38c29] text-[10px] font-black uppercase tracking-wider border border-[#a38c29]/40 inline-block shadow-2xs">
                                                    ✓ STORED IN BANK
                                                </span>
                                            </template>
                                            <template x-if="!r.company_bank_account_id">
                                                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black uppercase tracking-wider border border-amber-300 inline-block shadow-2xs">
                                                    UNSTORED
                                                </span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="filteredReceipts().length === 0">
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic font-medium">
                                            No receipts found matching Cheque payment mode or selected filters. Change filter options above.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Pagination -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500 font-medium">
                    <span>Showing <span x-text="filteredReceipts().length > 0 ? (currentPage - 1) * perPage + 1 : 0"></span> to <span x-text="Math.min(currentPage * perPage, filteredReceipts().length)"></span> of <span x-text="filteredReceipts().length"></span> receipts</span>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold hover:bg-slate-100 disabled:opacity-40">Prev</button>
                        <template x-for="p in getTotalPages()" :key="p">
                            <button type="button" @click="currentPage = p" x-text="p" class="px-3 py-1.5 rounded-lg font-bold" :class="currentPage === p ? 'bg-[#a38c29] text-white' : 'bg-white border border-slate-200'"></button>
                        </template>
                        <button type="button" @click="currentPage < getTotalPages() && currentPage++" :disabled="currentPage >= getTotalPages()" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold hover:bg-slate-100 disabled:opacity-40">Next</button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: OFFICIAL PAYMENT RECEIPT & MULTI-SELECT BULK BANK STORE CARD (1/3 width) -->
            <div class="w-1/3 min-w-[320px] bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between shrink-0 sticky top-6">
                
                <!-- Card Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-[#FAF0D7] via-[#F6F3E9] to-white border-b border-[#EAE3CD] text-slate-900 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest"
                             x-text="selectedReceiptIds.length > 1 ? 'MULTIPLE RECEIPTS SELECTED' : 'OFFICIAL PAYMENT RECEIPT'"></div>
                        <div class="text-xs font-extrabold text-slate-900 mt-0.5"
                             x-text="selectedReceiptIds.length > 1 ? selectedReceiptIds.length + ' Receipts Selected' : (selectedReceipt ? selectedReceipt.ref : 'Receipt Voucher')"></div>
                    </div>
                    <template x-if="selectedReceiptIds.length > 0">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border bg-amber-100 text-amber-900 border-amber-300"
                              x-text="selectedReceiptIds.length + ' SELECTED'">
                        </span>
                    </template>
                </div>

                <div class="p-6 flex-grow flex flex-col justify-between bg-white space-y-4">
                    
                    {{-- CASE 1: MULTIPLE RECEIPTS SELECTED (BULK BANK STORAGE ACTION) --}}
                    <template x-if="selectedReceiptIds.length > 1">
                        <div class="space-y-4 text-xs">
                            <div class="p-4 bg-amber-50 border-2 border-amber-300 rounded-2xl space-y-3">
                                <div class="border-b border-amber-200 pb-2 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-amber-900 uppercase tracking-widest">BATCH BANK STORAGE</span>
                                    <span class="text-xs font-bold text-amber-800" x-text="selectedReceiptIds.length + ' Receipts Selected'"></span>
                                </div>

                                <div>
                                    <div class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">COMBINED TOTAL AMOUNT</div>
                                    <div class="mt-1 font-mono font-black text-amber-950 text-xl" x-text="'₹' + formatCurrency(getSelectedTotalAmount())"></div>
                                </div>

                                {{-- BULK STORE FORM --}}
                                <div class="pt-2 border-t border-amber-200">
                                    <form action="{{ route('receipt-management.assign-bank-bulk') }}" method="POST" class="space-y-3">
                                        @csrf
                                        
                                        <template x-for="id in selectedReceiptIds" :key="id">
                                            <input type="hidden" name="receipt_ids[]" :value="id">
                                        </template>

                                        <label class="text-[10px] font-black text-[#a38c29] uppercase tracking-wider block">SELECT TARGET COMPANY BANK ACCOUNT</label>
                                        
                                        <select name="company_bank_account_id" required
                                                class="w-full pl-3 pr-8 py-2 bg-white border-2 border-[#a38c29] rounded-xl font-extrabold text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                                            @foreach($companyBankAccounts as $bAcc)
                                                <option value="{{ $bAcc->id }}">
                                                    {{ $bAcc->bank_name }} — A/C: {{ $bAcc->account_number ?: 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                                class="w-full py-3 px-3 bg-[#a38c29] hover:bg-[#8a7522] text-white font-black text-xs uppercase tracking-wider rounded-xl transition shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                            <span>STORE ALL <span x-text="selectedReceiptIds.length"></span> RECEIPTS TO BANK</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- CASE 2: SINGLE RECEIPT SELECTED --}}
                    <template x-if="selectedReceiptIds.length <= 1 && selectedReceipt">
                        <div class="space-y-4 text-xs">
                            
                            <!-- Receipt Intake Box -->
                            <div class="p-4 bg-slate-50 border border-slate-200/90 rounded-2xl space-y-3 relative overflow-hidden">
                                <div class="border-b border-slate-200 pb-2 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RECEIPT INTAKE VOUCHER</span>
                                    <span class="text-xs font-mono font-bold text-slate-500" x-text="formatDate(selectedReceipt.date)"></span>
                                </div>

                                <div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">RECEIVED FROM</div>
                                    <div class="mt-0.5 font-black text-slate-900 text-base" x-text="selectedReceipt.customer_name || 'Inbound Bank Deposit'"></div>
                                </div>

                                {{-- STORE / ASSIGN TO COMPANY BANK ACCOUNT DROPDOWN --}}
                                <div class="col-span-2 pt-2 border-t border-slate-200/60">
                                    <form :action="'{{ url('/receipt-management') }}/' + selectedReceipt.id + '/assign-bank'" method="POST" class="space-y-2">
                                        @csrf
                                        <label class="text-[10px] font-black text-[#a38c29] uppercase tracking-wider block">SELECT COMPANY BANK ACCOUNT TO STORE AMOUNT</label>
                                        
                                        <select name="company_bank_account_id" x-model="assignBankForm.company_bank_account_id" required
                                                class="w-full pl-3 pr-8 py-2 bg-white border-2 border-[#a38c29]/50 hover:border-[#a38c29] rounded-xl font-extrabold text-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition cursor-pointer">
                                            @foreach($companyBankAccounts as $bAcc)
                                                <option value="{{ $bAcc->id }}">
                                                    {{ $bAcc->bank_name }} — A/C: {{ $bAcc->account_number ?: 'N/A' }} {{ $bAcc->upi_id ? '| UPI: '.$bAcc->upi_id : '' }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                                class="w-full py-2.5 px-3 bg-[#a38c29] hover:bg-[#8a7522] text-white font-black text-[11px] uppercase tracking-wider rounded-xl transition shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                            <span>STORE AMOUNT TO THIS BANK ACCOUNT</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Collected Amount Card -->
                            <div class="grid grid-cols-2 gap-3 p-4 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] text-slate-900 rounded-2xl shadow-xs">
                                <div>
                                    <div class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider">COLLECTED AMOUNT</div>
                                    <div class="mt-1 font-mono font-black text-[#a38c29] text-lg" x-text="'₹' + formatCurrency(selectedReceipt.amount)"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider">PAYMENT MODE</div>
                                    <div class="mt-1.5">
                                        <span class="px-2.5 py-1 rounded-md bg-blue-100 text-blue-800 text-[10px] font-black uppercase tracking-wider inline-block">
                                            <span x-text="selectedReceipt.payment_mode || 'Cheque'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <button type="button" @click="openAddModal()"
                                class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-wider rounded-xl transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ RECORD NEW BANK RECEIPT</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── TAB 2: STORED BANK RECEIPTS (RECEIPT_STORES TABLE - SEPARATE DISPLAY) ── -->
        <div x-show="activeMainTab === 'stored'" class="w-full bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-black text-white flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest">STORED BANK RECEIPTS DIRECTORY</div>
                        <h3 class="text-base font-black text-white tracking-tight mt-0.5">STORED IN COMPANY BANK ACCOUNTS </h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Separate record of receipt amounts credited & stored in corporate bank accounts.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="px-4 py-2 bg-[#a38c29]/20 border border-[#a38c29]/40 text-[#a38c29] rounded-xl text-xs font-black uppercase tracking-wider">
                            Total Stored: ₹{{ number_format($totalTransferredAmount ?? 0, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Table View for receipt_stores Data -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[11px] font-black uppercase tracking-widest">
                                <th class="px-4 py-3.5 text-center text-white">STORE ID</th>
                                <th class="px-4 py-3.5 text-white">RECEIPT #</th>
                                <th class="px-4 py-3.5 text-white">STORED DATE & TIME</th>
                                <th class="px-4 py-3.5 text-white">CUSTOMER / PAYER</th>
                                <th class="px-4 py-3.5 text-white">COMPANY BANK ACCOUNT</th>
                                <th class="px-4 py-3.5 text-right text-white">STORED AMOUNT</th>
                                <th class="px-4 py-3.5 text-center text-white">MODE</th>
                                <th class="px-4 py-3.5 text-center text-white">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                            <template x-for="st in storedReceipts" :key="st.id">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-400" x-text="'#STORE-' + st.id"></td>
                                    <td class="px-4 py-3.5 font-mono font-black text-slate-900" x-text="st.ref"></td>
                                    <td class="px-4 py-3.5 text-slate-500 font-medium" x-text="st.created_at_formatted || formatDate(st.date)"></td>
                                    <td class="px-4 py-3.5 font-bold text-slate-800" x-text="st.customer_name || 'General Inbound Payer'"></td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-md bg-[#a38c29]/20 text-[#a38c29] border border-[#a38c29]/40 text-[10px] font-black flex items-center justify-center shrink-0">
                                                <span x-text="(st.company_bank_account_name || 'B').charAt(0)"></span>
                                            </span>
                                            <span class="font-extrabold text-slate-900" x-text="st.company_bank_account_name"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono font-black text-[#a38c29] text-right text-sm" x-text="'₹' + formatCurrency(st.amount)"></td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-md bg-blue-100 text-blue-800 text-[10px] font-black uppercase tracking-wider inline-block">
                                            <span x-text="st.payment_mode || 'Cheque'"></span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-full bg-[#a38c29]/15 text-[#a38c29] text-[10px] font-black uppercase tracking-wider border border-[#a38c29]/40 inline-block shadow-2xs">
                                            STORED IN BANK
                                        </span>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="storedReceipts.length === 0">
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic font-medium">
                                        No stored receipts found in  table yet. Select an inbound receipt and store it in a company bank account above.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-500 font-medium">
                Displaying <span class="font-extrabold text-slate-900" x-text="storedReceipts.length"></span> bank deposit records saved in <code class="font-mono bg-slate-200 px-1.5 py-0.5 rounded text-slate-800"></code> table.
            </div>
        </div>

        {{-- Record New Bank Receipt Modal --}}
        <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800" @click.away="addModalOpen = false">
                <div class="bg-black text-white px-6 py-4 flex items-center justify-between border-b border-[#a38c29]/40">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 text-[#a38c29] border border-[#a38c29]/40 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wider">RECORD INBOUND BANK RECEIPT</h3>
                            <p class="text-[11px] text-slate-400 font-medium">Record payment received into company bank account and auto-update liquidity balance.</p>
                        </div>
                    </div>
                    <button type="button" @click="addModalOpen = false" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-[#a38c29] text-slate-400 hover:text-white transition flex items-center justify-center font-bold text-sm">✕</button>
                </div>

                <div class="p-6">
                    <form action="{{ route('receipt-management.store') }}" method="POST" @submit="submitAddReceipt($event)" novalidate class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Company Bank Account <span class="text-rose-500">*</span></label>
                                <select name="company_bank_account_id" x-model="addForm.company_bank_account_id" required
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                    <option value="">Select Company Bank Account...</option>
                                    @foreach($companyBankAccounts as $bAcc)
                                        <option value="{{ $bAcc->id }}">
                                            {{ $bAcc->bank_name }} — A/C: {{ $bAcc->account_number ?: 'N/A' }} {{ $bAcc->upi_id ? '| UPI: '.$bAcc->upi_id : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Receipt Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="receipt_date" x-model="addForm.receipt_date" required
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Received Amount (₹) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" x-model="addForm.amount" placeholder="0.00" required
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-900 focus:outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Payment Mode <span class="text-rose-500">*</span></label>
                                <select name="payment_mode" x-model="addForm.payment_mode" required
                                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                    <option value="Cheque">Cheque</option>
                                    <option value="NEFT/RTGS">NEFT / RTGS / IMPS</option>
                                    <option value="Direct Transfer">Direct Bank Transfer</option>
                                    <option value="UPI">UPI / VPA</option>
                                    <option value="Cash">Cash Deposit</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cheque / UTR Ref #</label>
                                <input type="text" name="reference_no" x-model="addForm.reference_no" placeholder="e.g. CHQ-998877"
                                       class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-900 focus:outline-none transition-all">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="addModalOpen = false"
                                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
                                <span>Record Receipt & Deposit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

<script>
function receiptManagementWorkspace() {
    return {
        allReceipts: @json($allReceiptsFormatted->values()),
        storedReceipts: @json($storedReceiptsFormatted->values()),
        activeMainTab: 'inbound', // 'inbound' or 'stored'
        
        searchQuery: '',
        filterProject: '',
        filterBank: '',
        filterPaymentMode: 'Cheque', // DEFAULT ONLY CHEQUE MODE
        filterStorageStatus: 'unstored', // DEFAULT UNSTORED ONLY
        
        selectedReceiptId: '',
        selectedReceipt: null,

        selectedReceiptIds: [], // ARRAY FOR MULTIPLE SELECTION

        assignBankForm: {
            company_bank_account_id: ''
        },

        currentPage: 1,
        perPage: 10,

        errors: {},
        addModalOpen: false,

        addForm: {
            company_bank_account_id: '{{ $defaultBankAccount->id ?? "" }}',
            receipt_date: new Date().toISOString().split('T')[0],
            amount: '',
            payment_mode: 'Cheque',
            reference_no: '',
            customer_id: '',
            project_id: '',
            remarks: ''
        },

        init() {
            this.$watch('searchQuery', () => { this.currentPage = 1; this.autoSelectFirst(); });
            this.$watch('filterProject', () => { this.currentPage = 1; this.autoSelectFirst(); });
            this.$watch('filterBank', () => { this.currentPage = 1; this.autoSelectFirst(); });
            this.$watch('filterPaymentMode', () => { this.currentPage = 1; this.autoSelectFirst(); });
            this.$watch('filterStorageStatus', () => { this.currentPage = 1; this.autoSelectFirst(); });

            this.$watch('selectedReceiptIds', (newVal) => {
                if (newVal.length === 1) {
                    const match = this.allReceipts.find(r => String(r.id) === String(newVal[0]));
                    if (match) this.selectReceipt(match);
                }
            });

            this.$nextTick(() => {
                this.autoSelectFirst();
            });
        },

        autoSelectFirst() {
            const list = this.filteredReceipts();
            if (list.length > 0) {
                this.selectReceipt(list[0]);
                this.selectedReceiptIds = [list[0].id];
            } else if (this.allReceipts.length > 0) {
                this.selectReceipt(this.allReceipts[0]);
                this.selectedReceiptIds = [this.allReceipts[0].id];
            } else {
                this.selectedReceiptIds = [];
            }
        },

        filteredReceipts() {
            let list = this.allReceipts || [];
            return list.filter(r => {
                const matchesSearch = !this.searchQuery || 
                    (r.ref || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (r.reference_no || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (r.customer_name || '').toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (r.remarks || '').toLowerCase().includes(this.searchQuery.toLowerCase());
                
                const matchesProject     = !this.filterProject || String(r.project_id) === String(this.filterProject);
                const matchesBank        = !this.filterBank || String(r.company_bank_account_id) === String(this.filterBank);
                const matchesPaymentMode = !this.filterPaymentMode || (r.payment_mode && r.payment_mode.toLowerCase().includes(this.filterPaymentMode.toLowerCase()));
                
                let matchesStorageStatus = true;
                if (this.filterStorageStatus === 'stored') {
                    matchesStorageStatus = Boolean(r.company_bank_account_id);
                } else if (this.filterStorageStatus === 'unstored') {
                    matchesStorageStatus = !r.company_bank_account_id;
                }

                return matchesSearch && matchesProject && matchesBank && matchesPaymentMode && matchesStorageStatus;
            });
        },

        paginatedReceipts() {
            const list = this.filteredReceipts();
            const start = (this.currentPage - 1) * this.perPage;
            return list.slice(start, start + this.perPage);
        },

        getTotalPages() {
            const list = this.filteredReceipts();
            return Math.max(1, Math.ceil(list.length / (this.perPage || 10)));
        },

        isSelected(id) {
            return this.selectedReceiptIds.map(String).includes(String(id));
        },

        toggleSelectRow(r) {
            const idStr = String(r.id);
            const idx = this.selectedReceiptIds.map(String).indexOf(idStr);
            if (idx > -1) {
                this.selectedReceiptIds.splice(idx, 1);
            } else {
                this.selectedReceiptIds.push(r.id);
            }
            this.selectReceipt(r);
        },

        toggleSelectAll(e) {
            if (e.target.checked) {
                const currentIds = this.paginatedReceipts().map(r => r.id);
                this.selectedReceiptIds = Array.from(new Set([...this.selectedReceiptIds, ...currentIds]));
            } else {
                const currentIds = this.paginatedReceipts().map(r => String(r.id));
                this.selectedReceiptIds = this.selectedReceiptIds.filter(id => !currentIds.includes(String(id)));
            }
        },

        isAllSelected() {
            const current = this.paginatedReceipts();
            if (current.length === 0) return false;
            return current.every(r => this.isSelected(r.id));
        },

        getSelectedTotalAmount() {
            let total = 0;
            this.selectedReceiptIds.forEach(id => {
                const r = this.allReceipts.find(item => String(item.id) === String(id));
                if (r) {
                    total += parseFloat(r.amount || 0);
                }
            });
            return total;
        },

        selectReceipt(r) {
            this.selectedReceiptId = r.id;
            this.selectedReceipt = r;
            this.assignBankForm.company_bank_account_id = r.company_bank_account_id || '{{ $defaultBankAccount->id ?? "" }}';
        },

        openAddModal() {
            this.errors = {};
            this.addModalOpen = true;
        },

        submitAddReceipt(e) {
            this.errors = {};
            if (!this.addForm.company_bank_account_id) {
                this.errors.company_bank_account_id = 'Target Company Bank Account is required.';
            }
            if (!this.addForm.amount || parseFloat(this.addForm.amount) <= 0) {
                this.errors.amount = 'Valid receipt amount is required.';
            }

            if (Object.keys(this.errors).length > 0) {
                e.preventDefault();
            }
        },

        formatCurrency(val) {
            return Number(val || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        formatDate(dStr) {
            if (!dStr) return '—';
            try {
                const date = new Date(dStr);
                return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            } catch (e) {
                return dStr;
            }
        }
    }
}
</script>
</x-erp-layout>
