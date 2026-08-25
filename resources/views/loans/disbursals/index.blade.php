<x-erp-layout title="Loan Disbursal Registry" headerTitle="Bank Loan Disbursal Entry">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="disbursalApp()">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:opacity-75">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-655" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75">✕</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Loan Disbursal Entries</h1>
            <p class="text-xs text-slate-500 mt-1">Record and track the release of loan funds from lender banks to the company.</p>
        </div>

        <div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Record Disbursal
            </button>
        </div>
    </div>

    {{-- KPI Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        {{-- Card 1: Total Disbursed --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Total Disbursed</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider">Posted</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-emerald-700 font-mono tracking-tight block">₹{{ number_format($totalDisbursed, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Actual disbursed loan funds.</p>
            </div>
        </div>

        {{-- Card 2: Pending Drafts --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Pending Drafts</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider">Draft</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-blue-700 font-mono tracking-tight block">₹{{ number_format($totalDrafts, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Awaiting posting confirmation.</p>
            </div>
        </div>

        {{-- Card 3: Total Cancelled --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Total Cancelled</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider">Reversed</span>
            </div>
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight block">₹{{ number_format($totalCancelled, 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Cancelled/reversed entries.</p>
            </div>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <form method="GET" action="{{ route('loan-disbursals.index') }}" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
            {{-- Pro Light Search Input --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Search Lender / Disbursal No..." 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
            </div>

            {{-- Bank Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                    </svg>
                </div>
                <select name="bank_name" onchange="this.form.submit()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Banks</option>
                    @foreach($banks as $b)
                        <option value="{{ $b->bank_name }}" {{ request('bank_name') == $b->bank_name ? 'selected' : '' }}>
                            {{ $b->bank_name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Loan Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <select name="loan_id" onchange="this.form.submit()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Loans</option>
                    @foreach($loans as $loan)
                        <option value="{{ $loan->id }}" {{ request('loan_id') == $loan->id ? 'selected' : '' }}>
                            {{ $loan->lender_name }} - {{ $loan->loan_account_no }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <select name="status" onchange="this.form.submit()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Statuses</option>
                    <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                    <option value="POSTED" {{ request('status') === 'POSTED' ? 'selected' : '' }}>Posted</option>
                    <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button --}}
        <a href="{{ route('loan-disbursals.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Reset Filters</span>
        </a>
    </form>

    {{-- Disbursals Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #disbursals-table thead th {
                border-color: #8a7522 !important;
            }
            #disbursals-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #disbursals-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="disbursals-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">SL NO</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">DISBURSAL NO</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">BANK LOAN DETAIL</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">DISBURSAL DATE</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">DISBURSED AMOUNT</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">PAYMENT INFO</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">STATUS</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="disbursals-tbody" class="divide-y divide-[#EAE3CD] text-center">
                    @forelse($disbursals as $index => $disbursal)
                        <tr class="hover:bg-[#ebe5d0] transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $disbursals->firstItem() + $index }}</td>
                            <td class="px-4 py-3.5 border text-left font-mono font-bold text-slate-900">{{ $disbursal->disbursal_no }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <span class="font-bold text-slate-900 block leading-tight">{{ $disbursal->loan->lender_name }}</span>
                                <span class="text-[9px] text-slate-500 font-medium block">Acc: {{ $disbursal->loan->loan_account_no }}</span>
                                <span class="text-[8.5px] font-bold text-[#a38c29] uppercase tracking-wider block mt-0.5">{{ $disbursal->loan->project->name ?? 'No Project' }}</span>
                            </td>
                            <td class="px-4 py-3.5 border text-center font-bold text-slate-650">{{ $disbursal->disbursal_date ? $disbursal->disbursal_date->format('d-M-Y') : '-' }}</td>
                            <td class="px-4 py-3.5 border text-right font-mono font-bold text-slate-900">₹{{ number_format((float)$disbursal->amount, 2) }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <span class="badge-pill inline-flex items-center justify-center px-1.5 py-0.5 rounded bg-slate-100 text-slate-750 font-extrabold text-[8px] uppercase border border-slate-200 mb-0.5">{{ $disbursal->disbursal_type }}</span>
                                @if($disbursal->reference_no)
                                    <span class="text-[9px] text-slate-500 block leading-tight">Ref: {{ $disbursal->reference_no }}</span>
                                @endif
                                @if($disbursal->transaction_no)
                                    <span class="text-[9px] text-slate-500 block leading-tight">Txn: {{ $disbursal->transaction_no }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                <span class="badge-pill inline-flex items-center justify-center px-2 py-0.5 rounded-md border font-bold text-[9px] uppercase tracking-wider 
                                    {{ $disbursal->status === 'POSTED' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                    {{ $disbursal->status === 'DRAFT' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                    {{ $disbursal->status === 'CANCELLED' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                    {{ $disbursal->status }}
                                </span>
                                @if($disbursal->status === 'CANCELLED')
                                    <span class="text-[8.5px] text-rose-500 block font-medium mt-0.5" title="{{ $disbursal->cancellation_reason }}">
                                        Reason: {{ Str::limit($disbursal->cancellation_reason, 20) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($disbursal->status === 'DRAFT')
                                        <button @click="openEditModal({{ json_encode($disbursal) }})" class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition" title="Edit Draft">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('loan-disbursals.post', $disbursal->id) }}" class="inline" onsubmit="return confirm('Confirm posting this disbursal? Once posted, it cannot be edited.')">
                                            @csrf
                                            <button type="submit" class="p-1 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition" title="Post Disbursal">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('loan-disbursals.destroy', $disbursal->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this draft entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition" title="Delete Draft">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @elseif($disbursal->status === 'POSTED')
                                        <button @click="openCancelModal({{ json_encode($disbursal) }})" class="px-2 py-1 text-[10px] bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg font-bold transition flex items-center gap-1" title="Cancel & Reverse Disbursal">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            Cancel
                                        </button>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400 italic">No Action</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 border text-center text-slate-550 font-bold uppercase tracking-wider">
                                No loan disbursal entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($disbursals->hasPages())
            <div class="px-4 py-3 bg-slate-50 border-t border-[#EAE3CD]">
                {{ $disbursals->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL: RECORD / EDIT DISBURSAL --}}
    <div x-show="formModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="formModalOpen = false">
            <div class="bg-[#a38c29] px-6 py-4 flex items-center justify-between">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-white/20 text-white text-[9px] font-black uppercase tracking-wider rounded border border-white/30 mb-1">Bank Loan module</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white" x-text="isEdit ? 'Edit Loan Disbursal Entry' : 'Record Loan Disbursal Entry'"></h3>
                </div>
                <button type="button" @click="formModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            
            <form :action="isEdit ? '{{ url('/loan-disbursals') }}/' + formDisbursalId : '{{ route('loan-disbursals.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Select Bank Loan <span class="text-rose-500 font-bold">*</span></label>
                        <select name="loan_id" x-model="form.loan_id" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                            <option value="">-- Choose Active Loan --</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->id }}">
                                    {{ $loan->lender_name }} - {{ $loan->loan_account_no }} (₹{{ number_format($loan->principal_amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disbursal Date <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="disbursal_date" x-model="form.disbursal_date" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disbursed Amount (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" step="0.01" name="amount" x-model="form.amount" required placeholder="0.00" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Disbursal Type <span class="text-rose-500 font-bold">*</span></label>
                        <select name="disbursal_type" x-model="form.disbursal_type" required class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="RTGS/NEFT">RTGS/NEFT</option>
                            <option value="Demand Draft">Demand Draft</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reference No (Bank/Cheque Ref)</label>
                        <input type="text" name="reference_no" x-model="form.reference_no" placeholder="Optional reference detail" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Transaction ID / Instrument No</label>
                        <input type="text" name="transaction_no" x-model="form.transaction_no" placeholder="Optional transaction code" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Remarks / Internal Notes</label>
                    <textarea name="remarks" x-model="form.remarks" rows="3" placeholder="Describe the disbursal release detail..." class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="formModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Save Draft</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: CANCEL DISBURSAL ENTRY --}}
    <div x-show="cancelModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="cancelModalOpen = false">
            <div class="bg-rose-700 px-6 py-4 flex items-center justify-between">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-white/20 text-white text-[9px] font-black uppercase tracking-wider rounded border border-white/30 mb-1">Reversal request</span>
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Cancel Loan Disbursal Entry</h3>
                </div>
                <button type="button" @click="cancelModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            
            <form :action="'{{ url('/loan-disbursals') }}/' + cancelDisbursalId + '/cancel'" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs font-semibold leading-relaxed">
                    <p class="font-black uppercase tracking-wider mb-1 flex items-center gap-1 text-rose-900">
                        <svg class="w-4 h-4 text-rose-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Warning: High Risk Reversal
                    </p>
                    This will permanently cancel the disbursal entry and mark it as <strong class="text-rose-900 uppercase">cancelled</strong>. This action cannot be undone. Please write the explanation for audit purposes.
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reason for Cancellation <span class="text-rose-500 font-bold">*</span></label>
                    <textarea name="cancellation_reason" required rows="3" placeholder="Provide cancellation reason for registry logs..." class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-750 focus:bg-white focus:border-[#a38c29] focus:outline-none transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">Close</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-700 hover:bg-rose-800 text-white text-xs font-black uppercase rounded-xl transition shadow-md shadow-rose-700/20 cursor-pointer">Confirm Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function disbursalApp() {
    return {
        formModalOpen: false,
        cancelModalOpen: false,
        isEdit: false,
        formDisbursalId: null,
        cancelDisbursalId: null,
        form: {
            loan_id: '',
            disbursal_date: '',
            amount: '',
            disbursal_type: 'Bank Transfer',
            reference_no: '',
            transaction_no: '',
            remarks: ''
        },
        openAddModal() {
            this.isEdit = false;
            this.formDisbursalId = null;
            this.form = {
                loan_id: '',
                disbursal_date: '{{ date('Y-m-d') }}',
                amount: '',
                disbursal_type: 'Bank Transfer',
                reference_no: '',
                transaction_no: '',
                remarks: ''
            };
            this.formModalOpen = true;
        },
        openEditModal(disbursal) {
            this.isEdit = true;
            this.formDisbursalId = disbursal.id;
            
            // Format date correctly to YYYY-MM-DD
            let formattedDate = '';
            if (disbursal.disbursal_date) {
                const dateObj = new Date(disbursal.disbursal_date);
                if (!isNaN(dateObj.getTime())) {
                    const y = dateObj.getFullYear();
                    const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const d = String(dateObj.getDate()).padStart(2, '0');
                    formattedDate = `${y}-${m}-${d}`;
                }
            }

            this.form = {
                loan_id: disbursal.loan_id,
                disbursal_date: formattedDate,
                amount: disbursal.amount,
                disbursal_type: disbursal.disbursal_type,
                reference_no: disbursal.reference_no || '',
                transaction_no: disbursal.transaction_no || '',
                remarks: disbursal.remarks || ''
            };
            this.formModalOpen = true;
        },
        openCancelModal(disbursal) {
            this.cancelDisbursalId = disbursal.id;
            this.cancelModalOpen = true;
        }
    };
}
</script>

</x-erp-layout>
