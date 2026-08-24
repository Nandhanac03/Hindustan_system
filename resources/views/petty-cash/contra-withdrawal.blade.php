@extends('layouts.erp')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen" x-data="contraForm()">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#a38c29] text-white font-bold text-sm">2</span>
            <h1 class="text-2xl font-bold text-gray-900 m-0">Bank Cash Withdrawal (Contra)</h1>
        </div>
        <p class="text-sm text-gray-500 ml-11">Record cash transfers from Karnataka Bank into the site Petty Cash Box.</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: Form -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 p-4">
                    <h2 class="text-[13px] font-extrabold text-[#a38c29] uppercase tracking-wider">Contra Withdrawal Entry</h2>
                </div>
                
                <form action="{{ route('petty-cash.store-contra-withdrawal') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-6">
                        <!-- Voucher No -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Voucher No.</label>
                            <input type="text" name="voucher_number" value="PCON-00016" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-600 outline-none cursor-not-allowed">
                        </div>
                        
                        <!-- Date & Site Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Date</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                            </div>
                            <!-- Site -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Site <span class="text-red-500">*</span></label>
                                <select name="project_id" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Bank Account -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Bank Account <span class="text-red-500">*</span></label>
                            <select name="bank_account_id" x-model="selectedBank" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                <option value="">Select Bank Account</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cash Box -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Cash Box <span class="text-red-500">*</span></label>
                            <select name="cash_box_id" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                                @foreach($cashBoxes as $box)
                                    <option value="{{ $box->id }}">{{ $box->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Amount (₹) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" x-model="amount" @input="updateBalance" step="0.01" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-bold text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all" placeholder="0.00">
                        </div>

                        <!-- Amount in Words -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5 invisible">Amount in Words</label>
                            <input type="text" x-model="amountWords" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 h-10 text-[12px] font-medium text-gray-500 outline-none cursor-not-allowed" placeholder="Amount in words will appear here">
                        </div>

                        <!-- Narration -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Narration <span class="text-red-500">*</span></label>
                            <textarea name="narration" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-3 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all placeholder-gray-400" placeholder="E.g. Cash withdrawn from Karnataka Bank for site petty cash requirements..."></textarea>
                        </div>

                        <!-- Reference / Cheque No -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Reference / Cheque No.</label>
                            <input type="text" name="reference_no" placeholder="CKB123456" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                        </div>

                        <!-- Reference Date -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Reference Date</label>
                            <input type="date" name="reference_date" class="w-full bg-white border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-800 outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] transition-all">
                        </div>
                    </div>
                    
                    <hr class="border-gray-200 my-6">

                    <!-- Attachments -->
                    <div class="mb-8">
                        <label class="block text-[12px] font-extrabold text-[#a38c29] mb-3">Attachments</label>
                        
                        <div class="flex items-center gap-3 flex-wrap">
                            <!-- Mock Attachment Pill -->
                            <div class="flex items-center bg-[#f8f9fa] border border-[#e5e7eb] rounded-lg overflow-hidden group transition-all hover:border-[#a38c29]">
                                <div class="px-3 py-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    <span class="text-[12px] font-bold text-gray-700">cheque_scan.jpg</span>
                                    <span class="text-[11px] text-gray-400">(245 KB)</span>
                                </div>
                                <button type="button" class="px-3 py-2 bg-gray-100 text-gray-600 hover:bg-[#ecfdf5] hover:text-[#10b981] text-[11px] font-bold transition-colors">
                                    Remove
                                </button>
                            </div>
                            
                            <!-- Add Attachment Button -->
                            <label class="flex items-center gap-2 px-4 py-2 border border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-[#a38c29] hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-[12px] font-bold text-gray-600">Add File</span>
                                <input type="file" class="hidden">
                            </label>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">JPG, PNG, PDF up to 2MB</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('petty-cash.balance-register') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 text-[12px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            Cancel
                        </a>
                        
                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-8 py-2.5 bg-[#a38c29] text-white text-[12px] font-bold rounded-lg hover:bg-[#8f7a22] transition-colors shadow-sm shadow-[#a38c29]/30">
                                Save & Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Sidebar Panels -->
        <div class="xl:col-span-1 flex flex-col gap-6">
            
            <!-- Withdrawal Details -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 p-4">
                    <h2 class="text-[13px] font-extrabold text-[#a38c29] uppercase tracking-wider">Withdrawal Details</h2>
                </div>
                <div class="p-5">
                    <table class="w-full text-[12px]">
                        <tbody class="divide-y divide-gray-100 divide-dashed">
                            <tr>
                                <td class="py-3 font-medium text-gray-600">Bank Balance (As on {{ date('d-M-Y') }})</td>
                                <td class="py-3 text-right font-bold text-gray-900" x-text="formatCurrency(selectedBankBalance)">₹ 2,45,600.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-medium text-gray-600">Petty Cash Balance (Before)</td>
                                <td class="py-3 text-right font-bold text-gray-900" x-text="formatCurrency(pettyCashBefore)">₹ 30,750.00</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-medium text-gray-600">Withdrawal Amount</td>
                                <td class="py-3 text-right font-bold text-gray-900" x-text="formatCurrency(amount)">₹ 0.00</td>
                            </tr>
                        </tbody>
                        <tfoot class="border-t border-gray-200">
                            <tr>
                                <td class="pt-4 pb-2 font-bold text-[#10b981] text-[13px]">Petty Cash Balance (After)</td>
                                <td class="pt-4 pb-2 text-right font-extrabold text-[#10b981] text-[15px]" x-text="formatCurrency(pettyCashAfter)">₹ 30,750.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Contra History -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 p-4">
                    <h2 class="text-[13px] font-extrabold text-[#a38c29] uppercase tracking-wider">Contra History (Recent)</h2>
                </div>
                <div class="p-0">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Voucher No.</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-right">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentContras as $contra)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-[11px] font-bold text-gray-700">{{ $contra->voucher_number }}</td>
                                <td class="px-4 py-3 text-[11px] font-medium text-gray-600">{{ \Carbon\Carbon::parse($contra->date)->format('d-M-Y') }}</td>
                                <td class="px-4 py-3 text-[11px] font-bold text-gray-900 text-right">{{ number_format($contra->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-t border-gray-100 text-center bg-gray-50/50">
                    <a href="#" class="text-[11px] font-bold text-[#a38c29] hover:text-[#8f7a22] transition-colors">View All Contra Entries</a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- AlpineJS Logic for Dynamic Calculations -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contraForm', () => ({
            amount: '',
            pettyCashBefore: {{ $pettyCashBalance ?? 0 }},
            selectedBank: '',
            bankBalances: {
                @foreach($bankAccounts as $bank)
                '{{ $bank->id }}': {{ $bank->balance }},
                @endforeach
            },
            
            get selectedBankBalance() {
                return this.selectedBank ? this.bankBalances[this.selectedBank] : 0;
            },
            
            get pettyCashAfter() {
                let amt = parseFloat(this.amount) || 0;
                return this.pettyCashBefore + amt;
            },
            
            get amountWords() {
                let amt = parseFloat(this.amount) || 0;
                if (amt === 0) return '';
                return 'Amount in words placeholder...';
            },

            formatCurrency(value) {
                return '₹ ' + new Intl.NumberFormat('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }
        }))
    })
</script>
@endsection
