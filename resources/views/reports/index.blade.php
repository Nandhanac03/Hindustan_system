<x-erp-layout title="Business Reports Center" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-8 text-center space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-[#a38c29] flex items-center justify-center mx-auto border border-amber-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <h3 class="text-base font-black text-slate-900 uppercase tracking-wider">Reports Navigation Hub</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 font-medium">Please select a report from the sidebar menu to view report analytics and financial ledgers.</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('reports.dashboard') }}" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-sm inline-flex items-center gap-2">
                <span>Go to Executive Dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
