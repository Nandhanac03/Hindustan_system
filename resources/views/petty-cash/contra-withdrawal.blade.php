@extends('layouts.erp')

@section('title', 'Bank Cash Withdrawal (Contra) - Hindustan Real Estate ERP')

@section('content')
<div class="max-w-[1800px] mx-auto space-y-6 font-sans" x-data="contraForm()">

    <!-- ── TOP BREADCRUMB & HEADER BAR (Pure Gold Theme) ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-[#EAE3CD]">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <a href="{{ route('petty-cash.balance-register') }}" class="hover:text-slate-600 transition">PETTY CASH &amp; SITE EXPENSE</a>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">BANK CASH WITHDRAWAL (CONTRA)</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-[#FAF0D7] text-[#a38c29] border border-[#EAE3CD] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <span>Bank Cash Withdrawal (Contra)</span>
                <span class="text-xs bg-[#FAF0D7] text-[#a38c29] border border-[#EAE3CD] px-2.5 py-0.5 rounded-full font-bold">Cash Transfer</span>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('petty-cash.balance-register') }}" class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-[#a38c29]/25 hover:shadow-lg">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Petty Cash Balance Register</span>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: Form -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden">
                
                <!-- Card Header (Rich Gold Theme) -->
                <div class="relative overflow-hidden bg-gradient-to-r from-[#a38c29] via-[#b89e34] to-[#8a7520] px-6 py-5 border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white border border-white/30 text-[9px] font-black uppercase tracking-widest whitespace-nowrap shadow-2xs">Cash Transfer</span>
                            <span class="text-[10px] text-amber-100 font-bold uppercase tracking-wider hidden sm:inline">Treasury Outflow &rarr; Petty Cash Box</span>
                        </div>
                        <h2 class="text-base font-black text-white uppercase tracking-wider mt-1.5 flex items-center gap-2.5 drop-shadow-xs">
                            <svg class="w-5 h-5 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>Contra Withdrawal Entry</span>
                        </h2>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 relative z-10">
                        <span class="w-10 h-10 rounded-xl bg-white/15 text-white border border-white/25 flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                    </div>
                </div>
                
                <form action="{{ route('petty-cash.store-contra-withdrawal') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    @if(session('error'))
                        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3 text-rose-700 text-xs font-bold shadow-2xs">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-700 text-xs font-bold shadow-2xs">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-6">
                        <!-- Voucher No -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">VOUCHER NO.</label>
                            <input type="text" name="voucher_number" value="{{ $nextVoucherNo }}" readonly 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-700 outline-none cursor-not-allowed shadow-2xs">
                        </div>
                        
                        <!-- Date & Site Row -->
                        <div class="grid grid-cols-2 gap-3.5">
                            <!-- Date -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">DATE <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs">
                            </div>
                            <!-- Site -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">SITE <span class="text-rose-500">*</span></label>
                                <select name="project_id" required 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs cursor-pointer">
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Bank Account -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">DISBURSE FROM BANK ACCOUNT <span class="text-rose-500">*</span></label>
                            <select name="bank_account_id" x-model="selectedBank" required 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs cursor-pointer">
                                <option value="">Select Bank Account</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1.5 flex items-center justify-between text-[10px]" x-show="selectedBank">
                                <span class="text-slate-500 font-bold uppercase tracking-wider">Available Balance:</span>
                                <span class="font-mono font-bold text-[#8a7522] bg-[#FAF0D7] border border-[#EAE3CD] px-2 py-0.5 rounded-md" x-text="formatCurrency(selectedBankBalance)"></span>
                            </div>
                        </div>

                        <!-- Cash Box -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">RECEIVING CASH BOX <span class="text-rose-500">*</span></label>
                            <select name="cash_box_id" required 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs cursor-pointer">
                                @foreach($cashBoxes as $box)
                                    <option value="{{ $box->id }}">{{ $box->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1.5 flex items-center justify-between text-[10px]">
                                <span class="text-slate-500 font-bold uppercase tracking-wider">Current Cash In Hand:</span>
                                <span class="font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md" x-text="formatCurrency(pettyCashBefore)"></span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">AMOUNT (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" name="amount" x-model="amount" step="0.01" min="0.01" required 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-mono font-black text-slate-900 outline-none transition-all shadow-2xs" 
                                   placeholder="0.00">
                            
                            <!-- Amount In Words Box (Gold Theme) -->
                            <div x-show="amountInWordsText" class="mt-2 p-2.5 bg-[#FAF0D7]/70 border border-[#EAE3CD] rounded-xl text-[11px] font-bold text-[#8a7522] shadow-2xs flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="amountInWordsText"></span>
                            </div>

                            <!-- Insufficient Bank Balance Live Warning -->
                            <template x-if="isBankInsufficient">
                                <div class="mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2 text-rose-700 text-[11px] font-bold shadow-2xs">
                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Insufficient Bank Balance! Amount (<span x-text="formatCurrency(parsedAmount)"></span>) exceeds available bank balance (<span x-text="formatCurrency(selectedBankBalance)"></span>).</span>
                                </div>
                            </template>
                        </div>

                        <!-- Reference / Cheque No -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">REFERENCE / CHEQUE NO.</label>
                            <input type="text" name="reference_no" placeholder="CKB123456 or Chq #000123" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs">
                        </div>

                        <!-- Reference Date -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">REFERENCE DATE</label>
                            <input type="date" name="reference_date" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 outline-none transition-all shadow-2xs">
                        </div>

                        <!-- Narration -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">NARRATION <span class="text-rose-500">*</span></label>
                            <textarea name="narration" rows="2" required 
                                      class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-medium text-slate-800 outline-none transition-all placeholder-slate-400 shadow-2xs" 
                                      placeholder="E.g. Cash withdrawn from Karnataka Bank for site petty cash requirements..."></textarea>
                        </div>
                    </div>
                    
                    <hr class="border-slate-200 my-6">

                    <!-- Attachments -->
                    <div class="mb-8">
                        <label class="block text-[10px] font-black text-[#a38c29] uppercase tracking-wider mb-2.5">ATTACHMENTS (RECEIPT / BANK DEPOSIT SLIP)</label>
                        
                        <div class="flex items-center gap-3 flex-wrap">
                            <!-- Selected File Pill -->
                            <template x-if="fileName">
                                <div class="flex items-center bg-[#FAF0D7]/40 border border-[#EAE3CD] rounded-xl overflow-hidden group transition-all hover:border-[#a38c29]">
                                    <div class="px-3.5 py-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span class="text-xs font-bold text-slate-700" x-text="fileName"></span>
                                        <span class="text-[11px] font-semibold text-slate-400" x-text="'(' + fileSize + ')'"></span>
                                    </div>
                                    <button type="button" @click="removeFile" class="px-3 py-2 bg-[#FAF0D7] text-[#8a7522] hover:bg-rose-50 hover:text-rose-600 text-xs font-bold transition-colors">
                                        Remove
                                    </button>
                                </div>
                            </template>
                            
                            <!-- Add Attachment Button -->
                            <template x-if="!fileName">
                                <label class="flex items-center gap-2 px-4 py-2.5 border border-dashed border-[#EAE3CD] bg-[#FAF0D7]/20 rounded-xl cursor-pointer hover:border-[#a38c29] hover:bg-[#FAF0D7]/50 transition-all">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="text-xs font-bold text-slate-700">Add File</span>
                                    <input type="file" name="attachment" x-ref="fileInput" @change="handleFileChange" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                                </label>
                            </template>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 font-medium">Supported: JPG, PNG, PDF up to 2MB</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-5 border-t border-slate-100">
                        <a href="{{ route('petty-cash.balance-register') }}" 
                           class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase tracking-wider rounded-xl transition-all shadow-xs">
                            Cancel
                        </a>
                        
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    :disabled="isBankInsufficient"
                                    :class="isBankInsufficient ? 'opacity-50 cursor-not-allowed bg-slate-400 hover:bg-slate-400' : 'bg-gradient-to-r from-[#a38c29] to-[#8f7a22] hover:from-[#8f7a22] hover:to-[#7b681c] cursor-pointer shadow-md shadow-[#a38c29]/25 hover:shadow-lg'"
                                    class="px-8 py-2.5 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all">
                                Save &amp; Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Sidebar Panels -->
        <div class="xl:col-span-1 flex flex-col gap-6">
            
            <!-- Withdrawal Details (Rich Gold Theme) -->
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-gradient-to-r from-[#a38c29] via-[#b89e34] to-[#8a7520] border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <h2 class="text-[11px] font-black text-white uppercase tracking-widest">WITHDRAWAL DETAILS</h2>
                    </div>
                    <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Live Analysis</span>
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

            <!-- Contra History (Recent) (Rich Gold Theme) -->
            <div class="bg-white rounded-2xl border border-[#EAE3CD] shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-gradient-to-r from-[#a38c29] via-[#b89e34] to-[#8a7520] border-b border-[#7c691c] text-white flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h2 class="text-[11px] font-black text-white uppercase tracking-widest">CONTRA HISTORY (RECENT)</h2>
                    </div>
                    <span class="text-[9px] font-black text-white bg-white/20 border border-white/30 px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-2xs">Recent 5</span>
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

            get amountInWordsText() {
                let num = Math.floor(this.parsedAmount);
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
