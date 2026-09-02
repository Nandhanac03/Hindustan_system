<x-erp-layout title="Internal Contra Transfers - HindustanERP" headerTitle="Financial Accounting Workspace">

<div class="max-w-[1600px] mx-auto space-y-6" x-data="contraVoucherWorkspace()">

    {{-- Under Development Notice Banner --}}
    <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-4 md:p-5 shadow-2xs relative overflow-hidden backdrop-blur-sm">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-md text-xl">
                🚧
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white">Under Development</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Work In Progress
                    </span>
                </div>
                <h2 class="text-sm md:text-base font-extrabold text-red-950 mt-0.5">
                    We're working on this module. It is not yet ready for use and will be released shortly.
                </h2>
            </div>
        </div>
    </div>

    <!-- ── 1. HEADER BAR WITH + ADD CONTRA ENTRY BUTTON ── -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 bg-slate-900 text-white rounded-xl shadow-2xs flex items-center justify-center shrink-0 border border-slate-800">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Internal Contra Transfers</span>
                </h1>
                <p class="text-xs font-semibold text-slate-500">Bank to Bank Transfers, Cash Deposits & Cash Withdrawals</p>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <span class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-mono font-bold flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>{{ date('d-M-Y') }}</span>
            </span>

            <!-- + ADD CONTRA ENTRY BUTTON (OPENS FORM MODAL) -->
            <button type="button" @click="showFormModal = true" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-[#a38c29]/20 flex items-center gap-2 border border-[#a38c29]/40 cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                <span>ADD CONTRA ENTRY</span>
            </button>
        </div>
    </div>

    <!-- ── 2. DEFAULT MAIN PAGE DISPLAY: DIRECTORY TABLE & FILTER CARD ── -->
    <div class="space-y-4">
        
        <!-- TOP FILTER CONTROL CARD (BORDERLESS WITH FLAT GOLD SVG BANK ICON) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-4 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">SELECT BANK ACCOUNT / FILTER FOR CONTRA VOUCHERS</h3>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-3">
                <!-- SELECT BANK ACCOUNT FILTER WITH FLAT GOLD SVG BANK ICON -->
                <div class="w-full md:w-5/12 relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                        </svg>
                    </div>
                    <select x-model="selectedBankFilter" @change="currentPage = 1"
                            class="w-full h-11 pl-10 pr-8 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition shadow-2xs truncate cursor-pointer appearance-none">
                        <option value="">— All Bank Accounts & Cash Boxes —</option>
                        @php
                            $allBankNames = collect();
                            if(isset($companyBankAccounts)) {
                                foreach($companyBankAccounts as $b) {
                                    $allBankNames->push($b->bank_name);
                                }
                            }
                            if(isset($assetAccounts)) {
                                foreach($assetAccounts as $a) {
                                    $allBankNames->push($a->name);
                                }
                            }
                            $uniqueBanks = $allBankNames->unique()->sort();
                        @endphp
                        @foreach($uniqueBanks as $bName)
                            <option value="{{ $bName }}">{{ $bName }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- LIVE SEARCH INPUT BAR (WITH INTEGRATED FLAT GOLD SEARCH ICON) -->
                <div class="w-full md:w-5/12 relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Type to search Voucher No., Ref No., Particulars..."
                           class="w-full h-11 pl-10 pr-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition shadow-2xs">
                </div>

                <!-- ACTION BUTTONS GRID (PRINT & EXCEL) -->
                <div class="w-full md:w-2/12 flex items-center gap-2">
                    <button type="button" @click="printTable()" class="flex-1 h-11 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>PRINT</span>
                    </button>
                    <button type="button" @click="exportExcel()" class="flex-1 h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>EXCEL</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- DIRECTORY TABLE CONTAINER WITH PURE WHITE BANNER HEADER BAR -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs space-y-0">
            
            <!-- WHITE BANNER HEADER BAR -->
            <div class="bg-white text-slate-900 px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">ALL CONTRA ENTRIES DIRECTORY</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Overview of digital fund movements, cash deposits, and petty cash replenishments.</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-amber-50 text-[#a38c29] border border-amber-200/80 text-xs font-mono font-extrabold uppercase tracking-wider shadow-2xs" x-text="filteredContras.length + ' CONTRA VOUCHERS'">
                    15 CONTRA VOUCHERS
                </span>
            </div>

            <!-- TABLE CONTENT -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-[#a38c29] text-white text-[10px] sm:text-[11px] font-black uppercase tracking-widest border-b-2 border-[#8a7522]">
                        <tr>
                            <th class="px-4 py-3.5 text-white">SL NO</th>
                            <th class="px-4 py-3.5 text-white">VOUCHER NO.</th>
                            <th class="px-4 py-3.5 text-white">DATE</th>
                            <th class="px-4 py-3.5 text-white">FROM ACCOUNT (SOURCE)</th>
                            <th class="px-4 py-3.5 text-white">TO ACCOUNT (DESTINATION)</th>
                            <th class="px-4 py-3.5 text-white">MODE / REF NO.</th>
                            <th class="px-4 py-3.5 text-right text-white">TRANSFER AMOUNT (₹)</th>
                            <th class="px-4 py-3.5 text-center text-white">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800 bg-white">
                        <template x-for="(item, index) in paginatedContras" :key="item.id || item.voucher_number">
                            <tr class="hover:bg-amber-50/20 bg-white transition">
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-500" x-text="(currentPage - 1) * pageSize + index + 1"></td>
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-900" x-text="item.voucher_number"></td>
                                <td class="px-4 py-3.5 text-slate-600 font-semibold" x-text="item.date"></td>
                                <td class="px-4 py-3.5 font-bold text-slate-900" x-text="item.from_account"></td>
                                <td class="px-4 py-3.5 font-bold text-slate-900" x-text="item.to_account"></td>
                                <td class="px-4 py-3.5 text-slate-600 font-semibold" x-text="item.reference_no || 'RTGS / UTR8821'"></td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900 text-sm" x-text="'₹ ' + formatCurrency(item.amount)"></td>
                                <td class="px-4 py-3.5 text-center">
                                    <button type="button" @click="openSlipModal(item)" title="View Slip" class="w-8 h-8 rounded-xl bg-amber-50 hover:bg-[#a38c29] text-[#a38c29] hover:text-white border border-amber-200/80 transition flex items-center justify-center mx-auto shadow-2xs cursor-pointer group">
                                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredContras.length === 0">
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 font-bold">
                                No contra voucher records match your search or filter criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINATION BAR -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs font-semibold text-slate-500">
                    Showing <span class="font-bold text-slate-900" x-text="paginationStart"></span> to <span class="font-bold text-slate-900" x-text="paginationEnd"></span> of <span class="font-bold text-slate-900" x-text="filteredContras.length"></span> entries
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1"
                            class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                        ← Previous
                    </button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button type="button" @click="currentPage = p"
                                class="w-8 h-8 rounded-xl text-xs font-black transition cursor-pointer"
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-2xs' : 'bg-white border border-slate-300 text-slate-700 hover:bg-slate-100'"
                                x-text="p"></button>
                    </template>

                    <button type="button" @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages"
                            class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                        Next →
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ── 3. ADD CONTRA ENTRY FORM MODAL (EXACT UNIT SETUP MODAL STYLE) ── -->
    <div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden border-0 flex flex-col max-h-[92vh]"
             @click.away="showFormModal = false">
            
            <!-- MODAL HEADER BAR (EXACT UNIT SETUP MODAL HEADER STYLE: #232018 DARK CHARCOAL + GOLD BADGE) -->
            <div class="bg-[#232018] px-6 py-5 border-b border-[#a38c29]/20 flex items-center justify-between shrink-0">
                <div>
                    <span class="text-[#a38c29] text-[10px] font-extrabold uppercase tracking-widest block mb-1">CONTRA VOUCHER SETUP</span>
                    <h2 class="text-base sm:text-lg font-extrabold text-white leading-tight">Add Contra Entry</h2>
                </div>
                <button type="button" @click="showFormModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-sm cursor-pointer">✕</button>
            </div>

            <!-- MODAL BODY FORM (PURE WHITE BACKGROUND WITH ROOMY SPACING) -->
            <form action="{{ route('vouchers.contra.store') }}" method="POST" enctype="multipart/form-data" @submit="submitForm($event)" class="flex-1 overflow-y-auto p-6 md:p-7 space-y-5 font-sans text-xs bg-white">
                @csrf

                <!-- Row 1: Date & Transaction Type (2 COLS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest mb-1.5" :class="errors.date ? 'text-rose-500' : 'text-slate-600'">Voucher Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" required x-model="form.date" @change="delete errors.date"
                               :class="errors.date ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] bg-slate-50'"
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                        <span x-show="errors.date" x-cloak class="text-[10px] font-bold text-rose-500 block mt-1.5" x-text="errors.date"></span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-1.5">Transaction Type <span class="text-rose-500">*</span></label>
                        <select name="transaction_type" x-model="transactionType" @change="onTransactionTypeChange()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs cursor-pointer">
                            <option value="bank_to_bank">Bank to Bank Transfer</option>
                            <option value="cash_withdrawal">Bank Cash Withdrawal (Petty Cash Refill)</option>
                            <option value="cash_deposit">Cash Deposit (Cash Box → Bank Account)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: From Account & To Account (2 COLS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- FROM ACCOUNT -->
                    <div class="relative" x-data="{ openFromDropdown: false, fromSearch: '' }" @click.outside="openFromDropdown = false">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest block mb-1.5" :class="errors.credit_account_id ? 'text-rose-500' : 'text-slate-600'">From Account (Credit / Source) <span class="text-rose-500">*</span></label>
                        
                        <input type="hidden" name="credit_account_id" :value="form.credit_account_id" required>

                        <button type="button" @click="openFromDropdown = !openFromDropdown"
                                :class="errors.credit_account_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-slate-200 hover:border-[#a38c29] bg-slate-50'"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] flex items-center justify-between transition shadow-2xs cursor-pointer">
                            <span class="truncate text-left" x-text="selectedFromAccountName || 'Select From Account...'">Select From Account...</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1 transition-transform" :class="openFromDropdown ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[10px] font-bold text-[#a38c29] block" x-text="'Available Balance: ₹ ' + formatCurrency(fromAccountBalance)">Available Balance: ₹ 0</span>
                            <span x-show="errors.credit_account_id" x-cloak class="text-[10px] font-bold text-rose-500 block" x-text="errors.credit_account_id"></span>
                        </div>

                        <!-- SEARCH DROPDOWN POPOVER -->
                        <div x-show="openFromDropdown" x-cloak class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-2 space-y-1.5 min-w-[280px]">
                            <div class="relative flex items-center">
                                <input type="text" x-model="fromSearch" placeholder="Search Bank Account..." autofocus
                                       class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29]">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1">
                                <template x-for="acc in filteredFromAccounts(fromSearch)" :key="acc.id">
                                    <button type="button" @click="selectFromAccount(acc); openFromDropdown = false; fromSearch = ''"
                                            class="w-full text-left px-2.5 py-1.5 hover:bg-amber-50 rounded-lg text-xs font-bold text-slate-800 flex items-center justify-between gap-2 whitespace-nowrap transition cursor-pointer group">
                                        <span x-text="acc.name" class="truncate text-left shrink group-hover:text-[#a38c29] transition"></span>
                                        <span class="shrink-0 text-[10px] font-mono font-bold text-[#a38c29] bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60 whitespace-nowrap" x-text="'₹ ' + formatCurrency(acc.balance)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- TO ACCOUNT -->
                    <div class="relative" x-data="{ openToDropdown: false, toSearch: '' }" @click.outside="openToDropdown = false">
                        <label class="text-[10px] font-extrabold uppercase tracking-widest block mb-1.5" :class="errors.destination_account_id ? 'text-rose-500' : 'text-slate-600'">To Account (Debit / Destination) <span class="text-rose-500">*</span></label>
                        
                        <input type="hidden" name="destination_account_id" :value="form.destination_account_id" required>

                        <button type="button" @click="openToDropdown = !openToDropdown"
                                :class="errors.destination_account_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-slate-200 hover:border-[#a38c29] bg-slate-50'"
                                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] flex items-center justify-between transition shadow-2xs cursor-pointer">
                            <span class="truncate text-left" x-text="selectedToAccountName || 'Select To Account...'">Select To Account...</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1 transition-transform" :class="openToDropdown ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[10px] font-bold text-[#a38c29] block" x-text="'Available Balance: ₹ ' + formatCurrency(toAccountBalance)">Available Balance: ₹ 0</span>
                            <span x-show="errors.destination_account_id" x-cloak class="text-[10px] font-bold text-rose-500 block" x-text="errors.destination_account_id"></span>
                        </div>

                        <!-- SEARCH DROPDOWN POPOVER -->
                        <div x-show="openToDropdown" x-cloak class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-2 space-y-1.5 min-w-[280px]">
                            <div class="relative flex items-center">
                                <input type="text" x-model="toSearch" placeholder="Search Destination Account..." autofocus
                                       class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29]">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1">
                                <template x-for="acc in filteredToAccounts(toSearch)" :key="acc.id">
                                    <button type="button" @click="selectToAccount(acc); openToDropdown = false; toSearch = ''"
                                            class="w-full text-left px-2.5 py-1.5 hover:bg-amber-50 rounded-lg text-xs font-bold text-slate-800 flex items-center justify-between gap-2 whitespace-nowrap transition cursor-pointer group">
                                        <span x-text="acc.name" class="truncate text-left shrink group-hover:text-[#a38c29] transition"></span>
                                        <span class="shrink-0 text-[10px] font-mono font-bold text-[#a38c29] bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60 whitespace-nowrap" x-text="'₹ ' + formatCurrency(acc.balance)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Transfer Amount & Payment Mode (2 COLS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-widest mb-1.5" :class="errors.amount ? 'text-rose-500' : 'text-slate-600'">Transfer Amount (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" required min="1" step="1" placeholder="e.g. 25000"
                               x-model.number="form.amount" @input="delete errors.amount"
                               @keydown="if($event.key === '.' || $event.key === 'e' || $event.key === 'E' || $event.key === '-') $event.preventDefault()"
                               oninput="window.sanitizeAmountInput && window.sanitizeAmountInput(this)"
                               :class="errors.amount ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] bg-slate-50'"
                               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-mono font-bold text-slate-900 focus:outline-none transition shadow-2xs">
                        <span x-show="errors.amount" x-cloak class="text-[10px] font-bold text-rose-500 block mt-1.5" x-text="errors.amount"></span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-1.5">Payment Mode <span class="text-rose-500">*</span></label>
                        <select name="payment_mode" required x-model="form.payment_mode"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs cursor-pointer">
                            <option value="RTGS">RTGS / NEFT / IMPS</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Cash Withdrawal">Cash Withdrawal</option>
                            <option value="Cash Deposit Slip">Cash Deposit Slip</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Reference No & Supporting Document (2 COLS) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-1.5">Reference / UTR No.</label>
                        <input type="text" name="reference_no" placeholder="e.g. UTR8821 or Chq #40012" x-model="form.reference_no"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition shadow-2xs">
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-1.5">Supporting Document</label>
                        <div x-show="!fileName" class="border border-dashed border-slate-300 hover:border-[#a38c29] rounded-xl p-2 text-center bg-slate-50/50 flex flex-col items-center justify-center h-[42px] relative transition cursor-pointer">
                            <span class="text-[11px] font-semibold text-slate-600 block">Drag & Drop file or click to upload</span>
                            <input type="file" name="attachment" @change="handleFileChange($event)" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <div x-show="fileName" x-cloak class="border border-amber-300 bg-amber-50/80 rounded-xl p-2 flex items-center justify-between h-[42px] shadow-2xs">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <span class="text-xs font-bold text-slate-900 truncate" x-text="fileName"></span>
                                <span class="text-[10px] text-[#7a671b] font-mono" x-text="fileSize"></span>
                            </div>
                            <button type="button" @click="clearFile()" class="p-1 text-rose-600 hover:bg-rose-100 rounded-lg transition">✕</button>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Remarks / Purpose (FULL WIDTH) -->
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-600 uppercase tracking-widest mb-1.5">Remarks / Purpose</label>
                    <textarea name="narration" rows="2" placeholder="e.g. Fund transfer for site payroll release or site cash box replenishment..." x-model="form.narration"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none transition shadow-2xs resize-none"></textarea>
                </div>

                <!-- MODAL FOOTER -->
                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 bg-white">
                    <button type="button" @click="showFormModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold uppercase rounded-xl shadow-md transition cursor-pointer">
                        SAVE & POST ENTRY
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── 4. CONTRA VOUCHERS SLIP / RECEIPT MODAL (MATCHING UNIT SETUP MODAL EXACT STYLE) ── -->
    <div x-show="showSlipModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        
        <div id="printable-slip-modal" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border-0 flex flex-col max-h-[90vh]"
             @click.away="closeSlipModal()">
            
            <!-- MODAL HEADER BAR (EXACT UNIT SETUP MODAL HEADER STYLE: #232018 DARK CHARCOAL + GOLD BADGE) -->
            <div class="bg-[#232018] px-6 py-5 border-b border-[#a38c29]/20 flex items-center justify-between shrink-0">
                <div>
                    <span class="text-[#a38c29] text-[10px] font-extrabold uppercase tracking-widest block mb-1">CONTRA VOUCHER SLIP</span>
                    <h2 class="text-base sm:text-lg font-extrabold text-white leading-tight" x-text="activeVoucher.voucher_number || 'JV-CONTRA-045'">JV-CONTRA-045</h2>
                </div>
                <button type="button" @click="closeSlipModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-sm cursor-pointer">✕</button>
            </div>

            <div class="p-6 overflow-y-auto space-y-4 flex-1 bg-white font-sans text-xs">
                <!-- PURE WHITE SUMMARY CARD (PROJECT TAG REMOVED) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-white border border-slate-200 shadow-2xs">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block mb-0.5">Voucher Date</span>
                        <strong class="text-xs font-mono font-bold text-slate-900 block" x-text="activeVoucher.date || '31-Aug-2026'">31-Aug-2026</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block mb-0.5">Reference / UTR</span>
                        <strong class="text-xs font-mono font-bold text-slate-900 block" x-text="activeVoucher.reference_no || 'Cheque #40012'">Cheque #40012</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-[#a38c29] uppercase tracking-wider block mb-0.5">Transfer Amount</span>
                        <strong class="text-sm font-mono font-black text-[#a38c29] block" x-text="'₹ ' + formatCurrency(activeVoucher.amount || 25000)">₹ 25,000</strong>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-[#a38c29] text-white text-[10px] font-black uppercase tracking-wider border-b-2 border-[#8a741f]">
                            <tr>
                                <th class="px-4 py-3 text-white">Particulars / Account Head</th>
                                <th class="px-4 py-3 text-center text-white">Voucher Type / Code</th>
                                <th class="px-4 py-3 text-right text-white">Debit (Rs.)</th>
                                <th class="px-4 py-3 text-right text-white">Credit (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-800 bg-white">
                            <tr>
                                <td class="px-4 py-3.5 text-emerald-800 font-bold">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800">DR</span>
                                        <span x-text="activeVoucher.to_account || 'Petty Cash Account - Site Box (1020)'">Petty Cash Account - Site Box (1020)</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center text-slate-500 font-mono text-[11px]">Contra (C) · 1020</td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-emerald-700 text-sm" x-text="'₹ ' + formatCurrency(activeVoucher.amount || 25000)">₹ 25,000</td>
                                <td class="px-4 py-3.5 text-right font-mono text-slate-300">—</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3.5 text-rose-800 font-bold pl-8">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-rose-100 text-rose-800">CR</span>
                                        <span x-text="'To ' + (activeVoucher.from_account || 'Karnataka Bank Current A/c (1001)')">To Karnataka Bank Current A/c (1001)</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center text-slate-500 font-mono text-[11px]">Contra (C) · 1001</td>
                                <td class="px-4 py-3.5 text-right font-mono text-slate-300">—</td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-rose-700 text-sm" x-text="'₹ ' + formatCurrency(activeVoucher.amount || 25000)">₹ 25,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Narration / Particulars</span>
                    <p class="text-xs font-semibold text-slate-800 italic" x-text="activeVoucher.narration || '(Being cash withdrawn from Karnataka Bank Chq #40012 to replenish site petty cash box)'">(Being cash withdrawn from Karnataka Bank Chq #40012 to replenish site petty cash box)</p>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
                <button type="button" @click="closeSlipModal()" class="px-5 py-2 border border-slate-300 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase rounded-xl transition cursor-pointer">
                    Close
                </button>
                <button type="button" @click="printVoucherSlip()" class="px-6 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase rounded-xl transition shadow-2xs flex items-center gap-2 cursor-pointer">
                    <span>Print Slip</span>
                </button>
            </div>
        </div>
    </div>

</div>

<!-- ── 5. ALPINE.JS REACTIVE WORKSPACE LOGIC ── -->
<script>
function contraVoucherWorkspace() {
    return {
        transactionType: 'bank_to_bank',
        fileName: '',
        fileSize: '',
        showFormModal: false,
        showSlipModal: false,
        activeVoucher: {},

        // Search, Bank Filter & Pagination State
        searchQuery: '',
        selectedBankFilter: '',
        currentPage: 1,
        pageSize: 10,

        rawRecentContras: [
            @if(isset($recentContras) && count($recentContras) > 0)
                @foreach($recentContras as $v)
                    @php
                        $creditLine = $v->lines->firstWhere('credit', '>', 0);
                        $debitLine = $v->lines->firstWhere('debit', '>', 0);
                        $transferAmt = $debitLine?->debit ?? $creditLine?->credit ?? 0;
                        $fromName = $creditLine?->account?->name ?? 'Karnataka Bank Current A/c';
                        $toName = $debitLine?->account?->name ?? 'Site Petty Cash Box';
                    @endphp
                    {
                        id: {{ $v->id }},
                        voucher_number: '{{ $v->voucher_number }}',
                        date: '{{ \Carbon\Carbon::parse($v->date)->format("d-M-Y") }}',
                        from_account: '{{ addslashes($fromName) }}',
                        to_account: '{{ addslashes($toName) }}',
                        reference_no: '{{ addslashes($v->reference_no ?? "RTGS / UTR8821") }}',
                        amount: {{ (float) $transferAmt }},
                        narration: '{{ addslashes($v->narration ?? "Fund transfer for site payroll release") }}',
                        project_name: '{{ addslashes($v->project?->name ?? "Global Treasury") }}'
                    },
                @endforeach
            @else
                { id: 1, voucher_number: 'JV-CONTRA-045', date: '31-Aug-2026', from_account: 'Karnataka Bank Current A/c (1001)', to_account: 'Petty Cash Account - Site Box (1020)', reference_no: 'Cheque #40012', amount: 25000, narration: 'Being cash withdrawn from Karnataka Bank Chq #40012 to replenish site petty cash box', project_name: 'Global Treasury' },
                { id: 2, voucher_number: 'JV-CONTRA-044', date: '20-Aug-2026', from_account: 'HDFC Escrow Account (1002)', to_account: 'Karnataka Bank Operational A/c (1001)', reference_no: 'RTGS / UTR8821', amount: 1000000, narration: 'Site payroll release', project_name: 'Global Treasury' },
                { id: 3, voucher_number: 'JV-CONTRA-043', date: '10-Aug-2026', from_account: 'Petty Cash Account - Site Box', to_account: 'SBI Current - 2005', reference_no: 'Cash Deposit Slip', amount: 15000, narration: 'Cash deposit to SBI', project_name: 'Global Treasury' },
            @endif
        ],

        form: {
            date: '{{ date("Y-m-d") }}',
            voucher_number: '{{ $voucherNumber }}',
            destination_account_id: '',
            credit_account_id: '',
            amount: '',
            payment_mode: 'RTGS',
            reference_no: '',
            project_id: '',
            narration: ''
        },

        selectedFromAccountName: '',
        selectedToAccountName: '',

        // Populate From Accounts directly from Company Bank Account Master
        rawFromAccounts: [
            @if(isset($companyBankAccounts) && count($companyBankAccounts) > 0)
                @foreach($companyBankAccounts as $cBank)
                    {
                        id: {{ $cBank->chart_account_id ?? $cBank->id }},
                        name: '{{ addslashes($cBank->bank_name . ($cBank->account_number ? " - " . $cBank->account_number : "")) }}',
                        balance: {{ (float) ($cBank->calculated_balance ?? $cBank->current_balance ?? 0) }}
                    },
                @endforeach
            @else
                @foreach($assetAccounts as $acc)
                    {
                        id: {{ $acc->id }},
                        name: '{{ addslashes($acc->name) }}',
                        balance: {{ (float) ($acc->current_balance ?? 0) }}
                    },
                @endforeach
            @endif
        ],

        rawToAccounts: [
            @foreach($assetAccounts as $acc)
                {
                    id: {{ $acc->id }},
                    name: '{{ addslashes($acc->name) }}',
                    balance: {{ (float) ($acc->current_balance ?? 0) }}
                },
            @endforeach
        ],

        fromAccountBalance: 0,
        toAccountBalance: 0,
        projectName: '',

        filteredFromAccounts(query) {
            if (!query || query.trim() === '') return this.rawFromAccounts;
            const q = query.toLowerCase().trim();
            return this.rawFromAccounts.filter(a => a.name.toLowerCase().includes(q));
        },

        filteredToAccounts(query) {
            if (!query || query.trim() === '') return this.rawToAccounts;
            const q = query.toLowerCase().trim();
            return this.rawToAccounts.filter(a => a.name.toLowerCase().includes(q));
        },

        submitted: false,
        errors: {},

        validateForm() {
            this.submitted = true;
            this.errors = {};
            if (!this.form.date) this.errors.date = 'The voucher date field is required.';
            if (!this.form.credit_account_id) this.errors.credit_account_id = 'The from account field is required.';
            if (!this.form.destination_account_id) this.errors.destination_account_id = 'The to account field is required.';
            if (!this.form.amount || Number(this.form.amount) <= 0) this.errors.amount = 'The transfer amount field is required.';
            
            return Object.keys(this.errors).length === 0;
        },

        submitForm(e) {
            if (!this.validateForm()) {
                e.preventDefault();
                return false;
            }
        },

        selectFromAccount(acc) {
            this.form.credit_account_id = acc.id;
            this.selectedFromAccountName = acc.name;
            this.fromAccountBalance = acc.balance;
            delete this.errors.credit_account_id;
        },

        selectToAccount(acc) {
            this.form.destination_account_id = acc.id;
            this.selectedToAccountName = acc.name;
            this.toAccountBalance = acc.balance;
            delete this.errors.destination_account_id;
        },

        init() {
            this.onTransactionTypeChange();
        },

        get filteredContras() {
            let list = this.rawRecentContras;

            if (this.searchQuery && this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase().trim();
                list = list.filter(v => 
                    (v.voucher_number && v.voucher_number.toLowerCase().includes(q)) ||
                    (v.from_account && v.from_account.toLowerCase().includes(q)) ||
                    (v.to_account && v.to_account.toLowerCase().includes(q)) ||
                    (v.reference_no && v.reference_no.toLowerCase().includes(q)) ||
                    (v.narration && v.narration.toLowerCase().includes(q))
                );
            }

            if (this.selectedBankFilter && this.selectedBankFilter.trim() !== '') {
                const b = this.selectedBankFilter.toLowerCase().trim();
                const bWord = b.split(' ')[0].replace(/[^a-z0-9]/g, '');
                list = list.filter(v => {
                    const fromLower = (v.from_account || '').toLowerCase();
                    const toLower = (v.to_account || '').toLowerCase();
                    return fromLower.includes(b) || toLower.includes(b) ||
                           (bWord.length > 2 && (fromLower.includes(bWord) || toLower.includes(bWord)));
                });
            }

            return list;
        },

        get paginatedContras() {
            const start = (this.currentPage - 1) * this.pageSize;
            return this.filteredContras.slice(start, start + this.pageSize);
        },

        get totalPages() {
            return Math.ceil(this.filteredContras.length / this.pageSize) || 1;
        },

        get paginationStart() {
            if (this.filteredContras.length === 0) return 0;
            return (this.currentPage - 1) * this.pageSize + 1;
        },

        get paginationEnd() {
            return Math.min(this.currentPage * this.pageSize, this.filteredContras.length);
        },

        onTransactionTypeChange() {
            if (this.transactionType === 'bank_to_bank') {
                this.form.payment_mode = 'RTGS';
            } else if (this.transactionType === 'cash_withdrawal') {
                this.form.payment_mode = 'Cash Withdrawal';
            } else if (this.transactionType === 'cash_deposit') {
                this.form.payment_mode = 'Cash Deposit Slip';
            }
        },

        updateProjectName() {
            const sel = document.querySelector('select[name="project_id"]');
            if (sel && sel.selectedIndex >= 0) {
                this.projectName = sel.options[sel.selectedIndex].text;
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            }
        },

        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            const input = document.querySelector('input[type="file"][name="attachment"]');
            if (input) input.value = '';
        },

        openSlipModal(voucherData) {
            this.activeVoucher = voucherData;
            this.showSlipModal = true;
        },

        closeSlipModal() {
            this.showSlipModal = false;
        },

        printTable() {
            window.print();
        },

        async exportExcel() {
            if (typeof ExcelJS === 'undefined') {
                alert('ExcelJS library is loading. Please try again in a moment.');
                return;
            }

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Contra Directory');

            // Freeze top 3 rows for smooth horizontal and vertical scrolling
            worksheet.views = [{ state: 'frozen', xSplit: 2, ySplit: 3, activePane: 'bottomRight' }];
            worksheet.pageSetup = {
                paperSize: 9,
                orientation: 'landscape',
                fitToPage: true,
                fitToWidth: 1,
                fitToHeight: 0
            };

            // Set column widths with adequate padding
            worksheet.columns = [
                { width: 10 }, // SL NO
                { width: 24 }, // VOUCHER NO
                { width: 18 }, // DATE
                { width: 38 }, // FROM ACCOUNT
                { width: 38 }, // TO ACCOUNT
                { width: 22 }, // MODE / REF NO
                { width: 26 }, // TRANSFER AMOUNT
                { width: 45 }  // REMARKS / NARRATION
            ];

            // ── 1. Company Title Banner (Row 1) ──
            worksheet.mergeCells('A1:H1');
            const titleCell = worksheet.getCell('A1');
            titleCell.value = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.';
            titleCell.font = { name: 'Calibri', size: 14, bold: true, color: { argb: 'FFFFFFFF' } };
            titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E5D88' } };
            titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(1).height = 38;

            // ── 2. Subtitle Banner (Row 2) ──
            worksheet.mergeCells('A2:H2');
            const subTitleCell = worksheet.getCell('A2');
            const todayStr = (new Date()).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).toUpperCase();
            subTitleCell.value = 'INTERNAL CONTRA TRANSFERS DIRECTORY · GENERATED ON ' + todayStr;
            subTitleCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFF0E6B3' } };
            subTitleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF232018' } };
            subTitleCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(2).height = 26;

            // ── 3. Table Column Headers (Row 3) ──
            const headerRow = worksheet.getRow(3);
            headerRow.height = 30;
            const headers = ['SL NO', 'VOUCHER NO.', 'DATE', 'FROM ACCOUNT (SOURCE)', 'TO ACCOUNT (DESTINATION)', 'MODE / REF NO.', 'TRANSFER AMOUNT (RS)', 'REMARKS / NARRATION'];
            headers.forEach((h, i) => {
                const cell = headerRow.getCell(i + 1);
                cell.value = h;
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF9C8226' } };
                cell.alignment = {
                    horizontal: i === 0 || i === 1 || i === 2 || i === 5 ? 'center' : (i === 6 ? 'right' : 'left'),
                    vertical: 'middle'
                };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF826D1F' } },
                    bottom: { style: 'thin', color: { argb: 'FF826D1F' } },
                    left: { style: 'thin', color: { argb: 'FF826D1F' } },
                    right: { style: 'thin', color: { argb: 'FF826D1F' } }
                };
            });

            // ── 4. Populate Data Rows ──
            const contras = this.filteredContras;
            let totalTransferAmt = 0;

            contras.forEach((v, index) => {
                const rowIndex = index + 4;
                const row = worksheet.getRow(rowIndex);
                row.height = 26;

                const bgHex = (index % 2 === 0) ? 'FFFFFFFF' : 'FFF8FAFC';
                const amt = parseFloat(v.amount || 0);
                totalTransferAmt += amt;

                // SL NO
                const c1 = row.getCell(1);
                c1.value = index + 1;
                c1.alignment = { horizontal: 'center', vertical: 'middle' };
                c1.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF64748B' } };

                // VOUCHER NO
                const c2 = row.getCell(2);
                c2.value = v.voucher_number || '';
                c2.alignment = { horizontal: 'center', vertical: 'middle' };
                c2.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };

                // DATE
                const c3 = row.getCell(3);
                const rawDate = v.date ? v.date : '';
                if (rawDate && /^\d{4}-\d{2}-\d{2}$/.test(rawDate)) {
                    const [yyyy, mm, dd] = rawDate.split('-');
                    c3.value = new Date(parseInt(yyyy), parseInt(mm) - 1, parseInt(dd));
                } else {
                    c3.value = rawDate;
                }
                c3.numFormat = 'DD-MMM-YYYY';
                c3.alignment = { horizontal: 'center', vertical: 'middle' };
                c3.font = { name: 'Calibri', size: 10, color: { argb: 'FF334155' } };

                // FROM ACCOUNT (Source - Blue text accent)
                const c4 = row.getCell(4);
                c4.value = v.from_account || '—';
                c4.alignment = { horizontal: 'left', vertical: 'middle' };
                c4.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF1E3A8A' } };

                // TO ACCOUNT (Destination - Emerald Green text accent)
                const c5 = row.getCell(5);
                c5.value = v.to_account || '—';
                c5.alignment = { horizontal: 'left', vertical: 'middle' };
                c5.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF065F46' } };

                // REF NO
                const c6 = row.getCell(6);
                c6.value = v.reference_no || 'RTGS / UTR';
                c6.alignment = { horizontal: 'center', vertical: 'middle' };
                c6.font = { name: 'Calibri', size: 10, color: { argb: 'FF475569' } };

                // TRANSFER AMOUNT (Light Blue background fill + Currency Number Format)
                const c7 = row.getCell(7);
                c7.value = amt;
                c7.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
                c7.alignment = { horizontal: 'right', vertical: 'middle' };
                c7.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };

                // NARRATION
                const c8 = row.getCell(8);
                c8.value = v.narration || '';
                c8.alignment = { horizontal: 'left', vertical: 'middle' };
                c8.font = { name: 'Calibri', size: 10, italic: true, color: { argb: 'FF475569' } };

                for (let col = 1; col <= 8; col++) {
                    const c = row.getCell(col);
                    if (col === 7) {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFEFF6FF' } };
                    } else {
                        c.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: bgHex } };
                    }
                    c.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };
                }
            });

            // ── 5. Total Register Summary Row ──
            const totalRowIndex = contras.length + 4;
            worksheet.mergeCells(`A${totalRowIndex}:F${totalRowIndex}`);
            
            const totalLabelCell = worksheet.getCell(`A${totalRowIndex}`);
            totalLabelCell.value = 'TOTAL REGISTER SUMMARY (CONTRA TRANSFERS)';
            totalLabelCell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
            totalLabelCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E5D88' } };
            totalLabelCell.alignment = { horizontal: 'center', vertical: 'middle' };

            const totalAmtCell = worksheet.getCell(`G${totalRowIndex}`);
            totalAmtCell.value = totalTransferAmt;
            totalAmtCell.numFormat = '#,##0.00;[Red]-#,##0.00;0.00';
            totalAmtCell.font = { name: 'Calibri', size: 11, bold: true, color: { argb: 'FF38BDF8' } };
            totalAmtCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E5D88' } };
            totalAmtCell.alignment = { horizontal: 'right', vertical: 'middle' };

            const totalCountCell = worksheet.getCell(`H${totalRowIndex}`);
            totalCountCell.value = `${contras.length} Contra Transfers Logged`;
            totalCountCell.font = { name: 'Calibri', size: 9.5, bold: true, italic: true, color: { argb: 'FFFFFFFF' } };
            totalCountCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E5D88' } };
            totalCountCell.alignment = { horizontal: 'left', vertical: 'middle' };

            const totalRow = worksheet.getRow(totalRowIndex);
            totalRow.height = 32;
            for (let col = 1; col <= 8; col++) {
                totalRow.getCell(col).border = {
                    top: { style: 'thin', color: { argb: 'FF164666' } },
                    bottom: { style: 'thin', color: { argb: 'FF164666' } },
                    left: { style: 'thin', color: { argb: 'FF164666' } },
                    right: { style: 'thin', color: { argb: 'FF164666' } }
                };
            }

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const filename = 'Contra_Vouchers_Directory_' + (new Date().toISOString().split('T')[0]) + '.xlsx';
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        printVoucherSlip() {
            window.print();
        },

        formatCurrency(val) {
            return Number(Math.round(val || 0)).toLocaleString('en-IN');
        }
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

</x-erp-layout>
