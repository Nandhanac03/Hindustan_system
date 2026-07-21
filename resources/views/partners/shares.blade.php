<x-erp-layout title="Configure Shares" headerTitle="Configure Partner Shares">

<div class="max-w-2xl mx-auto bg-white rounded-2xl/80 shadow-sm p-6 space-y-6" x-data="shareFormApp()">
    
    {{-- Header --}}
    <div class="border-b border-slate-100 pb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('partners.index') }}" class="p-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg text-slate-500 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-900 tracking-tight uppercase">Configure Shares - {{ $project->name }}</h1>
                <p class="text-[11px] text-slate-500 mt-0.5">Assign collection share percentages to partners for this project. Total must not exceed 100%.</p>
            </div>
        </div>
    </div>

    {{-- Error messages --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('partners.shares.update', $project->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="space-y-4">
            @forelse($partners as $partner)
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
                               value="{{ old('shares.' . $partner->id, $existingShares->has($partner->id) ? $existingShares->get($partner->id)->share_pct : '0') }}"
                               x-on:input="calculateTotal()"
                               class="share-input w-full px-3 py-2 bg-white border border-slate-250 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 text-right focus:outline-none transition-all">
                        <span class="text-xs font-bold text-slate-500">%</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-450 italic">No partners registered yet. Please register partners first.</div>
            @endforelse
        </div>

        {{-- Calculations Summary --}}
        <div class="p-4 bg-slate-50 rounded-xl flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600 uppercase">Total Allocated Shares</span>
            <div class="flex items-center gap-1.5">
                <span class="text-sm font-bold font-mono" 
                      x-text="total.toFixed(2)"
                      x-bind:class="total > 100 ? 'text-rose-600' : (total === 100 ? 'text-emerald-700' : 'text-[#a38c29]')">0.00</span>
                <span class="text-xs font-bold text-slate-500">% / 100.00%</span>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
            <a href="{{ route('partners.index') }}" 
               class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                Cancel
            </a>
            <button type="submit" 
                    x-bind:disabled="total > 100"
                    x-bind:class="total > 100 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="px-4 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                Save Share Percentages
            </button>
        </div>
    </form>
</div>

<script>
function shareFormApp() {
    return {
        total: 0,
        init() {
            this.calculateTotal();
        },
        calculateTotal() {
            let sum = 0;
            document.querySelectorAll('.share-input').forEach(input => {
                sum += parseFloat(input.value) || 0;
            });
            this.total = sum;
        }
    };
}
</script>

</x-erp-layout>
