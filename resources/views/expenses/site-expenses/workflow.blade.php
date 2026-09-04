@extends('layouts.erp')

@section('title', 'Site Expenses Module - Interactive Workflow')

@section('content')
<div x-data="{ showCreateModal: false }" class="px-4 sm:px-6 lg:px-8 py-6 space-y-8 bg-slate-100 min-h-screen text-slate-800">

    {{-- Top Flash Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session('info'))
        <div class="p-4 rounded-xl bg-blue-50 border border-blue-300 text-blue-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="info" class="w-6 h-6 text-blue-600"></i>
                <span class="font-medium text-sm">{{ session('info') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-6 h-6 text-rose-600"></i>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    {{-- Header Banner & Action bar --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-[#a38c29]/15 text-[#8d7923] text-xs font-bold uppercase rounded-full tracking-wider border border-[#a38c29]/30">
                    Contractor Operations & Finance
                </span>
                <span class="text-xs font-semibold text-slate-500">Module #4000 Series</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                SITE EXPENSES MODULE – WORKFLOW
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                From Expense Recording to Automated Double-Entry & Project Profitability Impact
            </p>
        </div>

        {{-- Project Filter Switcher & Navigation Buttons --}}
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('site-expenses.workflow') }}" method="GET" class="flex items-center gap-2">
                <label for="project_id" class="text-xs font-bold text-slate-600">Project:</label>
                <select name="project_id" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg border-slate-300 bg-slate-50 text-slate-800 focus:ring-[#a38c29] focus:border-[#a38c29] py-2 px-3 shadow-sm">
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ $selectedProject->id == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('site-expenses.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                <i data-lucide="list" class="w-4 h-4 text-slate-500"></i> View All Records
            </a>
            
            <button @click="showCreateModal = true" type="button" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg bg-[#a38c29] text-white hover:bg-[#8d7923] transition shadow-md cursor-pointer">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Expense
            </button>
        </div>
    </div>

    {{-- STEP PIPELINE INDICATOR BAR --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 relative">
            
            {{-- Step 1 --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 relative">
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    1
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-wide uppercase">Expense Entry</h4>
                    <p class="text-[11px] text-slate-500 font-medium">Data Captured</p>
                </div>
                <div class="hidden lg:block absolute -right-3 top-1/2 -translate-y-1/2 z-10 text-slate-300">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 relative">
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    2
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-wide uppercase">Review & Validation</h4>
                    <p class="text-[11px] text-slate-500 font-medium">Verify Details</p>
                </div>
                <div class="hidden lg:block absolute -right-3 top-1/2 -translate-y-1/2 z-10 text-slate-300">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 relative">
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    3
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-wide uppercase">Approval</h4>
                    <p class="text-[11px] text-slate-500 font-medium">Authorized Sign-off</p>
                </div>
                <div class="hidden lg:block absolute -right-3 top-1/2 -translate-y-1/2 z-10 text-slate-300">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200 relative">
                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    4
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 tracking-wide uppercase">Automatic Journal Entry</h4>
                    <p class="text-[11px] text-slate-500 font-medium">System Generated</p>
                </div>
                <div class="hidden lg:block absolute -right-3 top-1/2 -translate-y-1/2 z-10 text-slate-300">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#a38c29]/10 border border-[#a38c29]/30">
                <div class="w-9 h-9 rounded-full bg-[#a38c29] text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    5
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#6b5d1c] tracking-wide uppercase">Profitability Impact</h4>
                    <p class="text-[11px] text-[#8d7923] font-medium">Updates Project Matrix</p>
                </div>
            </div>

        </div>
    </div>

    {{-- MAIN 5-COLUMN WORKFLOW GRID LAYOUT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 items-start">

        {{-- COLUMN 1: SITE EXPENSES - ADD NEW (Form Card) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-4 py-3 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-[#a38c29]"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider">SITE EXPENSES – ADD NEW</h3>
                </div>
                <span class="text-[10px] bg-slate-700 text-slate-200 px-2 py-0.5 rounded font-mono">Step 1</span>
            </div>

            <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data" x-data="{ payeeType: 'registered', gross: 75000, cgst: 6750, sgst: 6750, get net() { return (parseFloat(this.gross)||0) + (parseFloat(this.cgst)||0) + (parseFloat(this.sgst)||0); } }" class="p-4 space-y-4 text-xs flex-1 flex flex-col justify-between">
                @csrf
                
                {{-- A. Header & Association Details --}}
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-1 text-[11px] text-blue-900 flex items-center gap-1.5">
                        <i data-lucide="building" class="w-3.5 h-3.5 text-blue-600"></i> A. Header & Association Details
                    </h4>
                    
                    <div>
                        <label class="block font-semibold text-slate-700 mb-0.5">Project Name <span class="text-rose-500">*</span></label>
                        <select name="project_id" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29] focus:border-[#a38c29]" required>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ $selectedProject->id == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">Tower / Block Tag</label>
                            <input type="text" name="tower_block_tag" value="Tower A" placeholder="e.g. Tower A" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29] focus:border-[#a38c29]">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">Voucher Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="voucher_date" value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29] focus:border-[#a38c29]" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-0.5">Voucher Number</label>
                        <input type="text" value="{{ $autoVoucherNumber }}" readonly class="w-full text-xs rounded-lg border-slate-200 bg-slate-100 text-slate-600 font-mono font-bold py-1.5 px-2.5">
                    </div>
                </div>

                {{-- B. Payee / Vendor Information --}}
                <div class="space-y-2 pt-1 border-t border-slate-100">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-1 text-[11px] text-blue-900 flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-blue-600"></i> B. Payee / Vendor Information (Hybrid)
                    </h4>

                    <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-lg border border-slate-200">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="payee_type" value="registered" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span class="font-medium text-slate-800">Registered Vendor</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span class="font-medium text-slate-800">One-Time / Casual</span>
                        </label>
                    </div>

                    <template x-if="payeeType === 'registered'">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">Select Vendor <span class="text-rose-500">*</span></label>
                            <select name="payee_id" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29]">
                                <option value="">-- Select Registered Vendor --</option>
                                @foreach($payees as $payee)
                                    <option value="{{ $payee->id }}" selected>
                                        {{ $payee->name }} ({{ $payee->category ?? 'Vendor' }})
                                    </option>
                                @endforeach
                                <option value="" {{ $payees->isEmpty() ? 'selected' : '' }}>Kerala Earthmovers (Equipment Rental)</option>
                            </select>
                        </div>
                    </template>

                    <template x-if="payeeType === 'one_time'">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">Casual Payee Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="casual_payee_name" placeholder="e.g. Saju Auto Driver / Local Hardware" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5">
                        </div>
                    </template>
                </div>

                {{-- C. Expense Classification & Financial Details --}}
                <div class="space-y-2 pt-1 border-t border-slate-100">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-1 text-[11px] text-blue-900 flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-blue-600"></i> C. Expense Classification (4000-Series)
                    </h4>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-0.5">Expense Category <span class="text-rose-500">*</span></label>
                        <select name="expense_category_code" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29]" required>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ $code == '4020' ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-1.5">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">Gross (₹)</label>
                            <input type="number" step="0.01" name="gross_amount" x-model.number="gross" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2 font-mono" required>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">CGST (₹)</label>
                            <input type="number" step="0.01" name="cgst_amount" x-model.number="cgst" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2 font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-0.5">SGST (₹)</label>
                            <input type="number" step="0.01" name="sgst_amount" x-model.number="sgst" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2 font-mono">
                        </div>
                    </div>

                    <div class="bg-blue-50/70 p-2 rounded-lg border border-blue-100 flex items-center justify-between">
                        <span class="font-bold text-blue-900">Net Amount Paid (₹):</span>
                        <input type="hidden" name="net_amount" :value="net">
                        <span class="font-extrabold text-blue-900 text-sm font-mono" x-text="'₹ ' + net.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹ 88,500.00</span>
                    </div>
                </div>

                {{-- D. Payment Source --}}
                <div class="space-y-2 pt-1 border-t border-slate-100">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-1 text-[11px] text-blue-900 flex items-center gap-1.5">
                        <i data-lucide="credit-card" class="w-3.5 h-3.5 text-blue-600"></i> D. Payment Source (Bank / Loan Only)
                    </h4>

                    <div>
                        <input type="hidden" name="payment_source_type" value="bank">
                        <label class="block font-semibold text-slate-700 mb-0.5">Payment Source Account <span class="text-rose-500">*</span></label>
                        <select name="company_bank_account_id" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5 focus:ring-[#a38c29]">
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}" selected>
                                    {{ $bank->bank_name }} - A/c {{ substr($bank->account_number ?? '1001', -4) }} (Operational)
                                </option>
                            @endforeach
                            <option value="1" {{ $bankAccounts->isEmpty() ? 'selected' : '' }}>Karnataka Bank - A/c 1001 (Operational)</option>
                            <option value="2">HDFC Bank - A/c 1002 (Escrow / Collections)</option>
                            <option value="3">SBI Land Loan - A/c 2010 (Loan Disbursal Account)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-0.5">Transaction Ref / UTR No. <span class="text-rose-500">*</span></label>
                        <input type="text" name="transaction_reference_no" value="KKBK260916123456" placeholder="e.g. Bank UTR / Cheque Ref" class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2.5" required>
                    </div>
                </div>

                {{-- E. Document Attachment --}}
                <div class="space-y-2 pt-1 border-t border-slate-100">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-1 text-[11px] text-blue-900 flex items-center gap-1.5">
                        <i data-lucide="paperclip" class="w-3.5 h-3.5 text-blue-600"></i> E. Document Attachment
                    </h4>

                    <div class="p-2.5 border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 text-center hover:bg-slate-100/80 transition">
                        <input type="file" name="attachment" id="attachment" class="hidden">
                        <label for="attachment" class="cursor-pointer flex flex-col items-center">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-400 mb-1"></i>
                            <span class="text-[11px] font-semibold text-slate-700">Upload Invoice / Payment Receipt</span>
                            <span class="text-[10px] text-slate-500">PDF, PNG, JPG (Max 10MB)</span>
                        </label>
                    </div>
                    <div class="text-[10px] bg-slate-100 p-2 rounded flex items-center justify-between text-slate-600 border border-slate-200">
                        <span class="truncate font-mono">earthmovers_invoice_16092026.pdf</span>
                        <span class="text-emerald-700 font-bold shrink-0">Attached</span>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="submit" name="submit_action" value="draft" class="px-3 py-2 rounded-lg border border-slate-300 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50">
                        Save as Draft
                    </button>
                    <button type="submit" name="submit_action" value="submit" class="px-3.5 py-2 rounded-lg bg-blue-700 text-white text-xs font-bold hover:bg-blue-800 shadow-md">
                        Submit for Approval
                    </button>
                </div>
            </form>
        </div>

        {{-- COLUMN 2: REVIEW EXPENSE (Review & Validation Card) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-4 py-3 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider">REVIEW EXPENSE</h3>
                </div>
                <span class="text-[10px] bg-slate-700 text-slate-200 px-2 py-0.5 rounded font-mono">Step 2</span>
            </div>

            <div class="p-4 space-y-4 text-xs flex-1 flex flex-col justify-between">
                
                @if($activeExpense)
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="font-semibold text-slate-500">Voucher No.</span>
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-xs">
                                {{ $activeExpense->voucher_number }}
                            </span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Project</span>
                            <span class="font-bold text-slate-800 text-right">{{ $activeExpense->project?->name ?? 'Green Valley Residency' }}</span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Tower / Block</span>
                            <span class="font-semibold text-slate-700">{{ $activeExpense->tower_block_tag ?? 'Tower A' }}</span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Date</span>
                            <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($activeExpense->voucher_date)->format('d-m-Y') }}</span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Vendor</span>
                            <span class="font-bold text-slate-900 text-right">{{ $activeExpense->payee_display_name }}</span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Category</span>
                            <span class="font-semibold text-blue-900 text-right">{{ $activeExpense->expense_category_code }} - {{ $activeExpense->expense_category_name }}</span>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 space-y-1">
                            <div class="flex justify-between text-slate-600">
                                <span>Gross Amount</span>
                                <span class="font-mono">₹ {{ number_format($activeExpense->gross_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 text-[11px]">
                                <span>CGST</span>
                                <span class="font-mono">₹ {{ number_format($activeExpense->cgst_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 text-[11px]">
                                <span>SGST</span>
                                <span class="font-mono">₹ {{ number_format($activeExpense->sgst_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-900 font-extrabold border-t border-slate-200 pt-1">
                                <span>Net Amount</span>
                                <span class="font-mono text-sm text-blue-900">₹ {{ number_format($activeExpense->net_amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Payment Account</span>
                            <span class="font-semibold text-slate-800 text-right">{{ $activeExpense->payment_source_display_name }}</span>
                        </div>

                        <div class="flex justify-between border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Reference No.</span>
                            <span class="font-mono text-slate-700">{{ $activeExpense->transaction_reference_no }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                            <span class="text-slate-500 font-medium">Attachment</span>
                            <span class="font-semibold text-blue-600 hover:underline cursor-pointer flex items-center gap-1">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> earthmovers_invoice.pdf
                            </span>
                        </div>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex items-center gap-2 text-emerald-800">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <span class="font-semibold text-xs">All details verified and correct.</span>
                    </div>

                    {{-- Review Actions --}}
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
                        <form action="{{ route('site-expenses.reject', $activeExpense->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-lg border border-rose-300 text-rose-700 bg-rose-50 hover:bg-rose-100 text-xs font-bold transition">
                                Send Back
                            </button>
                        </form>

                        @if($activeExpense->status !== 'Approved')
                            <form action="{{ route('site-expenses.approve', $activeExpense->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
                                    Approve
                                </button>
                            </form>
                        @else
                            <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-lg flex items-center gap-1">
                                <i data-lucide="check" class="w-4 h-4"></i> Approved
                            </span>
                        @endif
                    </div>
                @else
                    {{-- Default Static Demo View matching image --}}
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="font-semibold text-slate-500">Voucher No.</span>
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-xs">EXP-2026-0042</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Project</span><span class="font-bold text-slate-800">Green Valley Residency</span></div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Tower / Block</span><span class="font-semibold">Tower A</span></div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Date</span><span>16-09-2026</span></div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Vendor</span><span class="font-bold">Kerala Earthmovers</span></div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Category</span><span class="font-semibold text-blue-900">4020 - Machinery Rental</span></div>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 space-y-1">
                            <div class="flex justify-between text-slate-600"><span>Gross Amount</span><span class="font-mono">₹ 75,000.00</span></div>
                            <div class="flex justify-between text-slate-500 text-[11px]"><span>CGST</span><span class="font-mono">₹ 6,750.00</span></div>
                            <div class="flex justify-between text-slate-500 text-[11px]"><span>SGST</span><span class="font-mono">₹ 6,750.00</span></div>
                            <div class="flex justify-between text-slate-900 font-extrabold border-t border-slate-200 pt-1"><span>Net Amount</span><span class="font-mono text-sm text-blue-900">₹ 88,500.00</span></div>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Payment Account</span><span class="font-semibold text-right">Karnataka Bank - Operational</span></div>
                        <div class="flex justify-between border-b border-slate-100 pb-1.5"><span class="text-slate-500">Reference No.</span><span class="font-mono">KKBK260916123456</span></div>
                        <div class="flex justify-between items-center border-b border-slate-100 pb-1.5"><span class="text-slate-500">Attachment</span><span class="text-blue-600 font-semibold cursor-pointer">earthmovers_invoice_16092026.pdf</span></div>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex items-center gap-2 text-emerald-800">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <span class="font-semibold text-xs">All details verified and correct.</span>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
                        <button class="px-3 py-2 rounded-lg border border-rose-300 text-rose-700 bg-rose-50 text-xs font-bold">Send Back</button>
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs font-bold shadow-sm">Approve</button>
                    </div>
                @endif
            </div>
        </div>

        {{-- COLUMN 3: APPROVAL (Sign-off Card) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-4 py-3 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider">APPROVAL</h3>
                </div>
                <span class="text-[10px] bg-slate-700 text-slate-200 px-2 py-0.5 rounded font-mono">Step 3</span>
            </div>

            <div class="p-4 space-y-4 text-xs flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    
                    {{-- Level 1 Sign-off --}}
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-xs">Finance Manager</span>
                            <span class="px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-full">Approved</span>
                        </div>
                        <p class="text-[11px] text-slate-500"><span class="text-slate-400">Level 1 Approval</span></p>

                        <div class="mt-2 text-[11px] space-y-1 border-t border-slate-200 pt-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Approved By</span>
                                <span class="font-bold text-slate-800">Ramesh B.</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Date & Time</span>
                                <span class="font-mono text-slate-600">16-09-2026 11:15 AM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Remarks</span>
                                <span class="font-semibold text-emerald-700">Verified</span>
                            </div>
                        </div>
                    </div>

                    {{-- Arrow connecting sign-offs --}}
                    <div class="flex justify-center -my-2">
                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center">
                            <i data-lucide="arrow-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>

                    {{-- Level 2 Sign-off --}}
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-xs">Project Head</span>
                            <span class="px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-full">Approved</span>
                        </div>
                        <p class="text-[11px] text-slate-500"><span class="text-slate-400">Level 2 Approval</span></p>

                        <div class="mt-2 text-[11px] space-y-1 border-t border-slate-200 pt-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Approved By</span>
                                <span class="font-bold text-slate-800">Arvind Kumar</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Date & Time</span>
                                <span class="font-mono text-slate-600">16-09-2026 01:20 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Remarks</span>
                                <span class="font-semibold text-emerald-700">Approved for Posting</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Approval Badge Footer --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex items-center gap-2 text-emerald-800">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span class="font-bold text-xs">Voucher Approved Successfully</span>
                </div>
            </div>
        </div>

        {{-- COLUMN 4: JOURNAL ENTRY (AUTO) (Accounting Card) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-4 py-3 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-sky-400"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider">JOURNAL ENTRY (AUTO)</h3>
                </div>
                <span class="text-[10px] bg-slate-700 text-slate-200 px-2 py-0.5 rounded font-mono">Step 4</span>
            </div>

            <div class="p-4 space-y-4 text-xs flex-1 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-[11px] font-medium">
                        System automatically creates journal entry on approval.
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Journal Entry No.</span>
                            <span class="font-mono font-bold text-slate-900">JE-2026-09-0356</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Date</span>
                            <span class="font-mono text-slate-700">16-09-2026</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Reference</span>
                            <span class="font-mono text-slate-700">EXP-2026-0042</span>
                        </div>
                    </div>

                    {{-- Particulars Table --}}
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-[11px] text-left">
                            <thead class="bg-slate-100 text-slate-700 border-b border-slate-200 font-bold">
                                <tr>
                                    <th class="p-2">Particulars</th>
                                    <th class="p-2 text-right">Dr. (₹)</th>
                                    <th class="p-2 text-right">Cr. (₹)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr>
                                    <td class="p-2 font-medium text-slate-800">
                                        4020 - Machinery & Heavy Equipment Rental
                                    </td>
                                    <td class="p-2 text-right font-mono font-bold text-slate-900">88,500.00</td>
                                    <td class="p-2 text-right font-mono text-slate-400">-</td>
                                </tr>
                                <tr>
                                    <td class="p-2 font-medium text-slate-800">
                                        Karnataka Bank - A/c 1001 (Operational)
                                    </td>
                                    <td class="p-2 text-right font-mono text-slate-400">-</td>
                                    <td class="p-2 text-right font-mono font-bold text-slate-900">88,500.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 flex items-center gap-2 text-emerald-800">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span class="font-bold text-[11px]">Journal Entry Posted Successfully</span>
                    </div>

                    {{-- Financial Impact Summary --}}
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-1.5 text-[11px]">
                        <h5 class="font-bold text-slate-900 border-b border-slate-200 pb-1">Impact</h5>
                        <div class="flex items-center gap-1.5 text-emerald-700 font-medium">
                            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                            <span>Total Project Cost Increased by <strong>₹ 88,500.00</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-rose-700 font-medium">
                            <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
                            <span>Bank Balance Decreased by <strong>₹ 88,500.00</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-blue-700 font-medium pt-1">
                            <i data-lucide="info" class="w-3.5 h-3.5"></i>
                            <span>Affects Cost Per Sq.Ft. & Profitability</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMN 5: PROJECT PROFITABILITY MATRIX (Analytics Card) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-4 py-3 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-[#a38c29]"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider">PROJECT PROFITABILITY MATRIX</h3>
                </div>
                <span class="text-[10px] bg-[#a38c29] text-white px-2 py-0.5 rounded font-mono font-bold">Step 5</span>
            </div>

            <div class="p-4 space-y-4 text-xs flex-1 flex flex-col justify-between">
                <div class="space-y-3">
                    
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-[11px]">Project Dashboard</label>
                        <select class="w-full text-xs rounded-lg border-slate-300 bg-slate-50 py-1.5 px-2 font-bold text-slate-800">
                            <option selected>{{ $selectedProject->name }}</option>
                        </select>
                    </div>

                    {{-- Cost Summary Table --}}
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 text-[11px]">Cost Summary (₹)</h4>
                        <div class="space-y-1 text-[11px]">
                            <div class="flex justify-between text-slate-600">
                                <span>Land Cost</span>
                                <span class="font-mono">12,50,00,000</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Construction Cost</span>
                                <span class="font-mono">28,75,00,000</span>
                            </div>
                            <div class="flex justify-between text-blue-900 font-bold bg-blue-50/80 px-1.5 py-0.5 rounded border border-blue-100">
                                <span>Direct Site Expenses</span>
                                <span class="font-mono">3,25,75,000</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Indirect Site Expenses</span>
                                <span class="font-mono">2,10,20,000</span>
                            </div>
                            <div class="flex justify-between text-slate-900 font-extrabold border-t border-slate-300 pt-1 text-xs">
                                <span>Total Project Cost</span>
                                <span class="font-mono text-slate-950">46,60,95,000</span>
                            </div>
                        </div>
                    </div>

                    {{-- Profitability Overview --}}
                    <div class="space-y-1 pt-2 border-t border-slate-200">
                        <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 text-[11px]">Profitability Overview</h4>
                        <div class="space-y-1 text-[11px]">
                            <div class="flex justify-between text-slate-600">
                                <span>Total Revenue (Expected)</span>
                                <span class="font-mono">56,00,00,000</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Total Project Cost</span>
                                <span class="font-mono">46,60,95,000</span>
                            </div>
                            <div class="flex justify-between text-emerald-800 font-bold pt-1">
                                <span>Gross Profit</span>
                                <span class="font-mono text-emerald-700 text-xs font-extrabold">9,39,05,000</span>
                            </div>
                            <div class="flex justify-between text-slate-700 font-semibold">
                                <span>Gross Margin</span>
                                <span class="font-mono font-bold text-slate-900">16.77%</span>
                            </div>
                        </div>

                        {{-- Highlight Box: Cost Per Sq.Ft. --}}
                        <div class="mt-2 bg-[#a38c29]/15 border border-[#a38c29]/40 rounded-xl p-2.5 flex items-center justify-between">
                            <span class="font-bold text-[#6b5d1c] text-xs">Cost Per Sq.Ft.</span>
                            <span class="font-mono font-extrabold text-sm text-[#4a4014]">₹ 4,660.95</span>
                        </div>
                    </div>

                    {{-- Recent Site Expenses Impact --}}
                    <div class="space-y-1.5 pt-2 border-t border-slate-200">
                        <h4 class="font-bold text-slate-900 text-[11px]">Recent Site Expenses Impact</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-[10px] text-left">
                                <thead class="bg-slate-100 text-slate-700 font-bold">
                                    <tr>
                                        <th class="p-1.5">Date</th>
                                        <th class="p-1.5">Voucher No.</th>
                                        <th class="p-1.5 text-right">Amount (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white font-mono">
                                    <tr>
                                        <td class="p-1.5">16-09-2026</td>
                                        <td class="p-1.5 text-blue-600 font-bold">EXP-2026-0042</td>
                                        <td class="p-1.5 text-right font-bold text-emerald-700">88,500.00</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1.5">15-09-2026</td>
                                        <td class="p-1.5 text-slate-600">EXP-2026-0041</td>
                                        <td class="p-1.5 text-right">25,000.00</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1.5">13-09-2026</td>
                                        <td class="p-1.5 text-slate-600">EXP-2026-0040</td>
                                        <td class="p-1.5 text-right">48,360.00</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                                    <tr>
                                        <td colspan="2" class="p-1.5 font-sans text-slate-800">Total (This Month)</td>
                                        <td class="p-1.5 text-right text-blue-900 font-mono">1,61,860.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- WORKFLOW SUMMARY FOOTER BANNER --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 space-y-4">
        <h3 class="text-sm font-extrabold text-slate-900 tracking-wider text-center uppercase">
            WORKFLOW SUMMARY
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6 items-center">
            
            {{-- Summary Step 1 --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="file-plus-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">1. Expense Entry</h4>
                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                        User enters site expense with all details, amount, category, payment & attachment.
                    </p>
                </div>
            </div>

            {{-- Summary Step 2 --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="search" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">2. Review & Validation</h4>
                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                        Finance team reviews and validates the expense details and documents.
                    </p>
                </div>
            </div>

            {{-- Summary Step 3 --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">3. Approval</h4>
                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                        Multi-level approval by Finance Manager and Project Head.
                    </p>
                </div>
            </div>

            {{-- Summary Step 4 --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">4. Automatic Journal Entry</h4>
                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                        System posts double-entry journal to Expense Account (Dr) and Bank/Loan Account (Cr).
                    </p>
                </div>
            </div>

            {{-- Summary Step 5 --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">5. Profitability Impact</h4>
                    <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                        Expense impacts total project cost, cost per sq.ft. and profitability reports.
                    </p>
                </div>
            </div>

        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-center text-blue-900 text-xs font-semibold flex items-center justify-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
            <span>This ensures real-time visibility, accurate cost tracking, and reliable profitability analysis for every project.</span>
        </div>
    </div>

    {{-- Creation Modal --}}
    <div x-show="showCreateModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        
        <div @click.away="showCreateModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-4xl w-full my-8 overflow-hidden text-slate-800 flex flex-col max-h-[90vh]">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 px-6 py-4 text-white flex items-center justify-between border-b border-[#a38c29]/30 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/50 flex items-center justify-center text-[#a38c29]">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold tracking-tight flex items-center gap-2">
                            Record New Site Expense Voucher
                            <span class="px-2 py-0.5 text-[10px] bg-[#a38c29] text-white font-bold rounded-full uppercase">ERP Form</span>
                        </h3>
                        <p class="text-xs text-slate-400">Direct operational payment (Land, JCB Rental, Diesel, Municipal Fees)</p>
                    </div>
                </div>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            {{-- Modal Form Content --}}
            <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data" 
                  x-data="{ 
                      payeeType: 'registered', 
                      paymentSource: 'bank',
                      gross: 0,
                      cgst: 0,
                      sgst: 0,
                      igst: 0,
                      get net() { return (parseFloat(this.gross)||0) + (parseFloat(this.cgst)||0) + (parseFloat(this.sgst)||0) + (parseFloat(this.igst)||0); } 
                  }" 
                  class="p-6 space-y-5 text-xs overflow-y-auto flex-1">
                @csrf

                {{-- Section A: Header & Association --}}
                <div class="space-y-3">
                    <h4 class="font-extrabold text-[#6b5d1c] uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-2">
                        <i data-lucide="building" class="w-4 h-4 text-[#a38c29]"></i> A. Header & Association Details
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Project Name <span class="text-rose-500">*</span></label>
                            <select name="project_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29] focus:border-[#a38c29]" required>
                                <option value="">-- Select Project --</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ $selectedProject->id == $proj->id ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Voucher Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="voucher_date" value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tower / Block Tag (Optional)</label>
                            <input type="text" name="tower_block_tag" placeholder="e.g. Tower A / Overall Project" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Floor Tag (Optional)</label>
                            <select name="floor_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                                <option value="">-- Overall Project / No Specific Floor --</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section B: Payee Info --}}
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <h4 class="font-extrabold text-[#6b5d1c] uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-[#a38c29]"></i> B. Payee / Vendor Information (Hybrid Model)
                    </h4>

                    <div class="flex items-center gap-6 bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-xs">
                        <span class="font-bold text-slate-700">Payee Type Toggle:</span>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                            <input type="radio" name="payee_type" value="registered" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span>Registered Vendor / Master</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer font-semibold">
                            <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span>One-Time / Casual Payee</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <template x-if="payeeType === 'registered'">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Select Registered Vendor <span class="text-rose-500">*</span></label>
                                <select name="payee_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                                    <option value="">-- Select Registered Vendor --</option>
                                    @foreach($payees as $payee)
                                        <option value="{{ $payee->id }}">
                                            {{ $payee->name }} ({{ $payee->category ?? 'Vendor' }})
                                        </option>
                                    @endforeach
                                    @if($payees->isEmpty())
                                        <option value="1" selected>Kerala Earthmovers (Equipment Rental)</option>
                                        <option value="2">District Land Registrar (Government Legal Fee)</option>
                                    @endif
                                </select>
                            </div>
                        </template>

                        <template x-if="payeeType === 'one_time'">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Casual Payee Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="casual_payee_name" placeholder="e.g. Saju Auto Driver, Local Hardware" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Section C: Expense Category & Amount --}}
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <h4 class="font-extrabold text-[#6b5d1c] uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-2">
                        <i data-lucide="tags" class="w-4 h-4 text-[#a38c29]"></i> C. Expense Classification (4000-Series)
                    </h4>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Expense Category <span class="text-rose-500">*</span></label>
                        <select name="expense_category_code" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]" required>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ $code == '4020' ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gross (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="gross_amount" x-model.number="gross" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 font-mono focus:ring-[#a38c29]" required>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">CGST (₹)</label>
                            <input type="number" step="0.01" name="cgst_amount" x-model.number="cgst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 font-mono focus:ring-[#a38c29]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">SGST (₹)</label>
                            <input type="number" step="0.01" name="sgst_amount" x-model.number="sgst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 font-mono focus:ring-[#a38c29]">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">IGST (₹)</label>
                            <input type="number" step="0.01" name="igst_amount" x-model.number="igst" placeholder="0.00" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 font-mono focus:ring-[#a38c29]">
                        </div>
                    </div>

                    <div class="bg-[#a38c29]/10 p-3 rounded-xl border border-[#a38c29]/30 flex items-center justify-between">
                        <span class="font-extrabold text-[#4a4014] text-xs">Calculated Net Amount Paid:</span>
                        <input type="hidden" name="net_amount" :value="net">
                        <span class="text-xl font-black text-[#2e2810] font-mono" x-text="'₹ ' + net.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹ 0.00</span>
                    </div>
                </div>

                {{-- Section D: Payment Source --}}
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <h4 class="font-extrabold text-[#6b5d1c] uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-[#a38c29]"></i> D. Payment Source (Bank / Loan Only)
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Payment Source Type <span class="text-rose-500">*</span></label>
                            <select name="payment_source_type" x-model="paymentSource" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]" required>
                                <option value="bank">Corporate Bank Account</option>
                                <option value="loan">Bank Loan Account</option>
                            </select>
                        </div>

                        <template x-if="paymentSource === 'bank'">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Corporate Bank Account <span class="text-rose-500">*</span></label>
                                <select name="company_bank_account_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                                    @foreach($bankAccounts as $bank)
                                        <option value="{{ $bank->id }}">
                                            {{ $bank->bank_name }} - {{ $bank->account_number }}
                                        </option>
                                    @endforeach
                                    @if($bankAccounts->isEmpty())
                                        <option value="1" selected>Karnataka Bank - A/c 1001 (Operational)</option>
                                        <option value="2">HDFC Bank - A/c 1002 (Escrow / Collections)</option>
                                    @endif
                                </select>
                            </div>
                        </template>

                        <template x-if="paymentSource === 'loan'">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Loan Liability Account <span class="text-rose-500">*</span></label>
                                <select name="loan_id" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                                    @foreach($loans as $loan)
                                        <option value="{{ $loan->id }}">
                                            {{ $loan->lender_name }} - {{ $loan->account_number }}
                                        </option>
                                    @endforeach
                                    @if($loans->isEmpty())
                                        <option value="1" selected>SBI Land Loan - A/c 2010 (Loan Disbursal)</option>
                                    @endif
                                </select>
                            </div>
                        </template>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Transaction Ref / UTR No. <span class="text-rose-500">*</span></label>
                            <input type="text" name="transaction_reference_no" placeholder="e.g. Bank UTR / Cheque Ref" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]" required>
                        </div>
                    </div>
                </div>

                {{-- Section E: Attachment & Remarks --}}
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <h4 class="font-extrabold text-[#6b5d1c] uppercase tracking-wider text-[11px] border-b border-slate-200 pb-1.5 flex items-center gap-2">
                        <i data-lucide="paperclip" class="w-4 h-4 text-[#a38c29]"></i> E. Document Attachment & Narration
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Upload Receipt (PDF / Photo)</label>
                            <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-1.5 px-3">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Voucher Narration</label>
                            <input type="text" name="narration" placeholder="e.g. Payment for JCB rental machine..." class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2 px-3 focus:ring-[#a38c29]">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-2 shrink-0">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" name="submit_action" value="draft" class="px-4 py-2 rounded-xl border border-slate-300 bg-slate-100 text-slate-800 font-bold hover:bg-slate-200">
                        Save as Draft
                    </button>
                    <button type="submit" name="submit_action" value="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] text-white font-bold hover:bg-[#8d7923] shadow-md transition">
                        Submit & Post Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
