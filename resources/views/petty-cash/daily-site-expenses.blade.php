@extends('layouts.erp')

@section('title', 'Daily Site Expenses - Hindustan Real Estate ERP')

@section('content')
<div class="p-6 h-full overflow-y-auto" x-data="{ showExpenseModal: {{ session('show_expense_modal') || $errors->any() ? 'true' : 'false' }} }">
    <div class="w-full space-y-6">
        
        <!-- Header Card (Breadcrumb & Title) -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                <span class="hover:text-gray-600 cursor-pointer">Home</span>
                <span class="mx-2">></span>
                <span class="hover:text-gray-600 cursor-pointer">Petty Cash & Site Expense</span>
                <span class="mx-2">></span>
                <span class="text-[#a38c29]">Daily Site Expenses</span>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-[#a38c29]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-800 m-0">Daily Site Expenses</h3>
                    <span class="bg-[#e6f4ea] text-[#1e8e3e] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ml-2">REAL-TIME TRACKING</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="#" class="flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded text-[11px] font-bold text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Import Expenses
                    </a>
                    <button type="button" @click="showExpenseModal = true" class="flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-[#a38c29] to-[#8f7a22] text-white text-[11px] font-bold rounded hover:shadow-lg transition-colors uppercase tracking-wider shadow-sm border border-[#8f7a22]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Expense
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Bar (Separate Section) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-4">
            <form action="{{ route('petty-cash.daily-site-expenses') }}" method="GET" class="flex flex-wrap items-end gap-4">
                
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Site</label>
                    <select name="project_id" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $selectedProject == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-[140px]">
                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">From Date</label>
                    <div class="relative">
                        <input type="date" name="from_date" value="{{ $fromDate }}" class="w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                    </div>
                </div>

                <div class="w-[140px]">
                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">To Date</label>
                    <div class="relative">
                        <input type="date" name="to_date" value="{{ $toDate }}" class="w-full bg-white border border-gray-200 rounded-lg pl-3 pr-8 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                    </div>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Category</label>
                    <select name="category" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                        <option value="All" {{ $category == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Refreshments" {{ $category == 'Refreshments' ? 'selected' : '' }}>Refreshments</option>
                        <option value="Transport" {{ $category == 'Transport' ? 'selected' : '' }}>Transport</option>
                        <option value="Minor Tools" {{ $category == 'Minor Tools' ? 'selected' : '' }}>Minor Tools</option>
                        <option value="Stationery" {{ $category == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                        <option value="Labour Welfare" {{ $category == 'Labour Welfare' ? 'selected' : '' }}>Labour Welfare</option>
                        <option value="Electrical Material" {{ $category == 'Electrical Material' ? 'selected' : '' }}>Electrical Material</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Payment Mode</label>
                    <select name="payment_mode" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                        <option value="All" {{ $paymentMode == 'All' ? 'selected' : '' }}>All</option>
                        @if(isset($paymentModes))
                            @foreach($paymentModes as $mode)
                                <option value="{{ $mode->name }}" {{ $paymentMode == $mode->name ? 'selected' : '' }}>{{ $mode->name }}</option>
                            @endforeach
                        @else
                            <option value="Cash" {{ $paymentMode == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="UPI" {{ $paymentMode == 'UPI' ? 'selected' : '' }}>UPI</option>
                        @endif
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="h-9 px-6 bg-gradient-to-r from-slate-800 to-slate-700 text-white text-[12px] font-bold rounded-lg hover:shadow-lg hover:from-slate-700 hover:to-slate-600 transition-all border border-slate-600">
                        Filter
                    </button>
                    <a href="{{ route('petty-cash.daily-site-expenses') }}" class="h-9 px-4 bg-white text-gray-600 text-[12px] font-bold rounded-lg border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition-all flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            


            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8f7a22]">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Voucher No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Particulars</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Payment Mode</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider">Bill No.</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-right">Amount (₹)</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-[#f8f9fa] transition-colors group">
                            <td class="px-6 py-4 text-[12px] font-medium text-gray-600">
                                {{ \Carbon\Carbon::parse($expense->transaction_date)->format('d-M-Y') }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-bold text-[#a38c29]">
                                {{ $expense->voucher_number }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-medium text-gray-700">
                                {{ $category !== 'All' ? $category : (explode('-', $expense->narration)[0] ?? 'General') }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-medium text-gray-600 truncate max-w-[200px]" title="{{ $expense->narration }}">
                                {{ $expense->narration }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-medium text-gray-600">
                                {{ $expense->payment_mode ?? 'Cash' }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-medium text-gray-500">
                                {{ $expense->reference_no ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-[12px] font-bold text-gray-900 text-right">
                                {{ number_format($expense->cash_out, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 text-[#a38c29] hover:bg-[#a38c29]/10 rounded-md transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-[13px] font-bold text-gray-500">No expenses found</p>
                                    <p class="text-[11px] text-gray-400 mt-1">Adjust your filters or add a new expense.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($expenses->count() > 0)
                    <tfoot>
                        <tr class="bg-gray-50 border-t border-gray-200">
                            <td colspan="6" class="px-6 py-3 text-[11px] font-bold text-gray-700">Total</td>
                            <td class="px-6 py-3 text-[13px] font-extrabold text-[#a38c29] text-right">{{ number_format($totalAmount, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination (Mocked for UI matching) -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-white">
                <p class="text-[11px] text-gray-500 font-medium">
                    Showing {{ $expenses->firstItem() ?? 0 }} to {{ $expenses->lastItem() ?? 0 }} of {{ $expenses->total() }} entries
                </p>
                <div class="flex items-center gap-1">
                    {{ $expenses->links('pagination::tailwind') ?? '' }}
                </div>
            </div>
            
        </div>
    </div>

    <!-- New Expense Modal -->
    <div x-show="showExpenseModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showExpenseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showExpenseModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showExpenseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full border border-gray-200">
                
                <form action="{{ route('petty-cash.store-expense') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Header -->
                    <div class="relative overflow-hidden rounded-t-xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0 border-b border-slate-700">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Petty Cash Expense Entry</p>
                                <h3 class="text-lg font-extrabold text-white">Add New Expense</h3>
                            </div>
                            <button type="button" @click="showExpenseModal = false" class="text-slate-400 hover:text-white transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row h-full">
                        <!-- Left Form Column -->
                        <div class="flex-1 p-6 space-y-5">
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Voucher No -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Voucher No.</label>
                                    <input type="text" name="voucher_number" required value="EXP-{{ rand(1000, 9999) }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none cursor-not-allowed">
                                </div>
                                <!-- Date -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}" class="w-full bg-white border @error('transaction_date') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                    @error('transaction_date') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <!-- Site -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Site <span class="text-red-500">*</span></label>
                                    <select name="project_id" required class="w-full bg-white border @error('project_id') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <!-- Category -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                                    <select name="category" required class="w-full bg-white border @error('category') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        <option value="Refreshments" {{ old('category') == 'Refreshments' ? 'selected' : '' }}>Refreshments</option>
                                        <option value="Transport" {{ old('category') == 'Transport' ? 'selected' : '' }}>Transport</option>
                                        <option value="Minor Tools" {{ old('category') == 'Minor Tools' ? 'selected' : '' }}>Minor Tools</option>
                                        <option value="Stationery" {{ old('category') == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                                        <option value="Labour Welfare" {{ old('category') == 'Labour Welfare' ? 'selected' : '' }}>Labour Welfare</option>
                                        <option value="Electrical Material" {{ old('category') == 'Electrical Material' ? 'selected' : '' }}>Electrical Material</option>
                                        <option value="Others" {{ old('category') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('category') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Bill No -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Bill No.</label>
                                    <input type="text" name="bill_no" placeholder="e.g. BILL-88" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                </div>
                                <!-- Bill Date -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Bill Date</label>
                                    <input type="date" name="bill_date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                </div>
                                <!-- Amount -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Amount (₹) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="amount" required value="{{ old('amount') }}" placeholder="0.00" class="w-full bg-white border @error('amount') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-bold text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                    @error('amount') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <!-- Payment Mode -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Payment Mode <span class="text-red-500">*</span></label>
                                    <select name="payment_mode" required class="w-full bg-white border @error('payment_mode') border-red-500 @else border-gray-200 @enderror rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode->name }}" {{ old('payment_mode') == $mode->name ? 'selected' : '' }}>{{ $mode->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_mode') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Attach Bill -->
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Attach Bill / Receipt</label>
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-1">
                                            <input type="file" name="attachment" id="attachment" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">
                                            <div class="w-full bg-gray-50 border border-dashed border-gray-300 rounded-lg px-3 h-9 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span id="file-name" class="text-[11px] font-medium text-gray-500 truncate">Select file...</span>
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-gray-400 mt-1 whitespace-nowrap">JPG, PNG, PDF up to 2MB</p>
                                    </div>
                                </div>
                                
                                <!-- Particulars -->
                                <div class="col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Particulars <span class="text-red-500">*</span></label>
                                    <textarea name="particulars" required rows="2" placeholder="Enter expense details (e.g. Tea & Snacks for Site Team)" class="w-full bg-white border @error('particulars') border-red-500 @else border-gray-200 @enderror rounded-lg p-3 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all resize-none">{{ old('particulars') }}</textarea>
                                    @error('particulars') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Sidebar Column -->
                        <div class="w-full md:w-[320px] bg-slate-50 border-l border-gray-200 p-6 flex flex-col gap-6">
                            
                            <!-- Available Balance -->
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                                <div class="absolute right-0 top-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                                <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 relative z-10">Available Petty Cash Balance</h4>
                                <div class="text-2xl font-black text-green-600 relative z-10 mb-1">
                                    ₹ {{ number_format($availableBalance, 2) }}
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium relative z-10">
                                    (As on {{ date('d-M-Y H:i A') }})
                                </p>
                            </div>

                            <!-- Category Summary -->
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex-1">
                                <h4 class="text-[11px] font-bold text-gray-800 mb-3">Expense Category Summary (This Month)</h4>
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="text-left text-[10px] font-bold text-gray-500 pb-2">Category</th>
                                            <th class="text-right text-[10px] font-bold text-gray-500 pb-2">Amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @php $catTotal = 0; @endphp
                                        @foreach($categorySummary as $catName => $catAmount)
                                            @php $catTotal += $catAmount; @endphp
                                            <tr>
                                                <td class="text-[11px] font-medium text-gray-700 py-2">{{ $catName }}</td>
                                                <td class="text-[11px] font-bold text-gray-900 text-right py-2">{{ number_format($catAmount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-200 bg-gray-50/50">
                                            <td class="text-[11px] font-bold text-gray-900 py-2">Total</td>
                                            <td class="text-[11px] font-bold text-[#a38c29] text-right py-2">{{ number_format($catTotal, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <button type="button" @click="showExpenseModal = false" class="px-5 py-2 text-[12px] font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <div class="flex gap-3">
                            <button type="submit" name="submit_action" value="save_new" class="px-5 py-2 text-[12px] font-bold text-[#a38c29] bg-white border border-[#a38c29] rounded-lg hover:bg-[#fbfaf5] transition-colors">
                                Save & New
                            </button>
                            <button type="submit" name="submit_action" value="save_post" class="px-5 py-2 text-[12px] font-bold text-white bg-gradient-to-r from-[#a38c29] to-[#8f7a22] border border-[#8f7a22] rounded-lg hover:shadow-lg transition-all">
                                Save & Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
