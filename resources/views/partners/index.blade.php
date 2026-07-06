<x-erp-layout title="Partner Management" headerTitle="Partner Management & Accounts">

<div class="max-w-[1400px] mx-auto space-y-6">

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
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Partner / Firm Name</label>
                              <input type="text" name="name" required placeholder="e.g. John Doe & Sons"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
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
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Registered Partners & Current Balances</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Partner Info</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Collections Share</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Payouts</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Current Balance</th>
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
                                    ₹{{ number_format($partner->total_allocated, 2) }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-rose-700">
                                    ₹{{ number_format($partner->total_paid, 2) }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold {{ $partner->balance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        ₹{{ number_format($partner->balance, 2) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('partners.statement', $partner->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-[10px] text-slate-650 font-bold rounded-lg transition uppercase tracking-wide">
                                        View Statement
                                    </a>
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
                        
                        <a href="{{ route('partners.shares', $project->id) }}" 
                           class="text-[10px] font-bold text-[#a38c29] hover:text-[#8d7923] uppercase tracking-wider">
                            Configure
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
