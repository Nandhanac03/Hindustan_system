@extends('layouts.erp')

@section('title', 'Site Expenses Dashboard')

@section('content')
<div x-data="{ 
    showCreateModal: {{ request()->has('create') ? 'true' : 'false' }},
    showViewModal: false,
    selectedExpense: null,
    projectId: '{{ old('project_id', $projects->first()?->id ?? '') }}',
    voucherDate: '{{ old('voucher_date', date('Y-m-d')) }}',
    expenseCategoryCode: '{{ old('expense_category_code', '4020') }}',
    paymentSourceType: 'bank',
    companyBankAccountId: '{{ old('company_bank_account_id', $bankAccounts->first()?->id ?? '1') }}',
    payeeType: '{{ old('payee_type', 'registered') }}',
    payeeId: '{{ old('payee_id', $payees->first()?->id ?? '') }}',
    payeesData: {{ json_encode($payees->keyBy('id')) }},
    casualPayeeName: '{{ old('casual_payee_name', '') }}',
    selectedVendorGstin: '',
    transactionRef: '{{ old('transaction_reference_no', '') }}',
    billDate: '{{ old('bill_date', date('Y-m-d')) }}',
    dueDate: '{{ old('due_date', '') }}',
    gross: '{{ old('gross_amount', '') }}',
    gstPct: 18,
    narration: '{{ old('narration', '') }}',
    uploadedFile: null,
    fileName: '',
    fileSize: '',

    showConfirmModal: false,
    confirmType: 'reject',
    confirmExpenseId: null,
    confirmVoucherNumber: '',
    confirmActionUrl: '',
    confirmMethod: 'POST',

    openConfirmModal(type, id, voucherNumber) {
        this.confirmType = type;
        this.confirmExpenseId = id;
        this.confirmVoucherNumber = voucherNumber || 'EXP-VOUCHER';
        this.showConfirmModal = true;
    },

    openViewModal(exp) {
        this.selectedExpense = exp;
        this.showViewModal = true;
    },

    openCreateModal() {
        this.selectedExpense = null;
        this.projectId = '{{ $projects->first()?->id ?? '' }}';
        this.voucherDate = '{{ date('Y-m-d') }}';
        this.expenseCategoryCode = '4020';
        this.payeeType = 'registered';
        this.payeeId = '{{ $payees->first()?->id ?? '' }}';
        this.casualPayeeName = '';
        this.companyBankAccountId = '{{ $bankAccounts->first()?->id ?? '1' }}';
        this.transactionRef = '';
        this.billDate = '{{ date('Y-m-d') }}';
        this.dueDate = '';
        this.gross = '';
        this.gstPct = 18;
        this.narration = '';
        this.uploadedFile = null;
        this.fileName = '';
        this.fileSize = '';
        this.onPayeeChange();
        this.showCreateModal = true;
    },

    openEditModal(exp) {
        this.selectedExpense = exp;
        if (exp.project_id) this.projectId = exp.project_id;
        if (exp.voucher_date || exp.raw_voucher_date) this.voucherDate = exp.voucher_date || exp.raw_voucher_date;
        if (exp.expense_category_code) this.expenseCategoryCode = exp.expense_category_code;
        if (exp.company_bank_account_id) this.companyBankAccountId = exp.company_bank_account_id;
        if (exp.payee_type || exp.raw_payee_type) {
            let pType = (exp.raw_payee_type || exp.payee_type || 'registered').toLowerCase();
            this.payeeType = pType.includes('one') ? 'one_time' : 'registered';
        }
        if (exp.payee_id) this.payeeId = exp.payee_id;
        if (exp.casual_payee_name) this.casualPayeeName = exp.casual_payee_name;
        this.transactionRef = (exp.transaction_ref && exp.transaction_ref !== '-') ? exp.transaction_ref : '';
        if (exp.bill_date) this.billDate = exp.bill_date;
        if (exp.due_date) this.dueDate = exp.due_date;
        if (exp.gross_raw !== undefined && exp.gross_raw !== null && exp.gross_raw !== '') {
            this.gross = parseFloat(exp.gross_raw) || 0;
        }
        if (exp.gst_rate !== undefined && exp.gst_rate !== null && exp.gst_rate !== '') {
            this.gstPct = parseFloat(exp.gst_rate) || 0;
        }
        this.narration = (exp.narration && exp.narration !== '-') ? exp.narration : '';
        this.fileName = exp.attachment_name || '';
        this.uploadedFile = null;
        this.onPayeeChange();
        this.showCreateModal = true;
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
        openCreateModal();
    }
    window.addEventListener('hashchange', () => {
        if (window.location.hash === '#add-site-expense-form') {
            openCreateModal();
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

    <!-- Header Title Section (Tabasco ERP Gold Theme Aligned) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
        <div>
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Site Expense Management</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Site Expenses Dashboard</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Site Expenses</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">Manage and track all non-contractor direct operational expenses</p>
        </div>

        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <button type="button" @click="openCreateModal()"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-black text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 uppercase tracking-wider cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                <span>Add Site Expense</span>
            </button>
        </div>
    </div>

    {{-- TOP 6 KEY METRIC CARDS ROW (Tabasco ERP Box Style - Icons Placed on Top Right) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3.5">
        
        {{-- Card 1: Total Site Expenses --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-[#a38c29] border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">Total Site Expenses</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-slate-900">₹ {{ number_format($totalAmount, 0) }}</div>
            </div>
            <div class="flex items-center justify-between pt-0.5">
                <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-0.5 border border-emerald-100">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> 12.6%
                </div>
                <span class="text-[10px] font-semibold text-slate-400">vs Last Month</span>
            </div>
        </div>

        {{-- Card 2: Pending Approval --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-amber-500 border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">Pending Approval</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-amber-600">₹ {{ number_format($pendingAmount, 0) }}</div>
            </div>
            <div class="text-[10px] font-extrabold text-amber-600 pt-0.5">9.4% of Total</div>
        </div>

        {{-- Card 3: Approved / Posted --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-emerald-500 border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">Approved / Posted</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-emerald-600">₹ {{ number_format($approvedAmount, 0) }}</div>
            </div>
            <div class="text-[10px] font-extrabold text-emerald-600 pt-0.5">84.8% of Total</div>
        </div>

        {{-- Card 4: This Month --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-purple-500 border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">This Month</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 border border-purple-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-purple-700">₹ {{ number_format($thisMonthExpenses, 0) }}</div>
            </div>
            <div class="flex items-center justify-between pt-0.5">
                <div class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-0.5 border border-emerald-100">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> 8.3%
                </div>
                <span class="text-[10px] font-semibold text-slate-400">vs Last Month</span>
            </div>
        </div>

        {{-- Card 5: Budget Utilization --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-[#a38c29] border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">Budget Utilization</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="target" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-[#a38c29]">{{ $budgetUtilizationPct }}%</div>
            </div>
            <div class="space-y-1">
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-[#a38c29] h-full rounded-full" style="width: {{ min(100, $budgetUtilizationPct) }}%"></div>
                </div>
                <div class="text-[10px] font-semibold text-slate-400">of ₹ 78,00,000</div>
            </div>
        </div>

        {{-- Card 6: Unposted / Accounting Pending --}}
        <div class="p-4 rounded-2xl border border-l-4 border-l-rose-500 border-slate-200/80 bg-white transition-all duration-300 space-y-2 hover:-translate-y-1 hover:shadow-md cursor-default group">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block leading-tight">Unposted / Pending</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                    <i data-lucide="disc" class="w-4 h-4"></i>
                </div>
            </div>
            <div>
                <div class="text-lg font-black font-mono text-rose-600">₹ {{ number_format($unpostedAmount, 0) }}</div>
            </div>
            <div class="space-y-1">
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full" style="width: {{ min(100, $unpostedPct) }}%"></div>
                </div>
                <div class="text-[10px] font-semibold text-rose-500">{{ $unpostedPct }}% of Total</div>
            </div>
        </div>

    </div>

    {{-- MAIN EXPENSE REGISTER TABLE CARD (FULL WIDTH - NO RIGHT SIDEBAR) --}}
    <div class="w-full space-y-6">

        {{-- EXPENSE REGISTER TABLE CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden text-xs">
            
            {{-- Header Row --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Recent Site Expenses</h3>
                </div>
            </div>

            {{-- Professional Segmented Pill Tabs Bar --}}
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2 overflow-x-auto">
                <a href="{{ route('site-expenses.index') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ empty($statusTab) || $statusTab === 'all' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>All</span>
                    <span class="{{ empty($statusTab) || $statusTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['all'] ?? $siteExpenses->total() }}</span>
                </a>
                <a href="{{ route('site-expenses.index', ['status' => 'draft']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ $statusTab === 'draft' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>Draft</span>
                    <span class="{{ $statusTab === 'draft' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['draft'] ?? 0 }}</span>
                </a>
                <a href="{{ route('site-expenses.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ $statusTab === 'pending' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>Pending Approval</span>
                    <span class="{{ $statusTab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['pending'] ?? 0 }}</span>
                </a>
                <a href="{{ route('site-expenses.index', ['status' => 'approved']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ $statusTab === 'approved' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>Approved</span>
                    <span class="{{ $statusTab === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['approved'] ?? 0 }}</span>
                </a>
                <a href="{{ route('site-expenses.index', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ $statusTab === 'rejected' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>Rejected</span>
                    <span class="{{ $statusTab === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['rejected'] ?? 0 }}</span>
                </a>
                <a href="{{ route('site-expenses.index', ['status' => 'posted']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 whitespace-nowrap flex items-center gap-2 {{ $statusTab === 'posted' ? 'bg-[#a38c29] text-white shadow-sm shadow-[#a38c29]/30' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 hover:bg-slate-50' }}">
                    <span>Posted</span>
                    <span class="{{ $statusTab === 'posted' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }} text-[10px] px-2 py-0.5 rounded-full font-black">{{ $tabCounts['posted'] ?? 0 }}</span>
                </a>
            </div>

            {{-- Professional Filter Controls Bar --}}
            <form action="{{ route('site-expenses.index') }}" method="GET" class="p-4 bg-white border-b border-slate-200/80 space-y-3">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    
                    {{-- Search Input Box --}}
                    <div class="relative flex-1">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by voucher no, vendor, project..."
                               class="w-full text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-4 text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition shadow-2xs placeholder-slate-400">
                    </div>

                    {{-- Filters Action Button (Gold Background as requested) --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] text-white font-black text-xs uppercase tracking-wider transition shadow-md shadow-[#a38c29]/20 flex items-center gap-2 cursor-pointer border-0">
                            <i data-lucide="sliders-horizontal" class="w-4 h-4 text-white"></i>
                            <span>Filters</span>
                        </button>
                    </div>
                </div>

                {{-- Filter Select Dropdowns Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 pt-1">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Project</label>
                        <select name="project_id" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]">
                            @if($projects->count() !== 1)
                                <option value="">All Projects</option>
                            @endif
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ ($projects->count() === 1 || request()->query('project_id') == $proj->id) ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Category</label>
                        <select name="category_code" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Categories</option>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ request()->query('category_code') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Payment Source</label>
                        <select name="payment_source" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Sources</option>
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}" {{ request()->query('payment_source') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} {{ $bank->account_name ? '('.$bank->account_name.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Payment Mode</label>
                        <select name="payment_mode" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Modes</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="RTGS / NEFT">RTGS / NEFT</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Date Range</label>
                        <div class="relative">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2"></i>
                            <input type="text" value="01/05/2025 - 20/05/2025" readonly class="w-full text-[11px] font-semibold rounded-xl border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-2 text-slate-600">
                        </div>
                    </div>
                </div>
            </form>

            {{-- Table View --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-800 border-collapse">
                    <thead class="bg-[#a38c29] text-white font-black uppercase tracking-widest text-[10px] border-b border-[#a38c29]">
                        <tr>
                            <th class="py-3.5 px-4 text-white">Voucher No.</th>
                            <th class="py-3.5 px-4 text-white">Date</th>
                            <th class="py-3.5 px-4 text-white">Project</th>
                            <th class="py-3.5 px-4 text-white">Expense Category</th>
                            <th class="py-3.5 px-4 text-white">Payee / Vendor</th>
                            <th class="py-3.5 px-4 text-right text-white">Amount (₹)</th>
                            <th class="py-3.5 px-4 text-white">Payment Source</th>
                            <th class="py-3.5 px-4 text-white">Payment Mode</th>
                            <th class="py-3.5 px-4 text-white">Status</th>
                            <th class="py-3.5 px-4 text-center text-white uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @forelse($siteExpenses as $expense)
                            <tr class="hover:bg-amber-50/20 transition">
                                <td class="py-3 px-4 font-mono font-bold text-[#a38c29] text-[11px]">
                                    {{ $expense->voucher_number }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                                    {{ \Carbon\Carbon::parse($expense->voucher_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    {{ $expense->project?->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900">
                                    {{ $expense->expense_category_name }}
                                </td>
                                <td class="py-3 px-4 text-slate-800 font-bold">
                                    {{ $expense->payee_display_name }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-black text-slate-950">
                                    ₹ {{ number_format($expense->net_amount, 0) }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $expense->payment_source_display_name }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $expense->payment_mode ?? 'Bank Transfer' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($expense->status === 'Approved')
                                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-md border border-emerald-200">
                                            Approved
                                        </span>
                                    @elseif($expense->status === 'Draft' || $expense->status === 'Pending')
                                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-[11px] rounded-md border border-amber-200">
                                            Pending
                                        </span>
                                    @elseif($expense->status === 'Posted')
                                        <span class="px-2.5 py-0.5 bg-amber-50 text-[#8a741f] font-bold text-[11px] rounded-md border border-amber-200">
                                            Posted
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-bold text-[11px] rounded-md border border-rose-200">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 text-slate-500">
                                        <button type="button" 
                                                @click="openViewModal({
                                                    id: {{ $expense->id }},
                                                    voucher_number: '{{ $expense->voucher_number }}',
                                                    voucher_date: '{{ \Carbon\Carbon::parse($expense->voucher_date)->format('d M Y') }}',
                                                    raw_voucher_date: '{{ \Carbon\Carbon::parse($expense->voucher_date)->format('Y-m-d') }}',
                                                    status: '{{ $expense->status }}',
                                                    project_id: '{{ $expense->project_id ?? '' }}',
                                                    project_name: '{{ addslashes($expense->project?->name ?? '-') }}',
                                                    payee_id: '{{ $expense->payee_id ?? '' }}',
                                                    payee_name: '{{ addslashes($expense->payee_display_name) }}',
                                                    payee_type: '{{ ucfirst($expense->payee_type ?? 'registered') }} Payee',
                                                    raw_payee_type: '{{ $expense->payee_type ?? 'registered' }}',
                                                    casual_payee_name: '{{ addslashes($expense->casual_payee_name ?? '') }}',
                                                    expense_category_code: '{{ $expense->expense_category_code ?? '4020' }}',
                                                    category_name: '{{ addslashes($expense->expense_category_code . ' - ' . $expense->expense_category_name) }}',
                                                    payment_source: '{{ addslashes($expense->payment_source_display_name) }}',
                                                    payment_source_type: '{{ $expense->payment_source_type ?? 'bank' }}',
                                                    company_bank_account_id: '{{ $expense->company_bank_account_id ?? '' }}',
                                                    transaction_ref: '{{ addslashes($expense->transaction_reference_no ?? '') }}',
                                                    bill_date: '{{ $expense->bill_date ? \Carbon\Carbon::parse($expense->bill_date)->format('Y-m-d') : '' }}',
                                                    due_date: '{{ $expense->due_date ? \Carbon\Carbon::parse($expense->due_date)->format('Y-m-d') : '' }}',
                                                    narration: '{{ addslashes($expense->narration ?? '') }}',
                                                    gross_amount: '{{ number_format($expense->gross_amount ?? $expense->net_amount, 2) }}',
                                                    gross_raw: '{{ $expense->gross_amount ?? $expense->net_amount }}',
                                                    gst_rate: {{ $expense->gst_rate ?? 18 }},
                                                    gst_amount: '{{ number_format($expense->gst_amount ?? 0, 2) }}',
                                                    net_amount: '{{ number_format($expense->net_amount, 2) }}',
                                                    attachment_url: '{{ $expense->attachment_path ? Storage::url($expense->attachment_path) : '' }}',
                                                    attachment_name: '{{ $expense->attachment_path ? basename($expense->attachment_path) : '' }}'
                                                })" 
                                                class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-[#a38c29] border border-amber-200/80 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="View Details">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button type="button" 
                                                @click="openEditModal({
                                                    id: {{ $expense->id }},
                                                    voucher_number: '{{ $expense->voucher_number }}',
                                                    voucher_date: '{{ \Carbon\Carbon::parse($expense->voucher_date)->format('Y-m-d') }}',
                                                    status: '{{ $expense->status }}',
                                                    project_id: '{{ $expense->project_id ?? '' }}',
                                                    expense_category_code: '{{ $expense->expense_category_code ?? '4020' }}',
                                                    payment_source_type: '{{ $expense->payment_source_type ?? 'bank' }}',
                                                    company_bank_account_id: '{{ $expense->company_bank_account_id ?? '' }}',
                                                    payee_type: '{{ $expense->payee_type ?? 'registered' }}',
                                                    payee_id: '{{ $expense->payee_id ?? '' }}',
                                                    casual_payee_name: '{{ addslashes($expense->casual_payee_name ?? '') }}',
                                                    transaction_ref: '{{ addslashes($expense->transaction_reference_no ?? '') }}',
                                                    bill_date: '{{ $expense->bill_date ? \Carbon\Carbon::parse($expense->bill_date)->format('Y-m-d') : '' }}',
                                                    due_date: '{{ $expense->due_date ? \Carbon\Carbon::parse($expense->due_date)->format('Y-m-d') : '' }}',
                                                    gross_raw: '{{ $expense->gross_amount ?? $expense->net_amount }}',
                                                    gst_rate: {{ $expense->gst_rate ?? 18 }},
                                                    narration: '{{ addslashes($expense->narration ?? '') }}',
                                                    attachment_name: '{{ $expense->attachment_path ? basename($expense->attachment_path) : '' }}'
                                                })" 
                                                class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200/80 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Edit Expense">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5 text-blue-600"></i>
                                        </button>
                                        @if($expense->status !== 'Approved')
                                            {{-- Hidden Approve Form --}}
                                            <form id="approve-form-{{ $expense->id }}" action="{{ route('site-expenses.approve', $expense->id) }}" method="POST" style="display:none">
                                                @csrf
                                            </form>
                                            <button type="button" 
                                                    @click="openConfirmModal('approve', {{ $expense->id }}, '{{ $expense->voucher_number }}')" 
                                                    class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200/80 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Approve Voucher">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                            </button>
                                        @endif

                                        {{-- Hidden Reject Form --}}
                                        <form id="reject-form-{{ $expense->id }}" action="{{ route('site-expenses.reject', $expense->id) }}" method="POST" style="display:none">
                                            @csrf
                                        </form>
                                        <button type="button" 
                                                @click="openConfirmModal('reject', {{ $expense->id }}, '{{ $expense->voucher_number }}')" 
                                                class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Reject / Send Back Voucher">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-amber-700"></i>
                                        </button>

                                        {{-- Hidden Delete Form --}}
                                        <form id="delete-form-{{ $expense->id }}" action="{{ route('site-expenses.destroy', $expense->id) }}" method="POST" style="display:none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" 
                                                @click="openConfirmModal('delete', {{ $expense->id }}, '{{ $expense->voucher_number }}')" 
                                                class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition inline-flex items-center justify-center shadow-2xs cursor-pointer" title="Delete Expense">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5 text-rose-600"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center bg-slate-50/50">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center">
                                            <i data-lucide="inbox" class="w-6 h-6"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-sm font-bold text-slate-800">No Site Expenses Found</p>
                                            <p class="text-xs text-slate-500">There are no expense records in the database matching your selected status tab or filters.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Clean Table Footer (No Pagination Buttons as requested) --}}
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between text-xs text-slate-600 font-semibold">
                <span>Showing {{ $siteExpenses->count() }} of {{ $siteExpenses->total() ?? 45 }} entries</span>
                <span class="text-[11px] text-slate-400 uppercase font-extrabold tracking-wider">Site Expense Management</span>
            </div>

        </div>

    </div>

    {{-- PREMIUM EXECUTIVE POPUP MODAL FOR ADD / EDIT SITE EXPENSE --}}
    <div x-show="showCreateModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        
        {{-- Backdrop blur overlay --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" @click="showCreateModal = false"></div>

        {{-- Modal Dialog Container --}}
        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden z-10 my-auto flex flex-col max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Modal Header Bar --}}
            <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                TABASCO HINDUSTAN · SITE EXPENSE MANAGEMENT
                            </p>
                            <template x-if="selectedExpense">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider shadow-2xs"
                                      :class="{
                                          'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': selectedExpense?.status === 'Approved',
                                          'bg-amber-500/20 text-amber-300 border border-amber-500/30': selectedExpense?.status === 'Draft' || selectedExpense?.status === 'Pending',
                                          'bg-rose-500/20 text-rose-300 border border-rose-500/30': selectedExpense?.status === 'Rejected'
                                      }"
                                      x-text="selectedExpense?.status || 'Draft'">
                                </span>
                            </template>
                            <template x-if="!selectedExpense">
                                <span class="px-2 py-0.5 text-[9px] font-bold rounded-lg bg-white/10 text-slate-300 uppercase tracking-wider">COA 4000s Direct</span>
                            </template>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-white uppercase tracking-wider" 
                            x-text="selectedExpense ? ('Edit Site Expense — ' + (selectedExpense.voucher_number || '')) : 'Add New Site Expense'"></h2>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer border-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Spacious Executive Form Body --}}
            <form id="site-expense-form" 
                  :action="selectedExpense ? ('{{ url('/site-expenses') }}/' + selectedExpense.id) : '{{ route('site-expenses.store') }}'" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="p-3.5 sm:p-5 space-y-3 text-xs bg-slate-50/70 overflow-y-auto flex-1">
                @csrf
                <template x-if="selectedExpense">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- SECTION 1: PROJECT ASSOCIATION & CATEGORY --}}
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2 text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                            <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] flex items-center justify-center border border-amber-200/50">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>1. Project Association & Expense Category</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">COA 4000s Series</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Project Name <span class="text-rose-500">*</span></label>
                            <select name="project_id" x-model="projectId" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs" required>
                                @if($projects->count() !== 1)
                                    <option value="">-- Select Project --</option>
                                @endif
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ ($projects->count() === 1 || old('project_id', $selectedProjectId ?? '') == $proj->id) ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                                @if($projects->isEmpty())
                                    <option value="1" selected>Skyline Heights</option>
                                @endif
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Voucher Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="voucher_date" x-model="voucherDate" value="{{ old('voucher_date', date('Y-m-d')) }}" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs" required>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Expense Category (COA) <span class="text-rose-500">*</span></label>
                            <select name="expense_category_code" x-model="expenseCategoryCode" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs" required>
                                @foreach($expenseCategories as $code => $name)
                                    <option value="{{ $code }}" {{ old('expense_category_code', '4020') == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-4">
                            <input type="hidden" name="payment_source_type" value="bank">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Payment Source Account <span class="text-rose-500">*</span></label>
                            <select name="company_bank_account_id" x-model="companyBankAccountId" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                                @if($bankAccounts->count() !== 1)
                                    <option value="">-- Select Payment Source Account --</option>
                                @endif
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}" {{ ($bankAccounts->count() === 1 || old('company_bank_account_id') == $bank->id) ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} {{ $bank->account_name ? '('.$bank->account_name.')' : '' }} - A/c {{ $bank->account_number ? '...'.substr($bank->account_number, -4) : '' }}
                                    </option>
                                @endforeach
                                @if($bankAccounts->isEmpty())
                                    <option value="" disabled>No Company Bank Accounts found in Master</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PAYEE & INVOICE REFERENCE --}}
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2 text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                            <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] flex items-center justify-center border border-amber-200/50">
                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>2. Payee & Invoice Reference</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                        <div class="sm:col-span-4 flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200/60">
                            <label class="flex items-center justify-center gap-1.5 cursor-pointer py-1.5 px-2.5 rounded-lg transition text-[11px] flex-1 text-center"
                                   :class="payeeType === 'registered' ? 'bg-[#a38c29] text-white font-black shadow-xs' : 'text-slate-600 hover:text-slate-900 font-bold'">
                                <input type="radio" name="payee_type" value="registered" x-model="payeeType" @change="onPayeeChange()" class="sr-only">
                                <span>Registered Vendor</span>
                            </label>

                            <label class="flex items-center justify-center gap-1.5 cursor-pointer py-1.5 px-2.5 rounded-lg transition text-[11px] flex-1 text-center"
                                   :class="payeeType === 'one_time' ? 'bg-[#a38c29] text-white font-black shadow-xs' : 'text-slate-600 hover:text-slate-900 font-bold'">
                                <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="sr-only">
                                <span>One-Time Payee</span>
                            </label>
                        </div>

                        <div class="sm:col-span-8">
                            <template x-if="payeeType === 'registered'">
                                <div>
                                    <select name="payee_id" x-model="payeeId" @change="onPayeeChange()" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
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
                                    <input type="text" name="casual_payee_name" x-model="casualPayeeName" placeholder="Enter casual payee name..." class="w-full text-xs font-semibold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Bill / Ref Voucher No.</label>
                            <input type="text" name="transaction_reference_no" x-model="transactionRef" placeholder="e.g. JCB/0525/0148" class="w-full text-xs font-mono font-bold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Bill Date</label>
                            <input type="date" name="bill_date" x-model="billDate" class="w-full text-xs font-semibold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Due Date (Optional)</label>
                            <input type="date" name="due_date" x-model="dueDate" class="w-full text-xs font-semibold rounded-xl border border-slate-200 bg-white py-2 px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: FINANCIALS, TAXES & ATTACHMENT --}}
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2 text-xs font-extrabold text-slate-900 uppercase tracking-wider">
                            <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] flex items-center justify-center border border-amber-200/50">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>3. Financial Breakdown & Attachment</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Auto GST Calculation</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="gross_amount" x-model.number="gross" placeholder="45000.00" class="w-full h-9 text-xs font-mono font-black text-slate-900 rounded-xl border border-slate-200 bg-white px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 transition shadow-2xs" required>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">GST Rate (%)</label>
                            <select x-model.number="gstPct" class="w-full h-9 text-xs font-bold rounded-xl border border-slate-200 bg-white px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                                <option value="0">0% (Nil)</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18" selected>18% Standard</option>
                                <option value="28">28%</option>
                            </select>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Net Total Payable</label>
                            <div class="h-9 px-3.5 rounded-xl bg-[#a38c29] text-white flex items-center justify-between shadow-xs">
                                <div>
                                    <span class="font-black text-amber-100 text-[10px] block uppercase tracking-wider">Net Total Payable (₹)</span>
                                </div>
                                <input type="hidden" name="net_amount" :value="netTotal">
                                <span class="font-black text-white font-mono text-base sm:text-lg" x-text="formatCurrency(netTotal)">₹ 53,100.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Remarks & Attach File Dropzone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                        <div class="sm:col-span-7">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Remarks / Particulars</label>
                            <input type="text" name="narration" x-model="narration" placeholder="e.g. JCB rental for excavation work – Block A" class="w-full h-9 text-xs font-semibold rounded-xl border border-slate-200 bg-white px-3 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 text-slate-900 transition shadow-2xs">
                        </div>
                        <div class="sm:col-span-5">
                            <label class="block font-bold text-slate-700 mb-1 text-[11px]">Attach Bill / Document</label>
                            <div class="h-9 px-3 rounded-xl border border-dashed border-slate-300 bg-slate-50 hover:bg-amber-50/40 hover:border-[#a38c29] transition cursor-pointer relative shadow-2xs group flex items-center justify-between">
                                <input type="file" name="attachment" accept=".pdf,.png,.jpg,.jpeg" @change="handleFileUpload($event)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i data-lucide="paperclip" class="w-3.5 h-3.5 text-[#a38c29] shrink-0"></i>
                                    <span class="text-[11px] font-bold text-slate-800 truncate" x-text="fileName || 'Upload Bill / PDF Document'">Upload Bill / PDF Document</span>
                                </div>
                                <template x-if="fileName">
                                    <span class="text-[10px] font-bold text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded shrink-0 ml-2">Attached</span>
                                </template>
                                <template x-if="!fileName">
                                    <span class="text-[10px] font-bold text-slate-400 shrink-0 ml-2">Browse</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            {{-- Pinned Footer — outside scrollable form --}}
            <div class="px-5 py-3 bg-white border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs transition border-0 cursor-pointer">
                    Cancel
                </button>
                <template x-if="!selectedExpense || selectedExpense.status === 'Draft'">
                    <button type="submit" form="site-expense-form" name="submit_action" value="draft" class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300/80 font-extrabold text-xs transition cursor-pointer flex items-center gap-1.5 shadow-2xs">
                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-700"></i>
                        <span>Save as Draft</span>
                    </button>
                </template>
                <button type="submit" form="site-expense-form" name="submit_action" value="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] text-white font-black text-xs shadow-md uppercase tracking-wider transition cursor-pointer flex items-center gap-2 border-0">
                    <i data-lucide="check" class="w-4 h-4 text-white"></i>
                    <span x-text="selectedExpense ? 'Update Expense' : 'Save & Approve'">Save & Approve</span>
                </button>
            </div>

        </div>
    </div>

    {{-- ULTRA-EXECUTIVE POPUP MODAL FOR VIEWING SITE EXPENSE VOUCHER DETAILS --}}
    <div x-show="showViewModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        
        {{-- Backdrop blur overlay --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" @click="showViewModal = false"></div>

        {{-- Modal Dialog Container --}}
        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden z-10 my-auto flex flex-col max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            {{-- Modal Header Bar --}}
            <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                TABASCO HINDUSTAN · SITE EXPENSE MANAGEMENT
                            </p>
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider shadow-2xs"
                                  :class="{
                                      'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': selectedExpense?.status === 'Approved',
                                      'bg-amber-500/20 text-amber-300 border border-amber-500/30': selectedExpense?.status === 'Draft' || selectedExpense?.status === 'Pending',
                                      'bg-rose-500/20 text-rose-300 border border-rose-500/30': selectedExpense?.status === 'Rejected'
                                  }"
                                  x-text="selectedExpense?.status || 'Approved'">
                            </span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-white uppercase tracking-wider" 
                            x-text="'View Voucher — ' + (selectedExpense?.voucher_number || '')"></h2>
                    </div>
                    <button type="button" @click="showViewModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer border-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 sm:p-6 space-y-4 sm:space-y-5 text-xs sm:text-sm bg-slate-50/70 overflow-y-auto flex-1">
                
                {{-- Top Details & Financial Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                    {{-- LEFT COLUMN: VOUCHER DETAILS & JOURNAL POSTING (7 Cols) --}}
                    <div class="lg:col-span-7 space-y-5">
                        
                        {{-- Voucher Details Card --}}
                        <div class="p-4.5 sm:p-5 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3.5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                                <h4 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="file-check" class="w-4.5 h-4.5 text-[#a38c29]"></i>
                                    Voucher Metadata & Payee
                                </h4>
                                <span x-text="selectedExpense?.status || 'Approved'" 
                                      class="px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-2xs"
                                      :class="{
                                          'bg-emerald-100 text-emerald-800 border border-emerald-200': selectedExpense?.status === 'Approved',
                                          'bg-amber-100 text-amber-800 border border-amber-200': selectedExpense?.status === 'Draft' || selectedExpense?.status === 'Pending',
                                          'bg-rose-100 text-rose-800 border border-rose-200': selectedExpense?.status === 'Rejected'
                                      }">
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-xs sm:text-sm">
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Voucher Number</span>
                                    <span class="font-mono font-black text-slate-900 block" x-text="selectedExpense?.voucher_number || 'EXP-2026-0003'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Voucher Date</span>
                                    <span class="font-bold text-slate-900 block" x-text="selectedExpense?.voucher_date || '09 Sep 2026'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Project Name</span>
                                    <span class="font-bold text-slate-900 block" x-text="selectedExpense?.project_name || 'Tabasco Hindustan Infra Developers Pvt. Ltd'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Payee Name</span>
                                    <span class="font-black text-slate-900 block" x-text="selectedExpense?.payee_name || 'Basheer'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Payee Type</span>
                                    <span class="font-bold text-blue-600 block" x-text="selectedExpense?.payee_type || 'Registered Payee'"></span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Expense Category</span>
                                    <span class="font-extrabold text-slate-900 block" x-text="selectedExpense?.category_name || '4003 - Agent Commission Expense'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Payment Source Account</span>
                                    <span class="font-bold text-slate-800 block" x-text="selectedExpense?.payment_source || 'HDFC - A/c 0678'"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Transaction Ref / UTR</span>
                                    <span class="font-mono font-bold text-slate-800 block" x-text="selectedExpense?.transaction_ref || 'JCB/0525/0148'"></span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100">
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Narration / Particulars</span>
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-700 text-xs leading-relaxed" x-text="selectedExpense?.narration || 'JCB rental for excavation work – Block A (Month of May 2025)'"></div>
                            </div>
                        </div>

                        {{-- Double-Entry Journal Posting Card --}}
                        <div class="p-4.5 sm:p-5 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3.5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                                <h4 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="book-open" class="w-4.5 h-4.5 text-blue-600"></i>
                                    Double-Entry Journal Posting
                                </h4>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-md uppercase tracking-wider border border-emerald-200/60">Auto-Posted</span>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-slate-100">
                                <table class="w-full text-xs text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-100/70 text-slate-600 font-extrabold uppercase text-[10px]">
                                            <th class="py-3 px-4">Particulars Account</th>
                                            <th class="py-3 px-4 text-right">Debit (Dr ₹)</th>
                                            <th class="py-3 px-4 text-right">Credit (Cr ₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-semibold text-xs">
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="py-3 px-4 font-bold text-slate-900" x-text="selectedExpense?.category_name || 'Site Expense Account'"></td>
                                            <td class="py-3 px-4 text-right font-mono font-black text-slate-900" x-text="'₹ ' + (selectedExpense?.net_amount || '531,000.00')"></td>
                                            <td class="py-3 px-4 text-right font-mono text-slate-400">-</td>
                                        </tr>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="py-3 px-4 font-bold text-slate-900" x-text="selectedExpense?.payment_source || 'Bank / Loan Account'"></td>
                                            <td class="py-3 px-4 text-right font-mono text-slate-400">-</td>
                                            <td class="py-3 px-4 text-right font-mono font-black text-slate-900" x-text="'₹ ' + (selectedExpense?.net_amount || '531,000.00')"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN: FINANCIAL BREAKDOWN & ATTACHMENT (5 Cols) --}}
                    <div class="lg:col-span-5 space-y-5">
                        
                        {{-- Financial Breakdown Card --}}
                        <div class="p-4.5 sm:p-5 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3.5">
                            <h4 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                                <i data-lucide="calculator" class="w-4.5 h-4.5 text-[#a38c29]"></i>
                                Financial Breakdown
                            </h4>

                            <div class="space-y-3 text-xs sm:text-sm">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="font-bold">Gross Amount</span>
                                    <span class="font-mono font-black text-slate-900 text-sm" x-text="'₹ ' + (selectedExpense?.gross_amount || '0.00')"></span>
                                </div>
                                <template x-if="parseFloat(selectedExpense?.gst_amount?.replace(/,/g, '') || 0) > 0">
                                    <div class="flex items-center justify-between text-slate-600">
                                        <span class="font-bold">GST Tax Amount</span>
                                        <span class="font-mono font-bold text-slate-800" x-text="'₹ ' + selectedExpense.gst_amount"></span>
                                    </div>
                                </template>
                                
                                {{-- Total Net Paid Executive Box --}}
                                <div class="p-3.5 rounded-xl bg-[#a38c29] text-white shadow-md flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-xs font-black uppercase tracking-wider block opacity-95">Total Net Paid</span>
                                        <span class="text-[10px] opacity-80 font-bold uppercase tracking-wider">Incl. Taxes & Levies</span>
                                    </div>
                                    <span class="font-mono text-lg sm:text-xl font-black text-white shrink-0" x-text="selectedExpense?.net_amount ? ('₹ ' + selectedExpense.net_amount) : '₹ 0.00'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Attachment Document Card --}}
                        <div class="p-4.5 sm:p-5 rounded-2xl bg-white shadow-xs border border-slate-200/80 space-y-3.5">
                            <h4 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3.5">
                                <i data-lucide="paperclip" class="w-4.5 h-4.5 text-blue-600"></i>
                                Attachment Document
                            </h4>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3 truncate">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/60 text-[#a38c29] flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                    </div>
                                    <div class="truncate">
                                        <span class="font-extrabold text-slate-800 text-xs block truncate" x-text="selectedExpense?.attachment_url && selectedExpense?.attachment_url !== '#' ? 'Voucher_Receipt.pdf' : 'Site_Expense_Bill.pdf'">Voucher_Receipt.pdf</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">PDF Document</span>
                                    </div>
                                </div>
                                <a :href="selectedExpense?.attachment_url || '#'" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] text-white font-black text-xs shrink-0 transition shadow-sm shadow-[#a38c29]/20 flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                    <span>View Document</span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Modal Footer Bar --}}
            <div class="px-6 py-3.5 bg-white flex items-center justify-between shrink-0">
                <span class="text-xs text-slate-400 font-semibold">HindustanERP • Site Expense Management</span>
                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            @click="showViewModal = false; openConfirmModal('reject', selectedExpense?.id, selectedExpense?.voucher_number)" 
                            class="px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 font-extrabold text-xs transition cursor-pointer flex items-center gap-1.5 border border-amber-200">
                        <i data-lucide="x-circle" class="w-4 h-4 text-amber-600"></i>
                        <span>Reject</span>
                    </button>

                    <template x-if="selectedExpense?.status !== 'Approved'">
                        <button type="button" 
                                @click="showViewModal = false; openConfirmModal('approve', selectedExpense?.id, selectedExpense?.voucher_number)" 
                                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs transition cursor-pointer flex items-center gap-1.5 shadow-sm border-0">
                            <i data-lucide="check-circle" class="w-4 h-4 text-white"></i>
                            <span>Approve</span>
                        </button>
                    </template>

                    <button type="button" @click="showViewModal = false; openEditModal(selectedExpense);" class="px-5 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] text-white font-black text-xs transition cursor-pointer flex items-center gap-2 shadow-sm shadow-[#a38c29]/20 border-0">
                        <i data-lucide="pencil" class="w-4 h-4 text-white"></i>
                        <span>Edit Expense</span>
                    </button>
                    <button type="button" @click="showViewModal = false" class="px-6 py-2.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-extrabold text-xs transition cursor-pointer border-0">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- CUSTOM DESIGNED CONFIRMATION MODAL (REJECT / DELETE / APPROVE) --}}
    <div x-show="showConfirmModal"
         x-cloak
         x-transition.opacity
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="background: rgba(2,6,23,0.65); backdrop-filter: blur(4px);">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden" @click.away="showConfirmModal = false">

            {{-- Unified Executive Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 border-b border-amber-500/20">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            <span x-text="confirmType === 'approve' ? 'Action Confirmation' : 'Security Verification'"></span>
                        </p>
                        <h2 class="text-sm sm:text-base font-extrabold text-white uppercase tracking-wider"
                            x-text="confirmType === 'reject' ? 'Reject Voucher' : (confirmType === 'delete' ? 'Delete Site Expense' : 'Approve Voucher')"></h2>
                    </div>
                    <button type="button" @click="showConfirmModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer border-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3">

                    {{-- Voucher Reference --}}
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Voucher No.</span>
                        <span class="font-mono font-black text-xs text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg" x-text="confirmVoucherNumber"></span>
                    </div>

                    {{-- Message per type --}}
                    <template x-if="confirmType === 'reject'">
                        <p class="text-sm text-slate-700">Are you sure you want to <strong class="text-amber-700">reject and send back</strong> this site expense voucher? It will revert to draft status for correction.</p>
                    </template>
                    <template x-if="confirmType === 'delete'">
                        <div>
                            <p class="text-sm text-slate-700">Are you sure you want to <strong class="text-rose-700">permanently delete</strong> this site expense record?</p>
                            <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide mt-1">This action cannot be undone and will remove the record.</p>
                        </div>
                    </template>
                    <template x-if="confirmType === 'approve'">
                        <p class="text-sm text-slate-700">Are you sure you want to <strong class="text-emerald-700">approve and post</strong> this site expense voucher? This will register the ledger entry.</p>
                    </template>

                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                <button type="button" @click="showConfirmModal = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer bg-white">
                    Cancel
                </button>

                <button x-show="confirmType === 'reject'" type="button"
                        @click="document.getElementById('reject-form-' + confirmExpenseId).submit()"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer border-0">
                    Reject Voucher
                </button>

                <button x-show="confirmType === 'delete'" type="button"
                        @click="document.getElementById('delete-form-' + confirmExpenseId).submit()"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer border-0">
                    Confirm Delete
                </button>

                <button x-show="confirmType === 'approve'" type="button"
                        @click="document.getElementById('approve-form-' + confirmExpenseId).submit()"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md cursor-pointer border-0">
                    Approve Voucher
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
