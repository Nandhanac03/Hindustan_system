@extends('layouts.erp')

@section('title', 'New Site Expense - 4-Step Wizard')

@section('content')
<div x-data="{ 
    currentStep: 1,
    payeeType: '{{ old('payee_type', 'registered') }}', 
    payeeId: '{{ old('payee_id', $payees->first()?->id ?? '') }}',
    payeesData: {{ json_encode($payees->keyBy('id')) }},
    casualPayeeName: '{{ old('casual_payee_name', '') }}',
    vendorGstin: '32AABCKE1234F1Z5',
    vendorPhone: '9895 432 100',
    vendorEmail: 'info@keralaearthmovers.com',
    voucherNo: '{{ $autoVoucherNumber }}',
    voucherDate: '{{ old('voucher_date', date('Y-m-d')) }}',
    billInvoiceNo: '{{ old('transaction_reference_no', 'JCB/0525/01148') }}',
    billDate: '{{ date('Y-m-d') }}',
    expenseCategoryCode: '{{ old('expense_category_code', '4020') }}',
    grossAmount: {{ old('gross_amount', 45000) }},
    cgstPct: 9,
    sgstPct: 9,
    igstPct: 0,
    paymentSourceType: '{{ old('payment_source_type', 'bank') }}',
    bankAccountId: '{{ old('company_bank_account_id', $bankAccounts->first()?->id ?? '') }}',
    loanId: '{{ old('loan_id', $loans->first()?->id ?? '') }}',
    paymentMode: 'Bank Transfer',
    transactionRef: 'UTR123456789',
    paymentDate: '{{ date('Y-m-d') }}',
    narration: '{{ old('narration', 'JCB rental for excavation work – Block A') }}',
    selectedProjectName: 'Skyline Heights',
    towerBlockTag: 'Tower A',
    
    init() {
        this.onPayeeChange();
    },

    get selectedPayeeName() {
        if (this.payeeType === 'registered') {
            let p = this.payeesData[this.payeeId];
            return p ? p.name : 'Kerala Earthmovers';
        }
        return this.casualPayeeName || 'Local JCB Owner - Rajesh';
    },

    get cgstAmount() {
        return (this.cgstPct / 100) * (parseFloat(this.grossAmount) || 0);
    },

    get sgstAmount() {
        return (this.sgstPct / 100) * (parseFloat(this.grossAmount) || 0);
    },

    get igstAmount() {
        return (this.igstPct / 100) * (parseFloat(this.grossAmount) || 0);
    },

    get totalAmountPayable() {
        return (parseFloat(this.grossAmount) || 0) + this.cgstAmount + this.sgstAmount + this.igstAmount;
    },

    onPayeeChange() {
        if (this.payeeType === 'registered' && this.payeeId && this.payeesData[this.payeeId]) {
            let p = this.payeesData[this.payeeId];
            this.vendorGstin = p.gstin || '32AABCKE1234F1Z5';
            this.vendorPhone = p.phone || '9895 432 100';
            this.vendorEmail = p.email || 'info@keralaearthmovers.com';
        }
    },

    formatCurrency(val) {
        let n = parseFloat(val) || 0;
        return '₹ ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}" x-init="init()" class="px-4 sm:px-6 lg:px-8 py-6 space-y-6 bg-slate-100 min-h-screen text-slate-800">

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 shadow-sm">
            <h4 class="font-bold text-xs uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i> Please correct the following errors:
            </h4>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                <span>Site Expenses</span>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-slate-800 font-bold">New Site Expense Wizard</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                New Site Expense
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('site-expenses.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                View Register
            </a>
            <a href="{{ route('site-expenses.workflow') }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition">
                Interactive Workflow
            </a>
        </div>
    </div>

    {{-- 4-STEP WIZARD PROGRESS HEADER BAR --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            
            {{-- Step 1 Indicator --}}
            <button type="button" @click="currentStep = 1" 
                    :class="currentStep === 1 ? 'border-blue-600 bg-blue-50/60 shadow-xs' : (currentStep > 1 ? 'border-emerald-300 bg-emerald-50/40' : 'border-slate-200 bg-slate-50')"
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all text-left cursor-pointer">
                <div :class="currentStep === 1 ? 'bg-blue-600 text-white' : (currentStep > 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600')"
                     class="w-7 h-7 rounded-full font-black text-xs flex items-center justify-center shrink-0">
                    <template x-if="currentStep > 1"><i data-lucide="check" class="w-3.5 h-3.5 text-white"></i></template>
                    <template x-if="currentStep <= 1"><span>1</span></template>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-tight">Project & Voucher</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Header details</p>
                </div>
            </button>

            {{-- Step 2 Indicator --}}
            <button type="button" @click="currentStep = 2" 
                    :class="currentStep === 2 ? 'border-blue-600 bg-blue-50/60 shadow-xs' : (currentStep > 2 ? 'border-emerald-300 bg-emerald-50/40' : 'border-slate-200 bg-slate-50')"
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all text-left cursor-pointer">
                <div :class="currentStep === 2 ? 'bg-blue-600 text-white' : (currentStep > 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600')"
                     class="w-7 h-7 rounded-full font-black text-xs flex items-center justify-center shrink-0">
                    <template x-if="currentStep > 2"><i data-lucide="check" class="w-3.5 h-3.5 text-white"></i></template>
                    <template x-if="currentStep <= 2"><span>2</span></template>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-tight">Payee & Category</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Vendor & 4000 COA</p>
                </div>
            </button>

            {{-- Step 3 Indicator --}}
            <button type="button" @click="currentStep = 3" 
                    :class="currentStep === 3 ? 'border-blue-600 bg-blue-50/60 shadow-xs' : (currentStep > 3 ? 'border-emerald-300 bg-emerald-50/40' : 'border-slate-200 bg-slate-50')"
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all text-left cursor-pointer">
                <div :class="currentStep === 3 ? 'bg-blue-600 text-white' : (currentStep > 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600')"
                     class="w-8 h-8 rounded-full font-black text-xs flex items-center justify-center shrink-0">
                    <template x-if="currentStep > 3"><i data-lucide="check" class="w-3.5 h-3.5 text-white"></i></template>
                    <template x-if="currentStep <= 3"><span>3</span></template>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-tight">Amount & Payment</h4>
                    <p class="text-[10px] text-slate-400 font-medium">GST & Bank/Loan</p>
                </div>
            </button>

            {{-- Step 4 Indicator --}}
            <button type="button" @click="currentStep = 4" 
                    :class="currentStep === 4 ? 'border-blue-600 bg-blue-50/60 shadow-xs' : 'border-slate-200 bg-slate-50'"
                    class="flex items-center gap-3 p-3 rounded-xl border transition-all text-left cursor-pointer">
                <div :class="currentStep === 4 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600'"
                     class="w-7 h-7 rounded-full font-black text-xs flex items-center justify-center shrink-0">
                    <span>4</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-tight">Documents & Review</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Upload & Preview</p>
                </div>
            </button>

        </div>
    </div>

    {{-- MAIN FORM CONTAINER --}}
    <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hidden mapping inputs --}}
        <input type="hidden" name="project_id" value="{{ $selectedProjectId }}">
        <input type="hidden" name="voucher_date" :value="voucherDate">
        <input type="hidden" name="payee_type" :value="payeeType">
        <input type="hidden" name="payee_id" :value="payeeType === 'registered' ? payeeId : ''">
        <input type="hidden" name="casual_payee_name" :value="payeeType === 'one_time' ? casualPayeeName : ''">
        <input type="hidden" name="expense_category_code" :value="expenseCategoryCode">
        <input type="hidden" name="gross_amount" :value="grossAmount">
        <input type="hidden" name="cgst_amount" :value="cgstAmount">
        <input type="hidden" name="sgst_amount" :value="sgstAmount">
        <input type="hidden" name="igst_amount" :value="igstAmount">
        <input type="hidden" name="net_amount" :value="totalAmountPayable">
        <input type="hidden" name="payment_source_type" :value="paymentSourceType">
        <input type="hidden" name="company_bank_account_id" :value="paymentSourceType === 'bank' ? bankAccountId : ''">
        <input type="hidden" name="loan_id" :value="paymentSourceType === 'loan' ? loanId : ''">
        <input type="hidden" name="transaction_reference_no" :value="transactionRef || billInvoiceNo">
        <input type="hidden" name="narration" :value="narration">

        {{-- STEP 1: PROJECT & VOUCHER DETAILS --}}
        <div x-show="currentStep === 1" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">1. Project & Voucher Details</h3>
                <span class="text-xs text-slate-400 font-semibold">Step 1 of 4</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Project Name <span class="text-rose-500">*</span></label>
                    <select name="project_id_select" x-model="selectedProjectName" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white" required>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->name }}">
                                {{ $proj->name }}
                            </option>
                        @endforeach
                        @if($projects->isEmpty())
                            <option value="Skyline Heights">Skyline Heights</option>
                            <option value="Green Valley">Green Valley</option>
                            <option value="Ocean View">Ocean View</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Tower / Block (Optional)</label>
                    <input type="text" x-model="towerBlockTag" placeholder="e.g. Tower A" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Voucher Date <span class="text-rose-500">*</span></label>
                    <input type="date" x-model="voucherDate" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Voucher Number</label>
                    <input type="text" x-model="voucherNo" readonly class="w-full text-xs font-mono font-bold rounded-xl border-slate-200 bg-slate-100 text-slate-600 py-2.5 px-3">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Bill / Invoice No.</label>
                    <input type="text" x-model="billInvoiceNo" placeholder="JCB/0525/01148" class="w-full text-xs font-mono font-bold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Bill Date</label>
                    <input type="date" x-model="billDate" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="button" @click="currentStep = 2" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md flex items-center gap-2 cursor-pointer">
                    <span>Next</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- STEP 2: PAYEE & CATEGORY --}}
        <div x-show="currentStep === 2" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">2. Payee / Vendor Information</h3>
                <span class="text-xs text-slate-400 font-semibold">Step 2 of 4</span>
            </div>

            <div class="space-y-4">
                {{-- Payee Toggle Box --}}
                <div class="flex items-center gap-6 bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs">
                    <span class="font-bold text-slate-700">Payee Type:</span>
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                        <input type="radio" value="registered" x-model="payeeType" @change="onPayeeChange()" class="text-blue-600 focus:ring-blue-500">
                        <span>Registered Vendor / Master</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                        <input type="radio" value="one_time" x-model="payeeType" class="text-blue-600 focus:ring-blue-500">
                        <span>One-Time / Casual Payee</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">Search Vendor <span class="text-rose-500">*</span></label>
                        <template x-if="payeeType === 'registered'">
                            <select x-model="payeeId" @change="onPayeeChange()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white text-slate-800">
                                <option value="">-- Select Vendor --</option>
                                @foreach($payees as $payee)
                                    <option value="{{ $payee->id }}">
                                        {{ $payee->name }}
                                    </option>
                                @endforeach
                                @if($payees->isEmpty())
                                    <option value="1">Kerala Earthmovers</option>
                                    <option value="2">District Land Registrar</option>
                                @endif
                            </select>
                        </template>

                        <template x-if="payeeType === 'one_time'">
                            <input type="text" x-model="casualPayeeName" placeholder="e.g. Saju Auto Driver, Local Hardware" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                        </template>
                    </div>

                    {{-- Vendor Details Preview Box --}}
                    <div class="md:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 uppercase text-[10px] tracking-wider">Vendor Details</span>
                            <span class="text-[10px] text-blue-600 font-bold">Verified Master</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                            <div><span class="text-slate-400 block">GSTIN:</span> <span class="font-mono font-bold text-slate-800" x-text="vendorGstin">32AABCKE1234F1Z5</span></div>
                            <div><span class="text-slate-400 block">Contact:</span> <span class="font-bold text-slate-800" x-text="vendorPhone">9895 432 100</span></div>
                            <div class="col-span-2"><span class="text-slate-400 block">Email:</span> <span class="font-bold text-slate-800" x-text="vendorEmail">info@keralaearthmovers.com</span></div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">Expense Category <span class="text-rose-500">*</span></label>
                        <select x-model="expenseCategoryCode" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white text-slate-800" required>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}">
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="currentStep = 1" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50">Back</button>
                <button type="button" @click="currentStep = 3" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md flex items-center gap-2 cursor-pointer">
                    <span>Next</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- STEP 3: AMOUNT & PAYMENT --}}
        <div x-show="currentStep === 3" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">3. Amount Details & Payment Information</h3>
                <span class="text-xs text-slate-400 font-semibold">Step 3 of 4</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Left: Amount Details --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Amount Details</h4>
                    
                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gross / Taxable Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model.number="grossAmount" class="w-full text-xs font-mono font-bold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white" required>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">CGST (9%)</label>
                                <input type="text" readonly :value="formatCurrency(cgstAmount)" class="w-full text-xs font-mono rounded-lg border-slate-200 bg-slate-100 py-2 px-2.5">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">SGST (9%)</label>
                                <input type="text" readonly :value="formatCurrency(sgstAmount)" class="w-full text-xs font-mono rounded-lg border-slate-200 bg-slate-100 py-2 px-2.5">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">IGST (0%)</label>
                                <input type="text" readonly value="₹ 0.00" class="w-full text-xs font-mono rounded-lg border-slate-200 bg-slate-100 py-2 px-2.5">
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                            <span class="font-bold text-blue-950 text-xs">Total Amount Payable (₹):</span>
                            <span class="font-black text-blue-950 font-mono text-base" x-text="formatCurrency(totalAmountPayable)">₹ 53,100.00</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Payment Source & Mode --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Payment Source & Mode</h4>

                    <div class="space-y-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Payment Source Account <span class="text-rose-500">*</span></label>
                            <select x-model="bankAccountId" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:bg-white">
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">
                                        {{ $bank->bank_name }} - A/c {{ $bank->account_number }}
                                    </option>
                                @endforeach
                                @if($bankAccounts->isEmpty())
                                    <option value="1">HDFC Bank - A/c 1002 (Escrow / Collections)</option>
                                    <option value="2">Karnataka Bank - A/c 1001 (Operational)</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Payment Mode</label>
                            <div class="flex flex-wrap items-center gap-4 pt-1">
                                <label class="inline-flex items-center gap-1.5 font-semibold cursor-pointer">
                                    <input type="radio" value="Bank Transfer" x-model="paymentMode" class="text-blue-600"> <span>Bank Transfer</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 font-semibold cursor-pointer">
                                    <input type="radio" value="RTGS / NEFT" x-model="paymentMode" class="text-blue-600"> <span>RTGS / NEFT</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 font-semibold cursor-pointer">
                                    <input type="radio" value="UPI" x-model="paymentMode" class="text-blue-600"> <span>UPI</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 font-semibold cursor-pointer">
                                    <input type="radio" value="Cheque" x-model="paymentMode" class="text-blue-600"> <span>Cheque</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Transaction Reference No.</label>
                                <input type="text" x-model="transactionRef" placeholder="UTR123456789" class="w-full text-xs font-mono font-bold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Payment Date</label>
                                <input type="date" x-model="paymentDate" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="currentStep = 2" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50">Back</button>
                <button type="button" @click="currentStep = 4" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md flex items-center gap-2 cursor-pointer">
                    <span>Next</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        {{-- STEP 4: DOCUMENTS & REVIEW --}}
        <div x-show="currentStep === 4" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">4. Attachments & Accounting Preview</h3>
                <span class="text-xs text-slate-400 font-semibold">Step 4 of 4</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Attachments Upload --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Attachments</h4>
                    
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center bg-slate-50 hover:bg-slate-100 transition cursor-pointer relative">
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        <i data-lucide="upload-cloud" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                        <p class="text-xs font-bold text-slate-800">Click to upload or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-1">PDF, JPG, PNG (Max 10MB)</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px]">PDF</span>
                                <span class="font-bold text-slate-800">JCB_Rental_Bill_0525.pdf</span>
                                <span class="text-slate-400 text-[10px]">125 KB</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-blue-600">
                                <button type="button" class="hover:underline">Preview</button>
                                <button type="button" class="hover:underline">Download</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-bold text-[10px]">PDF</span>
                                <span class="font-bold text-slate-800">Payment_Receipt.pdf</span>
                                <span class="text-slate-400 text-[10px]">98 KB</span>
                            </div>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-blue-600">
                                <button type="button" class="hover:underline">Preview</button>
                                <button type="button" class="hover:underline">Download</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Review & Accounting Preview --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Preview Before Submit</h4>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            <div><span class="text-slate-400 block">Project:</span> <span class="font-bold text-slate-900" x-text="selectedProjectName">Skyline Heights</span></div>
                            <div><span class="text-slate-400 block">Tower:</span> <span class="font-bold text-slate-900" x-text="towerBlockTag">Tower A</span></div>
                            <div><span class="text-slate-400 block">Payee:</span> <span class="font-bold text-slate-900" x-text="selectedPayeeName">Local JCB Owner - Rajesh</span></div>
                            <div><span class="text-slate-400 block">Category:</span> <span class="font-bold text-slate-900" x-text="expenseCategoryCode + ' - Machinery & Equipment'">4020</span></div>
                            <div><span class="text-slate-400 block">Amount:</span> <span class="font-bold font-mono text-slate-900" x-text="formatCurrency(totalAmountPayable)">₹ 53,100.00</span></div>
                            <div><span class="text-slate-400 block">Payment Source:</span> <span class="font-bold text-slate-900">HDFC Bank - A/c 1002</span></div>
                            <div><span class="text-slate-400 block">Reference:</span> <span class="font-mono font-bold text-slate-900" x-text="transactionRef">UTR123456789</span></div>
                            <div><span class="text-slate-400 block">Date:</span> <span class="font-bold text-slate-900" x-text="paymentDate">20/05/2026</span></div>
                        </div>
                    </div>

                    {{-- Accounting Preview Card --}}
                    <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-200 text-xs space-y-2">
                        <h5 class="font-bold text-blue-950 uppercase text-[10px] tracking-wider">Accounting Preview (On Approval)</h5>
                        <div class="space-y-1 font-mono text-[11px] text-blue-900">
                            <div class="flex items-center justify-between">
                                <span>Dr. 4020 - Machinery & Heavy Equipment Rental</span>
                                <span class="font-bold" x-text="formatCurrency(grossAmount)">₹ 45,000.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dr. Input CGST</span>
                                <span class="font-bold" x-text="formatCurrency(cgstAmount)">₹ 4,050.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dr. Input SGST</span>
                                <span class="font-bold" x-text="formatCurrency(sgstAmount)">₹ 4,050.00</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-blue-200/80 pt-1 font-bold">
                                <span>Cr. HDFC Bank</span>
                                <span class="text-blue-950" x-text="formatCurrency(totalAmountPayable)">₹ 53,100.00</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="currentStep = 3" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50">Back</button>
                <div class="flex items-center gap-3">
                    <button type="submit" name="submit_action" value="draft" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                        Save as Draft
                    </button>
                    <button type="submit" name="submit_action" value="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold transition shadow-md flex items-center gap-2 cursor-pointer">
                        <span>Submit for Approval</span>
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
