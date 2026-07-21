<x-erp-layout title="Partner Management" headerTitle="Partner Management & Accounts">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ editProjectModal: false, imagePreview: null }">

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        {{-- Partners List --}}
        <div x-data="{ selectedPartner: null }" class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Registered Partners & Current Balances</h2>
            </div>
            
            <div class="overflow-x-auto flex-1">
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 space-y-4 flex flex-col h-full">
            <div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Project Share Percentages</h3>
                <p class="text-[10px] text-slate-450 mt-1">Configure how booking payment collections are divided among project partners.</p>
            </div>

            <div class="divide-y divide-slate-100 flex-1">
                @forelse($projects as $project)
                    <div class="py-3 flex items-center justify-between" x-data="{ shareModal: false }">
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
                        
                        <button @click.prevent="shareModal = true" title="Configure Project Shares" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>
                        
                        {{-- Settings Modal --}}
                        <div x-show="shareModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;">
                            <div @click.away="shareModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-2xl space-y-5 relative" x-data="{
                                total: 0,
                                init() { this.calculateTotal(); },
                                calculateTotal() {
                                    let sum = 0;
                                    this.$el.querySelectorAll('.share-input-{{ $project->id }}').forEach(input => {
                                        sum += parseFloat(input.value) || 0;
                                    });
                                    this.total = sum;
                                }
                            }">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Configure Shares - {{ $project->name }}</h3>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Assign collection share percentages to partners for this project.</p>
                                    </div>
                                    <button @click="shareModal = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
                                </div>
                                
                                <form action="{{ route('partners.shares.update', $project->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    
                                    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
                                        @forelse($partners as $partner)
                                            @php
                                                $existingShare = $project->partnerShares->firstWhere('partner_id', $partner->id);
                                            @endphp
                                            <div class="flex items-center justify-between p-3 bg-slate-50/50 border border-slate-150 rounded-xl">
                                                <div>
                                                    <div class="font-bold text-slate-900 text-xs">{{ $partner->name }}</div>
                                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5">A/C: {{ $partner->linkedAccount->code ?? 'N/A' }}</div>
                                                </div>

                                                <div class="flex items-center gap-2 w-32">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0" 
                                                           max="100" 
                                                           name="shares[{{ $partner->id }}]" 
                                                           value="{{ old('shares.' . $partner->id, $existingShare ? $existingShare->share_pct : '0') }}"
                                                           x-on:input="calculateTotal()"
                                                           class="share-input-{{ $project->id }} w-full px-3 py-2 bg-white border border-slate-250 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 text-right focus:outline-none transition-all">
                                                    <span class="text-xs font-bold text-slate-500">%</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8 text-slate-450 italic">No partners registered yet.</div>
                                        @endforelse
                                    </div>
                                    
                                    <div class="p-4 bg-slate-50 rounded-xl flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-600 uppercase">Total Allocated Shares</span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm font-bold font-mono" 
                                                  x-text="total.toFixed(2)"
                                                  x-bind:class="total > 100 ? 'text-rose-600' : (total === 100 ? 'text-emerald-700' : 'text-[#a38c29]')">0.00</span>
                                            <span class="text-xs font-bold text-slate-500">% / 100.00%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 flex justify-end items-center gap-2 border-t border-slate-100">
                                        <button type="button" @click="shareModal = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                                        <button type="submit" 
                                                x-bind:disabled="total > 100"
                                                x-bind:class="total > 100 ? 'opacity-50 cursor-not-allowed' : ''"
                                                class="px-4 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                                            Save Shares
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-450 italic">No active projects found.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TABASCO HINDUSTAN INFRA - EXECUTIVE OVERVIEW DASHBOARD -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="mt-12 bg-[#FCFAF6] border border-[#EAE3CD] rounded-3xl p-6 lg:p-8 space-y-8 shadow-sm text-slate-800" style="font-family: 'Outfit', sans-serif;" x-data="{ hoveredUnit: null, hoveredEl: null }">
        
        <!-- Header Banner -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-[#EAE3CD] pb-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 border border-[#a38c29]/30 flex items-center justify-center text-[#a38c29] shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-[#7E6A1B] uppercase tracking-wider">Tabasco Hindustan Infra - Executive Overview</h2>
                    <p class="text-[10px] text-slate-500 font-medium">Real-time Project Performance & Property Allocation Matrix</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-2 rounded-xl bg-white border border-[#EAE3CD] px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        <span>{{ $dashboardProject ? $dashboardProject->name : 'All Projects...' }}</span>
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-xl border border-slate-200 shadow-lg py-1 z-30">
                        @foreach($projects as $proj)
                            <a href="{{ route('partners.index', ['project_id' => $proj->id]) }}" 
                               class="block px-4 py-2 text-xs hover:bg-[#a38c29]/5 text-slate-750 hover:text-[#7E6A1B] @if($dashboardProject && $dashboardProject->id == $proj->id) font-extrabold bg-[#a38c29]/10 text-[#7E6A1B] @endif">
                                {{ $proj->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Profile Card -->
        @if($dashboardProject)
        <div class="bg-white border border-[#EFECE1] rounded-2xl p-5 shadow-sm flex flex-col lg:flex-row gap-6 items-center" x-data="{ editProjectModal: false, imagePreview: null }">
            <div class="w-full lg:w-48 h-32 rounded-xl overflow-hidden shadow-md flex-shrink-0 bg-slate-100 relative">
                @if($dashboardProject->image_url)
                    <img src="{{ asset('storage/' . $dashboardProject->image_url) }}" alt="Project Image" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29]">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                @endif
                <div class="absolute top-2 right-2 bg-emerald-600 text-white text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                    {{ $dashboardProject->status }}
                </div>
            </div>
            
            <div class="flex-1 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight uppercase">{{ $dashboardProject->name }}</h3>
                        <div class="flex items-center gap-1 text-slate-450 mt-1">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-xs font-semibold text-slate-500">{{ $dashboardProject->location }}, {{ $dashboardProject->city }}, {{ $dashboardProject->state_or_emirate }}, India</span>
                        </div>
                    </div>
                    <button @click="editProjectModal = true" class="inline-flex items-center gap-1.5 rounded-xl border border-[#a38c29]/50 hover:bg-[#a38c29]/5 px-3 py-1.5 text-[10px] font-extrabold text-[#7E6A1B] uppercase tracking-wider transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Project
                    </button>
                </div>
                
                <p class="text-xs text-slate-500 font-medium leading-relaxed">{!! $dashboardProject->description !!}</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-xl p-3 flex flex-col justify-center">
                        <span class="text-[9px] font-extrabold text-slate-455 uppercase tracking-wider">Total Floors/Units</span>
                        <span class="text-xs font-bold text-slate-800 mt-1 uppercase">{{ $dashboardProject->total_floors }} Floors / {{ $floors->flatMap->units->count() }} Units</span>
                    </div>
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-xl p-3 flex flex-col justify-center">
                        <span class="text-[9px] font-extrabold text-slate-455 uppercase tracking-wider">Start Date</span>
                        <span class="text-xs font-bold text-slate-800 mt-1 uppercase">{{ $dashboardProject->start_date ? \Carbon\Carbon::parse($dashboardProject->start_date)->format('d-M-Y') : 'N/A' }}</span>
                    </div>
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-xl p-3 flex flex-col justify-center">
                        <span class="text-[9px] font-extrabold text-slate-455 uppercase tracking-wider">Target Completion</span>
                        <span class="text-xs font-bold text-slate-800 mt-1 uppercase">{{ $dashboardProject->expected_completion_date ? \Carbon\Carbon::parse($dashboardProject->expected_completion_date)->format('d-M-Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════ EDIT PROJECT MODAL ═══════════════════════ --}}
            <div x-show="editProjectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
                {{-- Backdrop --}}
                <div x-show="editProjectModal"
                     x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="editProjectModal = false"
                     class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                {{-- Modal panel --}}
                <div x-show="editProjectModal"
                     x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col"
                     @click.stop>
                    
                    {{-- Header --}}
                    <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 flex-shrink-0">
                        <div class="absolute -top-10 -right-10 w-36 h-36 bg-[#a38c29]/20 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-[#a38c29] text-[9px] font-bold uppercase tracking-widest mb-0.5">Edit Project</p>
                                <h2 class="text-xs font-extrabold text-white">{{ $dashboardProject->name }}</h2>
                            </div>
                            <button @click="editProjectModal = false" class="text-slate-400 hover:text-white transition-colors duration-150 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('projects.update', $dashboardProject->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
                        @csrf
                        @method('PUT')
            @php
                $projectImage = $dashboardProject->image_url
                    ? asset('storage/' . $dashboardProject->image_url)
                    : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80';
            @endphp
                        {{-- Single-pane body --}}
                        <div class="p-5 space-y-4 overflow-y-auto flex-1 min-h-0">
                            {{-- Media & Image --}}
                            <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 space-y-3">
                                <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Media & Image</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0 relative">
                                        <img x-show="!imagePreview" src="{{ $projectImage }}" class="w-full h-full object-cover" alt="Project image">
                                        <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover" x-cloak>
                                    </div>
                                    <div class="flex-1">
                                        <label class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-lg transition shadow-sm uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Upload
                                            <input type="file" name="image" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file);">
                                        </label>
                                        <p class="text-[9px] text-slate-400 mt-1">JPG, PNG up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Project Details Section --}}
                            <div class="space-y-3">
                                <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Project Details</p>
                                
                                <div class="space-y-1">
                                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Name</label>
                                    <input type="text" name="name" value="{{ old('name', $dashboardProject->name) }}"
                                        class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Location / Address</label>
                                    <input type="text" name="location" value="{{ old('location', $dashboardProject->location) }}"
                                        class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">City</label>
                                        <input type="text" name="city" value="{{ old('city', $dashboardProject->city) }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">State</label>
                                        <input type="text" name="state_or_emirate" value="{{ old('state_or_emirate', $dashboardProject->state_or_emirate) }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Country</label>
                                        <input type="text" name="country" value="{{ old('country', $dashboardProject->country) }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                </div>
                            </div>

                            {{-- Status & Scope Section --}}
                            <div class="space-y-3">
                                <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Status & Scope</p>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Status</label>
                                        <select name="status" class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                            @foreach(['planning' => 'Planning', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'on_hold' => 'On Hold'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('status', $dashboardProject->status) == $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total Floors</label>
                                        <input type="number" name="total_floors" value="{{ old('total_floors', $dashboardProject->total_floors) }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Start Date</label>
                                        <input type="date" name="start_date" value="{{ old('start_date', $dashboardProject->start_date ? \Carbon\Carbon::parse($dashboardProject->start_date)->format('Y-m-d') : '') }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Target Completion</label>
                                        <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', $dashboardProject->expected_completion_date ? \Carbon\Carbon::parse($dashboardProject->expected_completion_date)->format('Y-m-d') : '') }}"
                                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    </div>
                                </div>

                                <div class="space-y-1 mt-3">
                                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Description</label>
                                    <textarea name="description" id="ck_units_project_description" rows="4"
                                        placeholder="Write a detailed project description..."
                                        class="ck-editor-field w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none">{{ old('description', $dashboardProject->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-5 py-3.5 border-t border-slate-150 flex items-center justify-end gap-2.5 bg-slate-50 flex-shrink-0">
                            <button type="button" @click="editProjectModal = false"
                                class="px-3.5 py-1.5 border border-slate-250 hover:bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg transition uppercase tracking-wide">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-lg transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- COLLECTIONS & COMMISSION OVERVIEW ROW -->
        <div class="flex flex-col lg:flex-row gap-6 items-stretch w-full">
            
            <!-- Left Side: Collections Bar Card -->
            <div class="w-full lg:w-[65%] bg-white border border-[#EFECE1] rounded-3xl p-6 shadow-lg space-y-6 text-slate-800">
                <!-- Header -->
                <div class="flex justify-between items-center pb-2">
                    <h3 class="text-xs font-extrabold text-[#0B1E36] uppercase tracking-wider">Collection Overview</h3>
                    <div class="text-xs font-bold text-slate-650 flex items-center gap-1.5">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Total Target</span>
                        <span class="text-slate-900 font-mono">₹ {{ $collectionStats['target_formatted'] }}</span>
                    </div>
                </div>

                <!-- Progress Bar Section -->
                <div class="space-y-1 pb-2">
                    <div class="w-full h-6 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                        <div class="h-full bg-[#a38c29] rounded-full transition-all duration-500 flex items-center justify-end pr-3" style="width: {{ min(100, $collectionStats['efficiency']) }}%;">
                            <span class="text-[9px] font-extrabold text-white">{{ $collectionStats['efficiency'] }}%</span>
                        </div>
                    </div>
                    <div class="text-[9px] font-bold text-[#a38c29] text-right">
                        (Target Achieved)
                    </div>
                </div>
                
                <!-- Top Metric Row (Three equal cards) -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6m-6 4h6m-6-8h6a4 4 0 010 8H9v8m3-8L9 21"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 font-mono block leading-none">₹{{ $collectionStats['collected_formatted'] }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1">Total Collected</span>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 font-mono block leading-none">{{ $outstandingFormatted }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1">Outstanding</span>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 font-mono block leading-none">{{ $collectionStats['efficiency'] }}%</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1">Collection Efficiency</span>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Area -->
                <div id="collectionsChart" class="w-full h-[220px] bg-slate-50 rounded-2xl border border-slate-100"></div>
                
                <!-- Bottom Metric Row (Three equal cards) -->
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <!-- Card 1 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-950 font-mono block leading-none">{{ $receiptsCount }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1.5">Total Receipts Count</span>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-955 font-mono block leading-none">{{ $pendingEmisCount }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1.5">Pending EMIs Count</span>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-[#FAF8F2]/60 border border-[#EFECE1] rounded-2xl p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#FAF0D7] flex items-center justify-center text-[#9C6D3B] shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-955 font-mono block leading-none">{{ $thisMonthPercent }}%</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block mt-1.5">This Month %</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Commission Summary & Repayments Alerts -->
            <div class="w-full lg:w-[35%] flex flex-col gap-6">
                <!-- Commission Summary Card -->
                <div class="bg-white border border-[#EFECE1] rounded-3xl p-6 shadow-lg text-slate-800 flex flex-col justify-between">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-xs font-extrabold text-[#0B1E36] uppercase tracking-wider">Commission Summary</h3>
                    </div>
                    
                    <div class="py-4 space-y-4">
                        <!-- Row 1 -->
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Total Earned</span>
                                    <span class="text-[8px] text-slate-400 font-medium uppercase block -mt-0.5">(All Time)</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['total_earned']) }}</span>
                        </div>
                        <!-- Row 2 -->
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">This Month Earned</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['this_month_earned']) }}</span>
                        </div>
                        <!-- Row 3 -->
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Paid Out</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['paid_out']) }}</span>
                        </div>
                        <!-- Row 4 -->
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-emerald-750 uppercase tracking-wide block font-extrabold">Available Payout</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 font-mono">₹ {{ number_format($commissionSummary['available_payout']) }}</span>
                        </div>
                    </div>
                    
                    <!-- Request Payout Button -->
                    <div class="pt-2">
                        <button class="w-full bg-primary hover:bg-primary-700 text-white text-[10px] font-extrabold uppercase py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 tracking-wider transition shadow-sm">
                            Request Payout
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Bank Loan EMI Repayment Notifications Card -->
                <div class="bg-white border border-[#EFECE1] rounded-3xl p-6 shadow-lg text-slate-800 space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-extrabold text-[#0B1E36] uppercase tracking-wider">Bank Loan EMI Repayment Notifications</h3>
                    </div>
                    
                    <div class="space-y-3">
                        @if(isset($bankEmiAlerts) && $bankEmiAlerts->isNotEmpty())
                            @php
                                $bankColors = ['bg-rose-700', 'bg-indigo-700', 'bg-emerald-700', 'bg-amber-600', 'bg-purple-700', 'bg-teal-700', 'bg-blue-800'];
                            @endphp
                            @foreach($bankEmiAlerts as $alert)
                                <div class="p-3 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <!-- Bank Mini Logo/Placeholder Box -->
                                        <div class="w-9 h-9 rounded-xl {{ $bankColors[$loop->index % count($bankColors)] }} text-white flex items-center justify-center font-bold text-[10px] shrink-0 uppercase">
                                            {{ substr($alert->provider, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">{{ $alert->provider }}</div>
                                            <div class="text-[9px] mt-0.5 font-bold uppercase text-rose-600">
                                                Due Today
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end gap-1">
                                        <span class="font-mono font-bold text-slate-900 text-xs">₹{{ number_format($alert->amount, 2) }}</span>
                                        <a href="{{ route('loans.index') }}" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-[9px] font-bold uppercase rounded border border-amber-250 transition tracking-wider">
                                            Pay Now
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 bg-emerald-50/20 border border-emerald-100 rounded-2xl flex items-center gap-2 text-emerald-800">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-wider">All EMI payments up-to-date for {{ Carbon\Carbon::now()->format('F Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- FLOOR MATRIX GRID -->
        <div class="bg-gradient-to-br from-white to-slate-50/80 border border-slate-200/80 rounded-3xl p-6 shadow-md shadow-slate-200/30 space-y-6 relative">
            
            <!-- Header with Title and Legend -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#a38c29]/15 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Floor Matrix – Unit Availability</h3>
                </div>
                
                <!-- Status Legends -->
                <div class="flex flex-wrap items-center gap-3 sm:gap-5 text-[9px] font-black uppercase tracking-wider text-slate-600">
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-emerald-500 shadow-sm border border-emerald-600"></span> Available</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-blue-500 shadow-sm border border-blue-600"></span> Booked</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-amber-500 shadow-sm border border-amber-600"></span> Pending</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-rose-600 shadow-sm border border-rose-700"></span> Sold</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-slate-700 shadow-sm border border-slate-800"></span> Parking</span>
                </div>
            </div>

            @php
                // Count units per column (door_no) across all floor rows
                $colCounts = [];
                foreach ($matrixColumns as $doorNo) {
                    $colCounts[$doorNo] = 0;
                }
                foreach ($floorMatrix as $row) {
                    foreach ($row['columns'] as $doorNo => $unit) {
                        if ($unit !== null) {
                            $colCounts[$doorNo] = ($colCounts[$doorNo] ?? 0) + 1;
                        }
                    }
                }

                // Summary aggregates
                $totalUnitsCount = 0;
                $availableCount = 0;
                $bookedCount = 0;
                $pendingCount = 0;
                $soldCount = 0;
                foreach ($floorMatrix as $row) {
                    foreach ($row['columns'] as $u) {
                        if ($u) {
                            $totalUnitsCount++;
                            $st = strtolower($u->status);
                            if ($st === 'sold') $soldCount++;
                            elseif ($st === 'booked') $bookedCount++;
                            elseif ($st === 'blocked') $pendingCount++;
                            elseif ($st === 'available') $availableCount++;
                        }
                    }
                }
            @endphp

            @if(empty($matrixColumns) && empty($parkingRows))
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">No unit data available</p>
                    <p class="text-[10px] text-slate-400 mt-1">Add floors and units to see the property matrix.</p>
                </div>
            @else
                <!-- Calculate Max Columns needed -->
                @php
                    $maxFloorUnits = 0;
                    foreach ($floorMatrix as $row) {
                        $count = collect($row['columns'])->filter()->count();
                        if ($count > $maxFloorUnits) $maxFloorUnits = $count;
                    }
                    $maxParkingUnits = empty($parkingRows) ? 0 : collect($parkingRows)->max(fn($p) => $p['units']->count());
                    $totalGridCols = max($maxFloorUnits, $maxParkingUnits);
                @endphp

                <!-- Table Matrix Container -->
                <div class="overflow-x-auto relative rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] bg-white">
                    <table class="border-collapse w-full" style="min-width: max-content;">
                        <thead>
                            <tr class="border-b border-[#a38c29]/30 bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-widest shadow-[0_1px_2px_0_rgba(0,0,0,0.02)]">
                                <!-- Floor label column -->
                                <th class="p-3.5 text-left sticky left-0 bg-[#a38c29] backdrop-blur-md z-10 min-w-[130px] border-r border-[#a38c29]/30 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                    Floor / Unit
                                </th>
                                <!-- Dynamic Unit Column Headers -->
                                @for($i = 1; $i <= $totalGridCols; $i++)
                                    <th class="p-3.5 text-center min-w-[90px]">
                                        <span class="block text-white/90">{{ $i }}</span>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/40">
                            @php
                                $reversedFloors = array_reverse($floorMatrix);
                                $combinedFloors = [];
                                
                                $validParking = [];
                                foreach($parkingRows as $pRow) {
                                    if ($pRow['display_name'] === 'P3' || $pRow['units']->count() == 0) continue;
                                    
                                    $dName = $pRow['display_name'];
                                    if ($dName === 'P1') $dName = 'Floor 4';
                                    if ($dName === 'P2') $dName = 'Floor 5';
                                    
                                    $validParking[] = [
                                        'display_name' => $dName,
                                        'is_parking_row' => true,
                                        'columns' => collect($pRow['units'])->sortBy('door_no', SORT_NATURAL | SORT_FLAG_CASE)->values()->all()
                                    ];
                                }
                                
                                foreach($reversedFloors as $row) {
                                    $row['is_parking_row'] = false;
                                    $combinedFloors[] = $row;
                                    if (strtolower(trim($row['display_name'])) === 'floor 3') {
                                        foreach($validParking as $vp) {
                                            $combinedFloors[] = $vp;
                                        }
                                    }
                                }
                                
                                $hasAddedParking = false;
                                foreach($combinedFloors as $cf) {
                                    if(isset($cf['is_parking_row']) && $cf['is_parking_row']) {
                                        $hasAddedParking = true;
                                    }
                                }
                                if (!$hasAddedParking) {
                                    $combinedFloors = array_merge($combinedFloors, $validParking);
                                }
                            @endphp

                            <!-- Combined Floor Rows -->
                            @foreach ($combinedFloors as $row)
                                @php
                                    $isParkingRow = $row['is_parking_row'] ?? false;
                                    $validUnits = collect($row['columns'])->filter()->values();
                                    
                                    $isParkingFloor = $isParkingRow;
                                    if (!$isParkingFloor) {
                                        $firstUnit = $validUnits->first();
                                        if ($firstUnit) {
                                            $isParkingFloor = ($firstUnit->unitType && stripos($firstUnit->unitType->name, 'park') !== false) 
                                                              || stripos($firstUnit->door_no, 'P') === 0;
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-[#a38c29]/5 transition-colors duration-200 group">
                                    <!-- Floor Label -->
                                    <td class="p-3.5 sticky left-0 bg-white/95 group-hover:bg-[#FAF8F2] backdrop-blur-md z-10 border-l-2 border-l-[#a38c29] border-r-2 border-r-[#a38c29] border-b-2 border-b-[#a38c29]/20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center shrink-0 border border-[#a38c29]/20">
                                                @if($isParkingFloor)
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM4 16h-.5A1.5 1.5 0 012 14.5v-2c0-.5.2-1 .5-1.3l2-3.4A3 3 0 017 6.5h10a3 3 0 012.5 1.3l2 3.4c.3.3.5.8.5 1.3v2a1.5 1.5 0 01-1.5 1.5H20m-4-10v3m-8-3v3m12 1.5H4" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-black text-slate-800 uppercase tracking-wide">{{ $row['display_name'] }}</span>
                                                <span class="block text-[9px] text-slate-400 font-bold mt-0.5">{{ collect($row['columns'])->filter()->count() }} {{ $isParkingRow ? 'Slot(s)' : 'Unit(s)' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Unit Cells -->
                                    @for ($i = 0; $i < $totalGridCols; $i++)
                                        <td class="p-2.5">
                                            @if ($i < $validUnits->count())
                                                @php $unit = $validUnits[$i]; @endphp
                                                
                                                @if ($isParkingRow)
                                                    @php 
                                                        $isOccupied = in_array(strtolower($unit->status), ['sold', 'booked']); 
                                                    @endphp
                                                    <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: 'Car Parking Space', status: '{{ $isOccupied ? 'Reserved' : 'Available' }}', price: '₹{{ number_format($unit->expected_sale_amount ?? 300000) }}' }; hoveredEl = $el"
                                                         @mouseleave="hoveredUnit = null"
                                                         class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl shadow-[0_2px_4px_-1px_rgba(0,0,0,0.05)] border border-transparent transition-all hover:-translate-y-1 hover:shadow-md cursor-pointer duration-200
                                                         @if($isOccupied) bg-[#0B1E36] text-white shadow-slate-300/50 hover:shadow-slate-400/50 hover:border-slate-500 @else bg-emerald-500 text-white shadow-emerald-200/50 hover:shadow-emerald-300/50 hover:border-emerald-400 @endif">
                                                        <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-sm">{{ $unit->door_no }}</span>
                                                        <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-sm">Parking</span>
                                                    </div>
                                                @else
                                                    @php
                                                        $status = strtolower($unit->status);
                                                        $isSold     = in_array($status, ['sold']);
                                                        $isBooked   = ($status === 'booked');
                                                        $isBlocked  = ($status === 'blocked');
                                                    @endphp
                                                    <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: '{{ $unit->built_up_area ? $unit->built_up_area.' sq.ft' : 'N/A' }}', status: '{{ ucfirst($unit->status) }}', price: '₹{{ number_format($unit->expected_sale_amount ?? 0) }}' }; hoveredEl = $el"
                                                         @mouseleave="hoveredUnit = null"
                                                         class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl shadow-[0_2px_6px_-2px_rgba(0,0,0,0.1)] border border-transparent transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-200/40 cursor-pointer duration-200
                                                         @if ($isSold) bg-rose-600 text-white shadow-rose-200/50 hover:shadow-rose-300/50 hover:border-rose-400
                                                         @elseif ($isBooked) bg-blue-500 text-white shadow-blue-200/50 hover:shadow-blue-300/50 hover:border-blue-400
                                                         @elseif ($isBlocked) bg-amber-500 text-white shadow-amber-200/50 hover:shadow-amber-300/50 hover:border-amber-400
                                                         @else bg-emerald-500 text-white shadow-emerald-200/50 hover:shadow-emerald-300/50 hover:border-emerald-400 @endif">

                                                        <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-sm">
                                                            {{ $unit->door_no }}
                                                        </span>
                                                        <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-sm">
                                                            {{ $unit->built_up_area ? number_format((float)$unit->built_up_area, 2) . ' Sq.ft' : 'N/A' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100/80 rounded-xl bg-slate-50/30"></div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Hover Tooltip -->
                <div x-show="hoveredUnit" 
                     class="absolute z-50 bg-white border border-[#EAE3CD] rounded-2xl shadow-2xl p-4 w-[260px] pointer-events-none space-y-2 transition-all duration-150"
                     :style="`left: ${hoveredEl ? Math.min(hoveredEl.getBoundingClientRect().left - $el.parentElement.getBoundingClientRect().left + 10, $el.parentElement.clientWidth - 270) : 0}px; top: ${hoveredEl ? hoveredEl.getBoundingClientRect().top - $el.parentElement.getBoundingClientRect().top - 130 : 0}px;`"
                     x-transition>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <div>
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block" x-text="hoveredUnit?.floor"></span>
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider" x-text="hoveredUnit?.door_no"></span>
                        </div>
                        <span class="text-[8px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm"
                              :class="{'bg-rose-600': hoveredUnit?.status === 'Sold', 'bg-blue-500': hoveredUnit?.status === 'Booked', 'bg-amber-500': hoveredUnit?.status === 'Blocked', 'bg-emerald-500': hoveredUnit?.status === 'Available', 'bg-slate-700': hoveredUnit?.status === 'Reserved'}"
                              x-text="hoveredUnit?.status"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[9px] font-semibold text-slate-500 uppercase tracking-wider">
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Built Up Area</span>
                            <span class="text-slate-800 font-extrabold" x-text="hoveredUnit?.area"></span>
                        </div>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Expected Sale</span>
                            <span class="text-[#a38c29] font-extrabold font-mono" x-text="hoveredUnit?.price"></span>
                        </div>
                    </div>
                </div>

                <!-- Footer Summary Bar matching the design -->
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-4 p-4.5 bg-slate-50 border border-slate-150 rounded-2xl items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                    
                    <!-- Summary Icon Column -->
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                        <span class="font-extrabold text-[#0B1E36]">Summary</span>
                    </div>

                    <!-- Total Units -->
                    <div>
                        <span class="block text-[8px] text-slate-400 font-bold">Total Units</span>
                        <span class="text-slate-800 font-extrabold text-sm font-mono mt-0.5">{{ $totalUnitsCount }}</span>
                    </div>

                    <!-- Available -->
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-md bg-emerald-500 border border-emerald-600 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Available</span>
                            <span class="text-slate-800 font-extrabold text-sm font-mono mt-0.5">{{ $availableCount }}</span>
                        </div>
                    </div>

                    <!-- Booked -->
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-md bg-blue-500 border border-blue-600 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Booked</span>
                            <span class="text-slate-800 font-extrabold text-sm font-mono mt-0.5">{{ $bookedCount }}</span>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-md bg-amber-500 border border-amber-600 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Pending</span>
                            <span class="text-slate-800 font-extrabold text-sm font-mono mt-0.5">{{ $pendingCount }}</span>
                        </div>
                    </div>

                    <!-- Sold -->
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-md bg-rose-600 border border-rose-700 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Sold</span>
                            <span class="text-slate-800 font-extrabold text-sm font-mono mt-0.5">{{ $soldCount }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>












    <!-- Script to render ApexCharts line / area chart -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                    name: 'Collections',
                    type: 'column',
                    data: @json(array_values($collectionsTrend))
                }, {
                    name: 'Forecast',
                    type: 'line',
                    data: @json(array_values($forecastTrend))
                }],
                chart: {
                    type: 'line',
                    height: 220,
                    toolbar: { show: false },
                    fontFamily: 'Outfit, sans-serif'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '35%',
                        borderRadius: 2
                    }
                },
                colors: ['#DCE2E6', '#a38c29'],
                stroke: { curve: 'smooth', width: [0, 2.5] },
                markers: {
                    size: [0, 4.5],
                    colors: ['#a38c29'],
                    strokeColors: '#FAF8F2',
                    strokeWidth: 2,
                    hover: { size: 6 }
                },
                xaxis: {
                    categories: @json(array_keys($collectionsTrend)),
                    labels: { style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 600 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    min: 0,
                    max: 200000,
                    tickAmount: 4,
                    labels: {
                        formatter: function(val) {
                            return val === 0 ? '0' : (val / 1000) + 'K';
                        },
                        style: {
                            colors: '#94a3b8',
                            fontSize: '9px',
                            fontWeight: 600
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '10px',
                    fontWeight: 700,
                    labels: { colors: '#475569' },
                    markers: {
                        width: 14,
                        height: 3,
                        strokeWidth: 0,
                        radius: 2
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#collectionsChart"), options);
            chart.render();
        });
    </script>

</div>

</x-erp-layout>
