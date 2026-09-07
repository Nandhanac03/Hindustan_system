@extends('layouts.erp')

@section('title', 'Record New Site Expense')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6 bg-slate-100 min-h-screen text-slate-800">

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800">
            <h4 class="font-bold text-xs uppercase tracking-wider mb-1">Please correct the following errors:</h4>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-[#a38c29]/15 text-[#8d7923] text-xs font-bold uppercase rounded-full border border-[#a38c29]/30">
                    Non-Contractor Direct Cost Form
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-7 h-7 text-[#a38c29]"></i> Add New Site Expense
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Record high-value operational payment (Legal, Rental, Power/Diesel, Municipal) via Bank Transfer or Loan Account
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('site-expenses.workflow') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                <i data-lucide="git-merge" class="w-4 h-4 inline mr-1"></i> Interactive Workflow
            </a>
            <a href="{{ route('site-expenses.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </div>

    {{-- Create Form Container --}}
    <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              payeeType: '{{ old('payee_type', 'registered') }}', 
              paymentSource: '{{ old('payment_source_type', 'bank') }}',
              gross: {{ old('gross_amount', 0) }},
              cgst: {{ old('cgst_amount', 0) }},
              sgst: {{ old('sgst_amount', 0) }},
              igst: {{ old('igst_amount', 0) }},
              get net() { return (parseFloat(this.gross)||0) + (parseFloat(this.cgst)||0) + (parseFloat(this.sgst)||0) + (parseFloat(this.igst)||0); } 
          }" 
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf

        {{-- Section 3.A Header & Association Details --}}
        <div class="space-y-4">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 border-b border-slate-200 pb-2 flex items-center gap-2">
                <i data-lucide="building" class="w-4 h-4 text-blue-600"></i> A. Header & Association Details
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Project Name <span class="text-rose-500">*</span></label>
                    <select name="project_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]" required>
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ old('project_id', $selectedProjectId) == $proj->id ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tower / Block Tag (Optional)</label>
                    <input type="text" name="tower_block_tag" value="{{ old('tower_block_tag') }}" placeholder="e.g. Tower A / Overall Project" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Voucher Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="voucher_date" value="{{ old('voucher_date', date('Y-m-d')) }}" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Auto Voucher Number</label>
                    <input type="text" value="{{ $autoVoucherNumber }}" readonly class="w-full text-xs rounded-xl border-slate-200 bg-slate-100 text-slate-600 font-mono font-bold py-2.5 px-3">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Floor Tag (Optional)</label>
                    <select name="floor_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                        <option value="">-- Overall Project / No Specific Floor --</option>
                        @foreach($floors as $fl)
                            <option value="{{ $fl->id }}" {{ old('floor_id') == $fl->id ? 'selected' : '' }}>
                                Floor #{{ $fl->floor_number }} ({{ $fl->project?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Section 3.B Payee / Vendor Information --}}
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 border-b border-slate-200 pb-2 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i> B. Payee / Vendor Information (Hybrid Model)
            </h3>

            <div class="flex items-center gap-6 bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs">
                <span class="font-bold text-slate-700">Payee Type Toggle:</span>
                <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                    <input type="radio" name="payee_type" value="registered" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                    <span>(•) Registered Vendor / Master</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                    <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                    <span>( ) One-Time / Casual Payee</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <template x-if="payeeType === 'registered'">
                    <div class="md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Select Registered Vendor <span class="text-rose-500">*</span></label>
                        <select name="payee_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]">
                            <option value="">-- Select from Vendor Master Directory --</option>
                            @foreach($payees as $payee)
                                <option value="{{ $payee->id }}" {{ old('payee_id') == $payee->id ? 'selected' : '' }}>
                                    {{ $payee->name }} ({{ $payee->category ?? 'Vendor Master' }})
                                </option>
                            @endforeach
                            @if($payees->isEmpty())
                                <option value="1">Kerala Earthmovers (Heavy Machinery Rental)</option>
                                <option value="2">District Land Registrar (Government Legal Fee)</option>
                            @endif
                        </select>
                    </div>
                </template>

                <template x-if="payeeType === 'one_time'">
                    <div class="md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">One-Time / Casual Payee Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="casual_payee_name" value="{{ old('casual_payee_name') }}" placeholder="e.g. Saju Auto Driver, Local Hardware, Emergency Repair" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                    </div>
                </template>
            </div>
        </div>

        {{-- Section 3.C Expense Classification & Financial Details --}}
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 border-b border-slate-200 pb-2 flex items-center gap-2">
                <i data-lucide="tags" class="w-4 h-4 text-blue-600"></i> C. Expense Classification & Financial Details (4000-Series)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 mb-1">Expense Category (Chart of Accounts Mapping) <span class="text-rose-500">*</span></label>
                    <select name="expense_category_code" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]" required>
                        <option value="">-- Select Expense Category --</option>
                        @foreach($expenseCategories as $code => $name)
                            <option value="{{ $code }}" {{ old('expense_category_code') == $code ? 'selected' : '' }}>
                                {{ $code }} - {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Gross Amount (₹) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="gross_amount" x-model.number="gross" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono" required>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">CGST (₹)</label>
                    <input type="number" step="0.01" name="cgst_amount" x-model.number="cgst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">SGST (₹)</label>
                    <input type="number" step="0.01" name="sgst_amount" x-model.number="sgst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">IGST (₹)</label>
                    <input type="number" step="0.01" name="igst_amount" x-model.number="igst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono">
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 flex items-center justify-between">
                <div>
                    <h4 class="font-extrabold text-blue-900 text-sm">Calculated Net Amount Paid:</h4>
                    <p class="text-xs text-blue-700">Includes Gross Amount + Input Tax Credits</p>
                </div>
                <input type="hidden" name="net_amount" :value="net">
                <span class="text-2xl font-black text-blue-950 font-mono" x-text="'₹ ' + net.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹ 0.00</span>
            </div>
        </div>

        {{-- Section 3.D Payment Source --}}
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 border-b border-slate-200 pb-2 flex items-center gap-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-blue-600"></i> D. Payment Source (Bank / Loan Only)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Source Account Type <span class="text-rose-500">*</span></label>
                    <select name="payment_source_type" x-model="paymentSource" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]" required>
                        <option value="bank">Corporate Bank Account</option>
                        <option value="loan">Bank Loan Account</option>
                    </select>
                </div>

                <template x-if="paymentSource === 'bank'">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Corporate Bank Account <span class="text-rose-500">*</span></label>
                        <select name="company_bank_account_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]">
                            <option value="">-- Select Bank Account --</option>
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}" {{ old('company_bank_account_id') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} - {{ $bank->account_number }} (Bal: ₹ {{ number_format($bank->current_balance ?? 0, 2) }})
                                </option>
                            @endforeach
                            @if($bankAccounts->isEmpty())
                                <option value="1">Karnataka Bank - A/c 1001 (Operational)</option>
                                <option value="2">HDFC Bank - A/c 1002 (Escrow / Collections)</option>
                            @endif
                        </select>
                    </div>
                </template>

                <template x-if="paymentSource === 'loan'">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Loan Liability Account <span class="text-rose-500">*</span></label>
                        <select name="loan_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-[#a38c29]">
                            <option value="">-- Select Bank Loan Account --</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->id }}" {{ old('loan_id') == $loan->id ? 'selected' : '' }}>
                                    {{ $loan->lender_name }} - {{ $loan->account_number }} (Outstanding: ₹ {{ number_format($loan->outstanding_balance ?? 0, 2) }})
                                </option>
                            @endforeach
                            @if($loans->isEmpty())
                                <option value="1">SBI Land Loan - A/c 2010 (Loan Disbursal Account)</option>
                            @endif
                        </select>
                    </div>
                </template>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Transaction Ref / UTR / Cheque No. <span class="text-rose-500">*</span></label>
                    <input type="text" name="transaction_reference_no" value="{{ old('transaction_reference_no') }}" placeholder="e.g. Bank UTR / Cheque Ref" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3" required>
                </div>
            </div>
        </div>

        {{-- Section 3.E Document Attachment & Narration --}}
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-900 border-b border-slate-200 pb-2 flex items-center gap-2">
                <i data-lucide="paperclip" class="w-4 h-4 text-blue-600"></i> E. Document Attachment & Narration
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bill / Receipt Upload (PDF / Photo)</label>
                    <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3">
                    <p class="text-[10px] text-slate-400 mt-1">Automatically stored in the Vendor & Operations DMS folder.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Voucher Narration / Remarks</label>
                    <textarea name="narration" rows="2" placeholder="e.g. Payment for JCB rental machine operations for site excavation..." class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3">{{ old('narration') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
            <button type="submit" name="submit_action" value="draft" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                Save as Draft
            </button>
            <button type="submit" name="submit_action" value="submit" class="px-6 py-2.5 rounded-xl bg-[#a38c29] text-white text-xs font-bold hover:bg-[#8d7923] shadow-md transition">
                Submit & Post Expense
            </button>
        </div>

    </form>

</div>
@endsection
