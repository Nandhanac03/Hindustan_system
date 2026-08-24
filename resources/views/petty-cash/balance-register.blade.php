@extends('layouts.erp')

@section('content')
<div class="w-full px-6 py-6 bg-[#f8f9fa] min-h-screen font-sans">
    
    <!-- Header Card (Breadcrumb & Title) -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6 shadow-sm">
        <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
            <span class="hover:text-gray-600 cursor-pointer">Home</span>
            <span class="mx-2">></span>
            <span class="hover:text-gray-600 cursor-pointer">Petty Cash & Site Expense</span>
            <span class="mx-2">></span>
            <span class="text-[#a38c29]">Petty Cash Balance Register</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="text-[#a38c29]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-[18px] font-bold text-gray-800 m-0">Petty Cash Balance Register</h3>
                <span class="bg-[#e6f4ea] text-[#1e8e3e] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ml-2">Real-time Tracking</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded text-[11px] font-bold text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-wider shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </a>
                <button class="flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded text-[11px] font-bold text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-wider shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div id="petty-cash-content" class="relative">
        <!-- Metrics (Border-left style cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Opening Balance (Blue) -->
            <!-- Opening Balance (Blue) -->
            <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-blue-500 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-200">
                <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-1">Opening Balance</p>
                <h4 class="text-[22px] font-bold text-blue-700 m-0">₹ {{ number_format($openingBalance, 2) }}</h4>
                <p class="text-[10px] text-gray-500 mt-1">From Previous Day</p>
            </div>
            <!-- Cash In (Green) -->
            <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
                <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Cash In (Today)</p>
                <h4 class="text-[22px] font-bold text-[#10b981] m-0">₹ {{ number_format($cashIn, 2) }}</h4>
                <p class="text-[10px] text-gray-500 mt-1">Bank Withdrawals & Receipts</p>
            </div>
            <!-- Cash Out (Red) -->
            <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#ef4444] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#ef4444]/30">
                <p class="text-[11px] font-bold text-[#ef4444] uppercase tracking-wider mb-1">Cash Out (Today)</p>
                <h4 class="text-[22px] font-bold text-[#ef4444] m-0">₹ {{ number_format($cashOut, 2) }}</h4>
                <p class="text-[10px] text-gray-500 mt-1">Site Expenses</p>
            </div>
            <!-- Closing Balance (Gray/Black) -->
            <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-gray-700 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-gray-300 group">
                <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1 group-hover:text-[#a38c29] transition-colors duration-300">Closing Balance</p>
                <h4 class="text-[22px] font-bold text-gray-800 m-0 group-hover:text-[#a38c29] transition-colors duration-300">₹ {{ number_format($closingBalance, 2) }}</h4>
                <p class="text-[10px] text-gray-500 mt-1">Current Cash in Hand</p>
            </div>
        </div>

        <!-- Summaries Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Petty Cash Summary -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Petty Cash Summary</h5>
                <table class="w-full text-[12px]">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Site</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $siteName }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Cash Box / Incharge</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $cashBoxIncharge }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Cash Box Code</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $cashBoxCode }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Last Updated</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $lastUpdated }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Updated By</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">{{ $updatedBy }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Today's Transaction Summary -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Today's Transaction Summary</h5>
                <table class="w-full text-[12px]">
                    <thead>
                        <tr>
                            <th class="pb-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Particulars</th>
                            <th class="pb-2 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        <tr>
                            <td class="py-3 font-bold text-gray-700 uppercase text-[10px] tracking-wider">Bank Withdrawal (Contra)</td>
                            <td class="py-3 text-right text-[#10b981] font-bold">+ {{ number_format($bankWithdrawal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 font-bold text-gray-700 uppercase text-[10px] tracking-wider">Site Expenses</td>
                            <td class="py-3 text-right text-[#ef4444] font-bold">- {{ number_format($siteExpenses, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td class="pt-4 font-bold text-gray-800 uppercase text-[11px] tracking-wider">Net Cash Flow</td>
                            <td class="pt-4 text-right font-bold text-[14px] {{ $netCashFlow >= 0 ? 'text-[#10b981]' : 'text-[#ef4444]' }}">
                                {{ $netCashFlow >= 0 ? '+' : '' }} {{ number_format($netCashFlow, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Balance Snapshot -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm h-full flex flex-col">
                <h5 class="text-[12px] font-extrabold text-[#a38c29] uppercase tracking-wider mb-4 border-b border-[#a38c29]/20 pb-2">Balance Snapshot</h5>
                <table class="w-full text-[12px]">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Opening Balance</td>
                            <td class="py-2.5 text-right font-bold text-[#111827]">₹ {{ number_format($openingBalance, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Add: Cash In (Today)</td>
                            <td class="py-2.5 text-right font-bold text-[#10b981]">₹ {{ number_format($cashIn, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Less: Cash Out (Today)</td>
                            <td class="py-2.5 text-right font-bold text-[#ef4444]">₹ {{ number_format($cashOut, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td class="pt-4 font-bold text-gray-800 uppercase text-[11px] tracking-wider">Closing Balance</td>
                            <td class="pt-4 text-right font-bold text-[14px] text-[#a38c29]">₹ {{ number_format($closingBalance, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Top Filter Area (Matching Units Module Structure) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 mb-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
            <form method="GET" action="{{ route('petty-cash.balance-register') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full m-0" id="filter-form">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
                    {{-- Pro Light Search Input --}}
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Search Voucher..." 
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-sm">
                    </div>

                    {{-- Site Dropdown --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <select name="project_id"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm appearance-none">
                            <option value="">All Sites</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <select name="status"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm appearance-none">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Date Filter --}}
                    <div class="relative">
                        <input type="date" name="date" value="{{ $selectedDate }}"
                               class="w-full px-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-sm appearance-none">
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <button type="reset"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reset Filters</span>
                </button>
            </form>
        </div>

        <!-- Recent Transactions Table (Theme matched) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8 relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-[#a38c29] text-white">
                        <tr>
                            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider">Date</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider">Voucher No.</th>
                            <th class="px-5 py-3.5 text-[10px] font-bold uppercase tracking-wider">Type</th>
                            <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider">Cash In (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider">Cash Out (₹)</th>
                            <th class="px-5 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider">Balance (₹)</th>
                            <th class="px-5 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($transactions as $txn)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-[11px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($txn->date)->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-[11px] font-bold text-[#10b981] uppercase">{{ $txn->voucher_number }}</span>
                                </td>
                                <td class="px-5 py-4 text-[11px] font-bold text-gray-600 uppercase">{{ $txn->type_label }}</td>
                                <td class="px-5 py-4 text-right text-[12px] font-bold text-gray-700">
                                    {{ $txn->cash_in > 0 ? '₹' . number_format($txn->cash_in, 2) : 'N/A' }}
                                </td>
                                <td class="px-5 py-4 text-right text-[12px] font-bold text-gray-700">
                                    {{ $txn->cash_out > 0 ? '₹' . number_format($txn->cash_out, 2) : 'N/A' }}
                                </td>
                                <td class="px-5 py-4 text-right text-[12px] font-bold text-[#10b981]">₹{{ number_format($txn->balance, 2) }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center justify-center text-[10px] font-bold text-[#10b981] bg-[#ecfdf5] px-2 py-0.5 rounded-full lowercase tracking-wide">
                                        active
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <a href="#" class="w-6 h-6 flex items-center justify-center bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 transition-colors" title="View Details">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-[12px] font-bold text-gray-400 uppercase tracking-wider">No transactions found for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');
            const contentContainer = document.getElementById('petty-cash-content');

            if (filterForm && contentContainer) {
                let fetchController = null;

                function fetchData() {
                    const url = new URL(filterForm.action);
                    const formData = new FormData(filterForm);
                    const searchParams = new URLSearchParams(formData);
                    url.search = searchParams.toString();

                    if (fetchController) {
                        fetchController.abort();
                    }
                    fetchController = new AbortController();

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: fetchController.signal
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newContent = doc.getElementById('petty-cash-content');
                        if (newContent) {
                            contentContainer.innerHTML = newContent.innerHTML;
                        }
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Error fetching data:', error);
                        }
                    });
                }

                // Debounce helper
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func(...args), wait);
                    };
                }

                const debouncedFetch = debounce(fetchData, 400);

                // Auto-fetch on change
                document.body.addEventListener('change', function(e) {
                    if (e.target.closest('#filter-form') && (e.target.tagName === 'SELECT' || e.target.type === 'date')) {
                        fetchData();
                    }
                });

                document.body.addEventListener('input', function(e) {
                    if (e.target.closest('#filter-form') && e.target.name === 'search') {
                        debouncedFetch();
                    }
                });

                document.body.addEventListener('submit', function(e) {
                    if (e.target.id === 'filter-form') {
                        e.preventDefault();
                        fetchData();
                    }
                });
                
                document.body.addEventListener('reset', function(e) {
                    if (e.target.closest('#filter-form')) {
                        setTimeout(() => fetchData(), 10);
                    }
                });
            }
        });
    </script>
</div>
@endsection