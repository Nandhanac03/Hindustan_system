<x-erp-layout title="Cheque & Receipt Entry Desk" headerTitle="Cheque & Receipt Entry Workspace">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="chequeReceiptEntryDesk()" x-init="init()">
        
        {{-- Flash Notifications --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black">✕</button>
            </div>
        @endif

        <!-- Top Header & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Customer Management & Collections</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Cheque & Receipt Entry Display</span>
            </div>

            <!-- Intake Modal Button (Brand Gold Matching) -->
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" @click="openAddModal()" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-[#a38c29]/40 cursor-pointer">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>RECORD NEW CHEQUE / RECEIPT ENTRY</span>
                </button>
            </div>
        </div>

        <!-- ── KPI EXECUTIVE CARDS BAR ── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Realized Collections -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'realized']) }}" class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs hover:shadow-md transition-all flex items-center justify-between group">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#a38c29] block">Total Realized Collections</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalCollectionAmount ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">{{ $totalReceiptsCount ?? 0 }} Total Receipts Logged</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </a>

            <!-- Card 2: Cheques Pending Clearance -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'cheques']) }}" class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-amber-500 shadow-xs hover:shadow-md transition-all flex items-center justify-between group">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-700">Cheques Pending Clearance</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-black uppercase tracking-wider animate-pulse">UNCLEARED</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($pendingRealizationAmount ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-amber-700 font-bold block mt-0.5">{{ $pendingRealizationCount ?? 0 }} Cheques Awaiting Clearance</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </a>

            <!-- Card 3: Total Treasury Liquidity -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-blue-600 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 block">Total Treasury Liquidity</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalLiquidity ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-blue-700 font-bold block mt-0.5">{{ count($companyBankAccounts ?? []) }} Active Bank Accounts</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18v-7m4 7v-7m4 7v-7m4 7v-7M4 10l8-6 8 6"/></svg>
                </div>
            </div>

            <!-- Card 4: Dishonoured / Bounced Cheques -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'bounced']) }}" class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-rose-600 shadow-xs hover:shadow-md transition-all flex items-center justify-between group">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">Bounced Cheques</span>
                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-wider">DISHONOURED</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $bouncedCount ?? 0 }}</h3>
                    <span class="text-[10px] text-rose-700 font-bold block mt-0.5">₹{{ number_format($bouncedAmount ?? 0, 2) }} Total Bounced</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </a>
        </div>

        <!-- ── TAB NAVIGATION BAR (1 SINGLE ROW FIT - EQUAL FLEX DISTRIBUTION) ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1.5 flex items-center justify-between gap-1 w-full overflow-hidden">
            <!-- Tab 1: All Receipts -->
            <a href="{{ route('cheque-receipt-entry.index', ['tab' => 'all']) }}"
               class="flex-1 min-w-0 inline-flex items-center justify-center gap-1 px-2 py-2 rounded-xl text-[10px] font-black uppercase tracking-tight transition whitespace-nowrap {{ $activeTab === 'all' ? 'bg-[#a38c29] text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <svg class="w-3.5 h-3.5 shrink-0 {{ $activeTab === 'all' ? 'text-white' : 'text-[#a38c29]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="truncate">All Receipts</span>
                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-mono leading-none flex items-center justify-center shrink-0 {{ $activeTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $totalReceiptsCount }}</span>
            </a>

            <!-- Dynamic Status Tabs from Cheque Status Master Table -->
            @foreach($chequeStatusesMap as $stKey => $stData)
                <a href="{{ route('cheque-receipt-entry.index', ['tab' => $stKey]) }}"
                   class="flex-1 min-w-0 inline-flex items-center justify-center gap-1 px-2 py-2 rounded-xl text-[10px] font-black uppercase tracking-tight transition whitespace-nowrap {{ $activeTab === $stKey ? $stData['tab_active_classes'] : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg class="w-3.5 h-3.5 shrink-0 {{ $activeTab === $stKey ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($stKey === 'realized')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @elseif($stKey === 'bounced')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @elseif($stKey === 'cheque_in_hand' || $stKey === 'pending')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        @endif
                    </svg>
                    <span class="truncate">{{ $stData['name'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-mono leading-none flex items-center justify-center shrink-0 {{ $activeTab === $stKey ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $stData['count'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- ── MAIN CHEQUE & RECEIPT REGISTER TABLE (READ-ONLY DISPLAY WITH STATUS) ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        @if($activeTab === 'all') All Receipts Display Register
                        @elseif($activeTab === 'cheques') Pending Cheque Intake Queue Display
                        @elseif($activeTab === 'realized') Realized Collections Display Register
                        @elseif($activeTab === 'bounced') Dishonoured / Bounced Cheques Log Display
                        @else {{ strtoupper(str_replace('_', ' ', $activeTab)) }} Display Register
                        @endif
                    </span>
                    <span class="text-[11px] bg-slate-200 text-slate-700 px-2.5 py-0.5 rounded-full font-bold">
                        {{ count($allReceiptsFormatted) }} Records
                    </span>
                </div>

                {{-- Filter Bar --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <form method="GET" action="{{ route('cheque-receipt-entry.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <div class="relative flex items-center">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search receipt #, customer..."
                                   class="pl-8 pr-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-60 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>

                        <select name="payment_mode" @change="$el.form.submit()" class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]">
                            <option value="">All Payment Modes</option>
                            <option value="Cheque" {{ request('payment_mode') === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="NEFT/RTGS" {{ request('payment_mode') === 'NEFT/RTGS' ? 'selected' : '' }}>NEFT / RTGS</option>
                            <option value="UPI" {{ request('payment_mode') === 'UPI' ? 'selected' : '' }}>UPI</option>
                            <option value="Cash" {{ request('payment_mode') === 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-2xs transition cursor-pointer">Filter</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                {{-- 📋 MAIN RECEIPTS DISPLAY REGISTER TABLE --}}
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider sticky top-0 z-10 shadow-2xs">
                        <tr class="text-left">
                            <th class="px-3 py-3 w-[100px]">RECEIPT #</th>
                            <th class="px-3 py-3 w-[100px]">DATE</th>
                            <th class="px-3 py-3 w-[180px]">CUSTOMER / PAYER</th>
                            <th class="px-3 py-3 w-[180px]">COMPANY BANK ACCOUNT</th>
                            <th class="px-3 py-3 text-right w-[110px]">AMOUNT</th>
                            <th class="px-3 py-3 text-center w-[100px]">MODE</th>
                            <th class="px-3 py-3 text-center w-[140px]">REALIZATION STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                        @forelse($allReceiptsFormatted as $r)
                            <tr class="hover:bg-amber-50/20 transition-colors border-b border-slate-100 cursor-pointer" @click="openViewModal({{ json_encode($r) }})">
                                <td class="px-3 py-3 text-left font-mono font-bold text-slate-900">
                                    {{ $r['ref'] }}
                                </td>

                                <td class="px-3 py-3 text-left text-slate-600 font-medium">
                                    {{ $r['date'] }}
                                </td>

                                <td class="px-3 py-3 text-left">
                                    <div class="font-black text-slate-900 text-[11.5px]">{{ $r['customer_name'] }}</div>
                                    <div class="text-[9.5px] text-slate-500 font-semibold mt-0.5">{{ $r['project_name'] }} {{ $r['unit_name'] !== '—' ? '('.$r['unit_name'].')' : '' }}</div>
                                </td>

                                <td class="px-3 py-3 text-left">
                                    <div class="font-extrabold text-slate-800 text-[11px]">{{ $r['company_bank_account_name'] }}</div>
                                    @if($r['company_bank_account_number'])
                                        <div class="text-[9.5px] font-mono text-slate-500">A/C: {{ $r['company_bank_account_number'] }}</div>
                                    @endif
                                </td>

                                <td class="px-3 py-3 text-right font-mono font-black text-slate-950 text-sm">
                                    ₹{{ number_format((float) $r['amount'], 2) }}
                                </td>

                                <td class="px-3 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-md text-[9.5px] font-black uppercase tracking-wider inline-block {{ $r['payment_mode'] === 'Cheque' ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                        {{ $r['payment_mode'] ?: 'Cheque' }}
                                    </span>
                                <td class="px-3 py-3 text-center">
                                    @php
                                        $rst = strtolower($r['realization_status'] ?? 'pending');
                                        $rstMaster = $chequeStatusesMap[$rst] ?? null;
                                    @endphp
                                    @if($rstMaster)
                                        <span class="px-3 py-1 rounded-full text-[9.5px] font-black uppercase tracking-wider border inline-flex items-center justify-center whitespace-nowrap {{ $rstMaster['badge_classes'] }}">
                                            <span>{{ $rstMaster['name'] }}</span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[9.5px] font-black uppercase tracking-wider border inline-flex items-center justify-center whitespace-nowrap
                                            {{ $rst === 'realized' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : '' }}
                                            {{ $rst === 'cheque_in_hand' ? 'bg-amber-50 text-amber-800 border-amber-300' : '' }}
                                            {{ $rst === 'deposited' ? 'bg-blue-50 text-blue-800 border-blue-300' : '' }}
                                            {{ $rst === 'bounced' ? 'bg-rose-50 text-rose-800 border-rose-300' : '' }}
                                            {{ $rst === 'pending' ? 'bg-slate-100 text-slate-700 border-slate-300' : '' }}">
                                            <span>
                                                @if($rst === 'cheque_in_hand') CHEQUE IN HAND
                                                @elseif($rst === 'realized') REALIZED
                                                @elseif($rst === 'deposited') DEPOSITED
                                                @elseif($rst === 'bounced') BOUNCED
                                                @elseif($rst === 'pending') PENDING
                                                @else {{ strtoupper(str_replace('_', ' ', $rst)) }}
                                                @endif
                                            </span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic font-medium">
                                    No Cheque & Receipt entries found for this view.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($receipts, 'links'))
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    {{ $receipts->links() }}
                </div>
            @endif
        </div>

        <!-- ── MODAL: INTAKE NEW CHEQUE & RECEIPT ENTRY ── -->
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="addModalOpen = false">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/40 flex items-center justify-center text-[#f3e5ab] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-0.5">INTAKE ENTRY</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white">LOG CHEQUE & RECEIPT ENTRY</h3>
                        </div>
                    </div>
                    <button type="button" @click="addModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>

                <form action="{{ route('cheque-receipt-entry.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">COMPANY BANK ACCOUNT <span class="text-rose-500 font-bold">*</span></label>
                            <select name="company_bank_account_id" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                                <option value="">Select Company Bank Account</option>
                                @foreach($companyBankAccounts as $bAcc)
                                    <option value="{{ $bAcc->id }}" {{ $bAcc->is_default ? 'selected' : '' }}>
                                        {{ $bAcc->bank_name }} — A/C: {{ $bAcc->account_number ?: 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">RECEIPT INTAKE DATE <span class="text-rose-500 font-bold">*</span></label>
                            <input type="date" name="receipt_date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">CUSTOMER / PAYER <span class="text-rose-500 font-bold">*</span></label>
                            <select name="customer_id" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                                <option value="">Select Customer</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?: 'No Phone' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SITE PROJECT</label>
                            <select name="project_id"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                                <option value="">Select Project (Optional)</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAYMENT MODE <span class="text-rose-500 font-bold">*</span></label>
                            <select name="payment_mode" x-model="selectedMode" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                                <option value="Cheque">Cheque</option>
                                <option value="NEFT/RTGS">NEFT / RTGS / IMPS</option>
                                <option value="UPI">UPI / GPay</option>
                                <option value="Cash">Cash Deposit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">RECEIPT AMOUNT (₹) <span class="text-rose-500 font-bold">*</span></label>
                            <input type="number" step="0.01" name="amount" placeholder="e.g. 50000" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- Cheque Specific Fields -->
                    <div x-show="selectedMode === 'Cheque'" class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl space-y-3">
                        <div class="text-[10px] font-black text-amber-900 uppercase tracking-wider">CHEQUE INSTRUMENT DETAILS</div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">CHEQUE NO. / REF NO.</label>
                                <input type="text" name="reference_no" placeholder="e.g. CHQ-88219"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-mono font-bold focus:ring-2 focus:ring-[#a38c29]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">CHEQUE DATE</label>
                                <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold focus:ring-2 focus:ring-[#a38c29]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase mb-1">DRAWEE BANK NAME</label>
                            <input type="text" name="drawee_bank" placeholder="e.g. HDFC Bank, SBI..."
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#a38c29]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">REMARKS / NOTES</label>
                        <textarea name="remarks" rows="2" placeholder="Entry notes or reference narrative..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none transition-all"></textarea>
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md border border-[#a38c29]/40 cursor-pointer">SAVE CHEQUE & RECEIPT ENTRY</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── MODAL: READ-ONLY RECEIPT DISPLAY DETAILS ── -->
        <div x-show="viewModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.away="viewModalOpen = false">
                <div class="bg-slate-900 p-5 text-white flex items-center justify-between border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/40 flex items-center justify-center text-[#f3e5ab] shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-0.5">RECEIPT DETAILS DISPLAY</span>
                            <h3 class="font-black text-base uppercase tracking-wider text-white" x-text="selectedReceipt ? selectedReceipt.ref : 'Receipt Details'"></h3>
                        </div>
                    </div>
                    <button type="button" @click="viewModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>

                <template x-if="selectedReceipt">
                    <div class="p-6 space-y-4 text-xs text-slate-800">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Amount</span>
                                <span class="text-2xl font-black text-slate-900 font-mono" x-text="'₹' + Number(selectedReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Realization Status</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border inline-flex items-center justify-center"
                                      :class="{
                                          'bg-emerald-50 text-emerald-800 border-emerald-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'realized',
                                          'bg-amber-50 text-amber-800 border-amber-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'cheque_in_hand',
                                          'bg-blue-50 text-blue-800 border-blue-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'deposited',
                                          'bg-rose-50 text-rose-800 border-rose-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'bounced',
                                          'bg-slate-100 text-slate-700 border-slate-300': (selectedReceipt.realization_status || selectedReceipt.status) === 'pending'
                                      }">
                                    <span x-text="
                                        (selectedReceipt.realization_status || selectedReceipt.status) === 'cheque_in_hand' ? 'CHEQUE IN HAND' :
                                        ((selectedReceipt.realization_status || selectedReceipt.status) === 'realized' ? 'REALIZED' :
                                        ((selectedReceipt.realization_status || selectedReceipt.status) === 'deposited' ? 'DEPOSITED' :
                                        ((selectedReceipt.realization_status || selectedReceipt.status) === 'bounced' ? 'BOUNCED' : 'PENDING')))
                                    "></span>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Customer / Payer</span>
                                <span class="font-black text-slate-900 text-sm" x-text="selectedReceipt.customer_name || '—'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Receipt Date</span>
                                <span class="font-bold text-slate-800" x-text="selectedReceipt.date || '—'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Site Project</span>
                                <span class="font-bold text-slate-800" x-text="selectedReceipt.project_name || '—'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Payment Mode</span>
                                <span class="font-bold text-slate-800" x-text="selectedReceipt.payment_mode || '—'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-3">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Company Bank Account</span>
                                <span class="font-bold text-slate-900" x-text="selectedReceipt.company_bank_account_name || '—'"></span>
                                <span class="text-[10px] font-mono text-slate-500 block" x-show="selectedReceipt.company_bank_account_number" x-text="'A/C: ' + selectedReceipt.company_bank_account_number"></span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Cheque / Reference No</span>
                                <span class="font-mono font-bold text-slate-900" x-text="selectedReceipt.reference_no || '—'"></span>
                            </div>
                        </div>

                        <template x-if="selectedReceipt.drawee_bank">
                            <div class="border-b border-slate-100 pb-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Drawee Bank</span>
                                <span class="font-semibold text-slate-800" x-text="selectedReceipt.drawee_bank"></span>
                            </div>
                        </template>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Remarks / Narrative</span>
                            <p class="p-3 bg-slate-50 rounded-xl text-slate-700 italic border border-slate-200" x-text="selectedReceipt.remarks || 'No remarks recorded.'"></p>
                        </div>

                        <div class="pt-3 flex justify-end border-t border-slate-100">
                            <button type="button" @click="viewModalOpen = false" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold uppercase transition">CLOSE DISPLAY</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    <script>
    function chequeReceiptEntryDesk() {
        return {
            addModalOpen: false,
            viewModalOpen: false,
            selectedMode: 'Cheque',
            selectedReceipt: null,

            init() {
                // Read-only display ready
            },

            openAddModal() {
                this.addModalOpen = true;
            },

            openViewModal(receipt) {
                this.selectedReceipt = receipt;
                this.viewModalOpen = true;
            }
        };
    }
    </script>
</x-erp-layout>
