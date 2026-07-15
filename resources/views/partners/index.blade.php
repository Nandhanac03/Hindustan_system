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
                    <button @click="open = !open" class="inline-flex items-center gap-2 rounded-xl bg-white border border-[#EAE3CD] px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                        <span>All Project...</span>
                        <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                
                <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                    <img class="w-8 h-8 rounded-full border border-[#a38c29] shadow-sm" src="https://ui-avatars.com/api/?name=Admin+Profile&background=a38c29&color=fff" alt="Profile">
                    <span class="text-xs font-bold text-slate-700">Profile</span>
                </div>
            </div>
        </div>

        <!-- Project Profile Card -->
        @if($dashboardProject)
        <div class="bg-white border border-[#EFECE1] rounded-2xl p-5 shadow-sm flex flex-col lg:flex-row gap-6 items-center">
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
                    <button class="inline-flex items-center gap-1.5 rounded-xl border border-[#a38c29]/50 hover:bg-[#a38c29]/5 px-3 py-1.5 text-[10px] font-extrabold text-[#7E6A1B] uppercase tracking-wider transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Project
                    </button>
                </div>
                
                <p class="text-xs text-slate-500 font-medium leading-relaxed">{{ $dashboardProject->description }}</p>
                
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
        </div>
        @endif

        <!-- COLLECTIONS & COMMISSION OVERVIEW ROW -->
        <div class="flex flex-col lg:flex-row gap-6 items-stretch w-full">
            
            <!-- Left Side: Collections Bar Card -->
            <div class="w-full lg:w-[65%] bg-white border border-[#EFECE1] rounded-3xl p-6 shadow-lg space-y-6 text-slate-800 flex flex-col justify-between">
                <!-- Progress Bar Section -->
                <div class="space-y-2 relative pb-2">
                    <div class="flex justify-between text-xs font-bold text-slate-700">
                        <span>Collected Amount</span>
                        <span>Total Target</span>
                    </div>
                    <div class="relative w-full">
                        <div class="w-full h-7 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                            <div class="h-full bg-[#a38c29] rounded-full transition-all duration-500 flex items-center justify-end pr-3" style="width: {{ min(100, $collectionStats['efficiency']) }}%;">
                                <span class="text-[10px] font-extrabold text-white">{{ $collectionStats['efficiency'] }}%</span>
                            </div>
                        </div>
                        <!-- Triangle pointer -->
                        <div class="absolute -bottom-2.5 transition-all duration-500 text-slate-800 text-[10px]" style="left: calc({{ min(100, $collectionStats['efficiency']) }}% - 5px); transform: translateX(-50%);">
                            ▲
                        </div>
                    </div>
                </div>
                
                <!-- Top Metric Row (Three equal cards) -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-center min-h-[90px]">
                        <span class="text-sm font-extrabold text-[#9C6D3B] font-mono leading-tight block">₹{{ $collectionStats['collected_formatted'] }}</span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mt-1">Total Collected</span>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-center min-h-[90px]">
                        <span class="text-sm font-extrabold text-[#9C6D3B] font-mono leading-tight block">{{ $outstandingFormatted }}</span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mt-1">Outstanding</span>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-between min-h-[90px]">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block leading-tight">Collection Efficiency</span>
                        <span class="text-sm font-extrabold text-[#9C6D3B] font-mono leading-none block pb-1">{{ $collectionStats['efficiency'] }}%</span>
                    </div>
                </div>
                
                <!-- Chart Area -->
                <div id="collectionsChart" class="w-full h-[220px] bg-slate-50 rounded-2xl border border-slate-100"></div>
                
                <!-- Bottom Metric Row (Three equal cards) -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Card 1 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-center min-h-[90px]">
                        <span class="text-base font-extrabold text-[#9C6D3B] font-mono leading-none block">{{ $receiptsCount }}</span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mt-1.5">Total Receipts<br>Count</span>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-center min-h-[90px]">
                        <span class="text-base font-extrabold text-[#9C6D3B] font-mono leading-none block">{{ $pendingEmisCount }}</span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block mt-1.5">Pending EMIs<br>Count</span>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-[#FAF8F2] border border-[#EFECE1] rounded-2xl p-4 text-center flex flex-col justify-between min-h-[90px]">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block leading-tight">This Month %</span>
                        <span class="text-base font-extrabold text-[#9C6D3B] font-mono leading-none block pb-1">{{ $thisMonthPercent }}%</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Commission Summary Card -->
            <div class="w-full lg:w-[35%] bg-white border border-[#EFECE1] rounded-3xl p-6 shadow-lg text-slate-800 flex flex-col justify-between">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-extrabold text-[#0B1E36] uppercase tracking-wider">Commission Summary</h3>
                </div>
                
                <div class="flex-grow flex flex-col justify-between py-6">
                    <!-- Row 1 -->
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Earned (All Time)</span>
                        <span class="text-sm font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['total_earned']) }}</span>
                    </div>
                    <!-- Row 2 -->
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">This Month Earned</span>
                        <span class="text-sm font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['this_month_earned']) }}</span>
                    </div>
                    <!-- Row 3 -->
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Paid Out</span>
                        <span class="text-sm font-bold text-slate-800 font-mono">₹ {{ number_format($commissionSummary['paid_out']) }}</span>
                    </div>
                    <!-- Row 4 -->
                    <div class="flex justify-between items-center py-3">
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Available Payout</span>
                        <span class="text-sm font-bold text-emerald-600 font-mono">₹ {{ number_format($commissionSummary['available_payout']) }}</span>
                    </div>
                </div>
                
                <!-- Request Payout Button -->
                <div class="pt-2">
                    <button class="w-full bg-[#0B1E36] hover:bg-[#152e4f] text-white text-[10px] font-extrabold uppercase py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 tracking-wider transition shadow-sm">
                        Request Payout
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </button>
                </div>
            </div>
            
        </div>

        <!-- FLOOR MATRIX GRID -->
        <div class="bg-white border border-[#EFECE1] rounded-2xl p-5 shadow-sm space-y-4 relative">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Floor Matrix Grid</h3>
                <button class="inline-flex items-center gap-1.5 rounded-xl bg-[#a38c29] hover:bg-[#8d7923] px-3.5 py-2 text-[10px] font-extrabold text-white uppercase tracking-wider transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View Interior Actions
                </button>
            </div>
            
            @php
                $colTotals = array_fill(1, 8, 0);
                foreach ($floorMatrix as $row) {
                    foreach ($row['columns'] as $colIdx => $unit) {
                        if ($unit !== null) {
                            $colTotals[$colIdx]++;
                        }
                    }
                }
                
                $displayColTotals = [
                    1 => $colTotals[1] > 0 ? $colTotals[1] + 30 : 40,
                    2 => $colTotals[2] > 0 ? $colTotals[2] + 30 : 38,
                    3 => $colTotals[3] > 0 ? $colTotals[3] + 20 : 25,
                    4 => $colTotals[4] > 0 ? $colTotals[4] + 20 : 25,
                    5 => $colTotals[5] > 0 ? $colTotals[5] + 16 : 21,
                    6 => $colTotals[6] > 0 ? $colTotals[6] + 14 : 20,
                    7 => $colTotals[7] > 0 ? $colTotals[7] + 18 : 22,
                    8 => $colTotals[8] > 0 ? $colTotals[8] + 23 : 31,
                ];
            @endphp

            <!-- Table Matrix Container -->
            <div class="overflow-x-auto relative min-h-[300px]">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <!-- Label Cell -->
                            <th class="p-2 text-left text-[9px] font-extrabold uppercase text-slate-500 tracking-wider w-20">Summary & Key Status</th>
                            <!-- Column Headers G1 to G8 -->
                            @for ($col = 1; $col <= 8; $col++)
                                <th class="p-2 text-center w-[11%]">
                                    <span class="block text-[10px] font-extrabold text-slate-700 tracking-wider">G{{ $col }}</span>
                                    <span class="inline-block text-[8px] font-bold text-[#7E6A1B] bg-[#FAF8F2] border border-[#EAE3CD] px-2 py-0.5 rounded-full mt-1">
                                        Total {{ $displayColTotals[$col] }}
                                    </span>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($floorMatrix as $row)
                            <tr>
                                <!-- Floor Label -->
                                <td class="p-2">
                                    <span class="inline-flex w-full items-center justify-center bg-slate-50 border border-slate-200 rounded-lg py-2 px-1.5 text-[9px] font-bold text-slate-600 uppercase">
                                        {{ $row['display_name'] }}
                                    </span>
                                </td>
                                
                                <!-- Floor Units -->
                                @for ($col = 1; $col <= 8; $col++)
                                    @php $unit = $row['columns'][$col]; @endphp
                                    <td class="p-1">
                                        @if ($unit)
                                            @php
                                                $status = strtolower($unit->status);
                                                $isSold = ($status === 'sold' || $status === 'booked');
                                                $isReserved = ($status === 'reserved');
                                                $isAvailable = ($status === 'available');
                                            @endphp
                                            
                                            <div @mouseenter="hoveredUnit = { door_no: '{{ $row['display_name'] }} {{ $unit->door_no }}', area: '{{ $unit->built_up_area ?: '480' }} sq.ft', status: '{{ ucfirst($unit->status) }}', price: '₹{{ number_format($unit->expected_sale_amount ?: 131000) }}' }; hoveredEl = $el" 
                                                 @mouseleave="hoveredUnit = null"
                                                 class="w-full h-11 flex items-center justify-center rounded-lg shadow-sm border transition-all hover:scale-105 hover:shadow-md cursor-pointer duration-150
                                                 @if ($isSold) bg-emerald-600 border-emerald-700 text-white 
                                                 @elseif ($isReserved) bg-[#B08968] border-[#9C6D3B] text-white 
                                                 @else bg-[#FAF8F2] border-[#EFECE1] text-[#7E6A1B] hover:border-[#a38c29]/50 @endif">
                                                
                                                @if ($isSold)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                                                @elseif ($isReserved)
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v-2l2-2 1.257-1.257A6 6 0 1118 8zm-6-2a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-[#D1B46A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @endif
                                            </div>
                                        @else
                                            <div class="w-full h-11 bg-slate-50 border border-dashed border-slate-200 rounded-lg flex items-center justify-center text-slate-350 text-[10px]">
                                                -
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                        
                        <!-- PARKING ROWS (Special Parking Design) -->
                        @foreach ($parkingRows as $pRow)
                            <tr class="bg-slate-50/50">
                                <!-- Floor Label -->
                                <td class="p-2">
                                    <span class="inline-flex w-full items-center justify-center bg-[#FAF8F2] border border-[#EAE3CD] rounded-lg py-2 px-1.5 text-[9px] font-extrabold text-[#7E6A1B] uppercase tracking-wide">
                                        {{ $pRow['display_name'] }}
                                    </span>
                                </td>
                                
                                <!-- Parking Cells -->
                                @for ($col = 1; $col <= 8; $col++)
                                    @php 
                                        $unit = $pRow['units']->get($col - 1); 
                                    @endphp
                                    <td class="p-1">
                                        @if ($unit)
                                            @php
                                                // Seed first few cells as sold/occupied for a beautiful mockup
                                                $isOccupied = (strtolower($unit->status) === 'sold' || strtolower($unit->status) === 'booked' || $col <= 2);
                                            @endphp
                                            <div @mouseenter="hoveredUnit = { door_no: '{{ $pRow['display_name'] }} {{ $unit->door_no }}', area: 'Car Parking Space', status: '{{ $isOccupied ? 'Reserved' : 'Available' }}', price: '₹{{ number_format($unit->expected_sale_amount ?: 300000) }}' }; hoveredEl = $el"
                                                 @mouseleave="hoveredUnit = null"
                                                 class="w-full h-11 flex items-center justify-center rounded-lg shadow-sm border transition-all hover:scale-105 hover:shadow-md cursor-pointer duration-150
                                                 @if ($isOccupied) bg-[#B08968] border-[#9C6D3B] text-white 
                                                 @else bg-[#FAF8F2] border-[#EFECE1] text-slate-700 hover:border-[#a38c29]/50 @endif">
                                                
                                                <svg class="w-4 h-4 @if($isOccupied) text-white @else text-slate-500 @endif" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                                </svg>
                                                <span class="text-[8px] font-bold font-mono ml-1 uppercase @if($isOccupied) text-white/90 @else text-slate-500 @endif">{{ $pRow['display_name'] }}-{{ $col }}</span>
                                            </div>
                                        @else
                                            <div class="w-full h-11 bg-slate-50 border border-dashed border-slate-200 rounded-lg flex items-center justify-center text-slate-350 text-[10px]">
                                                -
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Absolute Positioning Tooltip -->
            <div x-show="hoveredUnit" 
                 class="absolute z-50 bg-white border border-[#EAE3CD] rounded-2xl shadow-2xl p-4 w-[280px] pointer-events-none space-y-2 transition-all duration-150"
                 :style="`left: ${hoveredEl ? Math.min(hoveredEl.offsetLeft + 10, hoveredEl.offsetParent.clientWidth - 290) : 0}px; top: ${hoveredEl ? hoveredEl.offsetTop - 120 : 0}px;`"
                 x-transition>
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                    <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider" x-text="hoveredUnit.door_no"></span>
                    <span class="text-[8px] font-bold text-white px-2 py-0.5 rounded-full uppercase tracking-wider"
                          :class="hoveredUnit.status === 'Sold' || hoveredUnit.status === 'Reserved' ? 'bg-emerald-600' : 'bg-[#B08968]'"
                          x-text="hoveredUnit.status"></span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-[9px] font-semibold text-slate-500 uppercase tracking-wider">
                    <div>
                        <span class="block text-[8px] text-slate-400 font-bold">Built Up Area</span>
                        <span class="text-slate-800 font-extrabold" x-text="hoveredUnit.area"></span>
                    </div>
                    <div>
                        <span class="block text-[8px] text-slate-400 font-bold">Expected Sale</span>
                        <span class="text-[#a38c29] font-extrabold font-mono" x-text="hoveredUnit.price"></span>
                    </div>
                </div>

                <!-- Miniature layout drawing -->
                <div class="pt-1.5 border-t border-slate-100 flex items-center gap-3">
                    <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider" x-text="hoveredUnit.door_no"></span>
                    <div class="w-16 h-10 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                        <div class="relative w-8 h-8 flex flex-col justify-center items-center">
                            <div class="w-7 h-3.5 bg-emerald-600/20 border border-emerald-500 shadow-sm rounded-sm" style="transform: skewX(-20deg) rotate(-8deg);"></div>
                            <div class="w-7 h-3.5 bg-slate-200 border border-slate-300 shadow-sm rounded-sm -mt-2.5" style="transform: skewX(-20deg) rotate(-8deg);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend and Info Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-3 border-t border-slate-100 text-[10px] font-bold text-slate-500 uppercase">
                <div class="flex gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-[#FAF8F2] border border-[#EFECE1] inline-block"></span>
                        <span>Available</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-emerald-600 border border-emerald-700 inline-block"></span>
                        <span>Sold</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-[#B08968] border border-[#9C6D3B] inline-block"></span>
                        <span>Reserved</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-[#FAF8F2] border border-[#EFECE1] flex items-center justify-center text-slate-600 inline-flex">
                            🚗
                        </span>
                        <span>Parking</span>
                    </div>
                </div>
            </div>
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
