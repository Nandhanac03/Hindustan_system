<x-erp-layout title="Company Bank Account Master" headerTitle="Company Bank Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="companyBankApp()">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#a38c29]/10 border border-[#a38c29]/30 text-[#6b5d1c] text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-[#a38c29] hover:opacity-75">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

    {{-- Overdue EMI Warning Banner --}}
    @if(isset($pendingEmisCount) && $pendingEmisCount > 0)
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-250 text-amber-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-amber-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>
                    Attention: You have <strong class="text-amber-900">{{ $pendingEmisCount }}</strong> pending/overdue Loan EMI repayments due this month totaling <strong class="text-amber-900">₹{{ number_format($pendingEmisAmount, 2) }}</strong>.
                </span>
            </div>
            <a href="{{ route('loans.index') }}" class="px-3 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-[10px] font-bold uppercase tracking-wide transition shadow-sm">
                View Repayments &rarr;
            </a>
        </div>
    @endif

    {{-- Summary Executive KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Liquid Balance --}}
        <div class="bg-gradient-to-br from-black via-slate-900 to-black rounded-2xl p-5 text-white shadow-md relative overflow-hidden border border-[#a38c29]/30">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#a38c29]">Total Company Liquidity</span>
                    <h3 class="text-2xl font-black tracking-tight mt-1 text-white">
                        ₹{{ number_format($totalBalance ?? 0, 2) }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/40 text-[#a38c29] flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] text-slate-400 font-medium relative z-10">
                <span class="inline-block w-2 h-2 rounded-full bg-[#a38c29] animate-pulse"></span>
                Combined current balance across all company accounts
            </div>
        </div>

        {{-- Card 2: Total Accounts --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Company Bank Accounts</span>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1">
                    {{ $totalAccounts ?? 0 }}
                </h3>
                <span class="text-[10px] text-[#a38c29] font-bold mt-1 inline-block">
                    {{ $activeAccounts ?? 0 }} Active / {{ ($totalAccounts ?? 0) - ($activeAccounts ?? 0) }} Inactive
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        {{-- Card 3: Default Primary Account --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="truncate max-w-[190px]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Primary Operating Bank</span>
                @if(isset($defaultAccount) && $defaultAccount)
                    <h3 class="text-sm font-black text-slate-900 truncate mt-1" title="{{ $defaultAccount->bank_name }}">
                        {{ $defaultAccount->bank_name }}
                    </h3>
                    <p class="text-[10px] font-mono font-bold text-[#a38c29] truncate mt-0.5">
                        {{ $defaultAccount->account_number ? 'A/C: ' . $defaultAccount->account_number : 'IFSC: ' . $defaultAccount->ifsc_code }}
                    </p>
                @else
                    <h3 class="text-xs font-bold text-amber-600 mt-1">Not Set</h3>
                    <p class="text-[10px] text-slate-400">Mark an account as default</p>
                @endif
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>

        {{-- Card 4: Quick Add Action Card --}}
        <div class="bg-gradient-to-br from-[#a38c29]/10 via-[#a38c29]/5 to-transparent rounded-2xl p-5 border border-[#a38c29]/30 flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#a38c29]">Quick Account Setup</span>
                <p class="text-xs font-semibold text-slate-700 mt-1">Configure company bank accounts, IFSC & routing numbers.</p>
            </div>
            <button @click="openAddModal()" class="mt-3 w-full py-2 px-3 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide inline-flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Company Bank Account
            </button>
        </div>
    </div>

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
        <div>
            <h1 class="text-lg font-extrabold text-slate-900 tracking-tight uppercase">Company Bank Account Master</h1>
            <p class="text-xs text-slate-500 mt-0.5">Directory of all corporate bank accounts, account numbers, IFSC codes, SWIFT/MICR, and balances.</p>
        </div>

        <div>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Bank Account
            </button>
        </div>
    </div>

    {{-- Company Bank Accounts Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #company-banks-table thead th {
                border-color: #8a7522 !important;
            }
            #company-banks-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #company-banks-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="company-banks-table" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-center w-12">SL NO</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-left">BANK & ACCOUNT NAME</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-left">ACCOUNT NO & TYPE</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-left">IFSC & BRANCH</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-right">CURRENT BALANCE</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-center">STATUS</th>
                        <th class="px-4 py-3.5 border sticky top-0 bg-[#a38c29] shadow-sm text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="company-banks-tbody" class="divide-y divide-[#EAE3CD] text-center">
                    @forelse($accounts as $index => $account)
                        <tr class="hover:bg-[#ebe5d0] transition-colors text-xs font-semibold text-slate-700">
                            <td class="px-3 py-3.5 border font-bold text-slate-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#a38c29] to-[#75631c] flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-sm border border-[#8a7522]">
                                        {{ substr(trim($account->bank_name), 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-extrabold text-slate-900 block text-sm leading-tight">{{ $account->bank_name }}</span>
                                            @if($account->is_default)
                                                <span class="px-1.5 py-0.5 bg-[#a38c29]/15 text-[#a38c29] text-[9px] font-black rounded border border-[#a38c29]/40 uppercase tracking-wider flex items-center gap-0.5">
                                                    ★ PRIMARY
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">
                                            {{ $account->account_name ?: 'Hindustan System Corporate Account' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-mono text-slate-800 font-bold text-xs">
                                    {{ $account->account_number ?: 'N/A' }}
                                </div>
                                <div class="mt-0.5">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $account->account_type ?? 'Current' }} A/C
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-mono text-[#a38c29] font-bold uppercase text-xs">
                                    {{ $account->ifsc_code }}
                                </div>
                                <div class="text-[10px] text-slate-500 font-medium truncate max-w-[180px]">
                                    {{ $account->branch_name ?: 'Main Branch' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <span class="font-bold text-slate-900 text-sm block">
                                    ₹{{ number_format((float) ($account->current_balance ?? 0), 2) }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-medium block">
                                    Opening: ₹{{ number_format((float) ($account->opening_balance ?? 0), 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                <span class="badge-pill inline-flex items-center justify-center px-2.5 py-1 rounded-md border font-bold text-[10px] uppercase tracking-wider {{ $account->status === 'active' ? 'bg-[#a38c29]/15 text-[#8a7522] border-[#a38c29]/30' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                      {{ $account->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-right pr-4">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openViewModal({{ json_encode($account) }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEditModal({{ json_encode($account) }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Bank Account">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="openDeleteModal({{ json_encode($account) }})" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Bank">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">No company bank records found. Click 'Add Bank Account' to configure corporate banking details.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals Wrapper --}}
    <div>

    {{-- Company Bank Add Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800 animate-fade-in-up" @click.away="addModalOpen = false">
            {{-- Modal Header: Dark-Gold Hero Header --}}
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BANK ACCOUNTS MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD COMPANY BANK ACCOUNT</h3>
                </div>
                <button type="button" @click="addModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <div class="p-6">
                <form action="{{ route('company-bank-accounts.store') }}" method="POST" @submit="submitAddBank($event)" novalidate class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Bank Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bank Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="bank_name" x-model="addForm.bank_name" required placeholder="e.g. HDFC Bank, ICICI Bank, SBI"
                                   :class="errors.bank_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                   class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                            <template x-if="errors.bank_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.bank_name) ? errors.bank_name[0] : errors.bank_name"></p></template>
                        </div>

                        {{-- Account Holder Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Holder Name</label>
                            <input type="text" name="account_name" x-model="addForm.account_name" placeholder="e.g. Hindustan Real Estate Pvt Ltd"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Account Number --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Number</label>
                            <input type="text" name="account_number" x-model="addForm.account_number" placeholder="e.g. 50200012345678"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Account Type --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Type <span class="text-rose-500">*</span></label>
                            <select name="account_type" x-model="addForm.account_type" required
                                    class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="Current">Current Account</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Overdraft">Overdraft / OD Account</option>
                                <option value="Escrow">Escrow Account</option>
                                <option value="CC">Cash Credit (CC)</option>
                            </select>
                        </div>

                        {{-- IFSC Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">IFSC Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="ifsc_code" x-model="addForm.ifsc_code" required placeholder="e.g. HDFC0001234"
                                   :class="errors.ifsc_code ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                   class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all uppercase">
                            <template x-if="errors.ifsc_code"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.ifsc_code) ? errors.ifsc_code[0] : errors.ifsc_code"></p></template>
                        </div>

                        {{-- Branch Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Branch Name</label>
                            <input type="text" name="branch_name" x-model="addForm.branch_name" placeholder="e.g. Main Branch, MG Road"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- SWIFT Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">SWIFT / BIC Code</label>
                            <input type="text" name="swift_code" x-model="addForm.swift_code" placeholder="e.g. HDFCINBBXXX"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all uppercase">
                        </div>

                        {{-- MICR Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">MICR Code</label>
                            <input type="text" name="micr_code" x-model="addForm.micr_code" placeholder="e.g. 500240002"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Opening Balance --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Opening Balance (₹)</label>
                            <input type="number" step="0.01" name="opening_balance" x-model="addForm.opening_balance" placeholder="0.00"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- UPI ID --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Company UPI ID / VPA</label>
                            <input type="text" name="upi_id" x-model="addForm.upi_id" placeholder="e.g. hindustan@hdfcbank"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="addForm.status" required
                                    class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- Primary Receiving Account Checkbox --}}
                        <div class="flex items-center gap-3 pt-6">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" x-model="addForm.is_default" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#a38c29]"></div>
                                <span class="ml-2.5 text-xs font-bold text-slate-700">Set as Primary Receiving Bank</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-wide">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-[#a38c29] hover:bg-[#8a7522] shadow-md hover:shadow-lg transition-all uppercase tracking-wide inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Add Bank Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Company Bank Edit Modal --}}
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800 animate-fade-in-up" @click.away="editModalOpen = false">
            {{-- Modal Header: Dark-Gold Hero Header --}}
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">BANK ACCOUNTS MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT COMPANY BANK ACCOUNT</h3>
                </div>
                <button type="button" @click="editModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            <div class="p-6">
                <form :action="editForm.action" method="POST" @submit="submitEditBank($event)" novalidate class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Bank Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bank Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="bank_name" x-model="editForm.bank_name" required
                                   :class="errors.edit_bank_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                   class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                            <template x-if="errors.edit_bank_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_bank_name) ? errors.edit_bank_name[0] : errors.edit_bank_name"></p></template>
                        </div>

                        {{-- Account Holder Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Holder Name</label>
                            <input type="text" name="account_name" x-model="editForm.account_name"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Account Number --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Number</label>
                            <input type="text" name="account_number" x-model="editForm.account_number"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Account Type --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Type <span class="text-rose-500">*</span></label>
                            <select name="account_type" x-model="editForm.account_type" required
                                    class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="Current">Current Account</option>
                                <option value="Savings">Savings Account</option>
                                <option value="Overdraft">Overdraft / OD Account</option>
                                <option value="Escrow">Escrow Account</option>
                                <option value="CC">Cash Credit (CC)</option>
                            </select>
                        </div>

                        {{-- IFSC Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">IFSC Code <span class="text-rose-500">*</span></label>
                            <input type="text" name="ifsc_code" x-model="editForm.ifsc_code" required
                                   :class="errors.edit_ifsc_code ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                   class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all uppercase">
                            <template x-if="errors.edit_ifsc_code"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_ifsc_code) ? errors.edit_ifsc_code[0] : errors.edit_ifsc_code"></p></template>
                        </div>

                        {{-- Branch Name --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Branch Name</label>
                            <input type="text" name="branch_name" x-model="editForm.branch_name"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- SWIFT Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">SWIFT / BIC Code</label>
                            <input type="text" name="swift_code" x-model="editForm.swift_code"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all uppercase">
                        </div>

                        {{-- MICR Code --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">MICR Code</label>
                            <input type="text" name="micr_code" x-model="editForm.micr_code"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Opening & Current Balance --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Current Balance (₹)</label>
                            <input type="number" step="0.01" name="current_balance" x-model="editForm.current_balance"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- UPI ID --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Company UPI ID / VPA</label>
                            <input type="text" name="upi_id" x-model="editForm.upi_id"
                                   class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status <span class="text-rose-500">*</span></label>
                            <select name="status" x-model="editForm.status" required
                                    class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- Primary Receiving Account Checkbox --}}
                        <div class="flex items-center gap-3 pt-6">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" x-model="editForm.is_default" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#a38c29]"></div>
                                <span class="ml-2.5 text-xs font-bold text-slate-700">Set as Primary Receiving Bank</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-wide">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-[#a38c29] hover:bg-[#8a7522] shadow-md hover:shadow-lg transition-all uppercase tracking-wide inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- View Details Modal --}}
    <div x-show="viewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800 animate-fade-in-up" @click.away="viewModalOpen = false">
            {{-- Modal Header: BLACK with Gold Accents --}}
            <div class="bg-black text-white px-6 py-4 flex items-center justify-between border-b border-[#a38c29]/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#a38c29]/20 text-[#a38c29] border border-[#a38c29]/40 flex items-center justify-center font-black text-lg flex-shrink-0">
                        <span x-text="viewForm.bank_name ? viewForm.bank_name.charAt(0) : 'B'"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider" x-text="viewForm.bank_name"></h3>
                            <template x-if="viewForm.is_default">
                                <span class="px-2 py-0.5 bg-[#a38c29] text-white text-[9px] font-black rounded uppercase">PRIMARY</span>
                            </template>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium" x-text="viewForm.account_name || 'Hindustan System Corporate Account'"></p>
                    </div>
                </div>
                <button type="button" @click="viewModalOpen = false" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-[#a38c29] text-slate-400 hover:text-white transition flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <div class="p-6">
                {{-- Account Summary Box --}}
                <div class="mb-5 p-4 rounded-2xl bg-black text-white border border-[#a38c29]/40 shadow-inner flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-wider block">Current Account Liquidity</span>
                        <span class="text-2xl font-black text-white block mt-0.5" x-text="'₹' + Number(viewForm.current_balance || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Account Type</span>
                        <span class="text-xs font-bold text-[#a38c29] block uppercase" x-text="(viewForm.account_type || 'Current') + ' A/C'"></span>
                    </div>
                </div>

                {{-- Key Details Grid --}}
                <div class="grid grid-cols-2 gap-3 text-left">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Account Number</span>
                        <span class="text-xs font-bold font-mono text-slate-900 block mt-0.5" x-text="viewForm.account_number || 'N/A'"></span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IFSC Code</span>
                        <span class="text-xs font-bold font-mono text-[#a38c29] block mt-0.5 uppercase" x-text="viewForm.ifsc_code || 'N/A'"></span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Branch Name</span>
                        <span class="text-xs font-bold text-slate-800 block mt-0.5" x-text="viewForm.branch_name || 'N/A'"></span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Company UPI ID</span>
                        <span class="text-xs font-bold font-mono text-slate-800 block mt-0.5" x-text="viewForm.upi_id || 'N/A'"></span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SWIFT / BIC Code</span>
                        <span class="text-xs font-bold font-mono text-slate-800 block mt-0.5 uppercase" x-text="viewForm.swift_code || 'N/A'"></span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">MICR Code</span>
                        <span class="text-xs font-bold font-mono text-slate-800 block mt-0.5" x-text="viewForm.micr_code || 'N/A'"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="viewModalOpen = false" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-black hover:bg-slate-900 border border-slate-800 transition-colors uppercase tracking-wide">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Company Bank Delete Modal --}}
    <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800 animate-fade-in-up" @click.away="deleteModalOpen = false">
            {{-- Modal Header: BLACK --}}
            <div class="bg-black text-white px-6 py-4 flex items-center justify-between border-b border-rose-500/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-wider">DELETE BANK ACCOUNT</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Permanent removal of account record</p>
                    </div>
                </div>
                <button type="button" @click="deleteModalOpen = false" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-rose-600 text-slate-400 hover:text-white transition flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <div class="p-6 text-center">
                <p class="text-xs text-slate-600 font-medium leading-relaxed mb-4">
                    Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteForm.bank_name"></span> (<span class="font-mono font-bold text-rose-700" x-text="deleteForm.ifsc_code"></span>)? This action cannot be undone.
                </p>

                <form :action="deleteForm.action" method="POST">
                    @csrf
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <button type="button" @click="deleteModalOpen = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors uppercase tracking-wide">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md hover:shadow-lg transition-all uppercase tracking-wide inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Yes, Delete Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    </div>

</div>

<script>
function companyBankApp() {
    return {
        errors: {},
        addModalOpen: false,
        editModalOpen: false,
        viewModalOpen: false,
        deleteModalOpen: false,

        addForm: {
            bank_name: '',
            account_name: '',
            account_number: '',
            account_type: 'Current',
            ifsc_code: '',
            branch_name: '',
            swift_code: '',
            micr_code: '',
            opening_balance: '0.00',
            upi_id: '',
            status: 'active',
            is_default: false
        },

        editForm: {
            action: '',
            bank_name: '',
            account_name: '',
            account_number: '',
            account_type: 'Current',
            ifsc_code: '',
            branch_name: '',
            swift_code: '',
            micr_code: '',
            current_balance: '0.00',
            upi_id: '',
            status: 'active',
            is_default: false
        },

        viewForm: {},

        deleteForm: {
            action: '',
            bank_name: '',
            ifsc_code: ''
        },

        openAddModal() {
            this.addForm = {
                bank_name: '',
                account_name: '',
                account_number: '',
                account_type: 'Current',
                ifsc_code: '',
                branch_name: '',
                swift_code: '',
                micr_code: '',
                opening_balance: '0.00',
                upi_id: '',
                status: 'active',
                is_default: false
            };
            this.errors = {};
            this.addModalOpen = true;
        },

        openEditModal(account) {
            this.editForm = {
                action: `{{ url('/company-bank-accounts') }}/${account.id}`,
                bank_name: account.bank_name || '',
                account_name: account.account_name || '',
                account_number: account.account_number || '',
                account_type: account.account_type || 'Current',
                ifsc_code: account.ifsc_code || '',
                branch_name: account.branch_name || '',
                swift_code: account.swift_code || '',
                micr_code: account.micr_code || '',
                current_balance: account.current_balance || '0.00',
                upi_id: account.upi_id || '',
                status: account.status || 'active',
                is_default: Boolean(account.is_default)
            };
            this.errors = {};
            this.editModalOpen = true;
        },

        openViewModal(account) {
            this.viewForm = account;
            this.viewModalOpen = true;
        },

        openDeleteModal(account) {
            this.deleteForm = {
                action: `{{ url('/company-bank-accounts') }}/${account.id}/delete`,
                bank_name: account.bank_name,
                ifsc_code: account.ifsc_code
            };
            this.deleteModalOpen = true;
        },

        submitAddBank(e) {
            let clientErrors = {};
            if (!this.addForm.bank_name || !String(this.addForm.bank_name).trim()) {
                clientErrors.bank_name = ['The bank name field is required.'];
            }
            if (!this.addForm.ifsc_code || !String(this.addForm.ifsc_code).trim()) {
                clientErrors.ifsc_code = ['The IFSC code field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        },

        submitEditBank(e) {
            let clientErrors = {};
            if (!this.editForm.bank_name || !String(this.editForm.bank_name).trim()) {
                clientErrors.edit_bank_name = ['The bank name field is required.'];
            }
            if (!this.editForm.ifsc_code || !String(this.editForm.ifsc_code).trim()) {
                clientErrors.edit_ifsc_code = ['The IFSC code field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                e.preventDefault();
                this.errors = clientErrors;
                return false;
            }
            this.errors = {};
        }
    }
}
</script>

</x-erp-layout>
