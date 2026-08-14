<x-erp-layout title="Realize Instrument" headerTitle="Realize Instrument">

    <div class="max-w-6xl mx-auto space-y-6">
        
        {{-- Header & Breadcrumb --}}
        <div>
            <h1 class="text-xl font-bold text-slate-800">Realize Instrument</h1>
            <p class="text-xs text-slate-500 mt-1">Home / Receipts / Realize Instrument</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 text-emerald-900 border border-emerald-200 rounded-lg text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 text-rose-900 border border-rose-200 rounded-lg text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form id="realizationForm" 
              action="{{ route('cheque-realization.realize', $receipt->id) }}" 
              method="POST" 
              x-data="{ actionRoute: '{{ route('cheque-realization.realize', $receipt->id) }}', newStatus: '' }"
              :action="actionRoute"
              class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                
                {{-- Left Column (Instrument Info) --}}
                <div class="space-y-4">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Receipt No.</label>
                        <input type="text" value="{{ $receipt->receipt_no ?? 'RV/2025-26/'.str_pad($receipt->id, 6, '0', STR_PAD_LEFT) }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Customer</label>
                        <input type="text" value="{{ $receipt->customer->name ?? 'N/A' }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Instrument No.</label>
                        <input type="text" value="{{ $receipt->reference_no ?? 'N/A' }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Instrument Date</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y') }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Bank of Instrument</label>
                        <input type="text" value="{{ $receipt->drawee_bank ?? 'N/A' }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Amount (₹)</label>
                        <input type="text" value="{{ number_format($receipt->amount, 2) }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700 font-semibold" readonly>
                    </div>
                </div>

                {{-- Right Column (Realization Info) --}}
                <div class="space-y-4" x-data="{ 
                    selectedBankId: '{{ $receipt->company_bank_account_id }}',
                    banks: {{ json_encode($companyBankAccounts->map->only('id', 'bank_name', 'account_name', 'account_number', 'branch_name')) }}
                }">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Realization Date</label>
                        <input type="date" name="realization_date" value="{{ date('Y-m-d') }}" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Deposit To Account</label>
                        <select name="company_bank_account_id" x-model="selectedBankId" class="w-2/3 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Account</option>
                            @foreach($companyBankAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->bank_name }} - {{ substr($account->account_number, -4) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Company Bank Account Details Card --}}
                    <div x-show="selectedBankId" class="mt-4 border border-slate-200 rounded-lg p-4 bg-slate-50" style="display: none;">
                        <h4 class="text-[11px] font-bold text-slate-800 mb-3 uppercase tracking-wide">Company Bank Account</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Bank Name</span>
                                <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.bank_name || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Account Name</span>
                                <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.account_name || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Account No.</span>
                                <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.account_number || 'N/A'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-slate-500">Branch</span>
                                <span class="text-xs font-medium text-slate-700" x-text="banks.find(b => b.id == selectedBankId)?.branch_name || 'N/A'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-slate-200">

            {{-- Realization Details --}}
            <h3 class="text-sm font-bold text-slate-800 mb-4">Realization Details</h3>
            
            <div class="space-y-4 max-w-3xl">
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Reference No.</label>
                    <input type="text" name="bank_reference_no" placeholder="e.g. NEFT/INWARD/5187" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Realized By</label>
                    <input type="text" value="{{ auth()->user()->name ?? 'Administrator' }}" class="w-2/3 md:w-3/4 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                </div>
                
                <div class="flex items-start">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600 pt-2">Remarks</label>
                    <textarea name="remarks" rows="2" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" placeholder="Cheque cleared and realized in bank."></textarea>
                </div>

                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Status</label>
                    <div class="w-2/3 md:w-3/4">
                        <span class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded text-[11px] font-bold tracking-wide">
                            Realized
                        </span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="new_status" :value="newStatus" x-bind:disabled="!newStatus">

            <div class="mt-8 flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('cheque-realization.queue') }}" class="px-5 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition">
                    ← Back to Queue
                </a>
                
                <div class="flex items-center gap-3">
                    <button type="button" 
                            @click="actionRoute = '{{ route('cheque-realization.advance-status', $receipt->id) }}'; newStatus = 'cancelled'; $nextTick(() => document.getElementById('realizationForm').submit())" 
                            class="px-5 py-2 bg-slate-100 text-slate-700 border border-slate-300 rounded text-xs font-semibold hover:bg-slate-200 transition">
                        Cancel Instrument
                    </button>
                    
                    <button type="button" 
                            @click="actionRoute = '{{ route('cheque-realization.bounced', $receipt->id) }}'; newStatus = ''; $nextTick(() => document.getElementById('realizationForm').submit())" 
                            class="px-5 py-2 bg-rose-600 text-white rounded text-xs font-semibold hover:bg-rose-700 transition shadow-sm border border-rose-700">
                        Bounce
                    </button>
                    
                    <button type="button" 
                            @click="actionRoute = '{{ route('cheque-realization.realize', $receipt->id) }}'; newStatus = ''; $nextTick(() => { const form = document.getElementById('realizationForm'); if(form.reportValidity()) form.submit(); })"
                            class="px-6 py-2 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-700 transition shadow-sm border border-emerald-700">
                        Realize Cheque
                    </button>
                </div>
            </div>
            
        </form>
    </div>

</x-erp-layout>
