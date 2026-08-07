<x-erp-layout title="System Approval Reports" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest border-b pb-3">System Approval Reports</h3>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table id="reportsTable" class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">Requested Date</th>
                            <th class="px-5 py-3">Rule Subject Type</th>
                            <th class="px-5 py-3">Requester User</th>
                            <th class="px-5 py-3">Approver User</th>
                            <th class="px-5 py-3">Decision Status</th>
                            <th class="px-5 py-3">Reason narrative</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                        @forelse($approvalReportEntries as $app)
                        <tr class="hover:bg-slate-50/60 font-semibold">
                            <td class="px-5 py-3 text-slate-500 font-sans">{{ $app->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 font-sans font-bold text-slate-800 text-[10px] uppercase tracking-wide">{{ class_basename($app->approvable_type ?? 'Generic Approval') }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->requester?->name }}</td>
                            <td class="px-5 py-3 font-sans text-slate-700">{{ $app->approver?->name ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @php $sc = ['approved'=>'bg-emerald-50 text-emerald-700 border border-emerald-100','pending'=>'bg-amber-50 text-amber-700 border border-amber-100','rejected'=>'bg-rose-50 text-rose-700 border border-rose-100']; @endphp
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase inline-block {{ $sc[$app->status] ?? 'bg-slate-100' }}">{{ $app->status }}</span>
                            </td>
                            <td class="px-5 py-3 font-sans font-medium text-slate-500 italic">{{ $app->reason ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">No approval workflows processed.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $approvalReportEntries->appends(request()->query())->links() }}</div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
