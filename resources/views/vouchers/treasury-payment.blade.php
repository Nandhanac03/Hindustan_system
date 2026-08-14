<x-erp-layout title="Treasury Disbursement" headerTitle="Treasury Disbursement">

    <div class="max-w-[1000px] mx-auto space-y-6">

        {{-- Flash Notifications --}}
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Header & Action Bar matching Receipt Management UI --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div>
                <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                    <span class="text-slate-300">›</span>
                    <a href="{{ route('receipt-management.index') }}" class="hover:text-slate-600 transition">Receipt Management</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-[#a38c29] font-black">Treasury Disbursement</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 mt-1 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Treasury Disbursement Workspace</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Disburse payments for Supplier Payables, Customer Refunds, or Site Expenses from verified bank balances.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('receipt-management.realization-queue') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm">
                    <span>⏳ REALIZATION QUEUE</span>
                </a>
                <a href="{{ route('receipt-management.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-slate-800">
                    <span>← BACK TO RECEIPTS</span>
                </a>
            </div>
        </div>

        {{-- Live Balance Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($companyBankAccounts as $acc)
                <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 {{ $acc->is_default ? 'border-l-[#a38c29]' : 'border-l-slate-300' }} shadow-xs hover:shadow-md transition-all cursor-pointer"
                     onclick="document.getElementById('company_bank_account_id').value='{{ $acc->id }}'; document.querySelectorAll('.bank-card').forEach(el => el.classList.remove('ring-2','ring-[#a38c29]')); this.classList.add('ring-2','ring-[#a38c29]');"
                     id="bank-card-{{ $acc->id }}" class="bank-card">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">{{ $acc->bank_name }}</div>
                    @if($acc->account_number)
                        <div class="text-[10px] text-slate-400 font-mono">Acc: {{ $acc->account_number }}</div>
                    @endif
                    <div class="text-xl font-black text-slate-900 mt-2">₹{{ number_format($acc->current_balance, 2) }}</div>
                    <div class="text-[10px] text-[#a38c29] font-bold mt-0.5 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29] inline-block"></span>
                        Available Treasury Balance
                        @if($acc->is_default) <span class="ml-1 bg-[#a38c29]/15 text-[#a38c29] px-1.5 rounded text-[9px] font-black">DEFAULT</span> @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Disbursement Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 bg-[#a38c29] text-white flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black uppercase tracking-wider text-white">Outward Treasury Payment Details</h2>
                    <p class="text-xs text-white/80 font-medium mt-0.5">Fills payment attributes &amp; generates official Payment Voucher (Step 5.6)</p>
                </div>
                <span class="px-3 py-1 rounded-xl bg-white/20 text-white text-xs font-black uppercase tracking-wider border border-white/30">
                    Step 5.5 - 5.6
                </span>
            </div>

            <form action="{{ route('vouchers.treasury-payment') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 space-y-1">
                        @foreach($errors->all() as $e)
                            <p class="text-xs text-rose-700 font-bold flex items-center gap-1.5">⚠ {{ $e }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Company Bank Account --}}
                    <div class="sm:col-span-2">
                        <label for="company_bank_account_id" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">
                            Debit From Bank Account <span class="text-rose-500">*</span>
                        </label>
                        <select id="company_bank_account_id" name="company_bank_account_id" required
                                class="w-full px-4 py-3 bg-white border-2 border-[#a38c29]/40 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">— Select Bank Account —</option>
                            @foreach($companyBankAccounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('company_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->bank_name }}{{ $acc->account_number ? ' ('.$acc->account_number.')' : '' }} — Balance: ₹{{ number_format($acc->current_balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment Purpose --}}
                    <div>
                        <label for="purpose" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">
                            Payment Purpose <span class="text-rose-500">*</span>
                        </label>
                        <select id="purpose" name="purpose" required
                                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">— Select Purpose —</option>
                            @foreach($purposes as $key => $label)
                                <option value="{{ $key }}" {{ old('purpose') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payee --}}
                    <div>
                        <label for="payee_id" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Payee</label>
                        <select id="payee_id" name="payee_id"
                                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">— Select Payee (Optional) —</option>
                            @foreach($payees as $payee)
                                <option value="{{ $payee->id }}" {{ old('payee_id') == $payee->id ? 'selected' : '' }}>
                                    {{ $payee->name }} {{ $payee->type ? '('.$payee->type.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payee Name (manual) --}}
                    <div>
                        <label for="payee_name" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Payee Name (Manual)</label>
                        <input type="text" id="payee_name" name="payee_name" value="{{ old('payee_name') }}"
                               placeholder="Or enter payee name manually..."
                               class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] placeholder-slate-300">
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label for="amount" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">
                            Amount (₹) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-black">₹</span>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required
                                   placeholder="0.00"
                                   class="w-full pl-8 pr-4 py-3 bg-white border-2 border-slate-300 rounded-xl text-sm font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] placeholder-slate-300">
                        </div>
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label for="payment_date" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">
                            Payment Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                    </div>

                    {{-- Payment Mode --}}
                    <div>
                        <label for="payment_mode" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">
                            Payment Mode <span class="text-rose-500">*</span>
                        </label>
                        <select id="payment_mode" name="payment_mode" required
                                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">— Select Mode —</option>
                            @foreach($paymentModes as $pm)
                                <option value="{{ $pm->code }}" {{ old('payment_mode') == $pm->code ? 'selected' : '' }}>{{ $pm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Reference No --}}
                    <div>
                        <label for="reference_no" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Reference / Cheque No.</label>
                        <input type="text" id="reference_no" name="reference_no" value="{{ old('reference_no') }}"
                               placeholder="UTR / Cheque No. / Transaction ID..."
                               class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] placeholder-slate-300">
                    </div>

                    {{-- Narration --}}
                    <div class="sm:col-span-2">
                        <label for="narration" class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Narration / Description</label>
                        <textarea id="narration" name="narration" rows="2" placeholder="Payment description for voucher..."
                                  class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] placeholder-slate-300 resize-none">{{ old('narration') }}</textarea>
                    </div>
                </div>

                {{-- Warning Box --}}
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="text-xs font-black text-amber-900 uppercase tracking-wider">⚠ Important: Balance Deduction Notice</p>
                        <p class="text-xs text-amber-800 mt-0.5 font-semibold">This action will <strong>immediately deduct</strong> the specified amount from the selected bank account treasury balance and generate an official Payment Voucher. This cannot be undone without a manual reversal entry.</p>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-4 pt-2 border-t border-slate-100">
                    <a href="{{ route('receipt-management.index') }}"
                       class="px-6 py-3 text-xs font-black uppercase tracking-wider text-slate-600 hover:text-slate-900 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            onclick="return confirm('Confirm payment disbursement?\n\nThis will deduct the amount from treasury balance and generate a Payment Voucher.')"
                            class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-black uppercase tracking-wider transition shadow-md hover:shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>PROCESS PAYMENT &amp; GENERATE VOUCHER</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-erp-layout>
