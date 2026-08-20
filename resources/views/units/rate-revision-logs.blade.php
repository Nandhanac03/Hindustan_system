<x-erp-layout title="Rate Revision Logs" headerTitle="Rate Revision Engine">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="rateRevisionApp()">
    
    {{-- Breadcrumb and Title Row --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                <!-- <span>Rate Revision Engine</span>
                <span>&rsaquo;</span> -->
                <!-- <span class="text-slate-600">Rate Revision Logs</span> -->
            </div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase mt-1">Rate Revision Logs</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track, audit, and manage all rate and price changes applied to units.</p>
        </div>
    </div>

    {{-- Alert Toast Notification --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-[200] p-4 rounded-xl shadow-xl border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>    {{-- KPI Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Card 1: Total Logs -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-purple-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(168,85,247,0.2)] hover:border-r-purple-500/20 hover:border-y-purple-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Total Logs</span>
                <div class="w-6 h-6 rounded-md bg-purple-50 text-purple-600 border border-purple-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900">
                {{ $totalRevisions }}
            </div>
            <div class="text-[10px] font-medium text-slate-400">Total rate history records</div>
        </div>

        <!-- Card 2: Active Units -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Active Units</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900">
                {{ $activeUnits }}
            </div>
            <div class="text-[10px] font-medium text-slate-400">Units with rate logs</div>
        </div>

        <!-- Card 3: Last Update -->
        <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.2)] hover:border-r-amber-500/20 hover:border-y-amber-500/20 cursor-default group">
            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                <span>Last Update</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-base font-black font-mono text-slate-900">
                {{ $lastRevisionDate ? \Carbon\Carbon::parse($lastRevisionDate)->format('d M Y') : 'Never' }}
            </div>
            <div class="text-[10px] font-medium text-slate-400">Most recent update date</div>
        </div>

    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
        <form method="GET" action="{{ route('rate-revision.index') }}" class="flex flex-wrap items-end gap-4 text-xs font-semibold">
            
            <div class="flex-1 min-w-[200px] space-y-1">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type</label>
                <select name="unit_type_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all cursor-pointer">
                    <option value="">All Types</option>
                    @foreach($unitTypes as $ut)
                        <option value="{{ $ut->id }}" {{ request('unit_type_id') == $ut->id ? 'selected' : '' }}>{{ $ut->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px] space-y-1">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Floor</label>
                <select name="floor_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none transition-all cursor-pointer">
                    <option value="">All Floors</option>
                    @foreach($floors as $fl)
                        <option value="{{ $fl->id }}" {{ request('floor_id') == $fl->id ? 'selected' : '' }}>
                            {{ $fl->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <a href="{{ route('rate-revision.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </a>
            </div>

        </form>
    </div>

    {{-- Revisions Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b border-[#8a7522] font-bold uppercase tracking-wider text-[10px] text-center">
                        <th class="px-5 py-3.5 font-black text-left">PROJECT / UNIT</th>
                        <th class="px-5 py-3.5 font-black text-right">OLD RATE / SQFT</th>
                        <th class="px-5 py-3.5 font-black text-right">NEW RATE / SQFT</th>
                        <th class="px-5 py-3.5 font-black text-right">RATE CHANGE</th>
                        <th class="px-5 py-3.5 font-black">EFFECTIVE DATE</th>
                        <th class="px-5 py-3.5 font-black text-left font-sans">CHANGED BY</th>
                        <th class="px-5 py-3.5 font-black text-left">REASON</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 bg-white text-center">
                    @forelse($logs as $log)
                        @php
                            $rateDiff = (float)$log->rate - (float)$log->previous_rate;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-left font-sans">
                                <div class="font-extrabold text-slate-800 truncate max-w-[300px]" title="{{ $log->unit?->project?->name }}">{{ $log->unit?->project?->name }}</div>
                                <div class="text-[10px] text-slate-500 font-semibold mt-0.5">
                                    {{ $log->unit?->door_no }} | Floor: {{ $log->unit?->floor?->name ?? '—' }} | Type: {{ $log->unit?->unitType?->name ?? '—' }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-slate-500">
                                @if($log->previous_rate > 0)
                                    ₹{{ number_format($log->previous_rate, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-slate-850">
                                ₹{{ number_format($log->rate, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold">
                                @if($rateDiff > 0)
                                    <span class="text-emerald-600">+₹{{ number_format($rateDiff, 2) }}</span>
                                @elseif($rateDiff < 0)
                                    <span class="text-rose-600">-₹{{ number_format(abs($rateDiff), 2) }}</span>
                                @else
                                    <span class="text-slate-400">₹0.00</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-505 font-sans">
                                {{ $log->effective_from ? \Carbon\Carbon::parse($log->effective_from)->format('d M Y') : '—' }}
                            </td>
                            <td class="px-5 py-4 text-left font-sans">
                                <div class="font-bold text-slate-800">{{ $log->user?->name ?? 'System' }}</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">
                                    {{ $log->user?->role ?? 'User' }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-left font-sans text-slate-500 max-w-[200px] truncate" title="{{ $log->reason ?? '—' }}">
                                {{ $log->reason ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No rate logs found for the current filter parameters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination footer --}}
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-150 flex items-center justify-between bg-slate-50/50">
                <div class="text-xs text-slate-500 font-semibold">
                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} Revisions
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL: Create New Rate Revision --}}
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-[150] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @keydown.escape.window="addModalOpen = false">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-150 transform transition-all" @click.away="addModalOpen = false">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-gradient-to-r from-black via-slate-900 to-black text-white flex justify-between items-center border-b border-[#a38c29]/20">
                <div>
                    <span class="inline-block px-2 py-0.5 bg-[#a38c29]/20 text-[#a38c29] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/30 mb-1">Rate Revision Engine</span>
                    <h3 class="font-extrabold text-sm uppercase tracking-wider text-white">Record Rate Update</h3>
                </div>
                <button type="button" @click="addModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            {{-- Modal Form --}}
            <form @submit.prevent="submitAddForm" class="p-6 space-y-4 text-xs font-semibold">
                
                {{-- Project Dropdown --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Select Project <span class="text-rose-500">*</span></label>
                    <select x-model="addForm.project_id" @change="onProjectChange" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] rounded-xl outline-none transition-all cursor-pointer">
                        <option value="">Choose Project...</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-rose-500 font-bold block" x-show="errors.project_id" x-text="errors.project_id[0]"></span>
                </div>

                {{-- Unit Dropdown --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider flex justify-between">
                        <span>Select Unit / Property <span class="text-rose-500">*</span></span>
                        <span x-show="loadingUnits" class="text-[9px] text-[#a38c29] animate-pulse lowercase font-bold">Loading units...</span>
                    </label>
                    <select x-model="addForm.unit_id" @change="onUnitChange" :disabled="!addForm.project_id || loadingUnits" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] rounded-xl outline-none transition-all cursor-pointer disabled:opacity-50">
                        <option value="">Choose Unit...</option>
                        <template x-for="u in projectUnits" :key="u.id">
                            <option :value="u.id" x-text="`${u.door_no} | ${u.unit_type?.name || 'Unit'} | Current Rate: ₹${Number(u.expected_rate_per_sqft || 0).toLocaleString('en-IN')}`"></option>
                        </template>
                    </select>
                    <span class="text-[10px] text-rose-500 font-bold block" x-show="errors.unit_id" x-text="errors.unit_id[0]"></span>
                </div>

                {{-- Unit Info Panel (Reactive) --}}
                <div x-show="selectedUnitDetails" class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl space-y-2" style="display: none;">
                    <div class="grid grid-cols-2 gap-2 text-[10px] text-slate-500 font-bold">
                        <div>BUILT-UP AREA: <span class="text-slate-800" x-text="`${selectedUnitDetails.built_up_area || 0} sqft`"></span></div>
                        <div>CURRENT RATE/SQFT: <span class="text-slate-800" x-text="`₹${Number(selectedUnitDetails.expected_rate_per_sqft || 0).toLocaleString('en-IN')}`"></span></div>
                        <div>CARPET AREA: <span class="text-slate-800" x-text="`${selectedUnitDetails.carpet_area || 0} sqft`"></span></div>
                        <div>CURRENT VALUE: <span class="text-slate-800" x-text="`₹${Number(selectedUnitDetails.expected_sale_amount || 0).toLocaleString('en-IN')}`"></span></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- New Rate Input --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                            <span x-text="isParkingUnit ? 'New Total Rate (₹)' : 'New Rate / SQFT (₹)'"></span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" x-model="addForm.rate" placeholder="Enter new rate..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] rounded-xl outline-none transition-all">
                        <span class="text-[10px] text-rose-500 font-bold block" x-show="errors.rate" x-text="errors.rate[0]"></span>
                    </div>

                    {{-- Effective Date --}}
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Effective From <span class="text-rose-500">*</span></label>
                        <input type="date" x-model="addForm.effective_from" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] rounded-xl outline-none transition-all cursor-pointer">
                        <span class="text-[10px] text-rose-500 font-bold block" x-show="errors.effective_from" x-text="errors.effective_from[0]"></span>
                    </div>
                </div>

                {{-- Reason --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Update Reason / Remarks <span class="text-rose-500">*</span></label>
                    <textarea x-model="addForm.reason" rows="3" placeholder="Explain the rationale for this rate change..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] rounded-xl outline-none transition-all font-medium"></textarea>
                    <span class="text-[10px] text-rose-500 font-bold block" x-show="errors.reason" x-text="errors.reason[0]"></span>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-[10px] font-black uppercase rounded-xl transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-black uppercase rounded-xl transition shadow-md shadow-[#a38c29]/20 cursor-pointer">Save Rate Log</button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function rateRevisionApp() {
    return {
        addModalOpen: false,
        loadingUnits: false,
        projectUnits: [],
        selectedUnitDetails: null,
        isParkingUnit: false,
        errors: {},
        addForm: {
            project_id: '',
            unit_id: '',
            effective_from: '',
            rate: '',
            reason: ''
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 4000);
        },
        openAddModal() {
            this.errors = {};
            this.addForm = {
                project_id: '{{ request('project_id') ?? ($projects->first()->id ?? '') }}',
                unit_id: '',
                effective_from: new Date().toISOString().split('T')[0],
                rate: '',
                reason: ''
            };
            this.projectUnits = [];
            this.selectedUnitDetails = null;
            this.isParkingUnit = false;
            this.addModalOpen = true;
            if (this.addForm.project_id) {
                this.loadUnits(this.addForm.project_id);
            }
        },
        onProjectChange() {
            this.projectUnits = [];
            this.addForm.unit_id = '';
            this.selectedUnitDetails = null;
            this.isParkingUnit = false;
            if (this.addForm.project_id) {
                this.loadUnits(this.addForm.project_id);
            }
        },
        loadUnits(projectId) {
            this.loadingUnits = true;
            fetch(`{{ url('/units') }}?project_id=${projectId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.projectUnits = data.units || [];
            })
            .catch(err => {
                console.error(err);
                this.showToast('Failed to load project units.', 'error');
            })
            .finally(() => {
                this.loadingUnits = false;
            });
        },
        onUnitChange() {
            this.selectedUnitDetails = null;
            this.isParkingUnit = false;
            if (this.addForm.unit_id) {
                const unit = this.projectUnits.find(u => u.id == this.addForm.unit_id);
                if (unit) {
                    this.selectedUnitDetails = unit;
                    this.addForm.rate = unit.expected_rate_per_sqft || '';
                    const typeName = String(unit.unit_type?.name || '').toLowerCase();
                    const catName = String(unit.unit_type?.category || '').toLowerCase();
                    if (typeName === 'parking' || catName === 'parking') {
                        this.isParkingUnit = true;
                        this.addForm.rate = unit.expected_sale_amount || '';
                    }
                }
            }
        },
        submitAddForm() {
            this.errors = {};
            // Set revision type as default for backwards compatibility
            const payload = {
                ...this.addForm,
                revision_type: 'Base Price Adjustment'
            };
            fetch('{{ route('rate-revision.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    if (data.errors) {
                        this.errors = data.errors;
                    }
                    this.showToast(data.message || 'Validation error. Please verify input data.', 'error');
                } else {
                    this.showToast('Unit rate revised and logged successfully.');
                    this.addModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1200);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        }
    };
}
</script>

</x-erp-layout>
