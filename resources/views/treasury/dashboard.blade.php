<x-erp-layout title="Treasury Dashboard" headerTitle="Treasury Dashboard">

    <div class="max-w-7xl mx-auto space-y-6">
        
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
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total Bank Balance (All Accounts)</h3>
                <p class="text-3xl font-black text-indigo-700">₹{{ number_format($totalBalance, 2) }}</p>
            </div>
            
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-6">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Available Balance (All Accounts)</h3>
                <p class="text-3xl font-black text-emerald-700">₹{{ number_format($availableBalance, 2) }}</p>
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
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800">Bank Accounts</h3>
                </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 w-12">#</th>
                            <th class="px-6 py-4">Bank Account</th>
                            <th class="px-6 py-4">Account No.</th>
                            <th class="px-6 py-4 text-right">Current Balance (₹)</th>
                            <th class="px-6 py-4 text-right">Available Balance (₹)</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($bankAccounts as $index => $account)
                            <tr class="hover:bg-slate-50 transition cursor-pointer"
                                :class="activeBank === {{ $account->id }} ? 'border-2 border-emerald-400 bg-emerald-50/20' : ''"
                                @click="activeBank = {{ $account->id }}; activeBankName = '{{ $account->bank_name }}'; activeBankAcct = '{{ substr($account->account_number, -4) }}'">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-blue-600">{{ $account->bank_name }}</td>
                                <td class="px-6 py-4 font-medium">{{ substr($account->account_number, -4) }}</td>
                                <td class="px-6 py-4 text-right font-black" :class="activeBank === {{ $account->id }} ? 'text-emerald-600' : '{{ $account->current_balance > 0 ? 'text-emerald-600' : 'text-slate-900' }}'">
                                    {{ number_format($account->current_balance, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-black" :class="activeBank === {{ $account->id }} ? 'text-emerald-600' : '{{ $account->current_balance > 0 ? 'text-emerald-600' : 'text-slate-900' }}'">
                                    {{ number_format($account->current_balance, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="p-2 transition" :class="activeBank === {{ $account->id }} ? 'text-blue-600 bg-blue-50 rounded-full' : 'text-slate-400 hover:text-blue-600'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if($bankAccounts->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">
                                    No company bank accounts found.
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
                                    <td class="px-6 py-4 text-blue-600 text-xs" x-text="txn.voucher_no"></td>
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
