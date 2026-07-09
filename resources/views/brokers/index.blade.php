<x-erp-layout title="Brokerage Management" headerTitle="Brokerage & Commission Management">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Brokerage & Commission Management</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage real estate brokers, track project/sale commission rates (default ~2%), and monitor transaction-wise earnings.</p>
        </div>

        <div class="flex items-center gap-3" x-data="{ openRegister: false }">
            {{-- Link to Payable Report --}}
            <a href="{{ route('brokers.payable-report') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all duration-200 hover:bg-slate-800 hover:shadow-lg">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m3.293-7.707a1 1 0 111.414 1.414L9 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 110-4 2 2 0 010 4z"/></svg>
                Brokerage Payable Report
                <span class="ml-1 px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-mono">₹{{ number_format($totalPayable, 0) }}</span>
            </a>

            {{-- Register Broker Button --}}
            <button @click="openRegister = true" 
                    class="inline-flex items-center gap-2 rounded-xl bg-[#a38c29] px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all duration-200 hover:bg-[#8d7923] hover:shadow-lg">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Register Broker Profile
            </button>

            {{-- Register Modal --}}
            <div x-show="openRegister" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity"
                 style="display: none;">
                 <div @click.away="openRegister = false" 
                      class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5 transform transition-all">
                      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                          <div class="flex items-center gap-2">
                              <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                              <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Register New Broker Profile</h3>
                          </div>
                          <button @click="openRegister = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                      </div>

                      <form action="{{ route('brokers.store') }}" method="POST" class="space-y-4">
                          @csrf
                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Broker / Agency Name *</label>
                              <input type="text" name="name" required placeholder="e.g. Apex Realty Brokers"
                                     class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide flex items-center justify-between">
                                  <span>Default Commission % *</span>
                                  <span class="text-slate-400 font-normal text-[9px]">(Typically 2% per sale)</span>
                              </label>
                              <div class="relative">
                                  <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" value="2.00" required
                                         class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all pr-8 font-mono font-bold">
                                  <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                              </div>
                              <p class="text-[9px] text-slate-400">This percentage is applied by default to all project sales handled by this broker.</p>
                          </div>

                          <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-[10px] text-amber-800 space-y-1">
                              <span class="font-bold flex items-center gap-1">
                                  <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                  Automated Accounting Integration:
                              </span>
                              <p>A dedicated liability ledger account will be automatically created in the accounts master for tracking commissions payable.</p>
                          </div>

                          <div class="pt-3 flex justify-end gap-2.5">
                              <button type="button" @click="openRegister = false" 
                                      class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                                  Cancel
                              </button>
                              <button type="submit" 
                                      class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                                  Save Profile
                              </button>
                          </div>
                      </form>
                 </div>
            </div>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- Key Metrics KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Card 1: Accrued (Locked) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/5 rounded-full pointer-events-none"></div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Accrued Commission (Locked)</span>
                <div class="text-2xl font-black text-amber-600 font-mono mt-1">₹{{ number_format($totalAccrued, 2) }}</div>
            </div>
            <div class="mt-3 text-[10px] text-slate-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>
                <span>Payable only after full payment or EMI completion</span>
            </div>
        </div>

        {{-- Card 2: Payable (Unlocked) --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-950 rounded-2xl border border-slate-800 p-5 shadow-lg relative overflow-hidden text-white flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/10 rounded-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Unlocked & Payable Commission</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[9px] font-bold uppercase tracking-wide">Ready for Disbursement</span>
                </div>
                <div class="text-2xl font-black text-white font-mono mt-1">₹{{ number_format($totalPayable, 2) }}</div>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <span class="text-[10px] text-slate-300">100% payment / EMI cleared</span>
                <a href="{{ route('brokers.payable-report') }}" class="text-[10px] text-[#a38c29] hover:text-[#d0b855] font-bold underline">View Payable Report →</a>
            </div>
        </div>

        {{-- Card 3: Paid Commission --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/5 rounded-full pointer-events-none"></div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Disbursed Commission</span>
                <div class="text-2xl font-black text-indigo-700 font-mono mt-1">₹{{ number_format($totalPaid, 2) }}</div>
            </div>
            <div class="mt-3 text-[10px] text-slate-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                <span>Successfully settled across all broker accounts</span>
            </div>
        </div>
    </div>

    {{-- Registered Brokers Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Registered Broker Profiles & Commission Rates</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Manage default commission percentages (~2%) and view broker-wise ledger account balances.</p>
            </div>
            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-2xs self-start sm:self-auto">{{ $brokers->count() }} Broker(s) Active</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Broker & Ledger Info</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Default Commission %</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Sales Handled</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Accrued (Locked)</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Payable (Unlocked)</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Paid Out</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($brokers as $broker)
                        <tr class="hover:bg-slate-50/70 transition-colors" x-data="{ openEdit: false, openView: false }">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                    <span>{{ $broker->name }}</span>
                                </div>
                                <div class="text-[9px] text-slate-400 font-mono mt-0.5 flex items-center gap-1">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-600 font-bold">A/C: {{ $broker->linkedAccount->code ?? 'N/A' }}</span>
                                    <span>{{ $broker->linkedAccount->name ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200/70 text-amber-900 font-bold font-mono text-xs shadow-2xs">
                                    <span>{{ number_format($broker->default_commission_pct, 2) }}%</span>
                                </div>
                                @if(abs($broker->default_commission_pct - 2.00) < 0.01)
                                    <span class="text-[9px] text-slate-400 block mt-0.5">Standard Rate</span>
                                @else
                                    <span class="text-[9px] text-indigo-600 font-semibold block mt-0.5">Custom Rate</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $broker->total_deals }} Deal(s)</div>
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5">₹{{ number_format($broker->total_sale_value, 2) }}</div>
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-amber-700">
                                ₹{{ number_format($broker->accrued_commission, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-sm {{ $broker->payable_commission > 0 ? 'text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-200' : 'text-slate-500' }}">
                                    ₹{{ number_format($broker->payable_commission, 2) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-indigo-700">
                                ₹{{ number_format($broker->paid_commission, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openView = true" title="View Broker Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEdit = true" title="Edit Broker Rate" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>

                                {{-- View Modal --}}
                                <div x-show="openView" 
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity text-left"
                                     style="display: none;">
                                     <div @click.away="openView = false" 
                                          class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-lg space-y-5">
                                          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                              <div class="flex items-center gap-2">
                                                  <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                                                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                  </div>
                                                  <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Broker Profile & Ledger Details</h3>
                                              </div>
                                              <button @click="openView = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                                          </div>

                                          <div class="space-y-4">
                                              <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                                                  <div>
                                                      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Broker / Agency Name</span>
                                                      <span class="text-base font-extrabold text-slate-900">{{ $broker->name }}</span>
                                                  </div>
                                                  <div class="text-right">
                                                      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Commission Structure</span>
                                                      <span class="px-2.5 py-1 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-mono font-bold text-xs inline-block mt-0.5">{{ number_format($broker->default_commission_pct, 2) }}% Default</span>
                                                  </div>
                                              </div>

                                              <div class="grid grid-cols-2 gap-3">
                                                  <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Linked Ledger Account</span>
                                                      <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $broker->linkedAccount->name ?? 'Unlinked' }}</span>
                                                      <span class="text-[10px] font-mono text-slate-500 block">Code: {{ $broker->linkedAccount->code ?? 'N/A' }}</span>
                                                  </div>
                                                  <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                                                      <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Deals & Sales</span>
                                                      <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $broker->total_deals }} Closed Deal(s)</span>
                                                      <span class="text-[10px] font-mono text-slate-500 block">Value: ₹{{ number_format($broker->total_sale_value, 2) }}</span>
                                                  </div>
                                              </div>

                                              <div class="grid grid-cols-3 gap-3 pt-2">
                                                  <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200/60 text-center">
                                                      <span class="text-[9px] font-bold text-amber-800 uppercase block">Accrued (Locked)</span>
                                                      <span class="text-xs font-bold font-mono text-amber-900 mt-1 block">₹{{ number_format($broker->accrued_commission, 2) }}</span>
                                                  </div>
                                                  <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/60 text-center">
                                                      <span class="text-[9px] font-bold text-emerald-800 uppercase block">Payable (Ready)</span>
                                                      <span class="text-xs font-bold font-mono text-emerald-900 mt-1 block">₹{{ number_format($broker->payable_commission, 2) }}</span>
                                                  </div>
                                                  <div class="p-3 rounded-xl bg-indigo-50/60 border border-indigo-200/60 text-center">
                                                      <span class="text-[9px] font-bold text-indigo-800 uppercase block">Total Paid Out</span>
                                                      <span class="text-xs font-bold font-mono text-indigo-900 mt-1 block">₹{{ number_format($broker->paid_commission, 2) }}</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="pt-3 flex justify-between items-center border-t border-slate-100">
                                              <button type="button" @click="openView = false" 
                                                      class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                                                  Close
                                              </button>
                                              <a href="{{ route('brokers.payable-report', ['broker_id' => $broker->id]) }}" 
                                                 class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md inline-flex items-center gap-1.5">
                                                  <span>Full Ledger Statement</span>
                                                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                              </a>
                                          </div>
                                     </div>
                                </div>

                                {{-- Edit Modal --}}
                                <div x-show="openEdit" 
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity text-left"
                                     style="display: none;">
                                     <div @click.away="openEdit = false" 
                                          class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5">
                                          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                              <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Edit Broker Rate: {{ $broker->name }}</h3>
                                              <button @click="openEdit = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                                          </div>

                                          <form action="{{ route('brokers.update', $broker->id) }}" method="POST" class="space-y-4">
                                              @csrf
                                              @method('PUT')
                                              <div class="space-y-1.5">
                                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Broker / Agency Name *</label>
                                                  <input type="text" name="name" value="{{ $broker->name }}" required
                                                         class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                                              </div>

                                              <div class="space-y-1.5">
                                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Default Commission % *</label>
                                                  <div class="relative">
                                                      <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" value="{{ $broker->default_commission_pct }}" required
                                                             class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all pr-8 font-mono font-bold">
                                                      <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                                                  </div>
                                              </div>

                                              <div class="pt-3 flex justify-end gap-2.5">
                                                  <button type="button" @click="openEdit = false" 
                                                          class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                                                      Cancel
                                                  </button>
                                                  <button type="submit" 
                                                          class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                                                      Update Changes
                                                  </button>
                                              </div>
                                          </form>
                                     </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-450 italic">No brokers registered yet. Click "Register Broker Profile" above to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transaction-wise Commission Visibility Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Transaction-wise Commission Visibility</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Real-time visibility into every property deal, commission amount, and payment completion status.</p>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('brokers.index') }}" class="flex items-center gap-2">
                <select name="broker_id" onchange="this.form.submit()"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none shadow-2xs font-semibold">
                    <option value="">All Brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
                @if(request('broker_id'))
                    <a href="{{ route('brokers.index') }}" class="text-[10px] text-slate-400 hover:text-slate-700 font-bold underline">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1100px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Booking & Date</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Property / Project</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Broker / Agent</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Net Sale Value</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Commission Calc</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Payment Progress</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Commission Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deals as $deal)
                        @php
                            $booking = $deal->booking;
                            $entry = $deal->commissionEntries->first();
                            $status = $entry->status ?? 'Accrued';
                            $commAmount = $entry->amount ?? round(($deal->sale_value * ($deal->commission_pct_override / 100)), 2);
                            
                            $badgeClass = match($status) {
                                'Payable' => 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-2xs font-bold',
                                'Paid' => 'bg-indigo-50 text-indigo-700 border-indigo-200 font-bold',
                                default => 'bg-amber-50 text-amber-700 border-amber-200 font-semibold'
                            };

                            $statusLabel = match($status) {
                                'Payable' => 'Payable (Unlocked)',
                                'Paid' => 'Paid Out',
                                default => 'Accrued (Locked)'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#a38c29] font-mono">{{ $booking->booking_number ?? 'N/A' }}</div>
                                <div class="text-[9px] text-slate-400 mt-0.5">{{ $deal->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] font-semibold text-slate-700 mt-0.5">{{ $booking->customer->name ?? 'Customer' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $deal->project->name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">Unit: <span class="font-bold text-slate-700 font-mono">{{ $booking->unit->door_no ?? 'N/A' }}</span></div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $deal->broker->name ?? 'Direct' }}</div>
                                <div class="text-[9px] text-slate-400 font-mono">Rate: {{ number_format($deal->commission_pct_override ?? $deal->broker->default_commission_pct, 2) }}%</div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                ₹{{ number_format($deal->sale_value, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-mono font-black text-slate-900 text-sm">₹{{ number_format($commAmount, 2) }}</div>
                                <div class="text-[9px] text-slate-400 uppercase mt-0.5">@ {{ number_format($deal->commission_pct_override ?? 2.00, 2) }}% of sale</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($booking)
                                    @if($booking->outstanding <= 0)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            100% Paid / EMI Complete
                                        </span>
                                    @else
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-[10px]">
                                                <span class="text-slate-500 font-semibold">Pending EMI</span>
                                                <span class="font-mono font-bold text-rose-600">₹{{ number_format($booking->outstanding, 2) }}</span>
                                            </div>
                                            <div class="w-28 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                @php
                                                    $pctPaid = $booking->amount > 0 ? (($booking->amount - $booking->outstanding) / $booking->amount) * 100 : 0;
                                                @endphp
                                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ min(100, max(0, $pctPaid)) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 block">{{ number_format($pctPaid, 0) }}% collected</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic text-[10px]">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="badge-pill border px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase {{ $badgeClass }} inline-block">
                                    {{ $statusLabel }}
                                </span>
                                @if($status === 'Accrued')
                                    <span class="text-[9px] text-slate-400 block mt-1 italic">Unlocks on full payment</span>
                                @elseif($status === 'Payable')
                                    <a href="{{ route('brokers.payable-report') }}" class="text-[9px] text-[#a38c29] hover:underline font-bold block mt-1">Disburse Now →</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-450 italic">No broker sales or transactions recorded yet. When bookings are registered with a broker, commission entries will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $deals->links() }}
            </div>
        @endif
    </div>

</div>

</x-erp-layout>
