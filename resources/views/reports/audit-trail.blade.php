<x-erp-layout title="System Audit Trail logs" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Audit Trail logs</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Timestamp</th>
                            <th class="px-5 py-3">User Executed</th>
                            <th class="px-5 py-3">Action Module</th>
                            <th class="px-5 py-3">Ref ID</th>
                            <th class="px-5 py-3">Narrative Details</th>
                            <th class="px-5 py-3">Network IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($auditTrailEntries as $log)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800">{{ $log->user?->name ?? 'System Process' }}</td>
                            <td class="px-5 py-3 font-sans text-indigo-700 uppercase tracking-wide text-[10px]">{{ $log->action }}</td>
                            <td class="px-5 py-3 font-bold text-slate-500">{{ $log->subject_id ?? '—' }}</td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-600">{{ $log->description }}</td>
                            <td class="px-5 py-3 text-slate-400 font-mono text-[10px]">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No activity logs recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $auditTrailEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
