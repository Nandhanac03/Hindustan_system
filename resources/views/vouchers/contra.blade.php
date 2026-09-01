<x-erp-layout title="Contra Voucher Creation Workspace - HindustanERP" headerTitle="Financial Accounting Workspace">

<div class="max-w-[1600px] mx-auto space-y-6" x-data="contraVoucherBlueprint()">

    <!-- ── 1. MODERN HEADER BAR ── -->
    <div class="bg-gradient-to-r from-white via-amber-50/20 to-white rounded-3xl border border-slate-200/90 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-amber-100/30 to-transparent pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#a38c29] via-[#b89635] to-[#8a741f] text-white rounded-2xl shadow-md flex items-center justify-center shrink-0 border border-amber-300/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div>
                    <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 mb-1 tracking-wider uppercase">
                        <a href="/" class="hover:text-slate-600 transition">HOME</a>
                        <span>›</span>
                        <span>ACCOUNTING</span>
                        <span>›</span>
                        <span class="text-[#a38c29] font-black">CONTRA VOUCHER</span>
                    </nav>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase flex items-center gap-3">
                        <span>Create Contra Voucher</span>
                        <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-amber-100/90 text-[#7a671b] border border-amber-300/80 tracking-wider">Treasury Hub</span>
                    </h1>
                </div>
            </div>

            <!-- Quick Status Badges -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase bg-white text-slate-700 border border-slate-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Asset-to-Asset (1000s)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase bg-white text-slate-700 border border-slate-200 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Double-Entry Safeguard
                </span>
            </div>
        </div>

        @if ($errors->any())
            <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- ── 2. MAIN FORM & SIDEBAR GRID ── -->
    <form action="{{ route('vouchers.contra.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT MAIN PANEL (8 COLS) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- MAIN FORM CARD -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 sm:p-8 space-y-6">
                    
                    <!-- ── A. HEADER DETAILS ── -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center text-xs font-black">A</span>
                                <span>Header & Transfer Type</span>
                            </h3>
                            <span class="text-[10px] font-black bg-amber-100/70 text-[#7a671b] px-2.5 py-0.5 rounded-full uppercase border border-amber-200/80">Classification</span>
                        </div>

                        <!-- Modern Visual Quick Selector Cards for Transaction Type -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Transaction Type *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <button type="button" @click="transactionType = 'bank_to_bank'; onTransactionTypeChange()"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 flex items-center gap-3.5 cursor-pointer group"
                                        :class="transactionType === 'bank_to_bank' ? 'bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-[#a38c29] shadow-sm' : 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30'">
                                    <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center shrink-0 shadow-xs transition-transform group-hover:scale-105"
                                         :class="transactionType === 'bank_to_bank' ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29]' : 'bg-slate-700'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 block leading-tight">Bank to Bank</span>
                                        <span class="text-[10px] font-semibold text-slate-500 block mt-0.5">Corporate Accounts</span>
                                    </div>
                                </button>

                                <button type="button" @click="transactionType = 'cash_deposit'; onTransactionTypeChange()"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 flex items-center gap-3.5 cursor-pointer group"
                                        :class="transactionType === 'cash_deposit' ? 'bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-[#a38c29] shadow-sm' : 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30'">
                                    <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center shrink-0 shadow-xs transition-transform group-hover:scale-105"
                                         :class="transactionType === 'cash_deposit' ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29]' : 'bg-slate-700'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 block leading-tight">Cash Deposit</span>
                                        <span class="text-[10px] font-semibold text-slate-500 block mt-0.5">Cash Box to Bank</span>
                                    </div>
                                </button>

                                <button type="button" @click="transactionType = 'cash_withdrawal'; onTransactionTypeChange()"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 flex items-center gap-3.5 cursor-pointer group"
                                        :class="transactionType === 'cash_withdrawal' ? 'bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-[#a38c29] shadow-sm' : 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30'">
                                    <div class="w-10 h-10 rounded-xl text-white flex items-center justify-center shrink-0 shadow-xs transition-transform group-hover:scale-105"
                                         :class="transactionType === 'cash_withdrawal' ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29]' : 'bg-slate-700'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-slate-900 block leading-tight">Cash Withdrawal</span>
                                        <span class="text-[10px] font-semibold text-slate-500 block mt-0.5">Bank to Petty Cash</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Hidden select bound to form model -->
                        <input type="hidden" name="transaction_type" x-model="transactionType">

                        <!-- Date, Number, & Project Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <!-- Voucher Date -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Voucher Date *</label>
                                <input type="date" name="date" required x-model="form.date"
                                       class="w-full h-11 bg-white border border-slate-300 text-slate-900 rounded-xl px-3 py-2 text-xs font-black focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 focus:outline-none cursor-pointer shadow-2xs">
                            </div>

                            <!-- Voucher Number -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Voucher Number</label>
                                <input type="text" name="voucher_number" value="{{ $voucherNumber }}" readonly
                                       class="w-full h-11 bg-slate-100 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-mono font-black focus:outline-none cursor-not-allowed shadow-2xs">
                            </div>

                            <!-- Project Tag -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Project Tag (Optional)</label>
                                <select name="project_id" x-model="form.project_id" @change="updateProjectName()"
                                        class="w-full h-11 bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 py-2 text-xs font-bold focus:border-[#a38c29] focus:outline-none cursor-pointer shadow-2xs truncate">
                                    @if(count($projects) > 1)
                                        <option value="">Global Treasury (No Specific Project)</option>
                                    @endif
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ count($projects) === 1 ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->code ?? 'PRJ-'.$p->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ── B. TRANSFER ACCOUNTS (SOURCE & DESTINATION) ── -->
                    <div class="space-y-4 pt-2 border-t border-slate-150">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center text-xs font-black">B</span>
                                <span>Transfer Accounts (Source & Destination)</span>
                            </h3>
                            <span class="text-[10px] bg-amber-100/90 text-[#7a671b] px-2.5 py-0.5 rounded-full font-black uppercase border border-amber-200/80">Asset Accounts Only</span>
                        </div>

                        <!-- FROM & TO ACCOUNTS CONTAINER -->
                        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- FROM ACCOUNT CARD (CREDIT / SOURCE) -->
                            <div class="p-5 bg-gradient-to-br from-rose-50/50 via-white to-amber-50/30 rounded-2xl border-2 transition-all space-y-3"
                                 :class="form.credit_account_id ? 'border-rose-300 shadow-sm' : 'border-slate-200'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-rose-800 uppercase tracking-widest flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        From Account (Credit / Source) *
                                    </span>
                                    <span class="text-[9px] font-black text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full uppercase">CREDIT (CR)</span>
                                </div>

                                <select name="credit_account_id" id="credit_account_id" required x-model="form.credit_account_id" @change="updateAccounts()"
                                        class="w-full h-11 bg-white border border-rose-200 text-slate-900 rounded-xl px-3 text-xs font-black focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 focus:outline-none cursor-pointer shadow-2xs">
                                    <option value="">Choose Source Bank or Petty Cash Account...</option>
                                    @foreach($assetAccounts as $acc)
                                        <option value="{{ $acc->id }}" data-balance="{{ $acc->current_balance ?? 0 }}" data-name="{{ $acc->name }}">
                                            {{ $acc->name }} (Available: ₹{{ number_format($acc->current_balance ?? 0, 2) }})
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex items-center justify-between pt-1 border-t border-rose-100/60 text-xs">
                                    <span class="text-slate-500 font-medium">Source Available Balance:</span>
                                    <strong class="font-mono text-sm font-black text-slate-900" x-text="'₹' + formatCurrency(fromAccountBalance)">₹0.00</strong>
                                </div>
                            </div>

                            <!-- SWAP BUTTON (CENTER FLOATING) -->
                            <div class="hidden md:flex absolute inset-0 items-center justify-center pointer-events-none z-10">
                                <button type="button" @click="swapAccounts()" class="pointer-events-auto w-11 h-11 rounded-full bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white hover:from-[#8d7923] hover:to-[#8d7923] transition-all shadow-md flex items-center justify-center border-2 border-white cursor-pointer group" title="Swap Source and Destination Accounts">
                                    <svg class="w-5 h-5 transition-transform group-hover:rotate-180 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- TO ACCOUNT CARD (DEBIT / DESTINATION) -->
                            <div class="p-5 bg-gradient-to-br from-emerald-50/50 via-white to-amber-50/30 rounded-2xl border-2 transition-all space-y-3"
                                 :class="form.destination_account_id ? 'border-emerald-300 shadow-sm' : 'border-slate-200'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-emerald-800 uppercase tracking-widest flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        To Account (Debit / Destination) *
                                    </span>
                                    <span class="text-[9px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full uppercase">DEBIT (DR)</span>
                                </div>

                                <select name="destination_account_id" id="destination_account_id" required x-model="form.destination_account_id" @change="updateAccounts()"
                                        class="w-full h-11 bg-white border border-emerald-200 text-slate-900 rounded-xl px-3 text-xs font-black focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer shadow-2xs">
                                    <option value="">Choose Destination Bank or Petty Cash Account...</option>
                                    @foreach($assetAccounts as $acc)
                                        <option value="{{ $acc->id }}" data-balance="{{ $acc->current_balance ?? 0 }}" data-name="{{ $acc->name }}">
                                            {{ $acc->name }} (Available: ₹{{ number_format($acc->current_balance ?? 0, 2) }})
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex items-center justify-between pt-1 border-t border-emerald-100/60 text-xs">
                                    <span class="text-slate-500 font-medium">Destination Available Balance:</span>
                                    <strong class="font-mono text-sm font-black text-slate-900" x-text="'₹' + formatCurrency(toAccountBalance)">₹0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── C. AMOUNT & REFERENCE DETAILS ── -->
                    <div class="space-y-4 pt-2 border-t border-slate-150">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center text-xs">C</span>
                                <span>Amount & Reference Details</span>
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Financial Particulars</span>
                        </div>

                        <!-- TRANSFER AMOUNT HERO INPUT -->
                        <div class="p-5 bg-gradient-to-r from-amber-50/90 via-white to-amber-50/90 border border-amber-300/90 rounded-2xl shadow-xs space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-black uppercase tracking-widest text-amber-900">Transfer Amount (Rs.) *</label>
                                <span class="text-[10px] bg-amber-200/80 text-amber-950 px-2.5 py-0.5 rounded-md font-mono font-bold">Real-Time Treasury Verification</span>
                            </div>

                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-[#a38c29] font-black text-2xl">₹</span>
                                <input type="number" name="amount" required min="0.01" step="0.01" placeholder="0.00"
                                       x-model.number="form.amount"
                                       class="w-full bg-white border-2 border-amber-300 text-[#a38c29] text-3xl font-mono font-black rounded-xl pl-10 pr-4 py-3 focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/15 focus:outline-none shadow-2xs">
                            </div>

                            <!-- Quick Preset Amount Buttons -->
                            <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-amber-200/60">
                                <span class="text-[10px] font-extrabold uppercase text-slate-500 mr-1">Quick Select:</span>
                                <button type="button" @click="form.amount = 10000" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-slate-800 rounded-lg text-[10px] font-bold border border-amber-300 transition shadow-2xs cursor-pointer">+ ₹10,000</button>
                                <button type="button" @click="form.amount = 50000" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-slate-800 rounded-lg text-[10px] font-bold border border-amber-300 transition shadow-2xs cursor-pointer">+ ₹50,000</button>
                                <button type="button" @click="form.amount = 100000" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-slate-800 rounded-lg text-[10px] font-bold border border-amber-300 transition shadow-2xs cursor-pointer">+ ₹1,00,000</button>
                                <button type="button" @click="form.amount = 500000" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-slate-800 rounded-lg text-[10px] font-bold border border-amber-300 transition shadow-2xs cursor-pointer">+ ₹5,00,000</button>
                                <button type="button" @click="form.amount = 0" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[10px] font-bold border border-rose-200 transition shadow-2xs cursor-pointer">Clear</button>
                            </div>
                        </div>

                        <!-- PAYMENT MODE & REFERENCE NUMBER -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Payment Mode -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Payment Mode *</label>
                                <select name="payment_mode" required x-model="form.payment_mode"
                                        class="w-full h-11 bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 text-xs font-black focus:border-[#a38c29] focus:outline-none cursor-pointer shadow-2xs">
                                    <option value="NEFT / RTGS / IMPS">NEFT / RTGS / IMPS</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Cash Withdrawal">Cash Withdrawal</option>
                                    <option value="Cash Deposit">Cash Deposit Slip</option>
                                    <option value="Internal Transfer">Internal Direct Transfer</option>
                                </select>
                            </div>

                            <!-- Transaction Reference / UTR Number -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Transaction Reference / UTR Number</label>
                                <input type="text" name="reference_no" placeholder="Bank UTR Number or Cheque Number"
                                       x-model="form.reference_no"
                                       class="w-full h-11 bg-white border border-slate-300 text-slate-900 rounded-xl px-3.5 text-xs font-bold focus:border-[#a38c29] focus:outline-none shadow-2xs">
                            </div>
                        </div>

                        <!-- REMARKS & SUPPORTING DOCUMENT -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                            <!-- Remarks / Purpose -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Remarks / Purpose *</label>
                                <textarea name="narration" required rows="3" placeholder="e.g. Fund transfer for site payroll release or Petty cash replenishment for August."
                                          x-model="form.narration"
                                          class="w-full h-[96px] bg-white border border-slate-300 text-slate-900 rounded-xl p-3 text-xs font-medium focus:border-[#a38c29] focus:outline-none shadow-2xs resize-none"></textarea>
                            </div>

                            <!-- Supporting Document (D) -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest block">Supporting Document (Upload Slip / Receipt)</label>
                                
                                <!-- Dropzone when no file selected -->
                                <div x-show="!fileName" class="border-2 border-dashed border-slate-200 rounded-xl p-3 text-center bg-slate-50/50 hover:bg-white hover:border-[#a38c29] transition cursor-pointer flex flex-col items-center justify-center h-[96px] relative">
                                    <svg class="w-6 h-6 text-[#a38c29] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span class="text-[11px] font-bold text-slate-600 block">Upload Bank Receipt / Counterfoil Slip</span>
                                    <span class="text-[9px] text-slate-400">PDF, PNG, JPG (Max 5MB)</span>
                                    <input type="file" name="attachment" @change="handleFileChange($event)" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>

                                <!-- File Preview Box when file selected -->
                                <div x-show="fileName" x-cloak class="border-2 border-amber-300 bg-amber-50/60 rounded-xl p-3 flex items-center justify-between h-[96px] relative shadow-2xs">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-9 h-9 rounded-lg bg-[#a38c29] text-white flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="overflow-hidden space-y-0.5">
                                            <span class="text-xs font-black text-slate-900 block truncate" x-text="fileName">File Selected</span>
                                            <span class="text-[10px] font-mono text-amber-800 block font-bold" x-text="'File Size: ' + fileSize">File Size: 0 MB</span>
                                        </div>
                                    </div>
                                    <button type="button" @click="clearFile()" class="p-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white transition shrink-0 z-20 cursor-pointer" title="Remove File">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM ACTIONS -->
                    <div class="pt-4 border-t border-slate-150 flex items-center justify-end gap-3">
                        <a href="{{ route('vouchers.contra.create') }}" class="px-6 py-3 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition">
                            Reset Form
                        </a>
                        <button type="submit"
                                class="px-8 py-3.5 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] hover:from-[#8d7923] hover:to-[#8d7923] text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md hover:shadow-lg flex items-center gap-2.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Contra Voucher</span>
                        </button>
                    </div>

                </div>

            </div>

            <!-- RIGHT SIDEBAR PANEL: AUTOMATED SYSTEM LOGIC (4 COLS) -->
            <div class="lg:col-span-4 space-y-6 sticky top-6">

                <!-- ── 5. REAL-TIME BALANCE CHECK (LIGHT GOLD TREASURY SAFEGUARD) ── -->
                <div class="rounded-3xl border transition-all duration-300 shadow-2xs p-5 space-y-4 overflow-hidden relative"
                     :class="!form.credit_account_id ? 'bg-gradient-to-br from-amber-50/90 via-white to-amber-100/40 text-slate-900 border-amber-300/80' : (balanceCheckStatus ? 'bg-gradient-to-br from-emerald-50/90 via-white to-emerald-50/60 text-slate-900 border-emerald-300' : 'bg-gradient-to-br from-rose-50/90 via-white to-rose-50/60 text-slate-900 border-rose-300')">

                    <!-- Ambient Background Glow -->
                    <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl pointer-events-none transition-colors duration-500"
                         :class="!form.credit_account_id ? 'bg-amber-400/20' : (balanceCheckStatus ? 'bg-emerald-400/20' : 'bg-rose-400/20')"></div>

                    <!-- Card Header / Title -->
                    <div class="flex items-center justify-between relative z-10 border-b pb-3"
                         :class="!form.credit_account_id ? 'border-amber-200/80' : (balanceCheckStatus ? 'border-emerald-200' : 'border-rose-200')">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" :class="!form.credit_account_id ? 'text-[#a38c29]' : (balanceCheckStatus ? 'text-emerald-600' : 'text-rose-600')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest"
                                  :class="!form.credit_account_id ? 'text-slate-800' : (balanceCheckStatus ? 'text-emerald-950' : 'text-rose-950')">
                                Real-Time Balance Safeguard
                            </span>
                        </div>
                        <template x-if="!form.credit_account_id">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100/90 text-[#7a671b] border border-amber-300/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                Select Source
                            </span>
                        </template>
                        <template x-if="form.credit_account_id && balanceCheckStatus">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Funds Verified
                            </span>
                        </template>
                        <template x-if="form.credit_account_id && !balanceCheckStatus">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-100 text-rose-800 border border-rose-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                Deficit Warning
                            </span>
                        </template>
                    </div>

                    <!-- Main Status Banner Display -->
                    <div class="flex items-start gap-3.5 relative z-10">
                        <div class="p-2.5 rounded-2xl text-white shrink-0 shadow-2xs"
                             :class="!form.credit_account_id ? 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29]' : (balanceCheckStatus ? 'bg-gradient-to-br from-emerald-500 to-teal-700' : 'bg-gradient-to-br from-rose-500 to-red-700')">
                            <template x-if="!form.credit_account_id">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </template>
                            <template x-if="form.credit_account_id && balanceCheckStatus">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="form.credit_account_id && !balanceCheckStatus">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </template>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-black uppercase tracking-wide"
                                :class="!form.credit_account_id ? 'text-[#7a671b]' : (balanceCheckStatus ? 'text-emerald-900' : 'text-rose-900')"
                                x-text="!form.credit_account_id ? 'Source Account Pending' : (balanceCheckStatus ? 'Sufficient Balance Available' : 'Insufficient Balance in Source Account')">
                                Sufficient Balance Available
                            </h4>
                            <p class="text-[11px] font-medium leading-relaxed text-slate-700"
                               x-text="!form.credit_account_id ? 'Select the source account above to trigger real-time liquidity verification.' : (balanceCheckStatus ? 'The source account has enough available balance for this transfer.' : 'Critical: The transfer amount exceeds available funds in the source account.')">
                            </p>
                        </div>
                    </div>

                    <!-- Live Treasury Impact Metrics (Grid & Progress Bar) -->
                    <div class="pt-3 border-t space-y-3 relative z-10" x-show="form.credit_account_id"
                         :class="!form.credit_account_id ? 'border-amber-200' : (balanceCheckStatus ? 'border-emerald-200' : 'border-rose-200')">
                        <!-- 3-Column Metrics Breakdown -->
                        <div class="grid grid-cols-3 gap-2 text-center p-2.5 rounded-2xl border"
                             :class="!form.credit_account_id ? 'bg-amber-100/50 border-amber-200/80 text-slate-900' : (balanceCheckStatus ? 'bg-emerald-100/40 border-emerald-200 text-slate-900' : 'bg-rose-100/40 border-rose-200 text-slate-900')">
                            <div>
                                <span class="text-[9px] font-bold text-slate-500 uppercase block">Source Avail.</span>
                                <span class="text-xs font-mono font-black text-slate-900 block mt-0.5" x-text="'₹' + formatCurrency(fromAccountBalance)">₹0.00</span>
                            </div>
                            <div class="border-x px-1" :class="!form.credit_account_id ? 'border-amber-200' : (balanceCheckStatus ? 'border-emerald-200' : 'border-rose-200')">
                                <span class="text-[9px] font-bold text-[#a38c29] uppercase block">Transfer Amt</span>
                                <span class="text-xs font-mono font-black text-[#a38c29] block mt-0.5" x-text="'₹' + formatCurrency(form.amount)">₹0.00</span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold uppercase block" :class="remainingBalance >= 0 ? 'text-emerald-700' : 'text-rose-700'">Post Transfer</span>
                                <span class="text-xs font-mono font-black block mt-0.5"
                                      :class="remainingBalance >= 0 ? 'text-emerald-800' : 'text-rose-700'"
                                      x-text="'₹' + formatCurrency(remainingBalance)">₹0.00</span>
                            </div>
                        </div>

                        <!-- Liquidity Usage Gauge -->
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-600">
                                <span>Liquidity Utilization Gauge</span>
                                <span class="font-mono font-black" :class="usagePercentage > 100 ? 'text-rose-700' : (usagePercentage > 85 ? 'text-amber-800' : 'text-emerald-700')" x-text="usagePercentage + '%'">0%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                                <div class="h-full rounded-full transition-all duration-500"
                                     :style="'width: ' + Math.min(usagePercentage, 100) + '%'"
                                     :class="usagePercentage > 100 ? 'bg-gradient-to-r from-rose-500 to-red-600' : (usagePercentage > 85 ? 'bg-gradient-to-r from-amber-400 to-amber-600' : 'bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29]')"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── DOUBLE-ENTRY JOURNAL IMPACT ── -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Double-Entry Journal (Auto)</span>
                        </h3>
                        <span class="text-[9px] bg-amber-100 text-[#a38c29] px-2 py-0.5 rounded font-black uppercase">System Generated</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <!-- Debit Entry Box (To Account) -->
                        <div class="p-3.5 bg-emerald-50/60 border border-emerald-200/70 rounded-2xl space-y-1">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase text-emerald-800">
                                <span>DEBIT (DR) · TO ACCOUNT</span>
                                <span>Receiver (+ Asset)</span>
                            </div>
                            <strong class="text-slate-900 block font-black text-xs truncate" x-text="toAccountName || 'To Account Not Selected'">To Account Not Selected</strong>
                            <div class="text-right font-mono font-black text-emerald-700 text-sm" x-text="'+ ₹' + formatCurrency(form.amount || 0)">+ ₹0.00</div>
                        </div>

                        <!-- Credit Entry Box (From Account) -->
                        <div class="p-3.5 bg-rose-50/60 border border-rose-200/70 rounded-2xl space-y-1">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase text-rose-800">
                                <span>CREDIT (CR) · FROM ACCOUNT</span>
                                <span>Giver (- Asset)</span>
                            </div>
                            <strong class="text-slate-900 block font-black text-xs truncate" x-text="fromAccountName || 'From Account Not Selected'">From Account Not Selected</strong>
                            <div class="text-right font-mono font-black text-rose-700 text-sm" x-text="'- ₹' + formatCurrency(form.amount || 0)">- ₹0.00</div>
                        </div>

                        <!-- Total Balanced Indicator Bar -->
                        <div class="p-3 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white rounded-2xl flex items-center justify-between font-mono text-xs shadow-2xs">
                            <span class="text-[10px] uppercase font-black text-amber-100 tracking-wider">DOUBLE ENTRY</span>
                            <span class="font-black text-white">BALANCED MATCH</span>
                        </div>
                    </div>
                </div>

                <!-- OVERVIEW SUMMARY CARD -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 space-y-3">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-2">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Voucher Overview</span>
                    </h3>

                    <div class="space-y-2 text-[11px] font-semibold text-slate-600">
                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span>Voucher Date:</span>
                            <strong class="text-slate-900 font-mono" x-text="form.date">2026-08-31</strong>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span>Voucher Ref:</span>
                            <strong class="text-slate-900 font-mono">{{ $voucherNumber }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span>Payment Mode:</span>
                            <strong class="text-slate-900" x-text="form.payment_mode">NEFT / RTGS / IMPS</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Project Tag:</span>
                            <strong class="text-slate-900 truncate max-w-[180px]" x-text="projectName || 'Global Treasury'">Global Treasury</strong>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <!-- ── 4. TABULAR SUMMARY (RECENT CONTRA VOUCHERS TABLE) ── -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4 mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-900 tracking-tight uppercase flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Recent Contra Vouchers Table</span>
                </h2>
                <p class="text-xs text-slate-500 font-medium">Tabular summary of recent bank transfers, cash deposits, and withdrawals</p>
            </div>

            <div class="relative w-full sm:w-72">
                <input type="text" x-model="searchQuery" placeholder="Search by Voucher No., Account, Amount..."
                       class="w-full h-9 bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 text-xs font-semibold focus:border-[#a38c29] focus:outline-none">
                <div class="absolute inset-y-0 left-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border border-slate-200/80 rounded-2xl">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="px-4 py-3.5 text-white">Voucher No.</th>
                        <th class="px-4 py-3.5 text-white">Date</th>
                        <th class="px-4 py-3.5 text-white">From Account (Source)</th>
                        <th class="px-4 py-3.5 text-white">To Account (Destination)</th>
                        <th class="px-4 py-3.5 text-white">Mode / Ref No.</th>
                        <th class="px-4 py-3.5 text-right text-white">Transfer Amount (Rs.)</th>
                        <th class="px-4 py-3.5 text-center text-white">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-800 bg-white">
                    @forelse($recentContras as $v)
                        @php
                            $creditLine = $v->lines->firstWhere('credit', '>', 0);
                            $debitLine = $v->lines->firstWhere('debit', '>', 0);
                            $transferAmt = $debitLine?->debit ?? $creditLine?->credit ?? 0;
                        @endphp
                        <tr class="hover:bg-amber-50/40 transition-colors">
                            <td class="px-4 py-3.5 font-mono font-black text-slate-900 whitespace-nowrap">
                                {{ $v->voucher_number }}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-slate-600">
                                {{ \Carbon\Carbon::parse($v->date)->format('d-M-Y') }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-rose-800 whitespace-nowrap">
                                {{ $creditLine?->account?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-emerald-800 whitespace-nowrap">
                                {{ $debitLine?->account?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-[10px] font-bold">
                                    {{ $v->reference_no ?? 'Direct Transfer' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-black text-slate-900 whitespace-nowrap">
                                Rs. {{ number_format($transferAmt, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <a href="{{ route('vouchers.ledger.index') }}?voucher_id={{ $v->id }}" class="px-3 py-1 bg-amber-100 text-[#a38c29] hover:bg-[#a38c29] hover:text-white rounded-lg text-[10px] font-black uppercase transition border border-amber-300">
                                    View Slip
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400 font-bold">
                                No contra transfer vouchers recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ── SCRIPT FOR ALPINE BLUEPRINT ── -->
<script>
function contraVoucherBlueprint() {
    return {
        transactionType: 'bank_to_bank',
        form: {
            date: new Date().toISOString().substring(0, 10),
            credit_account_id: '',
            destination_account_id: '',
            amount: 0.0,
            payment_mode: 'NEFT / RTGS / IMPS',
            reference_no: '',
            narration: '',
            project_id: '{{ count($projects) === 1 ? $projects->first()->id : "" }}',
        },
        fromAccountName: '',
        toAccountName: '',
        fromAccountBalance: 0.0,
        toAccountBalance: 0.0,
        projectName: '{{ count($projects) === 1 ? addslashes($projects->first()->name) : "Global Treasury" }}',
        searchQuery: '',
        fileName: '',
        fileSize: '',

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            } else {
                this.fileName = '';
                this.fileSize = '';
            }
        },

        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            const input = document.querySelector('input[name="attachment"]');
            if (input) input.value = '';
        },

        init() {
            this.$nextTick(() => {
                this.updateAccounts();
                this.updateProjectName();
            });
        },

        onTransactionTypeChange() {
            if (this.transactionType === 'cash_withdrawal') {
                this.form.payment_mode = 'Cash Withdrawal';
            } else if (this.transactionType === 'cash_deposit') {
                this.form.payment_mode = 'Cash Deposit';
            } else {
                this.form.payment_mode = 'NEFT / RTGS / IMPS';
            }
        },

        updateAccounts() {
            const creditSelect = document.getElementById('credit_account_id');
            if (creditSelect && creditSelect.selectedIndex > 0) {
                const opt = creditSelect.options[creditSelect.selectedIndex];
                this.fromAccountName = opt.getAttribute('data-name') || opt.text.split(' (')[0];
                this.fromAccountBalance = parseFloat(opt.getAttribute('data-balance') || 0);
            } else {
                this.fromAccountName = '';
                this.fromAccountBalance = 0.0;
            }

            const destSelect = document.getElementById('destination_account_id');
            if (destSelect && destSelect.selectedIndex > 0) {
                const opt = destSelect.options[destSelect.selectedIndex];
                this.toAccountName = opt.getAttribute('data-name') || opt.text.split(' (')[0];
                this.toAccountBalance = parseFloat(opt.getAttribute('data-balance') || 0);
            } else {
                this.toAccountName = '';
                this.toAccountBalance = 0.0;
            }
        },

        swapAccounts() {
            const temp = this.form.credit_account_id;
            this.form.credit_account_id = this.form.destination_account_id;
            this.form.destination_account_id = temp;
            this.$nextTick(() => {
                this.updateAccounts();
            });
        },

        updateProjectName() {
            const select = document.querySelector('select[name="project_id"]');
            if (select && select.selectedIndex >= 0) {
                const val = select.value;
                if (!val) {
                    this.projectName = 'Global Treasury';
                } else {
                    this.projectName = select.options[select.selectedIndex].text.split(' (')[0];
                }
            }
        },

        get remainingBalance() {
            return (parseFloat(this.fromAccountBalance) || 0) - (parseFloat(this.form.amount) || 0);
        },

        get usagePercentage() {
            const avail = parseFloat(this.fromAccountBalance) || 0;
            const amt = parseFloat(this.form.amount) || 0;
            if (!avail || avail <= 0) return amt > 0 ? 100 : 0;
            return Math.min(Math.round((amt / avail) * 100), 100);
        },

        get balanceCheckStatus() {
            if (!this.form.amount || this.form.amount <= 0) return true;
            if (this.fromAccountBalance <= 0) return false;
            return this.form.amount <= this.fromAccountBalance;
        },

        formatCurrency(val) {
            return Number(val || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }
}
</script>

</x-erp-layout>
