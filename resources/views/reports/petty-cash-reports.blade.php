@extends('layouts.erp')

@section('title', 'Petty Cash Reports - Hindustan Real Estate ERP')

@section('content')
<div class="p-6 h-full overflow-y-auto">
    <div class="w-full space-y-6">
        
        <!-- Header Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                <span class="hover:text-gray-600 cursor-pointer">Home</span>
                <span class="mx-2">></span>
                <span class="hover:text-gray-600 cursor-pointer">Petty Cash & Site Expense</span>
                <span class="mx-2">></span>
                <span class="text-[#a38c29]">Petty Cash Reports</span>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-[#a38c29]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-800 m-0">Petty Cash Reports</h3>
                    <span class="bg-[#fefce8] text-[#a38c29] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ml-2">FINANCIALS</span>
                </div>
                <div class="flex items-center gap-3">
                    <button class="flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded text-[11px] font-bold text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Export Excel
                    </button>
                    <button class="flex items-center gap-2 px-4 py-1.5 bg-white border border-gray-300 rounded text-[11px] font-bold text-gray-700 hover:bg-gray-50 transition-colors uppercase tracking-wider shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Print
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mt-4 border-b border-gray-100 flex overflow-x-auto no-scrollbar">
                <a href="#" class="px-5 py-3 border-b-2 border-[#a38c29] text-[#a38c29] text-[12px] font-bold whitespace-nowrap">
                    Balance Register
                </a>
                <a href="#" class="px-5 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-[12px] font-bold whitespace-nowrap transition-colors">
                    Expense Report
                </a>
                <a href="#" class="px-5 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-[12px] font-bold whitespace-nowrap transition-colors">
                    Contra Report
                </a>
                <a href="#" class="px-5 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 text-[12px] font-bold whitespace-nowrap transition-colors">
                    Category Summary
                </a>
            </div>
            <!-- Filters -->
            <div class="p-6 bg-gray-50/50 border-b border-gray-200">
                <form action="{{ route('reports.petty_cash.reports') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Site</label>
                        <select name="project_id" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $reportData['project_id'] == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold text-gray-700 mb-1.5">From Date</label>
                        <input type="date" name="date_from" value="{{ $reportData['from_date'] }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold text-gray-700 mb-1.5">To Date</label>
                        <input type="date" name="date_to" value="{{ $reportData['to_date'] }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-9 text-[12px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                    </div>
                    <div>
                        <button type="submit" class="h-9 px-6 bg-[#a38c29] text-white text-[12px] font-bold rounded-lg hover:bg-[#8a7622] hover:shadow-lg transition-all">
                            View Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Report Area -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Balance Register Report</h3>
                
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Opening Balance -->
                    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                        <p class="text-[11px] font-bold text-gray-600 mb-2">Opening Balance ({{ \Carbon\Carbon::parse($reportData['from_date'])->format('d-M-Y') }})</p>
                        <h4 class="text-xl font-black text-gray-800">₹ {{ number_format($reportData['opening_balance'], 2) }}</h4>
                    </div>
                    <!-- Total Cash In -->
                    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                        <p class="text-[11px] font-bold text-gray-600 mb-2">Total Cash In</p>
                        <h4 class="text-xl font-black text-green-600">₹ {{ number_format($reportData['total_cash_in'], 2) }}</h4>
                    </div>
                    <!-- Total Cash Out -->
                    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                        <p class="text-[11px] font-bold text-gray-600 mb-2">Total Cash Out</p>
                        <h4 class="text-xl font-black text-red-600">₹ {{ number_format($reportData['total_cash_out'], 2) }}</h4>
                    </div>
                    <!-- Closing Balance -->
                    <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                        <p class="text-[11px] font-bold text-gray-600 mb-2">Closing Balance ({{ \Carbon\Carbon::parse($reportData['to_date'])->format('d-M-Y') }})</p>
                        <h4 class="text-xl font-black text-[#a38c29]">₹ {{ number_format($reportData['closing_balance'], 2) }}</h4>
                    </div>
                </div>

                <!-- Table -->
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase">Voucher No.</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase">Particulars</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase text-right">Cash In (₹)</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase text-right">Cash Out (₹)</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase text-right">Balance (₹)</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase">Type</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-700 uppercase">Reference</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($reportData['entries'] as $entry)
                                <tr class="hover:bg-[#f8f9fa] transition-colors">
                                    <td class="px-4 py-3 text-[11px] font-medium text-gray-700">{{ $entry->date }}</td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-[#a38c29]">{{ $entry->voucher_number }}</td>

                                    <td class="px-4 py-3 text-[11px] font-medium text-gray-800">{{ $entry->particulars }}</td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-green-600 text-right">{{ $entry->cash_in > 0 ? number_format($entry->cash_in, 2) : '-' }}</td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-red-600 text-right">{{ $entry->cash_out > 0 ? number_format($entry->cash_out, 2) : '-' }}</td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-gray-900 text-right">{{ number_format($entry->balance, 2) }}</td>
                                    <td class="px-4 py-3 text-[11px] font-medium text-gray-600">{{ $entry->type }}</td>
                                    <td class="px-4 py-3 text-[11px] font-medium text-gray-500">{{ $entry->reference_no }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 text-[12px] font-medium">
                                        No transactions found for the selected date range.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($reportData['entries']) > 0)
                    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-500">Showing 1 to {{ count($reportData['entries']) }} of {{ count($reportData['entries']) }} entries</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
