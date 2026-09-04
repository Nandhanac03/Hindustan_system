@extends('layouts.erp')

@section('title', 'Site Expense Voucher Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6 bg-slate-100 min-h-screen text-slate-800">

    {{-- Top Banner --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold uppercase rounded-full">
                    Voucher Detail View
                </span>
                <span class="font-mono text-xs font-bold text-slate-500">{{ $siteExpense->voucher_number }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1 flex items-center gap-2">
                <i data-lucide="receipt" class="w-7 h-7 text-[#a38c29]"></i> {{ $siteExpense->expense_category_name }}
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Paid to <strong class="text-slate-800">{{ $siteExpense->payee_display_name }}</strong> for <strong class="text-slate-800">{{ $siteExpense->project?->name }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('site-expenses.workflow', ['expense_id' => $siteExpense->id, 'project_id' => $siteExpense->project_id]) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-[#a38c29] text-white hover:bg-[#8d7923] shadow-md transition">
                <i data-lucide="git-merge" class="w-4 h-4 inline mr-1"></i> Interactive Workflow View
            </a>
            <a href="{{ route('site-expenses.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                Back to Register
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Voucher Summary Box --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i> Voucher Details
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 block font-medium">Voucher Number</span>
                        <span class="font-mono font-bold text-slate-900 text-sm">{{ $siteExpense->voucher_number }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Voucher Date</span>
                        <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($siteExpense->voucher_date)->format('d M Y') }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Approval Status</span>
                        @if($siteExpense->status === 'Approved')
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full">Approved</span>
                        @elseif($siteExpense->status === 'Draft')
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-xs rounded-full">Draft</span>
                        @else
                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-bold text-xs rounded-full">Rejected</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Project Name</span>
                        <span class="font-bold text-slate-900">{{ $siteExpense->project?->name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Tower / Block Tag</span>
                        <span class="font-semibold text-slate-700">{{ $siteExpense->tower_block_tag ?? 'Overall Project' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Floor</span>
                        <span class="font-semibold text-slate-700">{{ $siteExpense->floor ? 'Floor #' . $siteExpense->floor->floor_number : 'N/A' }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 block font-medium">Payee Name</span>
                        <span class="font-bold text-slate-900">{{ $siteExpense->payee_display_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Payee Type</span>
                        <span class="font-semibold text-blue-700">{{ ucfirst($siteExpense->payee_type) }} Payee</span>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Expense Category</span>
                        <span class="font-bold text-blue-900">{{ $siteExpense->expense_category_code }} - {{ $siteExpense->expense_category_name }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 block font-medium">Payment Source</span>
                        <span class="font-bold text-slate-900">{{ $siteExpense->payment_source_display_name }}</span>
                    </div>

                    <div class="col-span-2">
                        <span class="text-slate-500 block font-medium">Transaction Reference / UTR</span>
                        <span class="font-mono font-bold text-slate-800">{{ $siteExpense->transaction_reference_no }}</span>
                    </div>
                </div>

                @if($siteExpense->narration)
                    <div class="pt-4 border-t border-slate-100 text-xs">
                        <span class="text-slate-500 block font-medium">Narration / Remarks</span>
                        <p class="text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200 mt-1">
                            {{ $siteExpense->narration }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Double-Entry Accounting Ledger --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="book-open" class="w-4 h-4 text-sky-600"></i> Double-Entry Journal Posting</span>
                    <span class="text-xs font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold">Auto-Posted</span>
                </h3>

                <table class="w-full text-xs text-left text-slate-700">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Particulars Account</th>
                            <th class="p-3 text-right">Debit ($Dr$)</th>
                            <th class="p-3 text-right">Credit ($Cr$)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="p-3 font-bold text-slate-900">
                                {{ $siteExpense->expense_category_code }} - {{ $siteExpense->expense_category_name }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900">
                                ₹ {{ number_format($siteExpense->net_amount, 2) }}
                            </td>
                            <td class="p-3 text-right font-mono text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-slate-900">
                                {{ $siteExpense->payment_source_display_name }}
                            </td>
                            <td class="p-3 text-right font-mono text-slate-400">-</td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900">
                                ₹ {{ number_format($siteExpense->net_amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Financial Summary & Document Preview Column --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-4 h-4 text-[#a38c29]"></i> Financial Breakdown
                </h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Gross Amount</span>
                        <span class="font-mono">₹ {{ number_format($siteExpense->gross_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>CGST Amount</span>
                        <span class="font-mono">₹ {{ number_format($siteExpense->cgst_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>SGST Amount</span>
                        <span class="font-mono">₹ {{ number_format($siteExpense->sgst_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>IGST Amount</span>
                        <span class="font-mono">₹ {{ number_format($siteExpense->igst_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-blue-900 font-extrabold border-t border-slate-200 pt-2 text-sm">
                        <span>Total Net Paid</span>
                        <span class="font-mono text-blue-950">₹ {{ number_format($siteExpense->net_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Document Attachment --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-200 pb-2 flex items-center gap-2">
                    <i data-lucide="paperclip" class="w-4 h-4 text-blue-600"></i> Attachment Document
                </h3>

                @if($siteExpense->attachment_path)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                            <span class="font-bold text-slate-800 font-mono truncate max-w-[150px]">
                                {{ basename($siteExpense->attachment_path) }}
                            </span>
                        </div>
                        <a href="{{ asset('storage/' . $siteExpense->attachment_path) }}" target="_blank" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg font-bold text-[11px] hover:bg-blue-700">
                            Download / View
                        </a>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No document attachment uploaded for this voucher.</p>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
