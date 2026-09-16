@extends('layouts.erp')

@section('title', 'Opening Balance Master - Tabasco ERP')

@section('content')
@php
    $formatInr = function($val) {
        $n = (float) $val;
        $formatted = number_format($n, 2, '.', '');
        $parts = explode('.', $formatted);
        $intPart = $parts[0];
        $decPart = $parts[1] ?? '00';
        $lastThree = substr($intPart, -3);
        $remaining = substr($intPart, 0, -3);
        if ($remaining !== '') {
            $lastThree = ',' . $lastThree;
            $remaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
        }
        return '₹ ' . $remaining . $lastThree . '.' . $decPart;
    };
@endphp

<div x-data="openingBalanceMasterApp()" class="max-w-[1700px] mx-auto space-y-6 text-slate-800 pb-16">

    {{-- Top Flash Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold text-xs sm:text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
    @endif

    @if(session('error') || session('status'))
        <div class="p-4 rounded-xl {{ session('error') ? 'bg-rose-50 border border-rose-300 text-rose-900' : 'bg-amber-50 border border-amber-300 text-amber-900' }} flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ session('error') ? 'text-rose-600' : 'text-amber-600' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="font-bold text-xs sm:text-sm">{{ session('error') ?? session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-600 hover:text-slate-900 cursor-pointer"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
    @endif

    <!-- Header Section matching Chart of Accounts Master -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span>Opening Balance </span>
                        @if($isLocked)
                            <span class="px-2.5 py-0.5 bg-amber-100 text-[#7a671b] text-[10px] font-black uppercase rounded-lg border border-amber-300 inline-flex items-center gap-1 shadow-xs">
                                <svg class="w-3 h-3 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Locked</span>
                            </span>
                        @endif
                    </h1>
                    <p class="text-xs text-slate-500 font-medium">Manage initial account balances, equity buffer (3090), and financial migration controls</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            @if(!$isLocked)
                <button type="button" @click="openAddAccountModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Account Head</span>
                </button>
                <button type="button" @click="openLockModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-[#7a671b] hover:text-[#5e4f13] text-xs font-black shadow-xs transition cursor-pointer border border-amber-300 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Freeze & Lock Balances</span>
                </button>
            @else
                <a href="{{ Route::has('journal-vouchers.index') ? route('journal-vouchers.index') : url('/vouchers') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:border-[#a38c29] text-slate-800 hover:text-[#a38c29] text-xs font-bold shadow-2xs hover:shadow-sm transition cursor-pointer uppercase tracking-wider">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Post Prior Period JV</span>
                </a>
                <button type="button" @click="openUnlockModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 text-xs font-bold transition cursor-pointer uppercase tracking-wider">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    <span>Admin Unlock</span>
                </button>
            @endif
        </div>
    </div>

    {{-- KPI Metric Cards matching Chart of Accounts style --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 1. Total Debits (Blue) --}}
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-blue-600 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-300">
            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-1">Total Opening Debits</p>
            <h4 class="text-[22px] font-bold text-blue-700 m-0 font-mono" x-text="formatCurrency(liveTotalDr)">
                {{ $formatInr($totalDebits) }}
            </h4>
            <p class="text-[10px] text-gray-500 mt-1">Assets & Expense starting values</p>
        </div>

        {{-- 2. Total Credits (Amber) --}}
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-amber-500 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-amber-300">
            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider mb-1">Total Opening Credits</p>
            <h4 class="text-[22px] font-bold text-amber-700 m-0 font-mono" x-text="formatCurrency(liveTotalCr)">
                {{ $formatInr($totalCredits) }}
            </h4>
            <p class="text-[10px] text-gray-500 mt-1">Liabilities, Capital & Income values</p>
        </div>

        {{-- 3. Opening Balance Equity 3090 --}}
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1"
             :class="liveDifference === 0 ? 'border-l-emerald-500 hover:border-emerald-300' : 'border-l-[#a38c29] hover:border-[#a38c29]/50'">
            <div class="flex items-center justify-between mb-1">
                <p class="text-[11px] font-bold uppercase tracking-wider m-0" :class="liveDifference === 0 ? 'text-emerald-700' : 'text-[#a38c29]'">
                    3090 · Equity Balance
                </p>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                      :class="liveDifference === 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200'"
                      x-text="liveDifference === 0 ? 'BALANCED' : liveDifferenceSide">
                    {{ $varianceSide }}
                </span>
            </div>
            <h4 class="text-[22px] font-bold font-mono m-0"
                :class="liveDifference === 0 ? 'text-emerald-700' : 'text-slate-900'"
                x-text="formatCurrency(liveDifference)">
                {{ $formatInr($variance) }}
            </h4>
            <p class="text-[10px] text-gray-500 mt-1">
                <span x-show="liveDifference === 0" class="text-emerald-600 font-bold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Balanced (Zero Variance)</span>
                </span>
                <span x-show="liveDifference > 0" class="text-slate-500">Auto-absorbed into Account 3090</span>
            </p>
        </div>

        {{-- 4. Accounts Status & Lock --}}
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#a38c29]/50">
            <div class="flex items-center justify-between mb-1">
                <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider m-0">Ledger Setup Status</p>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isLocked ? 'bg-amber-100 text-[#7a671b] border border-amber-300' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                    {{ $isLocked ? 'LOCKED' : 'OPEN' }}
                </span>
            </div>
            <h4 class="text-[22px] font-bold text-slate-900 m-0">
                <span x-text="liveConfiguredCount">{{ $configuredCount }}</span>
                <span class="text-xs font-normal text-slate-400">/ {{ $totalAccountsCount }} heads</span>
            </h4>
            <p class="text-[10px] text-gray-500 mt-1">
                {{ $isLocked ? 'Balances Locked & Frozen' : 'Pre-Go-Live migration window active' }}
            </p>
        </div>
    </div>

    {{-- Lock Warning / Guidelines Banner --}}
    @if($isLocked)
        <div class="p-4 rounded-2xl bg-amber-50/90 text-slate-800 border border-amber-300 flex items-start gap-3.5 shadow-xs">
            <div class="w-9 h-9 rounded-xl bg-amber-100 text-[#8a7522] flex items-center justify-center shrink-0 mt-0.5 border border-amber-300">
                <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="flex-1 text-xs space-y-1">
                <p class="font-extrabold text-[#7a671b] text-sm tracking-wide uppercase">
                    Opening Balances are Locked (Audit-Protection Active)
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Direct modifications to opening balances are disabled to prevent corruption of Trial Balances, P&L reports, and Bank Reconciliations. If an opening balance adjustment is required as per an audit finding, do not edit directly — post a <strong class="text-[#7a671b] font-bold underline">Prior Period Adjustment Journal Voucher (JV)</strong> dated on the first day of the financial year.
                </p>
            </div>
        </div>
    @endif

    {{-- ======================================================== --}}
    {{-- 1. DMS-STYLE CATEGORY GRID CARDS (Top Category Tabs)     --}}
    {{-- ======================================================== --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        {{-- Card 1: ALL HEADS --}}
        <button type="button" @click="activeTypeFilter = ''; filterRows()"
                class="bg-white rounded-2xl border border-l-[5px] border-l-[#a38c29] border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.25)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20 h-full min-h-[120px] cursor-pointer"
                :class="activeTypeFilter === '' ? 'ring-2 ring-[#a38c29] bg-amber-50/15 shadow-md -translate-y-0.5' : ''">
            <div class="w-9 h-9 rounded-xl bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110"
                 :class="activeTypeFilter === '' ? 'bg-[#a38c29] text-white' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div class="flex flex-col justify-between">
                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">
                    All Heads
                </h4>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">
                    {{ $totalAccountsCount }} Accounts
                </p>
            </div>
        </button>

        {{-- Card 2: ASSETS (DR) --}}
        <button type="button" @click="activeTypeFilter = 'ASSET'; filterRows()"
                class="bg-white rounded-2xl border border-l-[5px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.25)] hover:border-r-blue-500/20 hover:border-y-blue-500/20 h-full min-h-[120px] cursor-pointer"
                :class="activeTypeFilter === 'ASSET' ? 'ring-2 ring-blue-500 bg-blue-50/20 shadow-md -translate-y-0.5' : ''">
            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110"
                 :class="activeTypeFilter === 'ASSET' ? 'bg-blue-600 text-white' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="flex flex-col justify-between">
                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">
                    Assets (Dr)
                </h4>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">
                    {{ $assetCount }} Accounts
                </p>
            </div>
        </button>

        {{-- Card 3: LIABILITIES (CR) --}}
        <button type="button" @click="activeTypeFilter = 'LIABILITY'; filterRows()"
                class="bg-white rounded-2xl border border-l-[5px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(217,119,6,0.25)] hover:border-r-amber-500/20 hover:border-y-amber-500/20 h-full min-h-[120px] cursor-pointer"
                :class="activeTypeFilter === 'LIABILITY' ? 'ring-2 ring-amber-500 bg-amber-50/20 shadow-md -translate-y-0.5' : ''">
            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 group-hover:bg-amber-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110"
                 :class="activeTypeFilter === 'LIABILITY' ? 'bg-amber-600 text-white' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <div class="flex flex-col justify-between">
                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">
                    Liabilities (Cr)
                </h4>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">
                    {{ $liabilityCount }} Accounts
                </p>
            </div>
        </button>

        {{-- Card 4: REVENUE --}}
        <button type="button" @click="activeTypeFilter = 'REVENUE'; filterRows()"
                class="bg-white rounded-2xl border border-l-[5px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.25)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20 h-full min-h-[120px] cursor-pointer"
                :class="activeTypeFilter === 'REVENUE' ? 'ring-2 ring-emerald-500 bg-emerald-50/20 shadow-md -translate-y-0.5' : ''">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110"
                 :class="activeTypeFilter === 'REVENUE' ? 'bg-emerald-600 text-white' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex flex-col justify-between">
                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">
                    Revenue
                </h4>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">
                    {{ $revenueCount }} Accounts
                </p>
            </div>
        </button>

        {{-- Card 5: EXPENSES --}}
        <button type="button" @click="activeTypeFilter = 'EXPENSE'; filterRows()"
                class="bg-white rounded-2xl border border-l-[5px] border-l-rose-500 border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.25)] hover:border-r-rose-500/20 hover:border-y-rose-500/20 h-full min-h-[120px] cursor-pointer"
                :class="activeTypeFilter === 'EXPENSE' ? 'ring-2 ring-rose-500 bg-rose-50/20 shadow-md -translate-y-0.5' : ''">
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110"
                 :class="activeTypeFilter === 'EXPENSE' ? 'bg-rose-600 text-white' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div class="flex flex-col justify-between">
                <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">
                    Expenses
                </h4>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">
                    {{ $expenseCount }} Accounts
                </p>
            </div>
        </button>
    </div>

    {{-- ======================================================== --}}
    {{-- 2. DMS-STYLE ULTRA-CLEAN SEARCH & FILTER BAR (Single Line) --}}
    {{-- ======================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 transition-all">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1 min-w-0">
            {{-- Search Input --}}
            <div class="relative group flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" x-model="searchQuery" @input="filterRows()" 
                       placeholder="Search by Account Code (e.g. 1001) or Account Name..." 
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''; filterRows()" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-rose-500 cursor-pointer" style="display: none;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Category Filter Select --}}
            <div class="relative w-full sm:w-64 md:w-72 lg:w-80 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <select x-model="activeTypeFilter" @change="filterRows()" 
                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Categories</option>
                    <option value="ASSET">Assets (Dr)</option>
                    <option value="LIABILITY">Liabilities (Cr)</option>
                    <option value="REVENUE">Revenue (Cr)</option>
                    <option value="EXPENSE">Expenses (Dr)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button --}}
        <button type="button" @click="resetAllFilters()" 
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 h-[42px] text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 cursor-pointer shrink-0 whitespace-nowrap">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>RESET FILTERS</span>
        </button>
    </div>

    {{-- ======================================================== --}}
    {{-- 3. DMS-STYLE PREMIUM SEGMENTED STATUS NAVIGATION TABS     --}}
    {{-- ======================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
        <div class="bg-white p-1.5 rounded-2xl border border-slate-200/90 shadow-sm inline-flex items-center gap-1.5 max-w-full overflow-x-auto">
            {{-- Tab 1: All Records --}}
            <button type="button" @click="balanceStatusFilter = 'all'; filterRows()" 
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center gap-2 cursor-pointer relative group active:scale-95 whitespace-nowrap"
                    :class="balanceStatusFilter === 'all' 
                        ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                <div class="w-5 h-5 rounded-lg flex items-center justify-center transition-colors"
                     :class="balanceStatusFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200/60 text-slate-500 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <span>All Records</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors"
                      :class="balanceStatusFilter === 'all' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-[#a38c29]/15 group-hover:text-[#a38c29]'">
                    {{ $totalAccountsCount }}
                </span>
            </button>

            {{-- Tab 2: Configured Balances --}}
            <button type="button" @click="balanceStatusFilter = 'configured'; filterRows()" 
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center gap-2 cursor-pointer relative group active:scale-95 whitespace-nowrap"
                    :class="balanceStatusFilter === 'configured' 
                        ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                <div class="w-5 h-5 rounded-lg flex items-center justify-center transition-colors"
                     :class="balanceStatusFilter === 'configured' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span>Configured</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors"
                      :class="balanceStatusFilter === 'configured' ? 'bg-white/25 text-white' : 'bg-emerald-50 text-emerald-700 group-hover:bg-[#a38c29]/15 group-hover:text-[#a38c29]'">
                    <span x-text="liveConfiguredCount">{{ $configuredCount }}</span>
                </span>
            </button>

            {{-- Tab 3: Zero Balances --}}
            <button type="button" @click="balanceStatusFilter = 'zero'; filterRows()" 
                    class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center gap-2 cursor-pointer relative group active:scale-95 whitespace-nowrap"
                    :class="balanceStatusFilter === 'zero' 
                        ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                <div class="w-5 h-5 rounded-lg flex items-center justify-center transition-colors"
                     :class="balanceStatusFilter === 'zero' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span>Zero (0.00)</span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors"
                      :class="balanceStatusFilter === 'zero' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-[#a38c29]/15 group-hover:text-[#a38c29]'">
                    <span x-text="Object.keys(rowsData).length - liveConfiguredCount">{{ $totalAccountsCount - $configuredCount }}</span>
                </span>
            </button>
        </div>
    </div>

    {{-- Main Ledger Balances Grid Form --}}
    <form id="opening-balances-form" action="{{ route('opening-balances.save') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-xs text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a741f] text-[10px] font-black uppercase tracking-wider text-left">
                            <th class="px-5 py-3.5 w-14">SL NO</th>
                            <th class="px-5 py-3.5 w-32">ACCOUNT CODE</th>
                            <th class="px-5 py-3.5">ACCOUNT HEAD NAME</th>
                            <th class="px-5 py-3.5 w-36">ACCOUNT TYPE</th>
                            <th class="px-5 py-3.5 w-56 text-right">OPENING BALANCE (₹)</th>
                            <th class="px-5 py-3.5 w-32 text-center">TYPE</th>
                            <th class="px-5 py-3.5 w-36 text-right">EFFECTIVE DR (₹)</th>
                            <th class="px-5 py-3.5 w-36 text-right">EFFECTIVE CR (₹)</th>
                            <th class="px-5 py-3.5 w-28 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @php $sl = 1; @endphp
                        @foreach($accounts as $acc)
                            @php
                                $is3090 = ($acc->account_code === '3090');
                                $initBal = (float) $acc->opening_balance;
                                $initType = strtoupper($acc->opening_balance_type ?: ($acc->account_type === 'ASSET' || $acc->account_type === 'EXPENSE' ? 'DR' : 'CR'));
                            @endphp
                            <tr class="account-row transition hover:bg-[#faf7eb] {{ $is3090 ? 'bg-amber-50/40 font-bold' : '' }}"
                                data-id="{{ $acc->id }}"
                                data-code="{{ $acc->account_code }}"
                                data-name="{{ strtolower($acc->account_name) }}"
                                data-type="{{ $acc->account_type }}"
                                data-is-3090="{{ $is3090 ? 'true' : 'false' }}">
                                
                                {{-- SL No --}}
                                <td class="px-5 py-3.5 text-slate-400 font-bold row-sl-no">
                                    {{ $sl++ }}
                                </td>

                                {{-- Code --}}
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-mono font-bold {{ $is3090 ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                                        {{ $acc->account_code }}
                                    </span>
                                </td>

                                {{-- Name --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-xs">{{ $acc->account_name }}</span>
                                            @if($is3090)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#a38c29]/15 text-[#7a671b] border border-[#a38c29]/30 uppercase tracking-widest">
                                                    Auto-Balancing Buffer
                                                </span>
                                            @endif
                                        </div>
                                        @if(!$is3090 && !$isLocked)
                                            <button type="button" @click="openEditAccountModal({
                                                id: {{ $acc->id }},
                                                account_code: '{{ addslashes($acc->account_code) }}',
                                                account_name: '{{ addslashes($acc->account_name) }}',
                                                account_type: '{{ $acc->account_type }}',
                                                opening_balance: {{ (float)$acc->opening_balance }},
                                                opening_balance_type: '{{ $acc->opening_balance_type ?: ($acc->account_type === 'ASSET' || $acc->account_type === 'EXPENSE' ? 'DR' : 'CR') }}',
                                                project_id: '{{ $acc->project_id ?? '' }}',
                                                remarks: '{{ addslashes($acc->remarks ?? '') }}'
                                            })" class="p-1 rounded-lg hover:bg-amber-50 text-slate-400 hover:text-[#a38c29] transition cursor-pointer" title="Edit in Modal">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        Ledger Head ID: #{{ $acc->id }}
                                        @if($acc->project)
                                            · <span class="text-slate-600 font-semibold">{{ $acc->project->name }}</span>
                                        @endif
                                        @if($acc->remarks)
                                            · <span class="italic text-slate-500 font-medium">{{ $acc->remarks }}</span>
                                        @endif
                                    </p>
                                </td>

                                {{-- Type --}}
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($acc->account_type === 'ASSET')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full font-extrabold text-[10px]">ASSET</span>
                                    @elseif($acc->account_type === 'LIABILITY')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full font-extrabold text-[10px]">LIABILITY</span>
                                    @elseif($acc->account_type === 'REVENUE')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full font-extrabold text-[10px]">REVENUE</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full font-extrabold text-[10px]">EXPENSE</span>
                                    @endif
                                </td>

                                {{-- Opening Balance Input --}}
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    @if($is3090)
                                        <div class="font-mono font-bold text-sm text-[#8a7522] py-2 px-3 bg-amber-50/60 rounded-xl border border-amber-200/80 text-right inline-block min-w-[140px]"
                                             x-text="formatCurrency(liveDifference)">
                                            ₹ {{ number_format($variance, 2) }}
                                        </div>
                                    @else
                                        <div class="relative inline-block w-full max-w-[160px]">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-bold">₹</span>
                                            <input type="number" step="0.01" min="0" 
                                                   name="balances[{{ $acc->id }}][amount]"
                                                   x-model.number="rowsData[{{ $acc->id }}].amount"
                                                   @input="recalculateTotals()"
                                                   {{ $isLocked ? 'disabled' : '' }}
                                                   class="w-full pl-7 pr-3 py-2 text-right font-mono font-bold text-xs rounded-xl border {{ $isLocked ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' : 'bg-slate-50 hover:bg-white focus:bg-white text-slate-900 border-slate-200 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20' }} transition outline-none shadow-2xs">
                                        </div>
                                    @endif
                                </td>

                                {{-- Dr / Cr Toggle --}}
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($is3090)
                                        <span class="px-3 py-1 font-mono font-black text-xs rounded-lg border bg-amber-100 text-amber-900 border-amber-300"
                                              x-text="liveDifference === 0 ? 'CR' : liveDifferenceSide">
                                            {{ $varianceSide }}
                                        </span>
                                    @else
                                        <div class="inline-flex rounded-xl border border-slate-200 bg-slate-100 p-0.5 shadow-2xs {{ $isLocked ? 'opacity-50 pointer-events-none' : '' }}">
                                            <button type="button" @click="setRowType({{ $acc->id }}, 'DR')"
                                                    class="px-2.5 py-1 text-[10px] font-black rounded-lg transition cursor-pointer"
                                                    :class="rowsData[{{ $acc->id }}].type === 'DR' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'">
                                                DR
                                            </button>
                                            <button type="button" @click="setRowType({{ $acc->id }}, 'CR')"
                                                    class="px-2.5 py-1 text-[10px] font-black rounded-lg transition cursor-pointer"
                                                    :class="rowsData[{{ $acc->id }}].type === 'CR' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'">
                                                CR
                                            </button>
                                        </div>
                                        <input type="hidden" name="balances[{{ $acc->id }}][type]" :value="rowsData[{{ $acc->id }}].type">
                                    @endif
                                </td>

                                {{-- Effective DR --}}
                                <td class="px-5 py-3.5 text-right font-mono font-bold whitespace-nowrap text-blue-700"
                                    x-text="getEffectiveDrText({{ $acc->id }}, {{ $is3090 ? 'true' : 'false' }})">
                                    @if(!$is3090 && $initType === 'DR' && $initBal > 0)
                                        ₹ {{ number_format($initBal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Effective CR --}}
                                <td class="px-5 py-3.5 text-right font-mono font-bold whitespace-nowrap text-amber-700"
                                    x-text="getEffectiveCrText({{ $acc->id }}, {{ $is3090 ? 'true' : 'false' }})">
                                    @if(!$is3090 && $initType === 'CR' && $initBal > 0)
                                        ₹ {{ number_format($initBal, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                                          :class="getRowStatusClass({{ $acc->id }}, {{ $is3090 ? 'true' : 'false' }})"
                                          x-text="getRowStatusText({{ $acc->id }}, {{ $is3090 ? 'true' : 'false' }})">
                                        {{ $initBal > 0 ? 'Configured' : 'Zero (0.00)' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Empty state when filter has 0 matches --}}
                        <tr id="empty-filter-row" style="display: none;">
                            <td colspan="9" class="py-12 px-6 text-center bg-slate-50/50">
                                <div class="max-w-md mx-auto space-y-2">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-[#8a7522] mx-auto flex items-center justify-center font-bold text-sm">ℹ</div>
                                    <p class="font-black text-slate-800 text-xs uppercase tracking-wider">No Accounts Found Under Selected Filter</p>
                                    <p class="text-[11px] text-slate-500">There are currently no accounts matching this category in your Chart of Accounts.</p>
                                    <button type="button" @click="resetAllFilters()"
                                            class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase tracking-wider transition shadow-sm cursor-pointer">
                                        <span>Show All Heads ({{ $totalAccountsCount }})</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    {{-- Sticky Summary Footer Row --}}
                    <tfoot>
                        <tr class="bg-[#faf6e8] border-t-2 border-b-2 border-[#a38c29] text-slate-900 font-extrabold text-xs tracking-wider shadow-inner">
                            <td colspan="5" class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <span class="text-[#a38c29] font-black uppercase tracking-wider">FINAL TRIAL POSITION:</span>
                                    <span class="text-slate-700 font-bold uppercase tracking-wider">SUM OF OPERATIONAL HEADS</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black border tracking-wider"
                                      :class="liveDifference === 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-amber-100 text-[#8a7522] border-amber-300'"
                                      x-text="liveDifference === 0 ? 'EQUAL' : 'DIFF: ' + liveDifferenceSide">
                                    {{ $varianceSide }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-black text-sm text-blue-700 whitespace-nowrap bg-blue-50/40 border-x border-blue-100/50" x-text="formatCurrency(liveTotalDr)">
                                {{ $formatInr($totalDebits) }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-black text-sm text-amber-700 whitespace-nowrap bg-amber-50/50 border-r border-amber-100/50" x-text="formatCurrency(liveTotalCr)">
                                {{ $formatInr($totalCredits) }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <span x-show="liveDifference === 0" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>BALANCED</span>
                                </span>
                                <span x-show="liveDifference > 0" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-[#a38c29]/15 text-[#7a671b] border border-[#a38c29]/30">
                                    <span>AUTO 3090</span>
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Bottom Save Floating Action Bar (Only visible when unlocked) --}}
            @if(!$isLocked)
                <div class="px-6 py-4 bg-[#fbf9f1] border-t border-amber-200/60 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs text-slate-600 font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                        <span>Remember to save before leaving this page. Click <strong class="text-[#7a671b] font-bold">Freeze & Lock</strong> once opening accounts are balanced.</span>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-extrabold shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition cursor-pointer uppercase tracking-wider">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Opening Balances</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </form>

    {{-- ======================================================== --}}
    {{-- MODAL 0: ADD / EDIT ACCOUNT HEAD & OPENING BALANCE       --}}
    {{-- ======================================================== --}}
    <div x-show="openAccountModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
        <div @click.away="openAccountModal = false" class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0">
            {{-- Dark Header matching 3rd Picture (Units Modal) --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Account Head Setup</p>
                        <h2 class="text-lg font-extrabold text-white" x-text="accountModalMode === 'create' ? 'Add New Account' : 'Edit Account Head'">Add New Account</h2>
                    </div>
                    <button type="button" @click="openAccountModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Form Body --}}
            <form id="account-head-modal-form" action="{{ route('opening-balances.save-account') }}" method="POST">
                @csrf
                <input type="hidden" name="account_id" :value="accountForm.account_id">

                <div class="p-6 bg-white space-y-4 text-left">
                    {{-- Row 1: Code & Type --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Account Code <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="account_code" x-model="accountForm.account_code" required
                                   placeholder="e.g. 1001"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition shadow-2xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Account Type <span class="text-rose-500">*</span>
                            </label>
                            <select name="account_type" x-model="accountForm.account_type" required
                                    @change="if(accountModalMode === 'create') { accountForm.opening_balance_type = (accountForm.account_type === 'ASSET' || accountForm.account_type === 'EXPENSE') ? 'DR' : 'CR'; }"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition cursor-pointer shadow-2xs">
                                <option value="ASSET">Asset (Dr)</option>
                                <option value="LIABILITY">Liability (Cr)</option>
                                <option value="REVENUE">Revenue (Cr)</option>
                                <option value="EXPENSE">Expense (Dr)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: Account Head Name --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                            Account Head Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="account_name" x-model="accountForm.account_name" required
                               placeholder="e.g. Karnataka Bank / Cash in Hand"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition shadow-2xs">
                    </div>

                    {{-- Row 3: Opening Balance & Type --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Opening Balance (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs font-bold">₹</span>
                                <input type="number" step="0.01" min="0" name="opening_balance" x-model="accountForm.opening_balance"
                                       placeholder="0.00"
                                       class="w-full pl-8 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition shadow-2xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Balance Type <span class="text-rose-500">*</span>
                            </label>
                            <select name="opening_balance_type" x-model="accountForm.opening_balance_type" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition cursor-pointer shadow-2xs">
                                <option value="DR">Debit (Dr)</option>
                                <option value="CR">Credit (Cr)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 4: Project & Remarks --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Project {{ count($projects) > 1 ? '(Optional)' : '' }}
                            </label>
                            <select name="project_id" x-model="accountForm.project_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition cursor-pointer shadow-2xs">
                                @if(count($projects) > 1)
                                    <option value="">- None -</option>
                                @elseif(count($projects) === 0)
                                    <option value="">- No Projects Available -</option>
                                @endif
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Remarks
                            </label>
                            <input type="text" name="remarks" x-model="accountForm.remarks"
                                   placeholder="e.g. As per bank statement"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 outline-none transition shadow-2xs">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer matching 3rd Picture --}}
                <div class="px-6 py-4 border-t border-slate-150 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="openAccountModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl uppercase transition shadow-md shadow-[#a38c29]/20 cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="accountModalMode === 'create' ? 'ADD ACCOUNT' : 'SAVE CHANGES'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 1: FREEZE & LOCK OPENING BALANCES CONFIRMATION     --}}
    {{-- ======================================================== --}}
    <div x-show="openLockModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
        <div @click.away="openLockModal = false" class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0">
            {{-- Dark and Gold Header matching Units Modal --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                            FINANCIAL CONTROLS · GO-LIVE FREEZE
                        </p>
                        <h2 class="text-lg font-extrabold text-white">Freeze & Lock Opening Balances</h2>
                    </div>
                    <button type="button" @click="openLockModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 text-center bg-white space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center border border-amber-200 shadow-xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-wider">Lock Initial Financial Position?</h3>
                    <p class="text-xs text-slate-600 font-semibold mt-1">
                        Are you sure you want to finalize and lock the opening balances?
                    </p>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-left text-xs space-y-2 text-slate-600">
                    <div class="flex items-center justify-between font-bold text-slate-800 pb-1 border-b border-slate-200">
                        <span>Total Operational Debits:</span>
                        <span class="font-mono text-blue-700" x-text="formatCurrency(liveTotalDr)">{{ $formatInr($totalDebits) }}</span>
                    </div>
                    <div class="flex items-center justify-between font-bold text-slate-800 pb-1 border-b border-slate-200">
                        <span>Total Operational Credits:</span>
                        <span class="font-mono text-amber-700" x-text="formatCurrency(liveTotalCr)">{{ $formatInr($totalCredits) }}</span>
                    </div>
                    <div class="flex items-center justify-between font-bold text-slate-800">
                        <span>3090 · Equity Absorption:</span>
                        <span class="font-mono text-emerald-700" x-text="formatCurrency(liveDifference) + ' (' + liveDifferenceSide + ')'">
                            {{ $formatInr($variance) }} ({{ $varianceSide }})
                        </span>
                    </div>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-left text-[11px] text-amber-900 font-medium space-y-1">
                    <p class="font-extrabold flex items-center gap-1 text-amber-800">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>ERP Accounting Rule:</span>
                    </p>
                    <p>• Direct edits will be permanently disabled in the Master UI.</p>
                    <p>• Future corrections must be posted as a <strong>Prior Period Adjustment Journal Voucher (JV)</strong> to preserve audit trail integrity.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                <button type="button" @click="openLockModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                <form action="{{ route('opening-balances.lock') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md shadow-[#a38c29]/30 cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>YES, FREEZE & LOCK</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 2: ADMIN UNLOCK CONFIRMATION                       --}}
    {{-- ======================================================== --}}
    <div x-show="openUnlockModal" class="fixed inset-0 !m-0 top-0 left-0 right-0 bottom-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
        <div @click.away="openUnlockModal = false" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col border-0">
            {{-- Dark and Gold Header matching Units Modal --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            ADMINISTRATIVE AUDIT OVERRIDE
                        </p>
                        <h2 class="text-lg font-extrabold text-white">Unlock Opening Balances</h2>
                    </div>
                    <button type="button" @click="openUnlockModal = false" class="text-slate-400 hover:text-white transition cursor-pointer p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 text-center bg-white space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center border border-rose-200 shadow-xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider">Unlock Opening Balances?</h3>
                <p class="text-xs text-slate-600 font-medium">
                    Unlocking permits direct edits in the master ledger grid. Ensure you re-lock once adjustments are finished so future financial reports remain protected.
                </p>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                <button type="button" @click="openUnlockModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                <form action="{{ route('opening-balances.unlock') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        <span>CONFIRM UNLOCK</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Alpine.js Application Logic --}}
<script>
function openingBalanceMasterApp() {
    return {
        openLockModal: false,
        openUnlockModal: false,
        openAccountModal: false,
        defaultProjectId: '{{ count($projects) === 1 ? $projects->first()->id : '' }}',
        accountModalMode: 'create',
        accountForm: {
            account_id: '',
            account_code: '',
            account_name: '',
            account_type: 'ASSET',
            opening_balance: '',
            opening_balance_type: 'DR',
            project_id: '{{ count($projects) === 1 ? $projects->first()->id : '' }}',
            remarks: ''
        },

        openAddAccountModal() {
            this.accountModalMode = 'create';
            this.accountForm = {
                account_id: '',
                account_code: '',
                account_name: '',
                account_type: 'ASSET',
                opening_balance: '',
                opening_balance_type: 'DR',
                project_id: this.defaultProjectId,
                remarks: ''
            };
            this.openAccountModal = true;
        },

        openEditAccountModal(data) {
            this.accountModalMode = 'edit';
            this.accountForm = {
                account_id: data.id || '',
                account_code: data.account_code || '',
                account_name: data.account_name || '',
                account_type: data.account_type || 'ASSET',
                opening_balance: data.opening_balance > 0 ? data.opening_balance : '',
                opening_balance_type: data.opening_balance_type || 'DR',
                project_id: (data.project_id && data.project_id !== '') ? data.project_id : this.defaultProjectId,
                remarks: data.remarks || ''
            };
            this.openAccountModal = true;
        },

        searchQuery: '',
        activeTypeFilter: '',
        balanceStatusFilter: 'all',

        resetAllFilters() {
            this.searchQuery = '';
            this.activeTypeFilter = '';
            this.balanceStatusFilter = 'all';
            this.filterRows();
        },

        // Initialize state of rows from server
        rowsData: {
            @foreach($accounts as $acc)
                @if($acc->account_code !== '3090')
                    {{ $acc->id }}: {
                        amount: {{ (float) $acc->opening_balance }},
                        type: '{{ strtoupper($acc->opening_balance_type ?: ($acc->account_type === 'ASSET' || $acc->account_type === 'EXPENSE' ? 'DR' : 'CR')) }}'
                    },
                @endif
            @endforeach
        },

        liveTotalDr: {{ $totalDebits }},
        liveTotalCr: {{ $totalCredits }},
        liveDifference: {{ $variance }},
        liveDifferenceSide: '{{ $varianceSide }}',
        liveConfiguredCount: {{ $configuredCount }},

        init() {
            this.recalculateTotals();
        },

        setRowType(id, type) {
            if (this.rowsData[id]) {
                this.rowsData[id].type = type;
                this.recalculateTotals();
            }
        },

        recalculateTotals() {
            let drSum = 0;
            let crSum = 0;
            let count = 0;

            for (const id in this.rowsData) {
                const item = this.rowsData[id];
                const amt = parseFloat(item.amount) || 0;
                if (amt > 0) {
                    count++;
                    if (item.type === 'DR') {
                        drSum += amt;
                    } else {
                        crSum += amt;
                    }
                }
            }

            this.liveTotalDr = drSum;
            this.liveTotalCr = crSum;
            this.liveDifference = Math.abs(drSum - crSum);
            this.liveDifferenceSide = drSum > crSum ? 'CR' : (crSum > drSum ? 'DR' : 'BALANCED');
            this.liveConfiguredCount = count;
        },

        getEffectiveDrText(id, is3090) {
            if (is3090) {
                return (this.liveDifferenceSide === 'DR' && this.liveDifference > 0) 
                    ? this.formatCurrency(this.liveDifference) 
                    : '-';
            }
            const item = this.rowsData[id];
            if (item && item.type === 'DR' && parseFloat(item.amount) > 0) {
                return this.formatCurrency(item.amount);
            }
            return '-';
        },

        getEffectiveCrText(id, is3090) {
            if (is3090) {
                return (this.liveDifferenceSide === 'CR' && this.liveDifference > 0) 
                    ? this.formatCurrency(this.liveDifference) 
                    : '-';
            }
            const item = this.rowsData[id];
            if (item && item.type === 'CR' && parseFloat(item.amount) > 0) {
                return this.formatCurrency(item.amount);
            }
            return '-';
        },

        getRowStatusClass(id, is3090) {
            if (is3090) {
                return this.liveDifference === 0 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                    : 'bg-amber-50 text-amber-800 border border-amber-200';
            }
            const item = this.rowsData[id];
            const amt = item ? parseFloat(item.amount) || 0 : 0;
            return amt > 0 
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                : 'bg-slate-100 text-slate-400 border border-slate-200';
        },

        getRowStatusText(id, is3090) {
            if (is3090) {
                return this.liveDifference === 0 ? 'Balanced (0.00)' : 'Active Variance';
            }
            const item = this.rowsData[id];
            const amt = item ? parseFloat(item.amount) || 0 : 0;
            return amt > 0 ? 'Configured' : 'Zero (0.00)';
        },

        filterRows() {
            const query = (this.searchQuery || '').trim().toLowerCase();
            const typeFilter = this.activeTypeFilter;
            const balFilter = this.balanceStatusFilter;

            const rows = document.querySelectorAll('.account-row');
            let visibleIndex = 0;

            rows.forEach(row => {
                const id = row.dataset.id;
                const is3090 = row.dataset.is3090 === 'true';
                const code = (row.dataset.code || '').toLowerCase();
                const name = (row.dataset.name || '').toLowerCase();
                const type = row.dataset.type;

                const item = this.rowsData[id];
                const amt = is3090 ? this.liveDifference : (item ? parseFloat(item.amount) || 0 : 0);

                const matchesQuery = !query || code.includes(query) || name.includes(query);
                const matchesType = !typeFilter || type === typeFilter || (is3090 && typeFilter === 'LIABILITY');
                
                let matchesBalance = true;
                if (balFilter === 'configured') {
                    matchesBalance = amt > 0;
                } else if (balFilter === 'zero') {
                    matchesBalance = amt === 0;
                }

                if (matchesQuery && matchesType && matchesBalance) {
                    row.style.display = '';
                    visibleIndex++;
                    const slCell = row.querySelector('.row-sl-no');
                    if (slCell) slCell.textContent = visibleIndex;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('empty-filter-row');
            if (emptyRow) {
                emptyRow.style.display = (visibleIndex === 0) ? '' : 'none';
            }
        },

        formatCurrency(val) {
            const n = parseFloat(val) || 0;
            return '₹ ' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    };
}
</script>
@endsection
