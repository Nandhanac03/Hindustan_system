@extends('layouts.erp')

@section('content')
<div class="max-w-[1800px] mx-auto space-y-6" x-data="contraForm()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2 mb-6">
        <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
            <a href="#" class="hover:text-slate-600 transition">Home</a>
            <span class="text-slate-300">›</span>
            <span>Petty Cash</span>
            <span class="text-slate-300">›</span>
            <span class="text-[#a38c29] font-black">Bank Cash Withdrawal (Contra)</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Column: Form -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Cash Transfer</span>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Contra Withdrawal Entry
                        </h2>
                    </div>
                </div>
                
                <form action="{{ route('petty-cash.store-contra-withdrawal') }}" method="POST" enctype="multipart/form-data" class="px-6 pb-6 pt-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-6">
                        <!-- Voucher No -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1.5">Voucher No.</label>
                            <input type="text" name="voucher_number" value="{{ $nextVoucherNo }}" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 h-10 text-[13px] font-medium text-gray-600 outline-none cursor-not-allowed">
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
                            <!-- Selected File Pill -->
                            <template x-if="fileName">
                                <div class="flex items-center bg-[#f8f9fa] border border-[#e5e7eb] rounded-lg overflow-hidden group transition-all hover:border-[#a38c29]">
                                    <div class="px-3 py-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span class="text-[12px] font-bold text-gray-700" x-text="fileName"></span>
                                        <span class="text-[11px] text-gray-400" x-text="'(' + fileSize + ')'"></span>
                                    </div>
                                    <button type="button" @click="removeFile" class="px-3 py-2 bg-gray-100 text-gray-600 hover:bg-[#fef2f2] hover:text-[#ef4444] text-[11px] font-bold transition-colors">
                                        Remove
                                    </button>
                                </div>
                            </template>
                            
                            <!-- Add Attachment Button -->
                            <template x-if="!fileName">
                                <label class="flex items-center gap-2 px-4 py-2 border border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-[#a38c29] hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="text-[12px] font-bold text-gray-600">Add File</span>
                                    <input type="file" name="attachment" x-ref="fileInput" @change="handleFileChange" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                                </label>
                            </template>
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-gradient-to-r from-[#FAF0D7] via-[#F6F3E9] to-white border-b border-[#EAE3CD]">
                    <h2 class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest">WITHDRAWAL DETAILS</h2>
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-5 py-4 bg-gradient-to-r from-[#FAF0D7] via-[#F6F3E9] to-white border-b border-[#EAE3CD]">
                    <h2 class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest">CONTRA HISTORY (RECENT)</h2>
                </div>
                <div class="p-0">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-white">
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
                    <a href="{{ route('petty-cash.balance-register') }}" class="text-[11px] font-bold text-[#a38c29] hover:text-[#8f7a22] transition-colors">View All Contra Entries</a>
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
            fileName: null,
            fileSize: null,
            
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    let size = (file.size / 1024).toFixed(0);
                    if (size > 1024) {
                        this.fileSize = (size / 1024).toFixed(2) + ' MB';
                    } else {
                        this.fileSize = size + ' KB';
                    }
                }
            },
            
            removeFile() {
                this.fileName = null;
                this.fileSize = null;
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },
            
            get selectedBankBalance() {
                return this.selectedBank ? this.bankBalances[this.selectedBank] : 0;
            },
            
            get pettyCashAfter() {
                let amt = parseFloat(this.amount) || 0;
                return this.pettyCashBefore + amt;
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
