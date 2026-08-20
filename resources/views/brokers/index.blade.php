<x-erp-layout title="Agent Master Directory" headerTitle="Agent Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ openRegister: false }">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Agent Master Directory</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage real estate brokers, track default commission percentages, and monitor general profile details.</p>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 rounded-2xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <form method="GET" action="{{ route('brokers.index') }}" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all" x-data="{ search: '{{ request('search', '') }}' }">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
            {{-- Search: Name --}}
            <div class="relative sm:col-span-2 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Search by name..."
                       x-model="search"
                       class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center" x-show="search">
                    <a href="{{ route('brokers.index') }}"
                       class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('brokers.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </a>
            <button type="button" @click="openRegister = true"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-slate-900/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider">
                <svg class="w-4 h-4 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Broker
            </button>
        </div>
    </form>

    {{-- Register Modal --}}
    <div x-show="openRegister" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openRegister = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Brokerage Directory</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Register Broker</h2>
                      </div>
                      <button type="button" @click="openRegister = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <form action="{{ route('brokers.store') }}" method="POST" x-data="{ errors: {}, name: '', default_commission_pct: '2.00', submitRegister(e) { let errs = {}; if(!this.name || !String(this.name).trim()) errs.name = ['The broker name field is required.']; if(!this.default_commission_pct) errs.default_commission_pct = ['The commission % field is required.']; if(Object.keys(errs).length > 0) { e.preventDefault(); this.errors = errs; return false; } } }" @submit="submitRegister($event)" novalidate>
                  @csrf
                  <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                      <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Broker / Agency Name <span class="text-rose-500">*</span></label>
                              <input type="text" name="name" x-model="name" required placeholder="e.g. Apex Realty Brokers"
                                     :class="errors.name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                     class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                              <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.name) ? errors.name[0] : errors.name"></p></template>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide flex items-center justify-between">
                                  <span>Default Commission % <span class="text-rose-500">*</span></span>
                                  <span class="text-slate-400 font-normal text-[9px]">(Typically 2% per sale)</span>
                              </label>
                              <div class="relative">
                                  <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" x-model="default_commission_pct" required
                                         :class="errors.default_commission_pct ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                         class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all pr-8 font-mono font-bold shadow-sm">
                                  <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                              </div>
                              <template x-if="errors.default_commission_pct"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.default_commission_pct) ? errors.default_commission_pct[0] : errors.default_commission_pct"></p></template>
                              <p class="text-[9px] text-slate-400">This percentage is applied by default to all project sales handled by this broker.</p>
                          </div>

                          <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-[10px] text-amber-800 space-y-1">
                              <span class="font-bold flex items-center gap-1">
                                  <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                  Automated Accounting Integration:
                              </span>
                              <p>A dedicated liability ledger account will be automatically created in the accounts master for tracking commissions payable.</p>
                          </div>
                      </div>
                  </div>

                  <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                      <button type="button" @click="openRegister = false" 
                              class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                          Cancel
                      </button>
                      <button type="submit" 
                              class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                          Save Profile
                      </button>
                  </div>
              </form>
         </div>
    </div>

    {{-- Registered Brokers Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #brokers-table thead th { border-color: #8a7522 !important; }
            #brokers-tbody tr:nth-child(even) { background-color: #F6F3E9 !important; }
            #brokers-tbody tr:hover { background-color: #ebe5d0 !important; }
        </style>
        <div class="overflow-x-auto">
            <table id="brokers-table" class="w-full text-xs text-left min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">Broker</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Default Rate</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">Deals Closed</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Accrued (Locked)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Payable (Unlocked)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Paid Out</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="brokers-tbody" class="divide-y divide-slate-100">
                    @forelse($brokers as $broker)
                        <tr class="table-row transition-colors text-center text-xs font-semibold text-slate-700" x-data="{ openEdit: false, openView: false, openDelete: false }">
                            <td class="px-3 py-3 border text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[#a38c29] flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                        {{ strtoupper(substr($broker->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight">{{ $broker->name }}</span>
                                        <span class="text-[9px] text-slate-500 font-medium">{{ $broker->linkedAccount->name ?? 'Unlinked' }} ({{ $broker->linkedAccount->code ?? 'N/A' }})</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]">
                                    {{ number_format($broker->default_commission_pct, 2) }}%
                                </span>
                            </td>
                            <td class="px-3 py-3 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]">
                                    {{ $broker->total_deals }} Deals
                                </span>
                                <span class="block text-[9px] text-slate-500 font-mono mt-0.5">₹{{ number_format($broker->total_sale_value, 2) }}</span>
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-amber-700">
                                ₹{{ number_format($broker->accrued_commission, 2) }}
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-emerald-600">
                                ₹{{ number_format($broker->payable_commission, 2) }}
                            </td>
                            <td class="px-3 py-3 border text-right font-mono font-bold text-indigo-600">
                                ₹{{ number_format($broker->paid_commission, 2) }}
                            </td>
                            <td class="px-3 py-3 border text-right">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    <button @click="openView = true" title="View Broker Details" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="openEdit = true" title="Edit Broker Rate" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @if($broker->total_deals == 0)
                                        <button @click="openDelete = true" title="Delete Broker" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <button disabled class="p-2 rounded-lg bg-slate-100 text-slate-400 opacity-50 cursor-not-allowed shadow-sm" title="Cannot delete broker with associated sales">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>

                                {{-- View Modal --}}
                                 <div x-show="openView" 
                                      class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
                                      style="display: none;" x-transition.opacity>
                                      <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openView = false">
                                          {{-- Header --}}
                                          <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                                              <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                                              <div class="relative z-10 flex items-center justify-between gap-4">
                                                  <div>
                                                      <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Broker Profile</span>
                                                      <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Profile & Ledger Details</h2>
                                                  </div>
                                                  <button type="button" @click="openView = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                                              </div>
                                          </div>

                                          <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                                              <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
                                                  <div>
                                                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Broker / Agency Name</span>
                                                      <span class="text-sm font-extrabold text-slate-900">{{ $broker->name }}</span>
                                                  </div>
                                                  <div class="text-right">
                                                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Commission Structure</span>
                                                      <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-0.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20">{{ number_format($broker->default_commission_pct, 2) }}% Default</span>
                                                  </div>
                                              </div>

                                              <div class="grid grid-cols-2 gap-3">
                                                  <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Linked Ledger Account</span>
                                                      <span class="text-xs font-bold text-slate-800 mt-0.5 block truncate">{{ $broker->linkedAccount->name ?? 'Unlinked' }}</span>
                                                      <span class="text-[9px] font-mono text-slate-500 block">Code: {{ $broker->linkedAccount->code ?? 'N/A' }}</span>
                                                  </div>
                                                  <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white shadow-sm">
                                                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Deals & Sales</span>
                                                      <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $broker->total_deals }} Closed Deal(s)</span>
                                                      <span class="text-[9px] font-mono text-slate-500 block">Value: ₹{{ number_format($broker->total_sale_value, 2) }}</span>
                                                  </div>
                                              </div>

                                              <div class="grid grid-cols-3 gap-3">
                                                  <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-center shadow-2xs">
                                                      <span class="text-[9px] font-bold text-amber-800 uppercase block">Accrued (Locked)</span>
                                                      <span class="text-xs font-bold font-mono text-amber-900 mt-1 block">₹{{ number_format($broker->accrued_commission, 2) }}</span>
                                                  </div>
                                                  <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-center shadow-2xs">
                                                      <span class="text-[9px] font-bold text-emerald-800 uppercase block">Payable (Ready)</span>
                                                      <span class="text-xs font-bold font-mono text-emerald-900 mt-1 block">₹{{ number_format($broker->payable_commission, 2) }}</span>
                                                  </div>
                                                  <div class="p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-center shadow-2xs">
                                                      <span class="text-[9px] font-bold text-indigo-800 uppercase block">Total Paid Out</span>
                                                      <span class="text-xs font-bold font-mono text-indigo-900 mt-1 block">₹{{ number_format($broker->paid_commission, 2) }}</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                                              <button type="button" @click="openView = false" 
                                                      class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                                  Close
                                              </button>
                                              <a href="{{ route('brokers.payable-report', ['broker_id' => $broker->id]) }}" 
                                                 class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5">
                                                  <span>Full Ledger Statement</span>
                                                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                              </a>
                                          </div>
                                      </div>
                                 </div>
                                        {{-- Edit Modal --}}
                                 <div x-show="openEdit" 
                                      class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
                                      style="display: none;" x-transition.opacity>
                                      <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openEdit = false">
                                          {{-- Header --}}
                                          <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                                              <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                                              <div class="relative z-10 flex items-center justify-between gap-4">
                                                  <div>
                                                      <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Profile</span>
                                                      <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1" x-text="'Rate: ' + '{{ $broker->name }}'"></h2>
                                                  </div>
                                                  <button type="button" @click="openEdit = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                                              </div>
                                          </div>

                                          <form action="{{ route('brokers.update', $broker->id) }}" method="POST" x-data="{ errors: {}, edit_name: '{{ addslashes($broker->name) }}', edit_commission_pct: '{{ $broker->default_commission_pct }}', submitEdit(e) { let errs = {}; if(!this.edit_name || !String(this.edit_name).trim()) errs.edit_name = ['The broker name field is required.']; if(!this.edit_commission_pct) errs.edit_commission_pct = ['The commission % field is required.']; if(Object.keys(errs).length > 0) { e.preventDefault(); this.errors = errs; return false; } } }" @submit="submitEdit($event)" novalidate>
                                              @csrf
                                              @method('PUT')
                                              <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                                                  <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                                                      <div class="space-y-1.5">
                                                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Broker / Agency Name <span class="text-rose-500">*</span></label>
                                                          <input type="text" name="name" x-model="edit_name" required
                                                                 :class="errors.edit_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                                                 class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all shadow-sm font-semibold">
                                                          <template x-if="errors.edit_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_name) ? errors.edit_name[0] : errors.edit_name"></p></template>
                                                      </div>

                                                      <div class="space-y-1.5">
                                                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Default Commission % <span class="text-rose-500">*</span></label>
                                                          <div class="relative">
                                                              <input type="number" step="0.01" min="0.01" max="100.00" name="default_commission_pct" x-model="edit_commission_pct" required
                                                                     :class="errors.edit_commission_pct ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-250 bg-slate-50'"
                                                                     class="w-full px-3.5 py-2.5 border focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 focus:outline-none transition-all pr-8 font-mono font-bold shadow-sm">
                                                              <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">%</span>
                                                          </div>
                                                          <template x-if="errors.edit_commission_pct"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.edit_commission_pct) ? errors.edit_commission_pct[0] : errors.edit_commission_pct"></p></template>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                                                  <button type="button" @click="openEdit = false" 
                                                          class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                                      Cancel
                                                  </button>
                                                  <button type="submit" 
                                                          class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                                                      Update Changes
                                                  </button>
                                              </div>
                                          </form>
                                      </div>
                                 </div>

                                 {{-- Delete Modal --}}
                                 <div x-show="openDelete" 
                                      class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
                                      style="display: none;" x-transition.opacity>
                                      <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="openDelete = false">
                                          {{-- Header --}}
                                          <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-500/10">
                                              <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                                              <div class="relative z-10 flex items-center justify-between gap-4">
                                                  <div>
                                                      <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Safety Check</span>
                                                      <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1" x-text="'Delete Broker: ' + '{{ $broker->name }}'"></h2>
                                                  </div>
                                                  <button type="button" @click="openDelete = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                                              </div>
                                          </div>
                                          
                                          <form method="POST" action="{{ route('brokers.destroy', $broker->id) }}">
                                              @csrf
                                              @method('DELETE')
                                              <div class="p-6 bg-slate-50/50 text-xs font-sans space-y-4">
                                                  <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-2">
                                                      <p class="text-sm text-slate-700">Are you sure you want to delete this broker? This action cannot be undone.</p>
                                                      <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">This action will remove the broker profile record.</p>
                                                  </div>
                                              </div>

                                              <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                                                  <button type="button" @click="openDelete = false" 
                                                          class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                                      Cancel
                                                  </button>
                                                  <button type="submit" 
                                                          class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">
                                                      Delete
                                                  </button>
                                              </div>
                                          </form>
                                      </div>
                                 </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 border text-center text-slate-500 italic">No brokers registered yet. Click "Add Broker" above to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-erp-layout>
