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

        @php
            $currentStatusNameLower = strtolower(str_replace('_', ' ', $receipt->realization_status));
            $matchedCurrentStatus = $chequeStatuses->first(function($item) use ($currentStatusNameLower, $receipt) {
                return strtolower($item->name) === $currentStatusNameLower || 
                       strtolower($item->name) === strtolower($receipt->realization_status);
            });
            $currentStatusId = $matchedCurrentStatus ? $matchedCurrentStatus->id : '';

            $latestLog = $receipt->realizationLogs->first();
            $remarksText = $latestLog?->remarks ?? $receipt->remarks ?? '';
            $bankRef = '';
            if (preg_match('/\(Bank Ref:\s*(.*?)\)/', $remarksText, $matches)) {
                $bankRef = $matches[1];
                $remarksText = trim(str_replace($matches[0], '', $remarksText));
            }
        @endphp

        <form id="realizationForm" 
              method="POST" 
              x-data="{ 
                  selectedStatusId: '{{ $currentStatusId }}',
                  statusName: '',
                  updateStatusName() {
                      if(this.$refs.statusSelect && this.$refs.statusSelect.selectedIndex >= 0) {
                          this.statusName = this.$refs.statusSelect.options[this.$refs.statusSelect.selectedIndex].text.trim().toLowerCase();
                      }
                  },
                  init() {
                      this.$nextTick(() => this.updateStatusName());
                  },
                  get formAction() {
                      if (this.statusName === 'realized') return '{{ route('cheque-realization.realize', $receipt->id) }}';
                      if (this.statusName === 'bounced') return '{{ route('cheque-realization.bounced', $receipt->id) }}';
                      return '{{ route('cheque-realization.advance-status', $receipt->id) }}';
                  },
                  get mappedNewStatus() {
                      const map = {'pending': 'pending', 'cancelled': 'cancelled', 'cheque in hand': 'cheque_in_hand', 'deposited': 'deposited', 'in clearing': 'in_clearing'};
                      return map[this.statusName] || '';
                  }
              }"
              :action="formAction"
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
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Status</label>
                    <div class="w-2/3 md:w-3/4">
                        <select name="cheque_status_id" x-ref="statusSelect" x-model="selectedStatusId" @change="updateStatusName()" class="w-full px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Status</option>
                            @foreach($chequeStatuses as $status)
                                <option value="{{ $status->id }}">
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-start">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600 pt-2" 
                           x-text="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Reason for ' + (statusName.charAt(0).toUpperCase() + statusName.slice(1)) : 'Remarks'">Remarks</label>
                    <textarea name="remarks" rows="2" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500" 
                              :placeholder="['pending', 'cancelled', 'bounced'].includes(statusName) ? 'Enter reason...' : 'Enter remarks (Optional)'">{{ $remarksText }}</textarea>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Reference No.</label>
                    <input type="text" name="bank_reference_no" value="{{ $bankRef }}" placeholder="e.g. NEFT/INWARD/5187" class="w-2/3 md:w-3/4 px-3 py-2 border border-slate-300 rounded text-xs text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-xs font-medium text-slate-600">Realized By</label>
                    <input type="text" value="{{ auth()->user()->name ?? 'Administrator' }}" class="w-2/3 md:w-3/4 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                </div>

            </div>

            <input type="hidden" name="new_status" :value="mappedNewStatus" x-bind:disabled="!mappedNewStatus">

            <div class="mt-8 flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('cheque-realization.queue') }}" class="px-5 py-2 bg-white border border-slate-300 text-slate-700 rounded text-xs font-semibold hover:bg-slate-50 transition">
                    ← Back to Queue
                </a>
                
                <div class="flex items-center gap-3">
                    <button type="submit" 
                            class="px-6 py-2 bg-[#a38c29] text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-[#8a7522] transition shadow-sm border border-[#a38c29]">
                        Submit
                    </button>
                </div>
            </div>
            
        </form>
    </div>

</x-erp-layout>
