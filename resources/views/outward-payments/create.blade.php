<x-erp-layout title="Make Payment" headerTitle="Make Payment">

    <div class="max-w-[1000px] mx-auto space-y-6 bg-white p-8 rounded-xl shadow-sm border border-slate-200">
        
        {{-- Header & Breadcrumb --}}
        <div>
            <h1 class="text-xl font-bold text-slate-800">Make Payment</h1>
            <p class="text-xs text-slate-500 mt-1">Home / Payments / Make Payment</p>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 text-rose-900 border border-rose-200 rounded-lg text-sm font-semibold">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('outward-payments.store') }}" method="POST" enctype="multipart/form-data" class="pt-6 border-t border-slate-100" x-data="{
            selectedAccountId: '',
            amount: 0,
            selectedPaymentType: '{{ $paymentTypes[0] ?? '' }}',
            payeesByType: {{ json_encode($payeesByType) }},
            get availablePayees() {
                return this.payeesByType[this.selectedPaymentType] || [];
            },
            bankAccounts: {{ json_encode($companyBankAccounts->map->only('id', 'bank_name', 'account_number', 'current_balance')) }},
            get availableBalance() {
                const acc = this.bankAccounts.find(b => b.id == this.selectedAccountId);
                return acc ? parseFloat(acc.current_balance) : 0;
            },
            get balanceAfterPayment() {
                const bal = this.availableBalance - parseFloat(this.amount || 0);
                return isNaN(bal) ? 0 : bal;
            }
        }">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                {{-- Left Column --}}
                <div class="space-y-4">
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment Type</label>
                        <select name="payment_type" x-model="selectedPaymentType" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                            @foreach($paymentTypes as $type)
                                <option value="{{ $type }}">{{ $type }} Payment</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payee</label>
                        <select name="payee" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                            <template x-for="p in availablePayees" :key="p.id">
                                <option :value="p.name" x-text="p.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" x-model="amount" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment Mode</label>
                        <select name="payment_mode" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="NEFT">NEFT</option>
                            <option value="RTGS">RTGS</option>
                            <option value="IMPS">IMPS</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Pay From Account</label>
                        <select name="company_bank_account_id" x-model="selectedAccountId" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Account</option>
                            @foreach($companyBankAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->bank_name }} - {{ substr($account->account_number, -4) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Available Balance (₹)</label>
                        <div class="w-2/3">
                            <span class="text-emerald-600 font-bold text-sm" x-text="availableBalance > 0 ? availableBalance.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'"></span>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Remarks</label>
                        <input type="text" name="remarks" placeholder="Payment towards materials supplied" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-medium text-slate-600">Reference No.</label>
                            <input type="text" name="reference_no" placeholder="NEFT/OUT/2654" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-medium text-slate-600">UTR No.</label>
                            <input type="text" name="utr_no" placeholder="KKBKS2025052002654" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center">
                            <label class="w-1/3 text-xs font-medium text-blue-600">Attachment</label>
                            <div class="w-2/3 flex items-center gap-2">
                                <input type="file" name="attachment" class="text-xs text-slate-600 file:mr-2 file:py-1 file:px-2 file:border-0 file:bg-slate-100 file:text-slate-700 file:rounded file:text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- Balance After Payment Card --}}
                    <div class="mt-6 p-6 bg-indigo-50 border border-indigo-100 rounded-xl text-center">
                        <h3 class="text-xs font-bold text-indigo-900 mb-2">Balance After Payment (₹)</h3>
                        <p class="text-2xl font-black" :class="balanceAfterPayment < 0 ? 'text-rose-600' : 'text-indigo-700'" x-text="balanceAfterPayment.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></p>
                    </div>
                </div>

            </div>

            <div class="mt-10 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('treasury.dashboard') }}" class="px-5 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700 transition"
                        x-bind:disabled="balanceAfterPayment < 0"
                        x-bind:class="balanceAfterPayment < 0 ? 'opacity-50 cursor-not-allowed' : ''">
                    Proceed
                </button>
            </div>
        </form>

    </div>

</x-erp-layout>
