@extends('layouts.erp')

@section('title', 'Site Expenses Dashboard')

@section('content')
<div x-data="{ showCreateModal: false }" class="px-4 sm:px-6 lg:px-8 py-6 space-y-6 bg-slate-100 min-h-screen text-slate-800">

    {{-- Top Flash Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    {{-- TOP HEADER BANNER (PURE TABASCO GOLD & WHITE CORPORATE THEME) --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#a38c29]/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-[#a38c29]/15 text-[#7a671b] text-xs font-black uppercase rounded-full tracking-wider border border-[#a38c29]/30">
                    Direct Operational Costs
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight mt-2 flex items-center gap-3 text-slate-900">
                <div class="w-10 h-10 rounded-xl bg-[#a38c29] text-white flex items-center justify-center shadow-md">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                Site Expenses
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 font-semibold mt-1">
                Track and manage all direct site operational expenses (Land, Legal, Machinery Rental, Diesel, Municipal Fees)
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('site-expenses.workflow') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-extrabold rounded-xl border border-[#a38c29]/40 bg-white text-[#7a671b] hover:bg-[#a38c29]/10 shadow-sm transition">
                <i data-lucide="git-merge" class="w-4 h-4 text-[#a38c29]"></i> Interactive 5-Step Workflow
            </a>
            <button @click="showCreateModal = true" type="button" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold rounded-xl bg-[#a38c29] text-white hover:bg-[#8d7923] shadow-md hover:shadow-lg transition cursor-pointer">
                <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i> + New Site Expense
            </button>
        </div>
    </div>

    {{-- TOP ROW: 5 KEY METRIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        {{-- Card 1: Total Expenses (YTD) --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 border-t-4 border-t-[#a38c29] shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Total Expenses (YTD)</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">₹ {{ number_format($totalAmount, 2) }}</h3>
                <p class="text-xs text-emerald-700 font-bold mt-1 flex items-center gap-1">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i> 12.6% vs Last Month
                </p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-[#a38c29]/15 text-[#8a741f] flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Card 2: Approved Expenses --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 border-t-4 border-t-blue-500 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Approved Expenses</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">₹ {{ number_format($approvedAmount, 2) }}</h3>
                <p class="text-xs text-blue-700 font-extrabold mt-1">90.6% of Total</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Card 3: Pending Approval --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 border-t-4 border-t-amber-500 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Pending Approval</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">₹ {{ number_format($pendingAmount, 2) }}</h3>
                <p class="text-xs text-amber-700 font-extrabold mt-1">9.4% of Total</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Card 4: This Month Expenses --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 border-t-4 border-t-purple-500 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">This Month Expenses</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">₹ {{ number_format($thisMonthExpenses, 2) }}</h3>
                <p class="text-xs text-emerald-700 font-bold mt-1 flex items-center gap-1">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i> 8.3% vs Last Month
                </p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Card 5: Budget Utilization --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 border-t-4 border-t-rose-500 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Budget Utilization</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $budgetUtilizationPct }}%</h3>
                <p class="text-xs text-slate-600 font-semibold mt-1">of ₹ {{ number_format($budgetTotal) }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-800 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="target" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    {{-- MAIN DASHBOARD CONTENT: CATEGORY BREAKDOWN BANNER + FULL WIDTH TABLE --}}
    <div class="space-y-6">
        
        {{-- TOP SUMMARY: EXPENSE CATEGORY SUMMARY (THIS MONTH) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4 text-xs">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-[#a38c29]"></i> Expense Category Summary (This Month)
                </h3>
                <span class="text-xs text-slate-600 font-bold">Total: <span class="font-black text-slate-900 font-mono text-sm">₹ 4,31,500.00</span></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <div class="p-3.5 bg-blue-50/80 border-l-4 border-l-blue-600 border border-blue-200 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-blue-950 font-extrabold text-xs">
                        <span>Land & Reg.</span>
                        <span class="text-[10px] bg-blue-200 text-blue-900 px-1.5 py-0.5 rounded font-bold">46.3%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 2,00,000</p>
                    <p class="text-[10px] text-slate-600 font-medium">Land & Legal Fees</p>
                </div>

                <div class="p-3.5 bg-emerald-50/80 border-l-4 border-l-emerald-600 border border-emerald-200 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-emerald-950 font-extrabold text-xs">
                        <span>Site Office</span>
                        <span class="text-[10px] bg-emerald-200 text-emerald-900 px-1.5 py-0.5 rounded font-bold">32.3%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 1,39,620</p>
                    <p class="text-[10px] text-slate-600 font-medium">Office & Admin</p>
                </div>

                <div class="p-3.5 bg-amber-50/80 border-l-4 border-l-[#a38c29] border border-[#a38c29]/30 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-[#7a671b] font-black text-xs">
                        <span>Machinery</span>
                        <span class="text-[10px] bg-[#a38c29]/20 text-[#7a671b] px-1.5 py-0.5 rounded font-bold">12.3%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 53,100</p>
                    <p class="text-[10px] text-slate-600 font-medium">Rental & JCB</p>
                </div>

                <div class="p-3.5 bg-amber-50/80 border-l-4 border-l-amber-600 border border-amber-200 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-amber-950 font-extrabold text-xs">
                        <span>Fuel & Power</span>
                        <span class="text-[10px] bg-amber-200 text-amber-900 px-1.5 py-0.5 rounded font-bold">4.7%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 20,240</p>
                    <p class="text-[10px] text-slate-600 font-medium">Diesel & Transport</p>
                </div>

                <div class="p-3.5 bg-purple-50/80 border-l-4 border-l-purple-600 border border-purple-200 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-purple-950 font-extrabold text-xs">
                        <span>Statutory</span>
                        <span class="text-[10px] bg-purple-200 text-purple-900 px-1.5 py-0.5 rounded font-bold">2.4%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 10,540</p>
                    <p class="text-[10px] text-slate-600 font-medium">Municipal & RERA</p>
                </div>

                <div class="p-3.5 bg-slate-100 border-l-4 border-l-slate-500 border border-slate-300 rounded-xl space-y-1 shadow-sm">
                    <div class="flex items-center justify-between text-slate-900 font-extrabold text-xs">
                        <span>Others</span>
                        <span class="text-[10px] bg-slate-200 text-slate-800 px-1.5 py-0.5 rounded font-bold">1.9%</span>
                    </div>
                    <p class="font-mono font-black text-slate-950 text-base">₹ 8,000</p>
                    <p class="text-[10px] text-slate-600 font-medium">Misc Operational</p>
                </div>
            </div>
        </div>

        {{-- MAIN DATA SECTION (FULL WIDTH TABLE) --}}
        <div class="space-y-6">
            
            {{-- STATUS TABS & FILTER BAR --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
                
                {{-- Status Tabs --}}
                <div class="flex border-b border-slate-200 text-xs sm:text-sm font-bold gap-6">
                    <a href="{{ route('site-expenses.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="pb-3 border-b-2 transition {{ $statusTab === 'all' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        All Expenses
                    </a>
                    <a href="{{ route('site-expenses.index', array_merge(request()->query(), ['status' => 'pending'])) }}" 
                       class="pb-3 border-b-2 transition flex items-center gap-2 {{ $statusTab === 'pending' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Pending Approval
                        <span class="px-2 py-0.5 bg-amber-200 text-amber-900 text-xs font-black rounded-full">3</span>
                    </a>
                    <a href="{{ route('site-expenses.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
                       class="pb-3 border-b-2 transition {{ $statusTab === 'approved' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Approved
                    </a>
                    <a href="{{ route('site-expenses.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                       class="pb-3 border-b-2 transition {{ $statusTab === 'rejected' ? 'border-[#a38c29] text-[#a38c29] font-black' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                        Rejected
                    </a>
                </div>

                {{-- Filter Options --}}
                <form action="{{ route('site-expenses.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs items-center">
                    <input type="hidden" name="status" value="{{ $statusTab }}">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Project Filter</label>
                        <select name="project_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2.5 px-3 text-slate-800 focus:ring-2 focus:ring-[#a38c29] shadow-sm">
                            <option value="">All Projects</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Expense Category</label>
                        <select name="category_code" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2.5 px-3 text-slate-800 focus:ring-2 focus:ring-[#a38c29] shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ request('category_code') == $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Payment Source</label>
                        <select name="payment_source_type" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2.5 px-3 text-slate-800 focus:ring-2 focus:ring-[#a38c29] shadow-sm">
                            <option value="">All Payment Sources</option>
                            <option value="bank" {{ request('payment_source_type') == 'bank' ? 'selected' : '' }}>Bank Account</option>
                            <option value="loan" {{ request('payment_source_type') == 'loan' ? 'selected' : '' }}>Loan Account</option>
                        </select>
                    </div>

                    <div class="pt-5">
                        <button type="submit" class="w-full py-2.5 px-4 bg-[#a38c29] text-white rounded-xl font-extrabold hover:bg-[#8d7923] shadow-md transition flex items-center justify-center gap-2 uppercase tracking-wider">
                            <i data-lucide="filter" class="w-4 h-4"></i> Apply Filters
                        </button>
                    </div>
                </form>

            </div>

            {{-- VOUCHERS DATA TABLE (CORPORATE TABASCO GOLD TABLE HEADER) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden text-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-slate-800 border-collapse">
                        <thead class="bg-[#a38c29] text-white font-black uppercase tracking-wider text-[11px] border-b-2 border-[#8a7522]">
                            <tr>
                                <th class="py-3.5 px-4">Date</th>
                                <th class="py-3.5 px-4">Expense Category</th>
                                <th class="py-3.5 px-4">Payee / Vendor</th>
                                <th class="py-3.5 px-4 text-right">Amount (₹)</th>
                                <th class="py-3.5 px-4">Payment Source</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-medium">
                            @forelse($siteExpenses as $expense)
                                <tr class="hover:bg-amber-50/40 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($expense->voucher_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-[#a38c29]/20 text-[#8a741f] flex items-center justify-center shrink-0 border border-[#a38c29]/40">
                                                <i data-lucide="tag" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 block">{{ $expense->expense_category_name }}</span>
                                                <span class="text-[10px] text-slate-500 font-mono font-semibold">{{ $expense->expense_category_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 font-bold text-slate-900">
                                        {{ $expense->payee_display_name }}
                                    </td>

                                    <td class="py-3.5 px-4 text-right font-mono font-black text-slate-950 text-sm">
                                        ₹ {{ number_format($expense->net_amount, 2) }}
                                    </td>

                                    <td class="py-3.5 px-4 font-semibold text-slate-700">
                                        {{ $expense->payment_source_display_name }}
                                    </td>

                                    <td class="py-3.5 px-4">
                                        @if($expense->status === 'Approved')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-900 font-black text-xs rounded-full border border-emerald-300 inline-flex items-center gap-1 shadow-sm">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Approved
                                            </span>
                                        @elseif($expense->status === 'Draft')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-900 font-black text-xs rounded-full border border-amber-300 inline-flex items-center gap-1 shadow-sm">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Pending
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-100 text-rose-900 font-black text-xs rounded-full border border-rose-300 inline-flex items-center gap-1 shadow-sm">
                                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('site-expenses.workflow', ['expense_id' => $expense->id, 'project_id' => $expense->project_id]) }}" title="View Workflow Pipeline" class="p-1.5 text-blue-700 hover:bg-blue-100 rounded-lg transition">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('site-expenses.show', $expense->id) }}" title="View Voucher Details" class="p-1.5 text-slate-600 hover:bg-slate-200 rounded-lg transition">
                                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-700">20/05/2025</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 border border-amber-300">
                                                <i data-lucide="truck" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 block">Machinery & Equipment Rental</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">Local JCB Owner - Rajesh</td>
                                    <td class="py-3.5 px-4 text-right font-mono font-black text-slate-950 text-sm">₹ 53,100.00</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-700">Bank - HDFC</td>
                                    <td class="py-3.5 px-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-900 font-black text-xs rounded-full border border-emerald-300">Approved</span></td>
                                    <td class="py-3.5 px-4 text-center"><i data-lucide="eye" class="w-4.5 h-4.5 inline text-blue-700 cursor-pointer"></i></td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-700">19/05/2025</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-300">
                                                <i data-lucide="building-2" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 block">Site Office & Admin</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">Office Rent</td>
                                    <td class="py-3.5 px-4 text-right font-mono font-black text-slate-950 text-sm">₹ 35,400.00</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-700">Bank - HDFC</td>
                                    <td class="py-3.5 px-4"><span class="px-3 py-1 bg-emerald-100 text-emerald-900 font-black text-xs rounded-full border border-emerald-300">Approved</span></td>
                                    <td class="py-3.5 px-4 text-center"><i data-lucide="eye" class="w-4.5 h-4.5 inline text-blue-700 cursor-pointer"></i></td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-700">18/05/2025</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0 border border-blue-300">
                                                <i data-lucide="landmark" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 block">Statutory & Municipal Fees</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">Municipal Corporation</td>
                                    <td class="py-3.5 px-4 text-right font-mono font-black text-slate-950 text-sm">₹ 1,25,000.00</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-700">Bank - HDFC</td>
                                    <td class="py-3.5 px-4"><span class="px-3 py-1 bg-amber-100 text-amber-900 font-black text-xs rounded-full border border-amber-300">Pending</span></td>
                                    <td class="py-3.5 px-4 text-center"><i data-lucide="eye" class="w-4.5 h-4.5 inline text-blue-700 cursor-pointer"></i></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($siteExpenses->hasPages())
                    <div class="px-5 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-xs">
                        <span class="text-slate-600 font-semibold">Showing {{ $siteExpenses->firstItem() }} to {{ $siteExpenses->lastItem() }} of {{ $siteExpenses->total() }} entries</span>
                        {{ $siteExpenses->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- CREATION MODAL DIALOG MATCHING FORM SPECIFICATION --}}
    <div x-show="showCreateModal" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        
        <div @click.away="showCreateModal = false" class="bg-white rounded-2xl border border-slate-300 shadow-2xl max-w-4xl w-full my-8 overflow-hidden text-slate-800 flex flex-col max-h-[90vh]">
            
            {{-- Black & Gold Shade Header (Matching Add Unit Modal Header) --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-6 py-5 shrink-0 border-b border-[#a38c29]/30">
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-[#a38c29]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] text-white flex items-center justify-center font-black shadow-lg shadow-[#a38c29]/20 border border-[#a38c29]/50 shrink-0">
                            <i data-lucide="plus-circle" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-extrabold text-white tracking-tight">Add New Site Expense</h2>
                                <span class="px-2 py-0.5 text-[9px] bg-[#a38c29]/20 text-[#a38c29] border border-[#a38c29]/40 font-black rounded-full uppercase tracking-wider">Form</span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Direct operational payment (Land, JCB Rental, Diesel, Municipal Fees)</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-white transition p-2 rounded-lg hover:bg-slate-800/60">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            {{-- Form Content --}}
            <form action="{{ route('site-expenses.store') }}" method="POST" enctype="multipart/form-data" 
                  x-data="{ 
                      payeeType: 'registered', 
                      paymentSource: 'bank',
                      gross: 45000,
                      gstPct: 18,
                      get gstAmount() { return (parseFloat(this.gross)||0) * ((parseFloat(this.gstPct)||0)/100); },
                      get total() { return (parseFloat(this.gross)||0) + this.gstAmount; },
                      get amountInWords() {
                          let amt = parseFloat(this.gross)||0;
                          if (amt <= 0) return '';
                          let num = Math.floor(amt);
                          let paise = Math.round((amt - num) * 100);
                          const a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
                          const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                          function inWords(n) {
                              if ((n = n.toString()).length > 9) return '';
                              let n_array = ('000000000' + n).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
                              if (!n_array) return '';
                              let str = '';
                              str += (n_array[1] != 0) ? (a[Number(n_array[1])] || (b[n_array[1][0]] + ' ' + a[n_array[1][1]])) + 'Crore ' : '';
                              str += (n_array[2] != 0) ? (a[Number(n_array[2])] || (b[n_array[2][0]] + ' ' + a[n_array[2][1]])) + 'Lakh ' : '';
                              str += (n_array[3] != 0) ? (a[Number(n_array[3])] || (b[n_array[3][0]] + ' ' + a[n_array[3][1]])) + 'Thousand ' : '';
                              str += (n_array[4] != 0) ? (a[Number(n_array[4])] || (b[n_array[4][0]] + ' ' + a[n_array[4][1]])) + 'Hundred ' : '';
                              str += (n_array[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n_array[5])] || (b[n_array[5][0]] + ' ' + a[n_array[5][1]])) : '';
                              return str;
                          }
                          let words = inWords(num).trim();
                          if (words) words += ' Rupees';
                          if (paise > 0) words += ' and ' + inWords(paise).trim() + ' Paise';
                          return words ? words + ' Only' : '';
                      }
                  }" 
                  class="p-6 space-y-4 text-xs overflow-y-auto flex-1">
                @csrf

                {{-- Expense Details --}}
                <div>
                    <h4 class="font-black text-[#7a671b] border-b border-slate-200 pb-2 text-xs mb-3 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-[#a38c29]"></i> Expense Details
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Project <span class="text-rose-500">*</span></label>
                            <select name="project_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]" required>
                                <option value="">-- Select Project --</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}">
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Tower / Phase</label>
                            <input type="text" name="tower_block_tag" value="Tower A" placeholder="e.g. Tower A" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Expense Category <span class="text-rose-500">*</span></label>
                        <select name="expense_category_code" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]" required>
                            @foreach($expenseCategories as $code => $name)
                                <option value="{{ $code }}" {{ $code == '4020' ? 'selected' : '' }}>
                                    {{ $code }} - {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Expense Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="voucher_date" value="{{ date('Y-m-d') }}" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]" required>
                    </div>
                </div>

                {{-- Payee Type Toggle Box --}}
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-slate-900">Payee Type <span class="text-rose-500">*</span></span>
                        <span class="px-2.5 py-0.5 text-[10px] bg-emerald-200 text-emerald-900 font-extrabold rounded-full">New</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <label class="flex items-start gap-2.5 cursor-pointer bg-white p-3 rounded-xl border border-slate-200 hover:border-[#a38c29] transition shadow-sm">
                            <input type="radio" name="payee_type" value="registered" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29] mt-0.5">
                            <div>
                                <span class="font-black text-slate-900 block">Registered Vendor / Master</span>
                                <span class="text-xs text-slate-500 font-medium">Select from vendor master</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 cursor-pointer bg-white p-3 rounded-xl border border-slate-200 hover:border-[#a38c29] transition shadow-sm">
                            <input type="radio" name="payee_type" value="one_time" x-model="payeeType" class="text-[#a38c29] focus:ring-[#a38c29] mt-0.5">
                            <div>
                                <span class="font-black text-slate-900 block">One-Time / Casual</span>
                                <span class="text-xs text-slate-500 font-medium">Enter name manually</span>
                            </div>
                        </label>
                    </div>

                    <template x-if="payeeType === 'registered'">
                        <div class="pt-1">
                            <label class="block font-bold text-slate-800 mb-1">Select Vendor <span class="text-rose-500">*</span></label>
                            <select name="payee_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                                <option value="">-- Select from Vendor Master Directory --</option>
                                @foreach($payees as $payee)
                                    <option value="{{ $payee->id }}">
                                        {{ $payee->name }} {{ $payee->gstin ? '(GSTIN: '.$payee->gstin.')' : ($payee->phone ? '('.$payee->phone.')' : '(Vendor Master)') }}
                                    </option>
                                @endforeach
                                @if($payees->isEmpty())
                                    <option value="1" selected>Local JCB Owner - Rajesh (GSTIN: 32ABCDE1234F1Z5)</option>
                                    <option value="2">Sub Registrar Office (Government Land Fee)</option>
                                    <option value="3">Kerala Earthmovers & Machinery Hire</option>
                                @endif
                            </select>
                        </div>
                    </template>

                    <template x-if="payeeType === 'one_time'">
                        <div class="pt-1">
                            <label class="block font-bold text-slate-800 mb-1">Payee Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="casual_payee_name" value="Saju Tea Stall" placeholder="Enter name of person / shop / service provider" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-white py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                        </div>
                    </template>
                </div>

                {{-- Voucher Number & Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Bill / Voucher No.</label>
                        <input type="text" name="transaction_reference_no" value="JCB/0525/0148" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Bill Date</label>
                        <input type="date" value="{{ date('Y-m-d') }}" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-800 mb-1">Due Date (Optional)</label>
                        <input type="date" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                    </div>
                </div>

                {{-- Amount, Amount in Words & GST --}}
                <div class="space-y-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="gross_amount" x-model.number="gross" class="w-full text-xs rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 font-mono font-bold focus:ring-2 focus:ring-[#a38c29]" required>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">GST (%)</label>
                            <select x-model.number="gstPct" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                                <option value="0">0%</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18" selected>18%</option>
                                <option value="28">28%</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">GST Amount (₹)</label>
                            <input type="text" readonly :value="gstAmount.toFixed(2)" class="w-full text-xs rounded-xl border-slate-200 bg-slate-100 font-mono font-bold py-2.5 px-3 text-slate-700">
                        </div>
                    </div>

                    {{-- Amount in Words Live Display --}}
                    <div x-show="amountInWords" class="text-xs bg-amber-50 border border-[#a38c29]/40 px-3.5 py-2 rounded-xl font-bold text-[#7a671b] flex items-center justify-between shadow-sm">
                        <span class="text-slate-700 font-bold">In Words:</span>
                        <span class="italic text-slate-950 font-black" x-text="amountInWords">Forty Five Thousand Rupees Only</span>
                    </div>
                </div>

                {{-- Payment Source & Total --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                    <div>
                        <input type="hidden" name="payment_source_type" value="bank">
                        <label class="block font-bold text-slate-800 mb-1">Payment Source <span class="text-rose-500">*</span></label>
                        <select name="company_bank_account_id" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                            @foreach($bankAccounts as $bank)
                                <option value="{{ $bank->id }}">
                                    Bank Account - {{ $bank->bank_name }} ({{ substr($bank->account_number ?? '1234', -4) }})
                                </option>
                            @endforeach
                            @if($bankAccounts->isEmpty())
                                <option value="1" selected>Bank Account - HDFC (1234)</option>
                                <option value="2">Bank Account - Karnataka (1001)</option>
                            @endif
                        </select>
                    </div>

                    <div class="bg-blue-50 p-3.5 rounded-xl border border-blue-200 flex items-center justify-between shadow-sm">
                        <span class="font-extrabold text-blue-950 text-xs">Total Amount Paid (₹):</span>
                        <input type="hidden" name="net_amount" :value="total">
                        <span class="font-black text-blue-950 font-mono text-lg" x-text="'₹ ' + total.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})">₹ 53,100.00</span>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-800 mb-1">Remarks (Optional)</label>
                    <input type="text" name="narration" value="JCB rental for excavation work – Block A (Month of May 2025)" class="w-full text-xs font-semibold rounded-xl border-slate-300 bg-slate-50 py-2.5 px-3 focus:ring-2 focus:ring-[#a38c29]">
                </div>

                {{-- Attach Bill Dropzone --}}
                <div>
                    <label class="block font-bold text-slate-800 mb-1">Attach Bill (Photo / PDF)</label>
                    <div class="p-4 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 text-center hover:bg-slate-100 transition cursor-pointer">
                        <input type="file" name="attachment" id="modal_attachment" class="hidden">
                        <label for="modal_attachment" class="cursor-pointer flex flex-col items-center">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-500 mb-1"></i>
                            <span class="text-xs font-bold text-blue-800">Click to upload or drag and drop</span>
                            <span class="text-[10px] text-slate-500 font-medium">JPG, PNG, PDF (Max 10MB)</span>
                        </label>
                    </div>
                </div>

                {{-- Modal Footer Actions --}}
                <div class="pt-4 border-t border-slate-200 flex items-center justify-between shrink-0">
                    <button type="reset" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                        Reset
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button type="submit" name="submit_action" value="submit" class="px-6 py-2.5 rounded-xl bg-[#a38c29] text-white font-extrabold hover:bg-[#8d7923] shadow-md transition">
                            Save Expense
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
