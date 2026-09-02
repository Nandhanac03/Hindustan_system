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
        
        <!-- TOP FILTER CONTROL CARD -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-4 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">SELECT BANK ACCOUNT / FILTER FOR CONTRA VOUCHERS</h3>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-3">
                <!-- SELECT BANK ACCOUNT FILTER -->
                <div class="w-full md:w-1/3">
                    <select x-model="selectedBankFilter" @change="currentPage = 1"
                            class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-3.5 text-xs font-bold text-slate-800 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition shadow-2xs truncate">
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
                </div>

                <!-- SEARCH INPUT BAR -->
                <div class="w-full md:w-1/3">
                    <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Search by Voucher No., Ref No., Particulars..."
                           class="w-full h-11 bg-slate-50 border border-slate-200 rounded-xl px-3.5 text-xs font-bold text-slate-800 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none transition shadow-2xs">
                </div>

                <!-- ACTION BUTTONS GRID -->
                <div class="w-full md:w-1/3 flex items-center gap-2">
                    <button type="button" @click="currentPage = 1" class="flex-1 h-11 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-2xs flex items-center justify-center gap-2 cursor-pointer border border-[#a38c29]/40">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>SEARCH</span>
                    </button>
                    <button type="button" @click="printTable()" class="px-4 h-11 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>PRINT</span>
                    </button>
                    <button type="button" @click="exportExcel()" class="px-4 h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>EXCEL</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- DIRECTORY TABLE CONTAINER WITH GOLD HEADER BAR -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs space-y-0">
            
            <!-- GOLD BANNER HEADER BAR -->
            <div class="bg-[#a38c29] text-white px-5 py-3.5 flex items-center justify-between">
                <div>
                    <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider">ALL CONTRA ENTRIES DIRECTORY</h3>
                    <p class="text-[11px] text-amber-100 font-medium">Overview of digital fund movements, cash deposits, and petty cash replenishments.</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-[#8a7522] border border-amber-300/30 text-white text-xs font-mono font-black uppercase tracking-widest shadow-2xs" x-text="filteredContras.length + ' CONTRA VOUCHERS'">
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

    <!-- ── 3. ADD CONTRA ENTRY FORM MODAL (POPUP DIALOG) ── -->
    <div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white rounded-3xl shadow-2xl max-w-[1400px] w-full overflow-hidden border border-slate-100 flex flex-col max-h-[96vh]"
             @click.away="showFormModal = false">
            
            <!-- MODAL HEADER BAR -->
            <div class="bg-slate-900 px-5 py-3.5 border-b border-[#a38c29]/30 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#a38c29] text-white font-bold flex items-center justify-center text-sm shadow-2xs">
                        +
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-white uppercase tracking-wider">Add Contra Entry</h2>
                        <p class="text-[11px] text-slate-400 font-medium">Record internal bank transfers, cash deposits, or cash withdrawals.</p>
                    </div>
                </div>
                <button type="button" @click="showFormModal = false" class="w-8 h-8 rounded-full bg-white/10 text-slate-300 hover:bg-white/20 hover:text-white flex items-center justify-center transition cursor-pointer">
                    ✕
                </button>
            </div>

            <!-- MODAL BODY FORM -->
            <form action="{{ route('vouchers.contra.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-3.5 bg-slate-50/50">
                @csrf

                <!-- Top Row: Date, Voucher Number, Project Tag -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Voucher Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" required x-model="form.date"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Voucher Number</label>
                        <input type="text" name="voucher_number" value="{{ $voucherNumber }}" readonly
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Project Tag (Optional)</label>
                        <select name="project_id" x-model="form.project_id" @change="updateProjectName()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                            @if(count($projects) > 1)
                                <option value="">Select Project / Global Treasury</option>
                            @endif
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ count($projects) === 1 ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->code ?? 'PRJ-'.$p->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- SECTION 1 & 2 GRID: TRANSACTION TYPE & TRANSFER ACCOUNTS -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                    
                    <!-- 1. TRANSACTION TYPE (4 COLS) -->
                    <div class="md:col-span-4 p-4 rounded-2xl bg-white border border-slate-200 space-y-3 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#a38c29] text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <label class="text-xs font-extrabold text-slate-900 uppercase">Transaction Type <span class="text-rose-500">*</span></label>
                        </div>

                        <select name="transaction_type" x-model="transactionType" @change="onTransactionTypeChange()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none transition-all">
                            <option value="bank_to_bank">Bank to Bank Transfer</option>
                            <option value="cash_withdrawal">Bank Cash Withdrawal (Site Petty Cash Box Refill)</option>
                            <option value="cash_deposit">Cash Deposit (Cash Box → Bank Account)</option>
                        </select>
                    </div>

                    <!-- 2. TRANSFER ACCOUNTS (8 COLS - FROM COMPANY BANK ACCOUNT MASTER) -->
                    <div class="md:col-span-8 p-4 rounded-2xl bg-white border border-slate-200 space-y-3 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#a38c29] text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <label class="text-xs font-extrabold text-slate-900 uppercase">Transfer Accounts</label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <!-- FROM ACCOUNT (SEARCHABLE COMBOBOX - FROM COMPANY BANK ACCOUNT MASTER) -->
                            <div class="sm:col-span-5 space-y-1 relative" x-data="{ openFromDropdown: false, fromSearch: '' }" @click.outside="openFromDropdown = false">
                                <label class="text-[11px] font-bold text-slate-700 block">From Account (Credit / Source) <span class="text-rose-500">*</span></label>
                                
                                <input type="hidden" name="credit_account_id" :value="form.credit_account_id" required>

                                <button type="button" @click="openFromDropdown = !openFromDropdown"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] flex items-center justify-between transition-all cursor-pointer shadow-2xs">
                                    <span class="truncate text-left" x-text="selectedFromAccountName || 'Choose Company Bank Account...'">Choose Company Bank Account...</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1 transition-transform" :class="openFromDropdown ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <!-- SEARCH DROPDOWN POPOVER -->
                                <div x-show="openFromDropdown" x-cloak class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2.5 space-y-2">
                                    <div class="relative flex items-center">
                                        <input type="text" x-model="fromSearch" placeholder="Search Company Bank Account..." autofocus
                                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20">
                                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div class="max-h-48 overflow-y-auto space-y-1">
                                        <template x-for="acc in filteredFromAccounts(fromSearch)" :key="acc.id">
                                            <button type="button" @click="selectFromAccount(acc); openFromDropdown = false; fromSearch = ''"
                                                    class="w-full text-left px-3 py-2 hover:bg-amber-50 rounded-xl text-xs font-bold text-slate-800 flex items-center justify-between transition cursor-pointer group">
                                                <span x-text="acc.name" class="group-hover:text-[#a38c29] transition"></span>
                                                <span class="text-[10px] font-mono font-bold text-[#a38c29] bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60" x-text="'₹ ' + formatCurrency(acc.balance)"></span>
                                            </button>
                                        </template>
                                        <div x-show="filteredFromAccounts(fromSearch).length === 0" class="p-3 text-center text-xs font-semibold text-slate-400">
                                            No matching company bank account found
                                        </div>
                                    </div>
                                </div>

                                <span class="text-[10px] font-bold text-[#a38c29] block mt-1" x-text="'Available Balance: ₹ ' + formatCurrency(fromAccountBalance)">Available Balance: ₹ 0</span>
                            </div>

                            <!-- ARROW ICON -->
                            <div class="sm:col-span-2 flex items-center justify-center pt-2 sm:pt-4">
                                <div class="w-8 h-8 rounded-full bg-amber-50 text-[#7a671b] flex items-center justify-center font-bold text-base shadow-2xs border border-amber-200">
                                    →
                                </div>
                            </div>

                            <!-- TO ACCOUNT (SEARCHABLE COMBOBOX) -->
                            <div class="sm:col-span-5 space-y-1 relative" x-data="{ openToDropdown: false, toSearch: '' }" @click.outside="openToDropdown = false">
                                <label class="text-[11px] font-bold text-slate-700 block">To Account (Debit / Destination) <span class="text-rose-500">*</span></label>
                                
                                <input type="hidden" name="destination_account_id" :value="form.destination_account_id" required>

                                <button type="button" @click="openToDropdown = !openToDropdown"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-[#a38c29] rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] flex items-center justify-between transition-all cursor-pointer shadow-2xs">
                                    <span class="truncate text-left" x-text="selectedToAccountName || 'Choose Destination Account...'">Choose Destination Account...</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1 transition-transform" :class="openToDropdown ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <!-- SEARCH DROPDOWN POPOVER -->
                                <div x-show="openToDropdown" x-cloak class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2.5 space-y-2">
                                    <div class="relative flex items-center">
                                        <input type="text" x-model="toSearch" placeholder="Search Destination Account..." autofocus
                                               class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20">
                                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <div class="max-h-48 overflow-y-auto space-y-1">
                                        <template x-for="acc in filteredToAccounts(toSearch)" :key="acc.id">
                                            <button type="button" @click="selectToAccount(acc); openToDropdown = false; toSearch = ''"
                                                    class="w-full text-left px-3 py-2 hover:bg-amber-50 rounded-xl text-xs font-bold text-slate-800 flex items-center justify-between transition cursor-pointer group">
                                                <span x-text="acc.name" class="group-hover:text-[#a38c29] transition"></span>
                                                <span class="text-[10px] font-mono font-bold text-[#a38c29] bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60" x-text="'₹ ' + formatCurrency(acc.balance)"></span>
                                            </button>
                                        </template>
                                        <div x-show="filteredToAccounts(toSearch).length === 0" class="p-3 text-center text-xs font-semibold text-slate-400">
                                            No matching destination account found
                                        </div>
                                    </div>
                                </div>

                                <span class="text-[10px] font-bold text-[#a38c29] block mt-1" x-text="'Available Balance: ₹ ' + formatCurrency(toAccountBalance)">Available Balance: ₹ 0</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SECTION 3 & 4 GRID: AMOUNT & SUPPORTING DOCUMENT -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">

                    <!-- 3. AMOUNT & REFERENCE DETAILS (8 COLS) -->
                    <div class="md:col-span-8 p-4 rounded-2xl bg-white border border-slate-200 space-y-3 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#a38c29] text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <label class="text-xs font-extrabold text-slate-900 uppercase">Amount & Reference Details</label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-700 block">Transfer Amount (Rs.) <span class="text-rose-500">*</span></label>
                                <input type="number" name="amount" required min="1" step="1" placeholder="0"
                                       x-model.number="form.amount"
                                       @keydown="if($event.key === '.' || $event.key === 'e' || $event.key === 'E' || $event.key === '-') $event.preventDefault()"
                                       oninput="window.sanitizeAmountInput && window.sanitizeAmountInput(this)"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-black text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none">
                            </div>

                            <div>
                                <label class="text-[11px] font-bold text-slate-700 block mb-1">Payment Mode <span class="text-rose-500">*</span></label>
                                <select name="payment_mode" required x-model="form.payment_mode"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none">
                                    <option value="RTGS">RTGS / NEFT / IMPS</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Cash Withdrawal">Cash Withdrawal</option>
                                    <option value="Cash Deposit Slip">Cash Deposit Slip</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-[11px] font-bold text-slate-700 block mb-1">Transaction Reference / UTR No.</label>
                                <input type="text" name="reference_no" placeholder="e.g. UTR8821 or Chq #40012" x-model="form.reference_no"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none">
                            </div>
                        </div>

                        <!-- Quick Select Amount Preset Pills -->
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <span class="text-[10px] font-extrabold uppercase text-[#7a671b] block">Quick Select Amount Presets:</span>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button" @click="form.amount = 5000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹5,000</button>
                                <button type="button" @click="form.amount = 10000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹10,000</button>
                                <button type="button" @click="form.amount = 25000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹25,000</button>
                                <button type="button" @click="form.amount = 50000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹50,000</button>
                                <button type="button" @click="form.amount = 100000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹1,00,000</button>
                                <button type="button" @click="form.amount = 500000" class="px-2.5 py-1 bg-white hover:bg-[#a38c29] hover:text-white text-[#7a671b] rounded-lg text-[11px] font-bold border border-amber-200 transition shadow-2xs cursor-pointer">+ ₹5,00,000</button>
                                <button type="button" @click="form.amount = ''" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[11px] font-bold border border-rose-200 transition cursor-pointer">Clear</button>
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-slate-700 block mb-1">Remarks / Purpose</label>
                            <textarea name="narration" rows="2" placeholder="e.g. Fund transfer for site payroll release or site cash box replenishment" x-model="form.narration"
                                      class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] focus:outline-none resize-none"></textarea>
                        </div>
                    </div>

                    <!-- 4. SUPPORTING DOCUMENT (4 COLS) -->
                    <div class="md:col-span-4 p-4 rounded-2xl bg-white border border-slate-200 space-y-3 shadow-2xs h-full flex flex-col justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#a38c29] text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <label class="text-xs font-extrabold text-slate-900 uppercase">Supporting Document</label>
                        </div>

                        <div class="space-y-1">
                            <span class="text-[11px] font-bold text-slate-700 block">Attachment Upload</span>
                            <div x-show="!fileName" class="border-2 border-dashed border-slate-200 hover:border-[#a38c29] rounded-xl p-4 text-center bg-slate-50/50 flex flex-col items-center justify-center h-28 relative transition cursor-pointer">
                                <svg class="w-7 h-7 text-[#a38c29] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span class="text-xs font-bold text-slate-700 block">Drag & Drop files here or click to upload</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">PDF, JPG, PNG (Max 5MB)</span>
                                <input type="file" name="attachment" @change="handleFileChange($event)" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>

                            <div x-show="fileName" x-cloak class="border border-amber-300 bg-amber-50/80 rounded-xl p-3 flex items-center justify-between h-28 shadow-2xs">
                                <div class="flex items-center gap-2.5 overflow-hidden">
                                    <div class="w-8 h-8 rounded-lg bg-[#a38c29] text-white flex items-center justify-center shrink-0 text-xs">📄</div>
                                    <div class="overflow-hidden">
                                        <span class="text-xs font-bold text-slate-900 block truncate" x-text="fileName"></span>
                                        <span class="text-[10px] text-[#7a671b] font-mono block" x-text="fileSize"></span>
                                    </div>
                                </div>
                                <button type="button" @click="clearFile()" class="p-1 text-rose-600 hover:bg-rose-100 rounded-lg transition">✕</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- MODAL FOOTER ACTION BUTTONS -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="showFormModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold uppercase rounded-xl transition shadow-2xs cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase rounded-xl transition shadow-2xs flex items-center gap-2 border border-[#a38c29]/40 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>Save & Post Entry</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── 4. CONTRA VOUCHERS SLIP / RECEIPT MODAL (PRINTABLE) ── -->
    <div x-show="showSlipModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div id="printable-slip-modal" class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full overflow-hidden flex flex-col max-h-[90vh]"
             @click.away="closeSlipModal()">
            
            <div class="relative overflow-hidden bg-slate-900 px-6 py-5 flex-shrink-0 border-b border-[#a38c29]/40">
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD. · CONTRA VOUCHER</p>
                        <h2 class="text-lg font-extrabold text-white flex items-center gap-3">
                            <span x-text="activeVoucher.voucher_number || 'JV-CONTRA-045'">JV-CONTRA-045</span>
                            <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Official Record</span>
                        </h2>
                    </div>
                    <button type="button" @click="closeSlipModal()" class="w-8 h-8 rounded-full bg-white/10 text-slate-300 hover:bg-white/20 hover:text-white flex items-center justify-center transition cursor-pointer">
                        ✕
                    </button>
                </div>
            </div>

            <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-white">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-amber-50/50 border border-amber-200">
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Voucher Date</span>
                        <strong class="text-xs font-mono font-bold text-slate-900 block mt-0.5" x-text="activeVoucher.date || '31-Aug-2026'">31-Aug-2026</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Reference / UTR</span>
                        <strong class="text-xs font-mono font-bold text-slate-900 block mt-0.5" x-text="activeVoucher.reference_no || 'Cheque #40012'">Cheque #40012</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Project Tag</span>
                        <strong class="text-xs font-bold text-slate-900 block mt-0.5 truncate" x-text="activeVoucher.project_name || 'Global Treasury'">Global Treasury</strong>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-[#a38c29] uppercase block">Transfer Amount</span>
                        <strong class="text-sm font-mono font-extrabold text-[#a38c29] block mt-0.5" x-text="'₹ ' + formatCurrency(activeVoucher.amount || 25000)">₹ 25,000</strong>
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

        selectFromAccount(acc) {
            this.form.credit_account_id = acc.id;
            this.selectedFromAccountName = acc.name;
            this.fromAccountBalance = acc.balance;
        },

        selectToAccount(acc) {
            this.form.destination_account_id = acc.id;
            this.selectedToAccountName = acc.name;
            this.toAccountBalance = acc.balance;
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

        exportExcel() {
            let url = '{{ route("vouchers.contra.export") }}';
            const params = new URLSearchParams();
            if (this.selectedBankFilter) params.append('bank', this.selectedBankFilter);
            if (this.searchQuery) params.append('search', this.searchQuery);
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
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

</x-erp-layout>
