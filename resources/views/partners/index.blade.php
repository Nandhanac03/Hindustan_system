<x-erp-layout title="Partner Management" headerTitle="Partner Management & Accounts">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Partner Management</h1>
            <p class="text-xs text-slate-500 mt-1">Manage project partners, configure capital/collections share percentages, and track capital accounts & statements.</p>
        </div>

        <div x-data="{ open: false }">
            <button @click="open = true" 
                    class="inline-flex items-center gap-2 rounded-xl bg-[#a38c29] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all duration-200 hover:bg-[#8d7923]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Register Partner
            </button>

            {{-- Register Modal --}}
            <div x-show="open" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
                 style="display: none;">
                 <div @click.away="open = false" 
                      class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-4">
                      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                          <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Register New Partner</h3>
                          <button @click="open = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                      </div>

                      <form action="{{ route('partners.store') }}" method="POST" class="space-y-4">
                          @csrf
                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Partner / Firm Name *</label>
                              <input type="text" name="name" required placeholder="e.g. John Doe & Sons"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                          </div>

                          <div class="grid grid-cols-2 gap-3">
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Phone</label>
                                  <input type="text" name="phone" placeholder="+91 9876543210"
                                         class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                              </div>
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Email</label>
                                  <input type="email" name="email" placeholder="partner@email.com"
                                         class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                              </div>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Assign to Project (optional)</label>
                              <select name="project_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none transition-all">
                                  <option value="">— No project assignment —</option>
                                  @foreach($projects as $proj)
                                      <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Initial Share % (0–100)</label>
                              <input type="number" name="share_pct" min="0" max="100" step="0.01" placeholder="e.g. 50"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs focus:outline-none transition-all">
                          </div>

                          <div class="pt-4 flex justify-end gap-2">
                              <button type="button" @click="open = false" 
                                      class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                                  Cancel
                              </button>
                              <button type="submit" 
                                      class="px-4 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                                  Save Partner
                              </button>
                          </div>
                      </form>
                 </div>
            </div>
        </div>
    </div>

    {{-- Feedback messages --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-150 rounded-xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between">
            <span>{{ session('status') }}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Partners List --}}
        <div x-data="{ selectedPartner: null }" class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Registered Partners & Current Balances</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Partner Info</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Collected</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Payouts</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Net Balance</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">{{ $partner->name }}</div>
                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5">A/C: {{ $partner->linkedAccount->code ?? 'N/A' }}</div>
                                </td>
                                <td class="px-5 py-4 font-semibold text-emerald-700">
                                    ₹{{ number_format($partner->total_collected, 2) }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-rose-700">
                                    ₹{{ number_format($partner->total_allocated, 2) }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold {{ $partner->balance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        ₹{{ number_format($partner->balance, 2) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click.prevent="selectedPartner = {{ $partner->id }}" title="View Partner Profile" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        
                                        <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner and their linked account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Partner" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 hover:text-rose-700 transition inline-flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-slate-450 italic">No partners registered yet. Use the action button above to register.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Partner Modals (placed outside overflow-x-auto to prevent clipping) --}}
            @foreach($partners as $partner)
                <div x-show="selectedPartner === {{ $partner->id }}" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity text-left" style="display: none;">
                    <div @click.away="selectedPartner = null" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-5 whitespace-normal">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Partner Ledger Profile</h3>
                            </div>
                            <button @click="selectedPartner = null" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Partner / Firm Name</span>
                                    <span class="text-base font-extrabold text-slate-900">{{ $partner->name }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Linked Account</span>
                                    <span class="text-xs font-mono font-bold text-slate-600 bg-white border border-slate-200 px-2 py-0.5 rounded">{{ $partner->linkedAccount->code ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/60 text-center">
                                    <span class="text-[9px] font-bold text-emerald-800 uppercase block">Collections</span>
                                    <span class="text-xs font-bold font-mono text-emerald-900 mt-1 block">₹{{ number_format($partner->total_collected, 2) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200/60 text-center">
                                    <span class="text-[9px] font-bold text-rose-800 uppercase block">Total Payouts</span>
                                    <span class="text-xs font-bold font-mono text-rose-900 mt-1 block">₹{{ number_format($partner->total_allocated, 2) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-center">
                                    <span class="text-[9px] font-bold text-slate-700 uppercase block">Current Balance</span>
                                    <span class="text-xs font-bold font-mono mt-1 block {{ $partner->balance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">₹{{ number_format($partner->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 flex justify-between items-center border-t border-slate-100">
                            <button type="button" @click="selectedPartner = null" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Close</button>
                            <a href="{{ route('partners.statement', $partner->id) }}" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md inline-flex items-center gap-1.5">
                                <span>View Statement Ledger</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Projects Share Settings Side Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 space-y-4">
            <div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Project Share Percentages</h3>
                <p class="text-[10px] text-slate-450 mt-1">Configure how booking payment collections are divided among project partners.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($projects as $project)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-xs">{{ $project->name }}</div>
                            <div class="text-[9px] text-slate-450 mt-0.5">
                                @php
                                    $sharesSummary = $project->partnerShares->map(function ($s) {
                                        return $s->partner->name . ' (' . $s->share_pct . '%)';
                                    })->implode(', ');
                                @endphp
                                {{ $sharesSummary ?: 'No shares defined yet' }}
                            </div>
                        </div>
                        
                        <a href="{{ route('partners.shares', $project->id) }}" title="Configure Project Shares" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-450 italic">No active projects found.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

</x-erp-layout>
