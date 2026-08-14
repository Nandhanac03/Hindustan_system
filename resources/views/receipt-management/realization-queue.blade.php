<x-erp-layout title="Cheque Clearance Queue" headerTitle="Cheque Clearance Queue">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{
        realizeModalOpen: false,
        bounceModalOpen: false,
        targetReceipt: null,
        openRealizeModal(r) { this.targetReceipt = r; this.realizeModalOpen = true; },
        openBounceModal(r) { this.targetReceipt = r; this.bounceModalOpen = true; }
    }">

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

        {{-- Header & Action Bar matching Receipt Management UI --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div>
                <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                    <span class="text-slate-300">›</span>
                    <a href="{{ route('receipt-management.index') }}" class="hover:text-slate-600 transition">Receipt Management</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-[#a38c29] font-black">Cheque Clearance Queue</span>
                </div>
                <h1 class="text-xl font-black text-slate-900 mt-1">⏳ Cheque Clearance Queue</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Instruments awaiting bank clearance. Mark as Realized to credit the treasury balance.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('vouchers.treasury-payment.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>💸 TREASURY DISBURSEMENT</span>
                </a>
                <a href="{{ route('receipt-management.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-slate-800">
                    <span>← BACK TO RECEIPTS</span>
                </a>
            </div>
        </div>

        {{-- ── KPI EXECUTIVE CARDS BAR ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Card 1: Total Pending Amount (Gold Accent) --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#a38c29]">Total Pending Amount</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">₹{{ number_format($totalPendingAmount ?? 0, 2) }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">{{ $pendingReceipts->total() }} Instruments Uncleared</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>

            {{-- Card 2: Cheque in Hand (Sky Blue Accent) --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-sky-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-sky-700">Cheque in Hand</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $pendingReceipts->where('realization_status', 'cheque_in_hand')->count() }}</h3>
                    <span class="text-[10px] text-sky-700 font-bold block mt-0.5">Physical cheques not yet deposited</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>

            {{-- Card 3: Deposited — Awaiting Clearance (Blue/Indigo Accent) --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-blue-600 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">Deposited — Awaiting Clearance</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $pendingReceipts->where('realization_status', 'deposited')->count() }}</h3>
                    <span class="text-[10px] text-blue-700 font-bold block mt-0.5">Submitted to bank, pending confirm</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
            </div>
        </div>

        {{-- Filters Bar --}}
        <form method="GET" action="{{ route('receipt-management.realization-queue') }}" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Company Bank Account</label>
                    <select name="company_bank_account_id" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                        <option value="">All Bank Accounts</option>
                        @foreach($companyBankAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ request('company_bank_account_id') == $acc->id ? 'selected' : '' }}>
                                {{ $acc->bank_name }} {{ $acc->account_number ? '('.$acc->account_number.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1.5">Payment Mode</label>
                    <select name="payment_mode" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]">
                        <option value="">All Modes</option>
                        @foreach($paymentModes as $pm)
                            <option value="{{ $pm->name }}" {{ request('payment_mode') == $pm->name ? 'selected' : '' }}>{{ $pm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-[#a38c29] cursor-pointer">
                        🔍 FILTER
                    </button>
                </div>
            </div>
        </form>

        {{-- Status Legend --}}
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Status Legend:</span>
            <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[10px] font-black">⏳ Pending</span>
            <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 border border-sky-300 text-[10px] font-black">🖐 Cheque in Hand</span>
            <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 border border-blue-300 text-[10px] font-black">🏦 Deposited</span>
            <span class="text-[10px] text-slate-400 font-medium ml-2">→ Mark Realized to credit bank balance</span>
        </div>

        {{-- Main Table matching Receipt Management Table Styling --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 text-slate-900 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-900">INSTRUMENTS AWAITING CLEARANCE</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Showing {{ $pendingReceipts->firstItem() }}–{{ $pendingReceipts->lastItem() }} of {{ $pendingReceipts->total() }} pending instruments</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-black border border-amber-200 uppercase tracking-wider">
                    {{ $pendingReceipts->total() }} PENDING
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[11px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-4 py-3.5 text-white">RECEIPT #</th>
                            <th class="px-4 py-3.5 text-white">DATE</th>
                            <th class="px-4 py-3.5 text-white">CUSTOMER</th>
                            <th class="px-4 py-3.5 text-white">COMPANY BANK ACCOUNT</th>
                            <th class="px-4 py-3.5 text-white">DRAWEE BANK</th>
                            <th class="px-4 py-3.5 text-white">CHEQUE DATE</th>
                            <th class="px-4 py-3.5 text-right text-white">AMOUNT</th>
                            <th class="px-4 py-3.5 text-center text-white">MODE</th>
                            <th class="px-4 py-3.5 text-center text-white">STATUS</th>
                            <th class="px-4 py-3.5 text-center text-white">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold">
                        @forelse($pendingReceipts as $receipt)
                            @php
                                $rObj = [
                                    'id' => $receipt->id,
                                    'ref' => $receipt->reference_no ?: 'REC-' . str_pad((string)$receipt->id, 5, '0', STR_PAD_LEFT),
                                    'customer_name' => $receipt->customer?->name ?? ($receipt->payer_name ?? ($receipt->sale?->customer?->name ?? 'General Payer')),
                                    'payer_name' => $receipt->payer_name,
                                    'company_bank_account_name' => $receipt->companyBankAccount?->bank_name ?? 'General Account',
                                    'company_bank_account_number' => $receipt->companyBankAccount?->account_number ?? '—',
                                    'source_bank' => $receipt->drawee_bank ?: 'Customer Bank / Payer Instrument',
                                    'destination_bank' => ($receipt->companyBankAccount?->bank_name ?? 'General Account') . ($receipt->companyBankAccount?->account_number ? ' (A/C: '.$receipt->companyBankAccount->account_number.')' : ''),
                                    'drawee_bank' => $receipt->drawee_bank ?: '',
                                    'cheque_date' => $receipt->cheque_date?->format('d M Y') ?: '',
                                    'amount' => $receipt->amount,
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-all duration-150 border-l-4 border-l-amber-500">
                                <td class="px-4 py-3.5 font-mono font-bold text-slate-900">
                                    {{ $rObj['ref'] }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $receipt->receipt_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900">{{ $rObj['customer_name'] }}</td>
                                <td class="px-4 py-3.5">
                                    @if($receipt->companyBankAccount)
                                        <div class="font-extrabold text-slate-800">{{ $receipt->companyBankAccount->bank_name }}</div>
                                        @if($receipt->companyBankAccount->account_number)
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">Acc: {{ $receipt->companyBankAccount->account_number }}</div>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">—Not Assigned—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-700">{{ $receipt->drawee_bank ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-slate-500 font-medium">{{ $receipt->cheque_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3.5 font-mono font-black text-slate-950 text-right text-sm">
                                    ₹{{ number_format($receipt->amount, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-block
                                        {{ in_array($receipt->payment_mode, ['Cheque', 'CHEQUE', 'DD', 'Demand Draft (DD)']) ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $receipt->payment_mode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @php
                                        $statusConfig = [
                                            'pending'        => ['bg-amber-100 text-amber-800 border-amber-300', '⏳'],
                                            'cheque_in_hand' => ['bg-sky-100 text-sky-800 border-sky-300', '🖐'],
                                            'deposited'      => ['bg-blue-100 text-blue-800 border-blue-300', '🏦'],
                                        ];
                                        $sc = $statusConfig[$receipt->realization_status] ?? ['bg-slate-100 text-slate-600 border-slate-300', '•'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full {{ $sc[0] }} border text-[10px] font-black uppercase tracking-wider inline-block">
                                        {{ $sc[1] }} {{ \App\Models\Receipt::STATUSES[$receipt->realization_status] ?? $receipt->realization_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                                        {{-- Realize Button --}}
                                        @if($receipt->companyBankAccount)
                                            <button type="button"
                                                    @click="openRealizeModal({{ json_encode($rObj) }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[11px] font-extrabold uppercase tracking-wider shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 cursor-pointer">
                                                <svg class="w-3.5 h-3.5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>REALIZE</span>
                                            </button>
                                        @else
                                            <span class="px-2 py-1 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-[9px] font-extrabold uppercase tracking-wider">⚠ Assign Bank First</span>
                                        @endif

                                        {{-- Advance Status Button --}}
                                        @if($receipt->realization_status !== 'deposited')
                                            <form action="{{ route('receipt-management.advance-status', $receipt->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="new_status"
                                                       value="{{ $receipt->realization_status === 'pending' ? 'cheque_in_hand' : 'deposited' }}">
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 hover:border-sky-300 rounded-xl text-[11px] font-extrabold uppercase tracking-wider shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer">
                                                    <span>{{ $receipt->realization_status === 'pending' ? '🖐 IN HAND' : '🏦 DEPOSITED' }}</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Bounce Button --}}
                                        <button type="button"
                                                @click="openBounceModal({{ json_encode($rObj) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 hover:border-rose-300 rounded-xl text-[11px] font-extrabold uppercase tracking-wider shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>BOUNCE</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center border border-emerald-200">
                                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">All Clear!</h3>
                                        <p class="text-xs text-slate-500 font-medium">No pending cheques awaiting realization. All instruments have been processed.</p>
                                        <a href="{{ route('receipt-management.index') }}" class="text-xs font-black text-[#a38c29] hover:underline uppercase tracking-wider">← Back to Receipt Management</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pendingReceipts->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $pendingReceipts->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Custom Realize Confirmation Modal --}}
        <div x-show="realizeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-emerald-500" @click.away="realizeModalOpen = false">
                <div class="bg-gradient-to-r from-emerald-700 to-emerald-900 text-white px-6 py-5 flex items-center justify-between border-b border-emerald-600">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 text-white border border-white/30 flex items-center justify-center text-lg font-black shrink-0">
                            ✅
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wider">CONFIRM CHEQUE REALIZATION</h3>
                            <p class="text-[11px] text-emerald-100 font-medium">Clear instrument &amp; credit bank balance</p>
                        </div>
                    </div>
                    <button type="button" @click="realizeModalOpen = false" class="w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white transition flex items-center justify-center font-bold text-sm">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="targetReceipt">
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2.5">
                            <div class="flex justify-between items-center text-emerald-950 font-bold border-b border-emerald-200 pb-2">
                                <span class="text-[10px] text-emerald-700 uppercase tracking-wider font-black">Instrument Reference</span>
                                <div class="text-right">
                                    <span class="font-mono font-black text-slate-900 block" x-text="targetReceipt ? targetReceipt.ref : ''"></span>
                                    <span class="text-[10px] text-slate-500 font-medium block" x-show="targetReceipt && targetReceipt.cheque_date" x-text="targetReceipt ? 'Date: ' + targetReceipt.cheque_date : ''"></span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Customer / Payer</span>
                                <span class="font-black text-slate-900 text-sm" x-text="targetReceipt ? (targetReceipt.customer_name && targetReceipt.customer_name !== '—' ? targetReceipt.customer_name : (targetReceipt.payer_name || 'General Payer')) : ''"></span>
                            </div>

                            <!-- SOURCE BANK (Customer / Drawee Bank) -->
                            <div class="flex justify-between items-center text-slate-800 bg-white/80 p-2 rounded-xl border border-slate-200/80">
                                <div class="flex items-center gap-1.5 text-[#a38c29] font-black text-[10px] uppercase tracking-wider">
                                    <span>📥 SOURCE BANK</span>
                                    <span class="text-slate-400 font-normal">(Customer / Drawee)</span>
                                </div>
                                <span class="font-extrabold text-slate-900 text-xs" x-text="targetReceipt ? (targetReceipt.source_bank || targetReceipt.drawee_bank || 'Customer Bank / Payer Instrument') : 'Customer Bank'"></span>
                            </div>

                            <!-- DESTINATION BANK (Our Company Bank Account) -->
                            <div class="flex justify-between items-center text-slate-800 bg-emerald-100/70 p-2 rounded-xl border border-emerald-200">
                                <div class="flex items-center gap-1.5 text-emerald-800 font-black text-[10px] uppercase tracking-wider">
                                    <span>🏦 DESTINATION BANK</span>
                                    <span class="text-emerald-600 font-normal">(Our Company Account)</span>
                                </div>
                                <span class="font-extrabold text-emerald-950 text-xs" x-text="targetReceipt ? (targetReceipt.destination_bank || targetReceipt.company_bank_account_name || 'General Account') : 'Company Bank'"></span>
                            </div>

                            <div class="flex justify-between items-center text-slate-900 pt-2 border-t border-emerald-200">
                                <span class="text-[10px] text-emerald-800 uppercase tracking-wider font-black">Realization Amount</span>
                                <span class="font-mono font-black text-emerald-900 text-lg" x-text="targetReceipt ? '₹' + parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : ''"></span>
                            </div>
                        </div>
                    </template>

                    <p class="text-xs font-semibold text-slate-600">
                        Are you sure you want to mark this instrument as <strong class="text-emerald-700">REALIZED</strong>? This will credit the funds directly to the company bank account treasury balance.
                    </p>

                    <form :action="targetReceipt ? `/receipt-management/${targetReceipt.id}/realize` : '#'" method="POST" class="pt-2 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1">🏦 DESTINATION COMPANY BANK ACCOUNT (WHERE CLEARANCE CREDITED)</label>
                            <select name="company_bank_account_id" :value="targetReceipt ? targetReceipt.company_bank_account_id : ''" required
                                    class="w-full px-3 py-2 bg-emerald-50 border border-emerald-300 rounded-xl text-xs font-extrabold text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition cursor-pointer">
                                @foreach($companyBankAccounts as $bAcc)
                                    <option value="{{ $bAcc->id }}">
                                        {{ $bAcc->bank_name }} — A/C: {{ $bAcc->account_number ?: 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">📅 BANK CLEARANCE / REALIZATION DATE</label>
                            <input type="date" name="realized_at" :value="new Date().toISOString().split('T')[0]" required
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="realizeModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
                                <span>✅ Confirm Realize & Credit Treasury</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Custom Bounce Confirmation Modal --}}
        <div x-show="bounceModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-rose-500" @click.away="bounceModalOpen = false">
                <div class="bg-gradient-to-r from-rose-700 to-rose-900 text-white px-6 py-5 flex items-center justify-between border-b border-rose-600">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/20 text-white border border-white/30 flex items-center justify-center text-lg font-black shrink-0">
                            ❌
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white uppercase tracking-wider">CONFIRM CHEQUE BOUNCE</h3>
                            <p class="text-[11px] text-rose-100 font-medium">Mark instrument as dishonoured</p>
                        </div>
                    </div>
                    <button type="button" @click="bounceModalOpen = false" class="w-8 h-8 rounded-full bg-black/20 hover:bg-black/40 text-white transition flex items-center justify-center font-bold text-sm">✕</button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="targetReceipt">
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs space-y-2.5">
                            <div class="flex justify-between items-center text-rose-950 font-bold border-b border-rose-200 pb-2">
                                <span class="text-[10px] text-rose-700 uppercase tracking-wider font-black">Instrument Reference</span>
                                <span class="font-mono font-black" x-text="targetReceipt ? targetReceipt.ref : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-800">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Customer / Payer</span>
                                <span class="font-black text-slate-900 text-sm" x-text="targetReceipt ? (targetReceipt.customer_name && targetReceipt.customer_name !== '—' ? targetReceipt.customer_name : (targetReceipt.payer_name || 'General Payer')) : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-800" x-show="targetReceipt && targetReceipt.drawee_bank">
                                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Drawee Bank</span>
                                <span class="font-bold text-slate-800" x-text="targetReceipt ? targetReceipt.drawee_bank : ''"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-900 pt-2 border-t border-rose-200">
                                <span class="text-[10px] text-rose-800 uppercase tracking-wider font-black">Amount</span>
                                <span class="font-mono font-black text-rose-900 text-lg" x-text="targetReceipt ? '₹' + parseFloat(targetReceipt.amount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2}) : ''"></span>
                            </div>
                        </div>
                    </template>

                    <p class="text-xs font-semibold text-slate-600">
                        Are you sure you want to mark this instrument as <strong class="text-rose-700">BOUNCED / DISHONOURED</strong>? No balance change will occur.
                    </p>

                    <form :action="targetReceipt ? `/receipt-management/${targetReceipt.id}/bounced` : '#'" method="POST" class="pt-2 flex items-center justify-end gap-3">
                        @csrf
                        <button type="button" @click="bounceModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold uppercase rounded-xl transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
                            <span>❌ Mark Bounced</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-erp-layout>
