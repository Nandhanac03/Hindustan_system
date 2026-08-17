<x-erp-layout title="Treasury Dashboard" headerTitle="Treasury Dashboard">

    <div class="max-w-[1800px] mx-auto space-y-6">
        
        {{-- Header & Breadcrumb --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Treasury Dashboard</h1>
                <p class="text-xs text-slate-500 mt-1">Home / Treasury / Dashboard</p>
            </div>
            <div>
                <!-- <a href="{{ route('outward-payments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Make Payment
                </a> -->
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-lg text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: Total Bank Balance --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Bank Balance</span>
                    </div>
                    <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Verified</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">₹{{ number_format($totalBalance, 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Across all registered company accounts</p>
                </div>
            </div>
            
            {{-- Card 2: Available Balance --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Available Balance</span>
                    </div>
                    <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Synced</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">₹{{ number_format($availableBalance, 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Real-time liquid assets</p>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div x-data="{ 
                activeBank: null, 
                activeBankName: '', 
                activeBankAcct: '',
                transactions: {{ json_encode($recentTransactions) }},
                get activeTransactions() {
                    return this.activeBank ? (this.transactions[this.activeBank] || []) : [];
                }
            }">

            {{-- Bank Accounts Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                            Bank Accounts Directory
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all active company bank accounts and their balances.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                            <tr>
                                <th class="px-5 py-3 w-16 text-center">#</th>
                                <th class="px-5 py-3">Bank Account</th>
                                <th class="px-5 py-3">Account No.</th>
                                <th class="px-5 py-3 text-right">Current Balance</th>
                                <th class="px-5 py-3 text-right">Available Balance</th>
                                <th class="px-5 py-3 text-center w-28">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($bankAccounts as $index => $account)
                                <tr class="hover:bg-slate-50 transition cursor-pointer group"
                                    :class="activeBank === {{ $account->id }} ? 'bg-[#a38c29]/5' : ''"
                                    @click="activeBank = {{ $account->id }}; activeBankName = '{{ $account->bank_name }}'; activeBankAcct = '{{ substr($account->account_number, -4) }}'">
                                    <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $account->bank_name }}</td>
                                    <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $account->account_number ?: 'N/A' }}</td>
                                    <td class="px-5 py-3 text-right text-xs font-black text-[#a38c29]">
                                        ₹{{ number_format($account->current_balance, 2) }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-xs font-black text-emerald-600">
                                        ₹{{ number_format($account->current_balance, 2) }}
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <button class="p-2 rounded-xl transition-colors inline-flex items-center justify-center"
                                                :class="activeBank === {{ $account->id }} ? 'bg-[#a38c29]/20 text-[#a38c29] border border-[#a38c29]/30' : 'bg-[#a38c29]/10 text-[#a38c29] hover:bg-[#a38c29]/20'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if($bankAccounts->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">No bank accounts found</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Recent Transactions Table --}}
            <div x-show="activeBank" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" style="display: none;">
                <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Recent Transactions - <span x-text="activeBankName"></span> (<span x-text="activeBankAcct"></span>)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-700 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 w-32">Date</th>
                                <th class="px-6 py-4">Voucher No.</th>
                                <th class="px-6 py-4">Narration</th>
                                <th class="px-6 py-4 w-24">Type</th>
                                <th class="px-6 py-4 text-right w-40">Amount (₹)</th>
                                <th class="px-6 py-4 text-right w-40">Balance (₹)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="txn in activeTransactions" :key="txn.voucher_no">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4" x-text="txn.date"></td>
                                    <td class="px-6 py-4 text-primary-700 font-medium text-xs" x-text="txn.voucher_no"></td>
                                    <td class="px-6 py-4 text-xs text-slate-700" x-text="txn.narration"></td>
                                    <td class="px-6 py-4 font-semibold text-xs" :class="txn.type === 'Credit' ? 'text-emerald-600' : 'text-rose-600'" x-text="txn.type"></td>
                                    <td class="px-6 py-4 text-right text-xs" x-text="(Number(txn.amount) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-800 text-xs" x-text="(Number(txn.balance) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                </tr>
                            </template>
                            <tr x-show="activeTransactions.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-xs font-medium">
                                    No recent transactions found for this account.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</x-erp-layout>
