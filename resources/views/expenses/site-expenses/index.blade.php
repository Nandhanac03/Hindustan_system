@extends('layouts.erp')

@section('title', 'Site Expenses Dashboard')

@section('content')
<div x-data="{ 
    showCreateModal: {{ request()->has('create') ? 'true' : 'false' }},
    showViewModal: false,
    selectedExpense: null,
    payeeType: '{{ old('payee_type', 'registered') }}',
    payeeId: '{{ old('payee_id', $payees->first()?->id ?? '') }}',
    payeesData: {{ json_encode($payees->keyBy('id')) }},
    casualPayeeName: '{{ old('casual_payee_name', 'Saju Tea Stall') }}',
    selectedVendorGstin: '32ABCDE1234F1Z5',
    gross: {{ old('gross_amount', 45000) }},
    gstPct: 18,
    paymentSourceType: 'bank',
    companyBankAccountId: '{{ old('company_bank_account_id', $bankAccounts->first()?->id ?? '1') }}',
    transactionRef: '{{ old('transaction_reference_no', 'JCB/0525/0148') }}',
    narration: '{{ old('narration', 'JCB rental for excavation work – Block A (Month of May 2025)') }}',
    uploadedFile: null,
    fileName: 'JCB_Rental_Bill_0525.pdf',
    fileSize: '125 KB',

    openViewModal(exp) {
        this.selectedExpense = exp;
        this.showViewModal = true;
    },

    get gstAmount() { 
        return (parseFloat(this.gross) || 0) * (parseFloat(this.gstPct) || 0) / 100; 
    },
    get netTotal() { 
        return (parseFloat(this.gross) || 0) + this.gstAmount; 
    },
    get amountInWords() {
        let num = Math.floor(parseFloat(this.gross) || 0);
        if (!num || num <= 0) return '';
        const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        function inWords(n) {
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
        num %= 100;
        if (crore) str += inWords(crore) + ' Crore ';
        if (lakh) str += inWords(lakh) + ' Lakh ';
        if (thousand) str += inWords(thousand) + ' Thousand ';
        if (hundred) str += inWords(hundred) + ' Hundred ';
        if (num) {
            if (str !== '') str += 'and ';
            str += inWords(num) + ' ';
        }
        return str.trim() + ' Rupees Only';
    },
    onPayeeChange() {
        if (this.payeeType === 'registered' && this.payeeId && this.payeesData[this.payeeId]) {
            let p = this.payeesData[this.payeeId];
            this.selectedVendorGstin = p.gstin || '32ABCDE1234F1Z5';
        }
    },
    handleFileUpload(event) {
        let file = event.target.files[0];
        if (file) {
            this.uploadedFile = file;
            this.fileName = file.name;
            this.fileSize = (file.size / 1024).toFixed(0) + ' KB';
        }
    },
    formatCurrency(val) {
        let n = parseFloat(val) || 0;
        return '₹ ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}" x-init="
    onPayeeChange();
    if (window.location.hash === '#add-site-expense-form' || window.location.search.includes('create=1')) {
        showCreateModal = true;
    }
    window.addEventListener('hashchange', () => {
        if (window.location.hash === '#add-site-expense-form') {
            showCreateModal = true;
        }
    });
" class="px-4 sm:px-6 lg:px-8 py-6 space-y-6 bg-slate-100 min-h-screen text-slate-800">

    {{-- Top Flash Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                <span class="font-bold text-xs sm:text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                <span class="font-bold text-xs sm:text-sm">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    <!-- Breadcrumb & Top Action Header (Contractor Master Theme Aligned) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
        <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
            <span class="text-slate-300">›</span>
            <span>Site Expense Management</span>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-black">Site Expenses Dashboard</span>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="#add-site-expense-form" @click.prevent="showCreateModal = true; window.location.hash = 'add-site-expense-form';"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 uppercase tracking-wider cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                <span>+ Add Site Expense</span>
            </a>
        </div>
    </div>

    {{-- TOP 5 KEY METRIC CARDS ROW (Matched with Contractor Master Box Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        
        {{-- Card 1: Total Expenses (YTD) --}}
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-[#a38c29] border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Expenses (YTD)</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900">₹ {{ number_format($totalAmount, 0) }}</div>
            <div class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i data-lucide="trending-up" class="w-3 h-3"></i> 12.6% vs Last Month</div>
        </div>

        {{-- Card 2: Approved Expenses --}}
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Approved Expenses</span>
                <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="text-base font-black font-mono text-blue-700">₹ {{ number_format($approvedAmount, 0) }}</div>
            <div class="text-[10px] font-medium text-slate-400">90.6% of Total</div>
        </div>

        {{-- Card 3: Pending Approval --}}
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Pending Approval</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="text-base font-black font-mono text-amber-600">₹ {{ number_format($pendingAmount, 0) }}</div>
            <div class="text-[10px] font-medium text-slate-400">9.4% of Total</div>
        </div>

        {{-- Card 4: This Month Expenses --}}
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>This Month Expenses</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="text-base font-black font-mono text-emerald-700">₹ {{ number_format($thisMonthExpenses, 0) }}</div>
            <div class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i data-lucide="trending-up" class="w-3 h-3"></i> 8.3% vs Last Month</div>
        </div>

        {{-- Card 5: Budget Utilization --}}
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-rose-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Budget Utilization</span>
                <div class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <i data-lucide="target" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <div class="text-base font-black font-mono text-rose-600">{{ $budgetUtilizationPct }}%</div>
            <div class="text-[10px] font-medium text-slate-400">of ₹ 78,00,000</div>
        </div>

    </div>

    {{-- MAIN EXPENSE REGISTER TABLE CARD (FULL WIDTH) --}}
    <div class="w-full space-y-6">

            {{-- EXPENSE REGISTER TABLE CARD --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-xs">
                
                {{-- Filter Tabs Bar --}}
                <div class="px-5 pt-4 border-b border-slate-200 flex items-center gap-6">
                    <a href="{{ route('site-expenses.index') }}" class="pb-3 border-b-2 font-bold text-xs transition {{ empty($statusTab) ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        All Expenses
                    </a>
                    <a href="{{ route('site-expenses.index', ['status' => 'draft']) }}" class="pb-3 border-b-2 font-bold text-xs transition flex items-center gap-1.5 {{ $statusTab === 'draft' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Pending Approval <span class="px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold">3</span>
                    </a>
                    <a href="{{ route('site-expenses.index', ['status' => 'approved']) }}" class="pb-3 border-b-2 font-bold text-xs transition {{ $statusTab === 'approved' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Approved
                    </a>
                    <a href="{{ route('site-expenses.index', ['status' => 'rejected']) }}" class="pb-3 border-b-2 font-bold text-xs transition {{ $statusTab === 'rejected' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Rejected
                    </a>
                </div>

                {{-- Filter Controls Bar --}}
                <form action="{{ route('site-expenses.index') }}" method="GET" class="p-4 bg-slate-50/70 border-b border-slate-200 grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
                    <div>
                        <select name="project_id" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2 px-3 focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Projects</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ request()->query('project_id') == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="category_code" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2 px-3 focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Categories</option>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ request()->query('category_code') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="payment_source" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2 px-3 focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Payment Sources</option>
                            <option value="bank" {{ request()->query('payment_source') == 'bank' ? 'selected' : '' }}>Bank Accounts</option>
                            <option value="loan" {{ request()->query('payment_source') == 'loan' ? 'selected' : '' }}>Loan Accounts</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="text" value="01/05/2025 - 20/05/2025" readonly class="w-full text-[11px] font-semibold rounded-xl border-slate-300 bg-white py-2 px-2.5 text-slate-600">
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] text-white font-bold transition shadow-xs">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                {{-- Table View --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-slate-800 border-collapse">
                        <thead class="bg-[#a38c29] text-white font-black uppercase tracking-widest text-[10px] border-b border-[#a38c29]">
                            <tr>
                                <th class="py-3.5 px-4 text-white">Date</th>
                                <th class="py-3.5 px-4 text-white">Expense Category</th>
                                <th class="py-3.5 px-4 text-white">Payee / Vendor</th>
                                <th class="py-3.5 px-4 text-right text-white">Amount (₹)</th>
                                <th class="py-3.5 px-4 text-white">Payment Source</th>
                                <th class="py-3.5 px-4 text-white">Status</th>
                                <th class="py-3.5 px-4 text-center text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($siteExpenses as $expense)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">
                                        {{ \Carbon\Carbon::parse($expense->voucher_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200">
                                                <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-900 block leading-tight text-xs">{{ $expense->expense_category_name }}</span>
                                                <span class="text-[10px] text-slate-400 font-mono">{{ $expense->expense_category_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                                        {{ $expense->payee_display_name }}
                                    </td>

                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">
                                        ₹ {{ number_format($expense->net_amount, 0) }}
                                    </td>

                                    <td class="py-3 px-4 text-slate-600 font-medium text-[11px]">
                                        {{ $expense->payment_source_display_name }}
                                    </td>

                                    <td class="py-3 px-4">
                                        @if($expense->status === 'Approved')
                                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">
                                                Approved
                                            </span>
                                        @elseif($expense->status === 'Draft')
                                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[11px] rounded-md border border-amber-200">
                                                Pending
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-bold text-[11px] rounded-md border border-rose-200">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <button type="button" 
                                                    @click="openViewModal({
                                                        voucher_number: '{{ $expense->voucher_number ?? 'EXP-2026-0003' }}',
                                                        voucher_date: '{{ \Carbon\Carbon::parse($expense->voucher_date)->format('d M Y') }}',
                                                        status: '{{ $expense->status }}',
                                                        project_name: '{{ addslashes($expense->project?->name ?? 'Tabasco Hindustan Infra Developers Pvt. Ltd') }}',
                                                        tower_block_tag: '{{ addslashes($expense->tower_block_tag ?? 'Tower A') }}',
                                                        payee_name: '{{ addslashes($expense->payee_display_name) }}',
                                                        payee_type: '{{ ucfirst($expense->payee_type ?? 'registered') }} Payee',
                                                        category_name: '{{ addslashes($expense->expense_category_code . ' - ' . $expense->expense_category_name) }}',
                                                        payment_source: '{{ addslashes($expense->payment_source_display_name) }}',
                                                        transaction_ref: '{{ addslashes($expense->transaction_reference_no ?? 'JCB/0525/0148') }}',
                                                        narration: '{{ addslashes($expense->narration ?? 'JCB rental for excavation work – Block A (Month of May 2025)') }}',
                                                        gross_amount: '{{ number_format($expense->gross_amount ?? $expense->net_amount, 2) }}',
                                                        gst_amount: '{{ number_format($expense->gst_amount ?? 0, 2) }}',
                                                        net_amount: '{{ number_format($expense->net_amount, 2) }}',
                                                        attachment_url: '{{ $expense->attachment_path ? Storage::url($expense->attachment_path) : '#' }}'
                                                    })" 
                                                    class="p-1 hover:bg-blue-50 rounded transition text-blue-600 cursor-pointer" title="View Details">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <form action="{{ route('site-expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Delete this site expense record?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Delete">
                                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Demonstration Static Rows matching the Image --}}
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">20/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-amber-50 text-amber-600 flex items-center justify-center">🚜</span>
                                            <span class="font-bold text-slate-900 text-xs">Machinery & Equipment Rental</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Local JCB Owner - Rajesh</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 53,100</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <button type="button" @click="openViewModal({
                                                voucher_number: 'EXP-2026-0003',
                                                voucher_date: '20 May 2025',
                                                status: 'Approved',
                                                project_name: 'Tabasco Hindustan Infra Developers Pvt. Ltd',
                                                tower_block_tag: 'Tower A',
                                                payee_name: 'Local JCB Owner - Rajesh',
                                                payee_type: 'Registered Payee',
                                                category_name: '4020 - Machinery & Equipment Rental',
                                                payment_source: 'HDFC - A/c 0678',
                                                transaction_ref: 'JCB/0525/0148',
                                                narration: 'JCB rental for excavation work – Block A (Month of May 2025)',
                                                gross_amount: '45,000.00',
                                                gst_amount: '8,100.00',
                                                net_amount: '53,100.00',
                                                attachment_url: '#'
                                            })" class="p-1 hover:bg-blue-50 rounded transition text-blue-600 cursor-pointer" title="View Details">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">19/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">🏢</span>
                                            <span class="font-bold text-slate-900 text-xs">Site Office & Admin</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Office Rent</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 35,400</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <button type="button" @click="openViewModal({
                                                voucher_number: 'EXP-2026-0002',
                                                voucher_date: '19 May 2025',
                                                status: 'Approved',
                                                project_name: 'Tabasco Hindustan Infra Developers Pvt. Ltd',
                                                tower_block_tag: 'Tower A',
                                                payee_name: 'Office Rent Payee',
                                                payee_type: 'Registered Payee',
                                                category_name: '4010 - Site Office & Admin',
                                                payment_source: 'HDFC - A/c 0678',
                                                transaction_ref: 'RENT/0525/0091',
                                                narration: 'Site Office Rent for Month of May 2025',
                                                gross_amount: '30,000.00',
                                                gst_amount: '5,400.00',
                                                net_amount: '35,400.00',
                                                attachment_url: '#'
                                            })" class="p-1 hover:bg-blue-50 rounded transition text-blue-600 cursor-pointer" title="View Details">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">19/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-rose-50 text-rose-600 flex items-center justify-center">⛽</span>
                                            <span class="font-bold text-slate-900 text-xs">Fuel & Transportation</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Bharat Petroleum</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 12,980</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <button type="button" @click="openViewModal({
                                                voucher_number: 'EXP-2026-0001',
                                                voucher_date: '19 May 2025',
                                                status: 'Approved',
                                                project_name: 'Tabasco Hindustan Infra Developers Pvt. Ltd',
                                                tower_block_tag: 'Tower A',
                                                payee_name: 'Bharat Petroleum',
                                                payee_type: 'Registered Payee',
                                                category_name: '4030 - Generator Diesel & Power Expenses',
                                                payment_source: 'HDFC - A/c 0678',
                                                transaction_ref: 'BPCL/0525/4412',
                                                narration: 'Diesel fuel for site generator',
                                                gross_amount: '11,000.00',
                                                gst_amount: '1,980.00',
                                                net_amount: '12,980.00',
                                                attachment_url: '#'
                                            })" class="p-1 hover:bg-blue-50 rounded transition text-blue-600 cursor-pointer" title="View Details">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">18/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-purple-50 text-purple-600 flex items-center justify-center">🏛️</span>
                                            <span class="font-bold text-slate-900 text-xs">Statutory & Municipal Fees</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Municipal Corporation</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 1,25,000</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[11px] rounded-md border border-amber-200">Pending</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <i data-lucide="eye" class="w-4 h-4 cursor-pointer"></i>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">17/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center">📄</span>
                                            <span class="font-bold text-slate-900 text-xs">Land & Registration Fees</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Sub Registrar Office</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 2,00,000</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[11px] rounded-md border border-amber-200">Pending</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <i data-lucide="eye" class="w-4 h-4 cursor-pointer"></i>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">16/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">🏢</span>
                                            <span class="font-bold text-slate-900 text-xs">Site Office & Admin</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Internet & Phone Bill</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 1,770</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <i data-lucide="eye" class="w-4 h-4 cursor-pointer"></i>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">15/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-rose-50 text-rose-600 flex items-center justify-center">⛽</span>
                                            <span class="font-bold text-slate-900 text-xs">Fuel & Transportation</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Diesel for Generator</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 8,260</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <i data-lucide="eye" class="w-4 h-4 cursor-pointer"></i>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">14/05/2025</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">🏢</span>
                                            <span class="font-bold text-slate-900 text-xs">Site Office & Admin</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900 text-xs">Refreshments & Stationery</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-950 text-xs">₹ 2,450</td>
                                    <td class="py-3 px-4 text-slate-600 text-[11px]">Bank - HDFC</td>
                                    <td class="py-3 px-4"><span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">Approved</span></td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5 text-blue-600">
                                            <i data-lucide="eye" class="w-4 h-4 cursor-pointer"></i>
                                            <i data-lucide="more-vertical" class="w-4 h-4 text-slate-400 cursor-pointer"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer Bar --}}
                <div class="px-4 py-3 border-t border-slate-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <span class="text-slate-500 font-semibold text-[11px]">Showing 1 to 8 of 45 entries</span>
                    
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1">
                            <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50"><i data-lucide="chevron-left" class="w-3.5 h-3.5"></i></button>
                            <button type="button" class="w-7 h-7 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center">1</button>
                            <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center hover:bg-slate-50">2</button>
                            <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center hover:bg-slate-50">3</button>
                            <span class="text-slate-400 text-xs px-1">...</span>
                            <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center hover:bg-slate-50">6</button>
                            <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50"><i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></button>
                        </div>

                        <select class="text-xs rounded-lg border-slate-200 bg-slate-50 py-1 px-2 font-semibold">
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>

    {{-- PREMIUM EXECUTIVE POPUP MODAL FOR ADD NEW SITE EXPENSE --}}
    <div x-show="showCreateModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-hidden">
        
        {{-- Backdrop blur overlay --}}
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="showCreateModal = false"></div>

        {{-- Modal Dialog Container (Borderless & Fit without scrolling) --}}
        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden z-10 my-auto flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Modal Header Bar (Borderless) --}}
            <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-3.5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-0.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            HINDUSTAN ERP · SITE EXPENSE MANAGEMENT
                        </p>
                        <h2 class="text-sm sm:text-base font-extrabold text-white uppercase tracking-wider flex items-center gap-2">
                            Add New Site Expense
                            <span class="px-2 py-0.5 text-[9px] font-extrabold rounded-md bg-[#a38c29]/20 text-amber-300 uppercase tracking-wider">COA 4000s Direct</span>
                        </h2>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Compact Form Body (Borderless & Fit without scrolling) --}}
            <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-2.5 text-xs bg-slate-50/50">
                @csrf

                {{-- SECTION 1: ASSOCIATION & CATEGORY --}}
                <div class="p-3 rounded-2xl bg-white space-y-2">
                    <div class="flex items-center justify-between pb-1">
                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-[#a38c29]"></i>
                            <span>1. Project Association & Category</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">COA 4000s</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                        <div class="sm:col-span-4">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Project <span class="text-rose-500">*</span></label>
                            <select name="project_id" class="w-full text-xs font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white text-slate-800" required>
                                <option value="">-- Select Project --</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ old('project_id', $selectedProjectId ?? '') == $proj->id ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                                @if($projects->isEmpty())
                                    <option value="1" selected>Skyline Heights</option>
                                    <option value="2">Green Valley</option>
                                @endif
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Tower / Phase</label>
                            <input type="text" name="tower_block_tag" value="{{ old('tower_block_tag', 'Tower A') }}" placeholder="Tower A" class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white text-slate-800">
                        </div>
                        <div class="sm:col-span-5">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Expense Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="voucher_date" value="{{ old('voucher_date', date('Y-m-d')) }}" class="w-full text-xs font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white text-slate-800" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Expense Category (COA 4000s) <span class="text-rose-500">*</span></label>
                            <select name="expense_category_code" class="w-full text-xs font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white text-slate-900" required>
                                @foreach($expenseCategories as $code => $name)
                                    <option value="{{ $code }}" {{ old('expense_category_code', '4020') == $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="hidden" name="payment_source_type" value="bank">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Payment Source Account <span class="text-rose-500">*</span></label>
                            <select name="company_bank_account_id" x-model="companyBankAccountId" class="w-full text-xs font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white text-slate-900">
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">
                                        Bank Account - {{ $bank->bank_name }} ({{ substr($bank->account_number ?? '1234', -4) }})
                                    </option>
                                @endforeach
                                @if($bankAccounts->isEmpty())
                                    <option value="1" selected>Bank Account - HDFC (1234)</option>
                                    <option value="2">Karnataka Bank - A/c 1001</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PAYEE & BILLING DETAILS --}}
                <div class="p-3 rounded-2xl bg-white space-y-2">
                    <div class="flex items-center justify-between pb-1">
                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                            <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#a38c29]"></i>
                            <span>2. Payee & Invoice Reference</span>
                        </div>
                        <span class="px-2 py-0.5 text-[9px] bg-amber-50 text-amber-900 font-extrabold rounded-md">Hybrid Payee</span>
                    </div>

                    {{-- Payee Segment Radio Toggle --}}
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <div class="sm:col-span-6 flex items-center gap-2">
                            <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded-xl transition text-[11px] flex-1"
                                   :class="payeeType === 'registered' ? 'bg-amber-100/80 text-[#8a7522]' : 'bg-slate-100 text-slate-700'">
                                <input type="radio" name="payee_type" value="registered" x-model="payeeType" @change="onPayeeChange()" class="text-[#a38c29] focus:ring-[#a38c29]">
                                <span class="font-extrabold">Registered Vendor</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer p-1.5 rounded-xl transition text-[11px] flex-1"
                                   :class="payeeType === 'one_time' ? 'bg-amber-100/80 text-[#8a7522]' : 'bg-slate-100 text-slate-700'">
                                <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29]">
                                <span class="font-extrabold">One-Time Payee</span>
                            </label>
                        </div>

                        <div class="sm:col-span-6">
                            <template x-if="payeeType === 'registered'">
                                <div>
                                    <select name="payee_id" x-model="payeeId" @change="onPayeeChange()" class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                                        <option value="">-- Search vendor master --</option>
                                        @foreach($payees as $payee)
                                            <option value="{{ $payee->id }}">
                                                {{ $payee->name }} {{ $payee->gstin ? '(GSTIN: '.$payee->gstin.')' : '' }}
                                            </option>
                                        @endforeach
                                        @if($payees->isEmpty())
                                            <option value="1" selected>Local JCB Owner - Rajesh (GSTIN: 32ABCDE1234F1Z5)</option>
                                            <option value="2">Sub Registrar Office (Government Legal)</option>
                                        @endif
                                    </select>
                                </div>
                            </template>

                            <template x-if="payeeType === 'one_time'">
                                <div>
                                    <input type="text" name="casual_payee_name" x-model="casualPayeeName" placeholder="Casual Payee Name..." class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Bill / Ref Voucher No.</label>
                            <input type="text" name="transaction_reference_no" x-model="transactionRef" placeholder="JCB/0525/0148" class="w-full text-xs font-mono font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Bill Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Due Date (Optional)</label>
                            <input type="date" class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: FINANCIALS, TAXATION & ATTACHMENT --}}
                <div class="p-3 rounded-2xl bg-white space-y-2">
                    <div class="flex items-center justify-between pb-1">
                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                            <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#a38c29]"></i>
                            <span>3. Financials, Taxes & Document Attachment</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Auto GST</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-end">
                        <div class="sm:col-span-4">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="gross_amount" x-model.number="gross" placeholder="45000.00" class="w-full text-xs font-mono font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white" required>
                            
                            {{-- Dynamic Amount in Words Display --}}
                            <div x-show="amountInWords" class="mt-1 p-1 rounded-lg bg-amber-50 text-[10px] text-amber-900 font-bold flex items-center gap-1 italic">
                                <i data-lucide="info" class="w-3 h-3 text-[#a38c29] shrink-0"></i>
                                <span x-text="amountInWords"></span>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">GST Rate (%)</label>
                            <select x-model.number="gstPct" class="w-full text-xs font-bold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                                <option value="0">0% (Nil)</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18" selected>18% Standard</option>
                                <option value="28">28%</option>
                            </select>
                        </div>

                        <div class="sm:col-span-5">
                            <div class="p-2 rounded-xl bg-amber-50 text-amber-900 flex items-center justify-between border border-amber-200/60 shadow-2xs">
                                <div>
                                    <span class="font-extrabold text-amber-800 text-[10px] block uppercase tracking-wider">Net Total (₹)</span>
                                </div>
                                <input type="hidden" name="net_amount" :value="netTotal">
                                <span class="font-black text-[#8a7522] font-mono text-base sm:text-lg" x-text="formatCurrency(netTotal)">₹ 53,100.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Remarks & Attach File Dropzone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 pt-0.5 items-center">
                        <div class="sm:col-span-7">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Remarks / Particulars</label>
                            <input type="text" name="narration" x-model="narration" placeholder="JCB rental for excavation work – Block A (Month of May 2025)" class="w-full text-xs font-semibold rounded-xl border-0 bg-slate-100 py-1.5 px-2.5 focus:ring-2 focus:ring-[#a38c29] focus:bg-white">
                        </div>
                        <div class="sm:col-span-5">
                            <label class="block font-bold text-slate-700 mb-0.5 text-[11px]">Attach Bill / Document</label>
                            <div class="p-1.5 rounded-xl bg-slate-100 text-center hover:bg-amber-50/40 transition cursor-pointer relative shadow-2xs group">
                                <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg" @change="handleFileUpload($event)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i data-lucide="upload-cloud" class="w-4 h-4 text-[#a38c29]"></i>
                                    <span class="text-xs font-bold text-slate-800 truncate" x-text="fileName || 'Upload PDF/Image'">JCB_Rental_Bill_0525.pdf</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer Bar (Borderless) --}}
                <div class="pt-2.5 flex items-center justify-between shrink-0 bg-white -mx-4 -mb-4 px-4 py-3 rounded-b-3xl">
                    <span class="text-[11px] text-rose-500 font-bold flex items-center gap-1">
                        * Required Fields
                    </span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition border-0">
                            Cancel
                        </button>
                        <button type="submit" name="submit_action" value="submit" class="px-6 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8c7722] text-white font-extrabold text-xs shadow-md uppercase tracking-wider transition cursor-pointer flex items-center gap-2 border-0">
                            <i data-lucide="check" class="w-4 h-4 text-white"></i>
                            <span>Save Site Expense</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    {{-- POPUP MODAL FOR VIEWING SITE EXPENSE VOUCHER DETAILS --}}
    <div x-show="showViewModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-hidden">
        
        {{-- Backdrop blur overlay --}}
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="showViewModal = false"></div>

        {{-- Modal Dialog Container (Borderless & Fit without scrolling) --}}
        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden z-10 my-auto flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Modal Header Bar (Borderless) --}}
            <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-4 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-0.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            HINDUSTAN ERP · VOUCHER DETAIL VIEW
                            <span class="text-slate-400 font-mono font-bold text-[10px] ml-1" x-text="'(' + (selectedExpense?.voucher_number || 'EXP-2026-0003') + ')'"></span>
                        </p>
                        <h2 class="text-base font-extrabold text-white uppercase tracking-wider" x-text="selectedExpense?.category_name || 'Site Expense Detail'"></h2>
                    </div>
                    <button type="button" @click="showViewModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body (Borderless & Fit without scrolling) --}}
            <div class="p-4 space-y-3 text-xs bg-slate-50/50">
                
                {{-- Top Details & Financial Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">

                    {{-- LEFT COLUMN: VOUCHER DETAILS & JOURNAL POSTING (7 Cols) --}}
                    <div class="lg:col-span-7 space-y-3">
                        
                        {{-- Voucher Details Card (Borderless) --}}
                        <div class="p-3.5 rounded-2xl bg-white shadow-2xs space-y-2.5">
                            <div class="flex items-center justify-between pb-1">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="file-check" class="w-4 h-4 text-[#a38c29]"></i>
                                    Voucher Details
                                </h4>
                                <span x-text="selectedExpense?.status || 'Approved'" 
                                      class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800': selectedExpense?.status === 'Approved',
                                          'bg-amber-100 text-amber-800': selectedExpense?.status === 'Draft' || selectedExpense?.status === 'Pending',
                                          'bg-rose-100 text-rose-800': selectedExpense?.status === 'Rejected'
                                      }">
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Voucher Number</span>
                                    <span class="font-mono font-extrabold text-slate-800 block" x-text="selectedExpense?.voucher_number || 'EXP-2026-0003'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Voucher Date</span>
                                    <span class="font-bold text-slate-800 block" x-text="selectedExpense?.voucher_date || '09 Sep 2026'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project Name</span>
                                    <span class="font-bold text-slate-900 block" x-text="selectedExpense?.project_name || 'Tabasco Hindustan Infra Developers Pvt. Ltd'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tower / Block Tag</span>
                                    <span class="font-semibold text-slate-700 block" x-text="selectedExpense?.tower_block_tag || 'Tower A'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payee Name</span>
                                    <span class="font-extrabold text-slate-900 block" x-text="selectedExpense?.payee_name || 'Basheer'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payee Type</span>
                                    <span class="font-semibold text-blue-600 block" x-text="selectedExpense?.payee_type || 'Registered Payee'"></span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expense Category</span>
                                    <span class="font-extrabold text-slate-900 block" x-text="selectedExpense?.category_name || '4003 - Agent Commission Expense'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment Source Account</span>
                                    <span class="font-bold text-slate-800 block" x-text="selectedExpense?.payment_source || 'HDFC - A/c 0678'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Transaction Ref / UTR</span>
                                    <span class="font-mono font-bold text-slate-800 block" x-text="selectedExpense?.transaction_ref || 'JCB/0525/0148'"></span>
                                </div>
                            </div>

                            <div class="pt-1.5">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Narration / Remarks</span>
                                <div class="p-2 rounded-xl bg-slate-50 text-slate-700 text-xs leading-relaxed" x-text="selectedExpense?.narration || 'JCB rental for excavation work – Block A (Month of May 2025)'"></div>
                            </div>
                        </div>

                        {{-- Double-Entry Journal Posting Card (Borderless) --}}
                        <div class="p-3.5 rounded-2xl bg-white shadow-2xs space-y-2">
                            <div class="flex items-center justify-between pb-1">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                                    Double-Entry Journal Posting
                                </h4>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md">Auto-Posted</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px]">
                                            <th class="py-1.5 px-2.5">Particulars Account</th>
                                            <th class="py-1.5 px-2.5 text-right">Debit (Dr ₹)</th>
                                            <th class="py-1.5 px-2.5 text-right">Credit (Cr ₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-semibold">
                                        <tr>
                                            <td class="py-2 px-2.5 font-bold text-slate-900" x-text="selectedExpense?.category_name || 'Site Expense Account'"></td>
                                            <td class="py-2 px-2.5 text-right font-mono font-bold text-slate-900" x-text="'₹ ' + (selectedExpense?.net_amount || '53,100.00')"></td>
                                            <td class="py-2 px-2.5 text-right font-mono text-slate-400">-</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-2.5 font-bold text-slate-900" x-text="selectedExpense?.payment_source || 'Bank / Loan Account'"></td>
                                            <td class="py-2 px-2.5 text-right font-mono text-slate-400">-</td>
                                            <td class="py-2 px-2.5 text-right font-mono font-bold text-slate-900" x-text="'₹ ' + (selectedExpense?.net_amount || '53,100.00')"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: FINANCIAL BREAKDOWN & ATTACHMENT (5 Cols) --}}
                    <div class="lg:col-span-5 space-y-3">
                        
                        {{-- Financial Breakdown Card (Borderless) --}}
                        <div class="p-3.5 rounded-2xl bg-white shadow-2xs space-y-2.5">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 pb-1">
                                <i data-lucide="calculator" class="w-4 h-4 text-[#a38c29]"></i>
                                Financial Breakdown
                            </h4>

                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Gross Amount</span>
                                    <span class="font-mono font-bold text-slate-900" x-text="'₹ ' + (selectedExpense?.gross_amount || '45,000.00')"></span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>CGST Amount</span>
                                    <span class="font-mono" x-text="'₹ ' + (parseFloat(selectedExpense?.gst_amount?.replace(/,/g, '') || 0) / 2).toFixed(2)">₹ 0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>SGST Amount</span>
                                    <span class="font-mono" x-text="'₹ ' + (parseFloat(selectedExpense?.gst_amount?.replace(/,/g, '') || 0) / 2).toFixed(2)">₹ 0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500 pb-1">
                                    <span>IGST Amount</span>
                                    <span class="font-mono">₹ 0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-900 font-black text-sm pt-1">
                                    <span class="text-[#a38c29] uppercase tracking-wider">Total Net Paid</span>
                                    <span class="font-mono text-[#a38c29] text-base sm:text-lg" x-text="'₹ ' + (selectedExpense?.net_amount || '53,100.00')"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Attachment Document Card (Borderless) --}}
                        <div class="p-3.5 rounded-2xl bg-white shadow-2xs space-y-2">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 pb-1">
                                <i data-lucide="paperclip" class="w-4 h-4 text-blue-600"></i>
                                Attachment Document
                            </h4>

                            <div class="p-2.5 rounded-xl bg-slate-50 flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2 truncate">
                                    <i data-lucide="file-text" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                    <span class="font-bold text-slate-800 text-xs truncate">Voucher_Receipt_Attachment.pdf</span>
                                </div>
                                <a :href="selectedExpense?.attachment_url || '#'" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] shrink-0 transition shadow-2xs">
                                    Download / View
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Modal Footer Bar (Borderless) --}}
            <div class="pt-3 flex items-center justify-between shrink-0 bg-white px-6 py-3 rounded-b-3xl">
                <span class="text-[11px] text-slate-400 font-semibold">HindustanERP • Site Expenses Module</span>
                <div class="flex items-center gap-3">
                    <button type="button" @click="showViewModal = false" class="px-5 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-xs transition border-0">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
