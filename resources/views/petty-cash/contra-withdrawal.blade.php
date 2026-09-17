@extends('layouts.erp')

@section('title', 'Bank Cash Withdrawal (Contra) - Hindustan Real Estate ERP')

@section('content')
<div class="max-w-[1800px] mx-auto p-6 space-y-6 font-sans" x-data="contraForm()">

    <!-- ── BREADCRUMB & TOP ACTION HEADER (Clean Standard) ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">HOME</a>
            <span class="text-slate-300">›</span>
            <a href="{{ route('petty-cash.balance-register') }}" class="hover:text-slate-600 transition">PETTY CASH &amp; SITE EXPENSE</a>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-black">BANK CASH WITHDRAWAL (CONTRA)</span>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('petty-cash.balance-register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-4 py-2.5 text-xs font-extrabold text-white shadow-md transition-all duration-200 uppercase tracking-wider cursor-pointer active:scale-95">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Petty Cash Balance Register</span>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: Form -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Card Header (Theme Gold) -->
                <div class="px-6 py-4 bg-[#a38c29] text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black uppercase tracking-wider text-white">Contra Withdrawal Entry</h2>
                            <p class="text-[11px] text-amber-100 font-medium mt-0.5">Disburse cash from company bank account into site petty cash box</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-wider border border-white/30">
                        Cash Transfer
                    </span>
                </div>
                
                <form action="{{ route('petty-cash.store-contra-withdrawal') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                    @csrf
                    
                    @if(session('error'))
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-700 text-xs font-bold">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-700 text-xs font-bold">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Row 1: Basic Info (3 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <!-- Voucher No -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Voucher No.
                            </label>
                            <input type="text" name="voucher_number" value="{{ $nextVoucherNo }}" readonly 
                                   class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-700 cursor-not-allowed">
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition">
                        </div>

                        <!-- Site -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Site <span class="text-rose-500">*</span>
                            </label>
                            <select name="project_id" required 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Accounts (2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Disburse From Bank Account -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Disburse From Bank Account <span class="text-rose-500">*</span>
                            </label>
                            <select name="bank_account_id" x-model="selectedBank" required 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">— Select Bank Account —</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1.5 space-y-0.5" x-show="selectedBank">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500 font-medium">Available Balance:</span>
                                    <span class="font-mono font-bold text-[#a38c29]" x-text="formatCurrency(selectedBankBalance)"></span>
                                </div>
                                <div x-show="selectedBankBalanceInWords" 
                                     class="text-[10.5px] text-[#8a7522] italic font-semibold text-right leading-tight" 
                                     x-text="selectedBankBalanceInWords"></div>
                            </div>
                        </div>

                        <!-- Receiving Cash Box -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Receiving Cash Box <span class="text-rose-500">*</span>
                            </label>
                            <select name="cash_box_id" required 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer">
                                @foreach($cashBoxes as $box)
                                    <option value="{{ $box->id }}">{{ $box->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1.5 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500 font-medium">Current Cash in Hand:</span>
                                <span class="font-mono font-bold text-emerald-600" x-text="formatCurrency(pettyCashBefore)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Amount & Reference (2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Amount -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Amount (₹) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                                <input type="number" name="amount" x-model="amount" step="0.01" min="0.01" required 
                                       placeholder="0.00"
                                       class="w-full pl-8 pr-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition">
                            </div>
                            
                            <!-- Amount in Words -->
                            <div x-show="amountInWordsText" class="mt-1 text-[11px] text-[#a38c29] italic font-semibold" x-text="amountInWordsText"></div>

                            <!-- Live Insufficient Warning -->
                            <template x-if="isBankInsufficient">
                                <div class="mt-1.5 text-[11px] text-rose-600 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>Amount exceeds available bank balance (<span x-text="formatCurrency(selectedBankBalance)"></span>)</span>
                                </div>
                            </template>
                        </div>

                        <!-- Reference No & Reference Date (2 Sub-Columns) -->
                        <div class="grid grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                    Reference / Cheque No. <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="reference_no" value="{{ old('reference_no') }}" required placeholder="CKB123456 or Chq #000123" 
                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                    Reference Date
                                </label>
                                <input type="date" name="reference_date" 
                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition">
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Narration & Attachments in a Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Narration -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Narration / Purpose <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="narration" rows="3" required 
                                      placeholder="E.g. Cash withdrawn from Karnataka Bank for site petty cash requirements..." 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] placeholder-slate-400 transition resize-none"></textarea>
                        </div>

                        <!-- Attachments -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Attachments (Receipt / Bank Deposit Slip)
                            </label>
                            
                            <div class="flex items-center gap-3 flex-wrap mt-0.5">
                                <!-- Selected File Pill -->
                                <template x-if="fileName">
                                    <div class="flex items-center bg-amber-50 border border-amber-200 rounded-xl overflow-hidden group">
                                        <div class="px-3.5 py-2 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span class="text-xs font-bold text-slate-700 truncate max-w-[150px]" x-text="fileName"></span>
                                            <span class="text-[11px] font-semibold text-slate-400" x-text="'(' + fileSize + ')'"></span>
                                        </div>
                                        <button type="button" @click="removeFile" class="px-3 py-2 bg-amber-100/60 text-[#8a7522] hover:bg-rose-50 hover:text-rose-600 text-xs font-bold transition cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Add Attachment Button -->
                                <template x-if="!fileName">
                                    <label class="inline-flex items-center gap-2 px-4 py-2.5 border border-dashed border-slate-300 bg-slate-50 hover:bg-white hover:border-[#a38c29] rounded-xl cursor-pointer transition">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span class="text-xs font-semibold text-slate-600">Choose File</span>
                                        <input type="file" name="attachment" x-ref="fileInput" @change="handleFileChange" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                                    </label>
                                </template>
                            </div>
                            <span class="text-[11px] text-slate-400 mt-1.5 block">Supported formats: JPG, PNG, PDF (Max 2MB)</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('petty-cash.balance-register') }}" 
                           class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                            Cancel
                        </a>
                        
                        <button type="submit"
                                :disabled="isBankInsufficient"
                                :class="isBankInsufficient ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-[#a38c29] hover:bg-[#8a7522] cursor-pointer shadow-sm active:scale-95'"
                                class="inline-flex items-center gap-2 px-7 py-2.5 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save &amp; Post</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Sidebar Panels -->
        <div class="xl:col-span-1 flex flex-col gap-6">
            
            <!-- Withdrawal Details -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-[#a38c29] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <h2 class="text-xs font-black text-white uppercase tracking-wider">WITHDRAWAL DETAILS</h2>
                    </div>
                    <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Live Analysis</span>
                </div>
                <div class="p-5">
                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-3 font-semibold text-slate-600">Bank Balance (As on {{ date('d-M-Y') }})</td>
                                <td class="py-3 text-right font-mono font-bold text-slate-900" x-text="formatCurrency(selectedBankBalance)">₹ 0.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold text-slate-600">Bank Balance (After Payout)</td>
                                <td class="py-3 text-right font-mono font-bold" :class="bankBalanceAfterPayout < 0 ? 'text-rose-600 font-black' : 'text-slate-900'" x-text="formatCurrency(bankBalanceAfterPayout)">₹ 0.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold text-slate-600">Petty Cash Balance (Before)</td>
                                <td class="py-3 text-right font-mono font-bold text-slate-900" x-text="formatCurrency(pettyCashBefore)">₹ 0.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold text-slate-600">Withdrawal Amount</td>
                                <td class="py-3 text-right font-mono font-bold text-[#a38c29]" x-text="formatCurrency(parsedAmount)">₹ 0.00</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Petty Cash Balance After Highlight Box (Gold Accent) -->
                    <div class="mt-4 p-4 bg-gradient-to-r from-[#FAF0D7] to-[#F6F3E9] border border-[#EAE3CD] rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-wider text-[#8a7522]">PETTY CASH BALANCE (AFTER)</div>
                            <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Updated cash in hand</div>
                        </div>
                        <div class="text-lg font-mono font-black text-[#8a7522]" x-text="formatCurrency(pettyCashAfter)">₹ 0.00</div>
                    </div>
                </div>
            </div>

            <!-- Contra History (Recent) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-[#a38c29] text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h2 class="text-xs font-black text-white uppercase tracking-wider">CONTRA HISTORY (RECENT)</h2>
                    </div>
                    <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Recent 5</span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-[#FAF0D7]/30 border-b border-[#EAE3CD]">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-black text-[#8a7522] uppercase tracking-wider">Voucher No.</th>
                                <th class="px-4 py-3 text-[10px] font-black text-[#8a7522] uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-[10px] font-black text-[#8a7522] uppercase tracking-wider text-right">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentContras as $contra)
                            <tr class="hover:bg-[#FAF0D7]/20 transition-colors">
                                <td class="px-4 py-3 text-xs font-mono font-bold text-slate-800">{{ $contra->voucher_number }}</td>
                                <td class="px-4 py-3 text-xs font-semibold text-slate-600">{{ \Carbon\Carbon::parse($contra->date)->format('d-M-Y') }}</td>
                                <td class="px-4 py-3 text-xs font-mono font-black text-slate-900 text-right">₹ {{ number_format($contra->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-xs text-slate-400 italic">No recent contra records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3.5 border-t border-slate-100 text-center bg-slate-50/70">
                    <a href="{{ route('petty-cash.balance-register') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#a38c29] hover:text-[#8a7522] transition-colors">
                        <span>View All Contra Entries in Register</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- AlpineJS Logic for Dynamic Calculations -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contraForm', () => ({
            amount: '',
            pettyCashBefore: {{ $pettyCashBalance ?? 0 }},
            selectedBank: '',
            bankBalances: {
                @foreach($bankAccounts as $bank)
                '{{ $bank->id }}': {{ $bank->balance }},
                @endforeach
            },
            fileName: null,
            fileSize: null,
            
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    let size = (file.size / 1024).toFixed(0);
                    if (size > 1024) {
                        this.fileSize = (size / 1024).toFixed(2) + ' MB';
                    } else {
                        this.fileSize = size + ' KB';
                    }
                }
            },
            
            removeFile() {
                this.fileName = null;
                this.fileSize = null;
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },
            
            get parsedAmount() {
                return parseFloat(this.amount) || 0;
            },

            get selectedBankBalance() {
                return this.selectedBank ? (parseFloat(this.bankBalances[this.selectedBank]) || 0) : 0;
            },
            
            get bankBalanceAfterPayout() {
                return this.selectedBankBalance - this.parsedAmount;
            },

            get pettyCashAfter() {
                return this.pettyCashBefore + this.parsedAmount;
            },
            
            get isBankInsufficient() {
                return Boolean(this.selectedBank && this.parsedAmount > 0 && this.parsedAmount > this.selectedBankBalance);
            },

            numberToWords(val) {
                let num = Math.floor(parseFloat(val) || 0);
                if (!num || num <= 0) return '';
                const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                function toWords(n) {
                    if (n < 20) return a[n];
                    let digit = n % 10;
                    return b[Math.floor(n / 10)] + (digit ? ' ' + a[digit] : '');
                }
                let str = '';
                let crore = Math.floor(num / 10000000);
                num %= 10000000;
                let lakh = Math.floor(num / 100000);
                num %= 100000;
                let thousand = Math.floor(num / 1000);
                num %= 1000;
                let hundred = Math.floor(num / 100);
                let rest = num % 100;
                if (crore > 0) str += toWords(crore) + ' Crore ';
                if (lakh > 0) str += toWords(lakh) + ' Lakh ';
                if (thousand > 0) str += toWords(thousand) + ' Thousand ';
                if (hundred > 0) str += toWords(hundred) + ' Hundred ';
                if (rest > 0) str += (str !== '' ? 'and ' : '') + toWords(rest) + ' ';
                return str.trim() + ' Rupees Only';
            },

            get selectedBankBalanceInWords() {
                return this.numberToWords(this.selectedBankBalance);
            },

            get amountInWordsText() {
                return this.numberToWords(this.parsedAmount);
            },

            formatCurrency(value) {
                return '₹ ' + new Intl.NumberFormat('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value || 0);
            }
        }))
    })
</script>
@endsection
